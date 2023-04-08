<?php

/**
 * Class PromotionFunctionTest
 * @group product
 */
class PromotionFunctionTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_available_promotion_types() {
		// test promotion types.
		$default_types  = lisfinity_available_promotion_types();
		$types_to_check = [ 'product', 'addon', 'premium_profile' ];

		$this->assertEquals( array_keys( $default_types ), $types_to_check );

		// test promotion product types.
		$default_product_types  = lisfinity_available_promotion_product_types();
		$product_types_to_check = [
			'home-banner',
			'home-ads',
			'search-keyword',
			'category-featured',
			'bump-color',
			'bump-pin',
			'bump-up'
		];

		$this->assertEquals( array_keys( $default_product_types ), $product_types_to_check );

		// test promotion addon types.
		$default_addon_types  = lisfinity_available_promotion_addon_types();
		$addon_types_to_check = [ 'addon-image', 'addon-video', 'addon-docs' ];

		$this->assertEquals( array_keys( $default_addon_types ), $addon_types_to_check );

		// test listed promotions.
		$listed_promotion_types          = lisfinity_set_all_promotion_types();
		$listed_promotion_types_to_check = array_merge( $default_product_types, $default_addon_types, [ 'profile-premium' => __( 'Profile Premium', 'lisfinity-core' ) ] );

		$this->assertEquals( $listed_promotion_types, $listed_promotion_types_to_check );

	}

}
