<?php


namespace Lisfinity\REST_API\Forms;

use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Forms\FormBusinessSubmitModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\SubscriptionModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Models\Users\ProfilesModel;
use WP_REST_Request;
use WC_Product_Listing as Listing;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Forms\FormSubmitModel as FormModel;
use Lisfinity\Models\Forms\PackageSubmitModel as PackageForm;
use Lisfinity\Models\PackageModel as PackageModel;

class FormSubmitRoute extends Route {

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
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'package_fields'  => [
			'path'                => '/package/fields',
			'callback'            => 'package_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'product_fields'  => [
			'path'                => '/product/fields',
			'callback'            => 'product_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'business_fields' => [
			'path'                => '/business/fields',
			'callback'            => 'business_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'product_submit'  => [
			'path'                => '/product/store',
			'callback'            => 'submit_product',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function business_fields() {
		$model = new FormBusinessSubmitModel();

		return $model->get_fields();
	}

	/**
	 * Get fields for the given form
	 * -----------------------------
	 *
	 * @return mixed
	 */
	public function package_fields() {
		$form_model = new PackageForm();

		return $form_model->get_fields();
	}

	/**
	 * Get fields for the given form
	 * -----------------------------
	 *
	 * @return mixed
	 */
	public function product_fields() {
		$form_model = new FormModel();

		$form_fields = $form_model->get_fields();

        $titles = [
            'general'  => esc_html__( 'General', 'lisfinity-core' ),
            'packages'  => esc_html__( 'Packages', 'lisfinity-core' ),
            'details'  => esc_html__( 'Details', 'lisfinity-core' ),
            'price'    => esc_html__( 'Price', 'lisfinity-core' ),
            'address'  => esc_html__( 'Address', 'lisfinity-core' ),
            'media'    => esc_html__( 'Media', 'lisfinity-core' ),
            'payments' => esc_html__( 'Payments', 'lisfinity-core' ),
        ];

		return [
			'fields' => $form_fields,
			//todo create dynamic options for this one instead of using .po files.
			'titles' => $titles,
		];
	}

	/**
	 * Submit package
	 *
	 * @param $request_data
	 *
	 * @return array
	 */
	public function submit_package( $request_data ) {
		$fields  = $this->product_fields();
		$data    = $request_data->get_params();
		$user_id = get_current_user_id();
		$result  = [];

		if ( empty( $data['package_submit'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Package submit param has not been found.', 'lisfinity-core' );
		}

		if ( empty( $data ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Package data with ids has not been found.', 'lisfinity-core' );
		}

		// todo not sure if we need foreach here. To consider benefits.
		$package = false;
		foreach ( $data as $id ) {
			if ( $id !== 'true' ) {
				$package = $id;
			}
		}
		if ( $package ) {
			// todo use rest api here as we have found a solution for it.
			$result['id']       = $package;
			$result['checkout'] = get_permalink( lisfinity_get_page_id( 'page-account' ) );

			// leads to a page where we can process adding to cart and then checkout
			// as WooCommerce is not allowing it through rest api at the moment.
			$result['permalink'] = add_query_arg( 'lc-cart', $result['id'], $result['checkout'] );
		}

		return $result;
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

//        var_dump($data);
//        die();

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
		if ( $this->is_business ) {
			$args['post_status'] = 'publish';
		} else {
			$args['post_status'] = $this->has_promotions || $this->has_commission ? 'pending' : $default_status;
		}

		if ( $is_edit ) {
			if ( ! isset( $data['post_type'] ) ) {
				$args['post_status'] = $edit_status ?? 'publish';
			}
			if ( isset( $data['post_type'] ) && ProfilesModel::$post_type_name === $data['post_type'] ) {
				unset( $args['post_author'] );
			}

			$args['ID'] = $data['id'];
			if ( isset( $data['toPay'] ) ) {
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
		if ( $this->packages_enabled && isset( $data['package'] ) && ! $this->is_edit ) {
			$subscription_model = new SubscriptionModel();
			$package            = $package_model->where( 'id', $data['package'] )->get();
			if ( ! empty( $package ) && ! isset( $data['is_subscription'] ) ) {
				$package = array_shift( $package );
				if ( ! $this->has_promotions ) {
					$package_model->update_wp( [ 'products_count' => $package->products_count += 1 ], [ 'id' => $data['package'] ], [ '%d' ], [ '%s' ] );
				}
			}
			$subscription = $subscription_model->where( 'id', $data['package'] )->get();
			if ( ! empty( $subscription ) && isset( $data['is_subscription'] ) ) {
				if ( $subscription ) {
					$subscription = array_shift( $subscription );
					if ( ! $this->has_promotions ) {
						$subscription_model->update_wp( [ 'products_count' => $subscription->products_count += 1 ], [ 'id' => $data['package'] ], [ '%d' ], [ '%s' ] );
					}
				}
			}
		}

		if ( ! isset( $id ) || is_wp_error( $id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The product post has not been created.', 'lisfinity-core' );
		}

		if ( isset( $data['toPay'] ) ) {
			$wc_helper = new WC_Helper();
			$wc_helper->check_prerequisites();
			WC()->cart->empty_cart();

			if ( $this->has_commission ) {
				// if there's additional payment.
				if ( $this->additional_payment ) {
					$special_package = carbon_get_post_meta( $package->product_id, 'package-categories' );
					if ( empty( $special_package ) ) {
						$special_package = carbon_get_post_meta( $subscription->product_id, 's-package-categories' );
					}
					$special_categories = array_column( $special_package, 'category' );
					$special_key        = array_search( $data['cf_category'], $special_categories );
					WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
						'custom-price'    => (float) $special_package[ $special_key ]['price'],
						'publish_product' => $id,
					] );
				} elseif ( $data['pay_commission'] ) {
					WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
						'type'            => 'commission',
						'commission'      => (float) $data['commission_price'],
						'publish_product' => $id,
					] );
				}

				if ( $this->submission_commission ) {
					WC()->cart->add_to_cart( (int) $data['commission_id'], 1, '', '', [
						'type'            => 'commission',
						'commission'      => (float) $data['submission_commission'],
						'publish_product' => $id,
					] );
				}
			}

			$this->redirect      = true;
			$checkout_page       = get_permalink( wc_get_page_id( 'checkout' ) );
			$result['permalink'] = $checkout_page;
		}

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
		if ( ! $this->is_edit ) {
			wp_set_object_terms( $id, Listing::$type, 'product_type', true );
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

	protected function update_promotions( $id, $promotions, $duration ) {
		if ( empty( $promotions ) ) {
			return false;
		}

		foreach ( $promotions as $promotion ) {
			WC()->cart->add_to_cart( $promotion['id'], $promotion['days'], '', '', [
				'product'  => $id,
				'status'   => 'active',
				'duration' => $duration,
			] );
		}

		return true;
	}

	/**
	 * Update product videos for product post type
	 * -------------------------------------------
	 *
	 * @param $id
	 * @param $videos
	 *
	 * @return boolean
	 */
	protected function set_videos( $id, $videos, $data, $name ) {
		$videos_data = [];

		if ( empty( $videos ) ) {
			return false;
		}

		foreach ( $videos as $video ) {
			$videos_data[]['video'] = $video['url'];
		}
		if ( ! empty( $data['addon-video'] ) ) {
			$this->add_to_cart( $id, $data['addon-video'], $videos );
		}

		$new_name = lisfinity_replace_first_instance( $name, '_', '' );
		carbon_set_post_meta( $id, $new_name, $videos_data );

		return true;
	}

	/**
	 * Update product post type media files
	 * ------------------------------------
	 *
	 * @param $id
	 * @param $files
	 * @param $store_as
	 *
	 * @return boolean
	 */
	protected function set_media( $id, $files, $store_as, $data, $name ) {
		$media = [];

		if ( empty( $files ) ) {
			return false;
		}

		// if is file.
		if ( 'file' === $store_as ) {
			foreach ( $files as $file ) {
				if ( isset( $file['file'] ) ) {
					$media[]['file'] = $file['file'];
				} else {
					$media[]['file'] = $file;
				}
			}

			if ( ! empty( $data['addon-docs'] ) ) {
				$this->add_to_cart( $id, $data['addon-docs'], $media, 'yes' );
			}

			$new_name = lisfinity_replace_first_instance( $name, '_', '' );
			carbon_set_post_meta( $id, $new_name, $media );
		} else { // if is image.
			// set post thumbnail.
			set_post_thumbnail( $id, $files[0] );

			// set gallery images.
			$media = implode( ',', $files );
			update_post_meta( $id, $name, $media );

			if ( ! empty( $data['addon-image'] ) ) {
				$this->add_to_cart( $id, $data['addon-image'], $files );
			}
		}

		return true;
	}

	protected function add_to_cart( $id, $addon, $data ) {
		if ( ! empty( $addon ) ) {
			$data_count = count( $data );
			$count      = $data_count > $addon['value'] ? $data_count - $addon['value'] : false;
			if ( $count ) {
				update_post_meta( $id, "_needs-payment|{$addon['addon']}", $count );
				$wc_product_id = ! empty( $addon['wc_product_id'] ) ? $addon['wc_product_id'] : $addon['ID'];
				if ( ! empty( $wc_product_id ) ) {
					WC()->cart->add_to_cart( $wc_product_id, $count, '', '', [
						'product' => $id,
						'status'  => 'active',
						'is_edit' => $this->is_edit,
					] );

				}
			}
		}
	}

	/**
	 * Set the chosen taxonomies
	 * -------------------------
	 *
	 * @param $id
	 * @param $category
	 * @param $common
	 */
	protected function set_taxonomies( $id, $category, $common ) {
		// update chosen taxonomies types.
		if ( $category ) {
			foreach ( $category as $taxonomy => $term ) {
				if ( str_contains( $taxonomy, '|custom' ) ) {
					$tax = str_replace( '|custom', '', $taxonomy );
					wp_set_object_terms( $id, $term, $tax );

					$this->setTermParent( $term, $tax );
				} else {
					if ( $term !== 'custom' ) {
						wp_set_object_terms( $id, $term, $taxonomy );
					}
				}
			}
		}
		// update common taxonomies.
		if ( ! empty( $common ) ) {
			foreach ( $common as $taxonomy => $term ) {
				if ( str_contains( '|custom', $taxonomy ) ) {
					$tax = str_replace( '|custom', '', $taxonomy );
					wp_set_object_terms( $id, $term, $tax );

					$this->setTermParent( $term, $tax );
				} else {
					if ( $term !== 'custom' ) {
						wp_set_object_terms( $id, $term, $taxonomy );
					}
				}
			}
		}
	}

	public function setTermParent( $term_name, $taxonomy ) {
		$taxonomy_model = new TaxonomiesAdminModel();
		$options        = $taxonomy_model->get_taxonomy_options( $taxonomy );

		if ( ! empty( $options['parent'] ) ) {
			$parent = $this->data[ $this->data['cf_category'] ][ $options['parent'] ];

			$parent_term = get_term_by( 'name', $parent, $options['parent'] );

			if ( ! empty( $parent_term ) ) {
				$term = get_term_by( 'name', $term_name, $taxonomy );
				if ( ! empty( $term ) ) {
					update_term_meta( $term->term_id, 'parent_name', $parent_term->name );
					update_term_meta( $term->term_id, 'parent_slug', $parent_term->slug );
					update_term_meta( $term->term_id, 'parent_taxonomy', $parent_term->taxonomy );

					wp_update_term( $term->term_id, $taxonomy, [
						'parent' => $parent_term->term_id,
					] );
				}
			}
		}

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

	public function insert_promotions( $package_id, $product_id, $listing_id, $user_id, $promotions ) {
		$promotion_model   = new PromotionsModel();
		$products_duration = carbon_get_post_meta( $product_id, 'package-products-duration' );
		$duration          = $products_duration ?? 30;
		$expiration_date   = date( 'Y-m-d H:i:s', strtotime( "+ {$duration} days", current_time( 'timestamp' ) ) );
		$model             = new PromotionsModel();
		if ( ! empty( $promotions ) ) {
			foreach ( $promotions as $promotion ) {
				$promotion_product_id = $model->get_promotion_product( $promotion );
				$promotions_values    = [
					// payment package id.
					$promotion_product_id ?? $package_id,
					// wc order id.
					0,
					// wc product id, id of this WooCommerce product.
					$product_id,
					// id of the user that made order.
					$user_id,
					// id of the product that this promotion has been activated.
					$listing_id,
					// limit or duration number depending on the type of the promotion.
					$products_duration ?? 30,
					// count of addon promotions, this cannot be higher than value.
					0,
					// position of promotion on the site.
					$promotion,
					// type of the promotion.
					'product',
					// status of the promotion
					'active',
					// activation date of the promotion
					current_time( 'mysql' ),
					// expiration date of the promotion if needed.
					$expiration_date,
				];

				// save promotion data in the database.
				$promotion_model->store( $promotions_values );
			}
		}
	}

	/**
	 * @param $product_id
	 * @param $user_id
	 *
	 * @since 1.1.22
	 */
	public function send_edit_notifications( $product_id, $user_id ) {
		global $wpdb;

		$notification_model = new NotificationModel();
		$bidders            = lisfinity_get_product_subscribers( $product_id, $user_id );
		if ( ! empty( $bidders ) ) {
			foreach ( $bidders as $bidder ) {
				if ( 'yes' === get_user_meta( $bidder, '_email_subscription|product_change', true ) ) {
					$business = lisfinity_get_premium_profile_id( $user_id );
					// create notification.
					$notification_data = [
						'user_id'     => $bidder,
						'type'        => 1,
						'product_id'  => $product_id,
						'business_id' => $business ?? 0,
						'parent_id'   => 0,
						'parent_type' => 7,
						'status'      => 0,
					];

					$notification_model->store_notification( $notification_data );

					// send email.
					$bidder_data = get_userdata( $bidder );
					$body        = sprintf( __( 'The data has been changed for the listing: %s', 'lisfinity-core' ), get_permalink( $product_id ) );
					$headers     = [ 'Content-Type: text/html; charset=UTF-8' ];
					$mail        = wp_mail( $bidder_data->user_email, __( 'Listing Data Changed', 'lisfinity-core' ), $body, $headers );
				}
			}
		}
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
