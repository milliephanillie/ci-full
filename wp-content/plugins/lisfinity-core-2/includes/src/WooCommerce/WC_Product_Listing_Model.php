<?php
/**
 * Model for our custom WooCommerce product type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

namespace Lisfinity\WooCommerce;

use WC_Product_Listing as Listing;

/**
 * Class WC_Product_Listing_Model
 * ------------------------------
 *
 * @package Lisfinity\WooCommerce
 */
class WC_Product_Listing_Model {

	/**
	 * Functions and WooCommerce hooks that we're running
	 * on a default WordPress's 'init' hook.
	 */
	public function init() {
		$type = Listing::$type;
		add_filter( 'product_type_selector', [ $this, 'add_product_type_selector' ] );
		add_action( 'woocommerce_product_options_general_product_data', [ $this, 'product_data' ], 100 );
		add_action( "woocommerce_process_product_meta_{$type}", [ $this, 'save_product_data' ] );
	}

	/**
	 * Register our own custom WooCommerce product type
	 * with WooCommerce types selection.
	 * ------------------------------------------------
	 *
	 * @param array $types - array of available WooCommerce
	 * types that we're attaching to.
	 *
	 * @return mixed
	 */
	public function add_product_type_selector( $types ) {
		$types[ Listing::$type ] = __( 'Ad product', 'lisfinity-core' );

		return $types;
	}

	/**
	 * Register product meta data that will be displayed in
	 * WooCommerce's General Tab.
	 * ----------------------------------------------------
	 */
	public function product_data() {
		global $post;
		$args = [
			'product_type' => Listing::$type,
			'post_id'      => $post->ID,
		];

		$params = [
			'id'                => '_stock_custom',
			'value'             => get_post_meta( $post->ID, '_stock_custom', true ),
			'label'             => __( 'Stock quantity', 'lisfinity-core' ),
			'desc_tip'          => true,
			'description'       => __( 'Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce' ),
			'type'              => 'number',
			'custom_attributes' => [
				'step' => 'any',
			],
		];
		woocommerce_wp_text_input( $params );

		//include lisfinity_get_template_part( 'product-listing-fields', 'admin/product-listing', $args );
	}

	/**
	 * Save our custom product data that we attached
	 * to WooCommerce's General tab.
	 * ---------------------------------------------
	 *
	 * @param int $post_id ID of the post for which
	 * we wish to save meta fields.
	 */
	public function save_product_data( $post_id ) {
		// save meta.
		$meta_to_save = [
			'_stock_custom' => 0,
		];

		foreach ( $meta_to_save as $meta_key => $sanitize ) {
			$value = ! empty( $_POST[ $meta_key ] ) ? $_POST[ $meta_key ] : ''; //phpcs:ignore
			switch ( $sanitize ) {
				case 'int':
					$value = absint( $value );
					break;
				case 'float':
					$value = floatval( $value );
					break;
				case 'yesno':
					$value = 'yes' === $value ? 'yes' : 'no';
					break;
				default:
					$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

}
