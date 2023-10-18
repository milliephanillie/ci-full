<?php

namespace ConcreteIron\Includes;

use Lisfinity\Models\Forms\FormSubmitModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\REST_API\Forms\FormSubmitRoute;
use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Controllers\PackageController as PackageController;
use Lisfinity\Models\PromotionsModel as PromotionModel;

class RapidProductSubmit
{
    private $redirect = false;

    protected $data = [];

    protected $is_edit = false;

    protected $packages_enabled = false;

    protected $has_promotions = false;

    protected $has_commission = false;

    protected $is_business = false;

    protected $additional_payment = false;

    protected $submission_commission = false;

    protected $default_duration = 90;

    /**
     * CustomImport constructor
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
        add_filter('https_ssl_verify', '__return_false');
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_product_id_with_order_item'], 10, 4);
        add_action('woocommerce_checkout_order_processed', [$this, 'update_product'], 10, 3);
        //add_filter('lisfinity__submit_form_fields', [$this, 'alter_fields']);
        add_action( 'transition_post_status', [$this, 'trans_to_sold'], 10, 3 );
    }

    /**
     *
     *
     * @param $new_status
     * @param $old_status
     * @param $post
     * @return void
     */
    public function trans_to_sold( $new_status, $old_status, $post ) {
        if ( 'sold' === $new_status ) {
            // Your code here
            carbon_set_post_meta($post->ID, 'product-status', 'sold');
        }
    }

    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'product/store';

