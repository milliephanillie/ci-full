<?php
/**
 * ConcreteIron  Custom Import class.
 *
 * @category   Class
 * @package    ConcreteIron
 * @subpackage WordPress
 * @author     Philip Rudy <me@philiparudy.com>
 * @copyright  2022 Philip Rudy
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      1.0.0
 * php version 7.3.9
 */
if ( ! defined( 'ABSPATH' ) ) {
    // Exit if accessed directly.
    exit;
}

include_once 'class-tax-map.php';
/**
 * Main ConcreteIron Class
 *
 * The init class that runs the Concrete Iron plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the class-widgets.php file.
 */
class Single_Subcategory_Update {
    const ACF_PREFIX = 'ci_';
    const SINGLE_IMAGE_ROUTE = 'ci/v1/one_image';
    const PRODUCT_IMAGES_ROUTE = "ci/v1/product_images";

    private $concrete_equipment_subcategory_terms;
    private $subcat_slugs = [];
    private $concrete_equipment_type_terms;
    private $type_slugs = [];

    /**
     * CustomImport constructor
     */
    public function __construct() {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot() {
        add_filter('https_ssl_verify', '__return_false');
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }


    /**
     * Single subcategory update
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'single_subcategory_update';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'single_subcategory_update'],
            'args' => [],
        ));
    }

    /**
     * @return void
     */
    public function single_subcategory_update( WP_REST_Request $request ) {
        $params = $request->get_params();
        $subcat_slug = $params['concrete-equipment-subcategory'];
        $type_slug = $params['concrete-equipment-type'];
        $product_id = $params['product_id'];

        if (! $product_id ) {
            return rest_ensure_response(new WP_REST_Response(
                [
                    "message" => "There's no product id"
                ]
            ));
        }

        if($subcat_slug) {
            $this->concrete_equipment_subcategory_terms = get_terms('concrete-equipment-subcategory', [
                'hide_empty' => false,
            ]);

            foreach( $this->concrete_equipment_subcategory_terms as $subcat ) {
                if (is_object($subcat) && property_exists($subcat, 'slug')) {
                    array_push($this->subcat_slugs, $subcat->slug);
                }
            }

            if( in_array($subcat_slug, $this->subcat_slugs) ) {
                $set_term = wp_set_object_terms($product_id, $subcat_slug, 'concrete-equipment-subcategory');
            }
        }

        if($type_slug) {
            $this->concrete_equipment_type_terms = get_terms('concrete-equipment-type', [
                'hide_empty' => false,
            ]);

            foreach( $this->concrete_equipment_type_terms as $type ) {
                if (is_object($type) && property_exists($type, 'slug')) {
                    array_push($this->type_slugs, $type->slug);
                }
            }

            if( in_array($type_slug, $this->type_slugs) ) {
                $set_term = wp_set_object_terms($product_id, $type_slug, 'concrete-equipment-type');
            }
        }

        $subcat_query = get_the_terms($product_id, 'concrete-equipment-subcategory');
        $type_query = get_the_terms($product_id, 'concrete-equipment-type');

        return rest_ensure_response(new WP_REST_Response(
            [
                "subcat_query" => $subcat_query,
                "type_query" => $type_query
            ]
        ));
    }
}

$ssu = new Single_Subcategory_Update();
$ssu->boot();