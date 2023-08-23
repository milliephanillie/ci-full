<?php

use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;

/**
 * Lisfinity product functions
 *
 * Functions that are only relevant for our custom WooCommerce
 * product types. Those functions are relevant for all of them while
 * specific ones are based in their own classes. @see includes/src/
 *
 * @author pebas
 * @package lisfinity-core
 * @version 1.0.0
 */

/**
 * Format the status of the ad by the available theme options
 * ----------------------------------------------------------
 *
 * @param $type
 * @param bool $premium
 *
 * @return string
 */
function lisfinity_format_ad_status( $type = '', $premium = false ) {
	if ( $type === 'edit' ) {
		$status = lisfinity_get_option( 'product-status-edit' );
	} else {
		$status = lisfinity_get_option( 'product-status' );
	}

	switch ( $status ) :
		case 'pending':
			return 'pending';
		case 'live_premium':
			if ( $premium ) {
				return 'publish';
			} else {
				return 'pending';
			}
		default:
			return 'publish';
	endswitch;
}

if ( ! function_exists( 'lisfinity_transition_to_rejected' ) ) {
	/**
	 * Send email for the rejected listing only if admin review is needed
	 * ------------------------------------------------------------------
	 *
	 * @param $post_id
	 */
	function lisfinity_transition_to_rejected( $post_id ) {
		$product_type = get_the_terms( $post_id, 'product_type' );
		$status       = lisfinity_get_option( 'product-status' );
		if ( ! empty( $product_type[0] ) && 'live' !== $status ) {
			if ( 'listing' === $product_type[0]->slug ) {
				$business_id    = carbon_get_post_meta( $post_id, 'product-business' );
				$author_id      = carbon_get_post_meta( $post_id, 'product-owner' );
				$user_data      = get_userdata( $author_id );
				$business_email = carbon_get_post_meta( $business_id, 'profile-email' );
				$reason         = carbon_get_post_meta( $post_id, 'product-reject-reason' );

				$email = ! empty( $business_email ) ? $business_email : $user_data->user_email;

				$to      = $email;
				$subject = esc_html__( 'Listing rejection', 'lisfinity-core' );
				$body    = sprintf( esc_html__( 'Your submission for the listing "%s" has been rejected.', 'lisfinity-core' ), get_the_title( $post_id ) );
				if ( $reason ) {
					$body .= '<br />' . esc_html( $reason );
				}
				$headers = [ 'Content-Type: text/html; charset=UTF-8' ];

				wp_mail( $to, $subject, $body, $headers );
			}
		}
	}
}

