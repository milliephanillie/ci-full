<?php


namespace Lisfinity\REST_API\Payouts;


use Lisfinity\Abstracts\Route;
use Lisfinity\Gateways\PayPal;
use Lisfinity\Models\Vendors\PayoutsModel;

class PayoutsRoute extends Route {
	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'payouts_settings'      => [
			'path'                => '/payouts/settings',
			'callback'            => 'payouts_settings',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'payout_process'        => [
			'path'                => '/payouts/process-payout',
			'callback'            => 'process_payout',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'payout_process_mass'   => [
			'path'                => '/payouts/process-payout-mass',
			'callback'            => 'process_payout_mass',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'stripe_create_account' => [
			'path'                => '/stripe/create-account',
			'callback'            => 'stripe_create_account',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	/**
	 * Do a defined action for a current user
	 * --------------------------------------
	 *
	 * @param \WP_REST_Request $request_data
	 *
	 * @return array|bool
	 */
	public function payouts_settings( \WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['business_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Business ID has not been set.', 'lisfinity-core' );
		}

		if ( empty( $data['gateway'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Payment Gateway cannot be empty', 'lisfinity-core' );
		}
		update_post_meta( (int) $data['business_id'], '_payout-gateway', $data['gateway'] );

		if ( 'paypal' === $data['gateway'] ) {
			if ( empty( $data['paypal'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'PayPal Address is not set.', 'lisfinity-core' );
			} else if ( ! is_email( $data['paypal'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'PayPal address has to be an email address', 'lisfinity-core' );
			} else {
				update_post_meta( (int) $data['business_id'], '_payout-paypal', $data['paypal'] );
			}
		}

		if ( 'bank' === $data['gateway'] ) {
			if ( empty( $data['bank'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Bank details needs to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['account_name'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Account name has to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['account_number'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Account number has to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['bank_name'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Bank name has to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['routing_number'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Routing number has to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['iban'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'IBAN has to be provided', 'lisfinity-core' );
			}
			if ( empty( $data['bank']['swift'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'BIC / SWIFT has to be provided', 'lisfinity-core' );
			}

			update_post_meta( (int) $data['business_id'], 'bank__account-name', $data['bank']['account_name'] );
			update_post_meta( (int) $data['business_id'], 'bank__account-number', $data['bank']['account_number'] );
			update_post_meta( (int) $data['business_id'], 'bank__bank-name', $data['bank']['bank_name'] );
			update_post_meta( (int) $data['business_id'], 'bank__routing-number', $data['bank']['routing_number'] );
			update_post_meta( (int) $data['business_id'], 'bank__iban', $data['bank']['iban'] );
			update_post_meta( (int) $data['business_id'], 'bank__swift', $data['bank']['swift'] );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		$result['success'] = true;
		$result['message'] = __( 'Payment Gateway has been successfully set.', 'lisfinity-core' );

		return $result;
	}

	public function process_payout( \WP_REST_Request $request_data ) {
		$data     = $request_data->get_params();
		$user_id  = get_current_user_id();
		$response = [];

		$option    = get_option( 'woocommerce_paypal_settings' );
		$testmode  = 'yes' == $option['testmode'] ? 'sandbox_' : '';
		$signature = $option["{$testmode}api_signature"];
		$username  = $option["{$testmode}api_username"];
		$password  = $option["{$testmode}api_password"];

		$paypal = new PayPal( [
			'username'  => $username,
			'password'  => $password,
			'signature' => $signature,
			'cancelUrl' => '',
			'returnUrl' => '',
		] );

		$pdata             = [
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => get_option( 'woocommerce_currency' ),
		];
		$pdata['L_EMAIL0'] = $data['email'];
		$pdata['L_AMT0']   = $data['amount'];

		$details = $paypal->MassPay( $pdata );

		$response['details'] = $details;

		if ( ! empty( $details['error'] ) ) {
			$response['error'] = $details['error'];
		} else {
			$response['success'] = esc_html__( 'Payment Sent', 'lisfinity-core' );
			update_post_meta( $data['id'], '_payout-status', 'paid' );
		}

		wp_send_json( $response );
	}

	public function process_payout_mass() {
		$response  = [];
		$option    = get_option( 'woocommerce_paypal_settings' );
		$testmode  = 'yes' == $option['testmode'] ? 'sandbox_' : '';
		$signature = $option["{$testmode}api_signature"];
		$username  = $option["{$testmode}api_username"];
		$password  = $option["{$testmode}api_password"];

		$paypal = new PayPal( [
			'username'  => $username,
			'password'  => $password,
			'signature' => $signature,
			'cancelUrl' => '',
			'returnUrl' => '',
		] );

		$pdata = [
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => get_option( 'woocommerce_currency' ),
		];

		$payout_model = new PayoutsModel();

		$payout_ids = $payout_model->get_payout_ids();
		$pay_count  = 0;
		foreach ( $payout_ids as $id ) {
			$user_id     = carbon_get_post_meta( $id, 'payout-vendor' );
			$business_id = lisfinity_get_premium_profile_id( $user_id );
			$amount      = get_post_meta( $id, '_amount-due', true );
			$gateway     = get_post_meta( $business_id, '_payout-gateway', true );
			$email       = get_post_meta( $business_id, '_payout-paypal', true );

			if ( 'paypal' === $gateway && ! empty( $email ) && is_email( $email ) ) {
				$pdata["L_EMAIL{$pay_count}"] = $email;
				$pdata["L_AMT{$pay_count}"]   = floatval( $amount );
				$response['ids'][]            = $id;
				$response['success'][ $id ]   = true;
				$response['message'][ $id ]   = esc_html__( 'Payment Sent', 'lisfinity-core' );
			} else {
				$response['error'][ $id ]   = true;
				$response['message'][ $id ] = esc_html__( 'PayPal email address is not set', 'lisfinity-core' );
			}

			$pay_count ++;
		}

		$details = $paypal->MassPay( $pdata );

		$response['details'] = $details;

		if ( ! empty( $details['error'] ) ) {
			$response          = array();
			$response['error'] = $details['error'];
		} else {
			$response['success'] = esc_html__( 'Payments Sent', 'lisfinity-core' );
			foreach ( $payout_ids as $id ) {
				update_post_meta( $id, '_payout-status', 'paid' );
			}
		}

		wp_send_json( $response );
	}

	public function stripe_create_account( \WP_REST_Request $request_data ) {
		$data        = $request_data->get_params();
		$user_id     = get_current_user_id();
		$user        = get_userdata( $user_id );
		$business_id = lisfinity_get_premium_profile_id( $user_id );
		$result      = [];

		try {
			$payment_gateway = WC()->payment_gateways->payment_gateways()['stripe_connect'];
			\Stripe\Stripe::setApiKey( $payment_gateway->secret_key );
			$response = \Stripe\OAuth::token( [
				'grant_type' => 'authorization_code',
				'code'       => $data['stripe'],
			] );
		} catch ( \Exception $e ) {
			$result['error']   = true;
			$result['message'] = $e->getMessage();

			return $result;
		}

		// Access the connected account id in the response
		$connected_account_id = $response->stripe_user_id;

		update_post_meta( $business_id, '_stripe-connect-id', $connected_account_id );

		$result['success']          = true;
		$result['message']          = __( 'Successfully connected account to Stripe', 'lisfinity-core' );
		$result['stripe_connected'] = true;

		return $result;
	}
}
