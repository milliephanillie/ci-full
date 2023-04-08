<?php

namespace Lisfinity\Models\Auth\Login;

use Exception;
use WP_Error;

class LoginModel {

	public function process_login( $data ) {
		$result = [];

		$creds = [
			'user_login'    => trim( $data['username'] ),
			'user_password' => $data['password'],
			'remember'      => isset( $data['rememberme'] ),
		];


		if ( empty( $creds['user_login'] ) ) {
			$result['error']                     = true;
			$result['error_message']['username'] = __( 'Username is required', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		// On multisite, ensure user exists on current site, if not add them before allowing login.
		if ( is_multisite() ) {
			$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login',
				$creds['user_login'] );

			if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
				add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
			}
		}

		// Perform the login
		$user = wp_signon( apply_filters( 'lisfinity__login_credentials', $creds ), is_ssl() );


		if ( is_wp_error( $user ) ) {
			$code = $user->get_error_code();
			if ( 'invalid_username' == $code ) {
				$result['error']                     = true;
				$result['error_message']['username'] = sprintf( __( '%s username is invalid', 'lisfinity-core' ), $creds['user_login'] );
			} elseif ( 'invalid_password' ) {
				$result['error']                     = true;
				$result['error_message']['password'] = __( 'Password is invalid', 'lisfinity-core' );
			}

			return $result;
		}
		// check if verification is need and user is verified.
		$verified = carbon_get_user_meta( $user->ID, 'verified' );
		if ( '1' === lisfinity_get_option( 'auth-verification' ) && ! $verified ) {
			$result['error']                   = true;
			$result['error_message']['global'] = __( 'Your account has still not been verified.', 'lisfinity-core' );
			wp_logout();

			return $result;
		}

		// everything's ok.
		if ( isset( $data['redirect'] ) ) {
			$result['redirect'] = wp_sanitize_redirect( $data['redirect'] );
		} else {
			$account_page_id    = lisfinity_get_page_id( 'page-account' );
			$result['redirect'] = get_permalink( $account_page_id );
		}
		$result['success'] = true;
		$result['message'] = __( 'You have been successfully logged in! Please wait until redirection is completed.', 'lisfinity-core' );

		return $result;
	}

}
