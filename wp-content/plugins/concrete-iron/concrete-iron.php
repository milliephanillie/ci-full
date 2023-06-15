<?php
/*
Plugin Name: Concrete Iron
Plugin URI:  https://www.concreteiron.com/
Description: To manage concrete iron marketplace
Version:     1.0
Author:      1027
Author URI:  https://www.concreteiron.com/
*/

define( 'CONCRETEIRON', __FILE__ );

/**
 * Include the CONCRETEIRON class.
 */

require plugin_dir_path( CONCRETEIRON ) . 'class-concreteiron.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-custom-import.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-listings-import.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-single-subcategory-update.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-product-images.php';
//require plugin_dir_path( CONCRETEIRON ) . 'class-update-package.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-update-product.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-gf-forms.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-package-products.php';


$localized_vars = [
    "all_packages" => 'ci/v1/get_packages',
];



add_action( 'wp_enqueue_scripts', 'ci_scripts' );

function ci_scripts() {
    $localized_vars = [
        "ci_payment_package" => get_site_url() . '/wp-json/ci/v1/get_packages',
    ];

    wp_enqueue_script( 'ci-localize', plugin_dir_path( CONCRETEIRON ) . 'assets/scripts/localize.js', [
        'jquery',
        'wp-i18n'
    ], '1.0.0', true );

    wp_localize_script( 'ci-localize', 'ci_data', $localized_vars );
}

