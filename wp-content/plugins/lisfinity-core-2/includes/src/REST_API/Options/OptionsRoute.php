<?php


namespace Lisfinity\REST_API\Options;

use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class OptionsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'options'        => [
			'path'                => '/options/',
			'callback'            => 'get_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'option'         => [
			'path'                => '/option/(?P<option>\S+)',
			'rest_path'           => '/option',
			'callback'            => 'get_option',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'options_export' => [
			'path'                => '/options/export',
			'callback'            => 'export_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'options_import' => [
			'path'                => '/options/import',
			'callback'            => 'import_options',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
	];

	/**
	 * Get all theme options
	 * ---------------------
	 *
	 * @return bool|mixed|void
	 */
	public function get_options() {
		return $this->load_all_options();
	}

	/**
	 * Get a single option from the database
	 * -------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return mixed
	 */
	public function get_option( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		$option = lisfinity_get_option( $data['option'] );

		return $option;
	}

	/**
	 * Load all theme options
	 * ----------------------
	 *
	 * @return bool|mixed|void
	 */
	public function load_all_options() {
		global $wpdb;
		if ( wp_installing() || is_multisite() ) {
			return false;
		}

		// load from cache if possible.
		$all_options = wp_cache_get( 'lisfinity_all_options', 'options' );

		if ( ! $all_options ) {
			$all_options_db = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '\_%' AND option_name NOT LIKE '%transient%'" );
			$all_options    = [];
			foreach ( (array) $all_options_db as $option ) {
				$all_options[ $option->option_name ] = $option->option_value;
			}
			$all_options = apply_filters( 'lisfinity__pre_cache_all_options', $all_options );
			wp_cache_add( 'lisfinity_all_options', $all_options, 'options' );
		}

		return apply_filters( 'lisfinity__load_all_options', $all_options );
	}

	public function export_options() {
		$options = json_encode( $this->get_options() );

		return $options;
	}

	public function import_options( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		if ( empty( $data['fields'] ) ) {
			return false;
		}

		$fields = json_decode( $data['fields'], true );

		foreach ( $fields as $key => $value ) {
			update_option( $key, $value, false );
		}

		return $data;
	}

	public function import_options_demo( $fields ) {
		if ( empty( $fields ) ) {
			return false;
		}

		$fields = json_decode( $fields, true );

		foreach ( $fields as $key => $value ) {
			update_option( $key, $value, false );
		}

		return true;
	}

}

