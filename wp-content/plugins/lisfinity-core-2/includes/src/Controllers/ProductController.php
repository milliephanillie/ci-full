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


/**
 * Class PackageController
 * ------------------------------
 *
 * @package Lisfinity
 */
class ProductController {

	/**
	 * Handler for storing custom values in database that is
	 * activated when WooCommerce order status has been changed.
	 * ---------------------------------------------------------
	 *
	 * @param integer $order_id - Inherited id of WooCommerce order
	 */
	public function order_paid( $order_id ) {
		$order = wc_get_order( $order_id );

		$product_model = new \WC_Product_Listing();

		foreach ( $order->get_items() as $item ) {
			$product     = wc_get_product( $item['product_id'] );
			$product_id  = $product->get_id();
			$customer_id = $order->get_customer_id();

			if ( $product->is_type( $product_model::$type ) ) {
				$stock = (int) get_post_meta( $product_id, '_stock_custom', true );
				$stock -= 1;
				if ( $stock > 0 ) {
					update_post_meta( $product_id, '_stock_custom', $stock );
				} else {
					wp_update_post( [
						'ID'          => $product_id,
						'post_status' => 'sold',
					] );

					$owner_id = carbon_get_post_meta( $product_id, 'product-owner' );
					if ( ! empty( $owner_id ) ) {
						$owner_data = get_userdata( $owner_id );
						$headers    = [ 'Content-Type: text/html; charset=UTF-8' ];
						$body       = sprintf( __( 'Your product <strong>%1$s</strong> is out of stock after the latest order has been marked as completed.', 'lisfinity-core' ), get_the_title( $product_id ) );

						$mail = wp_mail( $owner_data->user_email, esc_html__( 'Your Listing is out of stock', 'lisfinity-core' ), $body, $headers );
					}
				}
			}
		}


	}

}
