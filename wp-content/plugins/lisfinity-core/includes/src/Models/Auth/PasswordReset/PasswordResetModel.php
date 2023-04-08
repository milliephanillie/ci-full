<?php

namespace Lisfinity\Models\Auth\PasswordReset;

use WP_Error;
use WP_User;

class PasswordResetModel {

	/**
	 * Handle lost password form.
	 */
	public function process_lost_password( $data ) {
		$result = [];

		$success = $this->retrieve_password( $data );

		if ( isset( $success['error'] ) ) {
			$result = $success;
			// If successful, redirect to my account with query arg set.
		} elseif ( $success ) {
			$result['success'] = true;
			$result['message'] = __( 'The Password has been emailed to you.', 'lisfinity-core' );
		}

		return $result;
	}

	protected function retrieve_password( $data ) {
		$result = [];
		$login  = sanitize_user( wp_unslash( $data['user_login'] ) );

		if ( empty( $login ) ) {
			$result['error']                       = true;
			$result['error_message']['user_login'] = __( 'Enter a username or email address.', 'lisfinity-core' );

			return $result;
		}

		// Check on username first, as users can use emails as username.
		$user_data = get_user_by( 'login', $login );

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new WP_Error();

		if ( $errors->get_error_code() ) {
			$result['error']                   = true;
			$result['error_message']['global'] = $errors->get_error_message();
		}

		if ( ! $user_data ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Invalid Username or Email', 'lisfinity-core' );

			return $result;
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Invalid Username or Email', 'lisfinity-core' );

			return $result;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Password reset is not allowed for this user', 'lisfinity-core' );

			return $result;
		} elseif ( is_wp_error( $allow ) ) {
			$result['error']                   = true;
			$result['error_message']['global'] = esc_html( $errors->get_error_message() );

			return $result;
		}

		// Get password reset key (function introduced in WordPress 4.4).
		$key = get_password_reset_key( $user_data );

		// Send email notification.
		//do_action( 'lisner_reset_password_notification', $user_login, $key );
		//todo implement real email system
		$permalink = add_query_arg( [
			'key' => $key,
			'id'  => $user_data->ID
		], get_permalink( lisfinity_get_page_id( 'page-reset' ) ) );
		$link      = '<a class="link" href="' . esc_url( $permalink ) . '">' . __( 'Click here to reset your password',
				'lisfinity-core' ) . '</a>';
		$to        = $user_data->user_email;
		$subject   = sprintf( esc_html__( 'You requested password reset for site: %s', 'lisfinity-core' ),
			get_option( 'blogname' ) );
		$body      = sprintf( esc_html__( 'Your new password is on link %s', 'lisfinity-core' ), $link );
		$headers   = [ 'Content-Type: text/html; charset=UTF-8' ];

		wp_mail( $to, $subject, $body, $headers );

		return true;
	}

	public function process_reset_password( $data ) {
		$posted_fields = [ 'password_1', 'password_2' ];
		$result        = [];

		foreach ( $posted_fields as $field ) {
			if ( ! isset( $data[ $field ] ) ) {
				return;
			}
			$posted_fields[ $field ] = $data[ $field ];
		}

		$cookie = $this->get_reset_password_cookie( $data['cookie'] );

		if ( ! $cookie ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'The password reset key has expired.', 'lisfinity-core' );

			return $result;
		}
		$posted_fields = array_merge( $posted_fields, $cookie );

		$user = $this->check_password_reset_key( $posted_fields['key'], $posted_fields['login'] );

		if ( $user instanceof WP_User ) {
			if ( empty( $posted_fields['password_1'] ) ) {
				$result['error']                       = true;
				$result['error_message']['password_1'] = __( 'Please enter your password', 'lisfinity-core' );

				return $result;
			}

			if ( $posted_fields['password_1'] !== $posted_fields['password_2'] ) {
				$result['error']                       = true;
				$result['error_message']['password_1'] = __( 'Passwords do not match', 'lisfinity-core' );
				$result['error_message']['password_2'] = __( 'Passwords do not match', 'lisfinity-core' );

				return $result;
			}

			$this->reset_password( $user, $posted_fields['password_1'] );
			do_action( 'lisfinity__user_reset_password', $user );
			if ( ! is_user_logged_in() ) {
				$result['message'] = __( 'Password has been successfully reset. Please proceed to login.',
					'lisfinity-core' );
			} else {
				$result['show_login'] = true;
				$result['message']    = esc_html__( 'Password has been successfully reset',
					'lisfinity-core' );
			}
			$result['success'] = true;

			return $result;
		}
	}

	protected function reset_password( $user, $new_pass ) {
		do_action( 'lisfinity__password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );
		$this->set_reset_password_cookie();

		wp_password_change_notification( $user );
	}

	/**
	 * Remove key and user ID (or user login, as a fallback) from query string, set cookie, and redirect to account page to show the form.
	 */
	public function redirect_reset_password_link() {
		if ( isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) {

			// If available, get $user_login from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) {
				$user_login = $_GET['login'];
			} else {
				$user       = get_user_by( 'id', absint( $_GET['id'] ) );
				$user_login = $user ? $user->user_login : '';
			}

			$value = sprintf( '%s:%s', wp_unslash( $user_login ), wp_unslash( $_GET['key'] ) );
			$this->set_reset_password_cookie( $value );
			wp_safe_redirect( add_query_arg( 'reset', 'true', get_permalink( lisfinity_get_page_id( 'page-reset' ) ) ) );
			exit;
		}
	}

	public function get_reset_password_cookie( $cookies ) {
		$args = [];

		$match = '';
		if ( ! empty( $cookies ) ) {
			foreach ( explode( ';', $cookies ) as $cookie ) {
				$exploded = explode( '=', $cookie );
				if ( $exploded[0] === 'wp-resetpass-' . COOKIEHASH ) {
					$match = urldecode( $exploded[1] );
				}
			}
		}

		if ( isset( $match ) && 0 < strpos( $match, ':' ) ) {
			list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $match ) ); // @codingStandardsIgnoreLine
			$user = check_password_reset_key( $rp_key, $rp_login );

			// Reset key / login is correct, display reset password form with hidden key / login values.
			if ( is_object( $user ) ) {
				$args = array(
					'key'   => $rp_key,
					'login' => $rp_login,
				);
			}

			return $args;
		}

		return false;
	}

	public function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		$rp_path   = isset( $_SERVER['REQUEST_URI'] ) ? current( explode( '?', lisfinity_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; // WPCS: input var ok.

		if ( ! empty( $value ) ) {
			setcookie( $rp_cookie, $value, time() + 5 * MINUTE_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), false );
		} else {
			setcookie( $rp_cookie, null, - 1, $rp_path, COOKIE_DOMAIN, is_ssl(), false );
		}
	}

	public function check_password_reset_key( $key, $login ) {
		// Check for the password reset key.
		// Get user data or an error message in case of invalid or expired key.
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			$message = __( 'This key is invalid or has already been used. Please reset your password again if needed.',
				'lisfinity-core' );

			return $message;
		}

		return $user;
	}

}
