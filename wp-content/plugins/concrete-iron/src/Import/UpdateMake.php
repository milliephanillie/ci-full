<?php

namespace ConcreteIron\Import;

class UpdateMake
{
    public function __construct()
    {
        $this->boot();
    }

    public function boot()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'update/make';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_make'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    public function update_make(\WP_REST_Request $request) {
        $params = $request->get_params();


    }
}
