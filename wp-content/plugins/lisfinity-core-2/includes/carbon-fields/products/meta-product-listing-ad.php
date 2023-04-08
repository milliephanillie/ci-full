<?php
/**
 * Meta Product Listing | Ad.
 *
 * Here are defined all ads fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-product
 * @version 1.0.0
 */

use Carbon_Fields\Field;

if ( ! function_exists( 'lisfinity_product_details_ad' ) ) {
	/**
	 * Expand default product meta details with Ad product type options
	 * ----------------------------------------------------------------
	 *
	 * @param array $fields Carbon fields array that we're expanding.
	 *
	 * @return array
	 */
	function lisfinity_product_details_ad( $fields ) {
		$new_fields = [
			Field::make( 'select', 'product-price-type', __( 'Price Type', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_options( 'lisfinity_get_chosen_price_types' )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Choose price type of the product from the list of available ones.', 'lisfinity-core' ) ),
			Field::make( 'radio', 'product-price-sell-on-site', __( 'Sell On Site?', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_options(
					[
						0 => __( 'No', 'lisfinity-core' ),
						1 => __( 'Yes', 'lisfinity-core' ),
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
						[
							'field'   => 'product-price-type',
							'value'   => [ 'fixed', 'auction', 'negotiable' ],
							'compare' => 'IN',
						],
					]
				)
				->set_help_text( __( 'Choose whether you wish to sell product through site or use price as information only.', 'lisfinity-core' ) ),
			// if auction type.
			Field::make( 'separator', 'auction_separator', __( 'Auction Details', 'lisfinity-core' ) )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-price-type',
							'value'   => 'auction',
							'compare' => '=',
						],
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
					]
				),
			Field::make( 'select', 'product-auction-status', __( 'Auction Status', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_options(
					[
						'active'   => __( 'Active', 'lisfinity-core' ),
						'upcoming' => __( 'Upcoming', 'lisfinity-core' ),
						'expired'  => __( 'Expired', 'lisfinity-core' ),
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-price-type',
							'value'   => 'auction',
							'compare' => '=',
						],
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the status of the auction. This field will be automatically set on product update.', 'lisfinity-core' ) ),
			Field::make( 'date_time', 'product-auction-starts', __( 'Auction Start Time', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_storage_format( 'U' )
				->set_picker_options(
					[
						'minDate'   => 'today',
						'time_24hr' => true,
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-price-type',
							'value'   => 'auction',
							'compare' => '=',
						],
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the start time of the auction', 'lisfinity-core' ) ),
			Field::make( 'date_time', 'product-auction-ends', __( 'Auction End Time', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_storage_format( 'U' )
				->set_picker_options(
					[
						'minDate'   => 'today',
						'time_24hr' => true,
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-price-type',
							'value'   => 'auction',
							'compare' => '=',
						],
						[
							'field'   => 'product-type',
							'value'   => 'ad',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the end time of the auction.', 'lisfinity-core' ) ),
			Field::make( 'text', 'product-auction-start-price', __( 'Auction Start Price', 'lisfinity-core' ) )
				->set_attribute( 'type', 'number' )
				->set_attribute( 'min', 1 )
			    ->set_width( '50%' )
			    ->set_conditional_logic(
				     [
					     [
						     'field'   => 'product-price-type',
						     'value'   => 'auction',
						     'compare' => '=',
					     ],
					     [
						     'field'   => 'product-type',
						     'value'   => 'ad',
						     'compare' => '=',
					     ],
				     ]
			     )
			     ->set_help_text( __( 'Set the starting price of the auction.', 'lisfinity-core' ) ),
		];

		$fields = array_merge( $fields, $new_fields );

		return apply_filters( 'lisfinity__product_details_ad', $fields );
	}

	add_filter( 'lisfinity__product_meta_fields_details', 'lisfinity_product_details_ad' );
}
