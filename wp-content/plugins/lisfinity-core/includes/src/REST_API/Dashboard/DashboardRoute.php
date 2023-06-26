<?php


namespace Lisfinity\REST_API\Dashboard;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Controllers\PackageController;
use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Models\Agents\AgentModel;
use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Forms\FormBusinessSubmitModel;
use Lisfinity\Models\Messages\MessageModel;
use Lisfinity\Models\Messages\ChatModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\PackageModel;
use Lisfinity\Models\ProductModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\SubscriptionModel;
use Lisfinity\Models\Vendors\PayoutsModel;
use Lisfinity\REST_API\Products\ProductsRoute;
use WP_REST_Request;
use WC_Product_Payment_Subscription as Subscription_Package;

class DashboardRoute extends Route {

	private $agent;
	private $ads;
	private $messages;
	private $bids;
	private $packages;
	private $active_packages;
	private $promotions;
	private $promotion_products;
	private $bookmarks;
	private $notifications;
	private $commissions;

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_business'        => [
			'path'                => '/dashboard/business/',
			'callback'            => 'get_business',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_all_ads'         => [
			'path'                => '/dashboard/all-ads/',
			'callback'            => 'get_all_ads_request',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_product'         => [
			'path'                => '/dashboard/product/',
			'callback'            => 'get_product',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'get_notifications'   => [
			'path'                => '/dashboard/notifications/',
			'callback'            => 'get_notifications',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'purchase_promotion'  => [
			'path'                => '/dashboard/product/purchase-promotion',
			'callback'            => 'purchase_promotion',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'purchase_package'    => [
			'path'                => '/dashboard/purchase-package',
			'callback'            => 'purchase_package',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'purchase_premium'    => [
			'path'                => '/dashboard/purchase-premium',
			'callback'            => 'purchase_premium',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'purchase_ad_renewal' => [
			'path'                => '/dashboard/purchase-ad-renewal',
			'callback'            => 'renew_ad',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'purchase_commission' => [
			'path'                => '/dashboard/purchase-commission',
			'callback'            => 'purchase_commission',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'upgrade_profile'     => [
			'path'                => '/dashboard/upgrade-profile',
			'callback'            => 'upgrade_profile',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function get_business( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		// todo to make functionality in case this is an agent.
		$this->agent        = lisfinity_get_agent( $data['id'] );
		$data['id']         = $this->agent ? $this->agent->owner_id : $data['id'];
		$business           = new \stdClass();
		$business->user_id  = $data['id'];
		$business->business = $this->get_business_data( $this->agent->owner_id ?? $business->user_id );

		$business->user    = $this->get_user( $this->agent->user_id ?? $business->user_id );
		$business->options = $this->get_theme_options();

		if ( ! $business->business ) {
			//todo Create an automatic email for administrator with the current user information so that administrator can create Premium Profile for the user.
			return [
				'error'    => true,
				'message'  => __( 'Business Profile has not been set for the user. If you are an admin please create a Business Profile and set the current user as an author, otherwise please contact the site administrator to report the issue.', 'lisfinity-core' ),
				'business' => $business,
				'redirect' => add_query_arg( [ 'e' => 'no-business' ], home_url( '/' ) ),
			];
		}

		$this->bids       = $this->get_bids( $data['id'] );
		$this->messages   = $this->get_unread_messages( $data['id'] );
		$this->promotions = $this->get_promotions( $data['id'] );

		$business->bookmarks            = $this->format_ads_meta( lisfinity_get_bookmarks( $business->user_id ), $business->user_id, true );
		$business->subscriptions        = lisfinity_get_user_subscriptions( $business->user_id );
		$business->subscription_details = lisfinity_get_user_subscription_details();
		$business->saved_searches       = lisfinity_get_saved_searches( $business->user_id );
		$business->agents               = $this->get_business_agents( $business->business->ID );

		// has to be after bids, messages and promotions.
		$this->ads                = $this->get_all_ads( $business->user_id, true, $data );
		$this->promotion_products = $this->get_promotion_products();
		$this->packages           = $this->get_packages();
		$this->active_packages    = $this->get_active_packages( $business->user_id );

		$business->stats          = $this->get_overall_stats( $business->user_id );
		$business->expiring       = $this->get_expiring_ads();
		$business->ads            = $this->ads;
		$ads_per_page             = lisfinity_get_option( 'dashboard-products-per-page' );
		$business->ads_pagination = [
			'page'     => 1,
			'per_page' => (int) $ads_per_page,
			'maxPages' => ! empty( $this->ads ) ? ceil( count( $this->ads ) / (int) $ads_per_page ) : 1,
		];

		$business->promotions         = $this->format_promotions_meta( $this->promotions );
		$business->promotion_products = $this->promotion_products;
		$business->packages           = $this->packages;
		$business->active_packages    = $this->active_packages;
		$business->bids               = $this->bids;
		$business->messages           = $this->messages;
		$business->banned             = $this->format_banned_users_meta( carbon_get_post_meta( $business->business->ID, 'blocked-profiles' ) );

		$business->commissions = $this->get_commissions( $business->business->ID );

		return $business;
	}

	public function get_notifications( WP_REST_Request $request_data ) {
		$data          = $request_data->get_params();
		$notifications = [];

		if ( empty( $data['business'] ) ) {
			$notifications['error']   = true;
			$notifications['message'] = __( 'The business ID has not been provided.', 'lisfinity-core' );

			return $notifications;
		}

		$model         = new NotificationModel();
		$notifications = $model->where( [
			[ 'business_id', $data['business'] ],
			[ 'status', 0 ],
		] )->get( '', 'ORDER BY created_at DESC' );

		return ! empty( $notifications ) ? $this->format_notifications_data( $notifications ) : [];
	}

	protected function format_notifications_data( $notifications ) {
		foreach ( $notifications as $notification ) {
			$notification->created_human     = human_time_diff( strtotime( $notification->created_at ), current_time( 'timestamp' ) );
			$notification->type_human        = lisfinity_get_human_notification_master_type( $notification->type );
			$notification->parent_type_human = lisfinity_get_human_notification_type( $notification->parent_type );
			$notification->parent_type_title = lisfinity_get_human_notification_type_title( $notification->parent_type );

			if ( $notification->parent_type == 1 ) {
				$chat_model    = new ChatModel();
				$message_model = new MessageModel();
				$my_chats      = $chat_model->where( 'owner_id', $notification->business_id )->orWhere( [
					[ 'sender_id', '=', $notification->business_id ],
				] )->get( '' );
				$chats         = [];
				foreach ( $my_chats as $chat ) {
					$chats[] = $chat->id;
				}
				$my_chats = implode( ',', $chats );
				$message  = $message_model->where( [
					[ 'sender_id', '<>', $notification->business_id ],
					[ 'status', '<>', 1 ],
					[ 'chat_id', 'IN', "({$my_chats})" ],
					[ 'created_at', $notification->created_at ],
				], '', false )->get();

				$notification->data = $message_model->format_message_data( array_shift( $message ) );
			} else if ( $notification->parent_type == 2 ) {
				$bid_model          = new BidModel();
				$bid                = $bid_model->where( [
					[ 'bidder_id', $notification->user_id ],
					[ 'product_id', $notification->product_id ],
					[ 'created_at', $notification->created_at ],
				] )->get();
				$notification->data = $bid_model->format_bid_data( array_shift( $bid ) );
			} else {
				if ( 0 !== $notification->user_id ) {
					$user_data                  = get_userdata( $notification->user_id );
					$premium_profile            = lisfinity_get_premium_profile_id( $notification->user_id );
					$meta_data['post_title']    = get_the_title( $notification->parent_id );
					$meta_data['thumbnail']     = has_post_thumbnail( $premium_profile ) ? get_the_post_thumbnail_url( $premium_profile ) : lisfinity_get_avatar_url( $notification->user_id );
					$meta_data['product_title'] = get_the_title( $notification->product_id );
					$meta_data['permalink']     = get_permalink( $notification->product_id );
					$notification->data         = $meta_data;
				}
			}
		}

		return $notifications;
	}

	public function get_commissions( $business_id ) {
		global $wpdb;

		$response = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '%d' AND meta_key LIKE 'commission%'", $business_id ) );

		$result = [];
		if ( ! empty( $response ) ) {
			$args               = [
				'post_type' => 'product',
				'tax_query' => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'name',
						'terms'    => \WC_Product_Commission::$type,
						'operator' => 'IN',
					],
				],
				'fields'    => 'ids',
			];
			$commission_product = get_posts( $args );
			if ( empty( $commission_product ) ) {
				return [
					'error'   => true,
					'message' => esc_html__( 'Commission product has not been created', 'lisfinity-core' )
				];
			}
			$commission_product = array_shift( $commission_product );
			$commission_product = wc_get_product( $commission_product );
			if ( ! empty ( $commission_product ) ) {
				$commission    = $commission_product->get_price();
				$is_percentage = get_post_meta( $commission_product->get_id(), '_is_percentage', true );
				foreach ( $response as $r ) {
					$data    = maybe_unserialize( $r );
					$product = wc_get_product( $data['product'] );
					if ( ! empty( $product ) ) {
						if ( $is_percentage ) {
							$commission = ( $commission / 100 ) * $product->get_price();
						}

						$result[ $data['product'] ] = [
							'id'               => $data['product'],
							'title'            => get_the_title( $data['product'] ),
							'price'            => $product->get_price_html(),
							'commission_price' => $commission_product->get_price(),
							'commission'       => lisfinity_get_price_html( $commission ),
							'commission_due'   => $commission,
							'product_id'       => $commission_product->get_id(),
							'is_percentage'    => 'yes' === $is_percentage,
						];
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Return only near expiring listings
	 * ----------------------------------
	 *
	 * @return array
	 */
	public function get_expiring_ads() {
		$expiring = [];
		$days     = lisfinity_get_option( 'near-expiration-days', '30' );
		if ( ! empty ( $this->ads ) ) {
			foreach ( $this->ads as $ad ) {
				if ( $ad['expires'] < strtotime( "+{$days} days", current_time( 'timestamp' ) ) ) {
					$expiring[] = $ad;
				}
			}
		}

		return $expiring;
	}

	public function get_business_data( $user_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE `post_author`=%d AND `post_type`='premium_profile' AND `post_status`='publish' LIMIT 1", (int) $user_id ) );

		if ( empty ( $result ) ) {
			return false;
		}

		$business = array_shift( $result );

		// premium profile working times
		$hours_enabled = carbon_get_post_meta( $business->ID, 'profile-hours-enable' );
		$weekdays      = lisfinity_days_of_the_week( true, true );
		$hours         = [];
		if ( 'yes' === $hours_enabled ) {
			$day_count = 1;
			foreach ( $weekdays as $slug => $label ) {
				$hours_type                  = carbon_get_post_meta( $business->ID, "profile-hours-{$slug}-type" );
				$hours[ $day_count ]['day']  = $weekdays[ $slug ];
				$hours[ $day_count ]['type'] = $hours_type;
				if ( 'working' === $hours_type ) {
					$options = carbon_get_post_meta( $business->ID, "profile-hours-{$slug}-hours" );
					if ( ! empty( $options ) ) {
						$option_count = 0;
						foreach ( $options as $option ) {
							if ( ! empty( $option['open'] ) ) {
								$hours[ $day_count ]['hours'][ $option_count ]['open'] = $option['open'];
							} else {
								$hours[ $day_count ]['hours'][ $option_count ]['open'] = __( 'Not set', 'lisfinity-core' );
							}
							if ( ! empty( $option['close'] ) ) {
								$hours[ $day_count ]['hours'][ $option_count ]['close'] = $option['close'];
							} else {
								$hours[ $day_count ]['hours'][ $option_count ]['close'] = __( 'Not set', 'lisfinity-core' );
							}
							$option_count += 1;
						}
					}
				}
				$day_count += 1;
			}
		}


		$meta                      = [];
		$meta['id']                = $business->ID;
		$meta['title']             = $business->post_title;
		$meta['description']       = $business->post_content;
		$meta['location']          = carbon_get_post_meta( $business->ID, 'profile-location' );
		$meta['status']            = carbon_get_post_meta( $business->ID, 'premium-status' );
		$meta['expires']           = carbon_get_post_meta( $business->ID, 'profile-expiration' );
		$meta['phones']            = carbon_get_post_meta( $business->ID, 'profile-phones' );
		$meta['website']           = carbon_get_post_meta( $business->ID, 'profile-website' );
		$meta['thumbnail']         = has_post_thumbnail( $business->ID ) ? get_the_post_thumbnail_url( $business->ID ) : false;
		$meta['thumbnail_id']      = has_post_thumbnail( $business->ID ) ? [ get_post_thumbnail_id( $business->ID ) ] : false;
		$meta['hours_enabled']     = 'yes' === $hours_enabled;
		$meta['hours']             = $hours;
		$meta['current_day']       = lisfinity_get_current_weekday();
		$business->data            = $meta;
		$model                     = new ProductsRoute();
		$submit_model              = new FormBusinessSubmitModel();
		$business->edit_data       = $model->get_product_edit_data( $meta, $submit_model );
		$business->forced_premium  = lisfinity_is_enabled( lisfinity_get_option( 'all-businesses-premium' ) );
		$business->premium         = $this->get_business_premium_data( $business->ID );
		$business->premium_product = $this->get_premium_product();

		// vendor settings information.
		$business->vendor = $this->vendor_settings( $business->ID, $user_id );

		return $business;
	}

	public function vendor_settings( $id, $user_id ) {
		// payouts.
		$payouts_model              = new PayoutsModel();
		$vendor['stripe_connected'] = ! empty( get_post_meta( $id, '_stripe-connect-id', true ) );
		$vendor['payouts']          = $this->format_payouts_data( $payouts_model->get_payouts( $user_id ) );
		$vendor['payment_due']      = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $payouts_model->calculate_total_due_amount( $user_id, 'not_paid' ) );
		$vendor['payment_received'] = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $payouts_model->calculate_total_due_amount( $user_id, 'paid' ) );
		$information                = lisfinity_get_option( 'vendors-payouts-info' );
		if ( ! empty( $information ) ) {
			$information_formatted         = str_replace( [ '{', '}', PHP_EOL ], [
				'<strong>',
				'</strong>',
				'<br />'
			], $information );
			$vendor['payment_information'] = $information_formatted;
		} else {
			$vendor['payment_information'] = false;
		}

		// gateway settings.
		$gateway = get_post_meta( $id, '_payout-gateway', true );
		if ( ! empty( $gateway ) ) {
			$vendor['gateway'] = $gateway;
			if ( 'paypal' === $gateway ) {
				$vendor['paypal'] = get_post_meta( $id, '_payout-paypal', true );
			}

			if ( 'bank' === $gateway ) {
				$vendor['bank'] = [
					'account_name'   => get_post_meta( (int) $id, 'bank__account-name', true ),
					'account_number' => get_post_meta( (int) $id, 'bank__account-number', true ),
					'bank_name'      => get_post_meta( (int) $id, 'bank__bank-name', true ),
					'routing_number' => get_post_meta( (int) $id, 'bank__routing-number', true ),
					'iban'           => get_post_meta( (int) $id, 'bank__iban', true ),
					'swift'          => get_post_meta( (int) $id, 'bank__swift', true ),
				];
			}
		} else {
			$vendor['gateway'] = false;
		}

		return $vendor;
	}

	public function format_payouts_data( $payouts ) {
		if ( empty( $payouts ) ) {
			return $payouts;
		}

		$payouts_model = new PayoutsModel();
		$formatted     = [];

		foreach ( $payouts as $payout_id ) {
			$formatted[ $payout_id ]['ID']            = $payout_id;
			$formatted[ $payout_id ]['title']         = get_the_title( $payout_id );
			$formatted[ $payout_id ]['created']       = get_the_date( 'M d, Y', $payout_id );
			$formatted[ $payout_id ]['product']       = carbon_get_post_meta( $payout_id, 'payout-product' );
			$formatted[ $payout_id ]['product_title'] = get_the_title( $formatted[ $payout_id ]['product'] );
			$formatted[ $payout_id ]['order']         = carbon_get_post_meta( $payout_id, 'payout-order' );
			$formatted[ $payout_id ]['status']        = carbon_get_post_meta( $payout_id, 'payout-status' );
			$formatted[ $payout_id ]['amount']        = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), get_post_meta( $payout_id, '_amount-due', true ) );
		}

		return $formatted;
	}

	/**
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function purchase_package( WP_REST_Request $request_data ) {
		global $woocommerce;
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['wc_product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The WooCommerce product has not been set.', 'lisfinity-core' );
		}

		$sold_once = carbon_get_post_meta( $data['wc_product'], 'package-sold-once' );

		$user_id     = get_current_user_id();
		$to_checkout = true;
		if ( ! empty( $sold_once ) ) {
			$package_model = new PackageModel();
			$packages      = $package_model->where( [
				[ 'user_id', $user_id ],
				[ 'product_id', $data['wc_product'] ]
			] )->get( '1', '', 'id', 'col' );

			if ( ! empty( $packages ) ) {
				$result['error']   = true;
				$result['message'] = __( 'This package type can be bought only once', 'lisfinity-core' );
			}

			if ( ! empty( $user_id ) ) {
				$to_checkout = false;
			}
		}

		if ( empty( $data['quantity'] ) ) {
			$data['quantity'] = 1;
		}
		if ( empty( $result['error'] ) ) {
			if ( $to_checkout ) {
				$cart_args = [ 'package_id' => $data['wc_product'] ];
				$discounts = carbon_get_post_meta( $data['wc_product'], 'subscription-discounts' );
				if ( empty( $discouns ) ) {
					$discounts = carbon_get_post_meta( $data['wc_product'], 'package-discounts' );
				}
				if ( ! empty( $data['discount'] ) ) {
					if ( ! empty( $discounts ) ) {
						foreach ( $discounts as $index => $discount ) {
							if ( $data['quantity'] >= $discount['duration'] ) {
								$cart_args['discount'] = $discount['discount'];
							}
						}
					}
				}

				$is_select = 'select' === carbon_get_post_meta( $data['wc_product'], 'package-discounts-type' );
				if ( $is_select && ! empty( $discounts ) ) {
					foreach ( $discounts as $index => $discount ) {
						if ( $data['quantity'] >= $discount['duration'] ) {
							$cart_args['custom-price'] = $discount['discount'];
						}
					}
				}

				WC()->cart->empty_cart();
				WC()->cart->add_to_cart( $data['wc_product'], (int) $data['quantity'] ?? 1, '', '', $cart_args );

				$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );
			} else {
				$order = wc_create_order( [
					'customer_id' => $user_id,
				] );
				$order->add_product( wc_get_product( $data['wc_product'] ), (int) $data['quantity'] ?? 1, [
					'package_id' => $data['wc_product'],
				] );

				$address = [
					'first_name'        => get_user_meta( $user_id, 'billing_first_name', true ) ?? '',
					'last_name'         => get_user_meta( $user_id, 'billing_last_name', true ) ?? '',
					'billing_company'   => get_user_meta( $user_id, 'billing_company', true ) ?? '',
					'billing_address_1' => get_user_meta( $user_id, 'billing_address_1', true ) ?? '',
					'billing_address_2' => get_user_meta( $user_id, 'billing_address_2', true ) ?? '',
					'billing_city'      => get_user_meta( $user_id, 'billing_city', true ) ?? '',
					'billing_state'     => get_user_meta( $user_id, 'billing_state', true ) ?? '',
					'billing_postcode'  => get_user_meta( $user_id, 'billing_postcode', true ) ?? '',
					'billing_phone'     => get_user_meta( $user_id, 'billing_phone', true ) ?? '',
					'billing_email'     => get_user_meta( $user_id, 'billing_email', true ) ?? '',
					'billing_country'   => get_user_meta( $user_id, 'billing_country', true ) ?? '',
				];

				$order->set_address( $address );
				$order->update_status( 'completed', '', true );

				do_action( 'woocommerce_order_status_completed', $order->get_id() );
				$result['message'] = esc_html__( 'Free trial package successfully added!', 'lisfinity-core' );
			}
			$result['success'] = true;
		}

		return $result;
	}

	/**
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 * @throws \Exception
	 *
	 */
	public function purchase_promotion( WP_REST_Request $request_data ) {
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['wc_product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The WooCommerce product has not been set.', 'lisfinity-core' );
		}

		if ( empty( $data['days'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The number of days for promotion duration has not been set.', 'lisfinity-core' );
		}

		if ( 1 > $data['days'] ) {
			$result['error']   = true;
			$result['message'] = __( 'The number of days for promotion duration has to be at least set to a single day.', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $data['wc_product'], $data['days'], '', '', [
			'product'               => $data['product'],
			'no-product-expiration' => true,
			//^^^ prevents extending expiration date of the product that is needed from the product submission form.
			'no-price'              => empty( $data['price'] ),
		] );

		$result['success']   = true;
		$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );

		return $result;
	}

	/**
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function purchase_premium( WP_REST_Request $request_data ) {
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['wc_product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The WooCommerce product has not been set.', 'lisfinity-core' );
		}

		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'pay-for-premium' ) ) ) {

			$user_data = get_userdata( get_current_user_id() );
			$to        = $user_data->user_email;
			$subject   = esc_html__( 'Premium Business Request', 'lisfinity-core' );
			$type      = carbon_get_post_meta( $data['wc_product'], 'promotion-cost-type' );
			$type      = $type === 'month' ? __( 'Months', 'lisfinity-core' ) : __( 'Days', 'lisfinity-core' );
			if ( $type === 'month' ) {
				$type_string = _n_noop( '<strong>%s</strong> month', '<strong>%s</strong> months', 'lisfinity-core' );
			} else {
				$type_string = _n_noop( '<strong>%s</strong> day', '<strong>%s</strong> days', 'lisfinity-core' );
			}

			$body    = sprintf( __( 'Customer %s required to expand their profile with premium business options for %s. You can do that by manually created an order for them.', 'lisfinity-core' ), '<a href="' . esc_url( 'http://lisfinity.test/wp-admin/user-edit.php?user_id=' . get_current_user_id() ) . '">' . esc_html( $user_data->display_name ) . '</a>', $data['quantity'] . ' ' . translate_nooped_plural( $type_string, $data['quantity'], 'lisfinity-core' ) );
			$headers = [ 'Content-Type: text/html; charset=UTF-8' ];

			$email_sent = wp_mail( $to, $subject, $body, $headers );

			$result['success']   = true;
			$result['permalink'] = false;
			$result['message']   = __( 'Site administration has been contacted about your request and they will approve it or get back to you as soon as it is reviewed.' );

			return $result;
		}

		$cart_args = [
			'type'    => 'premium_profile',
			'product' => $data['product'],
			'price'   => $data['price'],
		];
		if ( ! empty( $data['discount'] ) && 0 !== $data['discount'] ) {
			$cart_args['discount'] = $data['discount'];
		}

		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $data['wc_product'], $data['quantity'], '', '', $cart_args );

		$result['success']   = true;
		$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );

		return $result;
	}

	/**
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function renew_ad( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		$packages_enabled = lisfinity_packages_enabled( get_current_user_id() );
		if ( empty( $data['ad'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The id of the ad has not been set.', 'lisfinity-core' );
		}

		if ( $packages_enabled && empty( $data['package'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The ad package has not been set.', 'lisfinity-core' );
		}

		$submitted_date  = carbon_get_post_meta( $data['ad'], 'product-listed' );
		$expiration_date = carbon_get_post_meta( $data['ad'], 'product-expiration' );
		if ( $packages_enabled ) {
			$package_model = new PackageModel();
			$package       = $package_model->where( 'id', $data['package'] )->get();
			$package       = array_shift( $package );
			$package_model->update_wp( [ 'products_count' => $package->products_count += 1 ], [ 'id' => $data['package'] ], [ '%d' ], [ '%s' ] );
			$expiring = strtotime( "+{$package->products_duration} days", $expiration_date );
			update_post_meta( $data['ad'], '_payment-package', $data['package'] );

			$result['success'] = true;
			$result['message'] = sprintf( __( 'Ad has been successfully renewed for %d days', 'lisfinity-core' ), $package->products_duration );
			$result['package'] = (int) $data['package'];
		}

		if ( ! $packages_enabled ) {
			$duration          = lisfinity_get_option( 'product-duration' );
			$expiring          = strtotime( "+{$duration} days", $expiration_date );
			$result['success'] = true;
			$result['message'] = sprintf( __( 'Ad has been successfully renewed for %d days', 'lisfinity-core' ), $duration );
		}

		$result['remaining']     = $expiring;
		$result['expires_human'] = human_time_diff( $expiring, current_time( 'timestamp' ) );
		$result['percentage']    = lisfinity_calculate_expiring_percentage( $submitted_date, $expiration_date );

		// set the new expiration date.
		carbon_set_post_meta( $data['ad'], 'product-expiration', $expiring );
		$time = current_time( 'mysql' );
		// set the new submission date so it can be displayed on the first position
		wp_update_post( [
			'ID'            => $data['ad'],
			'post_date'     => $time,
			'post_date_gmt' => get_gmt_from_date( $time ),
		] );

		return $result;
	}

	public function purchase_commission( WP_REST_Request $request_data ) {
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['wc_product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The WooCommerce product has not been set.', 'lisfinity-core' );
		}

		if ( empty( $data['price'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The price has not been set.', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $data['wc_product'], 1, '', '', [
			'product'    => $data['product'],
			'type'       => 'commission',
			'commission' => $data['price'],
		] );

		$result['success']   = true;
		$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );

		return $result;
	}


	public function get_product( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		// todo make sure that we find business owner if the current user is an agent.
		$agent                    = lisfinity_get_agent( $data['id'] );
		$data['id']               = $agent->owner_id ?? $data['id'];
		$id                       = $data['product_id'];
		$result['business_owner'] = (int) $data['id'];
		$result['user_id']        = (int) $data['id'];
		$result['product_id']     = $id;
		$result['product_owner']  = (int) get_post_meta( $id, '_product-owner', true );

		// product status & reject reason
		$result['reject_reason'] = false;

		// if user is product owner.
		$wc_product = wc_get_product( $id );
		$listed     = carbon_get_post_meta( $id, 'product-listed' );
		// use default post submission date if the post meta list date is not available.
		$submitted = ! empty( $listed ) ? $listed : strtotime( get_the_date( '', $id ) );
		$expires   = carbon_get_post_meta( $id, 'product-expiration' );
		$duration  = $expires - $submitted;
		$remaining = $expires - current_time( 'timestamp' );

		$result['status'] = get_post_status( $id );
		// if post has been rejected for a reason.
		if ( 'trash' === $result['status'] ) {
			$result['reject_reason'] = carbon_get_post_meta( $id, 'product-reject-reason' );
		}
		$result['is_active']       = $remaining > 0;
		$result['id']              = $id;
		$result['thumbnail']       = get_the_post_thumbnail_url( $id, 'full' );
		$result['title']           = get_the_title( $id );
		$result['submitted']       = $submitted;
		$result['submitted_human'] = human_time_diff( $result['submitted'], current_time( 'timestamp' ) );
		$result['expires']         = $expires;
		$result['expires_human']   = human_time_diff( $result['expires'], current_time( 'timestamp' ) );
		$result['expires_date']    = date( 'M d', $expires );
		$result['duration']        = $duration;
		$result['remaining']       = $remaining;
		$result['percentage']      = $duration !== 0 ? floor( 100 - ( $remaining * 100 ) / $duration ) : 0;
		$result['days_remaining']  = round( $remaining / DAY_IN_SECONDS );

		// types && price types.
		$result['product_type']     = carbon_get_post_meta( $id, 'product-type' );
		$result['product_category'] = carbon_get_post_meta( $id, 'product-category' );
		$result['price_type']       = in_array( carbon_get_post_meta( $id, 'product-price-type' ), [
			'fixed',
			'negotiable'
		] ) && ! empty( $wc_product->get_sale_price() ) ? 'on-sale' : carbon_get_post_meta( $id, 'product-price-type' );
		$result['on_sale']          = ! empty( $wc_product->get_sale_price() );
		$result['price']            = (float) $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
		$result['regular_price']    = (float) $wc_product->get_regular_price() * lisfinity_get_chosen_currency_rate() ?? 0 * lisfinity_get_chosen_currency_rate();
		$result['sale-price']       = (float) $wc_product->get_sale_price() * lisfinity_get_chosen_currency_rate() ?? 0 * lisfinity_get_chosen_currency_rate();
		$result['price_html']       = lisfinity_get_price_html( $result['price'] );
		$result['likes']            = ! empty( $likes ) ? count( $likes ) : 0;

		if ( 'auction' === $result['price_type'] ) {
			$result['auction_status']   = carbon_get_post_meta( $id, 'product-auction-status' );
			$result['auction_ends']     = carbon_get_post_meta( $id, 'product-auction-ends' );
			$start_price                = carbon_get_post_meta( $id, 'product-auction-start-price' );
			$start_price                = ! empty( $start_price ) ? $start_price * lisfinity_get_chosen_currency_rate() : 1 * lisfinity_get_chosen_currency_rate();
			$result['start_price']      = $start_price;
			$result['start_price_html'] = lisfinity_get_price_html( $start_price, $wc_product );
		}

		// product agent.
		// todo update when functionality for agents is done.
		$agent                           = get_userdata( $data['id'] );
		$result['agent']['avatar']       = lisfinity_get_avatar_url( $data['id'] );
		$result['agent']['display_name'] = $agent->display_name;
		$result['agent']['first_name']   = ! empty( $agent->first_name ) ? $agent->first_name : false;
		$result['agent']['last_name']    = ! empty( $agent->last_name ) ? $agent->last_name : false;

		// if user is not product owner.
		if ( $result['product_owner'] !== $result['business_owner'] ) {
			$current_user  = get_current_user_id();
			$user_business = lisfinity_get_premium_profile_id( $current_user );
			$chat_model    = new ChatModel();
			$chat          = $chat_model->where( [
				[ 'product_id', $id ],
				[ 'sender_id', $user_business ]
			] )->value( 'id' );

			if ( ! empty( $chat ) ) {
				$result['to_chat'] = true;

				return $result;
			}

			return false;
		}

		// get product position.
		$result['position'] = $this->get_product_position( $id, $result['product_category'] );

		// get product package.
		$payment_package = get_post_meta( $id, '_payment-package', true );
		if ( ! empty( $payment_package ) ) {
			$package = $this->get_product_package( $payment_package, ! empty( get_post_meta( $id, '_package-is-subscription', true ) ) );
			if ( empty( $package ) ) {
				$result['package'] = false;

				return $result;
			}
			$promo_model                   = new PromotionsModel();
			$promotions                    = $promo_model->where( 'package_id', $package->id )->get();
			$package->title                = get_the_title( $package->product_id );
			$package->created_date         = date( 'm.d.Y', strtotime( $package->created_at ) );
			$package->price                = (float) $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
			$package->price_html           = lisfinity_get_price_html( $package->price * lisfinity_get_chosen_currency_rate() );
			$package->promotion['addon']   = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'addon' ] );
			$package->promotion['product'] = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'product' ] );
			$package->remaining            = (int) $package->products_limit - $package->products_count;
			$package->percentage           = (int) floor( 100 - ( $package->remaining * 100 ) / $package->products_limit );
			$result['package']             = $package;
		} else {
			$result['package'] = false;
		}

		return $result;
	}

	protected function get_active_packages( $user_id ) {
		$model    = new PackageModel();
		$packages = $model->where( [
			[ 'user_id', $user_id ],
			[ 'status', 'active' ],
			[ 'products_limit', '>', 'products_count' ]
		] )->get( '', 'ORDER BY created_at DESC' );

		$subscription_model = new SubscriptionModel();
		$subscriptions      = $subscription_model->where( [
			[ 'user_id', $user_id ],
			[ 'status', 'active' ],
			[ 'expires_at', '>', 'NOW()' ],
		] )->get( '1', 'ORDER BY created_at DESC' );

		if ( ! empty( $subscriptions ) ) {
			if ( empty( $packages ) ) {
				$packages = $subscriptions;
			} else {
				$packages = array_merge( $packages, $subscriptions );
			}
		}

		return $this->format_active_packages_meta( $packages );
	}

	protected function format_active_packages_meta( $packages ) {
		if ( empty( $packages ) ) {
			return $packages;
		}

		$promo_model  = new PromotionsModel();
		$packages_ids = implode( ',', array_column( $packages, 'id' ) );
		$promotions   = $promo_model->where( [ [ 'package_id', 'IN', "({$packages_ids})" ] ] )->get();

		foreach ( $packages as $index => $package ) {
			$wc_product = wc_get_product( $package->product_id );
			if ( ! empty ( $wc_product ) ) {
				$package->title                = get_the_title( $wc_product->get_id() );
				$package->currency             = get_woocommerce_currency_symbol();
				$package->decimals             = wc_get_price_decimals();
				$package->decimal_separator    = wc_get_price_decimal_separator();
				$package->thousand_separator   = wc_get_price_thousand_separator();
				$package->title                = $wc_product->get_title();
				$package->price                = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
				$package->price_html           = lisfinity_get_price_html( $package->price );
				$package->promotion['addon']   = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'addon' ] );
				$package->promotion['product'] = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'product' ] );
				$package->products_limit       = ! empty( $package->products_limit ) ? $package->products_limit : 999;
				$package->remaining            = $package->products_limit - $package->products_count;
				$package->percentage           = floor( 100 - ( $package->remaining * 100 ) / $package->products_limit );
				$package->additional_listing   = carbon_get_post_meta( $wc_product->get_id(), 'subscription-product-price' );
			}
		}

		return $packages;
	}

	protected function get_packages() {
		$model = new PackageModel();

		$packages = $model->get_packages_query( [ 'payment_package', 'payment_subscription' ] );

		return $packages->have_posts() ? $this->format_packages_meta( $packages->posts ) : false;
	}

	protected function format_packages_meta( $packages ) {
		foreach ( $packages as $package ) {
			$wc_product = wc_get_product( $package->ID );
			$prefix     = '';
			if ( $wc_product->is_type( \WC_Product_Payment_Subscription::$type ) ) {
				$prefix = 's-';
			}
			$package->currency           = get_woocommerce_currency_symbol();
			$package->decimals           = wc_get_price_decimals();
			$package->decimal_separator  = wc_get_price_decimal_separator();
			$package->thousand_separator = wc_get_price_thousand_separator();
			$package->price              = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
			$package->price_format       = get_woocommerce_price_format();
			$package->on_sale            = ! empty( $wc_product->get_sale_price() );
			if ( $package->on_sale ) {
				$price               = wc_format_sale_price( wc_get_price_to_display( $wc_product, array( 'price' => $wc_product->get_regular_price() * lisfinity_get_chosen_currency_rate() ) ), wc_get_price_to_display( $wc_product ) * lisfinity_get_chosen_currency_rate() ) . $wc_product->get_price_suffix();
				$package->price_html = $price;
			} else {
				$package->price_html = lisfinity_get_price_html( $package->price );
			}
			$package->features = $this->format_features( carbon_get_post_meta( $package->ID, "{$prefix}package-features" ), $package->ID, $wc_product->get_type() );
			$package->limit    = carbon_get_post_meta( $package->ID, 'package-products-limit' );
			$package->duration = carbon_get_post_meta( $package->ID, 'package-products-duration' );
			if ( Subscription_Package::$type === $wc_product->get_type() ) {
				$package->duration = carbon_get_post_meta( $package->ID, 'subscription-products-duration' );
				$package->limit    = carbon_get_post_meta( $package->ID, 'subscription-products-limit' );
			}

			$package->style             = carbon_get_post_meta( $package->ID, "{$prefix}package-style" );
			$package->discounts_enabled = carbon_get_post_meta( $package->ID, 'subscription-discounts-enable' );
			$package->discounts         = carbon_get_post_meta( $package->ID, 'subscription-discounts' );
			$package->discounts_type    = carbon_get_post_meta( $package->ID, 'package-discounts-type' ) ?? 'input';
			if ( empty( $package->discounts_enabled ) ) {
				$package->discounts_enabled = carbon_get_post_meta( $package->ID, 'package-discounts-enable' );
			}
			if ( empty( $package->discounts ) ) {
				$package->discounts = carbon_get_post_meta( $package->ID, 'package-discounts' );
			}
			if ( $package->discounts_enabled && ! empty( $package->discounts ) ) {
				foreach ( $package->discounts as $index => $discount ) {
					$package->discounts[ $index ]['discount'] = (float) $discount['discount'] * lisfinity_get_chosen_currency_rate();
				}
			}
			$package->additional_listing = carbon_get_post_meta( $package->ID, 'subscription-product-price' );
			$package->text               = __( 'Buy Package', 'lisfinity-core' );
			$package->type               = $wc_product->is_type( \WC_Product_Payment_Subscription::$type ) ? \WC_Product_Payment_Subscription::$type : \WC_Product_Payment_Package::$type;
			$different_text              = carbon_get_post_meta( $package->ID, 'package-different-buy-button' );
			$button_text                 = carbon_get_post_meta( $package->ID, 'package-button-text' );
			if ( $different_text && ! empty( $button_text ) ) {
				$package->text = $button_text;
			}
			$package->is_exclusive      = 'yes' === carbon_get_post_meta( $package->ID, "{$prefix}package-categories-exclusive" );
			$package->categories        = carbon_get_post_meta( $package->ID, "{$prefix}package-categories" );
			$submit_percentage          = carbon_get_post_meta( $package->ID, "{$prefix}package-price-submit-percentage" );
			$package->submit_percentage = ! empty( $submit_percentage ) ? $submit_percentage : false;
		}

		return $packages;
	}

	protected function format_features( $features, $id, $type ) {
		if ( empty( $features ) ) {
			return $features;
		}

		$result = [];
		$count  = 0;
		foreach ( $features as $feature ) {
			$result[ $count ]['package-feature']  = lisfinity_convert_to_option( $feature['package-feature'], $id, $type );
			$result[ $count ]['package-feature']  = str_replace( '<strong>', '<strong class="text-grey-1000">', $result[ $count ]['package-feature'] );
			$result[ $count ]['package-footnote'] = $feature['package-footnote'];
			$count                                += 1;
		}

		return $result;
	}

	protected function get_product_package( $id, $is_subscription = false ) {
		if ( ! $is_subscription ) {
			$model   = new PackageModel();
			$package = $model->where( 'id', $id )->get( '', '', 'id, product_id, products_limit, products_count, products_duration, created_at' );
		} else {
			$subscription_model = new SubscriptionModel();
			$package            = $subscription_model->where( 'id', $id )->get( '', '', 'id, product_id, products_limit, products_count, products_duration, starts_at, promotions_limit, promotions_count' );
		}

		return array_shift( $package );
	}

	public function get_product_position( $id, $category = 'real-estate' ) {
		$model = new ProductModel();
		$args  = [
			//'posts_per_page' => 1, //used for the testing of the bump up functionality
			'posts_per_page' => lisfinity_get_option( 'bump-up-position' ),
			'meta_key'       => '_product-category',
			'meta_value'     => $category,
			'fields'         => 'ids',
		];

		$results = $model->get_products_query( $args );

		$key = array_search( $id, $results->posts );

		$use_ordinal = lisfinity_get_option( 'use-ordinal' );
		if ( $use_ordinal ) {
			return $key || 0 === $key ? lisfinity_ordinal_suffixes( $key + 1 ) : false;
		}

		return $key || 0 === $key ? $key + 1 : false;
	}

	protected function get_premium_product() {
		$model = new PromotionsModel();

		$args            = [
			'posts_per_page' => 1,
		];
		$premium_product = $model->get_promotion_products( 'promotion', 'premium_profile', $args );

		if ( empty( $premium_product ) ) {
			return false;
		}

		$premium_product = array_shift( $premium_product );
		if ( carbon_get_post_meta( $premium_product->ID, 'promotion-discounts-enable' ) ) {
			$discounts           = carbon_get_post_meta( $premium_product->ID, 'promotion-discounts' );
			$discounts_formatted = [];
			if ( ! empty( $discounts ) ) {
				asort( $discounts );
				$count = 0;
				foreach ( $discounts as $discount ) {
					$discounts_formatted[ $count ]['duration'] = $discount['duration'];
					$discounts_formatted[ $count ]['discount'] = $discount['discount'];
					$count                                     += 1;
				}
			}
			$premium_product->discounts = $discounts_formatted;
		}

		return $premium_product;
	}

	protected function get_business_premium_data( $id ) {
		$model = new PromotionsModel();

		if ( lisfinity_is_enabled( lisfinity_get_option( 'all-businesses-premium' ) ) ) {
			return true;
		}

		$premium = $model->where( [
			[ 'product_id', $id ],
			[ 'status', 'active' ],
			[ 'expires_at', '>', 'NOW()' ],
		] )->get( '1', '', 'id, product_id, wc_product_id, status, expires_at, created_at' );

		return ! empty( $premium ) ? $this->format_business_premium_meta( array_shift( $premium ) ) : false;
	}

	protected function format_business_premium_meta( $premium ) {
		$premium->created_human = human_time_diff( strtotime( $premium->created_at ), current_time( 'timestamp' ) );
		$premium->expires_human = human_time_diff( current_time( 'timestamp' ), strtotime( $premium->expires_at ) );

		return $premium;
	}

	protected function format_banned_users_meta( $profiles ) {
		if ( empty( $profiles ) ) {
			return $profiles;
		}

		$data = [];

		foreach ( $profiles as $profile ) {
			$meta            = new \stdClass();
			$meta->ID        = $profile['id'];
			$meta->title     = get_the_title( $meta->ID );
			$meta->thumbnail = has_post_thumbnail( $meta->ID ) ? get_the_post_thumbnail_url( $meta->ID ) : false;
			$data[]          = $meta;
		}

		return $data;
	}

	protected function get_promotion_products() {
		$model = new PromotionsModel();

		$promotions = $model->get_promotion_products( 'promotion' );

		return $promotions;
	}

	public function get_all_ads_request( WP_REST_Request $request_data ) {
		$data  = $request_data->get_params();
		$model = new ProductModel();

		$status = lisfinity_get_option( 'product-status' );

		$statuses = [ 'publish', 'sold', 'pending' ];

		if ( 'live' !== $status ) {
			$statuses[] = 'trash';
		}

		$args = [
			'post_status'   => $statuses,
			'owner'         => $data['user_id'],
			'fields'        => 'ids',
			'cache_results' => false,
		];

		$query = $model->get_products_query( $args, isset( $data['expired'] ) );

		if ( ! $query->found_posts ) {
			return false;
		}

		return $this->format_ads_meta( $query->posts, $args['owner'] );
	}

	protected function get_all_ads( $user_id, $format = true, $data = '' ) {
		$model  = new ProductModel();
		$status = lisfinity_get_option( 'product-status' );

		$statuses = [ 'publish', 'sold', 'pending' ];

		if ( 'live' !== $status ) {
			$statuses[] = 'trash';
		}

		$args = [
			'post_status'   => $statuses,
			'owner'         => $user_id,
			'fields'        => 'ids',
			'cache_results' => false,
		];

		$query = $model->get_products_query( $args, isset( $data['expired'] ) );

		if ( ! $query->found_posts ) {
			return false;
		}

		return $format ? $this->format_ads_meta( $query->posts, $user_id ) : $query->posts;
	}

	protected function format_ads_meta( $ads, $user_id = '', $hide_info = false ) {
		$result = [];
		$count  = 0;
		if ( empty( $ads ) ) {
			return $ads;
		}
		foreach ( $ads as $ad ) {
			$wc_product = wc_get_product( $ad );
			if ( ! empty( $wc_product ) ) {
				$listed = carbon_get_post_meta( $ad, 'product-listed' );
				// use default post submission date if the post meta list date is not available.
				$submitted = ! empty( $listed ) ? $listed : strtotime( get_the_date( '', $ad ) );
				$expires   = carbon_get_post_meta( $ad, 'product-expiration' );
				$duration  = $expires - $submitted;
				$remaining = $expires - current_time( 'timestamp' );

				$result[ $count ]['status']    = get_post_status( $ad );
				$result[ $count ]['is_active'] = $remaining > 0;
				$result[ $count ]['id']        = $ad;
				$result[ $count ]['thumbnail'] = get_the_post_thumbnail_url( $ad, 'full' );
				$result[ $count ]['title']     = get_the_title( $ad );
				if ( ! $hide_info ) {
					$result[ $count ]['submitted']       = $submitted;
					$result[ $count ]['submitted_human'] = human_time_diff( $result[ $count ]['submitted'], current_time( 'timestamp' ) );
					$result[ $count ]['expires']         = $expires;
					$result[ $count ]['expires_date']    = date( 'M d', $expires );
					$result[ $count ]['expires_human']   = human_time_diff( $result[ $count ]['expires'], current_time( 'timestamp' ) );
					$result[ $count ]['duration']        = $duration;
					$result[ $count ]['remaining']       = $remaining;
					$result[ $count ]['percentage']      = $duration !== 0 ? floor( 100 - ( $remaining * 100 ) / $duration ) : 0;
				}
				$result[ $count ]['permalink'] = get_permalink( $ad );

				// types && price types.
				$result[ $count ]['product_type']  = carbon_get_post_meta( $ad, 'product-type' );
				$result[ $count ]['price_type']    = in_array( carbon_get_post_meta( $ad, 'product-price-type' ), [
					'fixed',
					'negotiable'
				] ) && ! empty( $wc_product->get_sale_price() ) ? 'on-sale' : carbon_get_post_meta( $ad, 'product-price-type' );
				$result[ $count ]['on_sale']       = ! empty( $wc_product->get_sale_price() );
				$result[ $count ]['price']         = $wc_product->get_price() ?? 0 * lisfinity_get_chosen_currency_rate();
				$result[ $count ]['regular_price'] = $wc_product->get_regular_price() ?? 0 * lisfinity_get_chosen_currency_rate();
				$result[ $count ]['sale-price']    = $wc_product->get_sale_price();
				$result[ $count ]['price_html']    = lisfinity_get_price_html( $result[ $count ]['price'] );
				$result[ $count ]['likes']         = ! empty( $likes ) ? count( $likes ) : 0;

				if ( 'auction' === $result[ $count ]['price_type'] ) {
					$result[ $count ]['auction_status']   = carbon_get_post_meta( $ad, 'product-auction-status' );
					$result[ $count ]['auction_ends']     = carbon_get_post_meta( $ad, 'product-auction-ends' );
					$start_price                          = carbon_get_post_meta( $ad, 'product-auction-start-price' );
					$start_price                          = ! empty( $start_price ) ? $start_price : 1;
					$result[ $count ]['start_price']      = $start_price;
					$result[ $count ]['start_price_html'] = lisfinity_get_price_html( $start_price, $wc_product );
				}

				// product agent.
				// todo update when functionality for agents is done.
				$agent_id                                  = carbon_get_post_meta( $ad, 'product-agent' ) ?? $user_id;
				$agent                                     = get_userdata( $agent_id );
				$result[ $count ]['agent']['avatar']       = lisfinity_get_avatar_url( $agent_id );
				$result[ $count ]['agent']['display_name'] = $agent->display_name;
				$result[ $count ]['agent']['first_name']   = ! empty( $agent->first_name ) ? $agent->first_name : false;
				$result[ $count ]['agent']['last_name']    = ! empty( $agent->last_name ) ? $agent->last_name : false;

				if ( ! $hide_info ) {
					// bids, messages and promotions.
					$result[ $count ]['bids']       = $this->filter_by_product_id( $this->bids, $ad );
					$result[ $count ]['messages']   = $this->filter_by_product_id( $this->messages, $ad );
					$result[ $count ]['promotions'] = $this->filter_by_product_id( $this->promotions, $ad );

					// package from which the ad has been submitted.
					$package                     = get_post_meta( $ad, '_payment-package', true );
					$result[ $count ]['package'] = $package ?? false;
				}

				$count += 1;
			}
		}

		return $result;
	}

	protected function filter_by_product_id( $array, $id ) {
		if ( ! is_array( $array ) || empty( $array ) ) {
			return [];
		}

		$filtered = [];
		foreach ( $array as $item ) {
			if ( $item->product_id == $id ) {
				$filtered[] = $item;
			}
		}

		return $filtered;
	}

	protected function get_theme_options() {
		$options                        = [];
		$user_id                        = $this->agent ? $this->agent->owner_id : get_current_user_id();
		$options['logo']                = lisfinity_get_option( 'identity-logo-admin' );
		$options['logo_size']           = lisfinity_get_option( 'identity-logo-size' );
		$options['header_taxonomy']     = lisfinity_get_option( 'header-taxonomy' );
		$options['site_title']          = get_option( 'blogname' );
		$options['enable_packages']     = lisfinity_packages_enabled( $user_id );
		$options['premium_profiles']    = lisfinity_is_enabled( lisfinity_get_option( 'site-premium-profiles' ) );
		$options['compare_enabled']     = '1' === lisfinity_get_option( 'header-compare' ) && '0' !== lisfinity_get_option( 'ads-compare' );
		$options['vendors_enabled']     = lisfinity_is_enabled( lisfinity_get_option( 'vendors-enabled' ) );
		$options['commissions_enabled'] = lisfinity_is_enabled( lisfinity_get_option( 'pay-for-details' ) );
		$options['logout_url']          = wp_logout_url(); // wc_logout_url() - maybe switch to this later.
		$options['is_business_account'] = lisfinity_is_business_account( $user_id );
		$options['account_type']        = lisfinity_get_account_type( $user_id );

		return $options;
	}

	protected function get_user( $user_id ) {
		$user      = [];
		$user_data = get_userdata( $user_id );

		$user['display_name'] = $user_data->display_name;
		$user['first_name']   = $user_data->first_name;
		$user['last_name']    = $user_data->last_name;
		$user['verified']     = carbon_get_user_meta( $user_id, 'verified' );
		$user['avatar']       = lisfinity_get_avatar_url( $user_id );

		return $user;
	}

	public function get_promotions( $user_id ) {
		$model      = new PromotionsModel();
		$promotions = $model->where( [
			[ 'user_id', $user_id ],
			[ 'expires_at', '>=', 'NOW()' ],
			[ 'status', 'active' ],
			[ 'position', 'NOT IN', "('bump-up', 'addon-image', 'addon-docs', 'addon-video', 'addon-qr')" ],
		] )->get( '', '', 'id, product_id, created_at, expires_at, wc_product_id, value, position' );

		if ( empty( $promotions ) ) {
			return false;
		}

		return $promotions;
	}

	protected function format_promotions_meta( $promotions ) {
		$result = [];
		if ( empty( $promotions ) ) {
			return [];
		}

		remove_filter( 'the_title', 'wptexturize' );
		$count        = 0;
		$page_account = lisfinity_get_page_id( 'page-account' );
		foreach ( $promotions as $promotion ) {
			$wc_product = wc_get_product( $promotion->wc_product_id );
			if ( ! empty( $wc_product ) ) {
				$result[ $count ]['id']            = $promotion->id;
				$result[ $count ]['wc_product_id'] = $promotion->wc_product_id;
				$result[ $count ]['product_id']    = $promotion->product_id;
				$result[ $count ]['thumbnail']     = get_the_post_thumbnail_url( $promotion->product_id, 'full' );
				$result[ $count ]['title']         = get_the_title( $promotion->product_id );
				$result[ $count ]['ad_link']       = "ad/{$promotion->product_id}/promotions";
				if ( 'profile-premium' === $promotion->position ) {
					$result[ $count ]['ad_link'] = 'premium-profile';
				}
				$result[ $count ]['days']               = $promotion->value;
				$result[ $count ]['price']              = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
				$result[ $count ]['promotion_position'] = $promotion->position;
				$result[ $count ]['position']           = get_the_title( urldecode( $promotion->wc_product_id ) );
				$result[ $count ]['created']            = $promotion->created_at;
				$result[ $count ]['created_date']       = date( 'm.d.Y', strtotime( $promotion->created_at ) );
				$result[ $count ]['created_human']      = human_time_diff( current_time( 'timestamp' ), strtotime( $promotion->created_at ) );
				$result[ $count ]['expires']            = $promotion->expires_at;
				$result[ $count ]['expires_human']      = human_time_diff( strtotime( $promotion->expires_at ), current_time( 'timestamp' ) );
				$result[ $count ]['max_duration']       = ( strtotime( $promotion->expires_at ) - current_time( 'timestamp' ) ) * DAY_IN_SECONDS;

				$expires                   = strtotime( $promotion->expires_at );
				$time_diff                 = $expires - current_time( 'timestamp' );
				$result[ $count ]['color'] = '';
				if ( $time_diff < DAY_IN_SECONDS ) {
					$result[ $count ]['color'] = 'red';
				}

				$count += 1;
			}
		}
		add_filter( 'the_title', 'wptexturize' );

		return $result;
	}

	protected function get_overall_stats( $user_id ) {
		$stats   = [];
		$all_ads = $this->ads;

		$stats['ads']      = ! empty( $all_ads ) ? count( $all_ads ) : 0;
		$stats['bids']     = ! empty( $all_ads ) ? count( $this->bids ) : 0;
		$stats['messages'] = ! empty( $all_ads ) ? count( $this->messages ) : 0;
		$stats['packages'] = ! empty( $all_ads ) ? $this->get_all_packages_count( $user_id ) : 0;

		return $stats;
	}

	protected function get_bids( $user_id ) {
		$bids_model  = new BidModel();
		$product_ids = $this->get_all_ads( $user_id, false );

		if ( empty( $product_ids ) ) {
			return false;
		}

		if ( is_array( $product_ids ) ) {
			$result = $bids_model->where( [
				[ 'owner_id', $user_id ],
				[ 'product_id', 'IN', '(' . implode( ',', $product_ids ) . ')' ],
				[ 'status', '<>', "'seen'" ],
			] )->get( '', '', 'id, product_id, status' );
		} else {
			$result = $bids_model->where( [
				[ 'owner_id', $user_id ],
				[ 'product_id', '=', $product_ids ],
				[ 'status', '<>', "'seen'" ],
			] )->get( '', '', 'id, product_id, status' );
		}

		return $result;
	}

	protected function get_unread_messages( $user_id ) {
		$product_ids    = $this->get_all_ads( $user_id, false );
		$chats_model    = new ChatModel();
		$messages_model = new MessageModel();

		if ( empty( $product_ids ) ) {
			return false;
		}

		$business = lisfinity_get_premium_profile_id( $user_id );
		if ( is_array( $product_ids ) ) {
			$result = $chats_model->join( $messages_model->db, 'chat_id', 'id', 'INNER' )
			                      ->where( [
				                      [
					                      "{$chats_model->db}.product_id",
					                      'IN',
					                      '(' . implode( ',', $product_ids ) . ')'
				                      ],
				                      [ "{$messages_model->db}.status", 0 ],
				                      [ "{$messages_model->db}.sender_id", '<>', $business ],
			                      ], '', false )
			                      ->get( '', '', "{$messages_model->db}.chat_id, {$messages_model->db}.id, {$messages_model->db}.product_id" );
		} else {
			$result = $chats_model->join( $messages_model->db, 'chat_id', 'id', 'INNER' )
			                      ->where( [
				                      [ "{$chats_model->db}.product_id", '=', $product_ids ],
				                      [ "{$messages_model->db}.status", 0 ],
				                      [ "{$messages_model->db}.sender_id", '<>', $business ],
			                      ], '', false )
			                      ->get( '', '', "{$messages_model->db}.chat_id, {$messages_model->db}.id, {$messages_model->db}.product_id" );
		}

		return $result;
	}

	protected function get_all_packages_count( $user_id ) {
		$packages_model = new PackageModel();

		$result = $packages_model->where( [
			[ 'user_id', $user_id ],
			[ 'products_count', '<>', 'products_limit' ]
		] )->get( '', '', 'COUNT(id)', 'col' );

		return array_shift( $result );
	}

	public function upgrade_profile( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['user_id'] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'The user ID has not been set.', 'lisfinity-core' );

			return $result;
		}
		$account_type = lisfinity_get_account_type( $data['user_id'] );

		if ( 'buyer' == $account_type ) {
			carbon_set_user_meta( $data['user_id'], 'account-type', 'personal' );
			$result['success'] = true;
			$result['message'] = esc_html__( 'Successfully switched the account type to personal.', 'lisfinity-core' );

			return $result;
		}
		if ( 'personal' == $account_type ) {
			carbon_set_user_meta( $data['user_id'], 'account-type', 'business' );
			$result['success'] = true;
			$result['message'] = esc_html__( 'Successfully switched the account type to business.', 'lisfinity-core' );

			return $result;
		}
		if ( 'business' == $account_type ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'The account cannot be upgraded further', 'lisfinity-core' );

			return $result;
		}

		return $result;
	}

	public function get_business_agents( $business_id ) {
		$model            = new AgentModel();
		$agents_formatted = [];

		$agents = $model->where( 'business_id', $business_id )->get();
		if ( ! empty( $agents ) ) {
			foreach ( $agents as $agent ) {
				$user = get_userdata( $agent->user_id );

				if ( ! is_wp_error( $user ) ) {
					$agents_formatted[ $agent->id ]['id']         = $agent->id;
					$agents_formatted[ $agent->id ]['user_id']    = $agent->user_id;
					$agents_formatted[ $agent->id ]['first_name'] = $user->first_name;
					$agents_formatted[ $agent->id ]['last_name']  = $user->last_name;
					$agents_formatted[ $agent->id ]['email']      = $user->user_email;
				}
			}
		}

		return $agents_formatted;
	}

}
