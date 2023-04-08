<?php


namespace Lisfinity\REST_API\Bids;

use Lisfinity\Helpers\WC_Helper;
use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Notifications\NotificationModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use WC_Cart;
use WC_Customer;

class BidsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'bids'       => [
			'rest_path'           => '/bids/',
			'path'                => '/bids/(?P<product>\d+)',
			'callback'            => 'get_bids',
			'permission_callback' => 'has_cap',
			'methods'             => 'GET',
		],
		'submit_bid' => [
			'path'                => '/bids/store',
			'callback'            => 'submit_bid',
			'permission_callback' => 'has_cap',
			'methods'             => 'POST',
		],
		'buy_bid'    => [
			'path'                => '/bids/buy',
			'callback'            => 'buy_now',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'update_bid' => [
			'path'                => '/bids/update',
			'callback'            => 'update_bid',
			'permission_callback' => 'has_cap',
			'methods'             => 'POST',
		],
	];

	/**
	 * Get all bids by for the specified product
	 * -----------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return mixed
	 */
	public function get_bids( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		// Error | Product not set
		if ( empty( $data['product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Product is not set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		$bid_model = new BidModel();
		$bids      = $bid_model->get_product_bids( $data['product'], $data['owner'] );

		return $bids;
	}

	/**
	 * Handles buy now of the product functionality
	 * --------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @throws \Exception
	 */
	public function buy_now( WP_REST_Request $request_data ) {
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$data    = $request_data->get_params();
		$user_id = get_current_user_id();
		$result  = [];

		if ( ! isset( $data['sell_on_site'] ) && ! is_user_logged_in() ) {
			$result['error']   = true;
			$result['message'] = __( 'You have to be logged in order to complete this action', 'lisfinity-core' );

			return $result;
		}

		// Error | Product not set
		if ( empty( $data['product'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Product is not set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		$user_data     = get_userdata( get_current_user_id() );
		$user_business = lisfinity_get_premium_profile_id( $user_data->ID );

		if ( lisfinity_is_enabled( lisfinity_get_option( 'pay-for-details' ) ) ) {
			$result = $this->send_commission( $data['product'], $user_data, $user_business );

			wp_send_json( $result );
		}

		if ( isset( $data['sell_on_site'] ) ) {
			$cart_args  = [
				'type'    => 'listing',
				'product' => $data['product'],
				'price'   => $data['price'],
			];
			$commission = (int) lisfinity_get_option( 'vendors-site-commission' );
			if ( 0 !== $commission ) {
				$cart_args['site-commission'] = $commission;
			}

			if ( ! empty( $data['discount'] ) && 0 !== $data['discount'] ) {
				$cart_args['discount'] = $data['discount'];
			}

			WC()->cart->empty_cart();
			$key = WC()->cart->add_to_cart( $data['product'], 1, '', '', $cart_args );

			if ( ! $key ) {
				$result['error']   = true;
				$result['message'] = __( 'Product could not be added to the cart.', 'lisfinity-core' );
			}

			if ( ! empty ( $result['error'] ) ) {
				wp_send_json( $result );
			}

			$result['success']   = true;
			$result['message']   = __( 'Redirecting to checkout...', 'lisfinity-core' );
			$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );
			// todo add the product status to be sold.
		} else {
			$user_email      = carbon_get_post_meta( $user_business, 'profile-email' );
			$business_phones = carbon_get_post_meta( $user_business, 'profile-phones' );

			if ( empty( $user_email ) ) {
				$result['error']   = true;
				$result['message'] = __( 'You need to provide your business email in the dashboard before you can complete this action', 'lisfinity-core' );

				wp_send_json( $result );
			}

			$arrange_visit = lisfinity_is_enabled( lisfinity_get_option( 'send-details' ) );

			// product owner.
			$business_id = carbon_get_post_meta( $data['product'], 'product-business' );
			$to          = carbon_get_post_meta( $business_id, 'profile-email' );
			$subject     = sprintf( esc_html__( 'New Buy Now Request | %s', 'lisfinity-core' ), get_option( 'blogname' ) );
			if ( $arrange_visit ) {
				$subject = sprintf( esc_html__( 'New Visit Request | %s', 'lisfinity-core' ), get_option( 'blogname' ) );
			}
			if ( empty( $business_phones ) ) {
				$body = sprintf( __( 'User %1$s has requested to buy your listing (%$1s) for the specified price. <br /> You can contact him on the details below: <br /><br /> Email: %2$s',
					'lisfinity-core' ), get_the_title( $user_business ), "<a href='mailto: {$user_email}'>$user_email</a>" );
				if ( $arrange_visit ) {
					$body = sprintf( __( 'User %1$s has requested to arrange a visit for the listing (%$1s). <br /> You can contact him on the details below: <br /><br /> Email: %2$s',
						'lisfinity-core' ), get_the_title( $user_business ), "<a href='mailto: {$user_email}'>$user_email</a>" );
				}
			} else {
				$phones = array_column( $business_phones, 'profile-phone' );
				$string = '';
				$count  = 1;
				if ( ! empty( $user_email ) ) {
					$string .= sprintf( __( 'Email: %s <br />', 'lisfinity-core' ), "<a href='mailto: {$user_email}'>$user_email</a>" );
				}
				foreach ( $phones as $phone ) {
					$phone_formatted = str_replace( ' ', '', $phone );
					$string          .= sprintf( __( 'Phone %1$s: %2$s <br />', 'lisfinity-core' ), $count, "<a href='tel:{$phone_formatted}'>$phone</a>" );
					$count           += 1;
				}
				$user_permalink = get_permalink( $user_business );
				$user_title     = get_the_title( $user_business );
				$permalink      = get_permalink( $data['product'] );
				$title          = get_the_title( $data['product'] );
				$body           = sprintf( __( 'User %1$s has requested to buy your ad (%2$s) for the specified price. <br /> You can contact him on the details below: <br /><br /> %3$s',
					'lisfinity-core' ), "<a href='{$user_permalink}' target='_blank'>{$user_title}</a>", "<a href='{$permalink}' target='_blank'>{$title}</a>", $string );
				if ( $arrange_visit ) {
					$body = sprintf( __( 'User %1$s has requested to arrange a visit for the listing (%2$s). <br /> You can contact him on the details below: <br /><br /> %3$s',
						'lisfinity-core' ), "<a href='{$user_permalink}' target='_blank'>{$user_title}</a>", "<a href='{$permalink}' target='_blank'>{$title}</a>", $string );
				}
			}

			$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
			$mail    = wp_mail( $to, $subject, $body, $headers );

			if ( ! $mail ) {
				$result['error']   = true;
				$result['message'] = __( 'The mail could not have been sent', 'lisfinity-core' );

				wp_send_json( $result );
			}

			do_action( 'lisfinity__buy_now', $data['product'], $user_id );

			$result['success'] = true;
			$result['message'] = __( 'Thank you for making a purchase. Confirmation email with your data has been sent to the ad owner.', 'lisfinity-core' );
			if ( $arrange_visit ) {
				$result['message'] = __( 'Thank you for arranging a visit. Confirmation email with your data has been sent to the ad owner and you can expect that they will contact you soon.', 'lisfinity-core' );
			}
		}
		wp_send_json( $result );
	}

	public function send_commission( $product, $user, $business ) {
		$result = [];

		$business_id = carbon_get_post_meta( $product, 'product-business' );

		// store commission meta.
		update_post_meta( $business_id, "commission|{$product}", [
			'product'  => (int) $product,
			'business' => (int) $business_id,
			'buyer_id' => get_current_user_id(),
		] );

		// create email.
		$to               = carbon_get_post_meta( $business_id, 'profile-email' );
		$subject          = sprintf( esc_html__( 'Auction Won | %s', 'lisfinity-core' ), get_option( 'blogname' ) );
		$headers          = [ 'Content-Type: text/html; charset=UTF-8' ];
		$commission_link  = get_permalink( lisfinity_get_page_id( 'page-account' ) ) . 'commissions';
		$commission_title = esc_html__( 'Commission Pending', 'lisfinity-core' );
		$body             = sprintf( __( 'Someone won an auction for your listing: <strong>%1$s</strong> <br /> Click on the link in order to pay for the commission: <br /><br /> %2$s',
			'lisfinity-core' ), get_the_title( $product ), "<a href='{$commission_link}' target='_blank'>{$commission_title}</a>" );

		apply_filters( 'lisfinity__send_commission_email', $product, $user, $body );

		// send the email.
		$mail = wp_mail( $to, $subject, $body, $headers );

		if ( ! $mail ) {
			$result['error']   = true;
			$result['message'] = __( 'The mail could not have been sent', 'lisfinity-core' );

			return $result;
		}

		$result['success'] = true;
		$result['message'] = __( 'You won the competition! And email with your details was sent to the listing owner and you can expect to be contacted soon to arrange the details.', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Submit bid handler
	 * ------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function submit_bid( WP_REST_Request $request_data ) {
		$data    = $request_data->get_params();
		$user_id = get_current_user_id();
		$result  = [];

		// Error | Product not set
		if ( empty( $data['product_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Product is not set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		// Error | Owner is not set
		if ( empty( $data['owner_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Owner of the product is not set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		// Error | Bidder is not set
		if ( empty( $user_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Bidder is not set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		// Error | Amount is not set
		if ( empty( $data['amount'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Amount is not specified or in correct format.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		$data['bidder_id'] = $user_id;
		$data['status']    = $user_id;

		if ( ! isset( $data['message'] ) ) {
			$data['message'] = '';
		}

		$bid_model = new BidModel();
		$bid_id    = $bid_model->store_bid( $data );

		// Error | Bid couldn't be placed.
		if ( empty( $bid_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Bid could not be placed.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		// add notification.
		$business          = carbon_get_post_meta( $data['product_id'], 'product-business' );
		$notification_data = [
			'user_id'     => $user_id,
			'type'        => 1,
			'product_id'  => $data['product_id'],
			'business_id' => $business,
			'parent_id'   => $bid_id,
			'parent_type' => 2,
			'status'      => 0,
		];

		$notification_model = new NotificationModel();
		$notification_model->store_notification( $notification_data );


		// notify all other bidders.
		$bidders = $bid_model->where( [
			[ 'bidder_id', '<>', $user_id ],
			[ 'product_id', '=', $data['product_id'] ],
		] )->get( '', '', 'DISTINCT bidder_id', 'col' );
		if ( ! empty( $bidders ) ) {
			foreach ( $bidders as $bidder ) {
				if ( 'yes' === get_user_meta( $bidder, '_email_subscription|bid', true ) ) {
					// create notification.
					$notification_data = [
						'user_id'     => $bidder,
						'type'        => 1,
						'product_id'  => $data['product_id'],
						'business_id' => $business,
						'parent_id'   => $bid_id,
						'parent_type' => 7,
						'status'      => 0,
					];

					$notification_model->store_notification( $notification_data );

					// send email.
					$bidder_data = get_userdata( $bidder );
					$body        = sprintf( __( 'Your proposed price for the product: %s has been outbid', 'lisfinity-core' ), get_permalink( $data['product_id'] ) );
					$headers     = [ 'Content-Type: text/html; charset=UTF-8' ];
					$mail        = wp_mail( $bidder_data->user_email, __( 'You have been outbid', 'lisfinity-core' ), $body, $headers );
				}
			}
		}

		$reserve_price = get_post_meta( $data['product_id'], '_product-auction-reserve-price', true );

		$result['reserve_message'] = false;
		if ( ! empty( $reserve_price ) ) {
			if ( $data['amount'] > $reserve_price ) {
				$result['reserve_message'] = __( 'Your offer has been added but is lower than the reserve price, you should make a higher offer to have a chance to win this auction', 'lisfinity-core' );
			}
		}

		$result['success'] = true;
		$result['message'] = __( 'Bid successfully placed.', 'lisfinity-core' );
		wp_send_json( $result );
	}

	/**
	 * Update a bid to seen - used by notifications section on
	 * a user dashboard
	 * -------------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function update_bid( WP_REST_Request $request_data ) {
		$data    = $request_data->get_params();
		$user_id = get_current_user_id();
		$result  = [];

		if ( $data['type'] === 'status' ) {

			// if user opened bids dashboard page
			if ( isset( $data['page'] ) && $data['page'] === 'bids' ) {
				// Error | Product id not set.
				if ( empty( $data['product'] ) ) {
					$result['error']   = true;
					$result['message'] = __( 'Product id has not been set.', 'lisfinity-core' );
					wp_send_json( $result );
				}

				$bid_model = new BidModel();
				$bid_id    = $bid_model->set( 'status', 'seen' )->where( 'product_id', $data['product'], false )->update();

				$result['success'] = true;
				$result['message'] = __( 'Bid status has been changed.', 'lisfinity-core' );
				wp_send_json( $result );
			}

			// Error | Bid id not set.
			if ( empty( $data['id'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Bid id has not been set.', 'lisfinity-core' );
				wp_send_json( $result );
			}

			if ( empty( $bid_id ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Bid status could not have been changed.', 'lisfinity-core' );
				wp_send_json( $result );
			}
		}
	}

}

