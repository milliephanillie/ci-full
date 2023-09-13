<?php
namespace ConcreteIron\Includes;

class RapidRenew {
    /**
     * RapidRenew constructor
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
    }

    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'rapid_renew';

        register_rest_route($namespace, $route, array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'rapid_renew'],
        ));
    }

    public function rapid_renew(\WP_REST_Request $request) {
        global $wpdb;

        if ( defined( 'WC_ABSPATH' ) ) {
            // WC 3.6+ - Cart and notice functions are not included during a REST request.
            include_once plugin_dir_path( CONCRETEIRON ) . 'Helpers/WCI_Helper.php';
            include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
            include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
        }

        $wc_helper = new \WCI_Helper();
        $wc_helper->check_prerequisites();

        $params = $request->get_params();
        $product_id = $params['product_id'] ?? null;
        $package_id = $params['package_id'] ?? null;
        $exact_package = $params['exact_package'] ? boolval($params['exact_package']) : true;
        $result = [];



        if( ! $product_id ) {
            return rest_ensure_response(new \WP_REST_Response([
                "error" => $product_id,
            ]));
        }

       if(empty($exact_package)) {
           $payment_package_id = get_post_meta($product_id, '_payment-package', true);

           $query = $wpdb->prepare(
               "SELECT product_id FROM {$wpdb->prefix}lisfinity_packages WHERE id = %d",
               $payment_package_id
           );

           $package_id = $wpdb->get_var($query);
       }



        if ( ! $payment_package_id ) {
            return rest_ensure_response(new \WP_REST_Response([
                "error" => "The product doesn't have an associated payment_package_id",
            ]));
        }

        $cart_args = [ 'package_id' => $package_id ];
        $cart_args['wc_product'] = $package_id;
        $cart_args['_listing_id'] = $product_id;


        WC()->cart->empty_cart();
        WC()->cart->add_to_cart( $package_id, 1, '', '', $cart_args );

        $result['sucess'] = true;
        $result['exact_package'] = $exact_package;
        $result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) ) . '?product_id=' . $product_id;
        $result['payment_package_id'] = $payment_package_id;
        $result['_listing_id'] = $product_id;

        return rest_ensure_response(new \WP_REST_Response($result));
    }
}