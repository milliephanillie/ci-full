<?php
/*
Plugin Name: ConcreteIron
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

// Include the Composer autoload file from the other plugin
require_once WP_PLUGIN_DIR . '/lisfinity-core/vendor/autoload.php';
// Include the Composer autoload file from your plugin
require_once plugin_dir_path( CONCRETEIRON ) . 'vendor/autoload.php';

// Include your class file
require_once plugin_dir_path( CONCRETEIRON ) . 'src/Includes/RapidFormSubmitModel.php';
require_once plugin_dir_path( CONCRETEIRON ) . 'src/Includes/RapidProductSubmit.php';


use ConcreteIron\Includes\RapidFormSubmitModel;
use ConcreteIron\Includes\RapidProductSubmit;
use ConcreteIron\Includes\RapidEditModel;
use ConcreteIron\Includes\RapidHooks;
use ConcreteIron\Includes\RapidRenew;
use ConcreteIron\Includes\RapidMailer;
use ConcreteIron\Includes\RapidMemberSince;
use ConcreteIron\Includes\RapidDashRoute;

use ConcreteIron\Import\ListingsImport;
use ConcreteIron\Import\UserImport;

add_action('plugins_loaded', 'load_my_plugin');

function load_my_plugin() {
    // Check if the class from the other plugin exists
    if(class_exists('Lisfinity\Models\Taxonomies\TaxonomiesAdminModel')) {
        // The other plugin is activated, and you can use its classes.
        $rapidHooks = new RapidHooks();
        $rapidProductSubmit = new RapidProductSubmit();
        $rapidEditModel = new RapidEditModel();
        $rapidRenew = new RapidRenew();
        $rapidMailer = new RapidMailer();
        $rapidMemberSince = new RapidMemberSince();
        $rapidDashRoute = new RapidDashRoute();
        $userImports = new UserImport();

        $listingsImport = new ListingsImport();

//        if ( class_exists( 'GFAPI' ) ) {
//            error_log( "Gravity Forms is active." );
//        } else {
//            error_log( "Gravity Forms is not active." );
//        }

        add_action('rest_api_init', [$rapidProductSubmit, 'register_routes']);
    } else {
        // The other plugin isn't loaded. Handle this case appropriately.
        var_dump("the lisfinity plugin wasn't loaded correctly");
        die();
    }
}

require plugin_dir_path( CONCRETEIRON ) . 'class-concreteiron.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-custom-import.php';
//require plugin_dir_path( CONCRETEIRON ) . 'class-listings-import.php';
//require plugin_dir_path( CONCRETEIRON ) . 'class-single-subcategory-update.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-product-images.php';
//require plugin_dir_path( CONCRETEIRON ) . 'class-update-package.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-update-product.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-gf-forms.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-package-products.php';
require plugin_dir_path( CONCRETEIRON ) . 'class-purchase-package.php';


add_action( 'wp_enqueue_scripts', 'ci_scripts' );

function ci_scripts() {
    $localized_vars = [
        "ci_payment_package" => get_site_url() . '/wp-json/ci/v1/get_packages',
        "ci_single_package" => get_site_url() . '/wp-json/ci/v1/get_single_package',
        "ci_purchase_package" => get_site_url() . '/wp-json/ci/v1/purchase-package',
        "ci_product_store" => get_site_url() . '/wp-json/ci/v1/product/store',
        "ci_rapid_renew" => get_site_url() . '/wp-json/ci/v1/rapid_renew',
        "ci_post_id" => get_the_ID(),
    ];

    wp_enqueue_script( 'ci-localize', plugin_dir_path( CONCRETEIRON ) . 'assets/scripts/localize.js', [
        'jquery',
        'wp-i18n'
    ], '1.0.0', true );

    wp_localize_script( 'ci-localize', 'ci_data', $localized_vars );
}

class ConcreteCore {
    private $instance = null;
}

