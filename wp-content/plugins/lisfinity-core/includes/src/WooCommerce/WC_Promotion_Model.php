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

use WC_Product_Promotion as Promotion;

/**
 * Class WC_Promotion_Model
 * ------------------------------
 *
 * @package Lisfinity\WooCommerce
 */
class WC_Promotion_Model {

	/**
	 * Functions and WooCommerce hooks that we're running
	 * on a default WordPress's 'init' hook.
	 */
	public function init() {
		$type = Promotion::$type;
		add_filter( 'product_type_selector', [ $this, 'add_product_type_selector' ] );
		add_action( 'woocommerce_product_options_general_product_data', [ $this, 'product_data' ] );
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
		$types[ Promotion::$type ] = __( 'Promotion product', 'lisfinity-core' );

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
			'product_type' => Promotion::$type,
			'post_id'      => $post->ID,
		];

		// include lisfinity_get_template_part( 'product-listing-fields', 'admin/product-listing', $args );
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
	}

}
