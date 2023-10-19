<?php
/**
 * Updates the price via the WP REST API
 *
 *
 */
namespace ConcreteIron\Import;

/*
 * Update Payment Package class
 */
class UpdatePrice
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
        $route = 'update/price';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_price'
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
    public function update_price(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['price']) {
            return new \WP_Error(
                'update_price',
                'No price or post id'
            );
        }

        $price = $this->convertCurrencyToNumber($params['price']);
        $price_update = update_post_meta($params['post_id'], '_price', $price);
        $reg_price_update = update_post_meta($params['post_id'], '_regular_price', $price);
        $actual = get_post_meta($params['post_id'], '_price');
        return rest_ensure_response(new \WP_REST_Response(
            [
                'price_update' => $price_update,
                'reg_price_update' => $reg_price_update,
                'actual' => $actual,
            ]
        ));
    }

    /**
     * Convert something like $49,876 to something like 49876.00
     *
     * @param string $currencyString
     * @return float|string
     */
    public function convertCurrencyToNumber(string $currencyString) {
        // Remove the dollar sign and commas
        $numberString = str_replace(['$', ','], '', $currencyString);

        // Convert the cleaned string to a float
        $number = (float) $numberString;

        // Check if the number is an integer, and format accordingly
        if (floor($number) == $number) {
            return number_format($number, 2, '.', '');
        } else {
            return $number;
        }
    }
}