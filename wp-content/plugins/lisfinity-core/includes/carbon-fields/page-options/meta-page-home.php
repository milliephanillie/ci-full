<?php
/**
 * Meta Page Options | Homepage.
 *
 * Here are defined all common fields for our custom WooCommerce
 * product types.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-page
 * @version 1.0.0
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

global $lisfinity_options;

Container::make( 'post_meta', __( 'Category Page Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->where( 'post_template', '=', 'lisfinity_archive' )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__category_page_meta_fields',
		         [
			         Field::make( 'select', 'category', __( 'Template for Category', 'lisfinity-core' ) )
			              ->set_options( call_user_func_array( 'lisfinity_format_group_taxonomies_select', [ true ] ) )
			              ->set_help_text( __( 'Choose the category that this page will be used as its template', 'lisfinity-core' ) ),
		         ]
	         )
         );


Container::make( 'post_meta', __( 'Page Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__page_meta_fields',
		         [
			         Field::make( 'select', 'page-header', __( 'Choose Page Header', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_get_post_type_select', 'lisfinity_header' ) )
			              ->set_help_text( __( 'Choose the header that will be displayed on this page specifically or leave empty to use default one', 'lisfinity-core' ) ),
			         Field::make( 'select', 'page-footer', __( 'Choose Page Footer', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_get_post_type_select', 'lisfinity_footer' ) )
			              ->set_help_text( __( 'Choose the footer that will be displayed on this page specifically or leave empty to use default one', 'lisfinity-core' ) ),
		         ]
	         )
         );


// Authentication page settings.
Container::make( 'post_meta', __( 'Page Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->where( 'post_id', 'IN', [
	         get_option( 'lisfinity-options' )['_page-register'] ?? '',
	         get_option( 'lisfinity-options' )['_page-login'] ?? '',
         ] )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__page_meta_fields_authentication',
		         [
			         Field::make( 'radio', 'page-auth-promo', __( 'Display Promoted Ad', 'lisfinity-core' ) )
			              ->set_options( [
				              false => __( 'Display Background Image', 'lisfinity-core' ),
				              true  => __( 'Display Promoted Ad', 'lisfinity-core' ),
			              ] )
			              ->set_help_text( __( 'Choose whether you wish to display promoted ad.', 'lisfinity-core' ) ),
			         Field::make( 'multiselect', 'page-auth-promo-product', __( 'Choose Promoted Product', 'lisfinity-core' ) )
			              ->set_options( 'lisfinity_format_products_select' )
			              ->set_help_text( __( 'Choose the products that will be promoted on this page. Leave empty to promote only those who actually purchased promotion. Product featured image will be used as a background.', 'lisfinity-core' ) ),
			         Field::make( 'image', 'page-auth-bg', __( 'Background Image', 'lisfinity-core' ) )
			              ->set_conditional_logic( [
				              [
					              'field'   => 'page-auth-promo',
					              'value'   => false,
					              'compare' => '=',
				              ]
			              ] )
			              ->set_help_text( __( 'Set the background promo image for the page.', 'lisfinity-core' ) ),
			         Field::make( 'color', 'page-auth-overlay', __( 'Set Image Overlay', 'lisfinity-core' ) )
			              ->set_help_text( __( 'Set overlay of the image that is used as background.', 'lisfinity-core' ) ),
			         Field::make( 'text', 'page-auth-overlay-opacity', __( 'Set Overlay Opacity', 'lisfinity-core' ) )
			              ->set_attribute( 'type', 'number' )
			              ->set_attribute( 'min', 0 )
			              ->set_attribute( 'max', 1 )
			              ->set_attribute( 'step', 0.1 )
			              ->set_default_value( 0.6 )
			              ->set_help_text( __( 'Set the opacity of the overlay used as background. 1 means full color while 0 means full transparent.', 'lisfinity-core' ) ),
		         ]
	         )
         );

// single product page meta options.
$listing_args  = [
	'post_type' => 'product',
	[
		'taxonomy' => 'product_type',
		'field'    => 'name',
		'terms'    => 'listing',
		'operator' => 'IN',
	],
];
$business_args = [
	'post_type'      => \Lisfinity\Models\Users\ProfilesModel::$post_type_name,
	'posts_per_page' => - 1,
];
Container::make( 'post_meta', __( 'Elementor Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->where( 'post_id', 'IN', [
	         get_option( 'lisfinity-options' )['_page-single-listing'] ?? 0,
         ] )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__single_page_meta_fields',
		         [
			         Field::make( 'select', 'elementor-mockup-product', __( 'Elementor Listing Mockup', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_format_post_select', $listing_args ) )
			              ->set_classes( 'crb-select2' )
			              ->set_default_value( 288 )
			              ->set_help_text( __( 'Choose the listing that will be used as the elementor mockup and will allow you to design your own template', 'lisfinity-core' ) ),
		         ]
	         )
         );
Container::make( 'post_meta', __( 'Elementor Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', \Lisfinity\Models\Elements\ElementsGlobalModel::$type )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__single_page_meta_fields',
		         [
			         Field::make( 'select', 'elementor-mockup-product', __( 'Elementor Listing Mockup', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_format_post_select', $listing_args ) )
			              ->set_classes( 'crb-select2' )
			              ->set_default_value( 288 )
			              ->set_help_text( __( 'Choose the listing that will be used as the elementor mockup in widgets that need it and will allow you to design your own template with them.', 'lisfinity-core' ) ),
			         Field::make( 'select', 'elementor-mockup-business', __( 'Elementor Business Profile Mockup', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_format_post_select', $business_args ) )
			              ->set_classes( 'crb-select2' )
			              ->set_default_value( 32 )
			              ->set_help_text( __( 'Choose the business profile that will be used as the elementor mockup and will allow you to design your own template.', 'lisfinity-core' ) ),

		         ]
	         )
         );

Container::make( 'post_meta', __( 'Elementor Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->where( 'post_id', 'IN', [
	         get_option( 'lisfinity-options' )['_page-business'] ?? 0,
	         get_option( 'lisfinity-options' )['_page-business-premium'] ?? 0,
         ] )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__single_business_page_meta_fields',
		         [
			         Field::make( 'select', 'elementor-mockup-business', __( 'Elementor Business Profile Mockup', 'lisfinity-core' ) )
			              ->set_options( call_user_func( 'lisfinity_format_post_select', $business_args ) )
			              ->set_classes( 'crb-select2' )
			              ->set_default_value( 32 )
			              ->set_help_text( __( 'Choose the business profile that will be used as the elementor mockup and will allow you to design your own template', 'lisfinity-core' ) ),
		         ]
	         )
         );

