<?php
/**
 * Register menu locations.
 *
 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
 *
 * @author pebas
 * @package themeSetup
 * @version 1.0.0
 */

$menus = [
	'main-menu' => esc_html__( 'Main Menu', 'lisfinity' ),
    'primary-navigation' => __('Primary Navigation', 'lisfinity'),
];

if ( lisfinity_is_core_active() ) {
	$menus['mobile-menu'] = esc_html__( 'Mobile Menu', 'lisfinity' );
}

register_nav_menus( $menus );
