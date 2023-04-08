<?php
/**
 * Meta Product Listing | Event.
 *
 * Here are defined all Event fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-product
 * @version 1.0.0
 */

use Carbon_Fields\Field;

if ( ! function_exists( 'lisfinity_product_details_event' ) ) {
	/**
	 * Expand default product meta details with Rental product type options
	 * ----------------------------------------------------------------
	 *
	 * @param array $fields Carbon fields array that we're expanding.
	 *
	 * @return array
	 */
	function lisfinity_product_details_event( $fields ) {
		$new_fields = [
			Field::make( 'select', 'product-event-status', __( 'Event Status', 'lisfinity-core' ) )
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
							'value'   => 'event',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the status of the event. This field will be automatically set on product update.', 'lisfinity-core' ) ),
			Field::make( 'date_time', 'product-event-starts', __( 'Event Start Time', 'lisfinity-core' ) )
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
							'value'   => 'event',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the start time of the event.', 'lisfinity-core' ) ),
			Field::make( 'date_time', 'product-event-ends', __( 'Event End Time', 'lisfinity-core' ) )
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
							'value'   => 'event',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Set the end time of the event.', 'lisfinity-core' ) ),
			Field::make( 'complex', 'product-event-url', __( 'Event Ticket Providers', 'lisfinity-core' ) )
				->set_visible_in_rest_api( true )
				->set_conditional_logic(
					[
						[
							'field'   => 'product-type',
							'value'   => 'event',
							'compare' => '=',
						],
					]
				)
				->set_help_text( __( 'Enter event ticket providers.', 'lisfinity-core' ) )
				->add_fields(
					[
						Field::make( 'text', 'event-provider-name', __( 'Event Provider Name', 'lisfinity-core' ) )
							->set_help_text( __( 'Enter the name of your event provider. ( TicketsNow, TicketMaster, etc. )', 'lisfinity-core' ), 'lisfinity-core' ),
						Field::make( 'text', 'event-provider-link', __( 'Event Provider Link', 'lisfinity-core' ) )
							->set_help_text( __( 'Enter the link to your event provider', 'lisfinity-core' ), 'lisfinity-core' ),
					]
				),
		];

		$fields = array_merge( $fields, $new_fields );

		return apply_filters( 'lisfinity__product_details_event', $fields );
	}

	add_filter( 'lisfinity__product_meta_fields_details', 'lisfinity_product_details_event' );
}
