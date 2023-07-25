<?php
/**
 * Helper file for various theme options settings
 *
 * @author pebas
 * @package helpers
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_get_option' ) ) {
    /**
     * Get an option from the Redux plugin
     * -----------------------------------
     *
     * @param $option
     * @param $default
     *
     * @return mixed
     */
    function lisfinity_get_option( $option, $default = '' ) {
        global $lisfinity_options;
        if ( ! class_exists( 'Redux' ) ) {
            return '';
        }

        if ( ! isset( $lisfinity_options["_{$option}"] ) ) {
            return $default;
        }

        return $lisfinity_options["_{$option}"];
    }
}

if ( ! function_exists( 'lisfinity_new_icon_font' ) ) {
    /**
     * Add fontawesome instead of elusive icons in Redux framework
     * -----------------------------------------------------------
     */
    function lisfinity_new_icon_font() {
        //wp_deregister_style( 'redux-elusive-icon' );
        //wp_deregister_style( 'redux-elusive-icon-ie7' );

        wp_register_style(
            'redux-font-awesome',
            '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            [],
            time(),
            'all'
        );
        wp_enqueue_style( 'redux-font-awesome' );
    }

    add_action( 'redux/page/lisfinity-options/enqueue', 'lisfinity_new_icon_font' );
}

if ( ! function_exists( 'lisfinity_redux_number_validate' ) ) {
    /**
     * Custom function for the callback validation referenced above
     *
     * @param array $field Field array.
     * @param mixed $value New value.
     * @param mixed $existing_value Existing value.
     *
     * @return mixed
     */
    function lisfinity_redux_number_validate( $field, $value, $existing_value ) {
        $error = false;

        // Do your validation.
        if ( ! is_numeric( $value ) ) {
            $field['msg']    = sprintf( esc_html__( 'Field %s value has to be numeric', 'lisfinity-core' ), $field['title'] );
            $return['error'] = $field;
            $return['value'] = $existing_value;

            return $return;
        }

        if ( ! empty( $field['attributes']['max'] ) && $value > $field['attributes']['max'] ) {
            $field['msg']    = sprintf( esc_html__( 'Field %s value is bigger than maximum allowed', 'lisfinity-core' ), $field['title'] );
            $return['error'] = $field;
            $return['value'] = $existing_value;

            return $return;
        }

        if ( ! empty( $field['attributes']['min'] ) && $value < $field['attributes']['min'] ) {
            $field['msg']    = sprintf( esc_html__( 'Field %s value is less than minimum allowed', 'lisfinity-core' ), $field['title'] );
            $return['error'] = $field;
            $return['value'] = $existing_value;

            return $return;
        }
    }
}

if ( ! function_exists( 'lisfinity_update_wc_option' ) ) {
    /**
     * Even out the options between theme options page and WP Job Manager
     * ------------------------------------------------------------------
     *
     * @hooked add_action( 'carbon_fields_theme_options_container_saved' )
     */
    function lisfinity_update_wc_option() {

        // update home page.
        $home_page = lisfinity_get_option( 'page-home' );
        update_option( 'page_on_front', $home_page );

        // update account page.
        $account_page = lisfinity_get_option( 'page-account' );
        update_option( 'woocommerce_myaccount_page_id', $account_page );

        // update privacy policy page.
        $privacy_page = lisfinity_get_option( 'page-privacy-policy' );
        update_option( 'wp_page_for_privacy_policy', $privacy_page );

        // update terms & conditions page.
        $privacy_page = lisfinity_get_option( 'page-terms' );
        update_option( 'woocommerce_terms_page_id', $privacy_page );

    }
}

if ( ! function_exists( 'lisfinity_update_theme_option' ) ) {
    /**
     * Even out the options between WP Job Manager and theme options
     * ------------------------------------------------------------
     *
     * @param string $option - all options returned from WordPress
     * settings.
     * @param mixed $old - the old value returned from WordPress.
     * @param mixed $new - the new value returned from WordPress.
     *
     * @hooked add_action( 'updated_option' )
     */
    function lisfinity_update_theme_option( $option, $old, $new ) {

        // update account page when woocommerce has been changed.
        if ( 'page_on_front' === $option && $old !== $new ) {
            Redux::set_option( 'lisfinity-options', '_page-home', $new );
        }

        // update account page when woocommerce has been changed.
        if ( 'woocommerce_myaccount_page_id' === $option && $old !== $new ) {
            Redux::set_option( 'lisfinity-options', '_page-account', $new );
        }

        // update privacy policy page when theme option has been changed.
        if ( 'wp_page_for_privacy_policy' === $option && $old !== $new ) {
            Redux::set_option( 'lisfinity-options', '_page-privacy-policy', $new );
        }

        // update terms & conditions page when theme option has been changed.
        if ( 'woocommerce_terms_page_id' === $option && $old !== $new ) {
            Redux::set_option( 'lisfinity-options', '_page-terms', $new );
        }

    }
}

if ( ! function_exists( 'lisfinity_update_meta' ) ) {
    /**
     * Update meta fields when the option is changed
     * ---------------------------------------------
     *
     * @param $post_id
     * @param $post
     * @param $update
     */
    function lisfinity_update_meta( $post_id, $post, $update ) {
        $product_type = get_the_terms( $post_id, 'product_type' );
        // make sure it is applied only for listings.
        if ( ! empty( $product_type[0] ) ) {
            if ( 'listing' === $product_type[0]->slug ) {
                if ( is_admin() && $update ) {
                    $business      = $_POST['carbon_fields_compact_input']['_product-business'] ?? '';
                    $current_owner = $_POST['carbon_fields_compact_input']['_product-owner'] ?? '';

                    $owner = get_post_field( 'post_author', $business );
                    if ( $current_owner !== $owner ) {
                        $_POST['carbon_fields_compact_input']['_product-owner'] = $owner;
                    }
                }
            }
        }
    }
}
