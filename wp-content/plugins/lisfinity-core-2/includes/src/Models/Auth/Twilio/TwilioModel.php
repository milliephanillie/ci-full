<?php


namespace Lisfinity\Models\Auth\Twilio;

use PasswordHash;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

class TwilioModel {

	/**
	 * Send a sms to a user trying to register
	 * ---------------------------------------
	 *
	 * @param $to_number
	 * @param $code
	 *
	 * @return \Exception|RestException
	 * @throws \Twilio\Exceptions\ConfigurationException
	 * @throws \Twilio\Exceptions\TwilioException
	 */
	public static function send_sms( $to_number, $code ) {
		// Your Account SID and Auth Token from twilio.com/console.
		$account_sid = lisfinity_get_option( 'auth-verification-sms-sid' );
		$auth_token  = lisfinity_get_option( 'auth-verification-sms-token' );

		// A Twilio number you own with SMS capabilities.
		$twilio_number = lisfinity_get_option( 'auth-verification-sms-number' );

		$client = new Client( $account_sid, $auth_token );
		try {
			$client->messages->create(
			// Where to send a text message (your cell phone?)
				$to_number,
				array(
					'from' => $twilio_number,
					'body' => "Your verification code: $code"
				)
			);

		} catch ( RestException $e ) {
			// uncomment to get full twilio error message.
			return $e->getMessage();
		}
	}

	/**
	 * Verify SMS Code
	 * ---------------
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function process_sms_code( $data ) {
		$result = [];

		if ( empty( $data['id'] ) || ! get_user_by( 'id', $data['id'] ) ) {
			$result['error']                     = true;
			$result['error_message']['sms_code'] = __( 'User is not existing', 'lisfinity-core' );
		}
		if ( empty( $data['sms_code'] ) ) {
			$result['error']                     = true;
			$result['error_message']['sms_code'] = __( 'Code cannot be empty', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		$verified       = carbon_get_user_meta( $data['id'], 'verified' );
		$activation_key = get_user_meta( $data['id'], 'user_verification_key', true );
		list( $db_login, $db_key ) = explode( ':', wp_unslash( $activation_key ), 2 );
		$check_key     = password_verify( $data['sms_code'], $db_key );
		$result['key'] = $check_key;

		if ( $verified ) {
			$result['success'] = true;
			$result['message'] = __( 'Your account has already been verified', 'lisfinity-core' );
		} elseif ( $check_key ) {
			carbon_set_user_meta( $data['id'], 'verified', true );
			$result['success'] = true;
			$result['message'] = __( 'Your account has been successfully verified', 'lisfinity-core' );
			delete_transient( "lisfinity--sms-{$data['id']}" );
		} else {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Your account could not be successfully verified. Please double check or resend the code.',
				'lisfinity-core' );
		}

		return $result;
	}

}
