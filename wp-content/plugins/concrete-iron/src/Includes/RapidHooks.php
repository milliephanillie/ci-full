<?php

namespace ConcreteIron\Includes;

class RapidHooks {
    /**
     * RapidHooks constructor
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
        add_action( 'init', [$this, 'rapid_register_product_statuses'], 10, 1 );
        add_filter('lisfinity__set_product_statuses', [$this, 'add_incomplete_status'], 10, 1);
        add_filter( 'display_post_states', [$this, 'rapid_add_display_post_states'], 11, 2 );
        add_filter('post_row_actions', [$this, 'modify_view_link_for_incomplete_status'], 10, 2);

        add_filter('get_post_metadata', [$this, 'format_price_meta'], 12, 4);

        add_filter('woocommerce_before_template_part', [$this, 'add_link_to_listings'], 10, 4);
        add_filter('lisfinity__get_overal_stats', [$this, 'add_leads'], 10, 1 );
        add_action('gform_after_submission', [$this, 'increment_form_submission_count'], 10, 2);
        add_filter( 'posts_search', [$this, 'add_plurals_to_search'], 10, 2 );
        add_filter( 'lisfinity__available_price_types', [$this, 'add_custom_price_types']);

        // Sets when the session is about to expire
        add_filter( 'wc_session_expiring', [$this, 'woocommerce_cart_session_about_to_expire']);
    }

    public function add_custom_price_types($types) {
        if(!is_array($types)) {
            return;
        }

        return array_merge($types, ['sell_price' => 'Sell Price']);
    }

    /**
     * Woocommerce_cart_session_about_to_expire
     */
    public function woocommerce_cart_session_about_to_expire() {
            return 60 * 60 * 47;
    }

    /**
     * Add plurals to search
     * 
     * @param $search
     * @param $wp_query
     * @return array|mixed|string|string[]
     */
    public function add_plurals_to_search( $search, $wp_query ) {
        if ( ! empty( $search ) && ! empty( $wp_query->query_vars['s'] ) ) {
            $search_term = $wp_query->query_vars['s'];
            $search = str_replace($search_term . '\'', $search_term . '\'s', $search); // Adjusting for terms ending with an apostrophe
            $search = str_replace('"' . $search_term . '"', '"' . $search_term . 's"', $search);
        }
        return $search;
    }

    /**
     * Update post meta count
     *
     * @param $entry
     * @param $form
     * @return void
     */
    public function increment_form_submission_count($entry, $form) {
        // Check if the form title matches "Contact Seller"
        if ($form['title'] !== 'Contact Seller') {
            return;
        }

        // Get the post ID from the entry
        $post_id = isset($entry[5]) ? intval($entry[5]) : 0;

        // If there's a post ID
        if ($post_id) {
            $current_count = get_post_meta($post_id, 'gravity_form_submission_count', true);

            // If there's no previous count, initialize to 0
            if (empty($current_count)) {
                $current_count = 0;
            }

            // Increment the count
            $new_count = $current_count + 1;

            // Update post meta
            update_post_meta($post_id, 'gravity_form_submission_count', $new_count);
        }
    }


    /**
     * Add leads
     *
     * @param $stats
     * @return mixed
     */
    public function add_leads($stats) {

        return $stats;
    }


    /**
     * Remove the view link for incomplete posts
     *
     * @param $actions
     * @param $post
     * @return mixed
     */
    public function modify_view_link_for_incomplete_status($actions, $post)
    {
        if ('incomplete' === $post->post_status) {
            unset($actions['view']);
        }
        return $actions;
    }

    /*
     * Register the incomplete post status
     */
    public function rapid_register_product_statuses() {
        register_post_status( 'incomplete', array(
            'label'                     => _x( 'Incomplete', 'lisfinity_core', 'lisfinity-core' ),
            'label_count'               => _n_noop( 'Incomplete <span class="count">(%s)</span>', 'Incomplete <span class="count">(%s)</span>', 'lisfinity-core' ),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true
        ) );
    }

