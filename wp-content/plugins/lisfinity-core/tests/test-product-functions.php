<?php

/**
 * Class ProductFunctionsTest
 * @group product
 */
class ProductFunctionsTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_default_product_types() {
		$default_types  = lisfinity_get_default_product_types();
		$types_to_check = [ 'ad', 'discount', 'event', 'rent' ];

		$this->assertEquals( $default_types, $types_to_check );

		$chosen_types = lisfinity_get_option( 'product-types' );

		$this->assertEquals( $chosen_types, $types_to_check );
	}

	public function test_lisfinity_product_statuses() {
		$statuses          = lisfinity_set_product_statuses();
		$statuses_to_check = [ 'active', 'expired', 'sold' ];

		$this->assertEquals( $statuses, $statuses_to_check );

		$statuses          = lisfinity_get_formatted_product_statuses();
		$statuses_to_check = [
			'active'  => 'Active',
			'expired' => 'Expired',
			'sold'    => 'Sold',
		];

		$this->assertEquals( $statuses, $statuses_to_check );
	}

	public function test_lisfinity_google_api_key() {
		carbon_set_theme_option( 'map-api', '123' );
		$api_key = lisfinity_get_option( 'map-api' );

		$this->assertEquals( '123', $api_key );
	}

	public function test_lisfinity_default_map_location() {
		$position = lisfinity_format_map_default_position();

		$this->assertEquals( [ 40.346544, - 101.645507, 8 ], $position );
	}

	public function test_default_price_types() {
		$default_types  = lisfinity_get_default_price_types();
		$types_to_check = [ 'fixed', 'negotiable', 'auction', 'price_on_call', 'free' ];

		$this->assertEquals( $default_types, $types_to_check );

		$chosen_types = lisfinity_get_option( 'product-price-types' );

		$this->assertEquals( $chosen_types, $types_to_check );
	}

}
