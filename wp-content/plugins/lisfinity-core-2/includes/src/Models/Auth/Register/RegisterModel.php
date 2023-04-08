<?php

namespace Lisfinity\Models\Auth\Register;

use Lisfinity\Abstracts\Model;
use Lisfinity\Controllers\PackageController;
use Lisfinity\Helpers\Crypto;
use Lisfinity\Models\Auth\Twilio\TwilioModel as twilio;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\Users\ProfilesModel;

class RegisterModel {

	public function process_registration( $data ) {
		global $wpdb;
		$result = [];

		if ( '0' === lisfinity_get_option( 'auth-enabled' ) ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Registration is not allowed', 'lisfinity-core' );

			return $result;
		}

		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$result['error']                  = true;
			$result['error_message']['email'] = __( 'Please provide a valid email address', 'lisfinity-core' );
		}
		if ( email_exists( $data['email'] ) ) {
			$result['error']                  = true;
			$result['error_message']['email'] = __( 'The email already exists.', 'lisfinity-core' );
		}
		if ( empty( $data['username'] ) ) {
			$result['error']                     = true;
			$result['error_message']['username'] = __( 'The username field cannot be empty.', 'lisfinity-core' );
		}
		$password_min = 4;
		if ( empty( $data['password'] ) ) {
			$result['error']                     = true;
			$result['error_message']['password'] = __( 'The password cannot be empty', 'lisfinity-core' );
		} else if ( strlen( $data['password'] ) < 4 ) {
			$result['error']                     = true;
			$result['error_message']['password'] = sprintf( __( 'The password cannot be less than %d characters', 'lisfinity-core' ), $password_min );
		}

		$use_name_fields = lisfinity_get_option( 'auth-name-fields' );
		if ( $use_name_fields ) {
			if ( empty( $data['name'] ) ) {
				$result['error']                 = true;
				$result['error_message']['name'] = __( 'The name field cannot be empty', 'lisfinity-core' );
			}
			if ( empty( $data['surname'] ) ) {
				$result['error']                    = true;
				$result['error_message']['surname'] = __( 'The surname field cannot be empty', 'lisfinity-core' );
			}
		}

		// send sms activation key if necessary.
		$verification_enabled = '1' === lisfinity_get_option( 'auth-verification' );
		$sms_verification     = '1' === lisfinity_get_option( 'auth-verification-sms' );
		if ( $verification_enabled && $sms_verification && empty( $data['phone'] ) ) {
			$result['error']                  = true;
			$result['error_message']['phone'] = __( 'The phone field cannot be empty.', 'lisfinity-core' );
		}

		if ( '1' === lisfinity_get_option( 'auth-captcha-enabled' ) ) {
			$captcha = $this->validate_recaptcha_field();
			if ( isset( $captcha['message'] ) ) {
				$result['error']                   = true;
				$result['error_message']['global'] = $captcha['message'];
			}
			if ( isset( $captcha->errors ) || true !== $captcha ) {
				$result['error']                   = true;
				$result['error_message']['global'] = __( 'Captcha check failed. Please try again.', 'lisfinity-core' );
			}

			if ( isset( $result['error'] ) ) {


				return $result;
			}
		}

		$page_terms = lisfinity_get_option( 'page-terms' );

		if ( ! empty( $page_terms ) ) {
			if ( empty( $data['terms'] ) ) {
				$result['error']                  = true;
				$result['error_message']['terms'] = __( 'The terms have to be accepted.', 'lisfinity-core' );
			}
		} else {
			$data['terms'] = true;
		}

		// bail if there's an error with a fields.
		if ( isset( $result['error'] ) ) {
			return $result;
		}

		// create a new user.
		$user_data = apply_filters(
			'lisfinity__new_user_data', [
				'user_login' => $data['username'],
				'user_pass'  => $data['password'],
				'user_email' => $data['email'],
				'role'       => 'editor',
			]
		);

		// if verification is being used.
		$verification_enabled = lisfinity_get_option( 'auth-verification' );
		if ( $verification_enabled && $sms_verification ) {

			// send sms verification if necessary.
			$code          = lisfinity_generate_pin();
			$result['sms'] = true;
			$twilio        = twilio::send_sms( $data['phone'], $code );

			if ( ! empty( $twilio ) ) {
				$result['error']                  = true;
				$result['error_message']['phone'] = $twilio;

				return $result;
			}

		}

		$user_id = wp_insert_user( $user_data );

