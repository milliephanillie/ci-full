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

use Lisfinity\Models\PackageModel;
use Lisfinity\Models\PackageModel as Package;
use Lisfinity\Models\PromotionsModel;
use WC_Product_Payment_Package as Payment_Package;

/**
 * Class PackageController
 * ------------------------------
 *
 * @package Lisfinity
 */
class PackageController {

	/**
	 * Fields for the database.
	 * ------------------------
	 *
	 * @var array $fields Fields that should be saved
	 * into the database.
	 */
	private $fields;

	/**
	 * PackageController constructor.
	 * ------------------------------
	 */
	public function __construct() {
		$package      = new Package();
		$fields       = $package->get_table_fields();
		$this->fields = $fields;
	}

	public function order_processing( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( get_post_meta( $order_id, 'package_processed', true ) ) {
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$product     = wc_get_product( $item['product_id'] );
			$product_id  = $product->get_id();
			$customer_id = $order->get_customer_id();
			$prefix     = '';
			if ( $product->is_type( \WC_Product_Payment_Subscription::$type ) ) {
				$prefix = 's-';
			}

			$products_limit    = carbon_get_post_meta( $product_id, 'package-products-limit' );
			if ( $item['quantity'] > 1 ) {
				$products_limit = absint( $item['quantity'] );
			} else {
				$products_limit = 0 != $products_limit ? $products_limit : 9999;
			}
			$products_duration = carbon_get_post_meta( $product_id, 'package-products-duration' );
			$products_limit    = 0 != $products_limit ? $products_limit : 9999;
			$products_duration = 0 != $products_duration ? $products_duration : 9999;
			if ( $product->is_type( Payment_Package::$type ) && $customer_id ) {
				$values = [
					// id of the customer that made order.
					$customer_id,
					// wc product id of this item.
					$product_id,
					// wc order id for this item.
					$order_id,
					// limit amount of products in a package.
					$products_limit,
					// current amount of submitted products in this package.
					0,
					// duration of the submitted products.
					$products_duration,
					// type of the package.
					'payment_package',
					// status of the package.
					'inactive',
				];
				// store package in the db.
				$package_id = $this->store( $values );

				// store promotions if there's any
				$promotions = carbon_get_post_meta( $product_id, "{$prefix}package-promotions" );

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
								$order_id,
								// wc product id, id of this WooCommerce product.
								$wc_product_id[0],
								// id of the user that made order.
								$customer_id,
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
								'pending',
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

		update_post_meta( $order_id, 'package_processed', true );
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

		if ( get_post_meta( $order_id, 'package_paid', true ) ) {
			return;
		}

		$package_model    = new PackageModel();
		$existing_package = $package_model->where( 'order_id', $order_id )->get( '1', '', 'id', 'col' );

		foreach ( $order->get_items() as $item ) {
			$product     = wc_get_product( $item['product_id'] );
			$product_id  = $product->get_id();
			$customer_id = $order->get_customer_id();
			$prefix     = '';
			if ( $product->is_type( \WC_Product_Payment_Subscription::$type ) ) {
				$prefix = 's-';
			}

			$products_limit = carbon_get_post_meta( $product_id, 'package-products-limit' );
			if ( $item['quantity'] > 1 ) {
				$products_limit = absint( $item['quantity'] );
			} else {
				$products_limit = 0 != $products_limit ? $products_limit : 9999;
			}
			$products_duration = carbon_get_post_meta( $product_id, 'package-products-duration' );
			$products_duration = 0 != $products_duration ? $products_duration : 9999;
			update_post_meta( $order_id, 'package', $customer_id );
			if ( $product->is_type( Payment_Package::$type ) && $customer_id ) {
				if ( ! empty( $existing_package ) ) {
					$package_id = $existing_package[0];
					$package_model->set( 'status', 'active' )->where( 'id', $package_id )->update();
				} else {
					$values = [
						// id of the customer that made order.
						$customer_id,
						// wc product id of this item.
						$product_id,
						// wc order id for this item.
						$order_id,
						// limit amount of products in a package.
						$products_limit,
						// current amount of submitted products in this package.
						0,
						// duration of the submitted products.
						$products_duration,
						// type of the package.
						'payment_package',
						// status of the package.
						'active',
					];
					// store package in the db.
					$package_id = $this->store( $values );
				}

				// find promotions if we're coming from processing order payment.
				$promotion_model     = new PromotionsModel();
				$existing_promotions = $promotion_model->where( 'order_id', $order_id )->get( '', '', 'id', 'col' );

				if ( ! empty( $existing_promotions ) ) {
					$existing_promotions = implode( ',', $existing_promotions );
					$promotion_model->set( 'status', 'inactive' )->where( [
						[
							'id',
							'IN',
							"({$existing_promotions})"
						]
					] )->update();
				} else {
					// store promotions if there's any
					$promotions = carbon_get_post_meta( $product_id, "{$prefix}package-promotions" );

					if ( ! empty( $promotions ) ) {
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
									$order_id,
									// wc product id, id of this WooCommerce product.
									$wc_product_id[0],
									// id of the user that made order.
									$customer_id,
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
		update_post_meta( $order_id, 'package_paid', true );
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
		$package = new Package();

		if ( empty( $this->fields ) ) {
			$error = __( 'There are no fields defined', 'lisfinity-core' );

			return $error;
		}

		$store = $package->store( $values );

		if ( ! empty( $store ) && $store > 0 ) {
			return $store;
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
