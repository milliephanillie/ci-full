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

use WC_Product_Commission as Commission;

/**
 * Class WC_Promotion_Model
 * ------------------------------
 *
 * @package Lisfinity\WooCommerce
 */
class WC_Commission_Model {

	/**
	 * Functions and WooCommerce hooks that we're running
	 * on a default WordPress's 'init' hook.
	 */
	public function init() {
		$type = Commission::$type;
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
		$types[ Commission::$type ] = __( 'Commission product', 'lisfinity-core' );

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
			'product_type' => Commission::$type,
			'post_id'      => $post->ID,
		];

		woocommerce_wp_checkbox(
			[
				'id'          => '_is_percentage',
				'label'       => __( 'Is Price Percentage?', 'lisfinity-core' ),
				'desc_tip'    => true,
				'description' => __( 'Check the box if you wish that the price is calculated as a percentage instead of a fixed value.', 'lisfinity-core' ),
				'value'       => wc_bool_to_string( get_post_meta( $post->ID, '_is_percentage', true ) ),
			]
		);

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
		// save meta.
		$meta_to_save = [
			'_is_percentage' => 'no',
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
					$value = wc_bool_to_string( $value );
					break;
				default:
					$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

}
