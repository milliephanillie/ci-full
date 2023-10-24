<?php
namespace ConcreteIron\Import;

/**
 * Get the user
 */
class GetUser {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = 'get/user-id';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::READABLE],
            'callback' => [$this, 'get_user_id'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function get_user_id(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['seller_title']) {
          return new \WP_Error(
              'get_user_id',
              'The seller_title is missing.'
          );
        }

        $query = new \WP_Query(
            [
                'post_type' => 'premium_profile',
                'title' => $params['seller_title'],
            ]
        );

        if (!$query->found_posts) {
            return new \WP_Error(
                'get_user_id',
                'There are no found posts with that seller_title'
            );
        }

        $post = $query->posts[0];

        return rest_ensure_response(new \WP_REST_Response(
            [
                "user_id" => $post->post_author,
                "business_profile" => $post,
            ]
        ));
    }
}