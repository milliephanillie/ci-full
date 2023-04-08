<?php
/**
 * Custom Taxonomies administration
 *
 * @author pebas
 * @package custom-fields/taxonomies
 * @version 1.0.0
 */

namespace Lisfinity\Models\Taxonomies;

/**
 * Class TaxonomiesAdminModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class TaxonomiesAdminModel {

	protected $fields;

	protected $options;

	protected $reserved_terms;

	public function __construct() {
		// delete_option('lisfinity_custom_fields' );
		$this->reserved_terms = $this->set_reserved_terms();
		$this->options        = get_option( 'lisfinity_custom_fields' );
		$this->set_fields();
	}

	/**
	 * List of reserved terms by WordPress
	 * that cannot be used as our taxonomies
	 * -------------------------------------
	 *
	 * @return array
	 */
	public function set_reserved_terms() {
		$reserved_terms = array(
			'attachment',
			'attachment_id',
			'author',
			'author_name',
			'calendar',
			'cat',
			'category',
			'category__and',
			'category__in',
			'category__not_in',
			'category_name',
			'comments_per_page',
			'comments_popup',
			'cpage',
			'day',
			'debug',
			'error',
			'exact',
			'feed',
			'hour',
			'link_category',
			'm',
			'minute',
			'monthnum',
			'more',
			'name',
			'nav_menu',
			'nopaging',
			'offset',
			'order',
			'orderby',
			'p',
			'page',
			'page_id',
			'paged',
			'pagename',
			'pb',
			'perm',
			'post',
			'post__in',
			'post__not_in',
			'post_format',
			'post_mime_type',
			'post_status',
			'post_tag',
			'post_type',
			'posts',
			'posts_per_archive_page',
			'posts_per_page',
			'preview',
			'robots',
			's',
			'search',
			'second',
			'sentence',
			'showposts',
			'static',
			'subpost',
			'subpost_id',
			'tag',
			'tag__and',
			'tag__in',
			'tag__not_in',
			'tag_id',
			'tag_slug__and',
			'tag_slug__in',
			'taxonomy',
			'tb',
			'term',
			'type',
			'w',
			'withcomments',
			'withoutcomments',
			'year',
		);

		return apply_filters( 'lisfinity__set_reserved_terms', $reserved_terms );
	}

	/**
	 * Get list of WordPress' reserved taxonomies
	 * ------------------------------------------
	 *
	 * @return array
	 */
	public function get_reserved_terms() {
		return $this->reserved_terms;
	}

	/**
	 * Add admin menu page
	 * -------------------
	 */
	public function admin_menu() {
		if ( \LisfinityBase::GetRegisterInfo() ) {
			$this->add_menu_page();
		}
	}

	/**
	 * Add admin submenu page
	 * ----------------------
	 */
	protected function add_menu_page() {
		add_menu_page(
			__( 'Fields Builder', 'lisfinity-core' ),
			__( 'Fields Builder', 'lisfinity-core' ),
			'manage_options',
			'custom-fields',
			[ $this, 'menu_settings' ],
			'dashicons-chart-pie',
			25
		);
		add_submenu_page(
			'custom-fields',
			__( 'All Categories', 'lisfinity-core' ),
			__( 'All Categories', 'lisfinity-core' ),
			'manage_options',
			'custom-fields',
			[ $this, 'group_settings' ]
		);
		add_submenu_page(
			'custom-fields',
			__( 'Common Fields', 'lisfinity-core' ),
			__( 'Common Fields', 'lisfinity-core' ),
			'manage_options',
			'custom-fields-common',
			[ $this, 'group_settings' ]
		);
	}

	/**
	 * Get submenu display
	 * -------------------
	 */
	public function menu_settings() {
		$options      = $this->get_options();
		$options_list = $this->get_fields();

		$args = [
			'options' => $options,
			'fields'  => $options_list,
		];

		include lisfinity_get_template_part( 'dashboard', 'admin/taxonomies', $args );
	}

	/**
	 * Get submenu display
	 * -------------------
	 */
	public function group_settings() {
		$screen              = get_current_screen();
		$groups_model        = new GroupsAdminModel();
		$options             = $this->get_options();
		$options_list        = $this->get_fields();
		$fields_builder      = translate( 'Fields Builder', 'lisfinity-core' );
		$fields_builder_slug = sanitize_title( $fields_builder );
		$fields_group        = str_replace( "{$fields_builder_slug}_page_custom-fields-", '', $screen->id );
		$group_options       = ! empty( $groups_model->get_options() ) ? $groups_model->get_options() : [];
		$groups              = array_merge( [
			[
				'single_name' => __( 'Common', 'lisfinity-core' ),
				'plural_name' => __( 'Commons', 'lisfinity-core' ),
				'slug'        => 'common',
			]
		], $group_options );

		$args = [
			'groups'       => $groups,
			'options'      => $options,
			'fields'       => $options_list,
			'fields_group' => $fields_group,
		];

		$taxonomies = $this->get_taxonomies_by_group( $args['fields_group'], true, __( 'No Parent', 'lisfinity-core' ) );
		ksort( $taxonomies );
		$args['taxonomies'] = $taxonomies;

		include lisfinity_get_template_part( 'custom-fields', 'admin/taxonomies', $args );
	}

	/**
	 * Get list of available options
	 * -----------------------------
	 *
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Configure the options. This is useful  for
	 * fields importing
	 * ------------------------------------------
	 *
	 * @param $options
	 */
	public function set_options( $options ) {
		if ( ! empty( $options ) ) {
			foreach ( $options as $taxonomy => $fields ) {
				if ( empty( $taxonomy ) ) {
					unset( $options[ $taxonomy ] );
				}
				foreach ( $fields as $index => $field ) {
					if ( count( $field ) < 4 ) {
						unset( $options[ $taxonomy ][ $index ] );
					}
				}
			}
		}

		update_option( 'lisfinity_custom_fields', $options );
		$this->options = $options;

		//todo create backup versions
		//$time = current_time( 'timestamp' );
		//update_option( "lisfinity_custom_fields-{$time}", $options );
	}

	/**
	 * Get list of available options by a given group
	 * ----------------------------------------------
	 *
	 * @param $group
	 *
	 * @return array
	 */
	public function get_options_by_group( $group ) {
		$filtered = [];
		if ( empty( $this->options ) ) {
			return $filtered;
		}

		$count = 0;
		foreach ( $this->options[ $group ] as $option ) {
			if ( $group === $option['field_group'] ) {
				$filtered[ $count ] = $option;
				$count ++;
			}
		}

		return $filtered;
	}

	/**
	 * Get taxonomy form fields
	 * ------------------------
	 *
	 * @return mixed
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Set taxonomies form fields
	 * --------------------------
	 *
	 * @return array
	 */
	protected function set_fields() {
		$count  = 1;
		$fields = [
			// fields | general.
			'general' => [
				'single_name'         => [
					'key'         => $count ++,
					'label'       => __( 'Singular Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'The singular version of the field group name so it can be used where appropriate.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
					'required'    => true,
				],
				'plural_name'         => [
					'key'         => $count ++,
					'label'       => __( 'Plural Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'The plural version of the field group name so it can be used where appropriate.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
					'required'    => true,
				],
				'slug'                => [
					'key'         => $count ++,
					'label'       => __( 'Slug', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Slug name is automatically created based on the single name if left empty.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'parent'              => [
					'key'         => $count ++,
					'label'       => __( 'Parent Field', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'select',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'type'                => [
					'key'         => $count ++,
					'label'       => __( 'Field Type', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Used to explain the purpose of the field group. For what is it used etc.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'select'   => __( 'Select', 'lisfinity-core' ),
						'checkbox' => __( 'Checkbox', 'lisfinity-core' ),
						'text'     => __( 'Text', 'lisfinity-core' ),
						'input'    => __( 'Number', 'lisfinity-core' ),
						'location' => __( 'Location', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'premium'     => [
					'key'         => $count ++,
					'label'       => __( 'For Premium Users Only', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Listings from this taxonomy will be available for premium users only.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => __( 'No', 'lisfinity-core' ),
						'yes' => __( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'first_empty' => [
					'key'         => $count ++,
					'label'       => __( 'First Value Empty?', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Select if you wish that the first value is displayed as empty when submitting a listing.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => esc_html__( 'No', 'lisfinity-core' ),
						'yes' => esc_html__( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full',
					],
					'conditional' => [ 'type', 'IN', [ 'select', 'location' ] ],
				],
				'submission_required' => [
					'key'         => $count ++,
					'label'       => __( 'Required in the listing submission form?', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Select if you wish that the field is required in the listing submission form.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => esc_html__( 'No', 'lisfinity-core' ),
						'yes' => esc_html__( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full',
					],
					'conditional' => [ 'type', 'IN', [ 'select', 'location' ] ],
				],
				'label'               => [
					'key'         => $count ++,
					'label'       => __( 'Label', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Type the label or leave empty to use the value of the Single Name field', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'placeholder'         => [
					'key'         => $count ++,
					'label'       => __( 'Placeholder', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Type the placeholder that will be displayed on the field', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'suffix_taxonomy'     => [
					'key'         => $count ++,
					'label'       => __( 'Suffix', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Type the suffix that will be displayed on the field', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'prefix_taxonomy'     => [
					'key'         => $count ++,
					'label'       => __( 'Prefix', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Type the prefix that will be displayed on the field', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'icon-size'           => [
					'key'         => $count ++,
					'label'       => __( 'Choose icon size', 'lisfinity-core' ),
					'value'       => 12,
					'description' => __( 'Choose icon size for the taxonomy.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						12 => '12px',
						13 => '13px',
						14 => '14px',
						15 => '15px',
						16 => '16px',
						17 => '17px',
						18 => '18px',
						19 => '19px',
						20 => '20px',
						21 => '21px',
						22 => '22px',
						23 => '23px',
						24 => '24px',
					],
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'bg_image'            => [
					'key'         => $count ++,
					'label'       => __( 'Background Image', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'file',
					'type_filter' => 'image',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'icon'                => [
					'key'         => $count ++,
					'label'       => __( 'Choose Icon', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'file',
					'type_filter' => 'image',
					'buttonLabel' => __( 'Select Icon', 'lisfinity-core' ),
					'additional'  => [
						'class' => 'w-full',
					],
				],
			],
		];

		$this->fields = apply_filters( 'lisfinity__custom_fields_fields', $fields );

		return $this->fields;
	}

	/**
	 * Get all available options
	 * -------------------------
	 *
	 * @return array
	 */
	public function get_all_options() {
		$all_fields = [];
		if ( ! empty( $this->options ) ) {
			foreach ( $this->options as $group ) {
				foreach ( $group as $fields ) {
					if ( ! empty( $fields ) ) {
						$all_fields[] = $fields;
					}
				}
			}
		}

		return $all_fields;
	}

	/**
	 * Register new taxonomies from the provided list
	 * ----------------------------------------------
	 */
	public function register_new_taxonomies() {
		$options = $this->get_all_options();
		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $option ) {

				$show_admin_table = false;
				if ( ! empty( $option['show_in_admin_column'] ) and $option['show_in_admin_column'] ) {
					$show_admin_table = true;
				}

				if ( empty( $option['numeric'] ) ) {
					$numeric = true;
				} else {
					$numeric          = false;
					$show_admin_table = false;
				}

				if ( ! empty( $option ) && count( $option ) >= 4 ) {
					$tax_object = register_taxonomy(
						$option['slug'],
						'product',
						array(
							'labels'             => array(
								'name'                       => $option['plural_name'],
								'singular_name'              => $option['single_name'],
								'search_items'               => __( 'Search ' . $option['plural_name'], 'lisfinity-core' ),
								'popular_items'              => __( 'Popular ' . $option['plural_name'], 'lisfinity-core' ),
								'all_items'                  => __( 'All ' . $option['plural_name'], 'lisfinity-core' ),
								'parent_item'                => null,
								'parent_item_colon'          => null,
								'edit_item'                  => __( 'Edit ' . $option['single_name'], 'lisfinity-core' ),
								'update_item'                => __( 'Update ' . $option['single_name'], 'lisfinity-core' ),
								'add_new_item'               => __( 'Add New ' . $option['single_name'], 'lisfinity-core' ),
								'new_item_name'              => __( 'New ' . $option['single_name'] . ' Name', 'lisfinity-core' ),
								'separate_items_with_commas' => __( 'Separate ' . $option['plural_name'] . ' with commas', 'lisfinity-core' ),
								'add_or_remove_items'        => __( 'Add or remove ' . $option['plural_name'], 'lisfinity-core' ),
								'choose_from_most_used'      => __( 'Choose from the most used ' . $option['plural_name'], 'lisfinity-core' ),
								'not_found'                  => __( 'No ' . $option['plural_name'] . ' found.', 'lisfinity-core' ),
								'menu_name'                  => __( $option['plural_name'], 'lisfinity-core' ),
							),
							'public'             => true,
							'hierarchical'       => $numeric,
							'show_ui'            => lisfinity_is_enabled( \Redux::get_option( 'lisfinity-options', '_site-fields-builder-ui' ) ),
							'show_in_menu'       => false,
							'show_admin_column'  => $show_admin_table,
							'show_in_nav_menus'  => false,
							'show_in_quick_edit' => false,
							'show_in_rest'       => true,
							'query_var'          => true,
							'rewrite'            => true,
						)
					);
				}
			}
		}
	}

	/**
	 * Get all registered taxonomies
	 * -----------------------------
	 *
	 * @param bool $first_empty
	 * @param string $first_label
	 *
	 * @return array
	 */
	public function get_taxonomies( $first_empty = false, $first_label = '' ) {
		$groups               = $this->get_options();
		$taxonomies_organized = [];
		if ( $first_empty ) {
			$label                = empty( $first_label ) ? __( 'No', 'lisfinity-core' ) : $first_label;
			$taxonomies_organized = [ '' => $label ];
		}

		if ( empty( $groups ) ) {
			return $taxonomies_organized;
		}

		foreach ( $groups as $group ) {
			if ( ! empty( $group ) ) {
				foreach ( $group as $taxonomy ) {
					if ( ! empty( $taxonomy['slug'] ) ) {
						$taxonomies_organized[ $taxonomy['slug'] ] = $taxonomy['single_name'];
					}
				}
			}
		}


		return $taxonomies_organized;
	}

	/**
	 * Format taxonomies by a given group for use in a select field
	 * ------------------------------------------------------------
	 *
	 * @param string $group_name
	 * @param bool $first_empty
	 * @param string $first_label
	 *
	 * @return array
	 */
	public function get_taxonomies_by_group( $group_name, $first_empty = false, $first_label = '' ) {
		$groups               = $this->get_options();
		$taxonomies_organized = [];
		if ( $first_empty ) {
			$label                = empty( $first_label ) ? __( 'No', 'lisfinity-core' ) : $first_label;
			$taxonomies_organized = [ '' => $label ];
		}

		if ( empty( $groups ) ) {
			return $taxonomies_organized;
		}

		foreach ( $groups as $group => $fields ) {
			if ( ! empty( $fields ) ) {
				if ( $group === $group_name ) {
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $field ) {
							if ( ! empty( $field['slug'] ) && ! isset( $taxonomies_organized[ $field['slug'] ] ) ) {
								$taxonomies_organized[ $field['slug'] ] = $field['single_name'];
							}
						}
					}
				}
			}
		}

		return $taxonomies_organized;
	}

	public function get_taxonomies_options() {
		$groups               = $this->get_options();
		$taxonomies_organized = [];

		if ( empty( $groups ) ) {
			return $taxonomies_organized;
		}

		foreach ( $groups as $group => $fields ) {
			if ( ! empty( $group ) ) {
				if ( ! empty( $fields ) ) {
					foreach ( $fields as $field ) {
						$taxonomies_organized[] = $field;
					}
				}
			}
		}

		return $taxonomies_organized;
	}


	/**
	 * Format taxonomies by a given group for use in a select field
	 * ------------------------------------------------------------
	 *
	 * @param string $group_name
	 *
	 * @return array
	 */
	public function get_taxonomies_options_by_group( $group_name ) {
		$groups               = $this->get_options();
		$taxonomies_organized = [];

		if ( empty( $groups ) ) {
			return $taxonomies_organized;
		}

		foreach ( $groups as $group => $fields ) {
			if ( ! empty( $group ) ) {
				if ( $group == $group_name ) {
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $field ) {
							$taxonomies_organized[] = $field;
						}
					}
				}
			}
		}

		return $taxonomies_organized;
	}


	/**
	 * Format taxonomies properly so that they can be used in a select field
	 * ---------------------------------------------------------------------
	 *
	 * @param bool $multiple
	 * @param $taxonomy
	 *
	 * @return array
	 */
	public function format_taxonomy_for_select( $multiple = false, $taxonomy ) {
		$select = [];
		if ( ! $multiple ) {
			$select[''] = __( 'Any Type', 'lisfinity-core' );
		}

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return $select;
		}

		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		] );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$select[ $term->slug ] = $term->name;
			}
		}

		return $select;
	}

	/**
	 * Get taxonomy options
	 * --------------------
	 *
	 * @param $taxonomy
	 *
	 * @return bool|mixed
	 */
	public function get_taxonomy_options( $taxonomy ) {
		$groups = $this->get_options();

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				if ( ! empty( $group ) ) {
					foreach ( $group as $field ) {
						if ( ! empty( $field['slug'] ) && $taxonomy === $field['slug'] ) {
							return $field;
						}
					}
				}
			}
		}

		return false;
	}

	public function get_taxonomies_by_type( $type ) {
		$taxonomies = $this->get_all_options();

		$results = [];
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! empty( $taxonomy['slug'] ) && $type === $taxonomy['type'] ) {
					$results[] = $taxonomy['slug'];
				}
			}
		}

		return $results;
	}

	public function get_taxonomies_options_by_type( $type ) {
		$taxonomies = $this->get_all_options();

		$results = [];
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! empty( $taxonomy['slug'] ) && $type === $taxonomy['type'] ) {
					$results[] = $taxonomy;
				}
			}
		}

		return $results;
	}

	/**
	 * Adjustment needed for the new Fields Builder
	 * --------------------------------------------
	 *
	 * @from 1.1.2
	 */
	public function correct_taxonomies_terms_order_for_fields_builder_change() {
		$options = $this->options;
		if ( ! empty( $options ) ) {
			foreach ( $options as $group => $fields ) {
				if ( ! empty( $fields ) ) {
					foreach ( $fields as $index => $taxonomy ) {
						$terms = get_terms( [
							'taxonomy'   => $taxonomy['slug'],
							'hide_empty' => false
						] );

						$formatted_terms = [];
						if ( ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								if ( $term->parent !== 0 ) {
									$parent_taxonomy = get_term_meta( $term->term_id, 'parent_taxonomy', true );
									if ( ! empty( $parent_taxonomy ) ) {
										$parent = get_term_by( 'id', $term->parent, $parent_taxonomy );
										if ( ! is_wp_error( $parent ) && ! empty( $parent ) ) {
											update_term_meta( $term->term_id, 'parent_name', $parent->name );
											update_term_meta( $term->term_id, 'parent_slug', $parent->slug );
										}
									}
								}
								$formatted_term = $taxonomy['slug'] . '-' . $term->term_id;
								if ( ! in_array( $formatted_term, $formatted_terms ) ) {
									$formatted_terms[] = $formatted_term;
								}
							}
						}
						$options[ $group ][ $index ]['term_ids'] = $formatted_terms;
					}
				}
			}
		}

		$this->set_options( $options );
	}

	public function get_taxonomies_by_parent( $parent ) {
		$taxonomies = [];
		if ( ! empty( $this->get_all_options() ) ) {
			foreach ( $this->get_all_options() as $tax ) {
				if ( $tax['parent'] === $parent ) {
					$taxonomies[] = $tax;
				}
			}
		}

		return $taxonomies;
	}

}
