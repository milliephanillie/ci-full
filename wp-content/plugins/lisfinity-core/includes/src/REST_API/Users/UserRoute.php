<?php


namespace Lisfinity\REST_API\Users;


use Lisfinity\Abstracts\Route;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\ProductModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;

class UserRoute extends Route {
	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'user'        => [
			'path'                => '/user',
			'callback'            => 'get_user',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'user_action' => [
			'rest_path'           => '/user',
			'path'                => '/user/(?P<action>\S+)',
			'callback'            => 'user_action',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	/**
	 * Get current user
	 * ----------------
	 *
	 * @return bool|object|\stdClass
	 */
	public function get_user() {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		$user               = wp_get_current_user();
		$user->bookmarks    = carbon_get_user_meta( $user->ID, 'bookmarks' );
		$user->ip           = lisfinity_get_ip_address();
		$display_name       = ! empty( $user->first_name ) && ! empty( $user->last_name ) ? "$user->first_name $user->last_name" : $user->display_name;
		$user->display_name = $display_name;
		unset( $user->user_pass );

		return $user->data;
	}

	/**
	 * Do a defined action for a current user
	 * --------------------------------------
	 *
	 * @param \WP_REST_Request $request_data
	 *
	 * @return array|bool
	 */
	public function user_action( \WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['action'] ) ) {
			return false;
		}

		if ( $data['action'] === 'bookmark' ) {
			$result = $this->manage_bookmarks( $data['product_id'] );
		}

		if ( $data['action'] === 'unblock' ) {
			$result = $this->unblock_user( $data['user_id'], $data['user_to_unblock'] );
		}

		if ( $data['action'] === 'subscribe' ) {
			$result = $this->subscribe_user( get_current_user_id(), $data['subscriptions'] );
		}

		if ( $data['action'] === 'save_search' ) {
			$result = $this->save_search( get_current_user_id(), $data['data'] );
		}

		if ( $data['action'] === 'save_search_email' ) {
			$result = $this->save_search_email( get_current_user_id(), $data['subs'] );
		}

		if ( $data['action'] === 'delete_saved_search' ) {
			$result = $this->delete_saved_search( get_current_user_id(), $data['hash'] );
		}

		return $result;
	}

	/**
	 * Save user search to the database
	 * --------------------------------
	 *
	 * @param $user_id
	 * @param $data
	 *
	 * @return int[]|mixed|\WP_Post[]
	 */
	protected function save_search( $user_id, $data ) {
		$to_hash = json_encode( $data ) . apply_filters( 'wpml_current_language', '' );
		$hash    = 'saved-search|' . md5( $to_hash );

		if ( ! ( $result = get_user_meta( $user_id, $hash, true ) ) ) {
			$result = $this->get_searched_query( $data );
			update_user_meta( $user_id, $hash, $result );
			update_user_meta( $user_id, 'saved-search--args|' . md5( $to_hash ), $data );
			update_user_meta( $user_id, 'saved-search-email|' . md5( $to_hash ), 'yes' );
		}

		return $result;
	}

	/**
	 * Change the email option for the saved searches
	 * ----------------------------------------------
	 *
	 * @param $user_id
	 * @param $subs
	 *
	 * @return bool
	 */
	protected function save_search_email( $user_id, $subs ) {

		if ( ! empty( $subs ) && is_array( $subs ) ) {
			foreach ( $subs as $sub ) {
				if ( ! empty( $sub['email'] ) ) {
					update_user_meta( $user_id, 'saved-search-email|' . $sub['hash'], $sub['email'] );
				}
			}
		}

		return true;
	}

	/**
	 * Delete saved search from the database
	 * -------------------------------------
	 *
	 * @param $user_id
	 * @param $hash
	 *
	 * @return bool
	 */
	protected function delete_saved_search( $user_id, $hash ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE user_id = %d AND meta_key LIKE %s", $user_id, '%' . $hash ) );

		return true;
	}

	/**
	 * Subscribe user to receive emails from the site
	 *
	 * @param $user_id
	 * @param $subscriptions
	 *
	 * @return array
	 */
	protected function subscribe_user( $user_id, $subscriptions ) {
		$result = [];

		if ( ! empty( $subscriptions ) ) {
			foreach ( $subscriptions as $name => $value ) {
				if ( $value ) {
					update_user_meta( $user_id, "_email_subscription|{$name}", 'yes' );
					$result['subscriptions'][ $name ] = true;
				} else {
					delete_user_meta( $user_id, "_email_subscription|{$name}" );
					$result['subscriptions'][ $name ] = false;
				}
			}
		}

		return $result;
	}

