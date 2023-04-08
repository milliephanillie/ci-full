<?php
/**
 * Lisfinity promotion functions
 *
 * Functions that are only relevant for our custom WooCommerce
 * product of type promotion. These functions are relevant them while
 * specific ones are based in their own classes. @see includes/src/
 *
 * @author pebas
 * @package lisfinity-core
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_available_promotion_types' ) ) {
	/**
	 * Define all available promotion types
	 * ------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_available_promotion_types() {
		$types = [
			'product'         => __( 'Product', 'lisfinity-core' ),
			'addon'           => __( 'Addon', 'lisfinity-core' ),
			'premium_profile' => __( 'Premium Profile', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__available_promotion_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_available_promotion_product_types' ) ) {
	/**
	 * Define all available promotion product types
	 * --------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_available_promotion_product_types() {
		$types = [
			//'home-banner'       => __( 'Home Banner', 'lisfinity-core' ),
			'home-ads'          => __( 'Home Ads', 'lisfinity-core' ),
			'search-keyword'    => __( 'Keyword Search', 'lisfinity-core' ),
			'category-featured' => __( 'Category Featured', 'lisfinity-core' ),
			'single-ad'         => __( 'Single Ad Page', 'lisfinity-core' ),
			'bump-color'        => __( 'Bump Color', 'lisfinity-core' ),
			'bump-pin'          => __( 'Bump Pin', 'lisfinity-core' ),
			'bump-up'           => __( 'Bump Up', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__available_promotion_product_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_available_promotion_addon_types' ) ) {
	/**
	 * Define all available promotion addon types
	 * ------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_available_promotion_addon_types() {
		$types = [
			'addon-image' => __( 'Additional Image', 'lisfinity-core' ),
			'addon-video' => __( 'Additional Video', 'lisfinity-core' ),
			'addon-docs'  => __( 'Additional Document', 'lisfinity-core' ),
			'addon-qr'    => __( 'QR Code', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__available_promotion_addon_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_set_all_promotion_types' ) ) {
	/**
	 * Define all available promotion addon types
	 * ---------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_set_all_promotion_types() {
		$product_types = lisfinity_available_promotion_product_types();
		$addon_types   = lisfinity_available_promotion_addon_types();
		$types         = array_merge( $product_types, $addon_types, [ 'profile-premium' => __( 'Profile Premium', 'lisfinity-core' ) ] );

		return apply_filters( 'lisfinity__set_all_promotion_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_get_all_promotion_types' ) ) {
	/**
	 * Get all available promotion addon types
	 * ---------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_all_promotion_types() {
		$all_types = lisfinity_set_all_promotion_types();
		$types     = [];

		if ( ! empty( $all_types ) ) {
			foreach ( $all_types as $value => $label ) {
				$types[ $value ] = $label;
			}
		}

		return apply_filters( 'lisfinity__get_all_promotion_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_before_calculate_totals' ) ) {
	/**
	 * Add additional functionality before calculating fees
	 * ----------------------------------------------------
	 *
	 * @param $cart
	 */
	function lisfinity_before_calculate_fees( $cart ) {
		// loop through the cart_contents
		foreach ( $cart->cart_contents as $cart_item_key => &$item ) {
			// if type is premium profile and has discount to add.
			if ( ! empty( $item['discount'] ) && 0 !== $item['discount'] ) {
				$day_or_month = carbon_get_post_meta( $item['product_id'] ?? $item['product'], 'promotion-cost-type' );
				$discount     = $cart->subtotal * ( $item['discount'] / 100 );
				if ( empty( $day_or_month ) ) {
					$day_or_month = __( 'listings', 'lisfinity-core' );
				}

				$cart->add_fee( sprintf( __( '%s%% discount for %s %s duration', 'lisfinity-core' ), $item['discount'], $item['quantity'], $day_or_month ), - $discount );
			}
		}
	}

	add_action( 'woocommerce_cart_calculate_fees', 'lisfinity_before_calculate_fees' );
}

if ( ! function_exists( 'lisfinity_before_calculate_totals' ) ) {
	/**
	 * Add additional functionality before calculating totals
	 * ------------------------------------------------------
	 *
	 * @param $cart
	 */
	function lisfinity_before_calculate_totals( $cart ) {
		// loop through the cart_contents
		foreach ( $cart->cart_contents as $cart_item_key => &$item ) {
			if ( ! empty( $item['type'] ) && $item['type'] === 'commission' && ! empty( $item['commission'] ) && 0 != $item['commission'] ) {
				$item['data']->set_price( $item['commission'] );
			}
			if ( ! empty( $item['no-price'] ) ) {
				$item['data']->set_price( 0 );
			}
			if ( ! empty( $item['custom-price'] ) ) {
				$item['data']->set_price( $item['custom-price'] / $item['quantity'] );
			}
		}
	}

	add_action( 'woocommerce_before_calculate_totals', 'lisfinity_before_calculate_totals' );
}

