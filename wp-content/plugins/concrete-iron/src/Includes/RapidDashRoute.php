<?php

namespace ConcreteIron\Includes;

class RapidDashRoute {
    /**
     * RapidDashRoute constructor
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
        add_filter('lisfinity__get_business', [$this, 'available_packgaes'], 10, 1);
    }

    /**
     * Merge the business object with ours to include available_packages on the Dashboard
     *
     * @param \stdClass $business
     * @return void
     */
    public function available_packgaes( \stdClass $business ) {
        $args = [
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
        ];

        $packages = new \WP_Query($args);

        if (!$packages->posts) {
            return false;
        }

        foreach ($packages->posts as $post) {
            // Skip the package if its title is "Introductory Package"
            if ($post->post_title === 'Introductory Package') {
                continue;
            }

            $wc_product = wc_get_product($post->ID);
            if (!empty($wc_product)) {
                $formatted_package = new \stdClass();
                $formatted_package->title = get_the_title($wc_product->get_id());
                $formatted_package->id = $wc_product->get_id();
                $formatted_package->currency = get_woocommerce_currency_symbol();
                $formatted_package->decimals = wc_get_price_decimals();
                $formatted_package->decimal_separator = wc_get_price_decimal_separator();
                $formatted_package->thousand_separator = wc_get_price_thousand_separator();
                $formatted_package->title = $wc_product->get_title();
                $formatted_package->price = $wc_product->get_price(); // Add your own conversion rate here if necessary
                $formatted_package->price_html = wc_price($wc_product->get_price()); // Example function to get price HTML
                $formatted_package->products_limit = carbon_get_post_meta($wc_product->get_id(), 'products_limit');
                $formatted_package->remaining = $formatted_package->products_limit - carbon_get_post_meta($wc_product->get_id(), 'products_count');
                $formatted_package->percentage = ($formatted_package->products_limit > 0) ? floor(100 - ($formatted_package->remaining * 100) / $formatted_package->products_limit) : 0;
                $formatted_package->additional_listing = carbon_get_post_meta($wc_product->get_id(), 'additional_listing');

                $formatted_packages[] = $formatted_package;

            }
        }

        error_log("avail packs");
        error_log(print_r($business, true));

        $business->available_packages = $formatted_packages;

        return $business;
    }
}