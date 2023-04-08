<?php


namespace Lisfinity\REST_API\Authentication;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Auth\Login\LoginModel;
use Lisfinity\Models\Auth\PasswordReset\PasswordResetModel;
use Lisfinity\Models\Auth\Register\RegisterModel;
use Lisfinity\Models\Auth\Twilio\TwilioModel;
use WP_REST_Request;

class AuthenticationRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'auth_options' => [
			'path'                => '/auth/options',
			'callback'            => 'get_auth_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'register'     => [
			'path'                => '/register',
			'callback'            => 'register_user',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'sms_verify'   => [
			'path'                => '/register/sms',
			'callback'            => 'verify_sms',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'sms_resend'   => [
			'path'                => '/register/sms/resend',
			'callback'            => 'resend_sms',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'login'        => [
			'path'                => '/auth/login',
			'callback'            => 'login_user',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'login_demo'   => [
			'path'                => '/auth/login/demo',
			'callback'            => 'login_user_demo',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'forgot'       => [
			'path'                => '/auth/forgot',
			'callback'            => 'forgot_password',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'reset'        => [
			'path'                => '/auth/reset',
			'callback'            => 'reset_password',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
	];

	/**
	 * Format an array of necessary options
	 * ------------------------------------
	 *
	 * @param $page
	 *
	 * @return array
	 */
	protected function get_options( $page ) {
		$options         = [];
		$options['page'] = 'login';
		if ( lisfinity_is_page_template( 'page-register', (int) $page ) ) {
			$options['page'] = 'register';
		}
		if ( lisfinity_is_page_template( 'page-reset', (int) $page ) ) {
			$options['page'] = 'reset';
		}
		$page_id = lisfinity_get_page_id( "page-{$options['page']}" );

		// required theme options.
		$options['vendor-approval']              = lisfinity_is_enabled( lisfinity_get_option( 'site-vendor-approval' ) );
		$options['auth-enabled']                 = '1' === lisfinity_get_option( 'auth-enabled' ) ? 1 : 0;
		$options['auth-captcha-enabled']         = '1' === lisfinity_get_option( 'auth-captcha-enabled' ) ? 1 : 0;
		$options['auth-captcha-site-key']        = lisfinity_get_option( 'auth-captcha-site-key' );
		$options['auth-captcha-label']           = lisfinity_get_option( 'auth-captcha-label' );
		$options['auth-verification']            = '1' === lisfinity_get_option( 'auth-verification' ) ? 1 : 0;
		$options['auth-verification-sms']        = '1' === lisfinity_get_option( 'auth-verification-sms' ) ? 1 : 0;
		$options['phone-number-register-form']   = lisfinity_is_enabled( lisfinity_get_option( 'phone-number-register-form' ) );
		$options['website-register-form']        = lisfinity_is_enabled( lisfinity_get_option( 'website-register-form' ) );
		$options['first-name-register-form']     = lisfinity_is_enabled( lisfinity_get_option( 'first-name-register-form' ) );
		$options['last-name-register-form']      = lisfinity_is_enabled( lisfinity_get_option( 'last-name-register-form' ) );
		$options['choose-profile-register-form'] = lisfinity_is_enabled( lisfinity_get_option( 'choose-profile-register-form' ) );

		// required page options.
		$options['page-auth-promo']           = carbon_get_post_meta( $page_id, 'page-auth-promo' );
		$options['page-auth-promo-product']   = carbon_get_post_meta( $page_id, 'page-auth-promo-product' );
		$options['page-auth-bg']              = wp_get_attachment_image_url( carbon_get_post_meta( $page_id, 'page-auth-bg' ), 'full' );
		$options['page-auth-overlay']         = carbon_get_post_meta( $page_id, 'page-auth-overlay' );
		$options['page-auth-overlay-opacity'] = carbon_get_post_meta( $page_id, 'page-auth-overlay-opacity' );

		// default page title and content.
		$options['title']            = get_the_title( $page_id );
		$options['page-terms']       = lisfinity_get_option( 'page-terms' );
		$options['page-terms-label'] = lisfinity_format_terms_and_policy_label();
		$options['site_url']         = esc_url( get_home_url( '/' ) );

		// social login
		$social_login = '';
		if ( class_exists( 'NextendSocialLogin' ) ) {
			$social_login = \NextendSocialLogin::renderButtonsWithContainer( 'default', \NextendSocialLogin::$providers );
		}
		$options['social-login'] = $social_login;

		return $options;
	}

	/**
	 * Get necessary auth page options
	 * -------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_auth_options( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		return $this->get_options( (int) $data['page'] );
	}

	/**
	 * Register user handler
	 * ---------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function register_user( WP_REST_Request $request_data ) {
		$data           = $request_data->get_params();
		$register_model = new RegisterModel();

		return $register_model->process_registration( $data );
	}

	public function resend_sms( WP_REST_Request $request_data ) {
		$data           = $request_data->get_params();
		$register_model = new RegisterModel();

		return $register_model->resend_sms_code( $data['user_id'] );
	}

	/**
	 * Verify SMS handler
	 * ------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function verify_sms( WP_REST_Request $request_data ) {
		$data           = $request_data->get_params();
		$register_model = new TwilioModel();

		return $register_model->process_sms_code( $data );
	}

	/**
	 * Login user handler
	 * ------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function login_user( WP_REST_Request $request_data ) {
		$data        = $request_data->get_params();
		$login_model = new LoginModel();

		return $login_model->process_login( $data );
	}

	public function login_user_demo( WP_REST_Request $request_data ) {
		$data        = $request_data->get_params();
		$login_model = new LoginModel();

		return $login_model->process_login( $data );
	}

	/**
	 * Forgot password handler
	 * -----------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|bool
	 */
	public function forgot_password( WP_REST_Request $request_data ) {
		$data           = $request_data->get_params();
		$password_model = new PasswordResetModel();

		return $password_model->process_lost_password( $data );
	}

	/**
	 * Reset password handler
	 * ----------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|void
	 */
	public function reset_password( WP_REST_Request $request_data ) {
		$headers        = $request_data->get_headers();
		$data           = $request_data->get_params();
		$password_model = new PasswordResetModel();
		$cookie         = isset( $headers['cookie'][ 'wp-resetpass-' . COOKIEHASH ] );
		if ( $cookie ) {
			$data['cookie'] = $cookie;
		}

		return $password_model->process_reset_password( $data );
	}

}
