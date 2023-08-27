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