<?php
/**
 * Meta Product Listing | Rental.
 *
 * Here are defined all Rental fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-product
 * @version 1.0.0
 */

use Carbon_Fields\Field;

if ( ! function_exists( 'lisfinity_product_details_rental' ) ) {
	/**
	 * Expand default product meta details with Rental product type options
	 * ----------------------------------------------------------------
	 *
	 * @param array $fields Carbon fields array that we're expanding.
	 *
	 * @return array
	 */
	function lisfinity_product_details_rental( $fields ) {
		$new_fields = [
			Field::make( 'complex', 'product-rental-url', __( 'Rental Providers', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'rent',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter booking providers.', 'lisfinity-core' ) )
				->add_fields(
					[
						Field::make( 'text', 'rental-provider-name', __( 'Rental Provider Name', 'lisfinity-core' ) )
							->set_help_text( __( 'Enter the name of your booking provider. ( Booking, Resurva, etc. )', 'lisfinity-core' ), 'lisfinity-core' ),
						Field::make( 'text', 'rental-provider-link', __( 'Rental Provider Link', 'lisfinity-core' ) )
							->set_help_text( __( 'Enter the link to your rental provider', 'lisfinity-core' ), 'lisfinity-core' ),
					]
				),
		];

		$fields = array_merge( $fields, $new_fields );

		return apply_filters( 'lisfinity__product_details_rental', $fields );
	}

	add_filter( 'lisfinity__product_meta_fields_details', 'lisfinity_product_details_rental' );
}
