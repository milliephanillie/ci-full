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
namespace ConcreteIron\Import;

use Lisfinity\Controllers\PackageController;
use Lisfinity\Models\PromotionsModel;

/**
 * Listings Import Class
 *
 * The init class that runs the Concrete Iron plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the class-widgets.php file.
 */
class ListingsImport {
    const ACF_PREFIX = 'ci_';
    const SINGLE_IMAGE_ROUTE = 'ci/v1/one_image';
    const PRODUCT_IMAGES_ROUTE = "ci/v1/product_images";
    const ORIGINAL_SOURCE_BASE_URL = 'https://concreteiron.com/concrete-equipment/';
    const DEFAULT_BUSINESS_PROFILE_PREFIX = 'Seller: %s';

    private $post_stati;
    private $subcats;
    private $makes;
    private $third_cats;
    private $updated_listed;
    private $update_expired;
    private $failed_uploads = [];
    private $description_add;
    private $invalidTopCategories = [
        'Parts and surplus low cost ads',
        'Help Wanted Ads'
    ];

    private $skipped_rows = [];
    private $row_skipped_codes = [
        "user_missing" => "The user is not in the database.",
        "invalid_top_level_category" => 'Must be in the top level category of "Concrete Equipment."',
        "missing_category" => 'Listing is missing the third level category from Flynax.',
        "missing_ad_title" => 'Listing is missing the ad title field from Flynax.',
        "missing_stock" => 'Listing is does not have a stock quantity.',
        "missing_status" => 'Listing is missing a post status.',
        "no_post_update" => 'Failed to update or create post',
        "no_business_id" => 'Failed to create a business ID',
        "missing_order_id" => 'Failed to create or get the order ID'
    ];

    /**
     * Function to perform common transformation on a string:
     * Convert to lowercase, replace spaces or dashes with a single dash, and add a trailing slash.
     *
     * @param string $string The string to transform.
     * @return string The transformed string.
     */
    private function transformString($string) {
        return trailingslashit(preg_replace('#[ -]+#', '-', strtolower($string)));
    }

