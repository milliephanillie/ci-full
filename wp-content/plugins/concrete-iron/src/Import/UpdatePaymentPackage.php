<?php
/**
 * Updates the payment package via the WP REST API
 *
 *
 */
namespace ConcreteIron\Import;

use Lisfinity\Controllers\PackageController;

/*
 * Update Payment Package class
 */
class UpdatePaymentPackage
{
    /**
     * class constructor
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Boot the class
     *
     * @return void
     */
    public function boot()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register our routes
     *
     * @return void
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'update/payment-package';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_payment_package'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Update the payment package callback
     *
     * @param \WP_REST_Request $request
     * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function update_payment_package(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['user_email'] || !$params['payment_package_id']) {
            return new \WP_Error(
                'update_payment_package',
                'No payment package or post id or user_email'
            );
        }

        $user = get_user_by('email', $params['user_email']);
        $user_id = $user->ID;

        $order_id = $this->order_exists_with_listing_id($params['post_id']);
        if ($order_id == false) {
            // Order with this listing ID already exists. Don't create a new one.
            $order_id = $this->create_woo_order($user_id, $params['payment_package_id'], $params['post_id']);
        }

        /*
         * Check to see if there is a package in wp_lisfinity_packages already for a single post type. Since this is an import, there should only be one entry
         */
        $lisfintiy_package_id = 'not created';
        if( ! $this->order_id_exists_in_wp_lisfinity_packages($order_id)) {
            $lisfintiy_package_id = $this->update_lisfinity_packages($params['payment_package_id'], $params['post_id'], $order_id, $user_id, 90, 1);
        }

