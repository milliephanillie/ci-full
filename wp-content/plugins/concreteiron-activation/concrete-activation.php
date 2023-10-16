<?php
/**
 * Plugin Name: ConcreteIron Activation
 * Plugin URI: https://www.concreteiron.com/
 * Description: Activation utilities for ConcreteIron launch.
 * Version: 1.0
 * Requires at least: 5.0
 * Requires PHP: 5.6.20
 * Author: Philip Rudy
 * Author URI: https://www.philiparudy.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: concrete-iron
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('CIACTIVATE', __FILE__);

if ( ! defined( 'CIACTIVATE_PATH' ) ) {
    define( 'CIACTIVATE_PATH', plugin_dir_path( CIACTIVATE ) );
}
//
//require_once 'vendor/autoload.php';

class ConcreteIronActivation {
    private $template_path;
    /**
     *
     */
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_route']);
        add_filter('woocommerce_email_content_reset_password', [$this, 'custom_wc_reset_password_message'], 10, 2);
        add_filter('retrieve_password_message', [$this, 'custom_wp_reset_password_message'], 10, 4);
        add_filter('retrieve_password_title', [$this, 'custom_wp_reset_password_title']);
    }

    /**
     * @return void
     */
    public function register_route() {
        $namespace = 'ci';
        $route = 'password-reset-test';

        register_rest_route($namespace, $route, [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'send_pass_reset'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * @param WP_REST_Request $request
     * @return void|WP_Error
     */
    public function send_pass_reset(\WP_REST_Request $request) {
        $params = $request->get_params();
        $user_login = $params['user_login'] ?? null;
        $email = null;

        if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
            $email = $user_login;
        } else {
            $user = get_user_by('user_login', $user_login);
            $email = $user->user_email;
        }

        if(!$email) {
            return new WP_Error(
                'plugin_error',
                'no email set.'
            );
        }

        // Set the email content type to HTML
        add_filter('wp_mail_content_type', [$this, 'set_html_content_type']);

        $retrieved = retrieve_password($email);

        // Revert the content type back to default
        remove_filter('wp_mail_content_type', [$this, 'set_html_content_type']);

        return rest_ensure_response(
            new WP_REST_Response(
                [
                    "retrieve" => $retrieved,
                    "message" => "check your email: " . $email
                ]
            )
        );
    }

    /**
     * @param $message
     * @param $args
     * @return mixed
     */
    function custom_wc_reset_password_message($message, $args) {
        $reset_key = $args['reset_key'];
        $user_login = $args['user_login'];

        $name = get_user_by('user_login', $user_login);
        // Define the path to the email template
        $template_path = plugin_dir_path(__FILE__) . 'templates/activation.php';
        $reset_link = $this->generate_reset_password_link($user_login);
        $email_vars = array(
            'name'  => 'Jane Doe',
            'user_login' => $user_login,
            'reset_link' => $reset_link,
            'reset_key' => $reset_key,
            'welcome_string' => ''
        );

        $email_content = $this->get_template_content($template_path, $email_vars);

        do_action('password_reset', $args['user_login']);


        // Customize your $message using the $reset_key and $user_login as required.

        return $email_content;
    }

    /**
     * Genereate te password reset link
     *
     * @param $user_login
     * @return false|string
     */
    function generate_reset_password_link($user_login) {
        // Get the user data by login
        $user_data = get_user_by('login', $user_login);
        if (!$user_data) return false;

        // Generate a key
        $key = get_password_reset_key($user_data);

        if (is_wp_error($key)) {
            return false;
        }

        // Construct the reset password URL
        $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');

        return $reset_url;
    }

    function custom_wc_reset_password_subject($subject, $email) {
        // Customize $subject as per your needs
        return $subject;
    }

    function custom_wp_reset_password_message($message, $key, $user_login, $user_data) {
        // Customize $message, possibly using $key, $user_login, $user_data as needed
        $template_path = plugin_dir_path(__FILE__) . 'templates/activation.php';

        $reset_link = $this->generate_reset_password_link($user_login);
        $user_display_name = isset($user_data->display_name) ? $user_data->display_name : $user_login;

        $email_vars = array(
            'name'  => $user_display_name,
            'user_login' => $user_login,
            'reset_link' => $reset_link,
            'reset_key' => $key,
            'welcome_string' => ''
        );

        $email_content = $this->get_template_content($template_path, $email_vars);
        return $email_content;
    }

    function custom_wp_reset_password_title($title) {
        // Customize $title as per your needs
        return $title;
    }



// Now, you can use $email_content with your email sending function.

    function get_template_content($template_path, $vars = array()) {
        if(file_exists($template_path)) {
            // Extract the variables to a local namespace
            extract($vars);

            // Start output buffering
            ob_start();

            // Include the template file
            include $template_path;

            // End buffering and return its contents
            return ob_get_clean();
        }
        return false;
    }

    function set_html_content_type() {
        return 'text/html';
    }

}

$activation = new ConcreteIronActivation();