if ( ! function_exists( 'lisfinity_commission_paid' ) ) {
	/**
	 * Send commission confirmation email to user and the listing owner
	 * ----------------------------------------------------------------
	 *
	 * @param $order_id
	 */
	function lisfinity_commission_paid( $order_id ) {
		$order = wc_get_order( $order_id );

		foreach ( $order->get_items() as $item ) {

			if ( ! empty ( $item['publish_product'] ) ) {
				wp_update_post( [ 'ID' => $item['publish_product'], 'post_status' => 'publish' ] );
			}

			$customer_id = $order->get_customer_id();
			$business_id = lisfinity_get_premium_profile_id( $customer_id );

			$commission_data = get_post_meta( (int) $business_id, "commission|{$item['product']}", true );

			if ( ! empty( $commission_data ) ) {
				$user_business = lisfinity_get_premium_profile_id( $commission_data['buyer_id'] );
				$headers       = [ 'Content-Type: text/html; charset=UTF-8' ];
				// send email to vendor.
				$to = carbon_get_post_meta( $business_id, 'profile-email' );
				if ( empty( $to ) ) {
					$customer = get_userdata( $customer_id );
					$to       = $customer->user_email;
				}
				$subject = sprintf( esc_html__( 'Auction Winner Details | %s', 'lisfinity-core' ), get_option( 'blogname' ) );

				$user_data       = get_userdata( $commission_data['buyer_id'] );
				$user_email      = carbon_get_post_meta( $user_business, 'profile-email' );
				$business_phones = carbon_get_post_meta( $user_business, 'profile-phones' );

				$user_permalink = get_permalink( $user_business );
				$user_title     = get_the_title( $user_business );
				$permalink      = get_permalink( $item['product'] );
				$title          = get_the_title( $item['product'] );
				if ( empty( $user_email ) ) {
					$user_email = $user_data->user_email;
				}
				if ( empty( $business_phones ) ) {
					$body = sprintf( __( 'User %1$s has requested to buy your listing (%$1s) for the specified price. <br /> You can contact him on the details below: <br /><br /> Email: %2$s',
						'lisfinity-core' ), get_the_title( $user_business ), "<a href='mailto: {$user_email}'>$user_email</a>" );
				} else {
					$phones = array_column( $business_phones, 'profile-phone' );
					$string = '';
					$count  = 1;
					if ( ! empty( $user_email ) ) {
						$string .= sprintf( __( 'Email: %s <br />', 'lisfinity-core' ), "<a href='mailto: {$user_email}'>$user_email</a>" );
					}
					foreach ( $phones as $phone ) {
						$phone_formatted = str_replace( ' ', '', $phone );
						$string          .= sprintf( __( 'Phone %1$s: %2$s <br />', 'lisfinity-core' ), $count, "<a href='tel:{$phone_formatted}'>$phone</a>" );
						$count           += 1;
					}
					$body = sprintf( __( 'User %1$s has requested to buy your ad (%2$s) for the specified price. <br /> You can contact him on the details below: <br /><br /> %3$s',
						'lisfinity-core' ), "<a href='{$user_permalink}' target='_blank'>{$user_title}</a>", "<a href='{$permalink}' target='_blank'>{$title}</a>", $string );
					$mail = wp_mail( $to, $subject, $body, $headers );
				}


				// send email to buyer.
				$to      = $user_email;
				$subject = sprintf( esc_html__( 'Auction Won Confirmation | %s' ), get_option( 'blogname' ) );
				$body    = sprintf( __( 'The owner of the listing <strong>%s</strong> has confirmed you as the winner of the auction and successfully obtained your details. You can expect to be contacted shortly.', 'lisfinity-core' ), $title );
				$mail    = wp_mail( $to, $subject, $body, $headers );

				// delete commission meta.
				delete_post_meta( (int) $business_id, "commission|{$item['product']}" );
			}
		}
	}

	add_action( 'woocommerce_order_status_completed', 'lisfinity_commission_paid' );
}

if ( ! function_exists( 'lisfinity_add_product_to_order' ) ) {
	/**
	 * Add commission meta data to the product line order so we can use it to send commission emails
	 * ---------------------------------------------------------------------------------------------
	 *
	 * @param $item
	 * @param $cart_item_key
	 * @param $values
	 * @param $order
	 */
	function lisfinity_add_product_to_order( $item, $cart_item_key, $values, $order ) {
		if ( ! isset( $values['commission'] ) || empty( $values['commission'] ) ) {
			return;
		}

		$item->update_meta_data( 'product', $values['product'] );
		$item->update_meta_data( 'commission', $values['commission'] );
		$item->update_meta_data( 'type', $values['type'] );
	}

	add_action( 'woocommerce_checkout_create_order_line_item', 'lisfinity_add_product_to_order', 10, 4 );
}

if ( ! function_exists( 'lisfinity_get_qr_promotion' ) ) {
	/**
	 * Get QR promotion type WC product
	 * --------------------------------
	 *
	 * @return array|false
	 */
	function lisfinity_get_qr_promotion() {
		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'tax_query'           => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'promotion',
					'operator' => 'IN',
				],
			],
			'meta_query'          => [
				[
					'key'   => '_promotion-type',
					'value' => 'addon',
				],
				[
					'key'   => '_promotion-addon-type',
					'value' => 'addon-qr',
				],
			],
			'fields'              => 'ids',
		];

		$products = get_posts( $args );

		if ( ! empty( $products ) ) {
			$product              = wc_get_product( $products[0] );
			$result['id']         = $product->get_id();
			$result['price']      = (float) $product->get_price();
			$result['price_html'] = $product->get_price_html();

			return $result;
		}

		return false;
	}
}
