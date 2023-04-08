<?php

namespace Lisfinity\Models\ProductImporter;

use Lisfinity\Abstracts\Importer;

class General extends Importer {

	protected $fields = [
		'_product-category'   => 'category',
		'_product-status'     => 'text',
		'_product-owner'      => 'text',
		'_product-listed'     => 'date',
		'_product-expiration' => 'date',
	];

	protected function set_name() {
		return __( 'Ad General Settings', 'lisfinity-core' );
	}

	protected function set_fields() {
		$this->addon->add_field( '_product-category', __( 'Product Category', 'lisfinity-core' ), 'radio', lisfinity_get_product_groups(), __( 'Choose the product category from the list or set a new one from the import file by clicking on Set with XPath', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-status', __( 'Product Status', 'lisfinity-core' ), 'radio', [
			'active'  => __( 'Active', 'lisfinity-core' ),
			'expired' => __( 'Expired', 'lisfinity-core' ),
			'sold'    => __( 'Sold', 'lisfinity-core' ),
		], __( 'Choose the product status', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-owner', __( 'Product Owner', 'lisfinity-core' ), 'text', null, __( 'Set the product Author ID', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-listed', __( 'Product Listed Date', 'lisfinity-core' ), 'text', null, __( 'Import the date in any strtotime compatible format', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-expiration', __( 'Product Expiration Date', 'lisfinity-core' ), 'text', null, __( 'Import the date in any strtotime compatible format', 'lisfinity-core' ), false, null );
	}
}
