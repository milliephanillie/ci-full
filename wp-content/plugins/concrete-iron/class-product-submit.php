<?php

require plugin_dir_path( CONCRETEIRON ) . 'class-ci-form-submit-model.php';

class ProductSubmit {
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
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'submit_product'],
        ));
    }

    public function custom_titles( $category, $post_title, $data ) {
        $format = lisfinity_get_option( "custom-listing-$category-title" );
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
        $form_model = new CiFormSubmitModel();

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

    /**
     * Form submission handler
     * -----------------------
     *
     * @param WP_REST_Request $request_data
     *
     * @return array
     * @throws \Exception
     */
    public function submit_product( WP_REST_Request $request_data ) {
        $data       = $request_data->get_params();
        $this->data = $data;

        $is_edit = isset( $data['action'] ) && 'edit' === $data['action'];

        $agent                  = lisfinity_get_agent( get_current_user_id() );
        $this->is_edit          = $is_edit;
        $this->packages_enabled = lisfinity_packages_enabled( $agent->owner_id ?? get_current_user_id() );
        $this->has_promotions   = isset( $data['promotions'] ) && ! empty( $data['promotions'] );
        $this->has_commission   = ! empty( $data['commission_id'] );
        if ( ! empty( $data['additional_payment'] ) ) {
            $this->additional_payment = true;
        }

        if ( ! empty( $data['submission_commission'] ) ) {
            $this->submission_commission = true;
        }

        $package_model = new PackageModel();

        $result = [];

        //currently not using premium profiles
        if ( isset( $data['post_type'] ) && $data['post_type'] === 'premium_profile' ) {
            $fields            = $this->business_fields();
            $user_id           = get_post_field( 'author', $data['id'] );
            $data['business']  = $data['id'];
            $this->is_business = true;
        } else {
            $fields = $this->product_fields()['fields'];
            if ( empty( $data['business'] ) ) {
                return [ 'error' => __( 'Business is not set', 'lisfinity-core' ) ];
            }
            $business = get_post( $data['business'] );
            $user_id  = $business->post_author;

            $is_premium_business = lisfinity_business_is_premium( $business->ID );
            $default_status      = lisfinity_format_ad_status( '', $is_premium_business );
            $edit_status         = lisfinity_format_ad_status( 'edit', $is_premium_business );
        }

        if ( empty( $fields ) ) {
            return [ 'error' => __( 'The fields are not set', 'lisfinity-core' ) ];
        }

        $variable = $data['cf_category'];

        $post_title      = wp_strip_all_tags( $data['title'] ?? '' );
        $formatted_title = '';

        if ( lisfinity_is_enabled( lisfinity_get_option( "enable-custom-listing-titles-$variable" ) ) ) {
            $formatted_title = $this->custom_titles( $variable, $post_title, $data );
        }

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

            // send notifications of the product changes.
            $this->send_edit_notifications( $data['id'], $user_id );
            if ( lisfinity_is_enabled( lisfinity_get_option( 'email-listing-edited' ) ) ) {
                $this->notify_admin( $data['id'], 'update' );
            }
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

        // if we're submitting package do its own handler.
        if ( isset( $data['package_submit'] ) ) {
            $package_result = $this->submit_package( $request_data );

            return $package_result;
        }

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

//        if ( isset( $data['toPay'] ) ) {
//            $wc_helper = new WC_Helper();
//            $wc_helper->check_prerequisites();
//            WC()->cart->empty_cart();
//
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
//
//            $this->redirect      = true;
//            $checkout_page       = get_permalink( wc_get_page_id( 'checkout' ) );
//            $result['permalink'] = $checkout_page;
//        }

        // store fields data from the form.
//		$duration = $this->packages_enabled && isset( $data['package'] ) && ! $this->is_edit ? $package->products_duration : '';
        if ( ! $this->packages_enabled && ! $this->is_edit ) {
            $duration = lisfinity_get_option( 'product-duration' );
        }


        $is_business = ! empty( $data['post_type'] ) && ProfilesModel::$post_type_name === $data['post_type'];

        //TODO: fix
        $duration = 1;
        $result['store'] = $this->store_data( $id, $fields, $data, $user_id, $duration, $is_business );

        // store agent.
        carbon_set_post_meta( $id, 'product-agent', $agent->user_id ?? get_current_user_id() );

        if ( lisfinity_is_enabled( lisfinity_get_option( 'vendors-only' ) ) ) {
            carbon_set_post_meta( $id, 'product-price-sell-on-site', 1 );
        }

        // set expiration date.
//		if ( ( isset( $data['package'] ) || ! $this->packages_enabled ) && ! $this->is_edit ) {
//			$expiration_date = lisfinity_get_product_expiration_date( $duration );
//			carbon_set_post_meta( $id, 'product-expiration', $expiration_date );
//			carbon_set_post_meta( $id, 'product-listed', current_time( 'timestamp' ) );
//		}

        $result['success'] = true;

        $account_page = get_permalink( lisfinity_get_page_id( 'page-account' ) );
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

        if ( lisfinity_is_enabled( lisfinity_get_option( 'email-listing-submitted' ) ) ) {
            $this->notify_admin( $id );
        }

        return $result;
    }

    /**
     * Handler to store data from the form fields
     * ------------------------------------------
     *
     * @param integer $id - The id of the post type
     * @param array $fields - array of the form fields
     * @param array $data - array of data from the form
     * @param integer $user_id - id of the current user
     * @param string $duration - duration in days when the product from this
     * @param boolean $is_business - is post_type premium_profiles
     * package should expire
     *
     * @return mixed|string
     */
    public function store_data( $id, $fields, $data, $user_id, $duration, $is_business ) {
        $result = [];

        // set the correct product type for the post.
        // TODO: Listing::$type is usually where 'listing' is
        if ( ! $this->is_edit ) {
            wp_set_object_terms( $id, 'listing', 'product_type', true );
        }

        // add meta and taxonomy for created product post.
        $location_index = 0;
        $location_data  = [];

        foreach ( $fields as $groups ) {
            if ( empty( $groups ) ) {
                return __( 'There is some issue with iterating groups.', 'lisfinity-core' );
            }

            foreach ( $groups as $name => $field ) {

                // set category type.
                if ( ! empty( $data['cf_category'] ) ) {
                    // todo make sure that user can create a default product category if there isn't one set.
                    carbon_set_post_meta( $id, 'product-category', $data['cf_category'] );
                }

                // if location.
                if ( isset( $field['product_gallery'] ) ) {
                    delete_post_meta( $id, '_product_image_gallery' );
                    delete_post_thumbnail( $id );
                }
                if ( 'image' === $field['type'] ) {
                    $files_to_add = [];
                    if ( ! empty( $_FILES[ $name ] ) ) {
                        $files = $_FILES[ $name ];
                        foreach ( $files['name'] as $key => $value ) {
                            if ( $files['name'][ $key ] ) {
                                $file = [
                                    'name'     => $files['name'][ $key ]['file'],
                                    'type'     => $files['type'][ $key ]['file'],
                                    'tmp_name' => $files['tmp_name'][ $key ]['file'],
                                    'error'    => $files['error'][ $key ]['file'],
                                    'size'     => $files['size'][ $key ]['file']
                                ];
                                if ( ! empty( $files['tmp_name'][ $key ]['file'] ) ) {
                                    $image_info = getimagesize( $files['tmp_name'][ $key ]['file'] );
                                }

                                $file_args = lisfinity_upload_file( $file, [ 'id' => $id ] );
                                if ( ! empty( $file_args ) ) {
                                    $attachment_id = wp_insert_attachment( [
                                        'guid'           => $file_args->url,
                                        'post_mime_type' => $file_args->type,
                                        'post_title'     => $file_args->name,
                                        'post_content'   => '',
                                        'post_status'    => 'inherit'
                                    ], $file_args->url, $id );

                                    if ( ! empty( $image_info ) ) {
                                        update_post_meta( $attachment_id, 'img_width', $image_info[0] );
                                        update_post_meta( $attachment_id, 'img_height', $image_info[1] );
                                    }

                                    if ( $attachment_id ) {
                                        $files_to_add[] = $attachment_id;
                                    }
                                }
                            }
                        }
                    }

                    if ( ! empty( $data[ $name ] ) ) {
                        foreach ( $data[ $name ] as $img_url ) {
                            if ( is_string( $img_url['data_url'] ) && ! str_contains( $img_url['data_url'], 'data:' ) ) {
                                $attachment_id = lisfinity_get_attachment_id( $img_url['data_url'] );
                                if ( ! empty( $attachment_id ) ) {
                                    $files_to_add[] = $attachment_id;
                                }
                            }
                        }
                    }

                    if ( ! empty( $files_to_add ) ) {
                        // set post thumbnail.
                        set_post_thumbnail( $id, $files_to_add[0] );

                        // set gallery images.
                        $media = implode( ',', $files_to_add );
                        update_post_meta( $id, $name, $media );
                        update_post_meta( $id, '_product_image_gallery', $media );
                    }
                } else if ( 'location' === $field['type'] ) {
                    // update location meta.
                    $location_data  = $this->set_location( $id, $location_index, $data['location'], $name );
                    $location_index += 1;
                } elseif ( 'qr' === $field['type'] ) {
                    if ( ! empty( $data[ $name ] ) ) {
                        update_post_meta( $id, $name, $data[ $name ] );
                        $qr_promotion = lisfinity_get_qr_promotion();
                        if ( $qr_promotion && $qr_promotion['price'] > 0 ) {
                            WC()->cart->add_to_cart( $qr_promotion['id'], 1, '', '', [
                                'product' => $id,
                                'status'  => 'active',
                            ] );
                        }
                    }
                } elseif ( 'taxonomies' === $field['type'] ) {
                    // if taxonomy.
                    if ( ! empty( $data[ $data['cf_category'] ] ) ) {
                        if ( $this->is_edit ) {
                            $model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
                            wp_delete_object_term_relationships( $id, array_keys( $model->get_taxonomies() ) );
                            delete_post_meta( $id, '_product-category', $data['cf_category'] );
                        }

                        $this->set_taxonomies( $id, $data[ $data['cf_category'] ], $data['common'] ?? [] );
                    } else if ( ! empty( $data['common'] ) && array_key_exists( $data['cf_category'], $data ) ) {
                        $this->set_taxonomies( $id, $data[ $data['cf_category'] ], $data['common'] ?? [] );
                    }
                } elseif ( 'single_image' === $field['type'] && ! empty( $data[ $name ] ) ) {
                    update_post_meta( $id, $name, sanitize_text_field( $data[ $name ] ) );
                    if ( isset( $field['post_thumbnail'] ) ) {
                        set_post_thumbnail( $id, $data[ $name ] );
                    }
                } elseif ( 'media' === $field['type'] ) {
                    if ( isset( $data[ $name ] ) ) {
                        if ( isset( $field['type_filter'] ) ) {
                            // if media | images & files
                            $this->set_media( $id, $data[ $name ], $field['store_as'], $data, $name );
                        } else {
                            // if video.
                            $this->set_videos( $id, $data[ $name ], $data, $name );
                        }
                    }
                    if ( ! isset( $data[ $name ] ) && isset( $field['product_gallery'] ) ) {
                        delete_post_meta( $id, '_product_image_gallery' );
                        delete_post_thumbnail( $id );
                    }
                } elseif ( 'promotions' === $field['type'] ) {
                    // if promotions.
                    if ( ! empty( $data['promotions'] ) ) {
                        $this->update_promotions( $id, $data[ $name ], $duration );
                    }

                } elseif ( 'date' === $field['type'] ) {
                    // if date.
                    update_post_meta( $id, $name, strtotime( $data[ $name ] ) );
                } elseif ( 'checkbox' === $field['type'] ) {
                    // if checkbox.
                    update_post_meta( $id, $name, isset( $data[ $name ] ) && "true" === $data[ $name ] ? 1 : 0 );
                } elseif ( 'working_hours' === $field['type'] ) { // update working hours.
                    if ( ! empty( $data[ $name ] ) ) {
                        $carbon_name = str_replace( '_', '', $name );
                        update_post_meta( $id, '_lisfinity-profile-hours', json_encode( $data[ $name ] ) );
                        foreach ( $data[ $name ] as $day_name => $day ) {
                            if ( 'enable' === $day_name ) {
                                carbon_set_post_meta( $id, "{$carbon_name}-enable", sanitize_text_field( $day ) );
                            } else {
                                carbon_set_post_meta( $id, "{$carbon_name}-{$day_name}-type", $day['type'] );
                                if ( 'working' === $day['type'] ) {
                                    $day_hours = [];
                                    $count     = 0;
                                    if ( ! empty( $day ) && ! empty( $day['hours'] ) ) {
                                        foreach ( $day['hours'] as $hour ) {
                                            $day_hours[ $count ]['open']  = sanitize_text_field( $hour['open'] );
                                            $day_hours[ $count ]['close'] = sanitize_text_field( $hour['close'] );
                                            $count                        += 1;
                                        }
                                        carbon_set_post_meta( $id, "{$carbon_name}-{$day_name}-hours", $day_hours );
                                    }
                                }
                            }
                        }
                    }
                } else { // update default fields.
                    if ( ! empty( $data[ $name ] ) ) {
                        if ( is_array( $data[ $name ] ) ) {
                            $values = [];
                            foreach ( $data[ $name ] as $index => $repeatable_group ) {
                                foreach ( $repeatable_group as $key => $value ) {
                                    if ( '_type' !== $key ) {
                                        //todo should be sanitized properly.
                                        $values[ $index ][ $key ] = $value;
                                    }
                                }
                            }

                            carbon_set_post_meta( $id, lisfinity_replace_first_instance( $name, '_', '' ), $values );
                        } else {
                            if ( '_price' === $name || '_price_buy_now' === $name ) {
                                update_post_meta( $id, '_regular_price', sanitize_text_field( $data[ $name ] ) );
                                update_post_meta( $id, '_price', sanitize_text_field( $data[ $name ] ) );
                            }
                            if ( '_sale_price' === $name ) {
                                update_post_meta( $id, '_sale_price', sanitize_text_field( $data[ $name ] ) );
                                update_post_meta( $id, '_price', sanitize_text_field( $data[ $name ] ) );
                            }
                            if ( isset( $field['carbon'] ) ) {
                                $new_key = lisfinity_replace_first_instance( $data[ $name ], '_', '' );
                                update_post_meta( $id, $new_key, sanitize_text_field( $data[ $name ] ) );
                            } else {
                                update_post_meta( $id, $name, sanitize_text_field( $data[ $name ] ) );
                            }
                        }
                    } else {
                        if ( '_sale_price' === $name ) {
                            delete_post_meta( $id, '_sale_price' );
                        }
                        if ( ! is_array( $data[ $name ] ) && ! empty( get_post_meta( $id, $name, true ) ) ) {
                            delete_post_meta( $id, $name );
                        }
                    }
                }
            }
        }

        // set owner of the product
        carbon_set_post_meta( $id, 'product-owner', $user_id );
        carbon_set_post_meta( $id, 'product-business', $data['business'] );

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

        $carbon_name = lisfinity_replace_first_instance( $name, '_', '' );
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

$productsubmit = new ProductSubmit();