        register_rest_route($namespace, $route, array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'submit_product'],
            'permission_callback' => '__return_true',
        ));
    }

    public function custom_titles($category, $post_title, $data)
    {
        $format = \lisfinity_get_option("custom-listing-$category-title");
        if (!empty($format)) {
            preg_match_all("/\%%([^\%%]*)\%%/", $format, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $index => $slug) {
                    if ('%%title%%' === $slug) {
                        $formatted_title = str_replace($slug, $post_title, $format);
                        $format = $formatted_title;
                    } else if (!empty($data[$category][$matches[1][$index]])) {
                        $term = get_term_by('slug', $data[$category][$matches[1][$index]], $matches[1][$index]);

                        if (!empty($term)) {
                            $formatted_title = str_replace($slug, $term->name, $format);

                        } else {
                            $formatted_title = str_replace($slug, $data[$category][$matches[1][$index]], $format);
                        }
                        $format = $formatted_title;
                    } else if (str_contains($slug, '%%common-')) {
                        $taxonomy = str_replace('common-', '', $matches[1][$index]);
                        $term = get_term_by('slug', $data['common'][$taxonomy], $taxonomy);
                        if (!empty($term)) {
                            $formatted_title = str_replace($slug, $term->name, $format);

                        } else {
                            $formatted_title = str_replace($slug, $data['common'][$matches[1][$index]], $format);
                        }
                        $format = $formatted_title;
                    } else {
                        $formatted_title = str_replace($slug, '', $format);
                        $format = $formatted_title;
                    }
                }
            }
        }

        return $formatted_title ?? '';
    }

    /**
     * Get fields for the given form
     * -----------------------------
     *
     * @return mixed
     */
    public function product_fields()
    {
        $form_model = new FormSubmitModel();

        $form_fields = $form_model->get_fields();

        $titles = [
            'general' => esc_html__('General', 'lisfinity-core'),
            'packages' => esc_html__('Packages', 'lisfinity-core'),
            'details' => esc_html__('Details', 'lisfinity-core'),
            'price' => esc_html__('Price', 'lisfinity-core'),
            'address' => esc_html__('Address', 'lisfinity-core'),
            'media' => esc_html__('Media', 'lisfinity-core'),
        ];

        return [
            'fields' => $form_fields,
            //todo create dynamic options for this one instead of using .po files.
            'titles' => $titles,
        ];
    }

    public function save_product_id_with_order_item($item, $cart_item_key, $values, $order)
    {
        if (!empty($values['_listing_id'])) {
            $item->add_meta_data('_listing_id', $values['_listing_id']);
        }
    }

    public function update_product($order_id, $posted_data, $order)
    {
        foreach ($order->get_items() as $item_id => $item) {
            $product_id = $item->get_meta('_listing_id', true);
            $package_id = $item->get_product_id();

            $user_id = $item->get_meta('_user_id', true);
        }

        $site_timestamp = current_time('timestamp');
        $gmt_offset = get_option('gmt_offset');

        if (!is_numeric($gmt_offset)) {
            error_log("Invalid gmt_offset value: ", print_r($gmt_offset, true));
        } else {
            $utc_timestamp = $site_timestamp - $gmt_offset * 3600;
            $activation_date = $site_timestamp;
            $expiration_date = date('Y-m-d H:i:s', strtotime("+90 days", $utc_timestamp));
        }

        $updated_activation = update_post_meta($product_id, '_product-listed', $activation_date);
        $updated_expiration = update_post_meta($product_id, '_product-expiration', strtotime('+90 days', (int)current_time('timestamp')));

        $listing_id = $this->update_lisfinity_packages((int)$package_id, $product_id, $order_id, 90);

        if ($listing_id) {
            $updated_payment_package = update_post_meta($product_id, '_payment-package', $listing_id);
        }

        $post_status = get_post_status($product_id);

        wp_publish_post($product_id);
        $updated_product_status = update_post_meta($product_id, '_product-status', 'active');

        if (\lisfinity_is_enabled(\lisfinity_get_option('email-listing-submitted'))) {
            $this->notify_admin($product_id);
        }
    }

    /**
     * Here are the fields we need to update: id (should be auto increment), user_id, product_id,
     * order_id, products_limit, products_count, products_duration, type, status, created_at, updated_at
     * Update the
     * @return void
     */
    public function update_lisfinity_packages(int $payment_package_id, int $listing_id, int $order_id, int $products_duration = 9999, int $products_limit = 9999)
    {
        $customer_id = get_current_user_id();

        $values = [
            // id of the customer that made order.
            $customer_id,
            // wc product id of this item.
            $payment_package_id,
            // wc order id for this item.
            $order_id,
            // limit amount of products in a package.
            carbon_get_post_meta($payment_package_id, 'package-products-limit') ?? 999,
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

            update_post_meta( $order_id, 'package_processed', true );
        }

        return $lisfinity_package_id;
    }

    public function update_lisfinity_promotions(int $payment_package_id, int $order_id, $product_id, $customer_id, $business_id, $quanity, $promotion_position, $promotion_type, $status, $activation_date, $expiration_date)
    {
        $promotion_model = new PromotionModel();

        $promotions_values = [
            // payment package id.
            $payment_package_id ?? 0,
            // wc order id.
            $order_id,
            // wc product id, id of this product if needed.
            $product_id,
            // id of the user that made order.
            get_current_user_id() ?? 0,
            // id of the product that this promotion has been activated for.
            0,
            // limit or duration number depending on the type of the promotion.
            $quanity,
            // number of products that used addon promotions type, this shouldn't be higher than value.
            1,
            // position of promotion on the site.
            $promotion_position,
            // type of the promotion.
            $promotion_type,
            // status of the promotion
            $status,
            // activation date of the promotion
            $activation_date,
            // expiration date of the promotion if needed.
            $expiration_date,
        ];

        $lisfinity_package_id = $promotion_model->store($promotions_values);

        return $lisfinity_package_id;
    }

    /**
     * Genreate a unique post name (permalink) if the ad's post title and permalink already exist.
     *
     * @param $post_name
     * @param $post_type
     * @return mixed|string
     */
    public function generate_unique_post_name($post_name, $post_type = 'product') {
        global $wpdb;

        $suffix = 1;
        $unique_post_name = $post_name;

        // Loop to check existing post_names
        while ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_name = %s AND post_type = %s", $unique_post_name, $post_type))) {
            $unique_post_name = "{$post_name}-{$suffix}";
            $suffix++;
        }

        return $unique_post_name;
    }


    /**
     * Form submission handler
     * -----------------------
     *
     * @param WP_REST_Request $request_data
     *
     * @return array
     * @throws \Exception
     */
    public function submit_product(\WP_REST_Request $request_data)
    {
        $data = $request_data->get_params();
        $this->data = $data;

        $is_edit = isset($data['action']) && 'edit' === $data['action'];
        if($data['toPay'] == false || $data['toPay'] == 'false') {
            unset($data['toPay']);
        }

        $agent = \lisfinity_get_agent(get_current_user_id());
//        error_log(get_current_user_id());
//        error_log("not agent id");

        $this->is_edit = $is_edit;
        $this->packages_enabled = \lisfinity_packages_enabled($agent->owner_id ?? get_current_user_id());
        $this->has_promotions = isset($data['promotions']) && !empty($data['promotions']);
        $this->has_commission = !empty($data['commission_id']);

        if (!empty($data['additional_payment'])) {
            $this->additional_payment = true;
        }

        if (!empty($data['submission_commission'])) {
            $this->submission_commission = true;
        }

        $result = [];


        $fields = $this->product_fields()['fields'];
        if (empty($data['business'])) {
            return ['error' => __('Business is not set', 'lisfinity-core')];
        }
        $business = get_post($data['business']);
        $user_id = $business->post_author;

        $is_premium_business = false;
        $default_status = \lisfinity_format_ad_status('', $is_premium_business);
        $edit_status = \lisfinity_format_ad_status('edit', $is_premium_business);


        if (empty($fields)) {
            return ['error' => __('The fields are not set', 'lisfinity-core')];
        }

        $variable = $data['cf_category'];

        $post_title = wp_strip_all_tags($data['title'] ?? '');
        $formatted_title = '';

        if (\lisfinity_is_enabled(\lisfinity_get_option("enable-custom-listing-titles-$variable"))) {
            $formatted_title = $this->custom_titles($variable, $post_title, $data);
        }

        $unique_post_name = $this->generate_unique_post_name(sanitize_title(wp_strip_all_tags($data['title'])), $data['post_type'] ?? 'product');

        // create basic product post and update package count.
        $args = [
            'post_type' => $data['post_type'] ?? 'product',
            'post_title' => !empty($formatted_title) ? $formatted_title : wp_strip_all_tags($data['title']),
            'post_name' => $unique_post_name,
            'post_content' => $data['description'],
            'post_author' => $user_id,
            'post_status' => 'incomplete',
        ];

        $account_page = get_permalink( lisfinity_get_page_id( 'page-account' ) );

        if ($is_edit) {
            $args['ID'] = $id = $data['id'];
            $args['post_status'] = $edit_status ?? 'publish';

            if ($id) {
                $expires = carbon_get_post_meta($id, 'product-expiration');
                $is_expired = $expires < current_time('timestamp');
                $result['is_expired'] = $is_expired;

                if ($is_expired) {
                    $args['post_status'] = 'draft';
                }
            }

            $id = wp_update_post($args);


            if (!isset($data['toPay'])) {
                $result['permalink'] = $account_page . '/ad/' . $id;
            }

            if ((!isset($data['toPay']) || $data['toPay'] === 'false' || $data['toPay'] === false) && ($is_expired === 'false' || $is_expired === false)) {
                $result['permalink'] = $account_page . '/ad/' . $id;
                $result['redirect'] = false;
                $result['toPay'] = $data['toPay'] ?? false;
                $result['post_status'] = $data['postStatus'];
                $this->redirect = false;
                $result['message'] = __( 'Your ad has been successfully edited', 'lisfinity-core' );
            }


            // send notifications of the product changes.
//            $this->send_edit_notifications($data['id'], $user_id);
//            if (\lisfinity_is_enabled(\lisfinity_get_option('email-listing-edited'))) {
//                $this->notify_admin($data['id'], 'update');
//            }
        } else {
            $id = wp_insert_post($args);
            carbon_set_post_meta($id, 'product-status', 'incomplete');

            $site_timestamp = current_time('timestamp');
            $gmt_offset = get_option('gmt_offset');

            if (!is_numeric($gmt_offset)) {
                error_log("Invalid gmt_offset value: ", print_r($gmt_offset, true));
            } else {
                $utc_timestamp = $site_timestamp - $gmt_offset * 3600;
                $activation_date = $site_timestamp;
                $expiration_date = date('Y-m-d H:i:s', strtotime("+90 days", $utc_timestamp));
            }

            $updated_activation = update_post_meta($id, '_product-listed', $activation_date);
            $updated_expiration = update_post_meta($id, '_product-expiration', strtotime('+90 days', (int)current_time('timestamp')));
        }

        // assign a package id to the product.
        if (isset($data['package'])) {
            update_post_meta($id, '_payment-package', $data['package']);
            update_post_meta($id, '_package-is-subscription', isset($data['is_subscription']));
        }


        if (!isset($id) || is_wp_error($id)) {
            $result['error'] = true;
            $result['message'] = __('The product post has not been created.', 'lisfinity-core');
        }

        $result['product_id'] = $id;


        if (isset($data['toPay']) && ($data['toPay'] != false && $data['toPay'] != 'false') ) {
            $wc_helper = new WC_Helper();
            $wc_helper->check_prerequisites();

            $this->redirect = true;
            $checkout_page = get_permalink(wc_get_page_id('checkout'));
            $result['permalink'] = $checkout_page;
        }

        $form_submit_model = new FormSubmitRoute();

        $duration = 90;
        $is_business = false;
        $result['store'] = $form_submit_model->store_data($id, $fields, $data, $user_id, $duration, $is_business);

        // store agent.
        carbon_set_post_meta($id, 'product-agent', $agent->user_id ?? get_current_user_id());

        if (\lisfinity_is_enabled(\lisfinity_get_option('vendors-only'))) {
            carbon_set_post_meta($id, 'product-price-sell-on-site', 1);
        }

        $result['success'] = true;


        if ($this->redirect) {
            $result['message'] = __('Ad will be active once the payment is made. Redirecting to checkout...', 'lisfinity-core');
        }

        $result['redirect'] = $this->redirect;

        return $result;
    }

    /**
     * Update the product post location
     * --------------------------------
     *
     * @param $id
     * @param $index
     * @param $locations
     * @param $name
     *
     * @return array
     */
    protected function set_location($id, $index, $locations, $name)
    {
        $location_data = [];
        $location_data[$index]['lat'] = sanitize_text_field($locations[$name]['marker']['lat']);
        $location_data[$index]['lng'] = sanitize_text_field($locations[$name]['marker']['lng']);
        $location_data[$index]['address'] = sanitize_text_field($locations[$name]['address'] ?? '');
        $location_data[$index]['value'] = "{$location_data[ $index ]['lat']},{$location_data[ $index ]['lng']}";
        $location_data[$index]['zoom'] = 8;

        $carbon_name = \lisfinity_replace_first_instance($name, '_', '');
        carbon_set_post_meta($id, $carbon_name, $location_data);

        return $location_data;
    }

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


    public function notify_admin($id, $type = 'insert')
    {
        $admin_email = get_option('admin_email');

        if ($type === 'update') {
            $subject = sprintf(__('%s | Listing Edited', 'lisfinity-core'), get_option('blogname'));
            $body = sprintf(__('The listing %s has been edited', 'lisfinity-core'), '<a href="' . esc_url(get_edit_post_link($id)) . '">' . get_the_title($id) . '</a>');
        } else {
            $subject = sprintf(__('%s | New Listing Submitted', 'lisfinity-core'), get_option('blogname'));
            $body = sprintf(__('The listing %s has been submitted to your site.', 'lisfinity-core'), '<a href="' . esc_url(get_edit_post_link($id)) . '">' . get_the_title($id) . '</a>');
        }

        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $mail = wp_mail($admin_email, $subject, $body, $headers);
    }
}
