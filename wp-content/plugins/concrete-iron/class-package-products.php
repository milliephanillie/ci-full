<?php

require_once( ABSPATH . '/wp-content/plugins/lisfinity-core/includes/src/Models/Forms/FormSubmitModel.php');


class Package_Products
{
    protected $table_prefix = 'lisfinity_';

    protected $table = 'promotions';

    protected $query;

    /**
     * The fields inserted into the table
     * ----------------------------------
     *
     * @var array
     */
    protected $fields;

    /**
     * CustomImport constructor
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot()
    {
        $this->set_table_fields();
        $this->table();
        add_filter('https_ssl_verify', '__return_false');
        add_action('rest_api_init', [$this, 'register_routes']);
        //add_filter('lisfinity__submit_form_fields', [$this, 'alter_fields']);
    }

    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'get_packages';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'get_packages'],
            'permission_callback' => '__return_true',
        ));

        $namespace = 'ci/v1';
        $route = 'get_single_package';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'get_single_package'],
            'permission_callback' => '__return_true',
        ));
    }

    public function alter_fields($fields) {
        $packages = new WP_Query(
            [
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'product_type',
                        'field' => 'name',
                        'terms' => 'payment_package',
                        'operator' => 'IN',
                    ],
                ],
            ]

        );

        foreach ($packages->posts as $post) {
            $promotions = carbon_get_post_meta($post->ID, "package-promotions");

            $types= [];
            $types["image"] = [
                "free" => 0,
            ];

            foreach ($promotions as $promotion) {
                if (array_key_exists('package-promotions-product', $promotion) && $promotion['package-promotions-product'] === 'addon-image') {
                    $types["image"]["free"] += ( array_key_exists('package-promotions-product-value', $promotion) && ! empty($promotion['package-promotions-product-value']) ) ? $promotion['package-promotions-product-value'] : $types["image"]["free"];
                }
            }

            foreach ($fields as $field_key => $value) {
                if ($field_key === 'media') {
                    $count = 0;
                    foreach ($value as $value_key => $field_value) {
                        if(array_key_exists('type', $field_value))
                        if(array_key_exists($field_value['type'], $types)) {
                            if ( $field_value['type'] === 'image') {
//                                $fields[$field_key][$value_key]['free'] = $types["image"]["free"];
                                $fields['test'] = $fields[$field_key][$value_key];
                                $fields[$field_key][$value_key]['free'] = $types["image"]["free"];
                                var_dump($fields);
                                die();
                            }
                        }
                    }
                }
            }
        }



        var_dump($fields);
        die();
        return $fields;
    }

    public function get_single_package(WP_REST_Request $request) {
        $params = $request->get_params();
        $package_id = $params['package_id'] ?? null;
        //TODO: This needs to add an agent to the mix
        $user_id = $params['id'] ?? get_current_user_id();

        if(! $package_id ) {
            return false;
        }

//        $package = get_post($package_id);
//
//        $package_data = [
//            'product_id' => get_post_meta($package_id),
//            'created_at' => get_post_meta($package_id, '_created_at'),
//        ];
//
//        return rest_ensure_response(new WP_REST_Response([$package_data]));



        $package = new stdClass();

        $package->api = "get_single_package";

        $free_promotions = carbon_get_post_meta( $package_id, 'package-free-promotions' );

        $is_subscription = false; // TODO: figure out subscriptions, we leave false for now


        $package->id = $package_id;
        $package->_promotion_addon_type = get_post_meta($package_id, '_promotion-addon-type');
        $package->promotions             = carbon_get_post_meta($package_id, 'package-promotions');
        foreach ($package->promotions as $promotion) {
            switch ($promotion['package-promotions-product']) {
                case 'addon-image':
                    $package->image_limit = $promotion['package-promotions-product-value'];
                    break;
                case 'addon-video':
                    $package->video_limit = $promotion['package-promotions-product-value'];
                    break;
                case 'addon-docs':
                    $package->docs_limit = $promotion['package-promotions-product-value'];
                    break;
            }
        }
        $package->free_promotions      = $free_promotions ?? false;
        $package->is_subscription      = $is_subscription;
        $package->currency             = get_woocommerce_currency_symbol();
        $package->decimals             = wc_get_price_decimals();
        $package->decimal_separator    = wc_get_price_decimal_separator();
        $package->thousand_separator   = wc_get_price_thousand_separator();
        $wc_product                    = wc_get_product( $package_id );
        $package->title                = $wc_product->get_title();
        $package->price                = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
        $package->price_html           = lisfinity_get_price_html( $package->price );
        $package->price_format         = get_woocommerce_price_format();
        $package->features             = [];
        $package_features = carbon_get_post_meta($package_id, "package-features");
        $count = 0;
        foreach($package_features as $feature) {
            $count++;
            array_push($package->features, array_merge($feature, ["uniqueId" => "featureID-" . $count]));
        }
        //
//        //TODO: We don't have the ability to add multiple packages just yet (should be $package->products_limit - $package->products_count)
        $package->remaining            = 0;
        //TODO: $package->percentage = floor( 100 - ( $package->remaining * 100 ) / $package->products_limit )
        $package->percentage           = 0;

        //TODO: There are no promotions at this point in time.
        //TODO: At this point in time we do not need to do this but for the futurue it should be: $this->filter_promotions_by_package( $promotions, $package_id, [ 'addon' ] )
        //TODO: $this->filter_promotions_by_package( $promotions, $package_id, [ 'product' ] )
        $package->promotion['addon']   = [];
        $package->promotion['product'] = [];
        //TODO: we may need this later should be $this->get_promotion_qr()
        $package->promotion_qr         = false;

        return rest_ensure_response(new WP_REST_Response($package));
    }



    public function filter_promotions_by_package( $promotions, $package_id, $type = [] ) {
        $filtered = [];

        $type = empty( $type ) ? [ 'addon', 'product', 'profile' ] : $type;
        if ( ! empty( $promotions ) ) {
            foreach ( $promotions as $promotion ) {
                if ( $promotion->package_id === $package_id && in_array( $promotion->type, $type ) ) {
                    $promotion->title = $this->format_promotion_title( $promotion );
                    $filtered[]       = $promotion;
                }
            }
        }

        return $filtered;
    }

    /**
     * Get all promotions that are connected to a package
     * --------------------------------------------------
     *
     * @param $package_id
     *
     * @return array
     */
    public function get_promotions_for_package( $package_id ) {
        $packages   = $this->where( 'package_id', $package_id )->get();
        $promotions = [];

        if ( $packages ) {
            foreach ( $packages as $package ) {
                $package->duration = carbon_get_post_meta( $package->wc_product_id, 'promotion-duration' );
                $package->addon    = str_replace( 'addon-', '', $package->position );
                $regular_price     = get_post_meta( $package->wc_product_id, '_regular_price', true );
                $sale_price        = get_post_meta( $package->wc_product_id, '_sale_price', true );
                $package->price    = $regular_price;
                if ( ! empty( $sale_price ) && 0 !== $sale_price ) {
                    $package->price = $sale_price;
                }
                $promotions[ $package->addon ] = $package;
            }
        }

        return $promotions;
    }

