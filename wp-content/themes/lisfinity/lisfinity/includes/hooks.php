<?php
/**
 * Declare all your actions and filters here.
 *
 * @package Pebas
 */

/**
 * ------------------------------------------------------------------------
 * WordPress
 * ------------------------------------------------------------------------
 */
if ( ! isset( $content_width ) ) {
	$content_width = 940;
}

/**
 * Assets
 */
add_action( 'wp_enqueue_scripts', 'lisfinity_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'lisfinity_admin_enqueue_assets' );
add_action( 'tgmpa_register', 'lisfinity_required_plugins' );
add_action( 'widgets_init', 'lisfinity_register_sidebars' );
add_action( 'init', function () {
	delete_transient( 'elementor_activation_redirect' );
} );
add_action( 'init', function () {
	add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );
} );
add_action( 'init', 'do_output_buffer' );
function do_output_buffer() {
	ob_start();
}
