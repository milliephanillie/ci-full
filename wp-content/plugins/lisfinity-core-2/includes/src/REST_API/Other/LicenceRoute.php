<?php


namespace Lisfinity\REST_API\Other;

use Lisfinity\Abstracts\Route as Route;

class LicenceRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'e_store' => [
			'path'                => '/other/store-licence',
			'callback'            => 'store',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function store( \WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		if ( ! empty( $data['e'] ) ) {
			update_option( 'lisfinity_licence', $data['e'] );
		}

		wp_send_json_success( [ 'message' => esc_html__( 'Successfully verified the purchase code. Please wait while page is being reloaded...', 'lisfinity-core' ) ] );


		return $data;
	}

}