    /**
     * Generate the original URL for a listing based on its second and third categories, ad title, and import ID.
     *
     * @param string $mysecondcategory The second category.
     * @param string $mythirdcategory The third category.
     * @param string $title The ad title.
     * @param string $import_id The import ID.
     * @return string The generated original URL.
     */
    public function generateOriginalUrl($mysecondcategory, $mythirdcategory, $title, $import_id) {
        $cat_2 = $this->transformString($mysecondcategory);
        $cat_3 = $this->transformString($mythirdcategory);
        $suff = $this->transformString($title);

        return self::ORIGINAL_SOURCE_BASE_URL . $cat_2 . $cat_3 . $suff . '-' . $import_id . '.html';
    }

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
        add_filter('https_ssl_verify', '__return_false');
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }


    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'ci_listings_import';

        register_rest_route($namespace, $route, array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'ci_listings_import'],
            'args' => [],
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Get the user in a multitude of ways
     *
     * @param $username
     * @param $alternate_contact_name
     * @return void|\WP_User
     */
    public function validateUser($username, $alternate_contact_name) {
        $user = get_user_by_email($username);
        if (!$user) {
            $user = get_user_by('login', $username);
            if(!$user) {
                return;
            }
        }
        return $user;
    }

    /**
     * Determine the condition of the imported item from flynax
     *
     * @param int|null $conditionCode
     * @return string|null
     */
    public function determineCondition($conditionCode = null) {
        if(gettype($conditionCode) === 'string') {
            return strtolower($conditionCode);
        }

        switch ($conditionCode) {
            case 1:
                return 'new';
            case 2:
                return 'used';
            default:
                return null;
        }
    }

    /**
     * Get the ID of the business profile
     *
     * @param $auth_business_name
     * @param $user
     * @return int|null
     */
    function getBusinessID($auth_business_name, $user) {
        $page_title = sprintf($auth_business_name, $user->user_login);
        $page = get_page_by_title($page_title, 'OBJECT', 'premium_profile');

        if (!$page) {
            $register_business = new \Lisfinity\Models\Auth\Register\RegisterModel();
            $register_business->lisfinity_create_business_profile_for_wc_customer($user->ID, null, null);
            $page = get_page_by_title($page_title, 'OBJECT', 'premium_profile');
        }

        return $page ? $page->ID : null;
    }

    /**
     * Get the user phone number
     *
     * @param $user_id
     * @return mixed
     */
    function getUserPhone($user_id) {
        $shipping_phone = get_user_meta($user_id, 'shipping_phone', true);
        $billing_phone = get_user_meta($user_id, 'billing_phone', true);
        return $shipping_phone ?? $billing_phone;
    }

    /**
     * Create the listing activated and expired dates
     *
     * @param string $given_date_str
     * @param int $post_id
     * @return mixed
     */
    public function getStatusFromDate(string $given_date_str) {// Your given date as a string
        if(!$given_date_str) {
            return null;
        }

        $given_date_timestamp = strtotime($given_date_str);
        $current_time = current_time('timestamp');
        $ninety_days_ago = strtotime('-90 days', $current_time);

        $status = 'expired';

        if ($given_date_timestamp >= $ninety_days_ago && $given_date_timestamp <= $current_time) {
            $status = 'active';
        }

       $status = $this->checkIfSold($this->description_add, $status);

        return $status;
    }

    /**
     * Look for the word "SOLD" in all caps in the description
     *
     * @param string $description
     * @param string $status
     * @return string
     */
    public function checkIfSold(string $description, string $status = '') : string {
        // Search for the word "SOLD" in the listing without converting case
        if (strpos($description, 'SOLD') !== false) {
            $status = "sold";
        }

        return $status;
    }

    /**
     * Convert something like $49,876 to something like 49876.00
     *
     * @param string $currencyString
     * @return float|string
     */
    public function convertCurrencyToNumber(string $currencyString) {
        // Remove the dollar sign and commas
        $numberString = str_replace(['$', ','], '', $currencyString);

        // Convert the cleaned string to a float
        $number = (float) $numberString;

        // Check if the number is an integer, and format accordingly
        if (floor($number) == $number) {
            return number_format($number, 2, '.', '');
        } else {
            return $number;
        }
    }

    /**
     * Check if the image already exists
     *
     * @param $url
     * @return false|string
     */
    public function image_already_exists($url) {
        global $wpdb;
        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1";
        $id = $wpdb->get_var($wpdb->prepare($query, $url));
        return ($id) ? $id : false;
    }

    /**
     * Download from url and upload to media, and add to product image gallery
     *
     * @param $csv_cell
     * @param $post_id
     * @return array|string[]
     */
    public function add_images_to_product_gallery($gallery_images, $post_id, $alt_text) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        // Convert gallery_images to array of URLs
        $urls = array_map('trim', str_getcsv($gallery_images));

        // Initialize an empty array to hold the new or existing attachment IDs
        $attachment_ids = [];

        // Check if the product already has a main image
        $has_main_image = get_post_meta($post_id, '_thumbnail_id', true);

        // Loop through each URL
        foreach ($urls as $url) {
            // Check if the image already exists
            $existing_id = $this->image_already_exists($url);

            if ($existing_id) {
                $attach_id = $existing_id;
            } else {
                // Download file to temp dir
                $response = wp_remote_get($url, ['timeout' => 300]);

                if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                    $this->failed_uploads[] = $url;
                    continue;
                }

                // Write to a temporary file
                $tmpfile = wp_tempnam($url);
                file_put_contents($tmpfile, wp_remote_retrieve_body($response));

                $file_array = array(
                    'name'     => basename($url),
                    'tmp_name' => $tmpfile,
                );

                // Insert downloaded file as an attachment
                $attach_id = media_handle_sideload($file_array, $post_id);

                // Check for handle sideload errors
                if (is_wp_error($attach_id)) {
                    $this->failed_uploads[] = $url;
                    continue;
                }
            }

            // Add alt text
            update_post_meta($attach_id, '_wp_attachment_image_alt', $alt_text);

            // If there is no main product image, set the first successful upload as the main image
            if (!$has_main_image) {
                update_post_meta($post_id, '_thumbnail_id', $attach_id);
                $has_main_image = true;  // Prevent setting additional images as main
            }

            $attachment_ids[] = $attach_id;
        }

        // Update product gallery
        if (!empty($attachment_ids)) {
            $existing_gallery = get_post_meta($post_id, '_product_image_gallery', true);
            $merged_gallery = implode(',', array_merge(explode(',', $existing_gallery), $attachment_ids));
            update_post_meta($post_id, '_product_image_gallery', $merged_gallery);
        }

        // Return information about failed uploads
        if (!empty($this->failed_uploads)) {
            return ['status' => 'error', 'message' => 'Failed to upload some images', 'failed_uploads' => $this->failed_uploads];
        }

        return ['status' => 'success', 'message' => 'All images uploaded successfully', 'alt_text' => $alt_text, 'has_main_image' => $has_main_image];
    }

    /**
     * Count reasons why the row was skipped.
     *
     * @param $row_number
     * @param $reason
     * @return array|false
     */
    public function add_to_row_skipped($row_number = null, $codes = null, $var = null) {
        if ( ! $row_number ||  ! $codes) {
            return false;
        }

        array_push($this->skipped_rows, [
            'row_number' => $row_number,
            'error_message' => $this->row_skipped_codes[$codes] ?? 'Error generating reason code.',
            'param' => $var
        ]);
    }

    /**
     * Loop through the csv, and update fields
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function ci_listings_import(\WP_REST_Request $request)
    {
        $params = $request->get_file_params();
        $fileName = $params["file"]['tmp_name'];
        $images = $request->get_params()['images'];
        $limit= $params["limit"] ?? 1000;
        $res = ["Nothing yet."];
        $headers = [];
        $fix_cats = [];

        $this->makes = get_terms('concrete-equipment-makes', [
            'hide_empty' => false,
        ]);

        $this->third_cats = get_terms('concrete-equipment-third-cat', [
            'hide_empty' => false,
        ]);

        if ($params["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            $row = 0;
            $imports = [];
            $count = 0;
            $missing_title = 0;
            $category_fail = 0;
            $no_post_id = 0;
            $this->post_stati = get_post_stati();

            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if ($count > $limit) {
                    break;
                }

                if (++$row == 1) {
                    $headers = array_flip($column); // Get the column names from the header.
                    continue;
                } else {
                    $data = [];
                    // Minimum
                    $user_id = null;

                    //Title
                    $year = $column[$headers['Year']] ?? null;
                    $make = $column[$headers['Make / Brand']] ?? null;
                    $model = $column[$headers['Model']] ?? null;
                    $condition = $column[$headers['Condition']] ?? null;

                    //Fields
                    $equipment_hours = $column[$headers['Equipment Hours']] ?? null;
                    $price = $column[$headers['Price']] ?? null;
                    $this->description_add = $description_add = $column[$headers['Description']] ?? null;
                    $stocknumber = $column[$headers['Stock Number']] ?? null;

                    $import_id = $row;
                    $username = $column[$headers['Username']] ?? null;
                    $date = $column[$headers['Date']] ?? null;
                    $status = null; //not in this spreadsheet
                    $title = null;
                    $category           = $column[$headers['Category']] ?? null;
                    $subcategory_lvl_1  = $column[$headers['Subcategory level 1']] ?? null;
                    $subcategory_lvl_2  = $column[$headers['Subcategory level 2']] ?? null;
                    $subcategory_lvl_3  = $column[$headers['Subcategory level 3']] ?? null;
                    $gallery_images  = $column[$headers['Picture URL']] ?? null;

                    $status = $this->getStatusFromDate($date);

                    $whitelist = [
                        10,
                        25,
                        569,
                        485,
                        499,
                        114,
                        88,
                        87,
                        30,
                        436,
                        680,
                    ];

                    if ($status != 'active' && !in_array($row, $whitelist)) {
                        continue;
                    }

                    if( ($row > $limit) ) {
                        break;
                    }

                    $user = $this->validateUser($username, null);

                    if (!$user) {
                        $user = get_user_by_email('me+assign@philiparudy.com');
                        if (!$user) {
                            $this->add_to_row_skipped($row, 'user_missing', [$username]);
                            continue;
                        }
                    }

                    $spare_parts = [
                        436,
                        680
                    ];

                    if(in_array($category, $this->invalidTopCategories) && !in_array($row, $spare_parts)) {
                        $this->add_to_row_skipped($row, 'invalid_top_level_category');
                        continue;
                    }

                    if(in_array($row, $spare_parts)) {
                        $subcategory_lvl_3 = 'Spare Parts';
                    }



                    if( empty($subcategory_lvl_3) ) {
                        $subcategory_lvl_3 = '';
                    }

                    $title_empty = false;
                    // Minimum required to create an ad title is year make and model
                    if (empty($title)) {
                        $title_empty = 'true';

                        // Try with all four
                        if (!empty($year) && !empty($make) && !empty($model) && !empty($condition)) {
                            $title = "$year $make $model $condition";
                        }
                        // Try with year, make, model
                        elseif (!empty($year) && !empty($make) && !empty($model)) {
                            $title = "$year $make $model";
                        }
                        // Try with year, make
                        elseif (!empty($year) && !empty($make)) {
                            $title = "$year $make";
                        }
                        // Try with year, model
                        elseif (!empty($year) && !empty($model)) {
                            $title = "$year $model";
                        }
                        // Try with make, model
                        elseif (!empty($make) && !empty($model)) {
                            $title = "$make $model";
                        }
                        // Try with just make
                        elseif (!empty($make)) {
                            $title = "$make";
                        }
                        // Try with just model
                        elseif (!empty($model)) {
                            $title = "$model";
                        }
                        // If all else fails, log an issue
                        else {
                            $this->add_to_row_skipped($row, 'missing_ad_title');
                            continue;
                        }
                    }

                    if(!empty($subcategory_lvl_3)) {
                        if (strpos($subcategory_lvl_3, 'Volumetric Mixers') || $subcategory_lvl_3 === 'Cementech Mobile Volumetric Mixers' || $subcategory_lvl_3 === 'Concrete Mobile Mixers' || $subcategory_lvl_3 === 'Concrete Volumetric Mixers') {
                            $subcategory_lvl_3 = 'Volumetric Mixers';
                        }

                        if (strpos($subcategory_lvl_3,'Diversion Valves') || strpos($subcategory_lvl_3,'Line Pumps') || strpos($subcategory_lvl_3,'City Pumps') || strpos($subcategory_lvl_3,'Line Pump') || $subcategory_lvl_3 === 'Concrete Trailer Line Pumps' || $subcategory_lvl_3 === 'Concrete City Pumps') {
                            $subcategory_lvl_3 = 'Line Pumps';
                        }
                    }


                    $auth_business_name = \Redux::getOption('lisfinity-options', '_auth-business-name') ?? self::DEFAULT_BUSINESS_PROFILE_PREFIX;
                    $user_id = $user->ID;

                    $business_id = $this->getBusinessID($auth_business_name, $user);
                    if ( empty($business_id) ) {
                        $this->add_to_row_skipped($row, 'no_business_id');
                        continue;
                    }

                    $user_phone = $this->getUserPhone($user_id);
                    if ($user_phone) {
                        $phone_update = update_post_meta($business_id, '_profile-phones|profile-phone|0|0|value', $user_phone);
//                        $businesses[$business_id] = [
//                            'business_id' => $business_id,
//                            'business_name' => get_post($business_id)->post_title,
//                            'phone_update' => $phone_update,
//                            'phone' => get_post_meta($business_id, '_profile-phones|profile-phone|0|0|value', true),
//                            'email' => get_post_meta($business_id, '_profile-email', true),
//                        ];
                    }

                    $post_id = $this->update_post($import_id, $title, $description_add, $user_id);

                    if( empty( $post_id ) ) {
                        $this->add_to_row_skipped($row, 'no_post_update');
                        continue;
                    }

                    $updated_gallery = null;
                    if($images) {
                        $updated_gallery = $this->add_images_to_product_gallery($gallery_images, $post_id, $title);
                    }


                    if( empty($status) ) {
                        $this->add_to_row_skipped($row, 'missing_status');
                        continue;
                    } else {
                        $updated_status = update_post_meta($post_id, '_product-status', $status);
                    }

                    $business_update = update_post_meta($post_id, '_product-business', $business_id);
                    $update_business_email = update_post_meta($business_id, '_profile-email', $user->user_email);

                    $use_current_date_for_expiration = true; // Set to true if needed

                    $current_time = current_time('timestamp');
                    $active_date = strtotime($date); // Active date is the given date

                    if ($use_current_date_for_expiration) {
                        $expired_date = strtotime('+90 days', $current_time); // Expired date is 90 days from the current time
                    } else {
                        $expired_date = strtotime('+90 days', $active_date); // Expired date is 90 days from the given date
                    }

                    $this->updated_listed = update_post_meta($post_id, '_product-listed', $active_date);
                    $this->updated_expired = update_post_meta($post_id, '_product-expiration', $expired_date);

                    $updated_payment_package = null;
                    $package_id = \Redux::getOption('lisfinity-options', '_auth-default-packages');
                    $package_id = $package_id[0];
                    if (!empty($package_id)) {
                        //Intro payment package
                        $updated_payment_package = update_post_meta($post_id, 'payment-package', $package_id);

                        $order_id = $this->order_exists_with_listing_id($post_id);
                        if ( $order_id == false) {
                            // Order with this listing ID already exists. Don't create a new one.
                            $order_id = $this->create_woo_order($user_id, $package_id, $post_id);
                        }

                        if ( ! $order_id ) {
                            $this->add_to_row_skipped($row, 'missing_order_id');
                            continue;
                        }

                        /*
                         * Check to see if there is a package in wp_lisfinity_packages already for a single post type. Since this is an import, there should only be one entry
                         */
                        $lisfintiy_package_id = 'not created';
                        if( ! $this->order_id_exists_in_wp_lisfinity_packages($order_id)) {
                            $lisfintiy_package_id = $this->update_lisfinity_packages($package_id, $post_id, $order_id, $user_id, 90, 1);
                        }
                    }

                    //TAXONOMY UPDATES
                    $category_update = update_post_meta($post_id, '_product-category', 'concrete-equipment');
                    $subcategory_lvl_3_update = null;
                    if(!empty($subcategory_lvl_3)) {
                        $subcategory_lvl_3_update = $this->update_subcats($post_id, $subcategory_lvl_3);
                    } else {
                        $missing_subcategory_lvl_3 = update_post_meta($post_id, 'missing_third_cat', true);
                    }


                    if($subcategory_lvl_3_update) {
                        $term = get_term_by('term_taxonomy_id', $subcategory_lvl_3_update[0], 'concrete-equipment-subcategory');

                        $parent = get_term_by('term_id', $term->parent, 'concrete-equipment-type');

                        if(is_object($parent) && property_exists($parent, 'slug')) {
                            $type_slugs =[];

                            $concrete_equipment_type_terms = get_terms('concrete-equipment-type', [
                                'hide_empty' => false,
                            ]);

                            foreach( $concrete_equipment_type_terms as $type ) {
                                if (is_object($type) && property_exists($type, 'slug')) {
                                    array_push($type_slugs, $type->slug);
                                }
                            }

                            if( in_array($parent->slug, $type_slugs) ) {
                                $type_term_update = wp_set_object_terms($post_id, $parent->slug, 'concrete-equipment-type');
                            }

                            if($subcategory_lvl_3_update) {
                                $subcategory_lvl_3_check = get_term_by('term_taxonomy_id', $subcategory_lvl_3_update[0], 'concrete-equipment-subcategory');
                            }

                            if($type_term_update) {
                                $type_term_update = get_term_by('term_taxonomy_id', $type_term_update, 'concrete-equipment-type');
                            }

                        }
                    }

                    $this->update_third_cat($post_id, $subcategory_lvl_3, $subcategory_lvl_2, $make);

                    if( $make ) {
                        $make_update = $this->update_makes($post_id, $make);
                    }

                    if( $model ) {
                        $model_update = $this->model_update($post_id, $model);
                    }

                    $condition = $this->determineCondition($condition);

                    $condition_update = null;
                    if( $condition ) {
                        $condition_update = $this->update_condition($post_id, $condition);
                    }

                    $equipment_update = null;
                    if( $equipment_hours ) {
                        $equipment_update = $this->equipment_update($post_id, $equipment_hours);
                    }

                    $year_update = null;
                    if( $year ) {
                        $year_update = $this->year_update($post_id, $year);
                    }

                    if ( $price ) {
                        $price = $this->convertCurrencyToNumber($price);
                        $price_update = update_post_meta($post_id, '_price', $price);
                        $reg_price_update = update_post_meta($post_id, '_regular_price', $price);
                    }

                    $new_url = get_permalink($post_id);

                    //Set woocommerce product type to listing
                    $this->update_product_type($post_id);

                    $data[$post_id] = [
                        "username" => $username,
                        "user_id" => $user_id,
                        "post_id" => $post_id,
                        "order_id" => $order_id,
                        "business_id" => $business_id,
                        "lisfinity_package_id" => $lisfintiy_package_id,
                        "title" => $title,
                        "stocknumber" => $stocknumber,
                        "auth_business_name" => $auth_business_name,
                        "user_phone" => $user_phone,
                        //"post" => get_post($post_id),
                        "status" => $status,
                        "active_date" => $active_date,
                        "expiration_date" => $expired_date,
                        "package_id" => $package_id,
//                            "all_the_makes" => $this->makes,
                        "make" => $make ?? null,
                        "model" => $model,
                        "condition" => $condition,
                        "equipment_hours" => $equipment_hours,
                        "year" => $year,
                        "price" => $price,
                        "new_url" => $new_url,
                        "description" => $description_add,
                        "updates" => [
                            'updated_gallery' => $updated_gallery,
                            "updated_status" => $updated_status,
                            "updated_business" => $business_update,
                            "updated_business_email" => $update_business_email,
                            "updated_active_date" => $this->updated_listed,
                            "updated_expiration_date" => $this->updated_expired,
                            "updated_payment_package" => $updated_payment_package,
                            "updated_subcategory_lvl_3" => $subcategory_lvl_3_update ?? '',
                            //"updated_type_term" => $type_term_update->slug,
                            "updated_make" => $make_update,
                            "updated_model" => $model_update,
                            "updated_condition" => $condition_update,
                            "updated_equipment_hours" => $equipment_update,
                            "updated_years" => $year_update,
                            "updated_price" => $price_update,
                            "updated_regular_price" => $reg_price_update,
                        ]
                    ];

                    array_push($imports, [$post_id => $data[$post_id]]);

