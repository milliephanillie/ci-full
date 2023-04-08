<?php
/**
 * Custom Terms administration
 *
 * @author pebas
 * @package custom-fields/terms
 * @version 1.0.0
 */

namespace Lisfinity\Models\Taxonomies;

/**
 * Class TermsAdminModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class TermsAdminModel {

	protected $fields;

	public function __construct() {
		$this->set_fields();
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
				'name'       => [
					'key'         => $count ++,
					'label'       => __( 'Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'The singular version of the field group name so it can be used where appropriate.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
					'required'    => true,
				],
				'slug'       => [
					'key'         => $count ++,
					'label'       => __( 'Slug', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Slug name is automatically created based on the single name if left empty.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'short_name' => [
					'key'         => $count ++,
					'label'       => __( 'Short Name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Used on the ad box in order to preserve space on it.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full',
					],
					'conditional' => [ 'type', 'location' ],
				],
				'parent'     => [
					'key'         => $count ++,
					'label'       => __( 'Parent', 'lisfinity-core' ),
					'value'       => '',
					'description' => '',
					'type'        => 'select',
					'select_type' => 'terms',
					'choices'     => [],
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'premium'     => [
					'key'         => $count ++,
					'label'       => __( 'For Premium Users Only', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Listings from this term will be available for premium users only.', 'lisfinity-core' ),
					'type'        => 'select',
					'choices'     => [
						'no'  => __( 'No', 'lisfinity-core' ),
						'yes' => __( 'Yes', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full',
					],
				],
				'bg_image'   => [
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
				'icon'       => [
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

		$this->fields = apply_filters( 'lisfinity__custom_terms_fields', $fields );

		return $this->fields;
	}

	/**
	 * Update taxonomy of the term
	 * ---------------------------
	 *
	 * @param $term_id
	 * @param $taxonomy
	 *
	 * @return false|int
	 */
	public function update_term_taxonomy( $term_id, $taxonomy ) {
		global $wpdb;
		$update = $wpdb->update(
			$wpdb->term_taxonomy,
			[ 'taxonomy' => $taxonomy ],
			[ 'term_taxonomy_id' => $term_id ],
			[ '%s' ],
			[ '%d' ]
		);

		return $update;
	}

}
