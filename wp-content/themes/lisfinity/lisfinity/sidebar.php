<?php
/**
 * Theme sidebars
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ( ! lisfinity_is_woocommerce_active() ) || ( ! is_shop() && ! is_product() && ! is_cart() && ! is_checkout() && ! is_product_category() && ! is_product_tag() ) ) {
	dynamic_sidebar( 'default-sidebar' );
} else {
	dynamic_sidebar( 'shop-sidebar' );
}
