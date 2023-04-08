<?php


namespace Lisfinity\REST_API\Taxonomies;

use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Models\Taxonomies\TermsAdminModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel as TaxonomiesAdmin;

class TaxonomyRoute extends Route {

	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'taxonomy_fields'           => [
			'path'                => '/taxonomy-fields',
			'callback'            => 'get_cf_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'taxonomy_options'          => [
			'path'                => '/taxonomy-options-get',
			'callback'            => 'get_cf_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'taxonomy_location_options' => [
			'path'                => '/taxonomy-options/locations',
			'callback'            => 'get_location_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'taxonomy_group_options'    => [
			'path'                => '/taxonomy-options-by-group/',
			'callback'            => 'get_group_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'taxonomy_options_store'    => [
			'path'                => '/taxonomy-options/store',
			'callback'            => 'store_cf_option',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'taxonomy_options_edit'     => [
			'path'                => '/taxonomy-options/edit',
			'callback'            => 'edit_cf_option',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'taxonomy_options_delete'   => [
			'path'                => '/taxonomy-options/delete',
			'callback'            => 'delete_cf_option',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		// todo move this to custom route class
		'attachment_data'           => [
			'path'                => '/attachment',
			'callback'            => 'get_attachment_data',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'get_cf_versions'           => [
			'path'                => '/cf/versions',
			'callback'            => 'get_cf_versions',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'reset_cf_version'          => [
			'path'                => '/cf/reset-version',
			'callback'            => 'reset_cf_version',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'save_cf_version'           => [
			'path'                => '/cf/save-version',
			'callback'            => 'save_cf_version',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'taxonomy_import'           => [
			'path'                => '/taxonomy/import',
			'callback'            => 'taxonomy_import',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'taxonomy_refresh'          => [
			'path'                => '/taxonomy/refresh',
			'callback'            => 'taxonomy_refresh',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function save_cf_version( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		if ( empty( $data['id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Version ID cannot be empty', 'lisfinity-core' );
		}
		if ( empty( $data['name'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Version name cannot be empty', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		update_option( 'lisfinity_custom_fields_template', $data['id'] );

		$result['success'] = true;
		$result['message'] = sprintf( __( 'Version %s has been successfully saved as template', 'lisfinity-core' ), $data['id'] );

		return $result;
	}

	protected function reset_version( $id, $name ) {
		$options        = get_option( $name );
		$taxonomy_model = new TaxonomiesAdminModel();
		$taxonomy_model->set_options( $options );

		return true;
	}

	public function reset_cf_version( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		if ( empty( $data['id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Version ID cannot be empty', 'lisfinity-core' );
		}
		if ( empty( $data['name'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Version name cannot be empty', 'lisfinity-core' );
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		$reset = $this->reset_version( $data['id'], $data['name'] );

		if ( $reset ) {
			$result['versions'] = $this->fetch_cf_versions();
		}

		$result['success'] = true;
		$result['message'] = sprintf( __( 'The Fields Builder has been successfully reset to version: %s', 'lisfinity-core' ), $data['id'] );

		return $result;
	}

	protected function fetch_cf_versions() {
		global $wpdb;
		$versions = $wpdb->get_results( "SELECT option_id, option_name FROM {$wpdb->options} WHERE option_name LIKE 'lisfinity_custom_fields-%' ORDER BY option_id DESC" );

		if ( empty( $versions ) ) {
			return false;
		}
		$versions_limit = lisfinity_get_option( 'site-fields-builder-versions-limit' );
		if ( count( $versions ) > $versions_limit ) {
			$to_delete = array_pop( $versions );
			$wpdb->delete( $wpdb->options, [ 'option_id' => $to_delete->option_id ] );
		}
		$filtered_versions = [];
		$count             = 0;
		foreach ( $versions as $version ) {
			$time                                      = explode( '-', $version->option_name )[1];
			$filtered_versions[ $count ]['id']         = (int) $version->option_id;
			$filtered_versions[ $count ]['name']       = $version->option_name;
			$filtered_versions[ $count ]['time']       = $time;
			$filtered_versions[ $count ]['time_mysql'] = date( 'd M, Y. H:i:s', $time );
			$filtered_versions[ $count ]['time_date']  = date( 'd M, Y.', $time );
			$filtered_versions[ $count ]['time_hour']  = date( 'H:i:s', $time );
			$filtered_versions[ $count ]['time_human'] = human_time_diff( $time, current_time( 'timestamp' ) );
			$count                                     += 1;
		}

		return $filtered_versions;
	}

	public function get_cf_versions( WP_REST_Request $request ) {
		$data              = $request->get_params();
		$filtered_versions = $this->fetch_cf_versions();
		$result            = [];
		if ( ! $filtered_versions ) {
			$result['error']   = true;
			$result['message'] = __( 'No old versions available', 'lisfinity-core' );
		}

		$versions_count     = count( $filtered_versions );
		$result['pages']    = ceil( $versions_count / 10 );
		$result['saved_id'] = (int) get_option( 'lisfinity_custom_fields_template' ) ?? 0;
		if ( $result['saved_id'] ) {
			global $wpdb;
			$saved = $wpdb->get_results( $wpdb->prepare( "SELECT option_id, option_name FROM {$wpdb->options} WHERE option_id=%d", $result['saved_id'] ) );
			if ( $saved ) {
				$time            = explode( '-', $saved[0]->option_name )[1];
				$saved_version   = [
					'id'         => (int) $saved[0]->option_id,
					'name'       => $saved[0]->option_name,
					'time'       => $time,
					'time_mysql' => date( 'd M, Y. H:i:s', $time ),
					'time_date'  => date( 'd M, Y.', $time ),
					'time_hour'  => date( 'H:i:s', $time ),
					'time_human' => human_time_diff( $time, current_time( 'timestamp' ) ),
				];
				$result['saved'] = $saved_version;
			}
		}
		$result['success']  = true;
		$result['versions'] = $filtered_versions;
		$result['message']  = __( 'Available versions', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Get taxonomy options fields from the list
	 * of available ones
	 * -----------------------------------------
	 *
	 * @return mixed
	 */
	public function get_cf_fields() {
		$taxonomy_admin = new TaxonomiesAdmin();

		return $taxonomy_admin->get_fields();
	}

	/**
	 * Get list of available options (taxonomies)
	 * ------------------------------------------
	 *
	 * @return mixed|void
	 */
	public function get_cf_options() {
		$taxonomy_admin = new TaxonomiesAdmin();

		return $taxonomy_admin->get_options();
	}

	/**
	 * Get list of available locations for a given field
	 * -------------------------------------------------
	 *
	 * @param string $options all custom fields options
	 *
	 * @return array
	 */
	public function get_location_options( $options = '' ) {
		if ( empty( $options ) ) {
			$options = $this->get_cf_options();
		}
		$new_options = [];

		if ( empty( $options ) ) {
			return [];
		}

		// get all fields marked as location
		foreach ( $options as $group => $field_option ) {
			foreach ( $field_option as $option ) {
				if ( 'location' === $option['type'] ) {
					$new_options[] = $option;
				}
			}
		}

		if ( empty( $new_options ) ) {
			return $new_options;
		}


		return $new_options;
	}

	/**
	 * Get list of available options for a given field group
	 * -----------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_group_options( WP_REST_Request $request_data ) {
		$data        = $request_data->get_params();
		$options     = $this->get_cf_options();
		$new_options = [];

		if ( empty( $options ) ) {
			return $new_options;
		}

		$new_options = $this->get_taxonomies_by_group( $options, $data['group'] );
		$locations   = $this->get_location_options();

		if ( ! empty( $locations ) ) {
			$new_options['xloc'] = $locations;
		}

		return $new_options;
	}

	/**
	 * Get taxonomies by the group name
	 * --------------------------------
	 *
	 * @param $options
	 * @param $group
	 *
	 * @return array
	 */
	public function get_taxonomies_by_group( $options, $group ) {
		$new_options = [];
		$groups      = explode( ',', $group );
		foreach ( $groups as $group ) {
			if ( ! empty( $options[ $group ] ) ) {
				foreach ( $options[ $group ] as $option ) {
					if ( null !== $option ) {
						$new_options[ $group ][] = $option;
					}
				}
			}
		}

		return $new_options;
	}

	/**
	 * Update taxonomy options
	 * -----------------------
	 *
	 * @param $options
	 * @param $field_group
	 */
	public function update_options( $options, $field_group ) {
		$taxonomies_admin = new TaxonomiesAdmin();
		$settings         = $taxonomies_admin->get_fields();

		update_option( 'lisfinity_custom_fields', $options );
		$taxonomies_admin->set_options( $options );

		// create backup version
		$time = current_time( 'timestamp' );
		update_option( "lisfinity_custom_fields-{$time}", $options );
	}

	/**
	 * Edit a single option or update its order
	 * ----------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @see $this->update_cf_order
	 *
	 * @see $this->update_cf_option
	 */
	public function edit_cf_option( WP_REST_Request $request_data ) {
		$result           = [];
		$taxonomies_admin = new TaxonomiesAdmin();
		$data             = $request_data->get_params();
		$options          = $taxonomies_admin->get_options();

		// If it is order update.
		if ( 'update_order' === $data['action'] ) {
			$result = $this->update_cf_order( $data, $options );
			if ( isset( $result['error'] ) ) {
				wp_send_json_error( $result );
			}
			wp_send_json_success( $result );
		}

		// If it is option update.
		if ( 'update_option' == $data['action'] ) {
			$result = $this->update_cf_option( $data['data'], $options, $data['old_slug'] );
			if ( isset( $result['error'] ) ) {
				wp_send_json_error( $result );
			}
			wp_send_json_success( $result );

			do_action( 'lisfinity__taxonomy_updated', $data );
		}

		$result['error'] = __( 'Something is not working correctly, please contact theme author about it.', 'lisfinity-core' );
		wp_send_json_error( $result );

	}

	/**
	 * Update a single option values
	 * -----------------------------
	 *
	 * @param $data
	 * @param $options
	 *
	 * @return mixed
	 */
	protected function update_cf_option( $data, $options, $old_slug ) {
		$taxonomies_admin  = new TaxonomiesAdmin();
		$reserved_terms    = $taxonomies_admin->get_reserved_terms();
		$result['message'] = __( 'The item couldn\'t be updated.', 'lisfinity-core' );
		$slugs             = array_column( $options[ $data['field_group'] ], 'slug' );
		$key               = array_search( $old_slug, $slugs );

		$taxonomy = $options[ $data['field_group'] ][ $key ];

		if ( empty( $taxonomy ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Cannot find taxonomy within fields options', 'lisfinity-core' );
		}

		if ( empty( $data['slug'] && ! empty( $data['single_name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['slug'] );
		}

		if ( empty( $data['single_name'] ) || empty( $data['plural_name'] ) || empty( $data['slug'] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Singular, Plural names and Slug are required', 'lisfinity-core' );
		} else {
			$data['slug'] = sanitize_title( $data['slug'] );

			if ( $taxonomy['slug'] !== $data['slug'] && ( in_array( $data['slug'], $reserved_terms ) || taxonomy_exists( $data['slug'] ) ) ) {
				$result['error']   = true;
				$result['message'] = esc_html__(
					'Slug name is already in use. Please choose another slug name.',
					'lisfinity-core'
				);
			}
		}

		if ( strlen( $data['slug'] ) >= 32 ) {
			$result['error'] = true;
			if ( lisfinity_is_cyrillic() ) {
				$result['message'] = esc_html__( 'Taxonomy slug has to be smaller than 16 characters due to WordPress database limitations', 'lisfinity-core' );
			} else {
				$result['message'] = esc_html__( 'Taxonomy slug has to be smaller than 32 characters due to WordPress database limitations', 'lisfinity-core' );
			}
		}

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		// update other options that has this one selected as parent.
		foreach ( $options[ $data['field_group'] ] as $index => $option ) {
			if ( $taxonomy['slug'] !== $data['slug'] && $option['parent'] === $taxonomy['slug'] ) {
				$options[ $data['field_group'] ][ $index ]['parent'] = $data['slug'];
			}
		}

		// Update terms with new taxonomy slug
		if ( $taxonomy['slug'] !== $data['slug'] ) {
			$term_admin = new TermsAdminModel();
			$terms      = get_terms(
				[
					'taxonomy'   => $taxonomy['slug'],
					'hide_empty' => false,
				]
			);

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_admin->update_term_taxonomy( $term->term_id, $data['slug'] );
				}
			}
			if ( ! empty( $taxonomy['term_ids'] ) ) {
				$data['term_ids'] = [];
				foreach ( $taxonomy['term_ids'] as $term_slug ) {
					$data['term_ids'][] = str_replace( "{$taxonomy['slug']}-", "{$data['slug']}-", $term_slug );
				}
			}
		}

		// update the option.
		$options[ $data['field_group'] ][ $key ] = $data;

		$this->update_options( $options, $data['field_group'] );

		do_action( 'lisfinity__taxonomy_updated', $data );

		$result['edit']         = true;
		$result['success']      = true;
		$result['order']        = $key;
		$result['slug']         = $data['slug'];
		$result['taxonomy']     = $data;
		$result['slug_changed'] = $data['slug'] !== $taxonomy['slug'];
		$result['message']      = __( 'The item has been updated successfully.', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Update a single option order of display
	 * ---------------------------------------
	 *
	 * @param $data
	 * @param $options
	 *
	 * @return mixed
	 */
	protected function update_cf_order( $data, $options ) {
		if ( empty( $data['group'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Category has not been defined', 'lisfinity-core' );
		}
		if ( empty( $options[ $data['group'] ] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Category cannot be found', 'lisfinity-core' );
		}
		$options_slugs = array_column( $options[ $data['group'] ], 'slug' );

		$new_order                   = $options;
		$new_order[ $data['group'] ] = [];
		foreach ( $data['order'] as $taxonomy_slug ) {
			$key = array_search( $taxonomy_slug, $options_slugs );

			// try parsing options/group/taxonomy
			if ( ! empty( $options[ $data['group'] ][ $key ] ) ) {
				$new_order[ $data['group'] ][] = $options[ $data['group'] ][ $key ];
			}
		}

		$this->update_options( $new_order, $data['group'] );

		$result['order']   = $new_order;
		$result['message'] = __( 'The item has been updated successfully.', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Save a single option in the database
	 * ------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function store_cf_option( WP_REST_Request $request_data ) {
		$result           = [];
		$taxonomies_admin = new TaxonomiesAdmin();
		$data             = $request_data->get_params();
		$options          = $taxonomies_admin->get_options();
		$reserved_terms   = $taxonomies_admin->get_reserved_terms();

		if ( empty( $data['slug'] && ! empty( $data['single_name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['single_name'] );
		}

		if ( empty( $data['single_name'] ) || empty( $data['plural_name'] ) || empty( $data['slug'] ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'Singular and Plural names are required', 'lisfinity-core' );
		} else {
			if ( in_array( $data['slug'], $reserved_terms ) || taxonomy_exists( $data['slug'] ) ) {
				$result['error']   = true;
				$result['message'] = esc_html__(
					'Slug name is already in use. Please choose another slug name.',
					'lisfinity-core'
				);
			}
		}

		if ( strlen( $data['slug'] ) >= 32 ) {
			$result['error'] = true;
			if ( lisfinity_is_cyrillic() ) {
				$result['message'] = esc_html__( 'Taxonomy slug has to be smaller than 16 characters due to WordPress database limitations', 'lisfinity-core' );
			} else {
				$result['message'] = esc_html__( 'Taxonomy slug has to be smaller than 32 characters due to WordPress database limitations', 'lisfinity-core' );
			}
		}

		$data['slug'] = str_replace( ' ', '-', strtolower( $data['slug'] ) );

		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result );
		}

		$current_option = [];
		$fields         = $taxonomies_admin->get_fields();

		foreach ( $fields as $group ) {
			foreach ( $group as $field_key => $value ) {
				if ( ! empty( $data[ $field_key ] ) ) {
					$current_option[ $field_key ] = sanitize_text_field( $data[ $field_key ] );
				} else {
					if ( strpos( $field_key, 'divider' ) === false ) {
						$current_option[ $field_key ] = '';
					}
				}
			}
		}

		$numeric = isset( $data['numeric'] ) ? esc_html__( 'Yes', 'lisfinity-core' ) : esc_html__(
			'No',
			'lisfinity-core'
		);
		$link    = get_site_url() . '/wp-admin/edit-tags.php?taxonomy=' . esc_attr( $data['slug'] ) . '&post_type=product';

		// assign field group to newly created taxonomy.
		$current_option['field_group'] = $data['field_group'];
		$current_option['term_ids']    = [];

        if (! is_array($options) ) {
            $options = [];
        }

		$options[ $data['field_group'] ][] = $current_option;

		$result['option'] = [
			'key'      => max( array_keys( $options ) ),
			'name'     => $data['single_name'],
			'plural'   => $data['plural_name'],
			'suffix'   => $data['suffix_taxonomy'],
			'prefix'   => $data['prefix_taxonomy'],
			'slug'     => $data['slug'],
			'parent'   => isset( $data['parent'] ) ? $data['parent'] : '',
			'numeric'  => $numeric,
			'link'     => $link,
			'term_ids' => [],
		];

		if ( empty( $data['slug'] ) ) {
			$data['slug'] = sanitize_title( $data['single_name'] );
		}

		$this->update_options( $options, $data['field_group'] );

		do_action( 'lisfinity__taxonomy_updated', $data );

		$result['success'] = true;
		$result['message'] = esc_html__(
			'Taxonomy has been successfully added.',
			'lisfinity-core'
		);
		wp_send_json_success( $result );
	}

	/**
	 * Delete the taxonomy from the database.
	 * --------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function delete_cf_option( WP_REST_Request $request_data ) {
		$result           = [];
		$form_data        = $request_data->get_params();
		$data             = $form_data['fields'];
		$group            = $form_data['group'];
		$taxonomies_admin = new TaxonomiesAdmin();

		if ( empty( $data['slug'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Taxonomy couldn\'t be deleted because required fields are missing.', 'lisfinity-core' );

			wp_send_json_error( $result );
		}

		$options_by_group = $taxonomies_admin->get_options_by_group( $group );
		$options          = $taxonomies_admin->get_options();
		$option_key       = array_search( $data['slug'], array_column( $options[ $group ], 'slug' ) );
		$child_option_key = array_search( $data['slug'], array_column( $options[ $group ], 'parent' ) );

		if ( empty( $options_by_group[ $option_key ] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Taxonomy couldn\'t be deleted because it cannot be found.', 'lisfinity-core' );

			wp_send_json_error( $result );
		}

		// detach parent > child relation for the given taxonomy.
		$child_key = $option_key + 1;
		if ( ! empty( $options_by_group[ $child_key ]['parent'] ) && $options_by_group[ $child_key ]['parent'] == $options_by_group[ $option_key ]['slug'] ) {
			$options_by_group[ $child_key ]['parent'] = '';
		}

		// delete the taxonomy.
		unset( $options[ $group ][ $option_key ] );
		// release the child taxonomy from the parent.
		if ( ! empty( $child_option_key ) ) {
			$options[ $group ][ $child_option_key ]['parent'] = '';
		}
		// reindex the taxonomy.
		$options[ $group ] = array_values( $options[ $group ] );

		$this->update_options( $options, is_string( $group ) ? $group : array_values( $group ) );

		do_action( 'lisfinity__taxonomy_deleted', $data );

		// delete attached term_ids
		if ( ! empty( $data['term_ids'] ) ) {
			foreach ( $data['term_ids'] as $term ) {
				$term_id = str_replace( "{$data['slug']}-", '', $term );
				wp_delete_term( (int) $term_id, $data['slug'] );
			}
		}

		//$result['options'] = $options[ $data['group'] ];
		$result['success'] = true;
		$result['order']   = $option_key;
		$result['message'] = __( 'Taxonomy has been successfully deleted.', 'lisfinity-core' );
		wp_send_json_success( $result );
	}

	// todo move below to custom route class

	/**
	 * Get attachment data by given ID or URL.
	 *
	 * @return array
	 */
	public function get_attachment_data() {
		$type  = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';
		$value = isset( $_GET['value'] ) ? sanitize_text_field( $_GET['value'] ) : '';

		return self::get_attachment_metadata( $value, $type );
	}

	public static function get_attachment_metadata( $id, $type ) {
		$attachment_metadata = array(
			'id'        => 0,
			'thumb_url' => '',
			'file_type' => '',
			'file_name' => '',
		);

		// when `$type` is set to 'url' the `$id` will hold the url, not the id
		if ( $type === 'url' ) {
			$attachment_id = static::get_attachment_id( $id );

			if ( $attachment_id === 0 ) {
				$attachment_metadata['thumb_url'] = $id;
			}

			$id = $attachment_id;
		}

		$attachment = get_post( $id );

		if ( ! $attachment ) {
			/**
			 * Filter the metadata for the attachment in case the attachment post is not found.
			 *
			 * @param array $attachment_metadata The attachment metadata.
			 * @param integer|string $id The attachment ID. Either attachment post ID or attachment url.
			 * @param string $type The type of `$id` passed. Either 'id' or 'url'.
			 *
			 * @since 3.0.0
			 */
			return apply_filters( 'lisfinity__attachment_not_found_metadata', $attachment_metadata, $id, $type );
		}

		$attachment_metadata['id']        = intval( $id );
		$attachment_metadata['file_url']  = is_numeric( $id ) ? wp_get_attachment_url( $id ) : $id;
		$attachment_metadata['file_name'] = basename( $attachment_metadata['file_url'] );
		$attachment_metadata['filetype']  = wp_check_filetype( $attachment_metadata['file_url'] );
		$attachment_metadata['file_type'] = preg_replace( '~\/.+$~', '', $attachment_metadata['filetype']['type'] ); // image, video, etc..

		if ( $attachment_metadata['file_type'] == 'image' ) {
			$attachment_metadata['thumb_url'] = $attachment_metadata['file_url'];

			if ( $type == 'id' ) {
				$attachment_metadata['thumb_url'] = wp_get_attachment_thumb_url( $id );
			}
		} else {
			$attachment_metadata['thumb_url'] = wp_mime_type_icon( $id );
		}

		/**
		 * Filter the metadata for the attachment.
		 *
		 * @param array $attachment_metadata The attachment metadata.
		 * @param integer|string $id The attachment ID. Either attachment post ID or attachment url.
		 * @param string $type The type of `$id` passed. Either 'id' or 'url'.
		 *
		 * @since 3.0.0
		 */
		return apply_filters( 'lisfinity__attachment_metadata', $attachment_metadata, $id, $type );
	}

	/**
	 * Get an attachment ID given a file URL
	 * Modified version of https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
	 *
	 * @static
	 * @access public
	 *
	 * @param string $url
	 *
	 * @return integer
	 */
	public static function get_attachment_id( $url ) {
		$attachment_id = 0;
		$dir           = wp_upload_dir();

		/**
		 * Filters the attachment URL from which the attachment ID is being determined.
		 *
		 * @param string $url
		 *
		 * @since 3.0.0
		 */
		$url = apply_filters( 'lisfinity__attachment_id_base_url', $url );

		$filename = basename( $url );

		if ( strpos( $url, $dir['baseurl'] . '/' ) !== false ) {
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $filename,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				),
			);

			$query = new \WP_Query( $query_args );

			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta                = wp_get_attachment_metadata( $post_id );
					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

					if ( $original_file === $filename || in_array( $filename, $cropped_image_files ) ) {
						$attachment_id = intval( $post_id );

						break;
					}
				}
			}
		}

		/**
		 * Filters the attachment id found from the passed attachment URL.
		 *
		 * @param integer $attachment_id
		 * @param string $url
		 *
		 * @since 3.0.0
		 */
		return apply_filters( 'lisfinity__attachment_id_from_url', $attachment_id, $url );
	}

	public function taxonomy_import( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data ) ) {
			$result['error']   = true;
			$result['message'] = esc_html__( 'No data has been provided', 'lisfinity-core' );
		}

		return $this->import_terms_to_taxonomy( $data );
	}

	public function import_terms_to_taxonomy( $data ) {
		// Get taxonomy to add terms for
		$taxonomy = false;

		if ( ! empty( $data['taxonomy'] ) ) {
			$taxonomy = get_taxonomy( $data['taxonomy'] );
		}

		if ( ! is_object( $taxonomy ) ) {
			$result['error'] = esc_html__( 'You have not specified a valid taxonomy.', 'lisfinity-core' );
		}

		// Existing terms
		$taxonomy_terms = get_terms( $taxonomy->name, [
			'hide_empty' => false
		] );

		$delimiter = "\n";
		if ( ! empty( $data['delimiter'] ) ) {
			$delimiter = $data['delimiter'];
		}
		// Terms data from form
		$terms_titles = explode( $delimiter, $data['fields'] );

		// Terms to add, organized via "term sets": each term set corresponds with a line from the textbox from the form, representing a hierarchy
		$termsets = [];

		// Hold term counts for term sets to change the order in which term sets are added later, so that top level terms get created before higher depth terms
		// Would probably not be necessary when "create inexistent parents" is checked
		$termsets_termcounts = [];

		// Loop through all terms and add them to the queue
		foreach ( $terms_titles as $index => $title ) {
			// Get and sanitize title and slug
			$title = trim( $title );

			if ( ! $title ) {
				continue;
			}

			$title = stripslashes( $title );
			$slug  = sanitize_title( $title );

			// Get hierarchy
			$hie_delimiter = '/';
			$parts         = preg_split( '~(?<!\\\)' . preg_quote( $hie_delimiter, '~' ) . '~', $title );

			foreach ( $parts as $index2 => $part ) {
				$parts[ $index2 ] = stripslashes( $part );
			}

			// For non-hierarchical taxonomies we only use the "last" term
			if ( ! $taxonomy->hierarchical ) {
				$parts = array( $parts[ count( $parts ) - 1 ] );
			}

			// Actually add the term set to the queue
			$termsets[ $index ] = [
				'terms'     => $parts,
				'slug'      => $slug,
				'num_terms' => count( $parts ),
			];

			$termsets_termcounts[ $index ] = $termsets[ $index ]['num_terms'];
		}

		// Sort term sets based on total depth
		array_multisort( $termsets_termcounts, SORT_ASC, SORT_NUMERIC, $termsets );

		// Main parent
		$parent_default = ! empty( $data['parent_id'] ) ? $data['parent_id'] : 0;
		$parent_default = $taxonomy->hierarchical ? max( 0, intval( $parent_default ) ) : 0;

		// Counters
		$num_errors         = 0;
		$num_terms_inserted = 0;

		// Add the terms from the queue
		foreach ( $termsets as $index => $termset ) {
			$parent = $parent_default;

			// Loop through term hierarchy of this set
			foreach ( $termset['terms'] as $index => $term ) {
				$term_exists = false;

				// Check if the term already exists
				foreach ( $taxonomy_terms as $index2 => $taxonomy_term ) {
					if ( $taxonomy_term->name == $term && ( ! $taxonomy->hierarchical || $taxonomy_term->parent == $parent ) ) {
						$term_exists = true;

						// Change the current parent as we go further into the hierarchy
						$parent = $taxonomy_term->term_id;

						break;
					}
				}

				// Add the term if it doesn't exist
				if ( ! $term_exists ) {
					// If we shouldn't create inexistent parent terms and we are not at the last term yet, don't insert the term and stop adding terms for this set

					$args = [];

					// Use custom slug for the last term if set
					if ( $index == $termset['num_terms'] - 1 && $termset['slug'] ) {
						$args['slug'] = wp_unique_term_slug( $termset['slug'], (object) $args );
					}

					// Set parent for hierarchical taxonomies
					if ( $taxonomy->hierarchical ) {
						$args['parent'] = $parent;
					}

					// Actually add term
					$inserted_term = wp_insert_term( $term, $taxonomy->name, $args );


					if ( is_wp_error( $inserted_term ) ) {
						$num_errors ++;

						$result['error']   = true;
						$result['message'] = sprintf( __( 'Error adding term &quot;%s&quot;.', 'lisfinity-core' ), esc_html( implode( '/', $termset['terms'] ) ) );

						break;
					} else {
						$num_terms_inserted ++;

						// Change parent for when we are creating a parent term ( if we are not at the last term in the term set yet )
						$parent = $inserted_term['term_id'];

						// Add term to list of existing terms
						$new_term         = get_term( $inserted_term['term_id'], $taxonomy->name );
						$taxonomy_terms[] = $new_term;

						// add post meta used for the fields builder dnd
						$parent_term = get_term_by( 'term_taxonomy_id', $data['parent_id'] );
						update_term_meta( $new_term->term_id, 'parent_name', $parent_term->name );
						update_term_meta( $new_term->term_id, 'parent_slug', $parent_term->slug );
						update_term_meta( $new_term->term_id, 'parent_taxonomy', $parent_term->taxonomy );

					}
				}
			}
		}

		// Remove hierarchy from cache
		delete_option( $taxonomy->name . '_children' );

		if ( isset( $result['error'] ) ) {
			return $result;
		}

		return $result = [
			'terms'   => $taxonomy_terms,
			'success' => esc_html__( 'Completed', 'lisfinity-core' ),
		];
	}

	public function taxonomy_refresh( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		$model = new TaxonomiesAdminModel();
		$model->correct_taxonomies_terms_order_for_fields_builder_change();

		$result['success'] = true;
		$result['message'] = esc_html__( 'Successfully refreshed taxonomies. Please wait for the page to refresh.', 'lisfinity-core' );

		return $result;
	}

}

