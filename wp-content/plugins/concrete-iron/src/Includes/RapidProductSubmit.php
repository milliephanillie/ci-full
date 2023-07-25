<?php

namespace ConcreteIron\Includes;

use Lisfinity\Models\Forms\FormSubmitModel;
use Lisfinity\REST_API\Forms\FormSubmitRoute;
use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Controllers\PackageController as PackageController;

class RapidProductSubmit {
    private $redirect = false;

    protected $data = [];

    protected $is_edit = false;

    protected $packages_enabled = false;

    protected $has_promotions = false;

    protected $has_commission = false;

    protected $is_business = false;

    protected $additional_payment = false;

    protected $submission_commission = false;

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
        ));
    }

    public function custom_titles( $category, $post_title, $data ) {
        $format = \lisfinity_get_option( "custom-listing-$category-title" );
        if ( ! empty( $format ) ) {
            preg_match_all( "/\%%([^\%%]*)\%%/", $format, $matches );
            if ( ! empty( $matches[0] ) ) {
                foreach ( $matches[0] as $index => $slug ) {
                    if ( '%%title%%' === $slug ) {
                        $formatted_title = str_replace( $slug, $post_title, $format );
                        $format          = $formatted_title;
                    } else if ( ! empty( $data[ $category ][ $matches[1][ $index ] ] ) ) {
                        $term = get_term_by( 'slug', $data[ $category ][ $matches[1][ $index ] ], $matches[1][ $index ] );

                        if ( ! empty( $term ) ) {
                            $formatted_title = str_replace( $slug, $term->name, $format );

                        } else {
                            $formatted_title = str_replace( $slug, $data[ $category ][ $matches[1][ $index ] ], $format );
                        }
                        $format = $formatted_title;
                    } else if ( str_contains( $slug, '%%common-' ) ) {
                        $taxonomy = str_replace( 'common-', '', $matches[1][ $index ] );
                        $term     = get_term_by( 'slug', $data['common'][ $taxonomy ], $taxonomy );
                        if ( ! empty( $term ) ) {
                            $formatted_title = str_replace( $slug, $term->name, $format );

                        } else {
                            $formatted_title = str_replace( $slug, $data['common'][ $matches[1][ $index ] ], $format );
                        }
                        $format = $formatted_title;
                    } else {
                        $formatted_title = str_replace( $slug, '', $format );
                        $format          = $formatted_title;
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
    public function product_fields() {
        $form_model = new FormSubmitModel();

        $form_fields = $form_model->get_fields();

        $titles = [
            'general'  => esc_html__( 'General', 'lisfinity-core' ),
            'packages'  => esc_html__( 'Packages', 'lisfinity-core' ),
            'details'  => esc_html__( 'Details', 'lisfinity-core' ),
            'price'    => esc_html__( 'Price', 'lisfinity-core' ),
            'address'  => esc_html__( 'Address', 'lisfinity-core' ),
            'media'    => esc_html__( 'Media', 'lisfinity-core' ),
        ];

        return [
            'fields' => $form_fields,
            //todo create dynamic options for this one instead of using .po files.
            'titles' => $titles,
        ];
    }

    public function save_product_id_with_order_item($item, $cart_item_key, $values, $order) {
        if (!empty($values['_listing_id'])) {
            $item->add_meta_data('_listing_id', $values['_listing_id']);
        }
    }

    public function update_product($order_id, $posted_data, $order) {
        // Assume you've saved post ID as meta data in the order
        // with a meta_key "my_custom_post_id" =; $order_id

        foreach ($order->get_items() as $item_id => $item) {
            $product_id = $item->get_meta('_listing_id', true);
            $user_id = $item->get_meta('_user_id', true);
        }

        $site_timestamp = current_time( 'timestamp' );
//
//        error_log("this the error");
//        error_log($site_timestamp);
        $gmt_offset = get_option( 'gmt_offset' );

        if (!is_numeric($gmt_offset)) {
            error_log("Invalid gmt_offset value: ", print_r($gmt_offset, true));
        } else {
            $utc_timestamp = $site_timestamp - $gmt_offset * 3600;
            $activation_date = $site_timestamp;
            error_log($site_timestamp);
            error_log("activation", print_r($activation_date, true));
            $expiration_date = date( 'Y-m-d H:i:s', strtotime( "+90 days", $utc_timestamp ) );
            error_log("expiration", print_r($expiration_date, true));
        }

        $updated_activation = update_post_meta($product_id, '_product-listed', $activation_date);
        $updated_expiration = update_post_meta( $product_id, '_product-expiration', strtotime( '+90 days', (int) current_time( 'timestamp' ) ) );

//        $this->update_lisfinity_packages();

        $updated_post = wp_publish_post($product_id);
//
//        error_log("activation", print_r($activation_date, true));
//        error_log("expiration", print_r($expiration_date, true));
//        error_log("activation", print_r($updated_activation, true));
//        error_log("expiration", print_r($updated_expiration, true));
//
//        error_log("hi there: " . print_r([['_listing_id' => $product_id], ['all_meta' => $item->get_meta()], $order_id, $posted_data,$order], true));

    }

    /**
     * Here are the fields we need to update: id (should be auto increment), user_id, product_id,
     * order_id, products_limit, products_count, products_duration, type, status, created_at, updated_at
     * Update the
     * @return void
     */
//    public function update_lisfinity_packages(int $product_id, int $order_id, int $products_limit = 9999, int $products_duration = 9999) {
//        $customer_id = get_current_user_id();
//
//        $values = [
//            // id of the customer that made order.
//            $customer_id,
//            // wc product id of this item.
//            $product_id,
//            // wc order id for this item.
//            $order_id,
//            // limit amount of products in a package.
//            $products_limit,
//            // current amount of submitted products in this package.
//            0,
//            // duration of the submitted products.
//            $products_duration,
//            // type of the package.
//            'payment_package',
//            // status of the package.
//            'inactive',
//        ];
//
//        $package_controller = new PackageController();
//
//        $package_controller->store($values);
//
//        error_log("the current user id");
//        error_log(get_current_user_id());
//    }
//
//    public function update_lisfinity_promotions() {
//
//    }

//    public function submit_product() {
//        return "sure";
//    }

    /**
     * Form submission handler
     * -----------------------
     *
     * @param WP_REST_Request $request_data
     *
     * @return array
     * @throws \Exception
     */
    public function submit_product( \WP_REST_Request $request_data ) {
        $data       = $request_data->get_params();
        $this->data = $data;

        $is_edit = isset( $data['action'] ) && 'edit' === $data['action'];

        $agent                  = \lisfinity_get_agent( get_current_user_id() );
        $this->is_edit          = $is_edit;
        $this->packages_enabled = \lisfinity_packages_enabled( $agent->owner_id ?? get_current_user_id() );
        $this->has_promotions   = isset( $data['promotions'] ) && ! empty( $data['promotions'] );
        $this->has_commission   = ! empty( $data['commission_id'] );
        if ( ! empty( $data['additional_payment'] ) ) {
            $this->additional_payment = true;
        }

        if ( ! empty( $data['submission_commission'] ) ) {
            $this->submission_commission = true;
        }

        $result = [];

        //currently not using premium profiles
        if ( isset( $data['post_type'] ) && $data['post_type'] === 'premium_profile' ) {
//            $fields            = $this->business_fields();
//            $user_id           = get_post_field( 'author', $data['id'] );
//            $data['business']  = $data['id'];
//            $this->is_business = true;
        } else {
            $fields = $this->product_fields()['fields'];
            if ( empty( $data['business'] ) ) {
                return [ 'error' => __( 'Business is not set', 'lisfinity-core' ) ];
            }
            $business = get_post( $data['business'] );
            $user_id  = $business->post_author;

            $is_premium_business = \lisfinity_business_is_premium( $business->ID );
            $default_status      = \lisfinity_format_ad_status( '', $is_premium_business );
            $edit_status         = \lisfinity_format_ad_status( 'edit', $is_premium_business );
        }

        if ( empty( $fields ) ) {
            return [ 'error' => __( 'The fields are not set', 'lisfinity-core' ) ];
        }

        $variable = $data['cf_category'];

        $post_title      = wp_strip_all_tags( $data['title'] ?? '' );
        $formatted_title = '';

//        if ( \lisfinity_is_enabled( \lisfinity_get_option( "enable-custom-listing-titles-$variable" ) ) ) {
//            $formatted_title = $this->custom_titles( $variable, $post_title, $data );
//        }

        // create basic product post and update package count.
        $args = [
            'post_type'    => $data['post_type'] ?? 'product',
            'post_title'   => ! empty( $formatted_title ) ? $formatted_title : wp_strip_all_tags( $data['title'] ),
            'post_name'    => sanitize_title( wp_strip_all_tags( $data['title'] ) ),
            'post_content' => $data['description'],
            'post_author'  => $user_id,
        ];

        if (isset($data['postStatus']) && 'draft' === $data['postStatus']) {
            $args['post_status'] = 'draft';
        } elseif ( $this->is_business ) {
            $args['post_status'] = 'publish';
        } else {
            $args['post_status'] = $this->has_promotions || $this->has_commission ? 'pending' : $default_status;
        }

        if ( $is_edit ) {
            if ( ! isset( $data['post_type'] ) ) {
                $args['post_status'] = $edit_status ?? 'publish';
            }

            //currently not using premium_profiles which is what $post_type_name refers to
//            if ( isset( $data['post_type'] ) && ProfilesModel::$post_type_name === $data['post_type'] ) {
//                unset( $args['post_author'] );
//            }

            $args['ID'] = $data['id'];
            if ( isset($data['postStatus']) && 'draft' ===  $data['postStatus'] ) {
                $args['post_status'] = 'draft';
            } elseif ( isset( $data['toPay'] ) ) {
                $args['post_status'] = 'pending';
            }
            $id = wp_update_post( $args );

//            // send notifications of the product changes.
//            $this->send_edit_notifications( $data['id'], $user_id );
//            if ( \lisfinity_is_enabled( \lisfinity_get_option( 'email-listing-edited' ) ) ) {
//                $this->notify_admin( $data['id'], 'update' );
//            }
        } else {
            $id = wp_insert_post( $args );

            // add free promotions to the product.
//			if ( ! empty( $data['package'] ) ) {
//				$wc_package_id = $package_model->where( [ [ 'id', '=', $data['package'] ] ] )->get();
//				if ( ! empty( $wc_package_id ) ) {
//					$promotions = carbon_get_post_meta( $wc_package_id[0]->product_id, 'package-free-promotions' );
//					if ( ! empty( $promotions ) ) {
//						$this->insert_promotions( $data['package'], $wc_package_id[0]->product_id, $id, $user_id, $promotions );
//					}
//				}
//			}
        }

        if ( ! $is_edit && ! isset( $data['post_type'] ) && isset( $data['promotions'] ) && ! empty( $data['promotions'] ) ) {
            update_post_meta( $id, 'ad_promotions_payment_pending', current_time( 'mysql' ) );
        }

//        // if we're submitting package do its own handler.
//        if ( isset( $data['package_submit'] ) ) {
//            $package_result = $this->submit_package( $request_data );
//
//            return $package_result;
//        }

        // assign a package id to the product.
        if ( isset( $data['package'] ) ) {
            update_post_meta( $id, '_payment-package', $data['package'] );
            update_post_meta( $id, '_package-is-subscription', isset( $data['is_subscription'] ) );
        }


        // update the payment package count.
//        if ( $this->packages_enabled && isset( $data['package'] ) && ! $this->is_edit ) {
//            $subscription_model = new SubscriptionModel();
//            $package            = $package_model->where( 'id', $data['package'] )->get();
//            if ( ! empty( $package ) && ! isset( $data['is_subscription'] ) ) {
//                $package = array_shift( $package );
//                if ( ! $this->has_promotions ) {
//                    $package_model->update_wp( [ 'products_count' => $package->products_count += 1 ], [ 'id' => $data['package'] ], [ '%d' ], [ '%s' ] );
//                }`
//            }
//            $subscription = $subscription_model->where( 'id', $data['package'] )->get();
//            if ( ! empty( $subscription ) && isset( $data['is_subscription'] ) ) {
//                if ( $subscription ) {
//                    $subscription = array_shift( $subscription );
//                    if ( ! $this->has_promotions ) {
//                        $subscription_model->update_wp( [ 'products_count' => $subscription->products_count += 1 ], [ 'id' => $data['package'] ], [ '%d' ], [ '%s' ] );
//                    }
//                }
//            }
//        }

        if ( ! isset( $id ) || is_wp_error( $id ) ) {
            $result['error']   = true;
            $result['message'] = __( 'The product post has not been created.', 'lisfinity-core' );
        }

        $result['product_id'] = $id;
        $result['product_id_test'] = "what";

        if ( isset( $data['toPay'] ) ) {
            $wc_helper = new WC_Helper();
            $wc_helper->check_prerequisites();
//            WC()->cart->empty_cart();

//            if ( $this->has_commission ) {
//                // if there's additional payment.
//                if ( $this->additional_payment ) {
//                    $special_package = carbon_get_post_meta( $package->product_id, 'package-categories' );
//                    if ( empty( $special_package ) ) {
//                        $special_package = carbon_get_post_meta( $subscription->product_id, 's-package-categories' );
//                    }
//                    $special_categories = array_column( $special_package, 'category' );
//                    $special_key        = array_search( $data['cf_category'], $special_categories );
//                    WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
//                        'custom-price'    => (float) $special_package[ $special_key ]['price'],
//                        'publish_product' => $id,
//                    ] );
//                } elseif ( $data['pay_commission'] ) {
//                    WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
//                        'type'            => 'commission',
//                        'commission'      => (float) $data['commission_price'],
//                        'publish_product' => $id,
//                    ] );
//                }
//
//                if ( $this->submission_commission ) {
//                    WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
//                        'type'            => 'commission',
//                        'commission'      => (float) $data['submission_commission'],
//                        'publish_product' => $id,
//                    ] );
//                }
//            }

            $this->redirect      = true;
            $checkout_page       = get_permalink( wc_get_page_id( 'checkout' ) );
            $result['permalink'] = $checkout_page;
        }

        // store fields data from the form.
//		$duration = $this->packages_enabled && isset( $data['package'] ) && ! $this->is_edit ? $package->products_duration : '';
        if ( ! $this->packages_enabled && ! $this->is_edit ) {
            $duration = \lisfinity_get_option( 'product-duration' );
        }


        $is_business = ! empty( $data['post_type'] ) && ProfilesModel::$post_type_name === $data['post_type'];

        //TODO: fix
        $duration = 1;
        $form_submit_model = new FormSubmitRoute();

        $result['store'] = $form_submit_model->store_data( $id, $fields, $data, $user_id, $duration, $is_business );

        // store agent.
        carbon_set_post_meta( $id, 'product-agent', $agent->user_id ?? get_current_user_id() );

        if ( \lisfinity_is_enabled( \lisfinity_get_option( 'vendors-only' ) ) ) {
            carbon_set_post_meta( $id, 'product-price-sell-on-site', 1 );
        }

        // set expiration date.
//		if ( ( isset( $data['package'] ) || ! $this->packages_enabled ) && ! $this->is_edit ) {
//			$expiration_date = \lisfinity_get_product_expiration_date( $duration );
//			carbon_set_post_meta( $id, 'product-expiration', $expiration_date );
//			carbon_set_post_meta( $id, 'product-listed', current_time( 'timestamp' ) );
//		}

        $result['success'] = true;

        $account_page = get_permalink( \lisfinity_get_page_id( 'page-account' ) );
        if ( ! isset( $data['post_type'] ) ) {
            if ( 'pending' === $default_status ) {
                $result['message'] = __( 'Your ad has been successfully submitted and will become live after the review.', 'lisfinity-core' );
            } else {
                $result['message'] = __( 'Your ad has been successfully submitted.', 'lisfinity-core' );
            }
            if ( isset( $data['toPay'] ) ) {
                $result['message'] .= __( ' You are now being redirected to checkout.', 'lisfinity-core' );
            }
            // do not change redirect if user has to go to the checkout.
            if ( ! isset( $data['toPay'] ) ) {
                $result['permalink'] = $account_page . '/ads';
                //todo it should become active only when it is finally approved by the admin.
                carbon_set_post_meta( $id, 'product-status', 'active' );
            }
        }

        if ( $is_edit ) {
            if ( isset( $data['post_type'] ) && 'premium_profile' === $data['post_type'] ) {
                $result['message'] = __( 'Your profile has been successfully edited', 'lisfinity-core' );
            } else {
                $result['message'] = __( 'Your ad has been successfully edited and will become live after the review.', 'lisfinity-core' );
                if ( 'pending' === $edit_status ) {
                    $result['message'] = __( 'Your ad has been successfully edited and will become live after the review.', 'lisfinity-core' );
                } else {
                    $result['message'] = __( 'Your ad has been successfully edited', 'lisfinity-core' );
                }
                if ( ! isset( $data['toPay'] ) ) {
                    $result['permalink'] = $account_page . '/ad/' . $id;
                }
            }
        }
        if ( $this->redirect ) {
            $result['message'] = __( 'Ad will be active once the payment is made. Redirecting to checkout...', 'lisfinity-core' );
        }

        if ( \lisfinity_is_enabled( \lisfinity_get_option( 'email-listing-submitted' ) ) ) {
            $this->notify_admin( $id );
        }

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
    protected function set_location( $id, $index, $locations, $name ) {
        $location_data                      = [];
        $location_data[ $index ]['lat']     = sanitize_text_field( $locations[ $name ]['marker']['lat'] );
        $location_data[ $index ]['lng']     = sanitize_text_field( $locations[ $name ]['marker']['lng'] );
        $location_data[ $index ]['address'] = sanitize_text_field( $locations[ $name ]['address'] ?? '' );
        $location_data[ $index ]['value']   = "{$location_data[ $index ]['lat']},{$location_data[ $index ]['lng']}";
        $location_data[ $index ]['zoom']    = 8;

        $carbon_name = \lisfinity_replace_first_instance( $name, '_', '' );
        carbon_set_post_meta( $id, $carbon_name, $location_data );

        return $location_data;
    }

    public function notify_admin( $id, $type = 'insert' ) {
        $admin_email = get_option( 'admin_email' );

        if ( $type === 'update' ) {
            $subject = sprintf( __( '%s | Listing Edited', 'lisfinity-core' ), get_option( 'blogname' ) );
            $body    = sprintf( __( 'The listing %s has been edited', 'lisfinity-core' ), '<a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . get_the_title( $id ) . '</a>' );
        } else {
            $subject = sprintf( __( '%s | New Listing Submitted', 'lisfinity-core' ), get_option( 'blogname' ) );
            $body    = sprintf( __( 'The listing %s has been submitted to your site.', 'lisfinity-core' ), '<a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . get_the_title( $id ) . '</a>' );
        }

        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
        $mail    = wp_mail( $admin_email, $subject, $body, $headers );
    }
}
