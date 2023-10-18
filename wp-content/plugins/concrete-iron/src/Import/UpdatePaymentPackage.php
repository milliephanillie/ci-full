<?php
namespace ConcreteIron\Import;

class UpdatePaymentPackage
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

    public function update_payment_package(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['payment_package_id']) {
            return new \WP_Error(
                'update_payment_package',
                'No payment package or post id'
            );
        }

        $payment_package_update = update_post_meta($params['post_id'], '_payment-package', $params['payment_package_id']);
        $actual = get_post_meta($params['post_id'], '_payment-package', $params['payment_package_id']);
        return rest_ensure_response(new \WP_REST_Response(
            [
                'payment_package_update' => $payment_package_update,
                'actual' => $actual,
            ]
        ));
    }
}