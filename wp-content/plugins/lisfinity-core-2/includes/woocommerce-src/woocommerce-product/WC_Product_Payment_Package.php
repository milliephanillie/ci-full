<?php
/**
 * Adding custom WooCommerce product type that we will
 * use throughout the theme instead of creating our
 * own custom post type and functionality for it.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

/**
 * Class WC_Product_Payment_Package
 * ------------------------
 *
 * Extension of WooCommerce default product type
 */
class WC_Product_Payment_Package extends WC_Product {

	/**
	 * Register the name of our custom WooCommerce product type.
	 * ---------------------------------------------------------
	 *
	 * @var string
	 */
	public static $type = 'payment_package';

	/**
	 * Register our own product type with WooCommerce.
	 * -----------------------------------------------
	 *
	 * @return string
	 */
	public function get_type() {
		return self::$type;
	}

}