        $payment_package_update = update_post_meta($params['post_id'], 'payment-package', $lisfintiy_package_id);
        $payment_package_update = update_post_meta($params['post_id'], '_payment-package', $lisfintiy_package_id);
        $actual = get_post_meta($params['post_id'], 'payment-package');
        return rest_ensure_response(new \WP_REST_Response(
            [
                'payment_package_update' => $payment_package_update,
                'lisfintiy_package_id' => $lisfintiy_package_id,
                'actual' => $actual,
            ]
        ));
    }

    /**
     * Create an order with a _listing_id in the post meta. If and order with this _listing_id already exists, don't create it again, but return the ID
     *
     * @param $post_id
     * @return false|int
     */
    public function order_exists_with_listing_id($post_id) {
        $args = array(
            'post_type'   => 'shop_order',
            'post_status' => 'any',
            'meta_key'    => '_listing_id',
            'meta_value'  => $post_id,
            'numberposts' => 1,
        );

        $orders = get_posts($args);

        if (!empty($orders)) {
            return $orders[0]->ID;  // Return the ID of the first order found
        } else {
            return false;  // No order found with the given listing ID
        }
    }

    /**
     * Create a woocommerce order for a listing
     *
     * @param $user_id
     * @param $product_id
     * @return int
     */
    public function create_woo_order($user_id, $package_id, $listing_id) {
        if( ! $user_id || ! $package_id ) {
            return null;
        }

        $product_id = $package_id;

        // Fetch user billing and shipping details.
        $user_info = get_userdata($user_id);

        $billing_address = array(
            'first_name' => $this->get_user_meta_fallback($user_id, 'billing_first_name', $user_info->first_name),
            'last_name'  => $this->get_user_meta_fallback($user_id, 'billing_last_name', $user_info->last_name),
            'company'    => $this->get_user_meta_fallback($user_id, 'billing_company'),
            'email'      => $user_info->user_email,
            'phone'      => $this->get_user_meta_fallback($user_id, 'billing_phone'),
            'address_1'  => $this->get_user_meta_fallback($user_id, 'billing_address_1'),
            'address_2'  => $this->get_user_meta_fallback($user_id, 'billing_address_2'),
            'city'       => $this->get_user_meta_fallback($user_id, 'billing_city'),
            'state'      => $this->get_user_meta_fallback($user_id, 'billing_state'),
            'postcode'   => $this->get_user_meta_fallback($user_id, 'billing_postcode'),
            'country'    => $this->get_user_meta_fallback($user_id, 'billing_country', 'US'), // Default to US
        );

        $shipping_address = array(
            'first_name' => $this->get_user_meta_fallback($user_id, 'shipping_first_name', $user_info->first_name),
            'last_name'  => $this->get_user_meta_fallback($user_id, 'shipping_last_name', $user_info->last_name),
            'company'    => $this->get_user_meta_fallback($user_id, 'shipping_company'),
            'address_1'  => $this->get_user_meta_fallback($user_id, 'shipping_address_1'),
            'address_2'  => $this->get_user_meta_fallback($user_id, 'shipping_address_2'),
            'city'       => $this->get_user_meta_fallback($user_id, 'shipping_city'),
            'state'      => $this->get_user_meta_fallback($user_id, 'shipping_state'),
            'postcode'   => $this->get_user_meta_fallback($user_id, 'shipping_postcode'),
            'country'    => $this->get_user_meta_fallback($user_id, 'shipping_country', 'US'), // Default to US
        );

        $order = wc_create_order(array(
            'status'        => apply_filters('woocommerce_default_order_status', 'pending'),
            'customer_id'   => $user_id,
            'customer_note' => '',
        ));

        $product = wc_get_product($product_id);
        $order->add_product($product, 1); // Add one quantity of the product, adjust as needed.

        $order->set_address($billing_address, 'billing');
        $order->set_address($shipping_address, 'shipping');

        $order->calculate_totals();
        $order->save();

        $order_id = $order->get_id();
        if (!empty($post_id)) {  // Assuming $listing_id contains the ID of the listing
            update_post_meta($order_id, '_listing_id', $post_id);
        }
        return $order_id;
    }

    // Helper function to safely retrieve user meta with a fallback.
    public function get_user_meta_fallback($user_id, $key, $default = '') {
        $value = get_user_meta($user_id, $key, true);
        return !empty($value) ? $value : $default;
    }

    /**
     * Update the Lisfinity packages
     *
     * @param int $payment_package_id - the id column in wp_lisfinity_packages
     * @param int $listing_id - the id of the actual post or listing
     * @param int $order_id - the id of the order
     * @param string $user_id - the id of the user
     * @param int $products_duration - the products duration
     * @param int $products_limit - the products limit
     * @return bool|mixed|string|void
     */
    public function update_lisfinity_packages(int $payment_package_id, int $listing_id, int $order_id, string $user_id, int $products_duration = 90, int $products_limit = 1)
    {
        $customer_id = get_current_user_id();

        $values = [
            // id of the customer that made order.
            $user_id,
            // wc product id of this item.
            $payment_package_id,
            // wc order id for this item.
            $order_id,
            // limit amount of products in a package.
            carbon_get_post_meta($payment_package_id, 'package-products-limit') ?? 1,
            // current amount of submitted products in this package. (this should be only 1 for each post for new setup
            1,
            // duration of the submitted products.
            $products_duration,
            // type of the package.
            'payment_package',
            // status of the package.
            'active',
        ];

        $package_controller = new PackageController();

        $lisfinity_package_id = $package_controller->store($values);

        if (!empty($lisfinity_package_id)) {
            $promotions = carbon_get_post_meta($payment_package_id, 'package-free-promotions');

            if (!empty($promotions)) {
                $this->insert_promotions($lisfinity_package_id, $payment_package_id, $listing_id, $customer_id, $promotions, $order_id, $products_duration);
            }
        }

        return $lisfinity_package_id;
    }

    /**
     * Check if the product exists in the table
     *
     * @param $product_id
     * @return bool
     */
    public function order_id_exists_in_wp_lisfinity_packages($order_id) {
        global $wpdb;

        // Your custom table name
        $table_name = $wpdb->prefix . 'lisfinity_packages';

        $query = $wpdb->prepare("SELECT EXISTS (SELECT 1 FROM $table_name WHERE order_id = %d LIMIT 1)", $order_id);

        // Execute the query. If the order ID exists, $result will be 1 (TRUE), otherwise it will be NULL.
        $result = $wpdb->get_var($query);

        return $result ? true : false;
    }
}