<?php
namespace ConcreteIron\Templates;

class CIWC_CartEmptyTemplate {
    public function __construct() {
        add_action('woocommerce_return_to_shop_redirect', [$this, 'filter_return_to_shop_redirect']);
        add_action('woocommerce_return_to_shop_text', [$this, 'filter_return_to_shop_text']);
    }

    public function filter_return_to_shop_redirect() {
        return trailingslashit(site_url()) . 'my-account';
    }

    public function filter_return_to_shop_text() {
        return 'Return to your Dashboard, and try again.';
    }
}