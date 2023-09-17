<?php
/**
 * ConcreteIron  Custom Import class.
 *
 * @category   Class
 * @package    ConcreteIron
 * @subpackage WordPress
 * @author     Philip Rudy <me@philiparudy.com>
 * @copyright  2022 Philip Rudy
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      1.0.0
 * php version 7.3.9
 */
if ( ! defined( 'ABSPATH' ) ) {
    // Exit if accessed directly.
    exit;
}
/**
 * Main ConcreteIron Class
 *
 * The init class that runs the Concrete Iron plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the class-widgets.php file.
 */
class CI_Custom_Import {
    const ACF_PREFIX = 'ci_';

    /**
     * CustomImport constructor
     */
    public function __construct() {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot() {
        //$this->register_acf_fields();

        add_filter('https_ssl_verify', '__return_false');
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        //add_filter('mp_media_background_task', [$this, 'mp_media_task'], 10, 1);
        remove_action('woocommerce_created_customer', ['RegisterModel', 'lisfinity_create_business_profile_for_wc_customer'], 10);
    }


    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'ci_users_import';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'ci_users_import'],
            'args' => [],
            'permission_callback' => '__return_true'
        ));
    }

    /**
     * Loop through the csv, and update fields
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function ci_users_import(WP_REST_Request $request): \WP_REST_Response
    {
        $params     = $request->get_file_params();
        $fileName   = $params["file"]['tmp_name'];
        $limit      = $params["limit"] ?? 1000;
        $res        = ["Nothing yet."];
        $headers    = [];
        $imports = [];

        if ($params["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            $row = 0;

            $count = 0;

            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($count > $limit) {
                    break;
                }
                if (++$row == 1) {
                    $headers = array_flip($column); // Get the column names from the header.
                    continue;
                } else {
                    // Disable the email notification
                    remove_action('after_password_reset', 'wp_password_change_notification');
                    remove_action('wp_insert_user', 'wp_send_new_user_notifications');
                    
                    // Minimum
                    $user_id            = null;
                    $admin_user_name    = 'admin';

                    $prev_user_id       = $column[$headers['ID']];
                    $user_type		    = $column[$headers['Type']]; // Read row by the column name.
                    $plan_id            = $column[$headers['Plan_ID']];
                    $pay_date           = $column[$headers['Pay_date']];

                    $username           = $column[$headers['Username']];
                    $own_address        = $column[$headers['Own_address']];
                    $defaultpass        = "password123";
                    $password           = $column[$headers['Password']];
                    $email              = $column[$headers['Mail']];
                    $first_name         = $column[$headers['First_name']] ?? '';
                    $last_name          = $column[$headers['Last_name']] ?? '';
                    $about              = $column[$headers['about_me']] ?? '';
                    $member_since       = $column[$headers['Date']] ?? '';

                    $company_name       = $column[$headers['company_name']] ?? null;
                    $address            = $column[$headers['address']] ?? null;
                    $country            = $column[$headers['country']] ?? null;
                    $zip                = $column[$headers['zip_code']] ?? null;

                    $address = ($address && $address !== 'Address' ) ? $address : null;
                    $phone = $column[$headers['phone']] ?? null;

                    if ( ! $prev_user_id ) {
                        return rest_ensure_response(new WP_REST_Response([
                            "error" => "please make sure the user has a previous user id (from flynax)",
                        ]));
                    }

                    if ( ! $email ) {
                        return rest_ensure_response(new WP_REST_Response([
                            "error" => "please make sure the user has an email address",
                        ]));
                    }

                    if ( ! $username ) {
                        return rest_ensure_response(new WP_REST_Response([
                            "error" => "please make sure the user has a username",
                        ]));
                    }

                    // if we don't have a user id we need to insert it

                    $userdata = [
                        'user_login' => $username,
                        'user_pass' => $defaultpass,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'user_email' => $email,
                    ];

                    $user_by_login = get_user_by('login', $username);

                    if( ! empty($user_by_login) ) {
                        $user_id = $user_by_login->ID;
                        $userdata = array_merge($userdata, ['ID' => $user_id]);

                        $user_id = wp_update_user(
                            $userdata
                        );

                        $user_id_from_update = $user_id;
                    }


                    $user_id_from_insert = null;
                    if ( ! $user_id && $username) {
                        // see https://developer.wordpress.org/reference/functions/wp_insert_user/
                        $user_id = wp_insert_user(
                            $userdata
                        );

                        $user_id_from_insert = $user_id;
                    }

                    $wp_user_object = new WP_User($user_id);
                    $wp_user_object->set_role('editor');

                    if ($user_type === 'dealer') {
                        $update = update_user_meta($user_id, '_account-type', 'business', 'personal');
                    }

                    if($first_name) {
                        $first_name_update = update_user_meta($user_id, 'first_name', $first_name);
                    }

                    if($last_name) {
                        $last_name_update = update_user_meta($user_id, 'last_name', $last_name);
                    }

                    if($about) {
                        $about_update = update_user_meta($user_id, 'description', $about);
                    }


                    if ($company_name) {
                        $billing_company_name_update = update_user_meta($user_id, 'billing_company', $company_name);
                        $shipping_company_name_update = update_user_meta($user_id, 'shipping_company', $company_name);
                    }

                    if( $address ) {
                        $billing_address_1_update = update_user_meta($user_id, 'billing_address_1', $address);
                        $shipping_address_1_update = update_user_meta($user_id, 'shipping_address_1', $address);
                    }

                    if ( $zip ) {
                        $billing_zip_update = update_user_meta($user_id, 'billing_postcode', $zip);
                        $shipping_zip_update = update_user_meta($user_id, 'shipping_postcode', $zip);
                    }

                    if( $phone ) {
                        $area_code = $this->get_string_between($phone, 'c:', '|');
                        $first = $this->get_string_between($phone, 'a:', '|');
                        $last = substr($phone, strpos($phone, "n:") + 2);

                        $phone = $area_code.$first.$last;
                        $phone = intval($phone);

                        if($phone) {
                            $phone_update = update_user_meta($user_id, 'shipping_phone', $phone);
                        }
                    }

                    if($country) {
                        $country = substr($country, strpos($country, "_") + 1);
                    }

                    $business_page = get_page_by_title('Business: ' . $username, 'premium_profile');

                    if ( ! $business_page ) {
                       // do_action('woocommerce_created_customer', $user_id, null, null);
                    }

                    if (!empty($member_since)) {
                        update_user_meta($user_id, 'member_since', $member_since);
                    }

                    $final_user_data = [
                        'user_data' => $userdata,
                        'username_from_csv' => $username,
                        'user_id' => $user_id,
                        'user_login' => get_userdata($user_id)->user_login,
                        'user_existed_already' => $user_id_from_update ? 'True' : 'False',
                        'user_id_from_insert' => $user_id_from_insert
                    ];

                    array_push($imports, (array) $final_user_data);
                }

                $count ++;
            }
        } else {
            $imports = ['Loop never started.'];
        }

        $res = new \WP_REST_Response($imports);

        return rest_ensure_response($res);
    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

$ci_import = new CI_Custom_Import();
$ci_import->boot();
