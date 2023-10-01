<?php

namespace ConcreteIron\Includes;

use Lisfinity\Models\Forms\FormSubmitModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\REST_API\Forms\FormSubmitRoute;
use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Controllers\PackageController as PackageController;
use Lisfinity\Models\PromotionsModel as PromotionModel;

class RapidProductSubmit
{
    private $redirect = false;

    protected $data = [];

    protected $is_edit = false;

    protected $packages_enabled = false;

    protected $has_promotions = false;

    protected $has_commission = false;

    protected $is_business = false;

    protected $additional_payment = false;

    protected $submission_commission = false;

    protected $default_duration = 90;

    /**
     * CustomImport constructor
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot()
    {
        add_filter('https_ssl_verify', '__return_false');
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_product_id_with_order_item'], 10, 4);
        add_action('woocommerce_checkout_order_processed', [$this, 'update_product'], 10, 3);
        //add_filter('lisfinity__submit_form_fields', [$this, 'alter_fields']);
        add_action( 'transition_post_status', [$this, 'trans_to_sold'], 10, 3 );
    }

    /**
     *
     *
     * @param $new_status
     * @param $old_status
     * @param $post
     * @return void
     */
    public function trans_to_sold( $new_status, $old_status, $post ) {
        if ( 'sold' === $new_status ) {
            // Your code here
            carbon_set_post_meta($post->ID, 'product-status', 'sold');
        }
    }

    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'product/store';

        register_rest_route($namespace, $route, array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'update_payment_package'],
            'permission_callback' => '__return_true',
        ));
    }

    public function custom_titles(\WP_REST_Request $request) {
    }
}
