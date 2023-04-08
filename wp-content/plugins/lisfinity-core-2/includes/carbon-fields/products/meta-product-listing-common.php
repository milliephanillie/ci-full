<?php
/**
 * Meta Product Listing Common.
 *
 * Here are defined all common fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-product
 * @version 1.0.0
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;


// Meta Product / Fields.
Container::make( 'post_meta', 'product_information1', __( 'Product Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'product' )
         ->set_priority( 'high' )
	// tab | general information.
	     ->add_tab(
		__( 'General', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__product_meta_fields_general',
			[
				Field::make( 'radio', 'members-only', __( 'Visible to Members Only?', 'lisfinity-core' ) )
				     ->set_options( [
					     0 => __( 'No', 'lisfinity-core' ),
					     1 => __( 'Yes', 'lisfinity-core' ),
				     ] )
				     ->set_default_value( false ),
				Field::make( 'hidden', 'terms', __( 'Terms Agreement', 'lisfinity-core' ) )
				     ->set_default_value( true ),
				Field::make( 'select', 'product-type', __( 'Product Type', 'lisfinity-core' ) )
				     ->set_options( 'lisfinity_get_chosen_product_types' )
				     ->set_classes( [ 'hidden' ] )
				     ->set_help_text( __( 'Choose listing type you wish to create from the list of predefined ones.', 'lisfinity-core' ) )
				     ->set_width( '33' ),
				Field::make( 'select', 'product-category', __( 'Product Category Type', 'lisfinity-core' ) )
				     ->set_options( 'lisfinity_get_product_groups' )
				     ->set_help_text( __( 'Choose the category type of the product you wish to ad. "Not Specified" will be the only option if you do not have any field groups created.', 'lisfinity-core' ) )
				     ->set_width( '33' ),
				Field::make( 'select', 'product-status', __( 'Product Status', 'lisfinity-core' ) )
				     ->set_options( 'lisfinity_get_formatted_product_statuses' )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Choose listing type you wish to create from the list of predefined ones.', 'lisfinity-core' ) ),
				Field::make( 'textarea', 'product-reject-reason', __( 'Product Rejection Reason', 'lisfinity-core' ) )
				     ->set_width( '100%' )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'product-status',
						     'value'   => 'rejected',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_help_text( __( 'Type the reason for the submission rejection', 'lisfinity-core' ) ),
				Field::make( 'select', 'product-owner', __( 'Product Owner', 'lisfinity-core' ) )
				     ->set_classes( [ 'hidden' ] )
				     ->set_options( 'lisfinity_format_users_select' )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Choose the owner of the product. When the owner is set then that user will be able to edit product from its own dashboard.', 'lisfinity-core' ) ),
				Field::make( 'select', 'product-agent', __( 'Product Agent', 'lisfinity-core' ) )
				     ->set_options( 'lisfinity_format_users_select' )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Choose the owner of the product. When the owner is set then that user will be able to edit product from its own dashboard.', 'lisfinity-core' ) ),
				Field::make( 'select', 'product-business', __( 'Product Business Owner', 'lisfinity-core' ) )
				     ->set_options( call_user_func( 'lisfinity_format_post_select', [ 'post_type' => 'premium_profile' ] ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Choose the owner of the product. When the owner is set then that user will be able to edit product from its own dashboard.', 'lisfinity-core' ) ),
				Field::make( 'date_time', 'product-listed', __( 'Listed Date', 'lisfinity-core' ) )
				     ->set_visible_in_rest_api( true )
				     ->set_storage_format( 'U' )
				     ->set_picker_options(
					     [
						     'time_24hr' => false,
					     ]
				     )
				     ->set_width( '50%' )
				     ->set_help_text( __( 'Choose the expiration date of the product.', 'lisfinity-core' ) ),
				Field::make( 'date_time', 'product-expiration', __( 'Expiration Date', 'lisfinity-core' ) )
				     ->set_visible_in_rest_api( true )
				     ->set_storage_format( 'U' )
				     ->set_picker_options(
					     [
						     'time_24hr' => false,
					     ]
				     )
				     ->set_width( '50%' )
				     ->set_help_text( __( 'Choose the expiration date of the product.', 'lisfinity-core' ) ),
			]
		)
	)
	// tab | Details.
	     ->add_tab(
		__( 'Details', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__product_meta_fields_details',
			[]
		)
	)
	// tab | Address.
	     ->add_tab(
		__( 'Address', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__product_meta_fields_contact',
			[
				Field::make( 'map', 'product-location', __( 'Location', 'lisfinity-core' ) )
				     ->set_position( apply_filters( 'lisfinity__product_meta_fields_default_map_latitude', 40 ), apply_filters( 'lisfinity__product_meta_fields_default_map_longitude', 40 ), apply_filters( 'lisfinity__product_meta_fields_default_map_zoom', 8 ) )
				     ->set_visible_in_rest_api( true )
				     ->set_help_text( __( 'Drag and drop the pin on the map to select product location.', 'lisfinity-core' ) ),
			]
		)
	);

Container::make( 'post_meta', 'product_stats', __( 'Product Stats', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'product' )
         ->add_fields(
	         apply_filters( 'lisfinity_product_stats_meta',
		         [
			         Field::make( 'text', 'product-views', esc_html__( 'Views', 'lisfinity-core' ) )
			              ->set_attribute( 'readOnly', 'true' )
			              ->set_default_value( '0' )
			              ->set_help_text( esc_html__( 'How many times product page has been opened', 'lisfinity-core' ) )
		         ]
	         )
         );

Container::make( 'post_meta', __( 'Product Videos', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'product' )
         ->set_context( 'side' )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__product_meta_fields_additional',
		         [
			         Field::make( 'complex', 'product-videos' )
			              ->add_fields(
				              [
					              Field::make( 'oembed', 'video', __( 'Product Video', 'lisfinity-core' ) )
					                   ->set_visible_in_rest_api( true )
					                   ->set_help_text( __( 'Enter videos for the product.', 'lisfinity-core' ) ),
				              ]
			              ),
			         Field::make( 'complex', 'product-files' )
			              ->add_fields(
				              [
					              Field::make( 'file', 'file', __( 'Product Files', 'lisfinity-core' ) )
					                   ->set_visible_in_rest_api( true )
					                   ->set_type( [ 'png', 'pdf', 'word', 'image' ] )
					                   ->set_help_text( __( 'Enter additional files for the product.', 'lisfinity-core' ) ),
				              ]
			              ),
		         ]
	         )
         );
