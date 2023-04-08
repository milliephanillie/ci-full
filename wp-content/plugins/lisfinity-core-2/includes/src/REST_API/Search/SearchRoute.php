<?php


namespace Lisfinity\REST_API\Search;

use Lisfinity\Models\ProductModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\Stats\StatModel;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class SearchRoute extends Route {

	private $keyword;

	private $search_title;
	private $search_description;
	private $search_id;
	private $search_category;
	private $search_category_types;
	private $search_authors;
	private $search_authors_type;
	private $search_ads;
	private $search_ads_type;
	private $is_premium = false;

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'search_keyword' => [
			'rest_path'           => '/search/keyword',
			'path'                => '/search/keyword/(?P<keyword>\S+)',
			'callback'            => 'get_keyword_search',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'search'         => [
			'path'                => '/search/',
			'callback'            => 'get_search',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
	];

	/**
	 * Get search request
	 * ------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function get_search( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		$products_per_page = lisfinity_get_option( 'search-products-per-page' );
		$display_sold      = lisfinity_is_enabled( lisfinity_get_option( 'search-products-display-sold' ) );
		$statuses          = [ 'publish' ];
		if ( $display_sold ) {
			$statuses[] = 'sold';
		}
		$args = [
			'post_type'      => 'product',
			'post_status'    => $statuses,
			'posts_per_page' => $products_per_page,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
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

		// do not query expired products.
		$product_model = new ProductModel();
		add_filter( 'posts_join', [ $product_model, 'products_join_meta' ] );
		add_filter( 'posts_where', [ $product_model, 'products_where_expires' ], 10, 2 );

		if ( ! empty( $data['business'] ) ) {
			$args['meta_query'][] = [
				'key'     => '_product-business',
				'value'   => $data['business'],
				'compare' => '=',
			];
		}

		// keyword meta field.
		if ( ! empty( $data['keyword'] ) ) {
			$args['s'] = $data['keyword'];
		}

		$check_for_premium = lisfinity_check_for_premium();
		$categories        = get_option( 'lisfinity_groups' );
		$category_slugs    = array_column( $categories, 'slug' );

		if ( $check_for_premium ) {
			$key = array_search( $data['category-type'], $category_slugs );
			if ( isset( $categories[ $key ]['premium'] ) && 'yes' === $categories[ $key ]['premium'] ) {
				$this->is_premium = true;
			}
		}
		if ( ! $this->is_premium ) {
			// category type meta field.
			if ( ! empty( $data['category-type'] ) && $data['category-type'] !== 'common' ) {
				$args['meta_query'][] = [
					'key'   => 'product-category',
					'value' => $data['category-type'],
				];
				//todo rework this piece of codes
				add_filter( 'posts_join', [ $this, 'products_join_promo' ] );
				add_filter( 'posts_orderby', [ $this, 'products_orderby_promo' ], 10, 2 );
			} else {
				if ( ! empty( lisfinity_get_hidden_categories() ) ) {
					$args['meta_query'][] = [
						'key'     => 'product-category',
						'value'   => lisfinity_get_hidden_categories(),
						'compare' => 'NOT IN'
					];
				}
			}
		}

		// taxonomies.
		if ( ! empty( $data['tax'] ) ) {
			foreach ( $data['tax'] as $taxonomy => $term ) {
				if ( ! empty( $term ) ) {
					if ( $check_for_premium ) {
						$taxonomy_model = new TaxonomiesAdminModel();
						$options        = $taxonomy_model->get_taxonomy_options( $taxonomy );
						if ( 'yes' === $options['premium'] ) {
							$this->is_premium = true;
						}
						$term_id = get_term_by( 'slug', $term, $taxonomy );
						if ( 'yes' === get_term_meta( $term_id->term_id, 'premium', true ) ) {
							$this->is_premium = true;
						}
					}
					if ( ! $this->is_premium ) {
						$args['tax_query'][] = [
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => [ $term ],
						];
					}
				}
			}
		}

		// taxonomy range field.
		if ( ! empty( $data['range'] ) ) {
			foreach ( $data['range'] as $taxonomy => $term ) {
				$min              = ! empty( $term['min'] ) ? $term['min'] : 0;
				$max              = ! empty( $term['max'] ) ? $term['max'] : 9999999999;
				$args['post__in'] = $this->get_product_ids_from_taxonomy_range( $taxonomy, $min, $max );
			}
		}

		// meta fields.
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

		// meta range field.
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

		// load only ids.
		if ( isset( $data['ids_only'] ) ) {
			$args['fields'] = 'ids';
		}

		// Generate hash
		$to_hash         = json_encode( $args ) . apply_filters( 'wpml_current_language', '' );
		$query_args_hash = 'lisfinity_' . md5( $to_hash ) . lisfinity_get_transient_version( 'get-ads', true );

		//delete_transient( $query_args_hash);
		if ( false === ( $result = get_transient( $query_args_hash ) ) ) {
			// offset pages if needed to recreate pagination.
			$result['page'] = ! empty( $data['offset'] ) ? $data['offset'] : 1;

			if ( ! empty( $data['offset'] ) ) {
				$offset         = ( absint( $result['page'] ) - 1 ) * $args['posts_per_page'];
				$args['offset'] = $offset;
			}


			$products = new \WP_Query( $args );

			remove_filter( 'posts_join', [ $product_model, 'products_join_meta' ] );
			remove_filter( 'posts_where', [ $product_model, 'products_where_expires' ] );
			//todo needs to be reworked
			remove_filter( 'posts_join', [ $this, 'products_join_promo' ] );
			remove_filter( 'posts_orderby', [ $this, 'products_orderby_promo' ] );

			if ( empty( $args['fields'] ) ) {
				$result['products'] = $this->prepare_products_for_display( $products->posts, isset( $data['fromPage'] ), $data['category-type'] );
			}

			$result['found_posts']   = $products->found_posts;
			$result['max_num_pages'] = $products->max_num_pages;
			$result['count']         = $products->found_posts;
			//$result['query']         = $products->query;
			set_transient( $query_args_hash, $result, DAY_IN_SECONDS * 30 );
		}

		//$result['a'] = lisfinity_show_listings_to_premium_only();
		$result['args']  = http_build_query( $data );
		$saved           = lisfinity_get_saved_searches();
		$result['saved'] = false;
		$a               = false !== strpos( $result['args'], '_wpnonce' ) ? substr( $result['args'], 20 ) : $result['args'];
		if ( ! empty( $saved ) ) {
			foreach ( $saved as $saved_search ) {
				$b = false !== strpos( $saved_search['args'], '_wpnonce' ) ? substr( $saved_search['args'], 20 ) : $saved_search['args'];
				if ( $a === $b ) {
					$result['saved'] = true;
				}
			}
		}

		$response = rest_ensure_response( $result );
		$response->header( 'X-WP-Total', (int) $result['found_posts'] );
		$response->header( 'X-WP-TotalPages', (int) $result['max_num_pages'] );
		$response->header( 'X-WP-Page', absint( $result['page'] ) );

		return $response;
	}

	public function products_join_promo( $join ) {
		global $wpdb;

		$join .= " LEFT JOIN {$wpdb->prefix}lisfinity_promotions AS promo ON $wpdb->posts.ID = promo.product_id ";

		return $join;
	}

	/**
	 * Include query parameter to load products with a running promotion
	 * -----------------------------------------------------------------
	 *
	 * @param $orderby
	 *
	 * @return string
	 */
	public function products_orderby_promo( $args ) {
		$new_args = "promo.position = 'category-featured' DESC";
		$new_args .= ", $args";

		return $new_args;
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

	/**
	 * Get post ids for the range taxonomy
	 * -----------------------------------
	 *
	 * @param $taxonomy
	 * @param int $min
	 * @param int $max
	 *
	 * @return array
	 */
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

	/**
	 * Prepare the products data to be used in a query
	 * -----------------------------------------------
	 *
	 * @param array $products
	 * @param bool $is_detailed
	 * @param string $category
	 *
	 * @return mixed
	 */
	public function prepare_products_for_display( $products, $is_detailed, $category ) {
		if ( empty( $products ) ) {
			return $products;
		}
		$model        = new StatModel();
		$promo_model  = new PromotionsModel();
		$promoted_ids = array_column( $promo_model->get_ads_with_promotion( 'category-featured' ), 'product_id' );

		foreach ( $products as $product ) {
			// stats.
			$data['type']       = 1;
			$data['user_id']    = get_post_meta( $product->ID, '_product-business', true );
			$data['author_id']  = get_post_meta( $product->ID, '_product-owner', true );
			$data['product_id'] = $product->ID;

			$ip = lisfinity_get_ip_address();
			$model->update_stat( $data );
			$wc_product      = wc_get_product( $product->ID );
			$location_format = lisfinity_get_option( 'format-location' );
			$owner           = carbon_get_post_meta( $product->ID, 'product-owner' );
			$account_type    = carbon_get_user_meta( $owner, 'account-type' );

			$product->meta                      = [];
			$product->meta['category']          = carbon_get_post_meta( $product->ID, 'product-category' );
			$product->meta['location']          = carbon_get_post_meta( $product->ID, 'product-location' );
			$product->meta['product_type']      = carbon_get_post_meta( $product->ID, 'product-type' );
			$product->meta['price_type']        = ! empty( $wc_product->get_sale_price() ) ? 'on-sale' : carbon_get_post_meta( $product->ID, 'product-price-type' );
			$product->guid                      = get_the_permalink( $product->ID );
			$product->type                      = carbon_get_post_meta( $product->ID, 'product-category' );
			$product->thumbnail                 = has_post_thumbnail( $product->ID ) ? get_the_post_thumbnail_url( $product->ID, 'full' ) : false;
			$product->thumbnail_placeholder     = has_post_thumbnail( $product->ID ) ? get_the_post_thumbnail_url( $product->ID, 'full' ) : false;
			$product->premium_profile           = lisfinity_get_premium_profile( $product->post_author );
			$product->profile_location          = carbon_get_post_meta( $product->premium_profile->ID, 'profile-location' );
			$product->location_formatted_user   = 'business' === $account_type ? lisfinity_format_location( $product->premium_profile->ID, 'full' === $location_format, false, explode( ',', $product->profile_location['address'] ) ) : carbon_get_post_meta( $product->ID, 'product-location' )['address'];
			$product->location_formatted_user   = lisfinity_format_location( $product->premium_profile->ID, 'full' === $location_format, false, explode( ',', $product->profile_location['address'] ) );
			$product->user_verified             = carbon_get_user_meta( $data['author_id'], 'verified' );
			$product->rating                    = ! empty( $product->premium_profile ) ? number_format_i18n( lisfinity_calculate_business_rating( $product->premium_profile->ID ), 1 ) : number_format_i18n( carbon_get_theme_option( 'business-reviews-default-rating' ) );
			$product->profile_image             = has_post_thumbnail( $product->premium_profile ) ? get_the_post_thumbnail_url( $product->premium_profile, 'premium-profile-image' ) : false;
			$product->profile_permalink         = get_permalink( $product->premium_profile );
			$product->profile_permalink_enabled = '1' === lisfinity_get_option( 'product-box-logo-clickable' );
			$product->on_sale                   = ! empty( $wc_product->get_sale_price() );
			$product->price_html                = ! empty( $wc_product->get_price() ) ? lisfinity_get_price_html( $wc_product->get_price() * floatval( lisfinity_get_chosen_currency_rate() ) ) : '';
//			$product->location_formatted        = 'business' === $account_type ? lisfinity_format_location( $product->ID, 'full' === $location_format ) : carbon_get_post_meta( $product->ID, 'product-location' )['address'];
			$product->permalink          = get_permalink( $product->ID );
			$product->location_formatted = lisfinity_format_location( $product->ID, 'full' === $location_format );
			$product->promoted           = lisfinity_is_promoted_product( $product->ID, 'bump-pin' );
			$product->promoted_color     = lisfinity_is_promoted_product( $product->ID, 'bump-color' );
			$product->promoted_category  = ! empty( $category ) && $category === $product->meta['category'] && in_array( $product->ID, $promoted_ids );
			$product->likes              = get_post_meta( $product->ID, 'product-likes' );
			$product->liked              = in_array( $ip, $product->likes );
			$product->account_type       = $account_type;

			$product->description = $product->post_content;

			// taxonomies setup
			$product->taxonomies = $this->format_product_taxonomies( $product->ID, $product->type );

			if ( 'auction' === $product->meta['price_type'] ) {
				$product->meta['auction_status'] = carbon_get_post_meta( $product->ID, 'product-auction-status' );
				$product->meta['auction_ends']   = carbon_get_post_meta( $product->ID, 'product-auction-ends' );
				$product_meta['sell_on_site']    = carbon_get_post_meta( $product->ID, 'product-price-sell-on-site' );
				$start_price                     = carbon_get_post_meta( $product->ID, 'product-auction-start-price' );
				$start_price                     = ! empty( $start_price ) ? $start_price : 1;
				$product->meta['start_price']    = lisfinity_get_price_html( $start_price, $wc_product );
				$price_option                    = lisfinity_get_option( 'product-start-price-default' );
				if ( 'last' === $price_option ) {
					$bids_model = new \Lisfinity\Models\Bids\BidModel();
					$last_bid   = $bids_model->where( 'product_id', $product->ID )->get( '1', 'ORDER BY id DESC', 'amount', 'col' );
					if ( ! empty( $last_bid[0] ) ) {
						$product->price_html = lisfinity_get_price_html( $last_bid[0], $wc_product );
					}
				}
				if ( 'start' === $price_option && ! empty( $start_price ) && ! empty( $last_bid[0] ) ) {
					$product->price_html = lisfinity_get_price_html( $start_price, $wc_product );
				}
			}

			// timeline.
			$listed = carbon_get_post_meta( $product->ID, 'product-listed' );
			// use default post submission date if the post meta list date is not available.
			$submitted                = ! empty( $listed ) ? $listed : strtotime( get_the_date( '', $product->ID ) );
			$expires                  = carbon_get_post_meta( $product->ID, 'product-expiration' );
			$duration                 = $expires - $submitted;
			$remaining                = $expires - current_time( 'timestamp' );
			$product->is_active       = $remaining > 0;
			$product->thumbnail       = get_the_post_thumbnail_url( $product->ID, 'full' );
			$product->title           = get_the_title( $product->ID );
			$product->submitted       = $submitted;
			$product->submitted_human = human_time_diff( $product->submitted, current_time( 'timestamp' ) );
			$product->expires         = $expires;
			$product->expires_human   = human_time_diff( $product->expires, current_time( 'timestamp' ) );
			$product->expires_date    = date( 'M d', $expires );
			$product->duration        = $duration;
			$product->remaining       = $remaining;
			$product->percentage      = $duration !== 0 ? floor( 100 - ( $remaining * 100 ) / $duration ) : 0;
			$product->days_remaining  = round( $remaining / DAY_IN_SECONDS );
		}

		return $products;
	}

	/**
	 * Format product taxonomies so we can use them in a product box
	 * -------------------------------------------------------------
	 *
	 * @param int $product_id
	 * @param string $type
	 *
	 * @return array
	 */
	protected function format_product_taxonomies( $product_id, $type ) {
		$search_page   = lisfinity_get_page_id( 'page-search' );
		$taxonomies    = lisfinity_get_option( "-taxonomy--{$type}" );
		$tax           = [];
		$field_options = get_option( 'lisfinity--search-builder-fields' )['sidebar'];
		$category      = carbon_get_post_meta( $product_id, 'product-category' );

		if ( ! empty( $taxonomies ) ) {
			$taxonomy_model = new TaxonomiesAdminModel();
			foreach ( $taxonomies as $taxonomy ) {
				$options           = $taxonomy_model->get_taxonomy_options( $taxonomy );
				$terms             = get_the_terms( $product_id, $taxonomy );
				$has_icon          = ! empty( $options['icon'] );
				$icon_size_default = lisfinity_get_option( 'ad-taxonomy-icon-size' );

				if ( ! is_wp_error( $terms ) ) {
					if ( ! empty( $terms[0] ) ) {
						$tax[ $taxonomy ]['slug'] = $taxonomy;
						$tax[ $taxonomy ]['name'] = lisfinity_convert_slug_to_name( $taxonomy );
						$term_label               = lisfinity_get_option( 'ad-term-labels-select' );
						$taxonomy_label           = lisfinity_get_option( 'ad-taxonomy-labels-select' );
						if ( 'display_icon' === $term_label || 'icon_and_term' === $term_label ) {
							$icon = wp_get_attachment_image_url( get_term_meta( $terms[0]->term_id, 'icon', true ), 'full' );
						} else {
							$icon = wp_get_attachment_image_url( $options['icon'], 'full' );
						}


						$tax[ $taxonomy ]['icon']       = $icon ?? false;
						$tax[ $taxonomy ]['icon-size']  = $options['icon-size'] ?? $icon_size_default;
						$tax[ $taxonomy ]['term-label'] = $taxonomy_label;
						$tax[ $taxonomy ]['term']       = $terms[0];
						$link                           = add_query_arg( "tax[{$taxonomy}]", $terms[0]->slug, get_permalink( $search_page ) );
						$tax[ $taxonomy ]['link']       = $link;
						$tax[ $taxonomy ]['icon-size']  = $options['icon-size'] ?? $icon_size_default;
						$tax[ $taxonomy ]['prefix']     = $field_options[ $category ]['options'][ $taxonomy ]['prefix'] ?? '';
						$tax[ $taxonomy ]['suffix']     = $field_options[ $category ]['options'][ $taxonomy ]['suffix'] ?? '';
						$tax[ $taxonomy ]['term']       = $terms[0]->name;
						$link                           = add_query_arg( "tax[{$taxonomy}]", $terms[0]->slug, get_permalink( $search_page ) );
						$tax[ $taxonomy ]['link']       = $link;
					}
				}

			}
		}

		return $tax;
	}

	public function get_keyword_search( WP_REST_Request $request_data ) {
		$data          = $request_data->get_params();
		$keyword       = ! empty( $data['keyword'] ) ? urldecode( $data['keyword'] ) : '';
		$this->keyword = $keyword;

		$settings                    = lisfinity_format_keyword_search_settings();
		$result['settings']          = $settings;
		$this->search_title          = $settings['titles'];
		$this->search_description    = $settings['descriptions'];
		$this->search_id             = $settings['ids'];
		$this->search_suggestions    = $settings['suggestions'];
		$this->search_category       = $settings['category'];
		$this->search_category_types = $settings['category-types'];
		$this->search_ads            = $settings['ads'];
		$this->search_ads_type       = ! empty( $settings['ads-type'] ) ? $settings['ads-type'] : 'ads-premium';
		$this->search_authors        = $settings['author'];
		$this->search_authors_type   = ! empty( $settings['author-type'] ) ? $settings['author-type'] : 'author-premium';

		// get matching products.
		add_filter( 'posts_where', [ $this, 'keyword_search_where' ] );

		$product_model = new ProductModel();
		$ads           = [];
		$promoted      = [];
		$authors       = [];
		$taxonomies    = [];

		// search for ads.
		if ( $this->search_ads ) {
			if ( 'ads-premium' === $this->search_ads_type ) {
				add_filter( 'posts_join', [ $this, 'promotions_join' ] );
				add_filter( 'posts_where', [ $this, 'promotions_where' ] );
				$promoted_query = $product_model->get_products_query();
				if ( ! empty( $promoted_query->posts ) ) {
					$promoted[] = array_shift( $promoted_query->posts );
				}

				$this->remove_keyword_filters( [ 'promotions' ] );

				$products = $product_model->get_products_query( [ 'fields' => 'ids' ] )->posts;
			} else {
				$ads_query = $product_model->get_products_query();
				$ads       = $ads_query->posts;
				$products  = array_column( $ads, 'ID' );
			}
		} else {
			$products = $product_model->get_products_query( [ 'fields' => 'ids' ] )->posts;
		}

		// remove all query filters.
		$this->remove_keyword_filters();

		// search by id typed.
		if ( $this->search_id && is_numeric( $this->keyword ) ) {
			$post_by_id = get_post( $this->keyword );
			if ( ! empty( $post_by_id ) ) {
				$post_by_id->guid = get_permalink( $post_by_id->ID );
				$post_by_id->type = 'promotion';
				$promoted[]       = $post_by_id;
			}
		}

		// search for authors (premium profiles).
		if ( $this->search_authors ) {
			$author_args = [
				'post_type'           => 'premium_profile',
				'post_status'         => 'publish',
				'posts_per_page'      => - 1,
				's'                   => $this->keyword,
				'ignore_sticky_posts' => true,
			];
			if ( 'author-premium' === $this->search_authors_type ) {
				add_filter( 'posts_join', [ $this, 'author_promotions_join' ] );
				add_filter( 'posts_where', [ $this, 'author_promotions_where' ] );
				$author_query = new \WP_Query( $author_args );
				$this->remove_keyword_filters( [ 'promotions' ] );
				if ( ! empty( $author_query->posts ) ) {
					$promoted[] = array_shift( $author_query->posts );
				}
			} else {
				$author_query = new \WP_Query( $author_args );
				if ( ! empty( $author_query->posts ) ) {
					$authors[] = array_shift( $author_query->posts );
				}
			}
		}

		// search for taxonomies.
		if ( ! empty( $products ) || ! empty( $promoted ) || ! empty( $authors ) ) {
			$result['message'] = '';
			if ( ! empty( $this->search_category ) ) {
				$taxonomies = $this->get_post_taxonomies( $products ?? [] );
			}

			$ads_formatted = [];
			if ( ! empty( $ads ) ) {
				foreach ( $ads as $ad ) {
					$ads_formatted[ $ad->ID ]['ID']         = $ad->ID;
					$ads_formatted[ $ad->ID ]['post_title'] = $ad->post_title;
					$ads_formatted[ $ad->ID ]['guid']       = get_permalink( $ad->ID );
				}
			}

			$result['query'] = array_merge( $promoted, $taxonomies, $ads_formatted, $authors );
		}

		if ( empty( $result['query'] ) ) {
			$result['message'] = __( 'No matching result found', 'lisfinity-core' );
		}

		return $result;
	}

	public function promotions_join( $join ) {
		global $wpdb;

		$model = new PromotionsModel();
		$join  .= " LEFT JOIN {$model->get_formatted_table_name()} as promotions ON {$wpdb->posts}.ID = promotions.product_id";

		return $join;
	}

	public function promotions_where( $where ) {
		$where .= " AND (promotions.position = 'search-keyword' AND promotions.status = 'active' AND promotions.expires_at >= UNIX_TIMESTAMP())";

		return $where;
	}

	public function author_promotions_join( $join ) {
		global $wpdb;

		$model = new PromotionsModel();
		$join  .= " LEFT JOIN {$model->get_formatted_table_name()} as promotions ON {$wpdb->posts}.ID = promotions.product_id";

		return $join;
	}

	public function author_promotions_where( $where ) {
		$where .= " AND (promotions.position = 'profile-premium' AND promotions.status = 'active' AND promotions.expires_at >= UNIX_TIMESTAMP())";

		return $where;
	}

	public function get_post_taxonomies( $ids ) {
		global $wpdb;

		if ( empty( $ids ) ) {
			return [];
		}
		$categories_in = '';
		$groups_admin  = new GroupsAdminModel();
		$groups        = count( $groups_admin->get_options() );

		if ( empty( $this->search_category_types ) ) {
			return [];
		}
		if ( 0 < $groups ) {
			$cats          = explode( ',', $this->search_category_types );
			$cats          = implode( "','", $cats );
			$categories_in = " AND meta_value IN ('{$cats}')";

			$query   = "SELECT post_id, meta_value, meta_key FROM {$wpdb->postmeta}
					WHERE post_id IN (" . implode( ',', $ids ) . ")
					AND (meta_key='_product-category'" . $categories_in . ")
					GROUP BY meta_value
					LIMIT 10
					";
			$results = $wpdb->get_results( $query );

			$results_formatted = [];
			if ( ! empty( $results ) ) {
				foreach ( $results as $result ) {
					$result->type          = 'category';
					$result->slug          = $result->meta_value;
					$result->taxonomy_name = ucwords( str_replace( '-', ' ', $result->meta_value ) );
					$results_formatted[]   = $result;
				}
			}
		} else {
			$cats          = explode( ',', $this->search_category_types );
			$cats          = implode( "','", $cats );
			$categories_in = "AND taxonomy IN ('{$cats}')";

			$query   = "SELECT object_id as post_id, {$wpdb->terms}.term_id, name, slug, taxonomy FROM {$wpdb->term_taxonomy}
					LEFT JOIN {$wpdb->term_relationships} ON {$wpdb->term_taxonomy}.term_taxonomy_id={$wpdb->term_relationships}.term_taxonomy_id
					LEFT JOIN {$wpdb->terms} ON {$wpdb->term_taxonomy}.term_id={$wpdb->terms}.term_id
					WHERE object_id IN (" . implode( ',', $ids ) . ")
					AND taxonomy NOT IN ('product_type', 'product_cat', 'product_tag')
					" . $categories_in . "
					GROUP BY taxonomy
					";
			$results = $wpdb->get_results( $query );

			$results_formatted = [];
			if ( ! empty( $results ) ) {
				foreach ( $results as $result ) {
					$result->type          = 'taxonomy';
					$result->taxonomy_name = ucwords( str_replace( '-', ' ', $result->taxonomy ) );
					$results_formatted[]   = $result;
				}
			}
		}

		return $results_formatted;
	}

	public function keyword_search_where( $where ) {
		global $wpdb;

		if ( $this->search_title && $this->search_description ) {
			$where .= " AND ({$wpdb->posts}.post_title LIKE '%{$this->keyword}%' OR {$wpdb->posts}.post_content LIKE '%{$this->keyword}%')";
		}

		if ( $this->search_title && ! $this->search_description ) {
			$where .= " AND ({$wpdb->posts}.post_title LIKE '%{$this->keyword}%')";
		}

		if ( $this->search_description && ! $this->search_title ) {
			$where .= " AND ({$wpdb->posts}.post_content LIKE '%{$this->keyword}%')";
		}

		return $where;
	}

	public function remove_keyword_filters( $types = [] ) {
		if ( in_array( 'keyword', $types ) || empty( $types ) ) {
			remove_filter( 'posts_where', [ $this, 'keyword_search_where' ] );
		}
		if ( in_array( 'promotions', $types ) ) {
			remove_filter( 'posts_join', [ $this, 'promotions_join' ] );
			remove_filter( 'posts_where', [ $this, 'promotions_where' ] );
			remove_filter( 'posts_join', [ $this, 'author_promotions_join' ] );
			remove_filter( 'posts_where', [ $this, 'author_promotions_where' ] );
		}
	}

	/**
	 * Get products with the matching keyword value
	 * --------------------------------------------
	 *
	 * @param $keyword
	 *
	 * @return int[]|\WP_Post[]
	 */
	protected function get_products() {
		$args     = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
			'no_found_rows'  => true,
			'cache_results'  => false,
		];
		$products = get_posts( $args );

		return $products;
	}

}
