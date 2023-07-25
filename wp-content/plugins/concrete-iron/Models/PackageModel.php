<?php
/**
 * Model for our custom WooCommerce product type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

include_once plugin_dir_path( CONCRETEIRON ) . 'Abstracts/Model.php';

/**
 * Class PackageModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class PackageModel extends Model {

    protected $table = 'packages';

    /**
     * Set the fields for the table
     * ----------------------------
     *
     * @return array
     */
    protected function set_table_fields() {
        $this->fields = [
            'user_id'           => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'product_id'        => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'order_id'          => [
                'type'  => 'bigint(20)',
                'value' => '0',
            ],
            'products_limit'    => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'products_count'    => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'products_duration' => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'type'              => [
                'type'  => 'varchar(100)',
                'value' => 'NULL',
            ],
            'status'            => [
                'type'  => 'varchar(100)',
                'value' => 'NULL',
            ],
        ];

        return $this->fields;
    }

    /**
     * Get all packages related to a given user
     * ----------------------------------------
     *
     * @param $user_id
     *
     * @return array|mixed|object|string|void|null
     */
    public function get_user_packages( $user_id ) {
        $package_model = new PackageModel();
        $packages      = $package_model->where( 'user_id', $user_id )->get();

        return $packages;
    }

    /**
     * Add package to cart and proceed to checkout
     * -------------------------------------------
     */
    public function add_to_cart() {
        if ( isset( $_GET['lc-cart'] ) ) {
            WC()->cart->empty_cart();
            $key = WC()->cart->add_to_cart( $_GET['lc-cart'] );

            if ( ! $key ) {
                return __( 'Package could not be added to the cart.', 'lisfinity-core' );
            }

            $permalink = get_permalink( wc_get_page_id( 'checkout' ) );

            if ( wp_safe_redirect( $permalink ) ) {
                exit;
            }
        }
    }

    /**
     * Get product packages query
     * --------------------------
     *
     * @param array $type
     *
     * @return \WP_Query
     */
    public function get_packages_query( $type = [] ) {
        if ( empty( $type ) ) {
            $type = \WC_Product_Payment_Package::$type;
        }
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => - 1,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'name',
                    'terms'    => $type,
                    'operator' => 'IN',
                ],
            ],
        ];

        $products = new \WP_Query( $args );

        return $products;
    }

    /**
     * Format products query to be used in select field
     * ------------------------------------------------
     *
     * @return array
     */
    public function format_packages_select() {
        $select   = [];
        $products = $this->get_packages_query();

        if ( $products->have_posts() ) {
            foreach ( $products->posts as $product ) {
                $select[ $product->ID ] = $product->post_title;
            }
        }

        return $select;
    }
}
