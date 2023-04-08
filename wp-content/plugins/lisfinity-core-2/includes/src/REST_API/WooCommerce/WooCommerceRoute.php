<?php


namespace Lisfinity\REST_API\WooCommerce;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Helpers\WC_Helper;
use WP_REST_Request;

class WooCommerceRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_profile'        => [
			'path'                => '/wc/profile/',
			'callback'            => 'get_profile',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'update_profile'     => [
			'path'                => '/wc/profile/update',
			'callback'            => 'update_profile',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_country_states' => [
			'path'                => '/wc/states',
			'callback'            => 'get_country_states',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_orders'         => [
			'path'                => '/wc/orders/',
			'callback'            => 'get_orders',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_order'          => [
			'path'                => '/wc/order/',
			'callback'            => 'get_order',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_downloads'      => [
			'path'                => '/wc/downloads',
			'callback'            => 'get_downloads',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_cart_count'     => [
			'path'                => '/wc/cart-count',
			'callback'            => 'get_cart_count',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'set_currency'       => [
			'path'                => '/wc/set-currency',
			'callback'            => 'set_currency',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
	];

	public function get_profile( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		$user_data   = get_userdata( $data['id'] );
		$user        = new \stdClass();
		$business_id = lisfinity_get_premium_profile_id( $data['id'] );

		$user->first_name   = $user_data->first_name;
		$user->last_name    = $user_data->last_name;
		$user->display_name = $user_data->display_name;
		$user->user_email   = $user_data->user_email;
		$user->website      = ! empty( $user_data->user_website ) ? $user_data->user_website : carbon_get_post_meta( $business_id, 'profile-website' );
		$user->phones       = ! empty( $user_data->user_website ) ? $user_data->user_phones : carbon_get_post_meta( $business_id, 'profile-phones' );
		$user->vat_number   = ! empty( $user_data->user_vat_number ) ? $user_data->user_vat_number : carbon_get_user_meta( $data['id'], 'user_vat_number' );
		$user->sdi_code     = ! empty( $user_data->user_sdi_code ) ? $user_data->user_sdi_code : carbon_get_user_meta( $data['id'], 'user_sdi_code' );
		$user->avatar       = (int) carbon_get_user_meta( $data['id'], 'avatar' ) ?? 0;
		$user->media_limit  = lisfinity_get_maximum_upload_size_setting()['output'];

		// billing details
		$billing           = new \stdClass();
		$billing_country   = get_user_meta( $data['id'], 'billing_country', true );
		$allowed_countries = WC()->countries->get_allowed_countries();
		$states            = WC()->countries->get_states( $billing_country );

		$form                 = new \stdClass();
		$form->countries      = $allowed_countries;
		$form->country_states = ! empty( $states ) && isset( $states['AF'] ) ? false : $states;

		$user->form = $form;

		if ( ! array_key_exists( $billing_country, $allowed_countries ) ) {
			$billing_country = current( array_keys( $allowed_countries ) );
		}
		$billing->country    = $billing_country;
		$billing->first_name = get_user_meta( $data['id'], 'billing_first_name', true );
		$billing->last_name  = get_user_meta( $data['id'], 'billing_last_name', true );
		$billing->company    = get_user_meta( $data['id'], 'billing_company', true );
		$billing->address_1  = get_user_meta( $data['id'], 'billing_address_1', true );
		$billing->address_2  = get_user_meta( $data['id'], 'billing_address_2', true );
		$billing->city       = get_user_meta( $data['id'], 'billing_city', true );
		$billing->state      = get_user_meta( $data['id'], 'billing_state', true );
		$billing->postcode   = get_user_meta( $data['id'], 'billing_postcode', true );
		$billing->phone      = get_user_meta( $data['id'], 'billing_phone', true );
		$billing->email      = get_user_meta( $data['id'], 'billing_email', true );

		$user->billing = $billing;

		// shipping details
		$shipping                      = new \stdClass();
		$shipping_country              = get_user_meta( $data['id'], 'shipping_country', true );
		$states                        = WC()->countries->get_states( $billing_country );
		$form->shipping_country_states = ! empty( $states ) && isset( $states['AF'] ) ? false : $states;

		if ( ! array_key_exists( $shipping_country, $allowed_countries ) ) {
			$shipping_country = current( array_keys( $allowed_countries ) );
		}
		$shipping->country    = $shipping_country;
		$shipping->first_name = get_user_meta( $data['id'], 'shipping_first_name', true );
		$shipping->last_name  = get_user_meta( $data['id'], 'shipping_last_name', true );
		$shipping->company    = get_user_meta( $data['id'], 'shipping_company', true );
		$shipping->address_1  = get_user_meta( $data['id'], 'shipping_address_1', true );
		$shipping->address_2  = get_user_meta( $data['id'], 'shipping_address_2', true );
		$shipping->city       = get_user_meta( $data['id'], 'shipping_city', true );
		$shipping->state      = get_user_meta( $data['id'], 'shipping_state', true );
		$shipping->postcode   = get_user_meta( $data['id'], 'shipping_postcode', true );
		$shipping->phone      = get_user_meta( $data['id'], 'shipping_phone', true );
		$shipping->email      = get_user_meta( $data['id'], 'shipping_email', true );

		$user->shipping = $shipping;

		// verification.
		$user->license   = carbon_get_user_meta( $data['id'], 'license' );
		$user->residence = carbon_get_user_meta( $data['id'], 'residence' );

		return $user;
	}

	public function update_profile( WP_REST_Request $request_data ) {
		global $wpdb, $blog_id;
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['action'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'No action set', 'lisfinity-core' );

			return $result;
		}

		if ( 'general' === $data['action'] ) {
			$args = [
				'ID'           => $data['id'],
				'user_email'   => $data['email'],
				'display_name' => $data['display_name'],
			];

			if ( empty( $data['email'] ) ) {
				$result['error_field']['email'] = __( 'Email cannot be empty', 'lisfinity-core' );
			}
			if ( empty( $data['first_name'] ) ) {
				$result['error_field']['first_name'] = __( 'First Name cannot be empty', 'lisfinity-core' );
			}
			if ( empty( $data['last_name'] ) ) {
				$result['error_field']['last_name'] = __( 'Last Name cannot be empty', 'lisfinity-core' );
			}
			if ( empty( $data['display_name'] ) ) {
				$result['error_field']['display_name'] = __( 'Display Name cannot be empty', 'lisfinity-core' );
			}
			if ( lisfinity_is_enabled( lisfinity_get_option( 'checkout-sdi-code' ) ) && empty( $data['sdi_code'] ) ) {
				$result['error_field']['sdi_code'] = __( 'SDI Code cannot be empty', 'lisfinity-core' );
			}
			if ( lisfinity_is_enabled( lisfinity_get_option( 'checkout-vat' ) ) && empty( $data['vat_number'] ) ) {
				$result['error_field']['vat_number'] = __( 'VAT Number cannot be empty', 'lisfinity-core' );
			}

			if ( isset( $result['error_field'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Profile information could not be updated', 'lisfinity-core' );
			} else {
				update_user_meta( $data['id'], 'first_name', $data['first_name'] );
				update_user_meta( $data['id'], 'last_name', $data['last_name'] );

				if ( ! empty( $data['avatar'] ) ) {
					carbon_set_user_meta( $data['id'], 'avatar', $data['avatar'] );
					if ( function_exists( 'WP_User_Avatar' ) ) {
						update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_avatar', $data['avatar'] );
					}
				} else {
					carbon_set_user_meta( $data['id'], 'avatar', '' );
					if ( function_exists( 'WP_User_Avatar' ) ) {
						update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_avatar', '' );
					}
				}
				if ( ! empty( $data['website'] ) ) {
					carbon_set_user_meta( $data['id'], 'website', $data['website'] );
					update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_website', $data['website'] );
				} else {
					carbon_set_user_meta( $data['id'], 'website', '' );
					update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_website', '' );

				}
				carbon_set_user_meta( $data['id'], 'user_vat_number', $data['vat_number'] ?? '' );
				carbon_set_user_meta( $data['id'], 'user_sdi_code', $data['sdi_code'] ?? '' );

				$business_id = lisfinity_get_premium_profile_id( $data['id'] );

				$phones[] = [
					'_type'              => '_',
					'profile-phone'      => $data['phone'],
					'profile-phone-apps' => false,
				];
				if ( ! empty( $data['phone'] ) ) {
					carbon_set_post_meta( $business_id, 'profile-phones', $phones );
					carbon_set_user_meta( $data['id'], 'phones', $phones );
					update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_phones', $phones );
				} else {
					carbon_set_user_meta( $data['id'], 'phones', '' );
					carbon_set_post_meta( $business_id, 'profile-phones', [] );
					update_user_meta( $data['id'], $wpdb->get_blog_prefix( $blog_id ) . 'user_phones', [] );

				}

				wp_update_user( $args );
			}
		}

		if ( 'password' === $data['action'] ) {
			$password_min = 4;
			if ( empty( $data['password'] ) ) {
				$result['error']                   = true;
				$result['error_field']['password'] = __( 'The password cannot be empty', 'lisfinity-core' );
			} else if ( strlen( $data['password'] ) < 4 ) {
				$result['error']                   = true;
				$result['error_field']['password'] = sprintf( __( 'The password cannot be less than %d characters', 'lisfinity-core' ), $password_min );
			}

			if ( empty( $data['password'] ) ) {
				$result['error']                   = true;
				$result['error_field']['password'] = __( 'Please enter your new password', 'lisfinity-core' );
			}

			if ( $data['password'] !== $data['password_2'] ) {
				$result['error']                     = true;
				$result['error_field']['password']   = __( 'Passwords do not match', 'lisfinity-core' );
				$result['error_field']['password_2'] = __( 'Passwords do not match', 'lisfinity-core' );
			}

			if ( isset( $result['error_field'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Profile information could not be updated', 'lisfinity-core' );

				return $result;
			}


			wp_set_password( $data['password'], $data['id'] );
		}

		$type = false;
		if ( 'billing' === $data['action'] ) {
			$type = 'billing';
		}
		if ( 'shipping' === $data['action'] ) {
			$type = 'shipping';
		}
		if ( $type === $data['action'] ) {
			if ( empty( $data["{$type}_first_name"] ) ) {
				$result['error']                     = true;
				$result['error_field']['first_name'] = __( 'First Name cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_last_name"] ) ) {
				$result['error']                    = true;
				$result['error_field']['last_name'] = __( 'Last Name cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_country"] ) ) {
				$result['error']                  = true;
				$result['error_field']['country'] = __( 'Country cannot be empty', 'lisfinity-core' );
			}

			if ( isset( $data["{$type}_state"] ) && empty( $data["{$type}_state"] ) ) {
				$result['error']                = true;
				$result['error_field']['state'] = __( 'State cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_address"] ) ) {
				$result['error']                    = true;
				$result['error_field']['address_1'] = __( 'Address cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_postcode"] ) ) {
				$result['error']                   = true;
				$result['error_field']['postcode'] = __( 'Postcode cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_city"] ) ) {
				$result['error']               = true;
				$result['error_field']['city'] = __( 'City cannot be empty', 'lisfinity-core' );
			}

			if ( empty( $data["{$type}_phone"] ) ) {
				$result['error']                = true;
				$result['error_field']['phone'] = __( 'Phone cannot be empty', 'lisfinity-core' );
			}
			if ( empty( $data["{$type}_email"] ) ) {
				$result['error']                = true;
				$result['error_field']['email'] = __( 'Email cannot be empty', 'lisfinity-core' );
			}

			if ( ! isset( $result['error'] ) ) {
				update_user_meta( $data['id'], "{$type}_first_name", $data["{$type}_first_name"] );
				update_user_meta( $data['id'], "{$type}_last_name", $data["{$type}_last_name"] );
				update_user_meta( $data['id'], "{$type}_country", $data["{$type}_country"] );
				update_user_meta( $data['id'], "{$type}_postcode", $data["{$type}_postcode"] );
				update_user_meta( $data['id'], "{$type}_phone", $data["{$type}_phone"] );
				update_user_meta( $data['id'], "{$type}_city", $data["{$type}_city"] );
				update_user_meta( $data['id'], "{$type}_email", $data["{$type}_email"] );
				update_user_meta( $data['id'], "{$type}_address_1", $data["{$type}_address"] );
				if ( ! empty( $data["{$type}_address_2"] ) ) {
					update_user_meta( $data['id'], "{$type}_address_2", $data["{$type}_address_2"] );
				}
				if ( ! empty( $data["{$type}_state"] ) ) {
					update_user_meta( $data['id'], "{$type}_state", $data["{$type}_state"] );
				}
				if ( ! empty( $data["{$type}_company"] ) ) {
					update_user_meta( $data['id'], "{$type}_company", $data["{$type}_company"] );
				}
			} else {
				$result['message'] = __( 'Profile information could not be updated', 'lisfinity-core' );

				return $result;
			}
		}

		if ( 'verification' === $data['action'] ) {
			if ( ! empty( $data['license'] ) ) {
				carbon_set_user_meta( $data['id'], 'license', $data['license'] );
			}
			if ( ! empty( $data['residence'] ) ) {
				carbon_set_user_meta( $data['id'], 'residence', $data['residence'] );
			}
		}

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'Profile information successfully updated', 'lisfinity-core' );
		}

		return $result;
	}

	public function get_country_states( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$states = WC()->countries->get_states( $data['country'] );

		return ! empty( $states ) && isset( $states['AF'] ) ? false : $states;
	}

	public function get_downloads( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		$downloads = wc_get_customer_available_downloads( $data['id'] );

		return ! empty( $downloads ) ? $downloads : false;
	}

	public function get_orders( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		$orders = wc_get_orders( [
			'customer' => (int) $data['id'],
			'page'     => isset( $data['page'] ) ? $data['page'] : 1,
			'paginate' => true,
		] );

		$customer_orders = [];
		if ( 0 < $orders->total ) {
			$wc_rest = new \WC_REST_Orders_V1_Controller();
			foreach ( $orders->orders as $key => $order ) {
				$customer_orders[]                          = $wc_rest->prepare_item_for_response( $order, $request_data )->data;
				$wc_order                                   = wc_get_order( $customer_orders[ $key ]['id'] );
				$customer_orders[ $key ]['date']            = wc_format_datetime( $wc_order->get_date_created() );
				$customer_orders[ $key ]['item_count']      = $wc_order->get_item_count() - $wc_order->get_item_count_refunded();
				$customer_orders[ $key ]['formatted_total'] = $wc_order->get_formatted_order_total();
				$customer_orders[ $key ]['actions']         = wc_get_account_orders_actions( $order );
				if ( isset( $customer_orders[ $key ]['actions']['view'] ) ) {
					$customer_orders[ $key ]['actions']['view']['url'] = wc_get_endpoint_url( 'view-order', $customer_orders[ $key ]['id'] );
				}
			}
		} else {
			return false;
		}
		$orders->page   = isset( $data['page'] ) ? $data['page'] : 1;
		$orders->orders = $customer_orders;

		$orders->pages = [];
		for ( $i = 1; $i <= $orders->max_num_pages; $i += 1 ) {
			$orders->pages[] = $i;
		}

		return $orders;
	}

	public function get_order( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		if ( ! current_user_can( 'view_order', (int) $data['order'] ) ) {
			return false;
		}

		$orders = wc_get_orders( [
			'customer' => $data['id'],
			'paginate' => true,
			'limit'    => - 1,
		] );

		$customer_orders = [];
		if ( 0 < $orders->total ) {
			$wc_rest = new \WC_REST_Orders_V1_Controller();
			foreach ( $orders->orders as $key => $order ) {
				$customer_orders[] = $wc_rest->prepare_item_for_response( $order, $request_data )->data;
				if ( (int) $data['order'] === $customer_orders[ $key ]['id'] ) {
					$order                 = $customer_orders[ $key ];
					$wc_order              = wc_get_order( $order['id'] );
					$order['order_status'] = sprintf(
					/* translators: 1: order number 2: order date 3: order status */
						esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'lisfinity-core' ),
						'<mark class="order-number">' . $wc_order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<mark class="order-date">' . wc_format_datetime( $wc_order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<mark class="order-status">' . wc_get_order_status_name( $wc_order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);

					ob_start();
					wc_get_template(
						'order/order-details.php',
						[
							'order_id' => $data['order'],
						]
					);
					$order['details'] = ob_get_contents();
					ob_end_clean();

					$notes                = $wc_order->get_customer_order_notes();
					$order['order_notes'] = [];
					if ( ! empty( $notes ) ) {
						foreach ( $notes as $key => $note ) {
							$order['order_notes'][ $key ]['meta']    = date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'lisfinity-core' ), strtotime( $note->comment_date ) );
							$order['order_notes'][ $key ]['comment'] = wptexturize( $note->comment_content );
						}
					}

					return $order;
				}
			}
		}
	}

	public function get_cart_count() {
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();

		$count = WC()->cart->get_cart_contents_count();

		return $count;
	}

	public function set_currency( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		return $data;
	}

}
