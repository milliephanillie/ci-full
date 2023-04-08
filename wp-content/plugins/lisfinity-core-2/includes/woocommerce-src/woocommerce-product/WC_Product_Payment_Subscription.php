<?php
/**
 * Adding custom WooCommerce product type that we will
 * use throughout the theme instead of creating our
 * own custom post type and functionality for it.
 *
 * @author pebas
 * @package woocommerce-subscription
 * @version 1.0.0
 */

/**
 * Class WC_Product_Payment_Subscription
 * ------------------------
 *
 * Extension of WooCommerce default product type
 */
class WC_Product_Payment_Subscription extends WC_Product {

	/**
	 * Register the name of our custom WooCommerce product type.
	 * ---------------------------------------------------------
	 *
	 * @var string
	 */
	public static $type = 'payment_subscription';

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
