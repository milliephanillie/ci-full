<?php


class Update_Package {
    /**
     * CustomImport constructor
     */
    public function __construct() {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot() {
        add_filter('https_ssl_verify', '__return_false');
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }


    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'ci_update_package';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'ci_update_package'],
            'args' => [],
        ));
    }

    public function ci_update_package(\WP_REST_Request $request) {
        var_dump($request->get_params());
        die();
    }
}

$update_package = new Update_Package();
