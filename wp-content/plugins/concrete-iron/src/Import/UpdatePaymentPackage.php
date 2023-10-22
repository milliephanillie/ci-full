<?php
/**
 * Updates the payment package via the WP REST API
 *
 *
 */
namespace ConcreteIron\Import;

use Lisfinity\Controllers\PackageController;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\PackageModel;

use ConcreteIron\Includes\RapidProductSubmit;

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

        $route = 'get/payment-package';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::READABLE],
            'callback' => [
                $this,
                'get_payment_package'
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

        if(!$params['order_id'] || !$params['package_id'] || !$params['post_id']) {
            return new \WP_Error(
                'update_payment_package',
                'No orderID or package id or post id'
            );
        }

        $package_model = new PackageModel();
        $existing_package = $package_model->where( 'order_id', $params['order_id'] )->get( '1', '', 'id', 'col' );

        if(empty($existing_package)) {
            return new \WP_Error(
                'update_payment_package',
                'There is no payment package with that order ID'
            );
        }

        $promotions_model = new PromotionsModel();
        $promotions_model->where('order_id', $params['order_id'])->destroy();

        $payment_package_id = $existing_package[0];
        $promotions = null;

        if (!empty($payment_package_id)) {
            $package_model->set( 'product_id', $params['package_id'] )->where( 'id', $payment_package_id )->update();
            $promotions = carbon_get_post_meta($params['package_id'], 'package-free-promotions');
        }


        $addons = carbon_get_post_meta($params['package_id'],'package-promotions');

        $promotions_inserted = null;
        if($promotions) {
            $promotions_inserted = $this->insert_promotions($payment_package_id, $params['package_id'], $params['post_id'], $params['user_id'], $promotions, $params['order_id'], $duration = 30);
        }

        $addons_inserted = null;
        if($addons) {
            $addons_inserted = $this->insert_addons($payment_package_id, $params['package_id'], $params['order_id'], $params['user_id'], $params['post_id']);
        }

        //delete_post_meta($params['post_id'], '_payment-package');
        update_post_meta($params['post_id'], '_payment-package', $payment_package_id);
        $actual = get_post_meta($params['post_id'], '_payment-package');
        return rest_ensure_response(new \WP_REST_Response(
            [
                'post_id' => $params['post_id'],
                'lisfinty_package_id' => $payment_package_id,
                'actual' => $actual,
                'update_order' => $this->update_order($params['order_id'], $params['package_id']),
                'promotions' => $promotions,
                'promotions_inserted' => $promotions_inserted,
                'addons_inserted' => $addons_inserted,
            ]
        ));
    }

    /**
     * @param $lisfinity_package_id
     * @param $package_id
     * @param $listing_id
     * @param $user_id
     * @param $promotions
     * @param $order_id
     * @param $products_duration
     * @return array
     */
    public function insert_promotions($lisfinity_package_id, $package_id, $listing_id, $user_id, $promotions, $order_id, $products_duration = 30){
        $promotion_model = new PromotionsModel();
        $duration = carbon_get_post_meta($package_id, 'package-products-duration') ?? $products_duration;
        $expiration_date = date('Y-m-d H:i:s', strtotime("+ {$duration} days", current_time('timestamp')));
        $model = new PromotionsModel();
        $promotions_inserted = [];

        if (!empty($promotions)) {
            foreach ($promotions as $promotion) {
                $promotion_object = $model->get_promotion_product($promotion);
                $promotion_product_id = $promotion_object[0]->ID;
                $promotions_values = [
                    // payment package id.
                    $lisfinity_package_id ?? 0,
                    // wc order id.
                    $order_id ?? 0,
                    // wc product id, id of this WooCommerce product.
                    $promotion_product_id,
                    // id of the user that made order.
                    $user_id,
                    // id of the product that this promotion has been activated.
                    $listing_id,
                    // limit or duration number depending on the type of the promotion.
                    $products_duration,
                    // count of addon promotions, this cannot be higher than value.
                    0,
                    // position of promotion on the site.
                    $promotion,
                    // type of the promotion.
                    'product',
                    // status of the promotion
                    'active',
                    // activation date of the promotion
                    current_time('mysql'),
                    // expiration date of the promotion if needed.
                    $expiration_date,
                ];



                // save promotion data in the database.
                $promotion_model->store($promotions_values);

                array_push($promotions_inserted, $promotions_values);
            }
        }

        return $promotions_inserted;
    }

    /**
     * @param $package_id
     * @param $order_id
     * @param $customer_id
     * @return array
     */
    public function insert_addons($payment_package_id, $package_id, $order_id, $customer_id, $post_id) {
        // store promotions if there's any
        $addons_inserted = [];
        $promotions = carbon_get_post_meta( $package_id, "package-promotions" );

        if ( ! empty( $promotions ) ) {
            $promotion_model = new PromotionsModel();
            foreach ( $promotions as $promotion ) {
                $promotion_type     = ! empty( $promotion['_type'] ) ? $promotion['_type'] : '';
                $promotion_position = ! empty( $promotion['package-promotions-product'] ) ? $promotion['package-promotions-product'] : '';
                $promotion_value    = ! empty( $promotion['package-promotions-product-value'] ) ? $promotion['package-promotions-product-value'] : '';

                // prepare wp_query args.
                // todo currently is only querying addons.
                $args                   = [];
                $args['meta_query'][]   = [
                    'key'     => 'promotion-addon-type',
                    'value'   => $promotion_position,
                    'compare' => '=',
                ];
                $args['fields']         = 'ids';
                $args['posts_per_page'] = 1;
                $wc_product_id          = $promotion_model->get_promotion_products( 'promotion', 'addon', $args );
                // bail if there is no WooCommerce product set or promotions is not an addon.
                // todo remove addon check when we provide a functionality for it.
                if ( ! empty( $wc_product_id ) && false !== strpos( $promotion_position, 'addon' ) ) {
                    $promotions_values = [
                        // payment package id.
                        $payment_package_id,
                        // wc order id.
                        $order_id,
                        // wc product id, id of this WooCommerce product.
                        $wc_product_id[0],
                        // id of the user that made order.
                        $customer_id,
                        // id of the product that this promotion has been activated.
                        $post_id,
                        // limit or duration number depending on the type of the promotion.
                        $promotion_value,
                        // count of addon promotions, this cannot be higher than value.
                        0,
                        // position of promotion on the site.
                        $promotion_position,
                        // type of the promotion.
                        $promotion_type,
                        // status of the promotion
                        'active',
                        // activation date of the promotion
                        '',
                        // expiration date of the promotion if needed.
                        '',
                    ];

                    // save promotion data in the database.
                    $promotion_model->store( $promotions_values );

                    array_push($addons_inserted, $promotions_values);
                }
            }
        }

        return $addons_inserted;
    }

    public function get_payment_package(\WP_REST_Request $request) {
        $params = $request->get_params();
        if(!$params['post_id']) {
            new \WP_Error(
                'get_payment_package',
                'missing post id'
            );
        }

        $payment_package_id = get_post_meta($params['post_id'], '_payment-package') ?? null;

        if(!$payment_package_id) {
            return new \WP_Error(
                'get_payment_package',
                'There is no payment package for that given post id ' .$params['post_id']
            );
        }

        return new \WP_REST_Response(
            [
                'payment_package_id' => $payment_package_id
            ]
        );
    }

    public function update_order($order_id, $package_id) {
        if($order_id || $package_id) {
            return new \WP_Error(
                'update_order',
                'missing order or package id'
            );
        }

        $order = wc_get_order($order_id);

        foreach ($order->get_items() as $item_id => $item) {
            $order->remove_item($item_id);
        }

        $product = wc_get_product($package_id);
        $order->add_product($product, 1); // Add one quantity of the product

        $order->calculate_totals();
        $order->save();

        return array(
            'status' => 'success',
            'message' => 'Order items successfully updated!',
            'order_id' => $order_id
        );
    }
}