if ( ! function_exists( 'lisfinity_register_post_status' ) ) {
	/**
	 * Register necessary post statuses so we can
	 * mark our products as sold and automatically
	 * exclude theme from WP_Query.
	 * -------------------------------------------
	 *
	 */
	function lisfinity_register_product_statuses() {
		register_post_status( 'sold', array(
			'label'                     => _x( 'Sold', 'lisfinity_core', 'lisfinity-core' ),
			'label_count'               => _n_noop( 'Sold <span class="count">(%s)</span>', 'Sold <span class="count">(%s)</span>', 'lisfinity-core' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true
		) );
		register_post_status( 'rejected', array(
			'label'                     => _x( 'Rejected', 'lisfinity_core', 'lisfinity-core' ),
			'label_count'               => _n_noop( 'Sold <span class="count">(%s)</span>', 'Sold <span class="count">(%s)</span>', 'lisfinity-core' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true
		) );
	}
}

function lisfinity_register_product_status_add_in_quick_edit() {
	echo "<script>
        jQuery(document).ready( function() {
            jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"sold\">Sold</option>' );
            jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"rejected\">Rejected</option>' );
        });
        </script>";
}

add_action( 'admin_footer-edit.php', 'lisfinity_register_product_status_add_in_quick_edit' );
function lisfinity_register_product_status_add_in_post_page() {
	echo "<script>
        jQuery(document).ready( function() {
            jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"sold\">Sold</option>' );
            jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"rejected\">Rejected</option>' );
        });
        </script>";
}

add_action( 'admin_footer-post.php', 'lisfinity_register_product_status_add_in_post_page' );
add_action( 'admin_footer-post-new.php', 'lisfinity_register_product_status_add_in_post_page' );

function lisfinity_add_display_post_states( $post_states, $post ) {
	if ( 'sold' === $post->post_status ) {
		$post_states['lisfinity_product_sold'] = sprintf( '<span style="padding: 2px 4px; background-color: #f86a6a; color: #fff; border-radius: 4px;">%s<span>', __( 'Sold', 'lisfinity-core' ) );
	}
	if ( 'rejected' === $post->post_status ) {
		$post_states['lisfinity_product_sold'] = sprintf( '<span style="padding: 2px 4px; background-color: #f0b429; color: #fff; border-radius: 4px;">%s<span>', __( 'Rejected', 'lisfinity-core' ) );
	}
	if ( 'pending' === $post->post_status ) {
		$post_states['lisfinity_product_pending'] = sprintf( '<span style="padding: 2px 4px; background-color: #f86a6a; color: #fff; border-radius: 4px;">%s<span>', __( 'Pending review', 'lisfinity-core' ) );
	}
	$product_type = get_the_terms( $post->ID, 'product_type' );
	if ( ! empty( $product_type[0] ) ) {
		if ( 'listing' === $product_type[0]->slug ) {
			$expires    = carbon_get_post_meta( $post->ID, 'product-expiration' );
			$is_expired = $expires < current_time( 'timestamp' );
			if ( empty( $post_states ) ) {
				if ( $is_expired ) {
					$post_states['lisfinity_product_pending'] = sprintf( '<span style="padding: 2px 4px; background-color: #f86a6a; color: #fff; border-radius: 4px;">%s<span>', __( 'Ad', 'lisfinity-core' ) );
				} else {
					$post_states['lisfinity_product_pending'] = sprintf( '<span style="padding: 2px 4px; background-color: #47a3f3; color: #fff; border-radius: 4px;">%s<span>', __( 'Ad', 'lisfinity-core' ) );
				}
			} else {
				$post_states['lisfinity_product_pending'] = sprintf( '<span style="color: #fff;">%s<span>', __( 'Ad', 'lisfinity-core' ) );
			}
			if ( $is_expired ) {
				$post_states['lisfinity_product_expired'] = sprintf( '<span style="color: #fff;">%s<span>', __( 'Expired', 'lisfinity-core' ) );
			}
		}
		if ( 'payment_package' === $product_type[0]->slug ) {
			$post_states['lisfinity_product_pending'] = sprintf( '<span style="padding: 2px 4px; background-color: #f0b429; color: #fff; border-radius: 4px;">%s<span>', __( 'Price Package', 'lisfinity-core' ) );
		}
		if ( 'promotion' === $product_type[0]->slug ) {
			$post_states['lisfinity_product_pending'] = sprintf( '<span style="padding: 2px 4px; background-color: #27ab83; color: #fff; border-radius: 4px;">%s<span>', __( 'Promotion', 'lisfinity-core' ) );
		}
	}

	return $post_states;
}

add_filter( 'display_post_states', 'lisfinity_add_display_post_states', 10, 2 );

if ( ! function_exists( 'lisfinity_set_product_statuses' ) ) {
	/**
	 * Set available product statuses
	 * ------------------------------
	 *
	 * @return array
	 */
	function lisfinity_set_product_statuses() {
		$statuses = [ 'active', 'expired', 'sold', 'rejected' ];

		return apply_filters( 'lisfinity__set_product_statuses', $statuses );
	}
}

if ( ! function_exists( 'lisfinity_get_formatted_product_statuses' ) ) {
	/**
	 * Format product statuses by key=>value pairs
	 * -------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_formatted_product_statuses() {
		$statuses           = lisfinity_set_product_statuses();
		$formatted_statuses = [];

		if ( ! empty( $statuses ) ) {
			foreach ( $statuses as $status ) {
				$formatted_statuses[ $status ] = ucfirst( $status );
			}
		}

		return $formatted_statuses;
	}
}

if ( ! function_exists( 'lisfinity_available_product_types' ) ) {
	/**
	 * Define all available product types
	 * ----------------------------------
	 *
	 * @return array
	 */
	function lisfinity_available_product_types() {
		$product_model = new \Lisfinity\Models\ProductModel();

		return $product_model->available_product_types();
	}
}

if ( ! function_exists( 'lisfinity_get_default_product_types' ) ) {
	/**
	 * Get default product types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_default_product_types() {
		$product_model = new \Lisfinity\Models\ProductModel();

		return $product_model->get_default_product_types();
	}
}

if ( ! function_exists( 'lisfinity_get_chosen_product_types' ) ) {
	/**
	 * Get chosen product types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_chosen_product_types() {
		$product_model = new \Lisfinity\Models\ProductModel();

		return $product_model->get_chosen_product_types();
	}
}

if ( ! function_exists( 'lisfinity_available_price_types' ) ) {
	/**
	 * Define all available price types
	 * ----------------------------------
	 *
	 * @return array
	 */
	function lisfinity_available_price_types() {
		$types = [
			'fixed'         => __( 'Fixed', 'lisfinity-core' ),
			'per_week'      => __( 'Per Week', 'lisfinity-core' ),
			'per_month'     => __( 'Per Month', 'lisfinity-core' ),
			'per_day'      => __( 'Per Day', 'lisfinity-core' ),
			'per_hour'     => __( 'Per Hour', 'lisfinity-core' ),
			'negotiable'    => __( 'Negotiable', 'lisfinity-core' ),
			'auction'       => __( 'Auction', 'lisfinity-core' ),
			'price_on_call' => __( 'Call for Price', 'lisfinity-core' ),
			'free'          => __( 'Free', 'lisfinity-core' )
		];

		return apply_filters( 'lisfinity__available_price_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_get_default_price_types' ) ) {
	/**
	 * Get default price types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_default_price_types() {
		$types = lisfinity_available_price_types();
		if ( empty( $types ) ) {
			return [];
		}

		$types = array_keys( $types );

		return apply_filters( 'lisfinity__get_default_price_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_get_chosen_price_types' ) ) {
	/**
	 * Get chosen price types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_chosen_price_types() {
		$types           = lisfinity_get_option( 'product-price-types' );
		$available_types = lisfinity_available_price_types();
		$chosen_types    = [];

		if ( empty( $types ) ) {
			return $available_types;
		}

		foreach ( $types as $type ) {
			if ( in_array( $type, array_keys( $available_types ), true ) ) {
				$chosen_types[ $type ] = $available_types[ $type ];
			}
		}

		return $chosen_types;
	}
}

if ( ! function_exists( 'lisfinity_get_product_expiration_date' ) ) {
	/**
	 * Calculate the product expiration date
	 * -------------------------------------
	 *
	 * @param string $duration Number of the days
	 * a product will be active for.
	 *
	 * @return bool|false|int
	 */
	function lisfinity_get_product_expiration_date( $duration = '' ) {
		$product_model = new \Lisfinity\Models\ProductModel();

		return $product_model->calculate_expiration_date( $duration );
	}
}

if ( ! function_exists( 'lisfinity_get_product_types' ) ) {
	/**
	 * Get available product group types
	 * ---------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_product_groups() {
		$groups = new GroupsAdminModel();
		$types  = $groups->get_options();

		return apply_filters( 'lisfinity__get_product_groups', lisfinity_prepare_product_groups_select( $types ) );
	}
}

if ( ! function_exists( 'lisfinity_prepare_product_groups_select' ) ) {
	/**
	 * Prepare available product group types for use in a select field
	 * ---------------------------------------------------------------
	 *
	 * @param $groups
	 *
	 * @return array
	 */
	function lisfinity_prepare_product_groups_select( $groups ) {
		if ( empty( $groups ) ) {
			$groups['common'] = __( 'Not Specified', 'lisfinity-core' );

			return $groups;
		}

		$prepared['common'] = __( 'Not Specified', 'lisfinity-core' );
		foreach ( $groups as $group ) {
			$prepared[ $group['slug'] ] = $group['single_name'];
		}

		return apply_filters( 'lisfinity__prepare_product_groups_select', $prepared );
	}
}

if ( ! function_exists( 'lisfinity_get_group_products_count' ) ) {
	/**
	 * Get the count of the products attached to a category group type
	 * ---------------------------------------------------------------
	 *
	 * @param string $group - Name of the group that we're pulling data from.
	 *
	 * @return int
	 */
	function lisfinity_get_group_products_count( $group ) {
		$model = new \Lisfinity\Models\ProductModel();
		$count = get_transient( "lisfinity__group_products_count[{$group}]" );

		if ( empty( $count ) ) {
			$products = $model->get_products_query( [
				'meta_key'   => '_product-category',
				'meta_value' => $group,
				'fields'     => 'ids'
			] );

			$count = count( $products->posts );

			set_transient( "lisfinity__group_products_count[{$group}]", $count, 1 * DAY_IN_SECONDS );
		}


		return apply_filters( 'lisfinity__get_group_products_count', $count );
	}
}

if ( ! function_exists( 'lisfinity_get_promoted_products' ) ) {
	/**
	 * Get all active promoted products
	 * --------------------------------
	 *
	 * @param string $position
	 *
	 * @return array|bool|mixed|object|string|void|null
	 */
	function lisfinity_get_promoted_products( $position = '' ) {
		$model = new \Lisfinity\Models\PromotionsModel();

		$last_changed = wp_cache_get_last_changed( 'lisfinity--get-promoted-products' );
		$key          = md5( serialize( $position ) );
		$cache_key    = "$key:$last_changed";
		$result       = wp_cache_get( $cache_key, 'lisfinity--get-promoted-products' );

		// try loading results from a wp cache.
		if ( false !== $result ) {
			return $result;
		}

		$current_time = current_time( 'mysql' );
		if ( ! empty( $position ) ) {
			$result = $model->where( [
				[ 'position', $position ],
				[ 'status', 'active' ],
				[ 'expires_at', '>', "'{$current_time}'" ],
			] )->get( '', 'ORDER BY created_at DESC', 'product_id, position, created_at, expires_at' );
		} else {
			$result = $model->where( [
				[ 'status', 'active' ],
				[ 'expires_at', '>', "'{$current_time}'" ],
			] )->get( '', ' ORDER BY created_at DESC', 'product_id, position, created_at, expires_at' );
		}

		// put results in a wp cache for multiple uses on a same page load.
		wp_cache_set( $cache_key, $result, 'lisfinity--get-promoted-products' );

		return apply_filters( 'lisfinity__get_promoted_products', $result );
	}
}

if ( ! function_exists( 'lisfinity_is_promoted_product' ) ) {
	/**
	 * Check if the product is promoted
	 * --------------------------------
	 *
	 * @param string $product_id
	 * @param string $position
	 *
	 * @return bool
	 */
	function lisfinity_is_promoted_product( $product_id = '', $position = '' ) {
		$promoted_products = lisfinity_get_promoted_products();
		$product_id        = ! empty( $product_id ) ? $product_id : get_the_ID();

		if ( ! empty( $promoted_products ) ) {
			foreach ( $promoted_products as $product ) {
				// if we're looking for position and product_id.
				if ( ! empty( $position ) ) {
					if ( $product_id == $product->product_id && $position === $product->position ) {
						return true;
					}
				}

				// if we're just looking for a position.
				if ( $product_id === $product->product_id ) {
					return true;
				}
			}
		}

		return false;

	}
}

if ( ! function_exists( 'lisfinity_format_location' ) ) {
	/**
	 * Format locations so it can be displayed it correctly
	 * ----------------------------------------------------
	 *
	 * @param string $post_id
	 * @param bool $full_format
	 * @param bool $taxonomy
	 * @param array $location
	 *
	 * @return string
	 */
	function lisfinity_format_location( $post_id = '', $full_format = false, $taxonomy = true, $location = [] ) {
		$post_id = ! empty( $post_id ) ? $post_id : get_the_ID();

		if ( $taxonomy ) {
			$taxonomies_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
			$taxonomies       = $taxonomies_model->get_taxonomies_by_type( 'location' );

			$formatted = [];
			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					$terms = get_the_terms( $post_id, $taxonomy );
					if ( ! is_wp_error( $terms ) && isset ( $terms[0]->name ) ) {
						$term_meta = get_term_meta( $terms[0]->term_id );
						if ( ! empty( $term_meta['short_name'][0] ) ) {
							$formatted[] = $term_meta['short_name'][0];
						} else {
							$formatted[] = $terms[0]->name;
						}
					}
				}
			}
		} else {
			$formatted = $location;
		}

		$result = '';
		if ( ! empty( $formatted ) ) {
			$formatted = array_reverse( $formatted );
			if ( $full_format ) {
				$result = implode( ', ', $formatted );
			} else {
				$result = $formatted[0];
			}
		}

		return apply_filters( 'lisfinity__format_location', ucwords( $result ) );
	}
}

if ( ! function_exists( 'lisfinity_get_price_html' ) ) {
	function lisfinity_get_price_html( $price, $product = '' ) {
		if ( '' === $price ) {
			$price = '';
		} else {
			if ( empty( $product ) ) {
				$price = wc_price( $price );
			} else {
				$price = wc_price( $price ) . $product->get_price_suffix();
			}
		}

		return apply_filters( 'lisfinity__get_price_html', $price, $product );
	}
}

if ( ! function_exists( 'lisfinity_share_options' ) ) {
	/**
	 * Define a list of available product share options
	 * ------------------------------------------------
	 *
	 * @return mixed
	 */
	function lisfinity_share_options() {
		$options = [
			'facebook'      => 'Facebook',
			'twitter'       => 'Twitter',
			'telegram'      => 'Telegram',
			'whatsapp'      => 'WhatsApp',
			'linkedin'      => 'Linkedin',
			'pinterest'     => 'Pinterest',
			'vk'            => 'VK',
			'odnoklassniki' => 'Odnoklassniki',
			'reddit'        => 'Reddit',
			'tumblr'        => 'Tumblr',
			'mail.ru'       => 'Mail.Ru',
			'livejournal'   => 'LiveJournal',
			'viber'         => 'Viber',
			'workplace'     => 'Workplace',
			'line'          => 'Line',
			'weibo'         => 'Weibo',
			'pocket'        => 'Pocket',
			'instapaper'    => 'Instapaper',
			'email'         => 'email',
		];

		return apply_filters( 'lisfinity__share_options', $options );
	}
}

if ( ! function_exists( 'lisfinity_store_impression' ) ) {
	function lisfinity_store_impression( $data ) {
		$model = new \Lisfinity\Models\Stats\StatModel();
		$model->update_stat( $data );
	}
}

if ( ! function_exists( 'lisfinity_exclude_from_query' ) ) {
	/**
	 * Exclude packages from WooCommerce
	 * products query
	 * ----------------------------------
	 *
	 * @param $tax_query
	 *
	 * @return mixed
	 */
	function lisfinity_exclude_from_query( $tax_query ) {
		if ( is_shop() || is_product() ) {
			$tax_query['tax_query'][] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => [ 'listing', 'payment_package', 'promotion', 'payment_subscription', 'commission' ],
				'operator' => 'NOT IN',
			);
		}

		return $tax_query;
	}

	add_filter( 'woocommerce_product_query_tax_query', 'lisfinity_exclude_from_query' );
}