		// set default account type.
		if ( lisfinity_is_enabled( lisfinity_get_option( 'choose-profile-register-form' ) ) && array_key_exists( 'profile', $data ) ) {
			carbon_set_user_meta( $user_id, 'account-type', $data['profile'] ?? 'personal' );
		} else {
			carbon_set_user_meta( $user_id, 'account-type', lisfinity_get_option( 'auth-default-account-type' ) ?? 'personal' );
		}

		if ( array_key_exists( 'first_name', $data ) ) {

			update_user_meta( $user_id, 'first_name', $data['first_name'] );
		}
		if ( array_key_exists( 'last_name', $data ) ) {
			update_user_meta( $user_id, 'last_name', $data['last_name'] );
		}

		// subscribe user to emails by default.
		if ( lisfinity_is_enabled( lisfinity_get_option( 'email-subscriptions-by-default' ) ) ) {
			foreach ( lisfinity_get_subscriptions() as $subscription ) {
				update_user_meta( $user_id, "_email_subscription|$subscription", 'yes' );
			}
		}

		// format business name.
		$business_name = lisfinity_get_option( 'auth-business-name' );
		if ( ! empty( $business_name ) && false !== strpos( $business_name, '%s' ) ) {
			$business_name = sprintf( $business_name, $user_data['user_login'] );
		} else if ( empty( $business_name ) ) {
			$business_name = sprintf( __( 'Business: %s', 'lisfinity-core' ), $user_data['user_login'] );
		}
		$business_args = [
			'post_type'   => ProfilesModel::$post_type_name,
			'post_status' => 'publish',
			'post_title'  => $business_name,
			'post_author' => $user_id,
		];

		$business_id = wp_insert_post( $business_args );
		if ( array_key_exists( 'phone', $data ) ) {
			$phones[] = [
				'_type'              => '_',
				'profile-phone'      => $data['phone'],
				'profile-phone-apps' => lisfinity_get_option( 'business-phone-apps' ) ?? false,
			];
			if ( lisfinity_is_enabled( lisfinity_get_option( 'phone-number-register-form' ) ) ) {
				carbon_set_post_meta( $business_id, 'profile-phones', $phones );
			}
		}

		if ( lisfinity_is_enabled( lisfinity_get_option( 'website-register-form' ) ) && array_key_exists( 'website', $data ) ) {
			carbon_set_post_meta( $business_id, 'profile-website', $data['website'] );
		}
		if ( lisfinity_is_enabled( lisfinity_get_option( 'site-vendor-approval' ) ) ) {
			carbon_set_post_meta( $business_id, 'can-post-listings', 0 );
		}

		carbon_set_post_meta( $business_id, 'profile-email', $data['email'] );

		$default_packages = lisfinity_get_option( 'auth-default-packages' );

		if ( ! empty( $default_packages ) ) {
			$package_controller = new PackageController();

			foreach ( $default_packages as $package ) {
				$package_obj = wc_get_product( $package );

				if ( ! empty( $package_obj ) ) {
					$values = [
						// id of the customer that made order.
						$user_id,
						// wc product id of this item.
						(int) $package,
						// wc order id for this item.
						0,
						// limit amount of products in a package.
						carbon_get_post_meta( $package, 'package-products-limit' ),
						// current amount of submitted products in this package.
						0,
						// duration of the submitted products.
						carbon_get_post_meta( $package, 'package-products-duration' ),
						// type of the package.
						'payment_package',
						// status of the package.
						'active',
					];
					$package_controller->store( $values );
				}
			}
		}

		if ( empty( $business_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Business profile could have not been set.', 'lisfinity-core' );

			return $result;
		}

		// store sms code in a transient.
		if ( $verification_enabled && $sms_verification ) {
			$crypted_code = Crypto::encrypt( $code, Crypto::KEY, true );
			set_transient( "lisfinity--sms-{$user_id}", "{$data['phone']}:{$crypted_code}", 3 * DAY_IN_SECONDS );
		}

		if ( is_wp_error( $user_id ) ) {
			$message                           = $user_id->get_error_message();
			$result['error']                   = true;
			$result['error_message']['global'] = $message;

			// bail
			return $result;
		}

		// if verification is being used | sms verification.
		if ( $verification_enabled && $sms_verification ) {
			$register_page_id   = lisfinity_get_page_id( 'page-register' );
			$result['redirect'] = add_query_arg( [
				'sms' => 'yes',
				'id'  => $user_id,
			], get_permalink( $register_page_id ) );

			$result['success'] = true;
			$result['message'] = __( 'You have been successfully registered. Please verify your phone number.',
				'lisfinity-core' );

			$sms_code      = ! empty( $code ) ? $code : '';
			$key           = self::send_account_verification( $user_id, true, $sms_code );
			$result['key'] = $key;

			return $result;
		}

		// if verification is being used | email verification.
		if ( $verification_enabled ) {
			$this->send_account_email( $user_data, $user_id );
			$result['success'] = true;
			$result['message'] = __( 'You have been successfully registered. Account verification email has been sent to the address you provided.',
				'lisfinity-core' );

			return $result;
		}

		// if verification is disabled verify user automatically.
		if ( ! $verification_enabled ) {
			carbon_set_user_meta( $user_id, 'verified', true );
		}

		// login new user.
		$this->set_user_auth_cookie( $user_id );
		$result['success'] = true;
		$result['message'] = __( 'You have been successfully registered', 'lisfinity-core' );

		// redirect new user.
		if ( isset( $data['redirect_to'] ) ) {
			$result['redirect'] = wp_sanitize_redirect( $data['redirect_to'] );
		} elseif ( $verification_enabled ) {
			$result['redirect'] = false;
		} else {
			$result['redirect'] = get_permalink( lisfinity_get_page_id( 'page-account' ) );
		}

		return $result;
	}