	protected function unblock_user( $user_id, $user_to_unblock ) {
		$result              = [];
		$business            = lisfinity_get_premium_profile_id( $user_id );
		$business_to_unblock = lisfinity_get_premium_profile_id( $user_to_unblock );
		$blocked_users       = carbon_get_post_meta( $business, 'blocked-profiles' );
		$to_unblock          = array_search( $business_to_unblock, array_column( $blocked_users, 'id' ) );
		unset( $blocked_users[ $to_unblock ] );
		carbon_set_post_meta( $business, 'blocked-profiles', $blocked_users );

		$result['success'] = true;
		$result['data']    = $blocked_users;

		return $result;
	}

	/**
	 * Manage current user bookmarked products
	 * ---------------------------------------
	 *
	 * @param $product_id
	 *
	 * @return array
	 */
	//todo should be separate table for storing bookmarks in the future.
	private function manage_bookmarks( $product_id ) {
		$result = [];
		if ( empty( $product_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The product id has not been set.', 'lisfinity-core' );
		}

		$user_id = get_current_user_id();

		// store bookmark to a user.
		$bookmarks      = carbon_get_user_meta( $user_id, 'bookmarks' );
		$bookmarked_ids = array_column( $bookmarks, 'id' );
		if ( ! in_array( $product_id, $bookmarked_ids ) ) {
			// bookmark product.
			$bookmark_data = [
				'id'      => $product_id,
				'subtype' => 'product',
				'type'    => 'post',
				'value'   => "post:product:{$product_id}"
			];
			$bookmarks[]   = $bookmark_data;

			// store bookmark notification.
			$notification_model = new NotificationModel();
			$is_bookmarked      = $notification_model->where( [
				[ 'user_id', $user_id ],
				[ 'parent_id', $product_id ],
				[ 'parent_type', 4 ],
			] )->get( '1', '', 'id' );
			if ( empty( $is_bookmarked ) ) {
				$business = carbon_get_post_meta( $product_id, 'product-business' );
				// todo integrate emails and notifications here!.
				$business_profile  = lisfinity_get_premium_profile_id( $user_id );
				$notification_data = [
					'user_id'     => $user_id,
					'type'        => 1,
					'product_id'  => $product_id,
					'business_id' => $business,
					'parent_id'   => $business_profile,
					'parent_type' => 4,
					'status'      => 0,
				];

				$notification_model->store_notification( $notification_data );
			}

			$result['success'] = true;
			$result['message'] = __( 'The product has been bookmarked.', 'lisfinity-core' );
			$result['class']   = 'fill-theme';
		} else {
			// remove from bookmarks.
			$remove_key = array_search( $product_id, array_column( $bookmarks, 'id' ) );
			unset( $bookmarks[ $remove_key ] );
			$result['error']   = true;
			$result['message'] = __( 'The product is not bookmarked anymore.', 'lisfinity-core' );
			$result['class']   = 'fill-white';
		}
		$result['bookmarks'] = $bookmarks;

		carbon_set_user_meta( $user_id, 'bookmarks', $bookmarks );

		return $result;
	}

	public function get_searched_query( $query ) {
		parse_str( $query, $data );
		$products_per_page = lisfinity_get_option( 'search-products-per-page' );
		$display_sold      = lisfinity_is_enabled( lisfinity_get_option( 'search-products-display-sold' ) );
		$statuses          = [ 'publish' ];
		if ( $display_sold ) {
			$statuses[] = 'sold';
		}

		$args = [
			'post_type'      => 'product',
			'post_status'    => $statuses,
			'posts_per_page' => - 1,
			//'posts_per_page' => $products_per_page,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
			'fields'         => 'ids',
		];

		if ( isset( $data['order'] ) && ! empty( $data['order'] ) ) {
			if ( 'price_asc' === $data['order'] || 'price_desc' === $data['order'] ) {
				$order            = explode( '_', $data['order'] );
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = strtoupper( $order[1] );
			}

			if ( 'nearby' === $data['order'] ) {
				$radius_filter = $this->filter_by_radius( $data['latitude'], $data['longitude'], lisfinity_get_option( 'search-nearby-radius' ) );
				if ( ! empty( $radius_filter ) ) {
					$args['post__in'] = $radius_filter[0];
					$args['orderby']  = 'post__in';
				}
			}
		}

		$product_model = new ProductModel();
		add_filter( 'posts_join', [ $product_model, 'products_join_meta' ] );
		add_filter( 'posts_where', [ $product_model, 'products_where_expires' ], 10, 2 );

		if ( ! empty( $data['keyword'] ) ) {
			$args['s'] = $data['keyword'];
		}

		if ( ! empty( $data['category-type'] ) && $data['category-type'] !== 'common' ) {
			$args['meta_query'][] = [
				'key'   => 'product-category',
				'value' => $data['category-type'],
			];

		}

		if ( ! empty( $data['tax'] ) ) {
			foreach ( $data['tax'] as $taxonomy => $term ) {
				if ( ! empty( $term ) ) {
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => [ $term ],
					];
				}
			}
		}

		if ( ! empty( $data['range'] ) ) {
			foreach ( $data['range'] as $taxonomy => $term ) {
				$min              = ! empty( $term['min'] ) ? $term['min'] : 0;
				$max              = ! empty( $term['max'] ) ? $term['max'] : 9999999999;
				$args['post__in'] = $this->get_product_ids_from_taxonomy_range( $taxonomy, $min, $max );
			}
		}

		if ( ! empty( $data['meta'] ) ) {
			foreach ( $data['meta'] as $meta => $value ) {
				if ( ! empty( $term ) ) {
					$args['meta_query'][] = [
						'key'     => $meta,
						'value'   => $value,
						'compare' => '=',
					];
				}
			}
		}

		if ( ! empty( $data['mrange'] ) ) {
			foreach ( $data['mrange'] as $meta => $value ) {
				$min       = ! empty( $value['min'] ) ? $value['min'] : 0;
				$max       = ! empty( $value['max'] ) ? $value['max'] : 9999999999;
				$meta_name = 'price' === $meta ? "_price" : "_{$meta}";
				if ( ! empty( $value ) ) {
					$args['meta_query'][] = [
						'key'     => $meta_name,
						'value'   => [ $min, $max ],
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC'
					];
				}
			}
		}

		$products = get_posts( $args );

		remove_filter( 'posts_join', [ $product_model, 'products_join_meta' ] );
		remove_filter( 'posts_where', [ $product_model, 'products_where_expires' ] );

		return $products;
	}

