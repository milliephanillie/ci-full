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
class UpdateYear
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
        $route = 'update/year';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_year'
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
    public function update_year(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['year']) {
            return new \WP_Error(
                'update_price',
                'No year or post id'
            );
        }

        $year_update = wp_set_object_terms($params['post_id'], $params['year'], 'concrete-equipment-year',  false);
        return rest_ensure_response(new \WP_REST_Response(
            [
                'year_update' => $year_update,
            ]
        ));
    }
}