	/**
	 * Get all promotions that are connected to a package
	 * --------------------------------------------------
	 *
	 * @param $package_id
	 *
	 * @return array
	 */
	public function get_promotions_for_package( $package_id ) {
		$promotion  = new PromotionsModel();
		$packages   = $promotion->where( 'package_id', $package_id )->get();
		$promotions = [];

		if ( $packages ) {
			foreach ( $packages as $package ) {
				$package->duration = carbon_get_post_meta( $package->wc_product_id, 'promotion-duration' );
				$package->addon    = str_replace( 'addon-', '', $package->position );
				$regular_price     = get_post_meta( $package->wc_product_id, '_regular_price', true );
				$sale_price        = get_post_meta( $package->wc_product_id, '_sale_price', true );
				$package->price    = $regular_price;
				if ( ! empty( $sale_price ) && 0 !== $sale_price ) {
					$package->price = $sale_price;
				}
				$promotions[ $package->addon ] = $package;
			}
		}

		return $promotions;
	}

	public function resend_sms_code( $user_id ) {
		$result = [];
		$code   = get_transient( "lisfinity--sms-{$user_id}" );
		if ( empty( $code ) ) {
			$result['error']                     = true;
			$result['error_message']['sms_code'] = __( 'Your code has been expired.', 'lisfinity-core' );

			return $result;
		}
		list( $phone, $sms_code ) = explode( ':', $code );
		$encrypted_code = Crypto::decrypt( $sms_code, Crypto::KEY, true );
		$twilio         = twilio::send_sms( $phone, $encrypted_code );

		if ( ! empty( $twilio ) ) {
			$result['error']                  = true;
			$result['error_message']['phone'] = $twilio;

			return $result;
		}

		$result['sms_code'] = $encrypted_code;
		$result['message']  = __( 'The code has been successfully sent', 'lisfinity-core' );
		$result['success']  = true;

		return $result;
	}