	public function get_product_ids_from_taxonomy_range( $taxonomy, $min = 0, $max = 9999999999 ) {
		global $wpdb;
		$post_ids = [];
		$posts    = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts
		LEFT JOIN  $wpdb->term_relationships AS relationship
		ON $wpdb->posts.ID = relationship.object_id
		LEFT JOIN  $wpdb->term_taxonomy AS tax
		ON relationship.term_taxonomy_id = tax.term_id
		LEFT JOIN  $wpdb->terms AS terms
		ON tax.term_id = terms.term_id
		WHERE tax.taxonomy = '%s' AND terms.slug BETWEEN %d AND %d", $taxonomy, $min, $max ) );

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$post_ids[] = $post->ID;
			}
		} else {
			$post_ids[] = 0;
		}

		return $post_ids;
	}

	protected function filter_by_radius( $lat, $lng, $radius = 100 ) {
		global $wpdb;
		$distance = 6371;
		if ( 'mi' === lisfinity_get_option( 'search-nearby-format' ) ) {
			$distance = 3959;
		}
		//todo maybe we'll need to find out what term_taxonomy_id is our product type.
		//if we wish to include the location taxonomy or category specific
		//INNER JOIN wp_term_relationships
		//ON (wp_posts.ID = wp_term_relationships.object_id)
		//AND ( wp_term_relationships.term_taxonomy_id IN (21) )
		$results   = $wpdb->get_results(
			$wpdb->prepare( "
				SELECT ID, post_type, (%s * acos (cos ( radians( %s ) )
				* cos( radians( latitude.meta_value ) )
    			* cos( radians( longitude.meta_value ) - radians( %s) )
    			+ sin ( radians( %s ) )
    			* sin( radians( latitude.meta_value ) ) ) )
    			AS distance FROM $wpdb->posts INNER JOIN $wpdb->postmeta latitude
    			ON (ID = latitude.post_id AND latitude.meta_key = '_product-location|||0|lat' )
    			INNER JOIN $wpdb->postmeta longitude
    			ON (ID = longitude.post_id AND longitude.meta_key = '_product-location|||0|lng' )
    			HAVING distance < %s AND post_type = 'product'
    			ORDER BY distance;", $distance, $lat, $lng, $lat, $radius
			)
		);
		$post_ids  = [];
		$distances = [];
		foreach ( $results as $result ) {
			$post_ids[]  = $result->ID;
			$distances[] = $result->distance;
		}
		$post_ids = array_unique( $post_ids );

		$all = [ $post_ids, $distances ];

		return $all;
	}

}
