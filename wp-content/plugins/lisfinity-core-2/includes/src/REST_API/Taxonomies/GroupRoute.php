<?php


namespace Lisfinity\REST_API\Taxonomies;

use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Taxonomies\GroupsAdminModel as GroupsAdmin;

class GroupRoute extends Route {

	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'group_fields'     => [
			'path'                => '/group-fields',
			'callback'            => 'get_group_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'groups'           => [
			'path'                => '/groups',
			'callback'            => 'get_groups',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'groups_by_term'   => [
			'path'                => '/groups-term/(?P<term>\S+)',
			'callback'            => 'get_groups_by_term',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'group_store'      => [
			'path'                => '/group/store',
			'callback'            => 'store_group',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'group_edit'       => [
			'path'                => '/group/edit',
			'callback'            => 'edit_group',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'group_edit_order' => [
			'path'                => '/group/edit-order',
			'callback'            => 'edit_group_order',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'group_delete'     => [
			'path'                => '/group/delete',
			'callback'            => 'delete_group',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		// exporting.
		'export_fields'    => [
			'path'                => '/fields/export',
			'callback'            => 'export_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'import_fields'    => [
			'path'                => '/fields/import',
			'callback'            => 'import_fields',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'import_terms'     => [
			'path'                => '/fields/import/terms',
			'callback'            => 'import_terms_rest',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
	];

	/**
	 * Get taxonomy options fields from the list
	 * of available ones
	 * -----------------------------------------
	 *
	 * @return mixed
	 */
	public function get_group_fields() {
		$group_admin = new GroupsAdmin();

		return $group_admin->get_fields();
	}

	public function get_groups() {
		$group_admin = new GroupsAdmin();

		return $group_admin->get_options();
	}

	public function get_groups_by_term( WP_REST_Request $request_data ) {
		return $this->get_groups();
	}

	/**
	 * Update taxonomy options
	 * -----------------------
	 *
	 * @param $options
	 */
	public function update_options( $options ) {
		$groups_admin = new GroupsAdmin();

		update_option( 'lisfinity_groups', $options );
	}

	/**
	 * Update the options in a single group
	 * ------------------------------------
	 *
	 * @param $data
	 * @param $options
	 *
	 * @return mixed
	 */
	protected function update_group( $data, $options ) {
		$result['message'] = __( 'The group couldn\'t be updated.', 'lisfinity-core' );

		if ( empty( $options[ $data['element-to-update'] ] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Something is not correct, please refresh then try again.', 'lisfinity-core' );
		}

		if ( empty( $data['slug'] && ! empty( $data['single_name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['single_name'] );
		}

		if ( empty( $data['single_name'] ) || empty( $data['plural_name'] ) || empty( $data['slug'] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Singular, Plural names and Slug are required', 'lisfinity-core' );
		} else {

			$group_admin = new GroupsAdmin();
			if ( $options[ $data['element-to-update'] ]['slug'] !== $data['slug'] && in_array( $data['slug'], $group_admin->get_groups_slugs() ) ) {
				$result['error']   = true;
				$result['message'] = esc_html__(
					'Slug name is already in use. Please choose another slug name.',
					'lisfinity-core'
				);
			}
		}

		// throw error if there's any.
		if ( isset( $result['error'] ) ) {
			return $result;
		}

		// update the option.
		foreach ( $data as $key => $value ) {
			if ( ! in_array( $key, [ 'element-to-update', 'element-old-slug', 'action' ] ) ) {
				$options[ $data['element-to-update'] ][ $key ] = $value;
			}
		}

		// update the group taxonomies if the slug has been changes.
		if ( $data['element-old-slug'] !== $data['slug'] ) {
			$taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
			$groups         = $taxonomy_model->get_options();
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group_slug => $taxonomies ) {
					if ( $group_slug === $data['element-old-slug'] && ! empty( $taxonomies ) ) {
						foreach ( $taxonomies as $key => $taxonomy ) {
							$groups[ $group_slug ][ $key ]['field_group'] = $data['slug'];
						}
					}
				}
				$new_groups = lisfinity_change_key( $groups, $data['element-old-slug'], $data['slug'] );

				$taxonomy_model->set_options( $new_groups );
			}
		}

		$this->update_options( $options );

		$result['success'] = true;
		$result['message'] = __( 'The group has been updated successfully.', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Edit a single group
	 * -------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @see $this->update_cf_order
	 *
	 * @see $this->update_cf_option
	 */
	public function edit_group( WP_REST_Request $request_data ) {
		$result      = [];
		$group_admin = new GroupsAdmin();
		$data        = $request_data->get_params();
		$options     = $group_admin->get_options();

		// throw error if there's any.
		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result );
		}

		$result = $this->update_group( $data, $options );
		do_action( 'lisfinity__group_updated', $data );
		wp_send_json_success( $result );

	}

	/**
	 * Edit the order of the categories
	 * --------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function edit_group_order( WP_REST_Request $request_data ) {
		$result      = [];
		$group_admin = new GroupsAdmin();
		$data        = $request_data->get_params();

		// throw error if there's any.
		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result );
		}

		$group_admin->set_options( $data );
		do_action( 'lisfinity__group_order_updated' );
		wp_send_json_success( $result );
	}

	/**
	 * Store group to the database
	 * ---------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function store_group( WP_REST_Request $request_data ) {
		$result       = [];
		$groups_admin = new GroupsAdmin();
		$data         = $request_data->get_params();
		$options      = $groups_admin->get_options();

		if ( empty( $data['slug'] && ! empty( $data['single_name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['single_name'] );
		}

		if ( empty( $data['single_name'] ) || empty( $data['plural_name'] ) || empty( $data['slug'] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Singular and Plural Name fields are required.', 'lisfinity-core' );
		} else {
			$data['slug'] = lisfinity_create_slug( $data['single_name'] );

			// check whether we're already using the slug.
			if ( ! empty( $options ) ) {
				foreach ( $options as $option ) {
					if ( $option['slug'] === $data['slug'] ) {
						$result['error']   = true;
						$result['message'] = esc_html__(
							'Slug name is already in use. Please choose another slug name.',
							'lisfinity-core'
						);
						break;
					}
				}
			}
		}

		// throw an error if there is one.
		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result );
		}

		$current_option = [];
		$fields         = $groups_admin->get_fields();

		foreach ( $fields as $group ) {
			foreach ( $group as $field_key => $value ) {
				if ( ! empty( $data[ $field_key ] ) ) {
					$current_option[ $field_key ] = 'slug' !== $field_key ? sanitize_text_field( $data[ $field_key ] ) : $data[ $field_key ];
				}
			}
		}

		// make sure that the options are accepting arrays.
		if ( empty( $options ) ) {
			$options = [];
		}

		$options[] = $current_option;

		$result['success'] = true;
		$result['message'] = esc_html__( 'New group has been successfully added.', 'lisfinity-core' );

		$this->update_options( $options );

		do_action( 'lisfinity__group_updated', $data );
		wp_send_json_success( $result );
	}

	/**
	 * Deleting group from the database.
	 * ---------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function delete_group( WP_REST_Request $request_data ) {
		$result      = [];
		$data        = $request_data->get_params();
		$group_admin = new GroupsAdmin();
		$options     = $group_admin->get_options();

		$option_key = intval( $data['order'] );

		if ( empty( $options[ $option_key ] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Group couldn\'t be deleted because it cannot be found.', 'lisfinity-core' );

			wp_send_json_error( $result );
		}

		// delete related taxonomies
		$group      = $options[ $option_key ];
		$tax_model  = new TaxonomiesAdminModel();
		$taxonomies = $tax_model->get_options();
		if ( ! empty( $taxonomies[ $group['slug'] ] ) ) {
			foreach ( $taxonomies[ $group['slug'] ] as $taxonomy ) {
				$terms = get_terms( [
					'taxonomy'   => $taxonomy['slug'],
					'hide_empty' => false,
				] );
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						wp_delete_term( $term->term_id, $taxonomy['slug'] );
					}
				}
			}
			unset( $taxonomies[ $group['slug'] ] );
			$tax_model->set_options( $taxonomies );
		}

		// delete the group.
		array_splice( $options, $option_key, 1 );

		$this->update_options( $options );

		do_action( 'lisfinity__group_deleted', $data );
		$result['success'] = true;
		$result['message'] = __( 'Group has been successfully deleted.', 'lisfinity-core' );
		wp_send_json_success( $result );
	}

	protected function get_export_terms( $groups ) {
		if ( empty( $groups ) ) {
			return [];
		}

		$terms = [];
		foreach ( $groups as $group ) {
			foreach ( $group as $taxonomy ) {
				$terms[ $taxonomy['slug'] ] = get_terms( [ 'taxonomy' => $taxonomy['slug'], 'hide_empty' => false ] );
				if ( ! empty( $terms[ $taxonomy['slug'] ] ) ) {
					foreach ( $terms[ $taxonomy['slug'] ] as $term ) {
						$meta       = get_term_meta( $term->term_id );
						$term->meta = $meta;
					}
				}
			}
		}

		return $terms;
	}

	public function export_fields() {
		$group_model          = new GroupsAdminModel();
		$tax_model            = new TaxonomiesAdminModel();
		$fields['groups']     = $group_model->get_options();
		$fields['taxonomies'] = $tax_model->get_options();
		$fields['terms']      = $this->get_export_terms( $fields['taxonomies'] );

		return json_encode( $fields );
	}

	public function import_terms( $fields ) {
		global $wpdb;
		if ( empty( $fields ) ) {
			return false;
		}

		$childs = [];
		foreach ( $fields as $taxonomy => $terms ) {
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( $term['parent'] > 0 ) {
						$childs[] = $term;
					} else {
						if ( term_exists( $term['slug'], $taxonomy ) ) {
							$term_id = get_term_by( 'slug', $term['slug'], $taxonomy );
						} else {
							$term_id = wp_insert_term( $term['name'], $taxonomy, [
								'slug' => $term['slug'],
							] );
						}
						if ( ! is_wp_error( $term_id ) ) {
							if ( ! empty( $term['meta'] ) ) {
								foreach ( $term['meta'] as $key => $value ) {
									update_term_meta( is_array( $term_id ) ? $term_id['term_id'] : $term_id->term_id, $key, $value[0] );
								}
							}
						}
					}
				}
			}
		}

		if ( ! empty( $childs ) ) {
			foreach ( $childs as $child ) {
				if ( ! empty( $child['meta']['parent_taxonomy'][0] ) ) {
					$parent = get_term_by( 'slug', $child['meta']['parent_slug'][0], $child['meta']['parent_taxonomy'][0] );
					if ( false !== $parent && ! empty( $parent ) && ! is_wp_error( $parent ) ) {
						if ( term_exists( $child['slug'], $child['taxonomy'] ) ) {
							$term    = get_term_by( 'slug', $child['slug'], $child['taxonomy'] );
							$term_id = $term->term_id;
						} else {
							if ( taxonomy_exists( $child['taxonomy'] ) ) {
								$term    = wp_insert_term( $child['name'], $child['taxonomy'], [
									'slug' => $child['slug'],
								] );
								$term_id = $term['term_id'];
							}
						}
						if ( false !== $term_id && ! is_wp_error( $term_id ) && ! empty( $child['taxonomy'] ) ) {
							$wpdb->update( $wpdb->term_taxonomy, [ 'parent' => $parent->term_id ], [ 'term_id' => $term_id ] );
							if ( ! empty( $child['meta'] ) ) {
								foreach ( $child['meta'] as $key => $value ) {
									if ( ! empty( $value ) ) {
										update_term_meta( $term_id, $key, $value[0] );
									}
								}
							}
						}
					}
				}
			}
		}

		return true;
	}

	public function import_fields( WP_REST_Request $request_data, $fields = '' ) {
		$data        = $request_data->get_params();
		$group_model = new GroupsAdminModel();
		$tax_model   = new TaxonomiesAdminModel();

		if ( ! empty( $fields ) ) {
			$fields = json_decode( $fields, true );
		} else {
			$data   = $request_data->get_params();
			$fields = json_decode( $data['fields'], true );
		}

		$group_model->set_options( $fields['groups'] );
		$tax_model->set_options( $fields['taxonomies'] );

		return [ 'import_terms' => true, 'terms' => json_encode( $fields['terms'], true ) ];
	}

	public function import_fields_demo( $fields = '' ) {
		$group_model = new GroupsAdminModel();
		$tax_model   = new TaxonomiesAdminModel();

		if ( empty( $fields ) ) {
			return [ 'error' => true, 'message' => __( 'No fields to import', 'lisfinity-core' ) ];
		}
		$fields = json_decode( $fields, true );

		$group_model->set_options( $fields['groups'] );
		$tax_model->set_options( $fields['taxonomies'] );

		return true;
	}

	public function import_terms_rest( WP_REST_Request $request_data, $fields = '' ) {
		if ( ! empty( $fields ) ) {
			$fields = json_decode( $fields, true );
		} else {
			$data   = $request_data->get_params();
			$fields = json_decode( $data['fields'], true );
		}

		return $this->import_terms( $fields );
	}
}
