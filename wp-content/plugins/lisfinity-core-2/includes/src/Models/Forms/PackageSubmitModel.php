<?php
/**
 * Form submit fields model
 *
 * @author pebas
 * @package forms/submit
 * @version 1.0.0
 */

namespace Lisfinity\Models\Forms;

use WC_Product_Payment_Package as Payment_Package;

/**
 * Class PackageSubmitModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class PackageSubmitModel {

	protected $fields;

	private $reserved_field_names = [
		'title',
		'description',
		'_price',
		'_sale_price',
	];

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

	public function get_reserved_field_names() {
		$this->reserved_field_names;
	}

	/**
	 * Set terms form fields
	 * --------------------------
	 *
	 * @return array
	 */
	protected function set_fields() {
		$fields = [
			// fields | packages.
			'payments' => [
				'package' => [
					'key'         => 1,
					'label'       => __( 'Choose Package', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Choose payment package.', 'lisfinity-core' ),
					'type'        => 'promotions',
					'product'     => Payment_Package::$type,
				],
			],
		];

		$this->fields = apply_filters( 'lisfinity__packages_form_fields', $fields );

		return $this->fields;
	}

}
