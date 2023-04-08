<?php
/**
 * Model for our custom WooCommerce product type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

namespace Lisfinity\Controllers;

use Lisfinity\Models\PackageModel as PackageModel;
use Lisfinity\Models\PromotionsModel as PromotionModel;
use Lisfinity\Models\SubscriptionModel;
use WC_Product_Promotion as Promotion;

/**
 * Class PromotionController
 * ------------------------------
 *
 * @package Lisfinity
 */
class PromotionController {

	/**
	 * Fields for the database.
	 * ------------------------
	 *
	 * @var array $fields Fields that should be saved
	 * into the database.
	 */
	private $fields;

	private $product_id;

	private $product_duration;

	/**
	 * PromotionController constructor.
	 * ------------------------------
	 */
	public function __construct() {
		$package      = new PromotionModel();
		$fields       = $package->get_table_fields();
		$this->fields = $fields;
	}

	/**
	 * Handler for storing custom values in database that is
	 * activated when WooCommerce order status has been changed.
	 * ---------------------------------------------------------
	 *
	 * @param integer $order_id - Inherited id of WooCommerce order
	 */
	public function order_paid( $order_id ) {
		$order = wc_get_order( $order_id );

		// return if promotion has already been processed.
		if ( get_post_meta( $order_id, 'promotion_processed', true ) ) {
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$product     = wc_get_product( $item['product_id'] );
			$status      = isset( $item['status'] ) ? $item['status'] : 'active';
			$product_id  = $product->get_id();
			$customer_id = $order->get_customer_id();

			// update product expiration date once the purchase has been made.
			$product_duration       = isset( $item['duration'] ) ? $item['duration'] : lisfinity_get_option( 'product-duration' );
			$this->product_id       = $item['product'];
			$this->product_duration = $product_duration;

			if ( $product->is_type( Promotion::$type ) && $customer_id ) {

				// if paying for the addon (image, video or document) only.
				if ( 'yes' === $item['is_edit'] ) {
					wp_update_post( [
						'ID'          => $this->product_id,
						'post_status' => 'publish',
					] );
					update_post_meta( $order_id, 'promotion_processed', true );
					delete_post_meta( $this->product_id, '_needs-payment|image' );
					delete_post_meta( $this->product_id, '_needs-payment|docs' );
					delete_post_meta( $this->product_id, '_needs-payment|video' );

					return;
				}

				$promotion_model    = new PromotionModel();
				$promotion_type     = carbon_get_post_meta( $product->get_id(), 'promotion-type' );
				$promotion_position = carbon_get_post_meta( $product->get_id(), 'promotion-product-type' );
				$activation_date    = current_time( 'mysql' );
				$expiration_date    = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} days", current_time( 'timestamp' ) ) );
				if ( $promotion_type === 'premium_profile' ) {
					$type = carbon_get_post_meta( $product->get_id(), 'promotion-cost-type' );
					if ( 'month' === $type ) {
						$expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} months", current_time( 'timestamp' ) ) );
					} else {
						$expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} days", current_time( 'timestamp' ) ) );
					}
					$promotion_position = 'profile-premium';
					$business_id        = lisfinity_get_premium_profile_id( $customer_id );
					carbon_set_user_meta( $customer_id, 'account-type', 'business' );
				}

				$promotions_values = [
					// payment package id.
					0,
					// wc order id.
					$order_id,
					// wc product id, id of this product if needed.
					$product_id,
					// id of the user that made order.
					$customer_id,
					// id of the product that this promotion has been activated for.
					$business_id ?? $item['product'],
					// limit or duration number depending on the type of the promotion.
					$item['quantity'],
					// number of products that used addon promotions type, this shouldn't be higher than value.
					1,
					// position of promotion on the site.
					$promotion_position,
					// type of the promotion.
					$promotion_type,
					// status of the promotion
					$status,
					// activation date of the promotion
					$activation_date,
					// expiration date of the promotion if needed.
					$expiration_date,
				];

				// try to find existing promotion so we can update it instead of storing a new value.
				$existing_promotion = $promotion_model->where( [
					[ 'position', $promotion_position ],
					[ 'product_id', $item['product'] ],
					[ 'status', 'active' ],
					[ 'expires_at', '>', 'NOW()' ],
				] )->get( '', '', 'id, expires_at' );

