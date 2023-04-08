<?php


namespace Lisfinity\REST_API\Taxonomies;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use WP_REST_Request;
use Lisfinity\Models\Taxonomies\TermsAdminModel as TermsAdmin;

class TermRoute extends Route {

	private $term_reserved = [
		'count',
		'description',
		'filter',
		'name',
		'parent',
		'slug',
		'taxonomy',
		'term_group',
		'term_id',
		'term_taxonomy_id',
	];

	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'term_fields'       => [
			'path'                => '/terms-fields',
			'callback'            => 'get_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'terms'             => [
			'rest_path'           => '/terms',
			'path'                => '/terms/(?P<group>\S+)',
			'callback'            => 'get_terms',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'terms_by_group'    => [
			'rest_path'           => '/terms-by-group',
			'path'                => '/terms-by-group/(?P<group>\S+)',
			'callback'            => 'get_terms_by_group',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'terms_by_taxonomy' => [
			'path'                => '/terms/(?P<taxonomy>\S+)',
			'callback'            => 'get_terms_by_taxonomy',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'terms_for_search'  => [
			'path'                => '/terms-search',
			'callback'            => 'get_terms_for_search',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'term'              => [
			'rest_path'           => '/term',
			'path'                => '/term/(?P<id>\w+)',
			'callback'            => 'get_term',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'term_store'        => [
			'path'                => '/term-store',
			'callback'            => 'store_term',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'term_edit'         => [
			'rest_path'           => '/term-edit',
			'path'                => '/term-edit/(?P<term>\d+)',
			'callback'            => 'edit_term',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'terms_edit'        => [
			'rest_path'           => '/terms-edit',
			'path'                => '/terms-edit/',
			'callback'            => 'edit_terms',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
		'term_remove'       => [
			'path'                => '/term-remove',
			'callback'            => 'remove_term',
			'permission_callback' => 'manage_options',
			'methods'             => 'POST',
		],
	];

	public function update_term_order( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();
	}

	/**
	 * Get taxonomy options fields from the list
	 * of available ones
	 * -----------------------------------------
	 *
	 * @return mixed
	 */
	public function get_fields() {
		$terms_admin = new TermsAdmin();

		return $terms_admin->get_fields();
	}

	/**
	 * Return only custom taxonomies created for a product
	 * post type
	 * ---------------------------------------------------
	 *
	 * @return array
	 */
	public function get_product_taxonomies() {
		$filtered   = [];
		$taxonomies = get_taxonomies(
			[
				'object_type' => [ 'product' ],
			],
			'objects'
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! in_array( $taxonomy->name, [ 'product_type', 'product_cat', 'product_tag' ] ) ) {
				$filtered[] = $taxonomy->name;
			}
		}

		return $filtered;
	}

	/**
	 * Filter terms to include only the ones that are
	 * connected to the custom created ones.
	 * -------------------------------------
	 *
	 * @param $terms
	 *
	 * @return array
	 */
	public function filter_terms_for_search( $terms ) {
		$filtered = [];
		if ( empty( $terms ) ) {
			return $terms;
		}

		$taxonomy_admin  = new TaxonomiesAdminModel();
		$taxonomies      = [];
		$taxonomy_groups = $taxonomy_admin->get_options();
		if ( ! empty( $taxonomy_groups ) ) {
			foreach ( $taxonomy_groups as $taxonomy_group => $taxonomies_group ) {
				foreach ( $taxonomies_group as $taxonomy ) {
					if ( ! empty( $taxonomy['slug'] ) ) {
						$taxonomies[ $taxonomy['slug'] ] = $taxonomy;
					}
				}
			}
		}

		$child_terms = [];
		foreach ( $terms as $term ) {
			if ( in_array( $term->taxonomy, $this->get_product_taxonomies() ) ) {
				if ( $term->parent !== 0 ) {
					$meta = get_term_meta( $term->term_id, '', true );
					//$child_terms[ $term->taxonomy ]['options']                     = $taxonomies[ $term->taxonomy ];
					$child_terms[ $term->taxonomy ][ $term->slug ]        = $term;
					$child_terms[ $term->taxonomy ][ $term->slug ]->meta  = $meta;
					$child_terms[ $term->taxonomy ]['organized']['group'] = $taxonomies[ $term->taxonomy ]['field_group'];
					$child_terms[ $term->taxonomy ]['parent']             = $taxonomies[ $term->taxonomy ]['parent'];
				} else {
					$meta = get_term_meta( $term->term_id, '', true );
					//$filtered[ $term->taxonomy ]['options']                     = isset( $taxonomies[ $term->taxonomy ] ) ? $taxonomies[ $term->taxonomy ] : '';
					$filtered[ $term->taxonomy ][ $term->slug ]       = $term;
					$filtered[ $term->taxonomy ][ $term->slug ]->meta = $meta;
				}
			}
		}

		$filtered = array_merge( $filtered, $child_terms );

		return $filtered;
	}

	/**
	 * Get all terms or the terms from a requested taxonomy
	 * ----------------------------------------------------
	 *
	 * @return array
	 */
	public function get_terms_for_search() {
		return lisfinity_cache_search_terms( true );
	}

	/**
	 * Filter terms to include only the ones that are
	 * connected to the custom created ones.
	 * -------------------------------------
	 *
	 * @param $terms
	 *
	 * @return array
	 */
	public function filter_terms( $terms ) {
		$filtered = [];
		if ( empty( $terms ) ) {
			return $terms;
		}

		foreach ( $terms as $term ) {
			if ( ! empty( $term->taxonomy ) ) {
				if ( in_array( $term->taxonomy, $this->get_product_taxonomies() ) ) {
					//$meta                             = get_term_meta( $term->term_id, '', true );
					$meta['field_group']              = get_term_meta( $term->term_id, 'field_group', true );
					$meta['parent_name']              = get_term_meta( $term->term_id, 'parent_name', true );
					$meta['parent_slug']              = get_term_meta( $term->term_id, 'parent_slug', true );
					$meta['taxonomy-slug']            = get_term_meta( $term->term_id, 'taxonomy-slug', true );
					$meta['premium']                  = get_term_meta( $term->term_id, 'premium', true );
					$meta['bg_image']                 = get_term_meta( $term->term_id, 'bg_image', true );
					$meta['icon']                     = get_term_meta( $term->term_id, 'icon', true );
					$filtered[ $term->term_id ]       = $term;
					$filtered[ $term->term_id ]->meta = $meta;
				}
			}
		}

		return $filtered;
	}

	/**
	 * Get all terms or the terms from a requested taxonomy
	 * ----------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_terms( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();
		$args = [
			'hide_empty' => false,
		];

		$taxonomies_model = new TaxonomiesAdminModel();

		if ( ! empty( $data['group'] ) ) {
			// get options by group.
			if ( 'all' === $data['group'] ) {
				$taxonomies = $taxonomies_model->get_taxonomies_options_by_group( $data['group'] );
			} else {
				// get all options.
				$taxonomies = $taxonomies_model->get_taxonomies_options();
			}
			foreach ( $taxonomies as $taxonomy ) {
				if ( taxonomy_exists( $taxonomy['slug'] ) ) {
					$args['taxonomy'][] = $taxonomy['slug'];
				}
				// todo do the check if we wish to hide terms with the taxonomy type input
				// todo maybe add it optionally
				/*if ( taxonomy_exists( $taxonomy['slug'] ) && 'input' !== $taxonomy['type'] ) {
				}*/
			}
		}

		return lisfinity_cache_terms( $args, ! empty( $data['update'] ) ? $data['update'] : false );
	}

	/**
	 * Get terms by a specific group. Used by fields builder to obtain terms
	 * ---------------------------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_terms_by_group( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();
		$args = [
			'hide_empty' => false,
		];

		$taxonomies_model = new TaxonomiesAdminModel();

		if ( ! empty( $data['group'] ) ) {
			$taxonomies = $taxonomies_model->get_taxonomies_options_by_group( $data['group'] );
		} else {
			// get all options.
			$taxonomies = $taxonomies_model->get_taxonomies_options();
		}
		foreach ( $taxonomies as $taxonomy ) {
			// todo maybe to do the check for the type input {'input' !== $taxonomy['type']}
			if ( taxonomy_exists( $taxonomy['slug'] ) ) {
				$args['taxonomy'][] = $taxonomy['slug'];
			}
		}

		return lisfinity_cache_terms( $args, true, "-{$data['group']}" );
	}

	public function get_terms_by_taxonomy( WP_REST_Request $request_data ) {
		$data             = $request_data->get_params();
		$args             = [
			'hide_empty' => false,
		];
		$taxonomies_model = new TaxonomiesAdminModel();

		if ( ! empty( $data['taxonomy'] ) ) {
			if ( taxonomy_exists( $data['taxonomy'] ) ) {
				$args['taxonomy'][] = $data['taxonomy'];
			}
		}
		$terms = get_terms( $args );

		if ( empty( $terms ) ) {
			return [];
		}

		// filters terms to exclude not custom related taxonomies.
		$terms_filtered = $this->filter_terms( $terms );

		return $terms_filtered;
	}

	/**
	 * Get the specific term that was requested
	 * ----------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_term( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();
		// if searching for term by its id.
		if ( is_numeric( $data['id'] ) ) {
			$term = get_term_by( 'term_taxonomy_id', $data['id'] );
		} else { // if searching for term by its slug.
			$taxonomies = $this->get_product_taxonomies();
			if ( $taxonomies ) {
				foreach ( $taxonomies as $key => $taxonomy ) {
					if ( $term = get_term_by( 'slug', $data['id'], $taxonomy ) ) {
						break;
					}
				}
			}
		}

		if ( empty( $term ) ) {
			return [ 'error' => __( 'The requested term does not exist.', 'lisfinity-core' ) ];
		}


		$result = [
			'term' => $term,
		];

		return $result;
	}

	/**
	 * Save the term to the database
	 * -----------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function store_term( WP_REST_Request $request_data ) {
		$result = [];
		$data   = $request_data->get_params();

		if ( empty( $data['slug'] && ! empty( $data['name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['name'] );
		} else {
			$data['slug'] = str_replace( ' ', '-', strtolower( $data['slug'] ) );
		}

		// throw error if term slug is not unique
		if ( term_exists( $data['slug'], $data['taxonomy-slug'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Term slug is already existent.', 'lisfinity-core' );
			wp_send_json_error( $result );
		}
		// throw error if taxonomy is not set
		if ( ! taxonomy_exists( $data['taxonomy-slug'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Taxonomy is not existent.', 'lisfinity-core' );
			wp_send_json_error( $result );
		}
		// throw error if term name is not set
		if ( empty( $data['name'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Term name cannot be empty.', 'lisfinity-core' );
			wp_send_json_error( $result );
		}

		$term_args           = [];
		$term_args['parent'] = ! empty( $data['parent'] ) ? $data['parent'] : 0;

		$term_result = wp_insert_term( $data['name'], $data['taxonomy-slug'], $term_args );

		if ( is_wp_error( $term_result ) ) {
			$result['error']   = true;
			$result['message'] = __( 'There has been some error with adding term to the database.', 'lisfinity-core' );
			wp_send_json_error( $result );
		}

		// Create meta fields for the parent term if needed.
		if ( ! empty( $data['parent'] ) ) {
			$parent_term = get_term_by( 'term_id', (int) $data['parent'], $data['parentTaxonomy'] );

			update_term_meta( $term_result['term_id'], 'parent_name', $parent_term->name );
			update_term_meta( $term_result['term_id'], 'parent_slug', $parent_term->slug );
			update_term_meta( $term_result['term_id'], 'parent_taxonomy', $parent_term->taxonomy );
		}

		// Update term meta values.
		foreach ( $data as $key => $value ) {
			if ( ! in_array( $key, $this->term_reserved ) && ! empty( $value ) ) {
				update_term_meta( $term_result['term_id'], $key, $value );
			}
		}

		$taxonomy_model = new TaxonomiesAdminModel();
		$tax_options    = $taxonomy_model->get_options();
		$taxonomies     = $tax_options[ $data['field_group'] ];
		$taxonomy_slugs = array_column( $tax_options[ $data['field_group'] ], 'slug' );
		$tax_key        = array_search( $data['taxonomy-slug'], $taxonomy_slugs );
		if ( ! in_array( $data['taxonomy-slug'] . '-' . $term_result['term_id'], $taxonomies[ $tax_key ]['term_ids'] ) ) {
			$taxonomies[ $tax_key ]['term_ids'][] = $data['taxonomy-slug'] . '-' . $term_result['term_id'];
		}
		$tax_options[ $data['field_group'] ] = $taxonomies;

		$taxonomy_model->set_options( $tax_options );

		do_action( 'lisfinity__term_updated', $data );

		$term       = get_term_by( 'term_taxonomy_id', (int) $term_result['term_id'], $data['taxonomy-slug'] );
		$meta       = get_term_meta( $term_result['term_id'], '', true );
		$term->meta = $meta;

		$result['success'] = true;
		$result['term']    = $term;
		$result['message'] = __( 'The term has been successfully added', 'lisfinity-core' );

		wp_send_json_success( $result );
	}

	/**
	 * Update terms order of display
	 * -----------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function edit_terms( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		// if we're just updating the order of terms.
		if ( 'update_order' === $data['action'] ) {
			// throw error on empty terms.
			if ( empty( $data['taxonomy'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Taxonomy is not defined.', 'lisfinity-core' );
			}
			if ( empty( $data['terms_ids'] ) ) {
				$result['error']   = true;
				$result['message'] = __( 'Terms are not defined.', 'lisfinity-core' );
			}
			if ( empty( $data['group'] ) ) {
				$result['error']   = true;
				$result['message'] = sprintf( __( 'Category %s cannot be found', 'lisfinity-core' ), $data['group'] );
			}

			$tax_model     = new TaxonomiesAdminModel();
			$options       = $tax_model->get_options();
			$options_slugs = array_column( $options[ $data['group'] ], 'slug' );
			$key           = array_search( $data['taxonomy'], $options_slugs );

			$options[ $data['group'] ][ $key ]['term_ids'] = $data['term_ids'];

			$tax_model->set_options( $options );

			do_action( 'lisfinity__term_updated', $data );

			$result['success'] = true;
			$result['message'] = __( 'The order of the items has been successfully updated', 'lisfinity-core' );
			wp_send_json_success( $result );
		}

		$result['error']   = true;
		$result['message'] = __( 'The necessary action hasn\'t been matched. Please contact theme authors.', 'lisfinity-core' );
	}

	/**
	 * Update the term by its requested id
	 * -----------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function edit_term( WP_REST_Request $request_data ) {
		$form_data       = $request_data->get_params();
		$data            = $form_data['data'];
		$group           = $form_data['group'];
		$parent_taxonomy = $form_data['parentTaxonomy'];

		// just update parent taxonomy.
		$current_term = get_term_by( 'term_taxonomy_id', $data['term_id'], $data['taxonomy'] );

		if ( empty( $data['slug'] && ! empty( $data['name'] ) ) ) {
			$data['slug'] = lisfinity_create_slug( $data['name'] );
		}

		// throw error if term name is not set
		if ( empty( $data['name'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Term name cannot be empty.', 'lisfinity-core' );
			wp_send_json_error( $result );
		}

		$updated_term = wp_update_term(
			$data['term_id'],
			$data['taxonomy'],
			[
				'name'   => $data['name'],
				'slug'   => $data['slug'],
				'parent' => ! empty( $data['parent'] ) ? $data['parent'] : 0,
			]
		);

		if ( is_wp_error( $updated_term ) ) {
			$result['error']   = true;
			$result['message'] = $updated_term->get_error_message();

			wp_send_json_error( $result );
		}

		// Create meta fields for the parent term if needed.
		if ( ! empty( $data['parent'] ) && ! empty( $parent_taxonomy ) ) {
			$parent_term = get_term_by( 'term_id', (int) $data['parent'], $parent_taxonomy );
			update_term_meta( (int) $data['term_id'], 'parent_name', $parent_term->name );
			update_term_meta( (int) $data['term_id'], 'parent_slug', $parent_term->slug );
			update_term_meta( (int) $data['term_id'], 'parent_taxonomy', $parent_term->taxonomy );
		}

		// Update term meta values.
		foreach ( $data['meta'] as $key => $value ) {
			if ( ! in_array( $key, [ 'parent', 'parent_name', 'parent_slug', 'parent_taxonomy', 'short_name' ] ) ) {
				if ( 'order' === $key ) {
					delete_term_meta( $data['term_id'], $key );
				}
				if ( ! empty( $value ) ) {
					update_term_meta( $data['term_id'], $key, $value );
				}
			}
		}

		if ( $current_term->slug !== $data['slug'] ) {
			$tax_model     = new TaxonomiesAdminModel();
			$options       = $tax_model->get_options();
			$options_slugs = array_column( $options[ $group ], 'slug' );
			$key           = array_search( $data['taxonomy'], $options_slugs );


			if ( ! empty( $options[ $group ][ $key ]['term_ids'] ) ) {
				$new_term_ids = [];
				foreach ( $options[ $group ][ $key ]['term_ids'] as $term_slug ) {
					if ( "{$data['taxonomy']}-{$current_term->term_id}" === $term_slug ) {
						$new_term_ids[] = "${data['taxonomy']}-{$data['term_id']}";
					} else {
						$new_term_ids[] = $term_slug;
					}
				}

				$options[ $group ][ $key ]['term_ids'] = $new_term_ids;
				$result['term_ids']                    = $new_term_ids;

				$tax_model->set_options( $options );
			}

		}

		do_action( 'lisfinity__term_updated', $data );

		$data['meta'] = get_term_meta( $data['term_id'] );

		$result['term']    = $data;
		$result['parent']  = $parent_term ?? false;
		$result['success'] = true;
		$result['message'] = __( 'The term has been successfully updated.', 'lisfinity-core' );

		wp_send_json_success( $result );
	}

	/**
	 * Remove the requested term from the database
	 * -------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function remove_term( WP_REST_Request $request_data ) {
		$form_data = $request_data->get_params();
		$data      = $form_data['data'];
		$group     = $form_data['group'];

		if ( empty( $data['term_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Requested term id is empty', 'lisfinity-core' );

			wp_send_json_error( $result );
		}
		if ( ! term_exists( (int) $data['term_id'], $data['taxonomy'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Requested term does not exists.', 'lisfinity-core' );

			wp_send_json_error( $result );
		}

		// remove term
		wp_delete_term( $data['term_id'], $data['taxonomy'] );

		// remove child terms
		$child_terms = $this->remove_child_terms( (int) $data['term_id'] );

		$tax_model     = new TaxonomiesAdminModel();
		$options       = $tax_model->get_options();
		$options_slugs = array_column( $options[ $group ], 'slug' );
		$key           = array_search( $data['taxonomy'], $options_slugs );
		$term_key      = array_search( "{$data['taxonomy']}-{$data['term_id']}", $options[ $group ][ $key ]['term_ids'] );

		array_splice( $options[ $group ][ $key ]['term_ids'], $term_key, 1 );

		$tax_model->set_options( $options );

		if ( ! empty( $child_terms ) ) {
			$tax_model->correct_taxonomies_terms_order_for_fields_builder_change();
		}

		$result['options']     = $options[ $group ][ $key ];
		$result['term']        = $data;
		$result['term_key']    = $term_key;
		$result['child_terms'] = ! empty( $child_terms ) ? $child_terms : false;
		$result['success']     = true;
		$result['message']     = __( 'The term has been successfully deleted.', 'lisfinity-core' );

		do_action( 'lisfinity__term_updated', $data );

		wp_send_json_success( $result );
	}

	/**
	 * Remove child terms when deleting parent term
	 * --------------------------------------------
	 *
	 * @param $parent_term
	 *
	 * @return array
	 */
	protected function remove_child_terms( $parent_term ) {
		global $wpdb;
		$terms = $wpdb->get_results( $wpdb->prepare( "SELECT term_id, taxonomy FROM $wpdb->term_taxonomy WHERE parent=%d", $parent_term ) );

		$child_terms = [];

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$child_terms[ $term->term_id ] = "{$term->taxonomy}-{$term->term_id}";
				wp_delete_term( $term->term_id, $term->taxonomy );
			}
		}

		return $child_terms;
	}

}
