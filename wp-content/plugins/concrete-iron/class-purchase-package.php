<?php
require plugin_dir_path( CONCRETEIRON ) . 'Helpers/WCI_Helper.php';
require plugin_dir_path( CONCRETEIRON ) . 'Models/PackageModel.php';


class PurchasePackage {

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
        $route = 'purchase-package';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'purchase_package'],
            'args' => [],
        ));
    }

    /**
     * @param WP_REST_Request $request_data
     *
     * @return array
     * @throws \Exception
     */
    public function purchase_package( WP_REST_Request $request_data ) {
        global $woocommerce;

        $wc_helper = new WCI_Helper();
        $wc_helper->check_prerequisites();
        $data   = $request_data->get_params();
        $result = [];

        if ( empty( $data['wc_product'] ) ) {
            $result['error']   = true;
            $result['message'] = __( 'The WooCommerce product has not been set.', 'lisfinity-core' );
        }

        $sold_once = carbon_get_post_meta( $data['wc_product'], 'package-sold-once' );

        $user_id     = get_current_user_id();
        $to_checkout = true;
        if ( ! empty( $sold_once ) ) {
            $package_model = new PackageModel();
            $packages      = $package_model->where( [
                [ 'user_id', $user_id ],
                [ 'product_id', $data['wc_product'] ]
            ] )->get( '1', '', 'id', 'col' );

            if ( ! empty( $packages ) ) {
                $result['error']   = true;
                $result['message'] = __( 'This package type can be bought only once', 'lisfinity-core' );
            }

            if ( ! empty( $user_id ) ) {
                $to_checkout = false;
            }
        }

        if ( empty( $data['quantity'] ) ) {
            $data['quantity'] = 1;
        }

        if ( empty( $result['error'] ) ) {
            if ( $to_checkout ) {
                $cart_args = [ 'package_id' => $data['wc_product'] ];
                $discounts = carbon_get_post_meta( $data['wc_product'], 'subscription-discounts' );

                if ( empty( $discouns ) ) {
                    $discounts = carbon_get_post_meta( $data['wc_product'], 'package-discounts' );
                }

                if ( ! empty( $data['discount'] ) ) {
                    if ( ! empty( $discounts ) ) {
                        foreach ( $discounts as $index => $discount ) {
                            if ( $data['quantity'] >= $discount['duration'] ) {
                                $cart_args['discount'] = $discount['discount'];
                            }
                        }
                    }
                }

                $is_select = 'select' === carbon_get_post_meta( $data['wc_product'], 'package-discounts-type' );

                if ( $is_select && ! empty( $discounts ) ) {
                    foreach ( $discounts as $index => $discount ) {
                        if ( $data['quantity'] >= $discount['duration'] ) {
                            $cart_args['custom-price'] = $discount['discount'];
                        }
                    }
                }

                $cart_args['_listing_id'] = $data['product_id'];

                WC()->cart->empty_cart();
                WC()->cart->add_to_cart( $data['wc_product'], (int) $data['quantity'] ?? 1, '', '', $cart_args );

                $result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) ) . '?product_id=' . $data['product_id'];
            } else {
                $order = wc_create_order( [
                    'customer_id' => $user_id,
                ] );
                $order->add_product( wc_get_product( $data['wc_product'] ), (int) $data['quantity'] ?? 1, [
                    'package_id' => $data['wc_product'],
                ] );

                $address = [
                    'first_name'        => get_user_meta( $user_id, 'billing_first_name', true ) ?? '',
                    'last_name'         => get_user_meta( $user_id, 'billing_last_name', true ) ?? '',
                    'billing_company'   => get_user_meta( $user_id, 'billing_company', true ) ?? '',
                    'billing_address_1' => get_user_meta( $user_id, 'billing_address_1', true ) ?? '',
                    'billing_address_2' => get_user_meta( $user_id, 'billing_address_2', true ) ?? '',
                    'billing_city'      => get_user_meta( $user_id, 'billing_city', true ) ?? '',
                    'billing_state'     => get_user_meta( $user_id, 'billing_state', true ) ?? '',
                    'billing_postcode'  => get_user_meta( $user_id, 'billing_postcode', true ) ?? '',
                    'billing_phone'     => get_user_meta( $user_id, 'billing_phone', true ) ?? '',
                    'billing_email'     => get_user_meta( $user_id, 'billing_email', true ) ?? '',
                    'billing_country'   => get_user_meta( $user_id, 'billing_country', true ) ?? '',
                ];

                $order->set_address( $address );
                $order->update_status( 'completed', '', true );
                $result['order_id'] = $order->get_id();

                do_action( 'woocommerce_order_status_completed', $order->get_id() );
                $result['message'] = esc_html__( 'Free trial package successfully added!', 'lisfinity-core' );
            }

            $result['product_id'] = $data['product_id'];
            $result['success'] = true;
        }

        return $result;
    }
}

$pp = new PurchasePackage();
