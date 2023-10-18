<?php

namespace ConcreteIron\Import;

class UserImport {
    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = 'import/user';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_user'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    public function update_user(\WP_REST_Request $request) {
        $params = $request->get_params();
        $post_id = $params['post_id'];
        $user_email = $params['email'];

        $user = get_user_by('email', $user_email);

        $business_owner = 'Seller: ' . $user->user_login;
        $business_id = $this->get_post_id_by_title($business_owner, 'premium_profile');

        if($user) {
            $update_user = update_post_meta($post_id, '_product-agent', $user->ID);
        }

        if($business_id) {
            $update_business_owner = update_post_meta($post_id, '_product-business', $business_id);
        }

        $user_phone = $this->get_user_phone($user->ID);
        if ($user_phone) {
            $phone_update = update_post_meta($business_id, '_profile-phones|profile-phone|0|0|value', $user_phone);
        }

        return rest_ensure_response(new \WP_REST_Response(
            [
                'user_updates' => $update_user,
                'update_business_owner' => $update_business_owner,
                'phone_update' => $phone_update,
            ]
        ));
    }

    function get_post_id_by_title($title, $post_type = 'page') {
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'name' => $title,
            'posts_per_page' => 1,
            'fields' => 'ids' // Only fetch IDs
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            return $query->posts[0];  // Return the post ID
        } else {
            return null;  // Or false, or any other preferred return value
        }
    }

    /**
     * Get the user phone number
     *
     * @param $user_id
     * @return mixed
     */
    public function get_user_phone($user_id) {
        $shipping_phone = get_user_meta($user_id, 'shipping_phone', true);
        $billing_phone = get_user_meta($user_id, 'billing_phone', true);
        return $shipping_phone ?? $billing_phone;
    }

}