				// update the expiration date in the database.
				if ( ! empty( $existing_promotion ) ) {
					if ( $promotion_type === 'premium_profile' ) {
						$type                = carbon_get_post_meta( $product->get_id(), 'promotion-cost-type' );
						$days                = $type === 'month' ? ( $item['quantity'] * 30 ) : $item['quantity'];
						$new_expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$days} days", strtotime( $existing_promotion[0]->expires_at ) ) );
					} else {
						$new_expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} days", strtotime( $existing_promotion[0]->expires_at ) ) );
					}
					$promotion_model->set( 'expires_at', $new_expiration_date )->where( 'id', $existing_promotion[0]->id )->update();
				} else {
					// save promotion data in the database.
					if ( 'bump-up' === $promotion_position ) {
						$promotion_model->store( $promotions_values );
						// change product listed time.
						$time = current_time( 'mysql' );
						wp_update_post( [
							'ID'            => $this->product_id,
							'post_date'     => $time,
							'post_date_gmt' => get_gmt_from_date( $time ),
						] );
					} else {
						$promotion_model->store( $promotions_values );
					}
				}

				if ( carbon_get_post_meta( $product->get_id(), 'promotion-addon-type' ) === 'addon-qr' ) {
					update_post_meta( $this->product_id, 'qr-paid', 'yes' );
				}
			}
		}

		// Check if the purchase is not coming from the product submission form.
		if ( ! empty( $this->product_duration ) && ! isset( $item['no-product-expiration'] ) ) {
			if ( empty( $product_id ) || carbon_get_post_meta( $product_id, 'promotion-type' ) !== 'premium_profile' ) {
				$default_status  = lisfinity_format_ad_status( '', carbon_get_post_meta( $this->product_id, 'product-business' ) );
				$expiration_date = lisfinity_get_product_expiration_date( $this->product_duration );
				carbon_set_post_meta( $this->product_id, 'product-expiration', $expiration_date );
				wp_update_post( [
					'ID'          => $this->product_id,
					'post_status' => $default_status ?? 'publish',
				] );
			}

			$package_id = get_post_meta( $this->product_id, '_payment-package', true );
			// increase package count.
			if ( ! empty( $package_id ) ) {
				$package_model = new PackageModel();
				$package       = $package_model->where( 'id', $package_id )->get();
				if ( ! empty( $package ) ) {
					$package = array_shift( $package );
					$package_model->update_wp( [ 'products_count' => $package->products_count += 1 ], [ 'id' => $package_id ], [ '%d' ], [ '%s' ] );
				}

				$subscription_model = new SubscriptionModel();
				$package            = $subscription_model->where( 'id', $package_id )->get();
				if ( ! empty( $package ) ) {
					$package = array_shift( $package );
					$subscription_model->update_wp( [ 'products_count' => $package->products_count += 1 ], [ 'id' => $package_id ], [ '%d' ], [ '%s' ] );
				}
			}
		}

		delete_post_meta( $this->product_id, 'ad_promotions_payment_pending' );
		delete_post_meta( $this->product_id, '_needs-payment|image' );
		delete_post_meta( $this->product_id, '_needs-payment|docs' );
		delete_post_meta( $this->product_id, '_needs-payment|video' );
		update_post_meta( $order_id, 'promotion_processed', true );
	}

	/**
	 * Store payment package in the database
	 * -------------------------------------
	 *
	 * @param array $values - Values that should be
	 * inserted in the database
	 *
	 * @return bool|mixed|string|void
	 */
	public function store( $values ) {
		$package = new PromotionModel();

		if ( empty( $this->fields ) ) {
			$error = __( 'There are no fields defined', 'lisfinity-core' );

			return $error;
		}

		$store = $package->store( $values );

		if ( ! empty( $store ) && $store > 0 ) {
			return true;
		}
	}

	// todo use this for example for future works!
	public function add_to_cart() {
		WC()->cart->empty_cart();
		foreach ( $_REQUEST as $key => $value ) {
			if ( false !== strpos( $key, 'lc_' ) && ! strpos( $key, '_value' ) ) {
				$package_id    = $_REQUEST[ $key ] ? $_REQUEST[ $key ] : 0;
				$package_value = $_REQUEST["{$key}_value"] ? $_REQUEST["{$key}_value"] : 0;
				WC()->cart->add_to_cart(
					$package_id,
					$package_value,
					'',
					'',
					[
						'package_id' => $package_id,
					]
				);
			}
		}

		$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );
		wp_send_json( $result );
	}

}