	protected function send_account_email( $user_data, $user_id ) {
		//todo implement email system
		$to      = $user_data['user_email'];
		$subject = sprintf( esc_html__( 'Welcome to %s', 'lisfinity-core' ), get_option( 'blogname' ) );
		$body    = sprintf( esc_html__( 'Thanks for creating account on %1$s. Your username is: %2$s ',
			'lisfinity-core' ), get_option( 'blogname' ), $user_data['user_login'] );
		//todo not existent option - will be useful for the phone registration when integrated.
		if ( lisfinity_get_option( 'auth-generate-password' ) ) {
			$body .= sprintf( esc_html__( 'Your password is: %s', 'lisfinity-core' ), $user_data['user_pass'] );
		}

		if ( lisfinity_get_option( 'auth-verification' ) ) {
			$body .= self::send_account_verification( $user_id );
		}

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );
	}

	private function set_user_auth_cookie( $user_id ) {
		global $current_user;

		$current_user = get_user_by( 'id', $user_id ); // WPCS: override ok.

		wp_set_auth_cookie( $user_id, true );
	}

	public function send_account_verification( $user_id, $sms_verification = false, $sms_code = '' ) {
		$mail_body        = '';
		$user             = get_user_by( 'id', absint( $user_id ) );
		$user_login       = $user ? $user->user_login : '';
		$verification_key = $sms_verification ? $sms_code : wp_generate_password( 20, false );
		$hashed           = password_hash( $verification_key, PASSWORD_DEFAULT );
		$value            = sprintf( '%s:%s', $user_login, $verification_key );
		$hashed_value     = sprintf( '%s:%s', $user_login, $hashed );
		update_user_meta( $user_id, 'user_verification_key', $hashed_value );
		if ( ! $sms_verification ) {
			$verification_link = add_query_arg(
				[
					'verification' => $value,
				],
				home_url() );

			$mail_body .= sprintf( '<a href="' . $verification_link . '">' . __( 'Click here to activate your account!',
					'lisfinity-core' ) . '</a>' );

			return $mail_body;
		}

		return $verification_key;
	}

	public function verify_user() {
		$verification = $this->check_verification();
		if ( false !== $verification && $verification[0] ) {
			carbon_set_user_meta( $verification[1], 'verified', true );
			delete_user_meta( $verification[1], 'user_verification_key' );

			$this->set_user_auth_cookie( $verification[1] );

			wp_redirect( get_permalink( lisfinity_get_page_id( 'page-account' ) ) );
		}

		return false;
	}

	private function check_verification() {
		if ( isset( $_GET['verification'] ) && 0 < strpos( $_GET['verification'],
				':' ) ) {  // @codingStandardsIgnoreLine
			list( $user_login, $verification_key ) = explode( ':',
				wp_unslash( $_GET['verification'] ), 2 );

			// check whether user exists.
			$user_data = get_user_by( 'login', $user_login );
			if ( $user_data ) {
				// check whether a key exists.
				$user_verification = get_user_meta( $user_data->ID, 'user_verification_key', true );
				if ( $user_verification ) {
					list( $db_login, $db_key ) = explode( ':', wp_unslash( $user_verification ), 2 );
					$check_key = password_verify( $verification_key, $db_key );

					return [ $check_key, $user_data->ID ];
				}
			}

			return false;
		}

		return false;
	}

	private function validate_recaptcha_field() {
		$recaptcha_field_label = lisfinity_get_option( 'auth-captcha-label' );
		if ( empty( $_POST['g-recaptcha-response'] ) ) {
			return $result['message'] = sprintf( esc_html__( '"%s" check failed. Please try again.', 'lisfinity-core' ), $recaptcha_field_label );
		}

		$response = wp_remote_get(
			add_query_arg(
				array(
					'secret'   => lisfinity_get_option( 'auth-captcha-secret-key' ),
					'response' => isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '',
					'remoteip' => isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
				),
				'https://www.google.com/recaptcha/api/siteverify'
			)
		);

		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			return false;
		}

		$json = json_decode( $response['body'] );
		if ( ! $json || ! $json->success ) {
			return false;
		}

		return true;
	}

	/**
	 * Create business profile for the users created by WooCommerce
	 * ------------------------------------------------------------
	 *
	 * @param $user_id
	 * @param $new_customer_data
	 * @param $password_generated
	 *
	 * @return array
	 */
	public function lisfinity_create_business_profile_for_wc_customer( $user_id, $new_customer_data, $password_generated ) {
		// set default account type.
		$user_data = get_userdata( $user_id );
		carbon_get_user_meta( $user_id, 'account-type', lisfinity_get_option( 'auth-default-account-type' ) ?? 'personal' );

		// format business name.
		$business_name = lisfinity_get_option( 'auth-business-name' );
		if ( ! empty( $business_name ) && false !== strpos( $business_name, '%s' ) ) {
			$business_name = sprintf( $business_name, $user_data->user_login );
		} else if ( empty( $business_name ) ) {
			$business_name = sprintf( __( 'Business: %s', 'lisfinity-core' ), $user_data->user_login );
		}
		$business_args = [
			'post_type'   => ProfilesModel::$post_type_name,
			'post_status' => 'publish',
			'post_title'  => $business_name,
			'post_author' => $user_id,
		];

		$business_id = wp_insert_post( $business_args );

		$default_packages = lisfinity_get_option( 'auth-default-packages' );

		if ( ! empty( $default_packages ) ) {
			$package_controller = new PackageController();

			foreach ( $default_packages as $package ) {
				$package_obj = wc_get_product( $package );

				if ( ! empty( $package_obj ) ) {
					$values = [
						// id of the customer that made order.
						$user_id,
						// wc product id of this item.
						(int) $package,
						// wc order id for this item.
						0,
						// limit amount of products in a package.
						carbon_get_post_meta( $package, 'package-products-limit' ),
						// current amount of submitted products in this package.
						0,
						// duration of the submitted products.
						carbon_get_post_meta( $package, 'package-products-duration' ),
						// type of the package.
						'payment_package',
						// status of the package.
						'active',
					];
					$package_controller->store( $values );
				}
			}
		}

		if ( empty( $business_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Business profile could have not been set.', 'lisfinity-core' );

			return $result;
		}
	}

}
