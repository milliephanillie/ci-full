<?php
namespace ConcreteIron\Import;

class CreatePost {
    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = 'create/post';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'create_post'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    public function create_post(\WP_REST_Request $request) {
        $params = $request->get_params();

        $user = $this->validateUser($params['username'], '');

        $title = $this->generate_title(null, $params['year'], $params['make'], $params['model'], $params['condition']);

        $post_id = $this->update_post(null, $title, $user->ID);

        return rest_ensure_response(new \WP_REST_Response(
            [
                'id' => $post_id,
                'title' => $title,
                'user' => $user
            ]
        ));
    }

    /**
     * Get the user in a multitude of ways
     *
     * @param $username
     * @param $alternate_contact_name
     * @return void|\WP_User
     */
    public function validateUser($username, $alternate_contact_name) {
        $user = get_user_by_email($username);
        if (!$user) {
            $user = get_user_by('login', $username);
            if(!$user) {
                return;
            }
        }
        return $user;
    }

    public function generate_title($title, $year, $make, $model, $condition) {
        if (empty($title)) {
            $title_empty = 'true';

            // Try with all four
            if (!empty($year) && !empty($make) && !empty($model) && !empty($condition)) {
                $title = "$year $make $model $condition";
            }
            // Try with year, make, model
            elseif (!empty($year) && !empty($make) && !empty($model)) {
                $title = "$year $make $model";
            }
            // Try with year, make
            elseif (!empty($year) && !empty($make)) {
                $title = "$year $make";
            }
            // Try with year, model
            elseif (!empty($year) && !empty($model)) {
                $title = "$year $model";
            }
            // Try with make, model
            elseif (!empty($make) && !empty($model)) {
                $title = "$make $model";
            }
            // Try with just make
            elseif (!empty($make)) {
                $title = "$make";
            }
            // Try with just model
            elseif (!empty($model)) {
                $title = "$model";
            }
        }

        return $title;
    }

    public function update_post($import_id = '', $title, $user_id) {
        $args = [
            'post_type' => 'product',
            'post_title' => wp_strip_all_tags($title),
            'post_name' => sanitize_title(wp_strip_all_tags($title)),
            'post_content' => '',
            'post_author' => $user_id,
            "post_status" => 'publish',
        ];

        $post_id = wp_insert_post($args);

        return $post_id;
    }
}

$createPost = new CreatePost();