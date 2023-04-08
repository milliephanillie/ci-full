<?php

namespace Lisfinity\REST_API\Other;

use Lisfinity\Abstracts\Route as Route;

class OtherRoute extends Route {
	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'e_verify' => [
			'path'                => '/other/verify',
			'callback'            => 'verify',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
	];

	public function verify( \WP_REST_Request $request_data ) {
	}
}