//                    if($count == 4) {
//                        break;
//                    }

                    $count++;
                }

                $res = new \WP_REST_Response(["top", $this->skipped_rows, $imports]);
            }

            return rest_ensure_response([["count" => $count], $imports, $this->skipped_rows]);
        }
    }

    /**
     * Should check for an import id, and if the post doesn't exist, create it, otherwise update it.
     *
     * @param $import_id
     * @param $title
     * @param $description_add
     * @param $user_id
     * @return int|\WP_Error
     */
    public function update_post($import_id, $title, $description_add, $user_id) {
        $query = new \WP_Query([
            'posts_per_page'   => -1,
            'post_status'      => 'publish',
            'post_type' => 'product',
            'meta_query' => [
                [
                    'key'     => 'import_id',
                    'value'   => $import_id,
                    'compare' => '=',
                ],
            ],
        ]);

        $args = [
            'post_type' => 'product',
            'post_title' => wp_strip_all_tags($title),
            'post_name' => sanitize_title(wp_strip_all_tags($title)),
            'post_content' => $description_add,
            'post_author' => $user_id,
            "post_status" => 'publish',
        ];

        $post_id = null;

        if($query->found_posts) {
            $post_id = $query->posts[0]->ID;
            $args = array_merge($args, ['ID' => $post_id]);
            $post_id = wp_update_post($args);
        } else {
            $post_id = wp_insert_post($args);
            $import_id = update_post_meta($post_id, 'import_id', $import_id);
        }

        return $post_id;
    }


    /**
     * Update the subcategories
     *
     * @param $post_id
     * @param $subcat
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function update_subcats($post_id, $subcat) {
        $this->subcats = get_terms('concrete-equipment-subcategory', [
            'hide_empty' => false
        ]);

        $filter = [];

        if ($this->subcats) {
            foreach ($this->subcats as $cat_term) {
                $filter[$cat_term->name] = $cat_term->slug;
            }
        }

        $term_update = null;

        // If exact match exists
        if (array_key_exists($subcat, $filter)) {
            $term_update = wp_set_object_terms($post_id, $filter[$subcat], 'concrete-equipment-subcategory', false);
        } else {
            // If you want to match substrings
            foreach ($filter as $key => $value) {
                if (stripos($key, $subcat) !== false || stripos($subcat, $key) !== false) {
                    $term_update = wp_set_object_terms($post_id, $filter[$key], 'concrete-equipment-subcategory', false);
                    break;
                }
            }
        }

        return $term_update;
    }

    /**
     * Update the Lisfinity packages
     *
     * @param int $payment_package_id - the id column in wp_lisfinity_packages
     * @param int $listing_id - the id of the actual post or listing
     * @param int $order_id - the id of the order
     * @param string $user_id - the id of the user
     * @param int $products_duration - the products duration
     * @param int $products_limit - the products limit
     * @return bool|mixed|string|void
     */
    public function update_lisfinity_packages(int $payment_package_id, int $listing_id, int $order_id, string $user_id, int $products_duration = 90, int $products_limit = 1)
    {
        $customer_id = get_current_user_id();

        $values = [
            // id of the customer that made order.
            $user_id,
            // wc product id of this item.
            $payment_package_id,
            // wc order id for this item.
            $order_id,
            // limit amount of products in a package.
            carbon_get_post_meta($payment_package_id, 'package-products-limit') ?? 1,
            // current amount of submitted products in this package. (this should be only 1 for each post for new setup
            1,
            // duration of the submitted products.
            $products_duration,
            // type of the package.
            'payment_package',
            // status of the package.
            'active',
        ];

        $package_controller = new PackageController();

        $lisfinity_package_id = $package_controller->store($values);

        if (!empty($lisfinity_package_id)) {
            $promotions = carbon_get_post_meta($payment_package_id, 'package-free-promotions');

            if (!empty($promotions)) {
                $this->insert_promotions($lisfinity_package_id, $payment_package_id, $listing_id, $customer_id, $promotions, $order_id, $products_duration);
            }
        }

        return $lisfinity_package_id;
    }

    public function update_third_cat($post_id, $third_cat, $second_cat, $make) {
        if(!$this->third_cats) {
            return null;
        }

//        var_dump($this->third_cats);
//        die();

        foreach ($this->third_cats as $third_cat) {
            var_dump($third_cat);
            if($this->findSubstring($make, $third_cat->name)) {
                $parent = get_term_by('term_id', $third_cat->parent, 'concrete-equipment-type');
                var_dump($parent);
                die();
            }
        }

        var_dump($this->third_cats);
        die();

        wp_set_object_terms($post_id, $filter[$key], 'concrete-equipment-subcategory', false);

        $term = get_term_by('term_taxonomy_id', $subcategory_lvl_3_update[0], 'concrete-equipment-subcategory');

        //check if the parent is the second category
    }

    function findSubstring($haystack, $needle) {
        $words = explode(' ', $needle);
        foreach ($words as $word) {
            if (strpos(strtolower($haystack), strtolower($word)) === false) {
                return null;
            }
        }
        return [
            'substring' => $needle,
            'slugified' => slugify($needle),
            'original'  => $haystack
        ];
    }

    function slugify($string) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    /**
     * Update the makes
     *
     * @param $post_id
     * @param $make
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function update_makes($post_id, $make) {
        if(! $this->makes ) {
            return null;
        }

        $filter_makes = [];
        $make_update = null;
        $make_found = false;

        foreach ($this->makes as $model_make) {
            if (str_contains(strtolower($make), strtolower($model_make->name))) {
                $make = $model_make->name;
                $make_found = true;
            }

            $filter_makes[$model_make->name] = $model_make->slug;
        }

        // If the make is not found, insert it
        if (!$make_found) {
            $term = wp_insert_term($make, 'concrete-equipment-makes');

            // Assuming wp_insert_term was successful, fetch the slug of the newly created term
            if (!is_wp_error($term) && isset($term['term_id'])) {
                $new_term = get_term($term['term_id'], 'concrete-equipment-makes');
                $filter_makes[$make] = $new_term->slug;
            }
        }

        // If the term exists in the filter or has been inserted, update the post meta
        if ($make && array_key_exists($make, $filter_makes)) {
            $make_update = wp_set_object_terms($post_id, $filter_makes[$make], 'concrete-equipment-makes', false);
        }

        return $make_update;
    }

    /**
     * Update the model
     *
     * @param $post_id
     * @param $model
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function model_update($post_id, $model) {
        // UPDATE MODEL
        if($model) {
            $term = get_term_by('name', $model, 'concrete-equipment-model');

            if( ! $term ) {
                $insert_term = wp_insert_term($model, 'concrete-equipment-model');
            }

            $term = get_term_by('name', $model, 'concrete-equipment-model');

            $model_update = wp_set_object_terms($post_id, $term->slug, 'concrete-equipment-model',  false);
        }

        return $model_update;
    }

    /**
     * Update the equipment hours
     *
     * @param $post_id
     * @param $equipment_hours
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function equipment_update($post_id, $equipment_hours) {
        if(!$equipment_hours) {
            return null;
        }

        //UPDATE Equipment Hours
        if($equipment_hours) {
            $term = get_term_by('name', $equipment_hours, 'concrete-equipment-hours');

            if( ! $term ) {
                $insert_term = wp_insert_term($equipment_hours, 'concrete-equipment-hours');
                return $insert_term;
            }

            $term = get_term_by('name', $equipment_hours, 'concrete-equipment-hours');

            $equipment_hours_update = wp_set_object_terms($post_id, $term->slug, 'concrete-equipment-hours',  false);
        }

        return $equipment_hours_update;
    }

    /**
     * Update the year
     *
     * @param $post_id
     * @param $year
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function year_update($post_id, $year) {
        $year_update = null;
        //UPDATE Year
        if($year) {
            $term = get_term_by('name', $year, 'concrete-equipment-year');

            if( ! $term ) {
                $insert_term = wp_insert_term($year, 'concrete-equipment-year');
            }

            $term = get_term_by('name', $year, 'concrete-equipment-year');

            $year_update = wp_set_object_terms($post_id, $term->slug, 'concrete-equipment-year',  false);
        }

        return $year_update;
    }

    /**
     * Update the condition
     *
     * @param $post_id
     * @param $condition
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function update_condition($post_id, $condition) {
        if (! $condition) {
            return null;
        }

        return wp_set_object_terms($post_id, $condition, 'concrete-equipment-condition',  false);
    }

    /**
     * Update the WooCommerce product type
     *
     * @param $post_id
     * @return void
     */
    public function update_product_type($post_id) {
        $product = wc_get_product($post_id);
        $product_id = $product->get_id();
        $product_classname = \WC_Product_Factory::get_product_classname( $product_id, 'listing' );
        $new_product       = new $product_classname( $product_id );
        $new_product->save();
    }

    /**
     * Helper function to get string inbetween
     *
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    function get_string_between($string, $start, $end = null){
        // get postigion of Year_String:
        // check with the offset
        // if the offset isn't there we try '\r'
        //
        $end = $end ?? '\r';
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Check if the product exists in the table
     *
     * @param $product_id
     * @return bool
     */
    public function order_id_exists_in_wp_lisfinity_packages($order_id) {
        global $wpdb;

        // Your custom table name
        $table_name = $wpdb->prefix . 'lisfinity_packages';

        $query = $wpdb->prepare("SELECT EXISTS (SELECT 1 FROM $table_name WHERE order_id = %d LIMIT 1)", $order_id);

        // Execute the query. If the order ID exists, $result will be 1 (TRUE), otherwise it will be NULL.
        $result = $wpdb->get_var($query);

        return $result ? true : false;
    }


    /**
     * Insert promotions
     *
     * @param $package_id
     * @param $product_id
     * @param $listing_id
     * @param $user_id
     * @param $promotions
     * @param $order_id
     * @param $products_duration
     * @return void
     */
    public function insert_promotions($package_id, $product_id, $listing_id, $user_id, $promotions, $order_id, $products_duration)
    {
        $promotion_model = new PromotionsModel();
        $products_duration = carbon_get_post_meta($product_id, 'package-products-duration');
        $duration = 7;
        $expiration_date = date('Y-m-d H:i:s', strtotime("+ {$duration} days", current_time('timestamp')));
        $model = new PromotionsModel();
        if (!empty($promotions)) {
            foreach ($promotions as $promotion) {
                $promotion_object = $model->get_promotion_product($promotion);
                $promotion_product_id = $promotion_object[0]->ID;
                $promotions_values = [
                    // payment package id.
                    $package_id ?? 0,
                    // wc order id.
                    $order_id ?? 0,
                    // wc product id, id of this WooCommerce product.
                    $promotion_product_id,
                    // id of the user that made order.
                    $user_id,
                    // id of the product that this promotion has been activated.
                    $listing_id,
                    // limit or duration number depending on the type of the promotion.
                    $products_duration,
                    // count of addon promotions, this cannot be higher than value.
                    0,
                    // position of promotion on the site.
                    $promotion,
                    // type of the promotion.
                    'product',
                    // status of the promotion
                    'active',
                    // activation date of the promotion
                    current_time('mysql'),
                    // expiration date of the promotion if needed.
                    $expiration_date,
                ];

                // save promotion data in the database.
                $promotion_model->store($promotions_values);
            }
        }
    }

    /**
     * Create an order with a _listing_id in the post meta. If and order with this _listing_id already exists, don't create it again, but return the ID
     *
     * @param $post_id
     * @return false|int
     */
    public function order_exists_with_listing_id($post_id) {
        $args = array(
            'post_type'   => 'shop_order',
            'post_status' => 'any',
            'meta_key'    => '_listing_id',
            'meta_value'  => $post_id,
            'numberposts' => 1,
        );

        $orders = get_posts($args);

        if (!empty($orders)) {
            return $orders[0]->ID;  // Return the ID of the first order found
        } else {
            return false;  // No order found with the given listing ID
        }
    }

    /**
     * Create a woocommerce order for a listing
     *
     * @param $user_id
     * @param $product_id
     * @return int
     */
    public function create_woo_order($user_id, $package_id, $listing_id) {
        if( ! $user_id || ! $package_id ) {
            return null;
        }

        $product_id = $package_id;

        // Fetch user billing and shipping details.
        $user_info = get_userdata($user_id);



        $billing_address = array(
            'first_name' => $this->get_user_meta_fallback($user_id, 'billing_first_name', $user_info->first_name),
            'last_name'  => $this->get_user_meta_fallback($user_id, 'billing_last_name', $user_info->last_name),
            'company'    => $this->get_user_meta_fallback($user_id, 'billing_company'),
            'email'      => $user_info->user_email,
            'phone'      => $this->get_user_meta_fallback($user_id, 'billing_phone'),
            'address_1'  => $this->get_user_meta_fallback($user_id, 'billing_address_1'),
            'address_2'  => $this->get_user_meta_fallback($user_id, 'billing_address_2'),
            'city'       => $this->get_user_meta_fallback($user_id, 'billing_city'),
            'state'      => $this->get_user_meta_fallback($user_id, 'billing_state'),
            'postcode'   => $this->get_user_meta_fallback($user_id, 'billing_postcode'),
            'country'    => $this->get_user_meta_fallback($user_id, 'billing_country', 'US'), // Default to US
        );

        $shipping_address = array(
            'first_name' => $this->get_user_meta_fallback($user_id, 'shipping_first_name', $user_info->first_name),
            'last_name'  => $this->get_user_meta_fallback($user_id, 'shipping_last_name', $user_info->last_name),
            'company'    => $this->get_user_meta_fallback($user_id, 'shipping_company'),
            'address_1'  => $this->get_user_meta_fallback($user_id, 'shipping_address_1'),
            'address_2'  => $this->get_user_meta_fallback($user_id, 'shipping_address_2'),
            'city'       => $this->get_user_meta_fallback($user_id, 'shipping_city'),
            'state'      => $this->get_user_meta_fallback($user_id, 'shipping_state'),
            'postcode'   => $this->get_user_meta_fallback($user_id, 'shipping_postcode'),
            'country'    => $this->get_user_meta_fallback($user_id, 'shipping_country', 'US'), // Default to US
        );

        $order = wc_create_order(array(
            'status'        => apply_filters('woocommerce_default_order_status', 'pending'),
            'customer_id'   => $user_id,
            'customer_note' => '',
        ));

        $product = wc_get_product($product_id);
        $order->add_product($product, 1); // Add one quantity of the product, adjust as needed.

        $order->set_address($billing_address, 'billing');
        $order->set_address($shipping_address, 'shipping');

        $order->calculate_totals();
        $order->save();

        $order_id = $order->get_id();
        if (!empty($post_id)) {  // Assuming $listing_id contains the ID of the listing
            update_post_meta($order_id, '_listing_id', $post_id);
        }
        return $order_id;
    }

    // Helper function to safely retrieve user meta with a fallback.
    public function get_user_meta_fallback($user_id, $key, $default = '') {
        $value = get_user_meta($user_id, $key, true);
        return !empty($value) ? $value : $default;
    }
}