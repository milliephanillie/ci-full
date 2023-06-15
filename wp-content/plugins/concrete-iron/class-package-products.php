<?php


class Package_Products
{
    const ACF_PREFIX = 'mp_';

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
        add_filter('https_ssl_verify', '__return_false');
        add_action('rest_api_init', [$this, 'register_routes']);
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
        ));
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
        $package['promotions'] = carbon_get_post_meta($package_id, "package-promotions");
        $package['package-sold-once'] = carbon_get_post_meta($package_id, 'package-sold-once');
        $package['limit'] = carbon_get_post_meta($package_id, 'package-products-limit');
        $package['duration'] = carbon_get_post_meta($package_id, 'package-products-duration');
        $package['post_title'] = get_the_title($package_id);
        $package['post_content'] = get_the_content($package_id);

        return $package;
    }
}

$packages = new Package_Products();