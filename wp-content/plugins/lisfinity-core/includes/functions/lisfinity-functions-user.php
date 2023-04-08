<?php
/**
 * Functions that are used by theme to define user related actions
 *
 * @author pebas
 * @package lisfinity-core/functions-user
 * @version 1.0.0
 */

use Lisfinity\Abstracts\Model;
use Lisfinity\Controllers\PackageController;
use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\Users\ProfilesModel;

if ( ! function_exists( 'lisfinity_get_bookmarks' ) ) {
	/**
	 * Get all posts that are bookmarked by the given user
	 * ---------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return array
	 */
	function lisfinity_get_bookmarks( $user_id = '' ) {
		$user_id = ! empty( $user_id ) ? $user_id : get_current_user_id();

		$bookmarks = carbon_get_user_meta( $user_id, 'bookmarks' );

		$products = [];
		if ( ! empty( $bookmarks ) ) {
			foreach ( $bookmarks as $bookmark ) {
				$products[] = $bookmark['id'];
			}
		}

		return apply_filters( 'lisfinity__get_bookmarks', $products );
	}
}

if ( ! function_exists( 'lisfinity_get_premium_profile' ) ) {
	/**
	 * Get premium profile attached to a user
	 * --------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return int[]|WP_Post[]
	 */
	function lisfinity_get_premium_profile( $user_id ) {
		$model = new \Lisfinity\Models\Users\ProfilesModel();

		return apply_filters( 'lisfinity__get_premium_profile', $model->get_premium_profile( $user_id ) );
	}
}

if ( ! function_exists( 'lisfinity_get_premium_profile_id' ) ) {
	/**
	 * Get premium profile attached to a user
	 * --------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return int[]|WP_Post[]
	 */
	function lisfinity_get_premium_profile_id( $user_id ) {
		$model = new \Lisfinity\Models\Users\ProfilesModel();

		return apply_filters( 'lisfinity__get_premium_profile', $model->get_premium_profile_id( $user_id ) );
	}
}

if ( ! function_exists( 'lisfinity_get_avatar_url' ) ) {
	/**
	 * Get custom user avatar url
	 * --------------------------
	 *
	 * @param string $user_id
	 *
	 * @return false|string
	 */
	function lisfinity_get_avatar_url( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$avatar_id = carbon_get_user_meta( $user_id, '_avatar' );

		if ( empty( $avatar_id ) ) {
			$avatar_url = get_avatar_url( $user_id );
		} else {
			$avatar_url = wp_get_attachment_image_url( $avatar_id );
		}

		return apply_filters( 'lisfinity__get_avatar_url', $avatar_url );
	}
}

if ( ! function_exists( 'lisfinity_restrict_media_attachments' ) ) {
	/**
	 * Restrict visible media attachments to the ones that were
	 * uploaded by the user
	 * --------------------------------------------------------
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	function lisfinity_restrict_media_attachments( $query ) {
		$user_id = get_current_user_id();
		if ( $user_id && ! current_user_can( 'administrator' ) ) {
			$query['author'] = $user_id;
		}

		return $query;
	}
}

if ( ! function_exists( 'lisfinity_upload_size_limit' ) ) {
	/**
	 * Limit the files maximum upload size
	 * -----------------------------------
	 *
	 * @return int
	 */
	function lisfinity_upload_size_limit() {
		$setting = lisfinity_get_maximum_upload_size_setting();

		return wp_convert_hr_to_bytes( $setting['output'] );
	}
}

