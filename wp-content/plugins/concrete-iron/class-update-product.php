<?php


class Update_Product {
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
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'update_product';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'update_product'],
            'args' => [],
        ));
    }

    public function update_product(\WP_REST_Request $request) {
        $params = $request->get_params();
        $product_id = $params['product_id'];
        $product_status = $params['product_status'];

        $post = get_post($product_id);


        $old_product_status = get_post_meta($product_id, '_product-status');


        $product_status_update = update_post_meta($product_id, '_product-status', $product_status);

        $product_status = get_post_meta($product_id, '_product-status');

        $data = [
            'old' => $old_product_status,
            'update' => $product_status_update,
            'new' => $product_status,
        ];

        return rest_ensure_response(new WP_REST_Response($data));
    }
}

$update_package = new Update_Product();