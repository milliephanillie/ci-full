<?php
namespace ConcreteIron\Includes;

class RapidDebugging {
    public function __construct() {
        add_action('rest_api_init', [$this, 'enable_rest_api_debug_display']);
    }

    public function enable_rest_api_debug_display() {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            @ini_set('display_errors', 1); // Ensure that PHP errors are displayed
            if (!defined('WP_DEBUG_DISPLAY')) {
                define('WP_DEBUG_DISPLAY', true);
            }
        }
    }
}