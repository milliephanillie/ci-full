<?php


namespace Lisfinity\REST_API\SearchBuilder;

use Lisfinity\Models\Taxonomies\GroupsAdminModel as GroupsAdmin;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel as TaxonomiesAdmin;
use Lisfinity\REST_API\Taxonomies\TermRoute;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\SearchBuilder\SearchBuilderModel as SearchBuilderModel;

class SearchBuilderRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'search_builder_options'      => [
			'path'                => '/search-builder/options',
			'callback'            => 'get_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'search_builder_fields'       => [
			'rest_path'           => '/search-builder/fields',
			'path'                => '/search-builder/fields/(?<type>\S+)',
			'callback'            => 'get_home_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'search_builder_submit'       => [
			'path'                => '/search-builder/store',
			'callback'            => 'submit_fields',
			'permission_callback' => 'has_cap',
			'methods'             => 'POST',
		],
		'search_builder_group_get'    => [
			'path'                => '/search-builder/group/get',
			'callback'            => 'get_groups',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'search_builder_group_submit' => [
			'path'                => '/search-builder/group/store',
			'callback'            => 'submit_group',
			'permission_callback' => 'has_cap',
			'methods'             => 'POST',
		],
		'search_builder_order_edit'   => [
			'path'                => '/search-builder/group/order',
			'callback'            => 'group_order_edit',
			'permission_callback' => 'has_cap',
			'methods'             => 'POST',
		],
		'search_builder_groups'       => [
			'path'                => '/search-builder/groups',
			'callback'            => 'get_groups_organized',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		// import/export.
		'search_export_fields'        => [
			'path'                => '/search-builder/export',
			'callback'            => 'export_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'search_import_fields'        => [
			'path'                => '/search-builder/import',
			'callback'            => 'import_fields',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
	];

	/**
	 * Edit order of the search builder groups
	 * ---------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function group_order_edit( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$values = get_option( 'lisfinity--search-builder-groups' );
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

		$items      = explode( ',', $data['order'] );
		$old_values = $values[ $data['niche'] ];

		$values[ $data['niche'] ] = [];
		foreach ( $items as $item ) {
			foreach ( $old_values as $slug => $value ) {
				if ( $item === $slug ) {
					$values[ $data['niche'] ][ $slug ] = $value;
				}
			}
		}

		update_option( 'lisfinity--search-builder-groups', $values );

		if ( ! isset( $result['error'] ) ) {
			$result['success'] = true;
			$result['message'] = __( 'Group order has been updated.', 'lisfinity-core' );
		}

		wp_send_json( $result );
	}

	public function organize_fields() {
		$organized = [];
		$groups    = [ 'sidebar', 'detailed' ];
		foreach ( $groups as $group ) {
			$organized[ $group ] = $this->get_groups_organized( $group );
		}

		return $organized;
	}

	/**
	 * Organize search builder groups so that they can
	 * easily be used in a detailed search page
	 * -----------------------------------------------
	 *
	 * @param $type
	 *
	 * @return array
	 */
	public function get_groups_organized( $type ) {
		$builder_model  = new SearchBuilderModel();
		$meta_fields    = $builder_model->meta_fields;
		$builder_groups = get_option( 'lisfinity--search-builder-groups' );
		if ( $type === 'sidebar' ) {
			$builder_fields = get_option( 'lisfinity--search-builder-fields' )['sidebar'];
		} else {
			$builder_fields = get_option( 'lisfinity--search-builder-fields' )['detailed'];
		}

		// try cache.
		$to_hash = json_encode( $builder_fields ) . apply_filters( 'wpml_current_language', '' );
		$hash    = 'lisfinity_' . md5( $to_hash ) . lisfinity_get_transient_version( 'get-search-builder-fields', true );

		if ( false === ( $formatted_groups = get_transient( $hash ) ) ) {

			$all_builder_groups = [];
			$formatted_groups   = [];
			foreach ( $builder_groups as $builder_group => $fields ) {
				$all_builder_groups = array_merge( $all_builder_groups, $fields );
			}

			// Organize the fields display
			if ( ! empty( $builder_fields ) ) {
				foreach ( $builder_fields as $builder_group => $fields ) {
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $groups => $group_fields ) {
							if ( 'fields' === $groups ) {
								foreach ( $group_fields as $group ) {
									if ( isset( $fields['groups'] ) && in_array( $group, array_keys( $fields['groups'] ) ) ) {
										if ( isset( $formatted_groups[ $builder_group ][ $fields['groups'][ $group ] ] ) && isset( $all_builder_groups[ $fields['groups'][ $group ] ] ) ) {
											$formatted_groups[ $builder_group ][ $fields['groups'][ $group ] ]['name'] = $all_builder_groups[ $fields['groups'][ $group ] ];
										}
										if ( in_array( $group, $meta_fields ) ) {
											$formatted_groups[ $builder_group ][ $fields['groups'][ $group ] ]['meta_fields'][] = $group;
										} else {
											$formatted_groups[ $builder_group ][ $fields['groups'][ $group ] ]['taxonomies'][] = $group;
										}
									} else {
										$formatted_groups[ $builder_group ]['any']['name'] = __( 'Any', 'lisfinity-core' );
										if ( in_array( $group, $meta_fields ) ) {
											$formatted_groups[ $builder_group ]['any']['meta_fields'][] = $group;
										} else {
											$formatted_groups[ $builder_group ]['any']['taxonomies'][] = $group;
										}
									}
								}
							}
						}
					}
				}
			}

			// Order the groups properly.
			if ( ! empty( $builder_groups ) ) {
				foreach ( $builder_groups as $group => $values ) {
					$old_fields                 = $formatted_groups[ $group ];
					$formatted_groups[ $group ] = [];
					foreach ( $values as $key => $label ) {
						if ( ! empty( $old_fields[ $key ] ) ) {
							$formatted_groups[ $group ][ $key ] = $old_fields[ $key ];
						}
					}
					if ( ! empty( $old_fields['any'] ) ) {
						$formatted_groups[ $group ]['any'] = $old_fields['any'];
					}

				}
			}

			set_transient( $hash, $formatted_groups, DAY_IN_SECONDS * 30 );
		}

		return $formatted_groups;
	}

	/**
	 * Get available search builder groups
	 * -----------------------------------
	 *
	 * @return mixed|void
	 */
	public function get_groups() {
		$groups = get_option( 'lisfinity--search-builder-groups' );

		return $groups;
	}


	/**
	 * Submit search builder group handler
	 * -----------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function submit_group( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];
		$values = get_option( 'lisfinity--search-builder-groups' );
		$values = ! empty( $values ) ? $values : [];

		if ( isset( $data['delete'] ) ) {
			foreach ( $values[ $data['niche'] ] as $key => $label ) {
				if ( $key === $data['old_group'] ) {
					unset( $values[ $data['niche'] ][ $key ] );
				}
			}

			update_option( 'lisfinity--search-builder-groups', $values );

			$result['success'] = true;
			$result['message'] = __( 'Group has been successfully deleted.', 'lisfinity-core' );

			wp_send_json( $result );
		}

		if ( empty( $data['new_group'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group name cannot be empty.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		if ( empty( $data['niche'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Niche has not been set.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		if ( isset( $data['edit'] ) ) {
			foreach ( $values[ $data['niche'] ] as $key => $label ) {
				if ( $key === $data['old_group'] ) {
					unset( $values[ $data['niche'] ][ $key ] );
				}
			}
		}

		if ( isset( $values[ $data['niche'] ][ $data['new_group'] ] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group already exists.', 'lisfinity-core' );
			wp_send_json( $result );
		}

		$values[ $data['niche'] ][ sanitize_title( $data['new_group'] ) ] = $data['new_group'];


		update_option( 'lisfinity--search-builder-groups', $values );

		$result['success'] = true;
		$result['message'] = __( 'A new group has been successfully added.', 'lisfinity-core' );

		wp_send_json( $result );
	}

	/**
	 * Handle submission of the search builder fields
	 * ----------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function submit_fields( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		// Error | Data cannot be empty.
		if ( empty( $data ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Data cannot be empty', 'lisfinity-core' );
			wp_send_json( $result );
		}

		$values = get_option( 'lisfinity--search-builder-fields' );
		$values = ! empty( $values ) ? $values : [];

		//return $data;
		// if we're choosing home search fields
		if ( 'home' === $data['type'] ) {
			$values[ $data['type'] ] = [];
			foreach ( $data as $key => $field ) {
				if ( $data['type'] !== $key ) {
					if ( 'maxNumbers' === $key ) {
						$values[ $data['type'] ][ $key ] = $field;
					} else if ( 'minNumbers' === $key ) {
						$values[ $data['type'] ][ $key ] = $field;
					} else if ( 'steps' === $key ) {
						$values[ $data['type'] ][ $key ] = $field;
					} else if ( 'label' === $key ) {
						$values[ $data['type'] ][ $key ] = $field;
					} else if ( 'placeholder' === $key ) {
						$values[ $data['type'] ][ $key ] = $field;
					} else if ( 'keywordOptions' === $key ) {
						if ( is_bool( $field ) ) {
							$values[ $data['type'] ][ $key ] = $field;
						} else {
							$values[ $data['type'] ][ $key ] = $field;
						}
					} else {
						$values[ $data['type'] ]['fields'][] = $key;
					}
				}
			}
		}

		if ( in_array( $data['type'], [ 'sidebar', 'detailed' ] ) ) {
			$values[ $data['type'] ] = [];
			foreach ( $data as $key => $fields ) {
				if ( $data['type'] != $key ) {
					if ( ! empty( $fields ) && is_array( $fields ) ) {
						foreach ( $fields as $group => $field ) {
							$values[ $data['type'] ][ $key ][ $group ] = $field;
						}
					}
				}
			}
		}

		update_option( 'lisfinity--search-builder-fields', $values );

		$result['success'] = true;
		$result['message'] = __( 'Search fields have been updated.', 'lisfinity-core' );

		wp_send_json( $result );
	}

	/**
	 * Get available search builder options
	 * ------------------------------------
	 *
	 * @return array
	 */
	public function get_options() {
		$search_builder_model = new SearchBuilderModel();

		return $search_builder_model->get_options();
	}

	/**
	 * Get available homepage fields for the requested type
	 * ----------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_home_fields( WP_REST_Request $request_data ) {
		$data                 = $request_data->get_params();
		$search_builder_model = new SearchBuilderModel();

		$fields = $search_builder_model->get_fields();
		if ( ! isset( $fields[ $data['type'] ] ) ) {
			return [];
		}

		// uncomment to test returning all fields
		//return $search_builder_model->get_fields();

		$taxonomies_admin     = new TaxonomiesAdmin();
		$result['taxonomies'] = $taxonomies_admin->get_options();
		$result['fields']     = $fields[ $data['type'] ];

		$term_route      = new TermRoute();
		$result['terms'] = $term_route->get_terms_for_search();

		$group_admin      = new GroupsAdmin();
		$result['groups'] = $group_admin->get_options();

		if ( $data['type'] !== 'home' ) {
			$result['fieldGroups'] = $this->organize_fields();
		} else {
			//$result['organized'] = $this->organize_home_fields( $result['terms'] );
		}

		return $result;
	}

	public function organize_home_fields( $terms ) {
		$organized = [];
		foreach ( $terms as $term_fields ) {
			foreach ( $term_fields['fields'] as $terms ) {
				$organized[ $terms->term_taxonomy_id ]['group']                     = $term_fields['options']['field_group'];
				$organized[ $terms->term_taxonomy_id ]['options']                   = $term_fields['options'];
				$organized[ $terms->term_taxonomy_id ]['type']                      = $term_fields['options']['slug'] === $terms->taxonomy ? $term_fields['options']['type'] : '';
				$organized[ $terms->term_taxonomy_id ]['fields'][ $terms->term_id ] = $terms;
				$organized[ $terms->term_taxonomy_id ]['parent']                    = $term_fields['options']['parent'];
			}
		}

		return $organized;
	}

	public function export_fields() {
		$model = new SearchBuilderModel();

		$fields['groups'] = get_option( 'lisfinity--search-builder-groups' );
		$fields['fields'] = $model->get_fields();
		$fields['single'] = get_option( 'lisfinity--single-fields' );

		return json_encode( $fields );
	}

	public function import_fields( WP_REST_Request $request_data, $fields = '' ) {
		$model = new SearchBuilderModel();

		if ( ! empty( $fields ) ) {
			$fields = json_decode( $fields, true );
		} else {
			$data   = $request_data->get_params();
			$fields = json_decode( $data['fields'], true );
		}

		update_option( 'lisfinity--search-builder-groups', $fields['groups'] );
		$model->import_fields( $fields['fields'] );

		return $fields;
	}

	public function import_fields_demo( $fields = '' ) {
		$model = new SearchBuilderModel();

		if ( ! empty( $fields ) ) {
			$fields = json_decode( $fields, true );
		}

		update_option( 'lisfinity--search-builder-groups', $fields['groups'] );
		update_option( 'lisfinity--single-fields', $fields['single'] );
		$model->import_fields( $fields['fields'] );

		return $fields;
	}

}
