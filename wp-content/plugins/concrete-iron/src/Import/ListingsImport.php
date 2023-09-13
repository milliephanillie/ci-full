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
class ListingsImport {
    const ACF_PREFIX = 'ci_';
    const SINGLE_IMAGE_ROUTE = 'ci/v1/one_image';
    const PRODUCT_IMAGES_ROUTE = "ci/v1/product_images";
    const ORIGINAL_SOURCE_BASE_URL = 'https://concreteiron.com/concrete-equipment/';
    const DEFAULT_BUSINESS_PROFILE_PREFIX = 'Seller: %s';

    private $post_stati;
    private $taxes;
    private $cats;
    private $terms_to_tax_slug;
    private $subcats;
    private $makes;
    private $invalidTopCategories = [
        'Parts and surplus low cost ads',
        'Help Wanted Ads'
    ];

    private $skipped_rows = [];
    private $row_skipped_codes = [
        "user_missing" => "The user is not in the database.",
        'invalid_top_level_category' => 'Must be in the top level category of "Concrete Equipment."',
        'missing_category' => 'Listing is missing the third level category from Flynax.',
        'missing_ad_title' => 'Listing is missing the ad title field from Flynax.',
        'missing_stock' => 'Listing is does not have a stock quantity.',
        'missing_status' => 'Listing is missing a post status.',
        'no_post_update' => 'Failed to update or create post',
        'no_business_id' => 'Failed to create a business ID',
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
     * @param string $myadtitle The ad title.
     * @param string $import_id The import ID.
     * @return string The generated original URL.
     */
    public function generateOriginalUrl($mysecondcategory, $mythirdcategory, $myadtitle, $import_id) {
        $cat_2 = $this->transformString($mysecondcategory);
        $cat_3 = $this->transformString($mythirdcategory);
        $suff = $this->transformString($myadtitle);

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
        //$this->register_acf_fields();
//        $taxes = new Tax_Map();
//        $this->cats = $taxes->cat_map;


        $terms = get_taxonomies([
            'object_type' => [ 'product' ],
        ],
            'objects');

//        var_dump(get_option('lisfinity_custom_fields'));
//        var_dump(get_terms('placing-equipment-type'));
//        die();

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
        ));
    }

    public function validateUser($username) {
        $user = get_user_by_email($username);
        if (!$user) {
            return false;
        }
        return $user;
    }

    /**
     * Determine the condition of the imported item from flynax
     *
     * @param int|null $conditionCode
     * @return string|null
     */
    public function determineCondition(int $conditionCode = null) {
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
     * Count reasons why the row was skipped.
     *
     * @param $row_number
     * @param $reason
     * @return array|false
     */
    public function add_to_row_skipped($row_number = null, $codes = null) {
        if ( ! $row_number ||  ! $codes) {
            return false;
        }

        error_log(print_r("codes", true));
        error_log(print_r($codes, true));
        error_log(print_r($this->row_skipped_codes[$codes], true));

        array_push($this->skipped_rows, [
            'row_number' => $row_number,
            'error_message' => $this->row_skipped_codes[$codes] ?? 'Error generating reason code.',
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
        $limit= $params["limit"] ?? 1000;
        $res = ["Nothing yet."];
        $headers = [];
        $fix_cats = [];

        $this->makes = get_terms('concrete-equipment-make', [
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
                    $admin_user_name = 'admin';
                    $import_id = $column[$headers['ID']] ?? null;
                    $mycustomfields = $column[$headers['mycustomfields']] ?? null; // Read row bhttps://concreteiron.com/concrete-equipment/concrete-finishing-equipment/laser-screeds/1995-somero-s-240-30.htmly the column name.
                    $mycustomfields = trim($mycustomfields, " \t\r\n.");
                    $myadtitle = $column[$headers['myadtitle']] ?? null; // Read row by the column name.
                    $myadtitle = trim($myadtitle, " \t\r\n.");
                    $remove = 'SOLD - ';
                    $myadtitle = str_replace($remove, "",$myadtitle);
                    $description_add = $column[$headers['description_add']] ?? null;
                    $mythirdcategory = $column[$headers['mythirdcategory']] ?? null;
                    $mythirdcategory = strtok($mythirdcategory, ',');
                    $mythirdcategory = strtok($mythirdcategory, '/');
                    $mythirdcategory = trim($mythirdcategory, " \t\r\n.");
                    $mytopcategory = $column[$headers['mytopcategory']] ?? null;
                    $mytopcategory = strtok($mythirdcategory, ',');
                    $mytopcategory = strtok($mythirdcategory, '/');
                    $mytopcategory = trim($mythirdcategory, " \t\r\n.");
                    $mysecondcategory = $column[$headers['mysecondcategory']] ?? null;
                    $mysecondcategory = trim($mysecondcategory, " \t\r\n.");
                    $mycreatedate = $column[$headers['mycreatedate']] ?? null;
                    $mycreatedate = trim($mycreatedate, "\r\n.");
                    $mycreatedate = strtotime($mycreatedate);
                    $myexpirationdate = $column[$headers['myexpirationdate']] ?? null;
                    $myexpirationdate = trim($myexpirationdate, "\r\n.");
                    $myexpirationdate = strtotime($myexpirationdate);
                    $myadimageurls = $column[$headers['myadimageurls']] ?? null;
                    $myusername = $column[$headers['myusername']] ?? null;
                    $myusername = preg_replace('/\s+/', '', $myusername);
                    $status = $column[$headers['Status']] ?? null;
                    $status = trim($status, " \t\r\n.");
                    $sub_status = $column[$headers['Sub_status'] ?? null] ?? null;
                    if($sub_status) {
                        $sub_status = trim($sub_status, " \t\r\n.");
                        $status = ($sub_status === 'sold') ? 'sold' : $status;
                    }
                    $stocknumber = $column[$headers['stocknumber']] ?? null;
                    $price = $column[$headers['price']] ?? null;
                    $year = $column[$headers['built']] ?? null;
                    $year = trim($year, "\r\n.");
                    $condition = $column[$headers['condition']] ?? null;
                    $condition = trim($condition, "\r\n.");
                    $model = $column[$headers['equipment_model']] ?? null;
                    $model = trim($model, "\r\n.");
                    $make = $column[$headers['model_make']] ?? null;
                    $make = trim($make, "\r\n.");
                    $equipment_hours = $column[$headers['equipment_hours']] ?? null;
                    $equipment_hours = trim($equipment_hours, "\r\n.");

                    $package_id = \Redux::getOption('lisfinity-options', '_auth-default-packages');



                    $rows_skipped = [];

                    $user = $this->validateUser($myusername);
                    if (!$user) {
                        $this->add_to_row_skipped($row, 'user_missing');
                        continue;
                    }

                    if(in_array($mytopcategory, $this->invalidTopCategories)) {
                        $this->add_to_row_skipped($row, 'invalid_top_level_category');
                        continue;
                    }

                    if( empty($mythirdcategory) ) {
                        $this->add_to_row_skipped($row, 'missing_category');
                        continue;
                    }

                    // Minimum required to create an ad title is year make and model
                    if (empty($myadtitle)) {
                        if (!empty($year) && !empty($make) && !empty($model)) {
                            $myadtitle = "$year $make $model";

                            if (!empty($condition)) {
                                $myadtitle .= " $condition";
                            }
                        } else {
                            $this->add_to_row_skipped($row, 'missing_ad_title');
                            continue;
                        }
                    }

                    if( empty($stocknumber) ) {
                        $this->add_to_row_skipped($row, 'missing_stock');
                        continue;
                    }

                    if( empty($status) ) {
                        $this->add_to_row_skipped($row, 'missing_status');
                        continue;
                    }

                    $condition = $this->determineCondition($condition);

                    //Special Categories
                    if ($mythirdcategory === 'Concrete Mobile Mixers' || $mythirdcategory === 'Concrete Volumetric Mixers') {
                        $mythirdcategory = 'Volumetric Mixers';
                    }

                    if ($mythirdcategory === 'Concrete Diversion Valves' || $mythirdcategory === 'Concrete Trailer Line Pumps' || $mythirdcategory === 'Concrete City Pumps') {
                        $mythirdcategory = 'Line Pumps';
                    }

                    $orig_url = $this->generateOriginalUrl($mysecondcategory, $mythirdcategory, $myadtitle, $import_id);

                    $auth_business_name = \Redux::getOption('lisfinity-options', '_auth-business-name') ?? self::DEFAULT_BUSINESS_PROFILE_PREFIX;
                    $user_id = $user->ID;

                    $business_id = $this->getBusinessID($auth_business_name, $user);

                    if ( empty($business_id) ) {
                        $this->add_to_row_skipped($row, 'no_business_id');
                        continue;
                    }

                    $user_phone = $this->getUserPhone($user_id);

                    $businesses = [];
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

                    $post_id = $this->update_post($import_id, $myadtitle, $description_add, $user_id);
                    $business_update = update_post_meta($post_id, '_product-business', $business_id);
                    $update_business_email = update_post_meta($business_id, '_profile-email', $user->user_email);

                    if( empty( $post_id ) ) {
                        $this->add_to_row_skipped($row, 'no_post_update');
                        continue;
                    }

                    $status_map = [
                        'expired' => 'expired',
                        'approval' => 'active',
                        'active' => 'active',
                        'sold' => 'sold',
                        'trash' => 'trash'
                    ];

                    $activate_date = current_time('timestamp');
                    $ex_duration = 90;
                    if($status_map[$status] === 'expired') {
                        $activate_date = date('Y-m-d H:i:s', strtotime('-2 days', (int)current_time('timestamp')));
                        $ex_duration = -1;
                    }

                    if($status_map[$status] === 'sold' && array_key_exists('sold', $this->post_stati)) {
                        wp_update_post(array(
                            'ID'    =>  $post_id,
                            'post_status'   =>  'sold'
                        ));
                    }

                    $expiration_date = date('Y-m-d H:i:s', strtotime("+ {$ex_duration} days", current_time('timestamp')));

                    if (!empty($package_id)) {
                        //Intro payment package
                        $payment_package_udpate = carbon_set_post_meta($post_id, 'payment-package', $package_id);
                    }

                    $post_status_update = update_post_meta($post_id, '_product-status', $status_map[$status]);
                    $create_date_update = update_post_meta($post_id, '_product-listed', current_time('timestamp'));
                    $expire_date_update = update_post_meta($post_id, '_product-expiration', $expiration_date);

                    //TAXONOMY UPDATES

                    $cat_update = update_post_meta($post_id, '_product-category', 'concrete-equipment');

                    $subcat_update = $this->update_subcats($post_id, $mythirdcategory);

                    if($subcat_update) {
                        $term = get_term_by('term_taxonomy_id', $subcat_update[0], 'concrete-equipment-subcategory');

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

                            $subcat_update = get_term_by('term_taxonomy_id', $subcat_update, 'concrete-equipment-subcategory');

                            $type_term_update = get_term_by('term_taxonomy_id', $type_term_update, 'concrete-equipment-type');
                        }
                    }

                    if( $make ) {
                        $make_update = $this->update_makes($post_id, $make);
                    }

                    if( $model ) {
                        $model_update = $this->model_update($post_id, $model);
                    }

                    if( $condition ) {
                        $condition_update = $this->update_condition($post_id, $condition);
                    }

                    if( $equipment_hours ) {
                        $equipment_update = $this->equipment_update($post_id, $equipment_hours);
                    }

                    if( $year ) {
                        $year_update = $this->year_update($post_id, $year);
                    }

                    if ( $price ) {
                        $price = (int) trim(strtok($price, '|'));
                        $price_update = update_post_meta($post_id, '_price', $price);
                        $reg_price_update = update_post_meta($post_id, '_regular_price', $price);
                    }

                    $new_url = get_permalink($post_id);

                    $this->update_product_type($post_id);

                    $post = get_post($post_id);

                    $data[$post_id] = [
                        "post_id" => $post_id,
                        'product-status' => $status_map[$status],
                        'product-status-update' => $status_map[$status],
                        "post_title" => $post->post_title,
                        'orig_url' => $orig_url,
                        'new_url' => $new_url,
                        "cat_update" => get_post_meta($post_id, '_product-category', true),
                        "subcat_update" => $subcat_update,
                        "type_term_update" => $type_term_update,
                        "get_sub_cat_terms" => get_the_terms($post_id, 'concrete-equipment-subcategory'),
                        "get_type_terms" => get_the_terms($post_id, 'concrete-equipment-type'),
                        "make_update" => $make_update,
//                        'dates' => [
//                            strval($myexpirationdate),
//                            new DateTime('@' . $myexpirationdate),
//                        ],
//                        "product_type" => $new_product->get_type(),
//                        "post_status_update" => [
//                            "updated" => $post_status_update,
//                            "value" => get_post_meta($post_id, '_product-status', true)
//                        ],
//                        "_product-listing"      => $create_date_update,
//                        "_product-expiration"   => $expire_date_update,
//                        "business_update" => [
//                            'ID' => $business_id,
//                            'title' => $business_title,
//                        ],
//                        "cat_update" => [
//                            "updated" => $cat_update,
//                            "value" => get_post_meta($post_id, '_product-category', true)
//                        ],
//                        "price_update" => [
//                            "updated" => $price_update,
//                            "value" => get_post_meta($post_id, '_price', true)
//                        ],
//                        "reg_price_update" => [
//                            "updated" => $reg_price_update,
//                            "value" => get_post_meta($post_id, '_regular_price', true)
//                        ],
                    ];

                    array_push($imports, [$post_id => $data[$post_id]]);

//                    if($count == 44) {
//                        break;
//                    }

                    $count++;
                }



                $res = new \WP_REST_Response([$this->skipped_rows,$imports]);
            }

            return rest_ensure_response([$imports, $this->skipped_rows]);
        }
    }

    public function update_post($import_id, $myadtitle, $description_add, $user_id) {
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
            'post_title' => wp_strip_all_tags($myadtitle),
            'post_name' => sanitize_title(wp_strip_all_tags($myadtitle)),
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


    public function update_subcats($post_id, $subcat) {
        $this->subcats = get_terms('concrete-equipment-subcategory', [
            'hide_empty' => false
        ]);

        $filter =[];

        if($this->subcats) {
            foreach ($this->subcats as $cat_term) {
                $filter[$cat_term->name] = $cat_term->slug;
            }
        }

        $term_update = null;
        if (array_key_exists($subcat, $filter)) {
            $term_update = wp_set_object_terms($post_id, $filter[$subcat], 'concrete-equipment-subcategory',  false);
        }

        return $term_update;
    }

    public function update_makes($post_id, $make) {
        $filter_makes = [];

        if(! $this->makes ) {
            return null;
        }

        foreach($this->makes as $model_make) {
            if(str_contains(strtolower($make), strtolower($model_make->name))) {
                $make = $model_make->name;
            }
            $filter_makes[$model_make->name] = $model_make->slug;
        }

        $make_update = null;

        if($make && array_key_exists($make, $filter_makes)) {
            $make_update = wp_set_object_terms($post_id, $filter_makes[$make], 'concrete-equipment-make',  false);
        }

        return $make_update;
    }

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

    public function equipment_update($post_id, $equipment_hours) {
        //UPDATE Equipment Hours
        if($equipment_hours) {
            $term = get_term_by('name', $equipment_hours, 'concrete-equipment-hours');

            if( ! $term ) {
                $insert_term = wp_insert_term($equipment_hours, 'concrete-equipment-hours');
            }

            $term = get_term_by('name', $equipment_hours, 'concrete-equipment-hours');

            $equipment_hours_update = wp_set_object_terms($post_id, $term->slug, 'concrete-equipment-hours',  false);
        }
    }

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

    public function update_condition($post_id, $condition) {
        if (! $condition) {
            return null;
        }

        return wp_set_object_terms($post_id, $condition, 'concrete-equipment-condition',  false);
    }

    public function update_product_type($post_id) {
        $product = wc_get_product($post_id);
        $product_id = $product->get_id();
        $product_classname = \WC_Product_Factory::get_product_classname( $product_id, 'listing' );
        $new_product       = new $product_classname( $product_id );
        $new_product->save();
    }



    public function category_id($category_id) {

        // turn a number into a category
        // Map to a category
        // The Second category is the main category
        // The Third Category
        $cats = [
            'Boom Pumps' => ''
        ];

    }

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

    public function terms_to_tax_slug() {
        $l_cfs = get_option('lisfinity_custom_fields');
        $terms_to_tax_slug = [];
        foreach($l_cfs as $cf => $taxonomies) {
            foreach($taxonomies as $taxonomy) {
                if(is_array($taxonomy) && array_key_exists('slug', $taxonomy)) {
                    $terms = get_terms($taxonomy['slug'], ['hide_empty' => false]);
                    foreach($terms as $term) {
                        $terms_to_tax_slug[$term->name] = [
                            'slug' => $term->slug,
                            'taxonomy' => $term->taxonomy
                        ];
                    }
                }
            }
        }

        return $terms_to_tax_slug;
    }
}