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
        add_filter('get_post_metadata', [$this, 'format_price_meta'], 12, 4);
        add_filter('woocommerce_before_template_part', [$this, 'add_link_to_listings'], 10, 4);
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