if ( ! function_exists( 'lisfinity_exclude_from_related' ) ) {
	/**
	 * Exclude packages from WooCommerce
	 * related products query
	 * ---------------------------------
	 *
	 * @param $exclude_ids
	 *
	 * @return array
	 */
	function lisfinity_exclude_from_related( $exclude_ids ) {
		$posts       = get_posts( array(
			'post_type' => 'product',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => [ 'listing', 'payment_package', 'promotion', 'payment_subscription', 'commission' ],
					'operator' => 'NOT IN',
				)
			)
		) );
		$exclude_ids = wp_list_pluck( $posts, 'ID' );

		return $exclude_ids;
	}

	add_filter( 'woocommerce_related_products', 'lisfinity_exclude_from_related' );
}

if ( ! function_exists( 'lisfinity_calculate_business_rating' ) ) {
	/**
	 * Calculate business average rating
	 * ---------------------------------
	 *
	 * @param $id
	 *
	 * @return float|int|mixed
	 */
	function lisfinity_calculate_business_rating( $id ) {
		$model = new \Lisfinity\Models\Testimonials\TestimonialModel();

		return apply_filters( 'lisfinity__calculate_business_rating', $model->calculate_business_average_rating( $id ) );
	}
}

if ( ! function_exists( 'lisfinity_count_business_ratings' ) ) {
	/**
	 * Count number of ratings a business received
	 * -------------------------------------------
	 *
	 * @param $id
	 *
	 * @return mixed|void
	 */
	function lisfinity_count_business_ratings( $id ) {
		$model = new \Lisfinity\Models\Testimonials\TestimonialModel();

		return apply_filters( 'lisfinity__count_business_rating', count( $model->get_business_reviews( $id ) ) );
	}
}

