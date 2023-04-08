<?php

namespace Lisfinity\Gateways;

class PayPal {

	public $username;
	public $password;
	public $signature;
	public $version;
	public $mode = '.sandbox'; // can be empty or .sandbox
	public $token;
	public $returnUrl = '';
	public $cancelUrl = '';
	public $url;

	public function __construct( $data ) {
		$option        = get_option( 'woocommerce_paypal_settings' );
		$this->mode    = 'yes' == $option['testmode'] ? '.sandbox' : '';
		$this->url     = "https://api-3t" . $this->mode . ".paypal.com/nvp";
		$this->version = urlencode( '109.0' );
		if ( ! empty( $data['username'] ) && ! empty( $data['password'] ) && ! empty( $data['signature'] ) ) {
			$this->username  = urlencode( $data['username'] );
			$this->password  = urlencode( $data['password'] );
			$this->signature = urlencode( $data['signature'] );
			$this->returnUrl = $data['returnUrl'];
			$this->cancelUrl = $data['cancelUrl'];
		} else {
			wp_send_json( [ 'error' => __( 'API username, API password and APi signature can not be blank.', 'lisfinity-core' ) ] );
		}

	}

	public function exception( $message ) {
		throw new Exception( $message );
	}

	public function MassPay( $data ) {
		$response = $this->httpPost( 'MassPay', $data );
		if ( ! empty( $response['error'] ) ) {
			return $response;
		} else {
			if ( "SUCCESS" == strtoupper( $response["ACK"] ) || "SUCCESSWITHWARNING" == strtoupper( $response["ACK"] ) ) {
				return $response;
			} else {
				return array( 'error' => urldecode( $response["L_LONGMESSAGE0"] ) );
			}
		}
	}

	public function httpPost( $method, $data ) {
		$body = array_merge( array(
			'METHOD'    => $method,
			'VERSION'   => $this->version,
			'PWD'       => $this->password,
			'USER'      => $this->username,
			'SIGNATURE' => $this->signature,
			'RETURNURL' => $this->returnUrl,
			'CANCELURL' => $this->cancelUrl,
		), $data );

		$response = wp_remote_post( $this->url, array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => $body,
			'cookies'     => array()
		) );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();

			return array( 'error' => "Something went wrong: $error_message" );
		} else {
			$data        = array();
			$data_string = explode( "&", $response['body'] );
			foreach ( $data_string as $key_value ) {
				$key_value             = explode( '=', $key_value );
				$data[ $key_value[0] ] = $key_value[1];
			}

			return $data;
		}
	}
}
