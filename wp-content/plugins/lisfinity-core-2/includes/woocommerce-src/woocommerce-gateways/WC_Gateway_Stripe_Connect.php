<?php


if ( ! class_exists( 'WC_Gateway_Stripe_Connect' ) ) {
	class WC_Gateway_Stripe_Connect extends WC_Payment_Gateway {

		public function __construct() {

			$this->id                 = 'stripe_connect';
			$this->icon               = apply_filters( 'woocommerce_offline_icon', '' );
			$this->has_fields         = false;
			$this->method_title       = __( 'Stripe Connect', 'lisfinity-core' );
			$this->method_description = __( 'Take payment via Stripe Connect and split the amount between the site and your vendors.', 'lisfinity-core' );
			$this->order_button_text  = __( 'Pay With Stripe', 'lisfinity-core' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->public_key   = $this->get_option( '_stripe-pk' );
			$this->secret_key   = $this->get_option( '_stripe-sk' );
			$this->client_id    = $this->get_option( '_stripe-ca' );
			$this->redirect_uri = $this->get_option( '_stripe-oauth-redirect' );

			// We need custom JavaScript to obtain a token
			add_action( 'wp_enqueue_scripts', [ $this, 'payment_scripts' ] );

			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				$this,
				'process_admin_options'
			) );
		}

		public function init_form_fields() {

			$stripe_link = wc_get_endpoint_url( 'earnings' ) . '/settings';

			$this->form_fields = apply_filters( 'stripe_connect_form_fields', array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'lisfinity-core' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Stripe Connect', 'lisfinity-core' ),
					'default' => 'no'
				),

				'title' => array(
					'title'       => __( 'Title', 'lisfinity-core' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'lisfinity-core' ),
					'default'     => __( 'Stripe Connect', 'lisfinity-core' ),
					'desc_tip'    => true,
				),

				'description' => array(
					'title'       => __( 'Description', 'lisfinity-core' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'lisfinity-core' ),
					'default'     => sprintf( __( 'Your account has to be connected with the site in order to use Stripe Connect. You can do that by clicking here %s', 'lisfinity-core' ), '<a href="' . esc_url( $stripe_link ) . '" target="_blank" class="stripe-connect-btn">Stripe Connect</a>' ),
					'desc_tip'    => true,
				),

				'_stripe-pk' => [
					'title'       => __( 'Publishable Key', 'lisfinity-core' ),
					'description' => sprintf( __( 'Your Stripe public key that you can obtain here: %s', 'lisfinity-core' ), '<a href="' . esc_url( 'https://dashboard.stripe.com/apikeys' ) . '" target="_blank">Get API Keys</a>' ),
					'type'        => 'text'
				],

				'_stripe-sk' => [
					'title'       => __( 'Secret Key', 'lisfinity-core' ),
					'description' => sprintf( __( 'Your Stripe secret key that you can obtain here: %s', 'lisfinity-core' ), '<a href="' . esc_url( 'https://dashboard.stripe.com/apikeys' ) . '" target="_blank">Get API Keys</a>' ),
					'type'        => 'text',
				],

				'_stripe-ca' => [
					'title'       => __( 'Client ID', 'lisfinity-core' ),
					'description' => sprintf( __( 'Live mode client ID that you can obtain here: %s', 'lisfinity-core' ), '<a href="' . esc_url( 'https://dashboard.stripe.com/settings/applications' ) . '" target="_blank">Get Client ID</a>' ),
					'type'        => 'text',
				],

				'_stripe-oauth-redirect' => [
					'title'       => __( 'Redirect URI (Do not delete)', 'lisfinity-core' ),
					'default'     => get_site_url() . wc_get_endpoint_url( 'earnings' ) . 'settings',
					'placeholder' => get_site_url() . wc_get_endpoint_url( 'earnings' ) . 'settings',
					'description' => sprintf( __( 'Copy the link above and create a redirect uri on this link: %s | Copy this link -> %s', 'lisfinity-core' ), '<a href="' . esc_url( 'https://dashboard.stripe.com/test/settings/applications' ) . '" target="_blank">Create Redirect URI</a>', get_site_url() . wc_get_endpoint_url( 'earnings' ) . 'settings' ),
					'type'        => 'text',
				],
			) );
		}

		public function payment_scripts() {
			if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && lisfinity_is_stripe_connect_enabled() ) {
				return;
			}

			// let's suppose it is our payment processor JavaScript that allows to obtain a token
			wp_enqueue_script( 'lisfinity-stripe', 'https://js.stripe.com/v3/', [], LISFINITY_CORE_VERSION );

			// and this is our custom JS in your plugin directory that works with token.js
			wp_register_script( 'lisfinity-payments', LISFINITY_CORE_URL . 'dist/statics/scripts/payments.js', [ 'jquery', ] );

			// in most payment processors you have to use PUBLIC KEY to obtain a token
			$business_id = lisfinity_get_premium_profile_id( get_current_user_id() );
			wp_localize_script( 'lisfinity-payments', 'payment_data', [
				'checkout_url'      => WC_AJAX::get_endpoint( 'checkout' ),
				'pk'                => $this->public_key,
				'stripe_connect_id' => get_post_meta( $business_id, '_stripe-connect-id', true ),
				'no_stripe_account' => sprintf( __( 'You need to connect your Stripe account with the platform in order to use Stripe Connect. You can do it by copying this link in the URL bar: %s', 'lisfinity-core' ), get_permalink( lisfinity_get_page_id( 'page-account' ) ) . 'earnings/settings' ),
			] );

			wp_enqueue_script( 'lisfinity-payments' );
		}

		public function process_payment( $order_id ) {

			$order             = wc_get_order( $order_id );
			$order_items       = $order->get_items();
			$product           = array_shift( $order_items );
			$payouts_model     = new \Lisfinity\Models\Vendors\PayoutsModel();
			$business_id       = carbon_get_post_meta( $product['product'], 'product-business' );
			$stripe_account_id = get_post_meta( $business_id, '_stripe-connect-id', true );

			\Stripe\Stripe::setApiKey( $this->secret_key );

			$total = number_format( $order->get_total(), 0 ) * 100;
			$fee   = $payouts_model->calculate_due_amount( $total );

			$stripe_args = [
				'payment_method_types' => [ 'card' ],
				'line_items'           => [
					[
						'name'     => $product->get_name(),
						'amount'   => $total,
						'currency' => $order->get_currency(),
						'quantity' => $product->get_quantity(),
						'images'   => [ get_the_post_thumbnail_url( $product['product'], 'full' ) ],
					]
				],
				'success_url'          => add_query_arg(
					[
						'result'  => 'success',
						'gateway' => 'stripe'
					], $this->get_return_url( $order ) ),
				'cancel_url'           => add_query_arg(
					[
						'result'  => 'cancel',
						'gateway' => 'stripe'
					], $this->get_return_url( $order ) ),
			];

			$product_id = isset( $product['product'] ) ? $product['product'] : $product['product_id'];

			// include fees only if the listings are being sold.
			if ( 'listing' === WC_Product_Factory::get_product_type( $product_id ) && $fee && ! empty( $stripe_account_id ) ) {
				$stripe_args['payment_intent_data'] = [
					'application_fee_amount' => $fee,
					'transfer_data'          => [
						'destination' => $stripe_account_id,
					],
				];
			}

			// create a session for the order.
			$session = \Stripe\Checkout\Session::create( $stripe_args );

			if ( $session->payment_intent ) {
				update_post_meta( $order_id, '_stripe-payment-intent', $session->payment_intent );
			}

			return [
				'result'            => 'success',
				'stripe_account_id' => $stripe_account_id,
				'session'           => $session->id,
				'order'             => $order_id,
				'refresh'           => false,
				'reload'            => false,
			];
		}
	}
}
