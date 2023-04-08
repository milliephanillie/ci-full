<?php
/**
 * Theme Options.
 *
 * Here, you can register Theme Options using the Carbon Fields library.
 *
 * @link https://carbonfields.net/docs/containers-theme-options/
 *
 * @author peabs
 * @package theme-options
 * @version 1.0.0
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

// Pages / Endpoints.
$lisfinity_other_builders = Container::make( 'theme_options', __( 'Lisfinity Builders', 'lisfinity-core' ) )
                                     ->set_page_menu_position( 30 )
                                     ->add_fields(
	                                     apply_filters(
		                                     'lisfinity__builders_fields',
		                                     [
			                                     Field::make( 'html', 'lisfinity-builders-html' )
			                                          ->set_html( 'lisfinity_builder_help_html' ),
		                                     ]
	                                     ) );

Container::make( 'theme_options', __( 'Multicurrency Builder', 'lisfinity-core' ) )
         ->set_page_parent( $lisfinity_other_builders )
         ->set_page_file( 'builder-options' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__theme_options_fields_currencies',
		         [
			         Field::make( 'html', 'multicurrency-html' )
			              ->set_html( 'lisfinity_currency_help_html' ),
			         Field::make( 'radio', 'multicurrency-enabled', __( 'Enable Multicurrency', 'lisfinity-core' ) )
			              ->set_options( [
				              'yes' => __( 'Yes', 'lisfinity-core' ),
				              'no'  => __( 'No', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( 'no' )
			              ->set_help_text( __( 'Choose if you wish to add more currencies to the site', 'lisfinity-core' ) ),
			         Field::make( 'complex', 'currencies', __( 'Currencies', 'lisfinity-core' ) )
			              ->set_conditional_logic( [
				              [
					              'field'   => 'multicurrency-enabled',
					              'value'   => 'yes',
					              'compare' => '=',
				              ]
			              ] )
			              ->add_fields( 'currency', [
					              Field::make( 'select', 'country', __( 'Choose Currency', 'lisfinity-core' ) )
					                   ->set_options( 'get_woocommerce_currencies' )
					                   ->set_classes( 'crb-select2' )
					                   ->set_help_text( __( 'Choose the currency you wish to add to the list.', 'lisfinity-core' ) ),
					              Field::make( 'text', 'rate', __( 'Currency Rate', 'lisfinity-core' ) )
					                   ->set_help_text( __( 'Enter the current rate of the currency in relation to the default one.', 'lisfinity-core' ) ),
				              ]
			              ),
		         ]
	         ) );

// Pages / Endpoints.
Container::make( 'theme_options', __( 'Testimonials Builder', 'lisfinity-core' ) )
         ->set_page_parent( $lisfinity_other_builders )
         ->add_tab(
	         __( 'General', 'lisfinity-core' ),
	         apply_filters(
		         'lisfinity__theme_options_fields_reviews',
		         [
			         Field::make( 'html', 'business-reviews-verification-html' )
			              ->set_html( 'lisfinity_reviews_help_html' )
			              ->set_conditional_logic( [
				              [
					              'field'   => 'business-reviews-enable',
					              'value'   => 'yes',
					              'compare' => '=',
				              ]
			              ] ),
			         Field::make( 'radio', 'business-reviews-enable', __( 'Enable Testimonials', 'lisfinity-core' ) )
			              ->set_options( [
				              'yes' => __( 'Enable', 'lisfinity-core' ),
				              'no'  => __( 'Disable', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( 'yes' )
			              ->set_help_text( __( 'Choose if you wish to enable testimonials for businesses.', 'lisfinity-core' ) ),
			         Field::make( 'select', 'business-reviews-default-rating', __( 'Default Rating', 'lisfinity-core' ) )
			              ->set_options( [
				              '0' => __( '0', 'lisfinity-core' ),
				              '1' => __( '1', 'lisfinity-core' ),
				              '2' => __( '2', 'lisfinity-core' ),
				              '3' => __( '3', 'lisfinity-core' ),
				              '4' => __( '4', 'lisfinity-core' ),
				              '5' => __( '5', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( '5' )
			              ->set_help_text( __( 'Choose what will be displayed if the business haven\'t been rated yet.', 'lisfinity-core' ) ),
			         Field::make( 'text', 'business-reviews-characters-limit', __( 'Characters Limit', 'lisfinity-core' ) )
			              ->set_attribute( 'type', 'number' )
			              ->set_attribute( 'min', '50' )
			              ->set_attribute( 'max', '300' )
			              ->set_default_value( '300' )
			              ->set_help_text( __( 'Set characters limit for reviews comment. Minimum value is 50 and maximum value is 300', 'lisfinity-core' ) ),
		         ]
	         )
         )
         ->add_tab(
	         __( 'Testimonials Builder', 'lisfinity-core' ),
	         apply_filters(
		         'lisfinity__theme_options_fields_reviews_builder',
		         [
			         Field::make( 'complex', 'business-reviews-options', __( 'Testimonials Builder', 'lisfinity-core' ) )
			              ->add_fields( [
				              Field::make( 'text', 'review-option', __( 'Review Option', 'lisfinity-core' ) )
				                   ->set_help_text( __( 'Type the name of the review option.', 'lisfinity-core' ) )
			              ] )
			              ->set_help_text( __( 'Create your own review criteria for profiles.', 'lisfinity-core' ) )
		         ]
	         )
         );


// Pages / Endpoints.
Container::make( 'theme_options', __( 'Flag/Reports Builder', 'lisfinity-core' ) )
         ->set_page_parent( $lisfinity_other_builders )
         ->add_tab(
	         __( 'General', 'lisfinity-core' ),
	         apply_filters(
		         'lisfinity__theme_options_fields_report',
		         [
			         Field::make( 'select', 'report', __( 'Flag/Report', 'lisfinity-core' ) )
			              ->set_options( [
				              'yes' => __( 'Enable', 'lisfinity-core' ),
				              'no'  => __( 'Disable', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( 'yes' )
			              ->set_help_text( __( 'Allow ads flagging/reporting', 'lisfinity-core' ) ),
			         Field::make( 'select', 'report-reasons-enable', __( 'Flag/Report Reasons', 'lisfinity-core' ) )
			              ->set_options( [
				              'yes' => __( 'Enable', 'lisfinity-core' ),
				              'no'  => __( 'Disable', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( 'yes' )
			              ->set_help_text( __( 'Allow customers to choose a reason to report an ad? If nothing is created you will receive just a description of the report.', 'lisfinity-core' ) ),
			         Field::make( 'complex', 'report-reasons', __( 'Product Report Reasons', 'lisfinity-core' ) )
			              ->set_conditional_logic( [
				              [
					              'field'   => 'report-reasons-enable',
					              'value'   => 'yes',
					              'compare' => '=',
				              ]
			              ] )
			              ->add_fields( [
				              Field::make( 'text', 'report-reason', __( 'Report Reason', 'lisfinity-core' ) )
				                   ->set_help_text( __( 'Enter a possible reason for reporting a product', 'lisfinity-core' ) )
			              ] )
		         ]
	         )
         );


