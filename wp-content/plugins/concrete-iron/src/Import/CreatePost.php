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

        $route = 'create/post/status';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'create_post_status'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);

        $route = 'update/product-type';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_product_type_from_rest'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);

        $route = 'update/expiration';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_expiration'
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

        $this->update_product_type($post_id);

        return rest_ensure_response(new \WP_REST_Response(
            [
                'id' => $post_id,
                'title' => $title,
                'user' => $user
            ]
        ));
    }

    public function create_post_status(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['status'] || !$params['post_id']) {
            return new \WP_Error(
                'update_status',
                'missing status param or post id'
            );
        }

        $updated_status = update_post_meta($params['post_id'], '_product-status', $params['status']);

        return rest_ensure_response(new \WP_REST_Response(
            [
                'updated_status' => $updated_status
            ]
        ));
    }


    public function update_product_type_from_rest(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id']) {
            return new \WP_Error(
                'update_product_type',
                'missing post id'
            );
        }

        $this->update_product_type($params['post_id']);

        return rest_ensure_response(new \WP_REST_Response(
            [
                'message' => "success?"
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

    /**
     * Update the WooCommerce product type
     *
     * @param $post_id
     * @return void
     */
    public function update_product_type($post_id) {
        $product = wc_get_product($post_id);
        $product_id = $product->get_id();
        $product_classname = \WC_Product_Factory::get_product_classname( $product_id, 'listing' );
        $new_product       = new $product_classname( $product_id );
        $new_product->save();
    }

    public function update_expiration(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['date'] || !$params['post_id']) {
            return new \WP_Error(
                'update_status',
                'missing date param or post id'
            );
        }

        $use_current_date_for_expiration = true; // Set to true if needed

        $current_time = current_time('timestamp');
        $active_date = strtotime($params['date']); // Active date is the given date

        if ($use_current_date_for_expiration) {
            $expired_date = strtotime('+90 days', $current_time); // Expired date is 90 days from the current time
        } else {
            $expired_date = strtotime('+90 days', $active_date); // Expired date is 90 days from the given date
        }

        $updated_listed = update_post_meta($params['post_id'], '_product-listed', $active_date);
        $updated_expired = update_post_meta($params['post_id'], '_product-expiration', $expired_date);

        return rest_ensure_response(new \WP_REST_Response(
            [
                'update_listed' => $updated_listed,
                'updated_expired' => $updated_expired,
            ]
        ));
    }
}

$createPost = new CreatePost();