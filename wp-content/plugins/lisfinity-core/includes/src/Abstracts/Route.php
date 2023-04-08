<?php


namespace Lisfinity\Abstracts;

use WP_REST_Request;

abstract class Route {

	/**
	 * Version of our routes API
	 * -------------------------
	 *
	 * @var string
	 */
	protected $version = '1';

	/**
	 * Custom endpoint for our routes API
	 * ----------------------------------
	 *
	 * @var string
	 */
	protected $vendor = 'lisfinity';

	/**
	 * List of routes to be registered
	 * -------------------------------
	 *
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Register new routes
	 * -------------------
	 *
	 * @param $routes
	 */
	public function set_routes( $routes ) {
		$this->routes = $routes;
	}

	/**
	 * Get registered routes
	 * ----------------------
	 *
	 * @return array
	 */
	public function get_routes() {
		return apply_filters( 'lisfinity__routes_get_routes', $this->routes );
	}

	/**
	 * Set Routes API version
	 * ----------------------
	 *
	 * @param $version
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * Get Routes API version
	 * ----------------------
	 *
	 * @return string
	 */
	public function get_version() {
		return apply_filters( 'lisfinity__routes_get_version', $this->version );
	}

	/**
	 * Set custom routes endpoint
	 * ------------------------------
	 *
	 * @param $vendor
	 */
	public function set_vendor( $vendor ) {
		$this->vendor = $vendor;
	}

	/**
	 * Get routes endpoint
	 * -------------------
	 *
	 * @return string
	 */
	public function get_vendor() {
		return apply_filters( 'lisfinity__routes_get_routes', $this->vendor );
	}

	/**
	 * Allow access through route permission check
	 * -------------------------------------------
	 *
	 * @return boolean
	 */
	public function allow_access() {
		return apply_filters( 'lisfinity__routes_allow_access', true );
	}

	/**
	 * Allow access to only admins in route permission check
	 * -----------------------------------------------------
	 *
	 * @return bool
	 */
	public function manage_options() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if the user is logged in
	 * ------------------------------
	 *
	 * @return bool
	 */
	public function is_user_logged_in() {
		return current_user_can( 'read' );
	}

	/**
	 * Check if user has capability to access the route
	 * ------------------------------------------------
	 *
	 * @return bool
	 */
	public function has_cap() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Register routes
	 * ---------------
	 */
	public function register_routes() {
		foreach ( $this->routes as $route ) {
			$this->register_route( $route );
		}
	}

	/**
	 * Register a single route
	 * -----------------------
	 *
	 * @param $route
	 */
	public function register_route( $route ) {
		register_rest_route(
			$this->get_vendor() . '/v' . $this->get_version(),
			$route['path'],
			[
				'methods'             => $route['methods'],
				'permission_callback' => [ $this, $route['permission_callback'] ],
				'callback'            => [ $this, $route['callback'] ],
			]
		);
	}
}
