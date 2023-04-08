<?php


namespace Lisfinity\REST_API\SingleBuilder;

use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class SingleBuilderRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'single_builder_options'      => [
			'path'                => '/single-builder/get',
			'callback'            => 'get_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'single_builder_add_group'    => [
			'path'                => '/single-builder/group/store',
			'callback'            => 'add_group',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'single_builder_delete_group' => [
			'path'                => '/single-builder/group/delete',
			'callback'            => 'delete_group',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'single_builder_change_group' => [
			'path'                => '/single-builder/group/change',
			'callback'            => 'change_group',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'single_builder_order_group'  => [
			'path'                => '/single-builder/group/order',
			'callback'            => 'change_order',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function get_options( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		return get_option( 'lisfinity--single-fields' );
	}

	public function add_group( WP_REST_Request $request_data ) {
		$result = [];
		$data   = $request_data->get_params();

		if ( empty( $data['new_group'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group has not been provided', 'lisfinity-core' );
		}

		if ( empty( $data['niche'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Niche has not been defined', 'lisfinity-core' );
		}

		$options = get_option( 'lisfinity--single-fields' );

		if ( ! empty( $data['old_group'] ) ) {
			$slug = sanitize_title( $data['old_group'] );
			foreach ( $options[ $data['niche'] ]['groups'] as $index => $group ) {
				if ( $group['slug'] === $slug ) {
					$options[ $data['niche'] ]['groups'][ $index ] = [
						'slug' => sanitize_title( $data['new_group'] ),
						'name' => $data['new_group']
					];
					break;
				}
			}
		} else {
			$options[ $data['niche'] ]['groups'][] = [
				'slug' => sanitize_title( $data['new_group'] ),
				'name' => $data['new_group']
			];
		}

		update_option( 'lisfinity--single-fields', $options );

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'New group has been added', 'lisfinity-core' );
		}

		return $result;
	}

	public function delete_group( WP_REST_Request $request_data ) {
		$result = [];
		$data   = $request_data->get_params();

		if ( empty( $data['niche'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Niche has not been defined', 'lisfinity-core' );
		}

		$options = get_option( 'lisfinity--single-fields' );

		if ( ! empty( $data['order'] ) || 0 === $data['order'] ) {
			unset( $options[ $data['niche'] ]['groups'][ $data['order'] ] );
			sort( $options[ $data['niche'] ]['groups'] );
		} else {
			$options[ $data['niche'] ]['groups'] = [];
		}

		update_option( 'lisfinity--single-fields', $options );

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'The group has been deleted', 'lisfinity-core' );
		}

		return $result;
	}

	public function change_group( WP_REST_Request $request_data ) {
		$result = [];
		$data   = $request_data->get_params();

		if ( empty( $data['taxonomy'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Taxonomy has not been provided', 'lisfinity-core' );
		}

		if ( empty( $data['niche'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Niche has not been defined', 'lisfinity-core' );
		}

		$options = get_option( 'lisfinity--single-fields' );

		$options[ $data['niche'] ]['fields'][ $data['taxonomy'] ] = $data['group'];

		update_option( 'lisfinity--single-fields', $options );

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'The field group has been updated', 'lisfinity-core' );
		}

		return $result;
	}

	public function change_order( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];


		if ( empty( $data['niche'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group niche has not been defined.', 'lisfinity-core' );

			wp_send_json( $result );
		}

		if ( empty( $data['order'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group order has not been defined', 'lisfinity-core' );

			wp_send_json( $result );
		}

		$values = get_option( 'lisfinity--single-fields' );

		$items      = explode( ',', $data['order'] );
		$old_values = $values[ $data['niche'] ]['groups'];

		$values[ $data['niche'] ]['groups'] = [];
		foreach ( $items as $item ) {
			$values[ $data['niche'] ]['groups'][] = $old_values[ $item ];
		}

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'The field group has been updated', 'lisfinity-core' );
		}

		update_option( 'lisfinity--single-fields', $values );

		return $result;
	}

}
