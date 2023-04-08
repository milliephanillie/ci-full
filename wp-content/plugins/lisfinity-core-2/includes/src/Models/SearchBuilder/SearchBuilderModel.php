<?php
/**
 * Custom Field Groups administration
 *
 * @author pebas
 * @package custom-fields/groups
 * @version 1.0.0
 */

namespace Lisfinity\Models\SearchBuilder;

use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel as TaxonomiesAdminModel;

/**
 * Class SearchBuilderModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class SearchBuilderModel {

	protected $options;

	protected $fields;

	public $meta_fields = [
		'price',
	];

	public function __construct() {
		$this->set_options();
		$this->set_fields();
	}

	/**
	 * Add admin menu page
	 * -------------------
	 */
	public function admin_menu() {
		$this->create_search_builder_menu_page();
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
	 * Configure default search builder fields and groups
	 * --------------------------------------------------
	 *
	 * @return array
	 */
	public function set_options() {
		$taxonomy_admin_model = new TaxonomiesAdminModel();
		$options              = [
			'keyword'  => [
				[ 'single_name' => __( 'Title', 'lisfinity-core' ), 'slug' => 'title' ],
				[ 'single_name' => __( 'Description', 'lisfinity-core' ), 'slug' => 'description' ],
				[ 'single_name' => __( 'Category', 'lisfinity-core' ), 'slug' => 'category' ],
				[ 'single_name' => __( 'Authors', 'lisfinity-core' ), 'slug' => 'authors' ],
			],
			'taxonomy' => $this->prepare_taxonomies_form_options( $taxonomy_admin_model->get_options() ),
			'meta'     => [
				[ 'single_name' => __( 'Price', 'lisfinity-core' ), 'slug' => 'price' ],
			],
		];

		$this->options = $options;

		return $this->options;
	}

	/**
	 * Prepare taxonomies for displaying on a search builder form
	 * ----------------------------------------------------------
	 *
	 * @param $taxonomies
	 *
	 * @return array
	 */
	private function prepare_taxonomies_form_options( $taxonomies ) {
		if ( empty( $taxonomies ) ) {
			return $taxonomies;
		}

		$prepared = [];
		foreach ( $taxonomies as $group_label => $group ) {
			if ( ! empty( $group ) ) {
				foreach ( $group as $taxonomy ) {
					$prepared[] = $taxonomy;
				}
			}
		}

		return $prepared;
	}

	/**
	 * Get all available fields
	 * ------------------------
	 *
	 * @return mixed
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Set default fields
	 * ------------------
	 *
	 * @return mixed|void
	 */
	public function set_fields() {
		return $this->fields = get_option( 'lisfinity--search-builder-fields' );
	}

	public function import_fields( $fields ) {
		return $this->fields = update_option( 'lisfinity--search-builder-fields', $fields );
	}

	/**
	 * Create admin page for search builder page
	 * -----------------------------------------
	 *
	 */
	public function create_search_builder_menu_page() {
		if ( \LisfinityBase::GetRegisterInfo() ) {
			add_menu_page(
				__( 'Search Builder', 'lisfinity-core' ),
				__( 'Search Builder', 'lisfinity-core' ),
				'manage_options',
				'search-builder',
				[ $this, 'menu_settings' ],
				'dashicons-code-standards',
				26
			);
		}
	}

	/**
	 * Get submenu display
	 * -------------------
	 */
	public function menu_settings() {
		$screen  = get_current_screen();
		$options = $this->get_options();

		$args = [
			'options' => $options,
		];

		include lisfinity_get_template_part( 'builder', 'admin/search-builder', $args );
	}

}
