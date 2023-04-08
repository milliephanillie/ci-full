<?php

namespace Lisfinity\Models\ProductImporter;

use Lisfinity\Abstracts\Importer;

class Details extends Importer {

	protected $fields = [
		'_price'                       => 'price',
		'_sale_price'                  => 'price',
		'_product-price-type'          => 'text',
		'_product-price-sell-on-site'  => 'text',
		'_product-auction-status'      => 'text',
		'_product-auction-starts'      => 'date',
		'_product-auction-ends'        => 'date',
		'_product-auction-start-price' => 'price',
	];

	protected function set_name() {
		return __( 'Ad Detailed Settings', 'lisfinity-core' );
	}

	protected function set_fields() {
		$this->addon->add_field( '_price', __( 'Regular Price', 'lisfinity-core' ), 'text', null, __( 'Set price of the ad', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_sale_price', __( 'Sale Price', 'lisfinity-core' ), 'text', null, __( 'Set the sale price of the ad', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-price-type', __( 'Price Type', 'lisfinity-core' ), 'radio', lisfinity_get_chosen_price_types(), __( 'Choose price type of the ad', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-price-sell-on-site', __( 'Sell On Site', 'lisfinity-core' ), 'radio', [
			0  => __( 'No', 'lisfinity-core' ),
			1 => __( 'Yes', 'lisfinity-core' ),
		], __( 'Choose whether you wish to sell a product through this site.', 'lisfinity-core' ), false, null );

		// auction details.
		$this->addon->add_field( 'auction_details', __( 'Auction Details', 'lisfinity-core' ), 'title', null, null, false, null );
		$this->addon->add_field( '_product-auction-status', __( 'Auction Status', 'lisfinity-core' ), 'radio', [
			'active'   => __( 'Active', 'lisfinity-core' ),
			'upcoming' => __( 'Upcoming', 'lisfinity-core' ),
			'expired'  => __( 'Expired', 'lisfinity-core' ),
		], __( 'Set status of the auction. This will be automatically set on the product update', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-auction-starts', __( 'Auction Start Time', 'lisfinity-core' ), 'text', null, __( 'Import the date in any strtotime compatible format', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-auction-ends', __( 'Auction End Time', 'lisfinity-core' ), 'text', null, __( 'Import the date in any strtotime compatible format', 'lisfinity-core' ), false, null );
		$this->addon->add_field( '_product-auction-start-price', __( 'Auction Start Price', 'lisfinity-core' ), 'text', null, __( 'Set the starting price of the auction.', 'lisfinity-core' ), false, null );
	}
}