    public function get_packages(WP_REST_Request $request)
    {
        $packages = new WP_Query(
            [
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'product_type',
                        'field' => 'name',
                        'terms' => 'payment_package',
                        'operator' => 'IN',
                    ],
                ],
            ]

        );



        $data = [];

        if (!$packages->posts) {
            return false;
        }

        foreach ($packages->posts as $post) {
            if( get_field('administrative_package', $post->ID) && ! current_user_can('manage_options') ) {
                continue;
            }

            $package = $this->prepare_meta($post);
            //$package_extra_meta = get_post_meta($post->ID);
            array_push($data, array_merge(['package_id' => $post->ID], $package));
        }

        return rest_ensure_response(new WP_REST_Response($data));
    }

    function prepare_meta($package)
    {
        if (!property_exists($package, 'ID')) {
            return false;
        }

        $package_id = $package->ID;
        $package = [];
        $meta = [];
        $thumbnail = [];
        $product = wc_get_product($package_id);

        $meta['type'] = get_post_meta($package_id, '_promotion-type', true);
        $meta['duration'] = get_post_meta($package_id, '_promotion-duration-profile', true);
        $meta['price'] = $product->get_regular_price();
        $meta['sale-price'] = $product->get_sale_price();
        $meta['sale-price-1'] = 'test';

        if ($meta['type'] === 'product') {
            $meta['product-type'] = get_post_meta($package_id, '_promotion-product-type', true);
            $meta['duration'] = get_post_meta($package_id, '_promotion-duration', true);
        }

        $package['meta'] = $meta;
        $thumbnail['id'] = get_post_thumbnail_id();
        $thumbnail['url'] = wp_get_attachment_image_url($thumbnail['id'], 'medium');
        $thumbnail['caption'] = get_the_post_thumbnail_caption($thumbnail['id']);
        $thumbnail['meta'] = wp_get_attachment_metadata($thumbnail['id']);
        $thumbnail['thumb'] = wp_get_attachment_metadata($thumbnail['id']);
        $package['thumbnail'] = $thumbnail;
        $package['style'] = carbon_get_post_meta($package_id, "package-style");
        $package['features'] = [];
        $package_features = carbon_get_post_meta($package_id, "package-features");
        $count = 0;
        foreach($package_features as $feature) {
            $count++;
            array_push($package['features'], array_merge($feature, ["uniqueId" => "featureID-" . $count]));
        }
        $package['recommended_package'] = get_field('recommended_package', $package_id);
        $package['promotions'] = carbon_get_post_meta($package_id, "package-promotions");
        $package['package-sold-once'] = carbon_get_post_meta($package_id, 'package-sold-once');
        $package['limit'] = carbon_get_post_meta($package_id, 'package-products-limit');
        $package['duration'] = carbon_get_post_meta($package_id, 'package-products-duration');
        $title = get_the_title($package_id);
        $package['class'] = sanitize_title($title);
        $package['post_title'] = $title;
        $package['post_content'] = get_the_content($package_id);

        return $package;
    }

    /**
     * Method to add where clauses to the query builder.
     * -------------------------------------------------
     *
     * @param $column
     * @param string $value
     * @param bool $prefix_db
     *
     * @return $this
     */
    public function where( $column, $value = '', $prefix_db = true ) {
        global $wpdb;

        $query      = '';
        $table_name = $prefix_db ? "$this->db." : '';
        if ( is_array( $column ) ) {
            $count = 0;
            $query = '';

            foreach ( $column as $clause ) {
                $clause[0] = esc_sql( $clause[0] );
                $clause[1] = esc_sql( $clause[1] );
                if ( $count === 0 ) {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( "WHERE {$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " WHERE {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                } else {
                    if ( 2 === count( $clause ) ) {
                        $query .= $wpdb->prepare( " AND {$table_name}{$clause[0]} = %s", $clause[1] );
                    } else {
                        $clause[2] = is_array( $clause[2] ) ? '(' . implode( ',', $clause[2] ) . ')' : $clause[2];
                        $query     .= " AND {$table_name}{$clause[0]} $clause[1] $clause[2]";
                    }
                }
                $count ++;
            }
        } else {
            $column = esc_sql( $column );
            $value  = esc_sql( $value );
            $query  .= $wpdb->prepare( " WHERE {$table_name}{$column} = %s", $value );
        }

        $this->query .= $query;

        return $this;
    }

    /**
     * Get formatted table name
     * ------------------------
     *
     * @return string
     */
    private function table() {
        global $wpdb;

        $this->db = $wpdb->prefix . $this->table_prefix . $this->table;

        return $this->db;
    }

    /**
     * Get the all results from the database row
     * -----------------------------------------
     *
     * @param string|int $limit - Should we limit results returned
     * from the database.
     * @param string $order - Additional input to choose the
     * ordering of the results.
     * @param string $arg - Additional manually input arguments
     * for our query.
     * @param string $return - Choose what we want to return between
     * all results, just row or just a col.
     *
     * @return array|mixed|object|string|void|null
     */
    public function get( $limit = '', $order = '', $arg = '', $return = '' ) {
        global $wpdb;
        $db_fields = $this->get_table_fields();

        $fields[] = "{$this->db}.id";
        foreach ( $db_fields as $field ) {
            $fields[] = "{$this->db}.{$field}";
        }
        $fields = implode( ',', $fields );

        $arg   = ! empty( $arg ) ? " $arg" : '*';
        $limit = ! empty( $limit ) ? " LIMIT $limit" : '';
        if ( empty( $return ) ) {
            $query = $wpdb->get_results( "SELECT $arg FROM {$this->db} $this->query $order $limit;" );
        } else if ( 'col' === $return ) {
            $query = $wpdb->get_col( "SELECT $arg FROM {$this->db} $this->query $order $limit;" );
        }

        if ( $wpdb->last_error ) {
            return $wpdb->last_error;
        }

        $this->query = '';

        return $query;
    }

    /**
     * Get the fields from the table
     * -----------------------------
     *
     * @return array
     */
    public function get_table_fields() {
        return array_keys( $this->fields );
    }


    protected function set_table_fields() {
        $this->fields = [
            'package_id'    => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'order_id'      => [
                'type'  => 'bigint(20)',
                'value' => '0',
            ],
            'wc_product_id' => [
                'type'  => 'bigint(20)',
                'value' => '0',
            ],
            'user_id'       => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'product_id'    => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'value'         => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'count'         => [
                'type'  => 'bigint(20)',
                'value' => 'NULL',
            ],
            'position'      => [
                'type'  => 'varchar(100)',
                'value' => 'NULL',
            ],
            'type'          => [
                'type'  => 'varchar(100)',
                'value' => 'NULL',
            ],
            'status'        => [
                'type'  => 'varchar(100)',
                'value' => 'NULL',
            ],
            'activated_at'  => [
                'type'  => 'timestamp',
                'value' => 'NULL NULL',
            ],
            'expires_at'    => [
                'type'  => 'timestamp NULL',
                'value' => 'NULL NULL',
            ],
        ];

        return $this->fields;
    }

    /**
     * Get the correct currency based on the user input
     * ------------------------------------------------
     *
     * @return mixed|string
     */
    function lisfinity_get_chosen_currency_v2() {
        $currency = 'yes' === get_option( '_multicurrency-enabled' ) && ! empty( $_COOKIE['currency'] ) ? $_COOKIE['currency'] : get_option( 'woocommerce_currency' );

        return $currency;
    }

}

$packages = new Package_Products();
