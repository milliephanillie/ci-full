<?php

namespace ConcreteIron\Import;

class UserImport {
    public function __construct() {
        $this->boot();
    }

    public function boot() {
        $this->register_routes();
    }

    public function register_routes() {
        $namespace = 'ci';
        $route = 'import/user';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_user'
            ],
            'permission_callback' => '__return_true',
        ]);
    }

    public function update_user(\WP_REST_Request $request) {
        $params = $request->get_params();
        $post_id = $params['post_id'];
        $user_email = $params['email'];

        $user = get_user_by('user_email', $user_email);

        $update_user = update_post_meta($post_id, '_product-agent', $user->ID);

        return rest_ensure_response(new WP_REST_Request(
            [
                'user_updates' => $update_user,
            ]
        ));
    }
}