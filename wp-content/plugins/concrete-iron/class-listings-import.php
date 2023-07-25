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

include_once 'class-tax-map.php';
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
class Listings_Import {
    const ACF_PREFIX = 'ci_';
    const SINGLE_IMAGE_ROUTE = 'ci/v1/one_image';
    const PRODUCT_IMAGES_ROUTE = "ci/v1/product_images";

    private $taxes;
    private $cats;
    private $terms_to_tax_slug;
    private $subcats;
    private $makes;

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
        $taxes = new Tax_Map();
        $this->cats = $taxes->cat_map;


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
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'ci_listings_import'],
            'args' => [],
        ));
    }

    /**
     * Loop through the csv, and update fields
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function ci_listings_import(WP_REST_Request $request)
    {
        $params = $request->get_file_params();
        $fileName = $params["file"]['tmp_name'];
        $limit = $params["limit"] ?? 1000;
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

            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if ($count > $limit) {
                    break;
                }

                if (++$row == 1) {
                    $headers = array_flip($column); // Get the column names from the header.
                    continue;
                } else {
                    // Minimum
                    $user_id = null;
                    $admin_user_name = 'admin';
                    $import_id = $column[$headers['ID']];
                    $mycustomfields = $column[$headers['mycustomfields']]; // Read row bhttps://concreteiron.com/concrete-equipment/concrete-finishing-equipment/laser-screeds/1995-somero-s-240-30.htmly the column name.
                    $mycustomfields = trim($mycustomfields, " \t\r\n.");
                    $myadtitle = $column[$headers['myadtitle']]; // Read row by the column name.
                    $myadtitle = trim($myadtitle, " \t\r\n.");
                    $remove = 'SOLD - ';
                    $myadtitle = str_replace($remove, "",$myadtitle);
                    $description_add = $column[$headers['description_add']];
                    $mythirdcategory = $column[$headers['mythirdcategory']];
                    $mythirdcategory = strtok($mythirdcategory, ',');
                    $mythirdcategory = strtok($mythirdcategory, '/');
                    $mytopcategory = $column[$headers['mytopcategory']];
                    $mytopcategory = strtok($mythirdcategory, ',');
                    $mytopcategory = strtok($mythirdcategory, '/');
                    $mytopcategory = trim($mythirdcategory, " \t\r\n.");
                    $mysecondcategory = $column[$headers['mysecondcategory']];
                    $mysecondcategory = trim($mysecondcategory, " \t\r\n.");
                    $mycreatedate = $column[$headers['mycreatedate']];
                    $mycreatedate = trim($mycreatedate, "\r\n.");
                    $mycreatedate = strtotime($mycreatedate);
                    $myexpirationdate = $column[$headers['myexpirationdate']];
                    $myexpirationdate = trim($myexpirationdate, "\r\n.");
                    $myexpirationdate = strtotime($myexpirationdate);
                    $myadimageurls = $column[$headers['myadimageurls']] ?? null;
                    $myusername = $column[$headers['myusername']];
                    $myusername = preg_replace('/\s+/', '', $myusername);
                    $status = $column[$headers['Status']];
                    $status = trim($status, " \t\r\n.");
                    $sub_status = $column[$headers['Sub_status']];
                    $sub_status = trim($sub_status, " \t\r\n.");
                    $status = ($sub_status === 'sold') ? 'sold' : $status;
                    $stocknumber = $column[$headers['stocknumber']];
                    $price = $column[$headers['price']];
                    $year = $column[$headers['built']];
                    $year = trim($year, "\r\n.");
                    $condition = $column[$headers['condition']];
                    $condition = trim($condition, "\r\n.");
                    $model = $column[$headers['equipment_model']];
                    $model = trim($model, "\r\n.");
                    $make = $column[$headers['model_make']];
                    $make = trim($make, "\r\n.");
                    $equipment_hours = $column[$headers['equipment_hours']];
                    $equipment_hours = trim($equipment_hours, "\r\n.");

                    if($mytopcategory === 'Parts and surplus low cost ads' || $mytopcategory === 'Help Wanted Ads') {
                        continue;
                    }

                    if($condition == 1) {
                        $condition = 'new';
                    } elseif($condition = 2) {
                        $condition = 'used';
                    } else {
                        $condition = null;
                    }

                    if( ! $mythirdcategory ) {
                        continue;
                    }
                    if( empty($myadtitle) ) {
                        continue;
                    }

                    if( ! $stocknumber ) {
                        return rest_ensure_response(new \WP_REST_Response([
                            'message' => "Missing stock number for",
                        ]));
                    }

                    if (! $status ) {
                        return rest_ensure_response(new \WP_REST_Response([
                            'message' => "No post status"
                        ]));
                    }


                    $cat_2 = trailingslashit(preg_replace('#[ -]+#', '-', strtolower($mysecondcategory)));
                    $cat_3 = trailingslashit(preg_replace('#[ -]+#', '-', strtolower($mythirdcategory)));
                    $suff = preg_replace('#[ -]+#', '-', strtolower($myadtitle));

                    $orig_url = 'https://concreteiron.com/concrete-equipment/' . $cat_2 . $cat_3 . $suff.'-'.$import_id.'.html';


                   // Check if the user exists, if the user doesn't exist, return an error as we must import this user
                    if(! $user = get_user_by_email($myusername) ) {
                        return rest_ensure_response(new \WP_REST_Response([
                            'message' => "The user " . $myusername ." does not yet exist!"
                        ]));
                    }



                    // This page should only be created if there is a business type.
                    if (! $page = get_page_by_title('Seller: '.$user->user_login, 'OBJECT', 'premium_profile')) {
                        $register_business = new \Lisfinity\Models\Auth\Register\RegisterModel();
                        $register_business->lisfinity_create_business_profile_for_wc_customer($user->ID, null, null);
                        $page = get_page_by_title('Seller: '.$user->user_login, 'OBJECT', 'premium_profile');
                    }

                    $business_id = null;

                    if( $page ) {
                        $business_id = $page->ID;
                    }

                    $user_id = $user->ID;
                    $shipping_phone = get_user_meta($user_id, 'shipping_phone', true);
                    $billing_phone = get_user_meta($user_id, 'billing_phone', true);
                    $user_phone = $shipping_phone ?? $billing_phone;

                    if($user->user_email) {
                        $business_email_update = update_post_meta($business_id, '_profile-email', $user->user_email);
                    }

                    $businesses = [];
                    if ($user_phone && $business_id) {
                       $phone_update = update_post_meta($business_id, '_profile-phones|profile-phone|0|0|value', $user_phone);
                        $businesses[$business_id] = [
                            'business_id' => $business_id,
                            'business_name' => $page->post_title,
                            'phone_update' => $phone_update,
                            'phone' => get_post_meta($business_id, '_profile-phones|profile-phone|0|0|value', true),
                            'email' => get_post_meta($business_id, '_profile-email', true),
                        ];
                    }

                    $post_id = $this->update_post($import_id, $myadtitle, $description_add, $user_id);

                    if( ! $post_id ) {
                        return rest_ensure_response(new \WP_REST_Response([
                            'message' => "No post_id, Unable to create business profile!"
                        ]));
                    }

                    if( $page ) {
                        $business_id = $page->ID;
                        $business_title = $page->post_title;
                        $business_update = update_post_meta($post_id, '_product-business', $business_id);
                    }

                    $status_map = [
                        'expired' => 'expired',
                        'approval' => 'active',
                        'active' => 'active',
                        'sold' => 'sold',
                        'trash' => 'trash'
                    ];

                    $post_status_update = update_post_meta($post_id, '_product-status', $status_map[$status]);
                    $create_date_update = update_post_meta($post_id, '_product-listed', strval($mycreatedate));
                    $expire_date_update = update_post_meta($post_id, '_product-expiration', strval($myexpirationdate));

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
                    $make_update = $this->update_makes($post_id, $make);
                    $model_update = $this->model_update($post_id, $model);
                    $condition_update = $this->update_condition($post_id, $condition);
                    $equipment_update = $this->equipment_update($post_id, $equipment_hours);
                    $year_update = $this->year_update($post_id, $year);

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



                $res = new \WP_REST_Response($imports);
            }

            return rest_ensure_response($res);
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

    public function create_seller($prefix = "Seller") {

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
        $product_classname = WC_Product_Factory::get_product_classname( $product_id, 'listing' );
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

$ci_import = new Listings_Import();
$ci_import->boot();
