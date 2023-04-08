<?php
/**
 * Meta Product Listing | Discount.
 *
 * Here are defined all discount fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-product
 * @version 1.0.0
 */

use Carbon_Fields\Field;

if ( ! function_exists( 'lisfinity_product_details_discount' ) ) {
	/**
	 * Expand default product meta details with Ad product type options
	 * ----------------------------------------------------------------
	 *
	 * @param array $fields Carbon fields array that we're expanding.
	 *
	 * @return array
	 */
	function lisfinity_product_details_discount( $fields ) {
		$new_fields  = [
			Field::make( 'select', 'product-discount-type', __( 'Discount Type', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_options(
					[
						'deal'   => __( 'Deal', 'lisfinity-core' ),
						'coupon' => __( 'Coupon', 'lisfinity-core' ),
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Choose price type of the discount from the list of available ones.', 'lisfinity-core' ) ),
			Field::make( 'select', 'product-discount-status', __( 'Discount Status', 'lisfinity-core' ) )
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
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the status of the discount. This field will be automatically set on product update.', 'lisfinity-core' ) ),
			Field::make( 'date', 'product-discount-starts', __( 'Discount Start Time', 'lisfinity-core' ) )
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
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the start time of the discount.', 'lisfinity-core' ) ),
			Field::make( 'date_time', 'product-discount-ends', __( 'Discount End Time', 'lisfinity-core' ) )
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
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the end time of the discount.', 'lisfinity-core' ) ),
			// Discount type | Coupon.
			Field::make( 'separator', 'coupon_separator', __( 'Coupon Details', 'lisfinity-core' ) )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'coupon',
							'compare' => '=',
						],
					]
				),
			Field::make( 'select', 'product-coupon-type', __( 'Discount Type', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_options(
					[
						'code'  => __( 'Code', 'lisfinity-core' ),
						'link'  => __( 'Link', 'lisfinity-core' ),
						'image' => __( 'Print Image', 'lisfinity-core' ),
					]
				)
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'coupon',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Choose the type of the coupon you wish to create.', 'lisfinity-core' ) ),
			Field::make( 'text', 'product-coupon-code', __( 'Coupon Code', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'coupon',
							'compare' => '=',
						],
						[
							'field'   => 'product-coupon-type',
							'value'   => 'code',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter coupon code.', 'lisfinity-core' ) ),
			Field::make( 'text', 'product-coupon-link', __( 'Coupon Link', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'coupon',
							'compare' => '=',
						],
						[
							'field'   => 'product-coupon-type',
							'value'   => 'link',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter coupon link.', 'lisfinity-core' ) ),
			Field::make( 'image', 'product-coupon-image', __( 'Coupon Print Image', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '50%' )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'coupon',
							'compare' => '=',
						],
						[
							'field'   => 'product-coupon-type',
							'value'   => 'image',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Upload printable image for the coupon.', 'lisfinity-core' ) ),
			// Discount type | Coupon.
			Field::make( 'separator', 'deal_separator', __( 'Deal Details', 'lisfinity-core' ) )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'deal',
							'compare' => '=',
						],
					]
				),
			Field::make( 'text', 'product-deal-items', __( 'Deal Items', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_attribute( 'type', 'number' )
				->set_attribute( 'min', 1 )
				->set_default_value( 1 )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'deal',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter number of deal vouchers that will be available for purchase. Leave empty for unlimited.', 'lisfinity-core' ) ),
			Field::make( 'text', 'product-deal-minimum-sales', __( 'Deal Minimum Sales', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_attribute( 'type', 'number' )
				->set_attribute( 'min', 0 )
				->set_default_value( 0 )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'deal',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter the minimum number of sales in order for deal to become valid.', 'lisfinity-core' ) ),
			Field::make( 'text', 'product-deal-voucher-expiration', __( 'Deal Voucher Duration', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_width( '33%' )
				->set_attribute( 'type', 'number' )
				->set_attribute( 'min', 0 )
				->set_default_value( 0 )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'discount',
							'compare' => '=',
						],
						[
							'field'   => 'product-discount-type',
							'value'   => 'deal',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter the number of days a voucher will be valid after purchase. Leave 0 for unlimited number of days.', 'lisfinity-core' ) ),
		];

		$fields = array_merge( $fields, $new_fields );

		return apply_filters( 'lisfinity__product_details_discount', $fields );
	}

	add_filter( 'lisfinity__product_meta_fields_details', 'lisfinity_product_details_discount' );
}
