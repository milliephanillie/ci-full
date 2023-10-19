<?php
/**
 * Updates the year via the WP REST API
 *
 *
 */
namespace ConcreteIron\Import;

/*
 * Update Year class
 */
class UpdateCondition
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
        $route = 'update/condition';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_condition'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Update the price
     *
     * @param \WP_REST_Request $request
     * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function update_condition(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['condition']) {
            return new \WP_Error(
                'update_condition',
                'No condition or post id'
            );
        }

        $update = wp_set_object_terms($params['post_id'], $params['year'], 'concrete-equipment-condition',  false);
        return rest_ensure_response(new \WP_REST_Response(
            [
                'condition_update' => $update,
            ]
        ));
    }
}