if ( ! function_exists( 'lisfinity_get_maximum_upload_size_setting' ) ) {
	/**
	 * Get and format user defined setting for maximum files upload size
	 * -----------------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_maximum_upload_size_setting() {
		$size = lisfinity_get_option( 'site-media-limit' );
		if ( $size < 1 ) {
			$size_output = "{$size}kb";
		} else {
			$size_output = "{$size}mb";
		}

		return [ 'limit' => $size, 'output' => $size_output ];
	}
}

if ( ! function_exists( 'lisfinity_disable_admin_bar' ) ) {
	/**
	 * Disable admin bar for the non admins
	 * ------------------------------------
	 */
	function lisfinity_disable_admin_bar() {
		if ( ! current_user_can( 'administrator' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}
}

if ( ! function_exists( 'lisfinity_no_admin_access' ) ) {
	/**
	 * Prevent access to wp-admin area to non administrators
	 * -----------------------------------------------------
	 */
	function lisfinity_no_admin_access() {
		if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			exit( wp_redirect( esc_url( home_url( '/' ) ) ) );
		}
	}
}

if ( ! function_exists( 'lisfinity_user_has_business' ) ) {
	/**
	 * Check if the user has business page connected to their account
	 * --------------------------------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	function lisfinity_user_has_business( $user_id = '' ) {
		$dashboard = new \Lisfinity\REST_API\Dashboard\DashboardRoute();
		$user_id   = ! empty( $user_id ) ? $user_id : get_current_user_id();
		$agent     = lisfinity_get_agent( $user_id );
		if ( $agent ) {
			$user_id = $agent->owner_id;
		}
		$business = $dashboard->get_business_data( $user_id );
		$page_id  = lisfinity_get_page_id( 'page-account' );
		if ( ! $business ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'lisfinity_create_business_post_after_social_login' ) ) {
	/**
	 * Create business post type after registering a user with social login
	 * --------------------------------------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return int|WP_Error
	 */
	function lisfinity_create_business_post_after_social_login( $user_id ) {

		carbon_set_user_meta( $user_id, 'account-type', lisfinity_get_option( 'auth-default-account-type' ) ?? 'personal' );
		carbon_set_user_meta( $user_id, 'verified', true );

		$user_data = get_userdata( $user_id );

		// assign a user 'editor' role.
		$user_data->set_role( 'editor' );

		// format business name.
		$business_name = lisfinity_get_option( 'auth-business-name' );
		if ( ! empty( $business_name ) && false !== strpos( $business_name, '%s' ) ) {
			$business_name = sprintf( $business_name, $user_data->user_login );
		} else if ( empty( $business_name ) ) {
			$business_name = sprintf( __( 'Business: %s', 'lisfinity-core' ), $user_data->user_login );
		}
		$business_args = [
			'post_type'   => ProfilesModel::$post_type_name,
			'post_status' => 'publish',
			'post_title'  => $business_name,
			'post_author' => $user_id,
		];

		$business_id = wp_insert_post( $business_args );

		$default_packages = lisfinity_get_option( 'auth-default-packages' );

		if ( ! empty( $default_packages ) ) {
			$package_controller = new PackageController();

			foreach ( $default_packages as $package ) {
				$package_obj = wc_get_product( $package );

				if ( ! empty( $package_obj ) ) {
					$values     = [
						// id of the customer that made order.
						$user_id,
						// wc product id of this item.
						(int) $package,
						// wc order id for this item.
						0,
						// limit amount of products in a package.
						carbon_get_post_meta( $package, 'package-products-limit' ) ?? 999,
						// current amount of submitted products in this package.
						0,
						// duration of the submitted products.
						carbon_get_post_meta( $package, 'package-products-duration' ),

						// type of the package.
						'payment_package',
						// status of the package.
						'active',
					];
					$package_id = $package_controller->store( $values );

					// store promotions if there's any
					$prefix     = '';
					$promotions = carbon_get_post_meta( $package, "{$prefix}package-promotions" );

					if ( ! empty( $promotions ) ) {
						$promotion_model = new PromotionsModel();
						foreach ( $promotions as $promotion ) {
							$promotion_type     = ! empty( $promotion['_type'] ) ? $promotion['_type'] : '';
							$promotion_position = ! empty( $promotion['package-promotions-product'] ) ? $promotion['package-promotions-product'] : '';
							$promotion_value    = ! empty( $promotion['package-promotions-product-value'] ) ? $promotion['package-promotions-product-value'] : '';

							// prepare wp_query args.
							// todo currently is only querying addons.
							$args                   = [];
							$args['meta_query'][]   = [
								'key'     => 'promotion-addon-type',
								'value'   => $promotion_position,
								'compare' => '=',
							];
							$args['fields']         = 'ids';
							$args['posts_per_page'] = 1;
							$wc_product_id          = $promotion_model->get_promotion_products( 'promotion', 'addon', $args );
							// bail if there is no WooCommerce product set or promotions is not an addon.
							// todo remove addon check when we provide a functionality for it.
							if ( ! empty( $wc_product_id ) && false !== strpos( $promotion_position, 'addon' ) ) {
								$promotions_values = [
									// payment package id.
									$package_id,
									// wc order id.
									0,
									// wc product id, id of this WooCommerce product.
									$wc_product_id[0],
									// id of the user that made order.
									$user_id,
									// id of the product that this promotion has been activated.
									0,
									// limit or duration number depending on the type of the promotion.
									$promotion_value,
									// count of addon promotions, this cannot be higher than value.
									0,
									// position of promotion on the site.
									$promotion_position,
									// type of the promotion.
									$promotion_type,
									// status of the promotion
									'inactive',
									// activation date of the promotion
									'',
									// expiration date of the promotion if needed.
									'',
								];

								// save promotion data in the database.
								$promotion_model->store( $promotions_values );
							}
						}
					}
				}
			}
		}

		return $business_id;
	}
}

if ( ! function_exists( 'lisfinity_is_vendor_approved' ) ) {
	/**
	 * Check if the vendor has been approved by the admin
	 * --------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return bool
	 */
	function lisfinity_is_vendor_approved( string $user_id = '' ): bool {
		$user_id     = ! empty( $user_id ) ? $user_id : get_current_user_id();
		$business_id = lisfinity_get_premium_profile_id( $user_id );
		if ( lisfinity_is_enabled( lisfinity_get_option( 'site-vendor-approval' ) ) && 1 !== carbon_get_post_meta( $business_id, 'can-post-listings' ) ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'lisfinity_is_business_account' ) ) {
	/**
	 * Check if user is of business type
	 * ---------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_business_account( $user_id ) {
		if ( 'business' === carbon_get_user_meta( $user_id, 'account-type' ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_get_account_type' ) ) {
	/**
	 * Check if user is of business type
	 * ---------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_get_account_type( $user_id ) {
		return apply_filters( 'lisfinity__get_account_type', carbon_get_user_meta( $user_id, 'account-type' ) ?? 'business' );
	}
}

if ( ! function_exists( 'lisfinity_get_product_subscribers' ) ) {
	/**
	 * Get all the users that bid or subscribed to a product
	 * -----------------------------------------------------
	 *
	 * @param $product_id
	 * @param $user_id
	 *
	 * @return array|mixed|object|string|void|null
	 */
	function lisfinity_get_product_subscribers( $product_id, $user_id ) {
		global $wpdb;

		$bid_model = new BidModel();
		// get bidders.
		$bidders = $bid_model->where( [
			[ 'bidder_id', '<>', $user_id ],
			[ 'product_id', '=', $product_id ],
		] )->get( '', '', 'DISTINCT bidder_id', 'col' );

		// get subscribers.
		$users = $wpdb->get_col( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key LIKE '_bookmarks%' AND meta_value='%d' AND user_id<>'%d'", $product_id, $user_id ) );
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				if ( ! in_array( $user, $bidders ) ) {
					$bidders[] = $user;
				}
			}
		}

		return $bidders;
	}
}

if ( ! function_exists( 'lisfinity_get_user_subscriptions' ) ) {
	/**
	 * Get all subscriptions that the user has been subscribed to
	 * ----------------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return array
	 */
	function lisfinity_get_user_subscriptions( $user_id = '' ): array {
		$user_id = ! empty( $user_id ) ? $user_id : get_current_user_id();

		$subscriptions                       = [];
		$subscriptions['bid']                = 'yes' === get_user_meta( $user_id, '_email_subscription|bid', true );
		$subscriptions['product_change']     = 'yes' === get_user_meta( $user_id, '_email_subscription|product_change', true );
		$subscriptions['product_expiration'] = 'yes' === get_user_meta( $user_id, '_email_subscription|product_expiration', true );

		if ( lisfinity_is_enabled( lisfinity_get_option( 'email-new-message' ) ) ) {
			$subscriptions['new_message'] = 'yes' === get_user_meta( $user_id, '_email_subscription|new_message', true );
		}


		return apply_filters( 'lisfinity__get_user_subscriptions', $subscriptions );
	}
}

if ( ! function_exists( 'lisfinity_get_user_subscription_details' ) ) {
	/**
	 * Get the correct title & description for the user email subscriptions
	 * --------------------------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return array
	 */
	function lisfinity_get_user_subscription_details() {
		$titles = [
			'bid'                => [
				'title'       => __( 'Bids', 'lisfinity-core' ),
				'description' => __( 'Receive an email when your offer has been outbid', 'lisfinity-core' ),
			],
			'product_change'     => [
				'title'       => __( 'Listing Updates', 'lisfinity-core' ),
				'description' => __( 'Receive an email when a change has happened to a listing from your bookmarks or the one you bid for', 'lisfinity-core' ),
			],
			'product_expiration' => [
				'title'       => __( 'Listing Expiration', 'lisfinity-core' ),
				'description' => __( 'Receive an email when a listing is near expiration', 'lisfinity-core' ),
			],
		];
		if ( lisfinity_is_enabled( lisfinity_get_option( 'email-new-message' ) ) ) {
			$titles['new_message'] = [
				'title'       => __( 'New Message', 'lisfinity-core' ),
				'description' => __( 'Receive an email when you have a new message', 'lisfinity-core' ),
			];
		}

		return apply_filters( 'lisfinity__get_user_subscription_titles', $titles );
	}
}

if ( ! function_exists( 'lisfinity_get_saved_searches' ) ) {
	/**
	 * Get all subscriptions that the user has been subscribed to
	 * ----------------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return array
	 */
	function lisfinity_get_saved_searches( $user_id = '' ): array {
		global $wpdb;
		$user_id = ! empty( $user_id ) ? $user_id : get_current_user_id();

		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->usermeta WHERE user_id=%d AND meta_key LIKE 'saved-search%'", $user_id ) );

		$array = [];
		if ( ! empty( $results ) ) {
			foreach ( $results as $index => $result ) {
				$values                       = explode( '|', $result->meta_key );
				$array[ $values[1] ]['ids'][] = $result->umeta_id;
				if ( empty( $array[ $values[1] ]['hash'] ) ) {
					$array[ $values[1] ]['hash']     = $values[1];
					$array[ $values[1] ]['index']    = $index + 1;
					$array[ $values[1] ]['products'] = get_user_meta( $user_id, "saved-search|$values[1]", true );
				}
				if ( empty( $array[ $values[1] ]['args'] ) && str_contains( $result->meta_key, 'saved-search--args' ) ) {
					$array[ $values[1] ]['args'] = $result->meta_value;
				}
				if ( empty( $array[ $values[1] ]['email'] ) && str_contains( $result->meta_key, 'saved-search-email' ) ) {
					$array[ $values[1] ]['email'] = $result->meta_value;
				}
			}
		}

		return apply_filters( 'lisfinity__get_saved_searches', $array );
	}
}

if ( ! function_exists( 'lisfinity_additional_user_meta' ) ) {
	/**
	 * Include user meta fields dynamically
	 * ------------------------------------
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	function lisfinity_additional_user_meta( $fields ) {
		if ( lisfinity_is_enabled( Redux::get_option( 'lisfinity-options', '_checkout-vat' ) ) ) {
			$fields[] = \Carbon_Fields\Field::make( 'text', 'user_vat_number', __( 'User VAT Number', 'lisfinity-core' ) )
			                                ->set_help_text( __( 'VAT number provided by the user', 'lisfinity-core' ) );
		}
		if ( lisfinity_is_enabled( Redux::get_option( 'lisfinity-options', '_checkout-sdi-code' ) ) ) {
			$fields[] = \Carbon_Fields\Field::make( 'text', 'user_sdi_code', __( 'User SDI Code', 'lisfinity-core' ) )
			                                ->set_help_text( __( 'SDI code provided by the user', 'lisfinity-core' ) );
		}

		return $fields;
	}
}
add_filter( 'lisfinity__user_meta_fields', 'lisfinity_additional_user_meta', 1 );

if ( ! function_exists( 'lisfinity_check_for_premium' ) ) {
	/**
	 * Function to decide if the system should check if the
	 * categories, taxonomy or term is restricted for the user
	 *
	 * @return bool
	 */
	function lisfinity_check_for_premium() {
		$check_for_premium = false;
		// should we perform the check at all.
		if ( ! empty( get_option( 'lisfinity__premium_taxonomies' ) ) ) {
			$check_for_premium = true;
		}
		// check for the logged in users only.
		if ( 'members' === lisfinity_get_option( 'site-fields-builder-premium-type' ) ) {
			return ! is_user_logged_in();
		}
		// check for the users that have purchased the premium package.
		if ( is_user_logged_in() ) {
			$model   = new PromotionsModel();
			$premium = $model->where( [
				[ 'user_id', get_current_user_id() ],
				[ 'status', 'active' ],
				[ 'expires_at', '>', 'NOW()' ],
				[ 'type', 'premium_profile' ],
			] )->get( '1', '', 'id', 'col' );

			$check_for_premium = empty( $premium );
		}

		return apply_filters( 'lisfinity__check_for_premium', $check_for_premium );
	}
}

function lisfinity_show_listings_to_premium_only() {
	$restrict = false;
	if ( current_user_can( 'administrator' ) ) {
		$restrict = false;
	}

	$restrict_listings = lisfinity_get_option( 'site-restrict-listings' );

	if ( 'members' === $restrict_listings ) {
		if ( is_user_logged_in() ) {
			$restrict = false;
		} else {

			$restrict = true;
		}
	}

	if ( 'premium' === $restrict_listings ) {
		if ( is_user_logged_in() ) {
			$model   = new PromotionsModel();
			$premium = $model->where( [
				[ 'user_id', get_current_user_id() ],
				[ 'status', 'active' ],
				[ 'expires_at', '>', 'NOW()' ],
				[ 'type', 'premium_profile' ],
			] )->get( '1', '', 'id', 'col' );

			$restrict = ! $premium;
		} else {
			$restrict = true;
		}
	}

	return apply_filters( 'lisfinity__show_listings_to_premium_only', $restrict );
}

if ( ! function_exists( 'lisfinity_on_user_delete' ) ) {
	/**
	 * Delete the agent when we're deleting the user from the wp-admin area
	 * --------------------------------------------------------------------
	 *
	 * @param $id
	 * @param $reassign
	 * @param $user
	 */
	function lisfinity_on_user_delete( $id, $reassign, $user ) {
		$model = new \Lisfinity\Models\Agents\AgentModel();
		$agent = $model->where( 'user_id', $id )->get();

		if ( ! empty( $agent ) ) {
			$model->where( 'id', $agent[0]->id )->destroy();
		}
	}
}

if ( ! function_exists( 'lisfinity_get_agent' ) ) {
	/**
	 * Lisfinity get agent for the specified business
	 * ----------------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return false|mixed
	 */
	function lisfinity_get_agent( $user_id ) {
		$model = new \Lisfinity\Models\Agents\AgentModel();

		$agent = $model->where( 'user_id', $user_id )->get();

		return ! empty( $agent[0] ) ? $agent[0] : false;
	}
}

if ( ! function_exists( 'lisfinity_get_subscriptions' ) ) {
	function lisfinity_get_subscriptions() {
		return [ 'bid', 'new_message', 'product_change', 'product_expiration' ];
	}
}
