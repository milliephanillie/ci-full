<?php
namespace ConcreteIron\Import;

use ConcreteIron\Includes\RapidProductSubmit;

class CreateOrder {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = 'create/order';

        register_rest_route($namespace, $route, [
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'create_order'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Create Order
     *
     * @return void
     */
    public function create_order(\WP_REST_Request $request) {
        $params = $request->get_params();

        if( ! $params['user_id'] || ! $params['package_id'] ) {
            return new \WP_Error(
                'create_order',
                'missing user id or package id'
            );
        }



        remove_action('woocommerce_checkout_order_processed', [RapidProductSubmit::class, 'update_product']);

        // Fetch user billing and shipping details.
        $user = get_userdata($params['user_id']);

        $billing_address = array(
            'first_name' => $this->get_user_meta_fallback($params['user_id'], 'billing_first_name', $user->first_name),
            'last_name'  => $this->get_user_meta_fallback($params['user_id'], 'billing_last_name', $user->last_name),
            'company'    => $this->get_user_meta_fallback($params['user_id'], 'billing_company'),
            'email'      => $user->user_email,
            'phone'      => $this->get_user_meta_fallback($params['user_id'], 'billing_phone'),
            'address_1'  => $this->get_user_meta_fallback($params['user_id'], 'billing_address_1'),
            'address_2'  => $this->get_user_meta_fallback($params['user_id'], 'billing_address_2'),
            'city'       => $this->get_user_meta_fallback($params['user_id'], 'billing_city'),
            'state'      => $this->get_user_meta_fallback($params['user_id'], 'billing_state'),
            'postcode'   => $this->get_user_meta_fallback($params['user_id'], 'billing_postcode'),
            'country'    => $this->get_user_meta_fallback($params['user_id'], 'billing_country', 'US'), // Default to US
        );

        $shipping_address = array(
            'first_name' => $this->get_user_meta_fallback($params['user_id'], 'shipping_first_name', $user->first_name),
            'last_name'  => $this->get_user_meta_fallback($params['user_id'], 'shipping_last_name', $user->last_name),
            'company'    => $this->get_user_meta_fallback($params['user_id'], 'shipping_company'),
            'address_1'  => $this->get_user_meta_fallback($params['user_id'], 'shipping_address_1'),
            'address_2'  => $this->get_user_meta_fallback($params['user_id'], 'shipping_address_2'),
            'city'       => $this->get_user_meta_fallback($params['user_id'], 'shipping_city'),
            'state'      => $this->get_user_meta_fallback($params['user_id'], 'shipping_state'),
            'postcode'   => $this->get_user_meta_fallback($params['user_id'], 'shipping_postcode'),
            'country'    => $this->get_user_meta_fallback($params['user_id'], 'shipping_country', 'US'), // Default to US
        );

        $order = wc_create_order(array(
            'status'        => apply_filters('woocommerce_default_order_status', 'pending'),
            'customer_id'   => $params['user_id'],
            'customer_note' => '',
        ));

        $product = wc_get_product($params['package_id']);
        $order->add_product($product, 1); // Add one quantity of the product, adjust as needed.

        $order->set_address($billing_address, 'billing');
        $order->set_address($shipping_address, 'shipping');

        $order->calculate_totals();
        $order->save();

        $order_id = $order->get_id();
        if (!empty($params['post_id'])) {  // Assuming $listing_id contains the ID of the listing
            update_post_meta($order_id, '_listing_id', $params['post_id']);
        }

        do_action('woocommerce_order_status_completed', $order->get_id());

        return rest_ensure_response(new \WP_REST_Response(
            [
                "order" => $order_id,
                "package_paid" => get_post_meta( $order_id, 'package_paid', true ),
                'promotion_processed' => get_post_meta( $order_id, 'promotion_processed', true )
            ]
        ));
    }

    // Helper function to safely retrieve user meta with a fallback.
    public function get_user_meta_fallback($user_id, $key, $default = '') {
        $value = get_user_meta($user_id, $key, true);
        return !empty($value) ? $value : $default;
    }
}