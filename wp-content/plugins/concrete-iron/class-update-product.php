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
            'permission_callback' => '__return_true',
        ));

        $route = 'force_dates';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'force_dates'],
            'args' => [],
            'permission_callback' => '__return_true',
        ));

        $route = 'force_post_status';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'force_post_status'],
            'args' => [],
            'permission_callback' => '__return_true',
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

    /**
     * Force a certain date
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function force_dates(\WP_REST_Request $request) {
        $params = $request->get_params();
        $product_id = $params['product_id'];
        $days_expired = isset($params['days_expired']) ? (int) $params['days_expired'] : null;
        $days_activated = isset($params['days_activated']) ? (int) $params['days_activated'] : null;

        if(!$product_id || ! $days_expired || ! $days_activated ) {
            return new WP_Error('missing_parameters', 'Required parameters are missing or invalid.');
        }

        $updated_activation = update_post_meta($product_id, '_product-listed', strtotime($days_activated.' days', (int)current_time('timestamp')));
        $updated_expiration = update_post_meta($product_id, '_product-expiration', strtotime($days_expired.' days', (int)current_time('timestamp')));

        return rest_ensure_response(new WP_REST_Response([
            "updated_activation" => $updated_activation,
            "updated_expiration" => $updated_expiration
        ]));
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function force_post_status(\WP_REST_Request $request) {
        $params = $request->get_params();
        $product_ids = isset($params['product_ids']) ? (array) $params['product_ids'] : [];
        $status = $params['status'];

        // Ensure that the necessary parameters are set
        if (empty($product_ids) || !$status) {
            return new WP_Error('missing_parameter', 'Required parameters are missing', array('status' => 400));
        }

        // If the status is not a valid post status
        if (!in_array($status, get_post_stati())) {
            return new WP_Error('invalid_status', 'Invalid status provided', array('status' => 400));
        }

        $results = [];

        // Loop through each product ID and update its status
        foreach ($product_ids as $product_id) {
            $post = get_post($product_id);

            // If the post doesn't exist
            if (!$post) {
                $results[$product_id] = 'Invalid product ID';
                continue;
            }

            // Update the post status
            $updated = wp_update_post(array(
                'ID' => $product_id,
                'post_status' => $status,
            ), true);

            // If there was an error in the update process
            if (is_wp_error($updated)) {
                $results[$product_id] = $updated->get_error_message();
                continue;
            }

            $results[$product_id] = 'Updated';
        }

        // Return the results of the update
        return rest_ensure_response(new WP_REST_Response($results));
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