if ( ! function_exists( 'lisfinity_is_premium_business' ) ) {
	/**
	 * Check if the business is under premium time
	 * -------------------------------------------
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	function lisfinity_is_premium_business( $id ) {
		$model = new PromotionsModel();

		$premium = $model->where( [
			[ 'product_id', $id ],
			[ 'status', 'active' ],
			[ 'expires_at', '>', 'NOW()' ],
		] )->get( '1', '', 'id, product_id, wc_product_id, status, expires_at, created_at' );

		return ! empty( $premium );
	}
}

if ( ! function_exists( 'lisfinity_menu_pending_count' ) ) {
	/**
	 * Add count of the pending ads in the WordPress menu.
	 * ---------------------------------------------------
	 *
	 */
	function lisfinity_menu_pending_count() {
		global $submenu;

		if ( isset( $submenu['edit.php?post_type=product'] ) ) {
			// Remove 'WooCommerce' sub menu item.
			unset( $submenu['edit.php?post_type=product'][0] );

			// Add count if user has access.
			if ( apply_filters( 'lisfinity__menu_pending_count', true ) && current_user_can( 'manage_woocommerce' ) ) {
				$pending_count = lisfinity_count_pending_ads();

				if ( $pending_count ) {
					foreach ( $submenu['edit.php?post_type=product'] as $key => $menu_item ) {
						if ( 0 === strpos( $menu_item[0], _x( 'All Products', 'Admin menu name', 'lisfinity-core' ) ) ) {
							$submenu['edit.php?post_type=product'][ $key ][0] .= ' <span class="awaiting-mod update-plugins count-' . esc_attr( $pending_count ) . '"><span class="processing-count">' . number_format_i18n( $pending_count ) . '</span></span>'; // WPCS: override ok.
							break;
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'lisfinity_count_pending_ads' ) ) {
	/**
	 * Count pending ads
	 * -----------------
	 *
	 * @return int
	 */
	function lisfinity_count_pending_ads() {
		$model = new \Lisfinity\Models\ProductModel();
		$ads   = $model->get_products_query( [
			'post_status' => 'pending',
			'fields'      => 'ids',
		] );

		return $ads->found_posts;
	}
}
if ( ! function_exists( 'lisfinity_get_promoted_businesses' ) ) {
	/**
	 * Get all active promoted businesses
	 * ----------------------------------
	 *
	 * @return object
	 */
	function lisfinity_get_promoted_businesses() {
		$model = new \Lisfinity\Models\PromotionsModel();

		$result = $model->where( [
			[ 'type', 'premium_profile' ],
			[ 'status', 'active' ],
		] )->get( '', 'ORDER BY created_at DESC', 'DISTINCT product_id', 'col' );

		return apply_filters( 'lisfinity__get_promoted_businesses', $result );
	}
}

if ( ! function_exists( 'lisfinity_get_businesses_letters' ) ) {
	/**
	 * Get all first letters from the available vendors
	 * ------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_businesses_letters() {
		global $wpdb;
		$letters = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT LEFT(post_title, 1) AS letter FROM {$wpdb->posts} WHERE post_type = '%s' ORDER BY letter ASC", \Lisfinity\Models\Users\ProfilesModel::$post_type_name ) );

		return apply_filters( 'lisfinity__get_businesses_letters', $letters );
	}
}

if ( ! function_exists( 'lisfinity_calculate_expiring_percentage' ) ) {
	/**
	 * Return the percentage of the time left until the expiration of an ad
	 * --------------------------------------------------------------------
	 *
	 * @param $submitted
	 * @param $expires
	 *
	 * @return false|float
	 */
	function lisfinity_calculate_expiring_percentage( $submitted, $expires ) {
		$submitted = is_numeric( $submitted ) ? $submitted : strtotime( $submitted );
		$expires   = is_numeric( $expires ) ? $expires : strtotime( $expires );
		$duration  = $expires - $submitted;
		$remaining = $expires - current_time( 'timestamp' );

		return apply_filters( 'lisfinity__calculate_expiring_percentage', floor( 100 - ( $remaining * 100 ) / $duration ) );
	}
}

if ( ! function_exists( 'lisfinity_is_unlimited' ) ) {
	/**
	 * Check if the value should be considered as unlimited
	 * ----------------------------------------------------
	 *
	 * @param $number
	 *
	 * @return bool
	 */
	function lisfinity_is_unlimited( $number ) {
		if ( 9999 === $number ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_change_product_owner' ) ) {
	/*
	 * Change the product owner
	 * ------------------------
	 */
	function lisfinity_change_product_owner( $new_owner_id, $product_ids = [], $owner_id = '' ) {
		if ( ! empty( $product_ids ) ) {
			foreach ( $product_ids as $product_id ) {
				$new_business = lisfinity_get_premium_profile_id( $new_owner_id );
				carbon_set_post_meta( $product_id, 'product-owner', $new_owner_id );
				carbon_set_post_meta( $product_id, 'product-business', $new_business );
			}
		}

		if ( empty( $product_ids ) && ! empty( $owner_id ) ) {
			$type = \WC_Product_Listing::$type;
			$args = [
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'posts_per_page'      => - 1,
				'meta_key'            => '_product-owner',
				'meta_value'          => $owner_id,
				'tax_query'           => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'name',
						'terms'    => $type,
						'operator' => 'IN',
					],
				],
			];

			$product_ids = get_posts( $args );

			if ( ! empty( $product_ids ) ) {
				foreach ( $product_ids as $product_id ) {
					$new_business = lisfinity_get_premium_profile_id( $new_owner_id );
					carbon_set_post_meta( $product_id, 'product-owner', $new_owner_id );
					carbon_set_post_meta( $product_id, 'product-business', $new_business );
				}
			}
		}
	}
}

if ( ! function_exists( 'lisfinity_recently_visited_listings' ) ) {
	/**
	 * Store the recently viewed listings by the user
	 * ----------------------------------------------
	 *
	 * @param $product_id
	 */
	function lisfinity_recently_visited_listings( $product_id ) {
		$user_id         = get_current_user_id();
		$recent_listings = get_user_meta( get_current_user_id(), 'recent-listings', true );
		if ( ! empty( $user_id ) ) {
			if ( empty( $recent_listings ) ) {
				update_user_meta( $user_id, 'recent-listings', [ $product_id ] );
			} else {
				if ( ! in_array( $product_id, $recent_listings ) ) {
					$limit = lisfinity_get_option( 'recent-listings' ) ?? 6;
					if ( count( $recent_listings ) >= $limit ) {
						array_splice( $recent_listings, 0, 1 );
					}
					$recent_listings[] = $product_id;
					update_user_meta( $user_id, 'recent-listings', $recent_listings );
				}
			}
		}
	}

	add_action( 'lisfinity__single_product', 'lisfinity_recently_visited_listings' );
}

if ( ! function_exists( 'lisfinity_store_views_count' ) ) {

	/**
	 * Store all product views count
	 * -----------------------------
	 *
	 * @param $product_id
	 */
	function lisfinity_store_views_count( $product_id ) {
		$model = new \Lisfinity\Models\Stats\StatModel();
		carbon_set_post_meta( $product_id, 'product-views', (int) $model->get_product_views( $product_id ) );
	}

	add_action( 'lisfinity__single_product', 'lisfinity_store_views_count' );
}
if ( ! function_exists( 'lisfinity_get_submission_fields_ids' ) ) {
	/**
	 * Get the ids of every field from the submission form
	 * ---------------------------------------------------
	 *
	 * @param $type - string
	 * @param $specific_type - string
	 *
	 * @return array
	 */
	function lisfinity_get_submission_fields_ids( $type = '', $specific_type = '' ) {
		$model = new \Lisfinity\Models\Forms\FormSubmitModel();

		return $model->get_field_ids( $type, $specific_type );
	}
}

if ( ! function_exists( 'lisfinity_get_hidden_categories' ) ) {
	/**
	 * Get categories that are hidden by default
	 * -----------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_hidden_categories() {
		$model             = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$options           = $model->get_options();
		$hidden_by_default = [];
		if ( ! empty( $options ) ) {
			foreach ( $options as $option ) {
				if ( ! empty( $option['slug'] ) && ! empty( $option['hidden'] ) && 'yes' === $option['hidden'] ) {
					$hidden_by_default[] = $option['slug'];
				}
			}
		}

		return $hidden_by_default;
	}
}

function lisfinity_additional_product_fields( $fields ) {
	if ( lisfinity_is_enabled( Redux::get_option( 'lisfinity-options', '_phone-number-listing-submission-form' ) ) ) {
		$fields[] = \Carbon_Fields\Field::make( 'text', 'phone', __( 'Phone Number', 'lisfinity-core' ) );
	}
	if ( lisfinity_is_enabled( Redux::get_option( 'lisfinity-options', '_website-listing-submission-form' ) ) ) {
		$fields[] = \Carbon_Fields\Field::make( 'text', 'website', __( 'Website', 'lisfinity-core' ) );
	}
	if ( lisfinity_is_enabled( Redux::get_option( 'lisfinity-options', '_email-listing-submission-form' ) ) ) {
		$fields[] = \Carbon_Fields\Field::make( 'text', 'email', __( 'Email Address', 'lisfinity-core' ) );
	}

	return $fields;
}

add_filter( 'lisfinity__product_meta_fields_general', 'lisfinity_additional_product_fields' );


function lisfinity_add_display_post_states_to_businesses( $post_states, $post ) {
	if ( ! lisfinity_is_vendor_approved( $post->post_author ) ) {
		$post_states['lisfinity__business_approved'] = sprintf( '<span style="padding: 2px 4px; background-color: #f86a6a; color: #fff; border-radius: 4px;">%s<span>', __( 'Pending Approval', 'lisfinity-core' ) );
	}

	return $post_states;
}

add_filter( 'display_post_states', 'lisfinity_add_display_post_states_to_businesses', 10, 2 );

function lisfinity_listings_default_post_author() {
	$args = [
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => - 1,
		'tax_query'           => [
			[
				'taxonomy' => 'product_type',
				'field'    => 'name',
				'terms'    => 'listing',
				'operator' => 'IN',
			],
		],
		'fields'              => 'ids',
	];

	$product_ids = get_posts( $args );

	if ( ! empty( $product_ids ) ) {
		foreach ( $product_ids as $id ) {
			$owner = carbon_get_post_meta( $id, 'product-owner' );
			carbon_set_post_meta( $id, 'product-agent', $owner );
		}
	}
}

function lisfinity_upload_file( $file, $args = [] ) {
	global $job_manager_upload, $job_manager_uploading_file;

	include_once ABSPATH . 'wp-admin/includes/file.php';
	include_once ABSPATH . 'wp-admin/includes/media.php';

	$args = wp_parse_args(
		$args,
		[
			'file_key'           => '',
			'file_label'         => '',
			'allowed_mime_types' => '',
		]
	);

	$job_manager_upload         = true;
	$job_manager_uploading_file = $args['file_key'];
	$uploaded_file              = new stdClass();
	if ( '' === $args['allowed_mime_types'] ) {
		$allowed_mime_types = job_manager_get_allowed_mime_types( $job_manager_uploading_file );
	} else {
		$allowed_mime_types = $args['allowed_mime_types'];
	}

	$file = apply_filters( 'job_manager_upload_file_pre_upload', $file, $args, $allowed_mime_types );

	if ( is_wp_error( $file ) ) {
		return $file;
	}

	if ( ! in_array( $file['type'], $allowed_mime_types, true ) ) {
		// Replace pipe separating similar extensions (e.g. jpeg|jpg) to comma to match the list separator.
		$allowed_file_extensions = implode( ', ', str_replace( '|', ', ', array_keys( $allowed_mime_types ) ) );

		if ( $args['file_label'] ) {
			// translators: %1$s is the file field label; %2$s is the file type; %3$s is the list of allowed file types.
			return new WP_Error( 'upload', sprintf( __( '"%1$s" (filetype %2$s) needs to be one of the following file types: %3$s', 'wp-job-manager' ), $args['file_label'], $file['type'], $allowed_file_extensions ) );
		} else {
			// translators: %s is the list of allowed file types.
			return new WP_Error( 'upload', sprintf( __( 'Uploaded files need to be one of the following file types: %s', 'wp-job-manager' ), $allowed_file_extensions ) );
		}
	} else {
		$upload = wp_handle_upload( $file, apply_filters( 'submit_job_wp_handle_upload_overrides', [ 'test_form' => false ] ) );
		if ( empty( $upload['error'] ) ) {
			$uploaded_file->url       = $upload['url'];
			$uploaded_file->file      = $upload['file'];
			$uploaded_file->name      = basename( $upload['file'] );
			$uploaded_file->type      = $upload['type'];
			$uploaded_file->size      = $file['size'];
			$uploaded_file->extension = substr( strrchr( $uploaded_file->name, '.' ), 1 );
		}
	}

	$job_manager_upload         = false;
	$job_manager_uploading_file = '';

	return $uploaded_file;
}

function lisfinity_prepare_uploaded_files( $file_data ) {
	$files_to_upload = [];

	if ( is_array( $file_data['name'] ) ) {
		foreach ( $file_data['name'] as $file_data_key => $file_data_value ) {
			if ( $file_data['name'][ $file_data_key ] ) {
				$type              = wp_check_filetype( $file_data['name'][ $file_data_key ] ); // Map mime type to one WordPress recognises.
				$files_to_upload[] = [
					'name'     => $file_data['name'][ $file_data_key ],
					'type'     => $type['type'],
					'tmp_name' => $file_data['tmp_name'][ $file_data_key ],
					'error'    => $file_data['error'][ $file_data_key ],
					'size'     => $file_data['size'][ $file_data_key ],
				];
			}
		}
	} else {
		$type              = wp_check_filetype( $file_data['name'] ); // Map mime type to one WordPress recognises.
		$file_data['type'] = $type['type'];
		$files_to_upload[] = $file_data;
	}

	return apply_filters( 'job_manager_prepare_uploaded_files', $files_to_upload );
}

function job_manager_get_allowed_mime_types( $field = '' ) {
	$allowed_mime_types = [
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
	];

	/**
	 * Mime types to accept in uploaded files.
	 *
	 * Default is image, pdf, and doc(x) files.
	 *
	 * @param array  {
	 *     Array of allowed file extensions and mime types.
	 *     Key is pipe-separated file extensions. Value is mime type.
	 * }
	 * @param string $field The field key for the upload.
	 *
	 * @since 1.25.1
	 *
	 */
	return apply_filters( 'job_manager_mime_types', $allowed_mime_types, $field );
}

if ( ! function_exists( 'lisfinity_get_attachment_id' ) ) {
	/**
	 * Get the Attachment ID for a given image URL.
	 *
	 * @link   http://wordpress.stackexchange.com/a/7094
	 *
	 * @param  string $url
	 *
	 * @return boolean|integer
	 */
	function lisfinity_get_attachment_id( $url ) {

		$dir = wp_upload_dir();

		// baseurl never has a trailing slash
		if ( false === strpos( $url, $dir['baseurl'] . '/' ) ) {
			// URL points to a place outside of upload directory
			return false;
		}

		$file  = basename( $url );
		$query = array(
			'post_type'  => 'attachment',
			'fields'     => 'ids',
			'meta_query' => array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => $file,
					'compare' => 'LIKE',
				),
			)
		);

		// query attachments
		$ids = get_posts( $query );

		if ( ! empty( $ids ) ) {

			foreach ( $ids as $id ) {

				// first entry of returned array is the URL
				$array1 = wp_get_attachment_image_src( $id, 'full' );
				if ( $url === array_shift( $array1 ) )
					return $id;
			}
		}

		$query['meta_query'][0]['key'] = '_wp_attachment_metadata';

		// query attachments again
		$ids = get_posts( $query );

		if ( empty( $ids) )
			return false;

		foreach ( $ids as $id ) {

			$meta = wp_get_attachment_metadata( $id );

			foreach ( $meta['sizes'] as $size => $values ) {

				$array = wp_get_attachment_image_src( $id, $size );
				if ( $values['file'] === $file && $url === array_shift( $array ) )
					return $id;
			}
		}

		return false;
	}
}