    /*
     * Add the incomplete status to the available lisfinity status types (for the carbon field)
     */
    public function add_incomplete_status($statuses) {
        if (is_array($statuses)) {
            $statuses = array_merge($statuses, ['incomplete']);
        }

        return $statuses;
    }

    /*
     * Set the incomplete post state and remove all other (non-default) post states
     */
    public function rapid_add_display_post_states($post_states, $post) {
        //We will keep these default WP states
        $default_states = array(
            'draft' => true,
            'pending' => true,
            'private' => true,
            'password protected' => true,
            'protected' => true,
            'scheduled' => true,
            'custom' => true,
            'sticky' => true,
        );

        $product_type = get_the_terms( $post->ID, 'product_type' );
        if ( ! empty( $product_type[0] ) ) {
            if ( 'listing' === $product_type[0]->slug ) {
                $product_status = carbon_get_post_meta($post->ID, 'product-status');

                if ($product_status && $product_status === 'incomplete') {
                    // Remove custom states
                    foreach ($post_states as $key => $value) {
                        if (!isset($default_states[$key])) {
                            unset($post_states[$key]);
                        }
                    }

                    $post_states['rapid_product_incomplete'] = sprintf( '<span style="padding: 2px 4px; background-color: #f3c647; color: #fff; border-radius: 4px;">%s<span>', __( 'Incomplete', 'lisfinity-core' ) );
                }
            }
        }

        return $post_states;
    }

    public function add_link_to_listings($template_name, $template_path, $located, $args) {
        if ($template_name == 'checkout/thankyou.php') {
            if (isset($args['order'])) {
                $order = $args['order'];
                ?>
                <section class="listing-links">
                    <div class="listing-links-inner">
                        <?php
                        global $wpdb;

                        $order_id = $order->get_id();
                        $sql = $wpdb->prepare("SELECT id FROM {$wpdb->prefix}lisfinity_packages WHERE order_id = %s", $order_id);
                        $id = $wpdb->get_var($sql);

                        $post_args = array(
                            'post_type'      => 'product',
                            'meta_key'       => '_payment-package',
                            'meta_value'     => $id,
                            'posts_per_page' => 1,
                        );

                        $posts = get_posts($post_args);

                        if (!empty($posts)) {
                            $post = $posts[0];
                            $permalink = get_permalink($post->ID);
                            echo '<div style="display:flex; justify-content: center; margin-bottom: 30px;">';
                            echo '<a class="button" style="margin-right: 20px;" href="' . esc_url($permalink) . '">View Listing</a>';
                            echo '<a class="button" href="' . esc_url(untrailingslashit(home_url()) .'/my-account/ads') . '">View All Listings</a>';
                            echo '</div>';
                        } else {
                            echo "No post found with matching _payment-package meta value.";
                        }
                        ?>
                    </div>
                </section>
                <?php
            }
        }
    }

    public function format_price_meta($metadata, $object_id, $meta_key, $single) {
        global $wpdb;

        if ('_price' === $meta_key && $single) {
            // we do this other wise getting the post meta would result in an infinite loop as the hook would keep running
            $query = $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1", $object_id, $meta_key);
            $original_value = $wpdb->get_var($query);

            if (!empty($original_value)) {
                $formatted_metadata = $this->formatPrice($original_value, '.', ',', 2);

                if($formatted_metadata) {
                    return $formatted_metadata;
                }
            }
        }

        // Return the original metadata if conditions aren't met
        return $metadata;
    }

    public function formatPrice($price, $decimalSeparator = '.', $thousandsSeparator = ',', $decimalPoints = 2) {
        // Use WordPress's sanitize_text_field() to ensure a clean string input
        $price = sanitize_text_field($price);

        // Remove the thousands separator
        $price = str_replace($thousandsSeparator, '', $price);

        // Remove characters that are not digits and not the decimal separator
        $sanitizedPrice = preg_replace('/[^\d' . preg_quote($decimalSeparator) . ']/', '', $price);

        // Convert the sanitized price to a float and then format it with the desired decimal separator and decimal points, without a thousands separator
        $formattedPrice = number_format((float)$sanitizedPrice, $decimalPoints, $decimalSeparator, '');

        return $formattedPrice;
    }
}