<?php

use WC_Product_Listing as Listing;

class ProductListingTest extends WP_UnitTestCase {

	public function test_get_type() {
        $listing = new Listing();

        $this->assertEquals( Listing::$type, $listing->get_type() );

	}
}