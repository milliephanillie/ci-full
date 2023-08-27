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
//        $route = 'update_product';
//
//        register_rest_route($namespace, $route, array(
//            'methods' => WP_REST_Server::CREATABLE,
//            'callback' => [$this, 'update_product'],
//            'args' => [],
//        ));

        $route = 'force_expired';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'force_expired'],
            'args' => [],
        ));
    }

    /**
     * Force a listing to be expired for testing purposes
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function force_expired(\WP_REST_Request $request) {
        $params = $request->get_params();
        $product_id = $params['product_id'];

        $updated_activation = update_post_meta($product_id, '_product-listed', strtotime('-3 days', (int)current_time('timestamp')));
        $updated_expiration = update_post_meta($product_id, '_product-expiration', strtotime('-2 days', (int)current_time('timestamp')));

        return rest_ensure_response(new WP_REST_Response([
            "updated_activation" => $updated_activation,
            "updated_expiration" => $updated_expiration
        ]));
    }

//    public function update_product(\WP_REST_Request $request) {
//        $params = $request->get_params();
//        $product_id = $params['product_id'];
//        $product_status = $params['product_status'];
//        $expiration_date = $params['expiration_date'];
//
//        $site_timestamp = current_time('timestamp');
//
//        $gmt_offset = get_option('gmt_offset');
//
//        if (!is_numeric($gmt_offset)) {
//            error_log("Invalid gmt_offset value: ", print_r($gmt_offset, true));
//        } else {
//            $utc_timestamp = $site_timestamp - $gmt_offset * 3600;
//            $activation_date = $site_timestamp;
//            $expiration_date = date('Y-m-d H:i:s', strtotime("+7 days", $utc_timestamp));
//        }
//
//        $updated_activation = update_post_meta($product_id, '_product-listed', $activation_date);
//        $updated_expiration = update_post_meta($product_id, '_product-expiration', strtotime('+2 days', (int)current_time('timestamp')));
//
//        $post = get_post($product_id);
//
//
//        $old_product_status = get_post_meta($product_id, '_product-status');
//
//
//        $product_status_update = update_post_meta($product_id, '_product-status', $product_status);
//
//        $product_status = get_post_meta($product_id, '_product-status');
//
//        $data = [
//            'old' => $old_product_status,
//            'update' => $product_status_update,
//            'new' => $product_status,
//        ];
//
//        return rest_ensure_response(new WP_REST_Response($data));
//    }
}

$update_package = new Update_Product();
