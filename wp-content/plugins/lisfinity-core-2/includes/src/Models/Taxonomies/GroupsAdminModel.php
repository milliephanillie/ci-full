<?php
/**
 * Custom Field Groups administration
 *
 * @author pebas
 * @package custom-fields/groups
 * @version 1.0.0
 */

namespace Lisfinity\Models\Taxonomies;

/**
 * Class GroupsAdminModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class GroupsAdminModel {

	protected $fields;

	protected $options;

	public function __construct() {
		// delete_option( 'lisfinity_groups' );
		$this->options = get_option( 'lisfinity_groups' );
		if ( ! is_array( $this->options ) ) {
			$this->options = [];
		}
		//use below to reset array keys.
		$this->options = array_values( $this->options );
		update_option( 'lisfinity_groups', $this->options );

		$this->set_fields();
	}

	/**
	 * Add admin menu page
	 * -------------------
	 */
	public function admin_menu() {
		$this->create_groups_pages();
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
	 * Set terms form fields
	 * --------------------------
	 *
	 * @return array
	 */
	protected function set_fields() {
		$count  = 0;
		$fields = [
			// fields | general.
			'general' => [
				'single_name' => [
					'key'         => $count ++,
					'label'       => __( 'Singular Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'The singular version of the field group name so it can be used where appropriate.', 'lisfinity-core' ),
					'type'        => 'text',
					'placeholder' => '',
					'additional'  => [
						'class' => 'w-48%',
					],
					'required'    => true,
				],
				'plural_name' => [
					'key'         => $count ++,
					'label'       => __( 'Plural Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'The plural version of the field group name so it can be used where appropriate.', 'lisfinity-core' ),
					'type'        => 'text',
					'placeholder' => '',
					'additional'  => [
						'class' => 'w-48%',
					],
					'required'    => true,
				],
				'slug'        => [
					'key'         => $count ++,
					'label'       => __( 'Slug', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Slug name is automatically created based on the single name if left empty.', 'lisfinity-core' ),
					'type'        => 'text',
					'placeholder' => '',
					'additional'  => [
						'class' => 'w-48%',
					],
				],
				'hidden'      => [
					'key'         => $count ++,
					'label'       => __( 'Hidden by Default', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Useful when you wish to hide the products from this category by default. Example 18+ categories or similar.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => __( 'No', 'lisfinity-core' ),
						'yes' => __( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-48%',
					],
				],
				'premium'     => [
					'key'         => $count ++,
					'label'       => __( 'For Premium Users Only', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Listings from this category will be available for premium/member users only.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => __( 'No', 'lisfinity-core' ),
						'yes' => __( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-48%',
					],
				],
				'description' => [
					'key'         => $count ++,
					'label'       => __( 'Description', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Used to explain the purpose of the field group. For what is it used etc.', 'lisfinity-core' ),
					'type'        => 'textarea',
					'placeholder' => '',
					'additional'  => [
						'class' => 'w-48%',
					],
				],
				'bg_image'    => [
					'key'         => $count ++,
					'label'       => __( 'Background Image', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'file',
					'type_filter' => 'image',
					'additional'  => [
						'class' => 'w-48%',
					],
				],
				'icon'        => [
					'key'         => $count ++,
					'label'       => __( 'Choose Icon', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'file',
					'type_filter' => 'image',
					'buttonLabel' => __( 'Select Icon', 'lisfinity-core' ),
					'additional'  => [
						'class' => 'w-48%',
					],
				],
			],
		];

		$this->fields = apply_filters( 'lisfinity__custom_groups_fields', $fields );

		return $this->fields;
	}

	/**
	 * Get list of available options
	 * -----------------------------
	 *
	 * @return array
	 */
	public function get_options( $include_common = false ) {
		if ( $include_common ) {
			$options[] = [
				"single_name" => "Common",
				"plural_name" => "Commons",
				"slug"        => "common",
			];
			$options   = array_merge( $options, $this->options );
		} else {
			$options = $this->options;
		}

		return $options;
	}

	/**
	 * Configure the options. This is useful  for
	 * fields importing
	 * ------------------------------------------
	 *
	 * @param $options
	 */
	public function set_options( $options ) {
		update_option( 'lisfinity_groups', $options );
	}

	/**
	 * Get options for a given taxonomy group
	 * --------------------------------------
	 *
	 * @param $group
	 *
	 * @return mixed
	 */
	public function get_group_options( $group ) {
		$options = $this->get_options();
		if ( ! empty( $options ) ) {
			foreach ( $options as $option ) {
				if ( $option['slug'] === $group ) {
					return $option;
				}
			}
		}
	}

	/**
	 * Create admin page for each available group
	 * ------------------------------------------
	 *
	 * @return bool
	 */
	public function create_groups_pages() {
		$groups = $this->get_options();

		if ( empty( $groups ) ) {
			return false;
		}

		foreach ( $groups as $group ) {
			add_submenu_page(
				'custom-fields',
				sprintf( __( '%s Fields', 'lisfinity-core' ), $group['single_name'] ),
				sprintf( __( '%s Fields', 'lisfinity-core' ), $group['plural_name'] ),
				'manage_options',
				"custom-fields-{$group['slug']}",
				[ $this, 'group_settings' ]
			);
		}
	}

	/**
	 * Get submenu display
	 * -------------------
	 */
	public function group_settings() {
		$screen              = get_current_screen();
		$options             = $this->get_options();
		$options_list        = $this->get_fields();
		$fields_builder      = translate( 'Fields Builder', 'lisfinity-core' );
		$fields_builder_slug = sanitize_title( $fields_builder );
		$fields_group        = str_replace( "{$fields_builder_slug}_page_custom-fields-", '', $screen->id );

		$args             = [
			'options'      => $options,
			'fields'       => $options_list,
			'fields_group' => $fields_group,
		];
		$taxonomies_model = new TaxonomiesAdminModel();
		$taxonomies       = $taxonomies_model->get_taxonomies_by_group( $args['fields_group'], true, __( 'No Parent', 'lisfinity-core' ) );
		ksort( $taxonomies );
		$args['taxonomies'] = $taxonomies;

		include lisfinity_get_template_part( 'custom-fields', 'admin/taxonomies', $args );
	}

	/**
	 * Get the slugs of all available groups
	 * -------------------------------------
	 *
	 * @param string $prefix
	 *
	 * @return array
	 */
	public function get_groups_slugs( $prefix = '' ) {
		$options          = $this->get_options();
		$filtered_options = [];

		if ( empty( $options ) ) {
			return [];
		}

		foreach ( $options as $option ) {
			$filtered_options[] = $prefix . $option['slug'];
		}

		return $filtered_options;
	}

	/**
	 * Format available options for use in a select field
	 * --------------------------------------------------
	 *
	 * @param bool $multiple
	 * @param bool $include_common
	 *
	 * @return mixed
	 */
	public function format_options_for_select( $multiple = false, $include_common = false ) {
		$select = [];
		if ( ! $multiple ) {
			$select[''] = __( 'Any Type', 'lisfinity-core' );
		}
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return $select;
		}

		if ( $include_common ) {
			$select['common'] = __( 'Common', 'lisfinity-core' );
		}

		foreach ( $options as $option ) {
			$select[ $option['slug'] ] = $option['single_name'];
		}

		return $select;
	}

	/**
	 * Get all groups with taxonomies attached to it
	 * ---------------------------------------------
	 *
	 * @return array
	 */
	public function get_groups_with_taxonomies() {
		$taxonomy_model        = new TaxonomiesAdminModel();
		$groups                = ! empty( $this->get_options() ) ? array_column( $this->get_options(), 'slug' ) : [];
		$groups_and_taxonomies = [];
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				if ( isset( $taxonomy_model->get_options()[ $group ] ) ) {
					$groups_and_taxonomies[ $group ] = $taxonomy_model->get_options()[ $group ];
				} else {
					$groups_and_taxonomies[ $group ] = [];
				}
			}
		}

		return $groups_and_taxonomies;
	}

	/**
	 * Get all groups with taxonomies slugs attached to it
	 * ---------------------------------------------------
	 *
	 * @return array
	 */
	public function get_groups_with_taxonomies_slugs() {
		$taxonomy_model        = new TaxonomiesAdminModel();
		$groups                = ! empty( $this->get_options() ) ? array_column( $this->get_options(), 'slug' ) : [];
		$groups_and_taxonomies = [];
		$groups[]              = 'common';
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				if ( isset( $taxonomy_model->get_options()[ $group ] ) ) {
					$current = $taxonomy_model->get_options()[ $group ];
					foreach ( $current as $tax ) {
						if ( ! empty( $tax['slug'] ) ) {
							$groups_and_taxonomies[ $group ][] = $tax['slug'];
						}
					}
				} else {
					$groups_and_taxonomies[ $group ] = [];
				}
			}
		}

		return $groups_and_taxonomies;
	}

	public function get_groups_by_key() {
		$groups = $this->get_options();

		$organized = [];
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$organized[ $group['slug'] ] = $group;
			}
		}

		return $organized;
	}

}
