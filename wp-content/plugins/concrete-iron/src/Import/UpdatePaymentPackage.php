<?php
/**
 * Updates the payment package via the WP REST API
 *
 *
 */
namespace ConcreteIron\Import;

use Lisfinity\Models\PackageModel;

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

        if(!$params['post_id'] || !$params['payment_package_id']) {
            return new \WP_Error(
                'update_payment_package',
                'No payment package or post id'
            );
        }

        $payment_package_update = update_post_meta($params['post_id'], 'payment-package', $params['payment_package_id']);
        $actual = get_post_meta($params['post_id'], 'payment-package', $params['payment_package_id']);
        return rest_ensure_response(new \WP_REST_Response(
            [
                'payment_package_update' => $payment_package_update,
                'actual' => $actual,
            ]
        ));
    }
}