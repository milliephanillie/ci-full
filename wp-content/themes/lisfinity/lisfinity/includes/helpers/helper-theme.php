<?php
/**
 * General theme helper functions
 *
 * @author pebas
 * @package helpers
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_can_show_post_thumbnail' ) ) {
	function lisfinity_can_show_post_thumbnail() {
		return apply_filters( 'lisfinity_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
	}
}

if ( ! function_exists( 'lisfinity_plugin_is_active' ) ) {
	/**
	 * Check whether the plugin has be installed and activated
	 * -------------------------------------------------------
	 *
	 * @param string $class Name of the class we are checking
	 * @param string $function Name of the function we are
	 * checking
	 *
	 * @return bool
	 */
	function lisfinity_plugin_is_active( $class = '', $function = '' ) {
		$plugin_installed = false;
		if ( ! empty( $class ) && class_exists( $class ) ) {
			$plugin_installed = true;
		} elseif ( ! empty( $function ) && function_exists( $function ) ) {
			$plugin_installed = true;
		}

		return $plugin_installed;
	}
}

if ( ! function_exists( 'lisfinity_is_core_active' ) ) {
	/**
	 * Check if the core theme plugin is activated
	 * -------------------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_core_active() {
		return lisfinity_plugin_is_active( 'Lisfinity_Core' );
	}
}

if ( ! function_exists( 'lisfinity_is_woocommerce_active' ) ) {
	/**
	 * Check whether WooCommerce is activated
	 * --------------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_woocommerce_active() {
		return lisfinity_plugin_is_active( 'WooCommerce' );
	}
}
