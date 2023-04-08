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

// Authentication page settings.
Container::make( 'post_meta', __( 'Page Options', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'page' )
         ->where( 'post_id', 'IN', [
	         lisfinity_get_option( 'page-vendors' ),
         ] )
         ->set_priority( 'core' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__page_meta_fields_archive',
		         [
			         Field::make( 'image', 'page-header-image', __( 'Background Image', 'lisfinity-core' ) )
			              ->set_help_text( __( 'Set the header background image for the page.', 'lisfinity-core' ) ),
			         Field::make( 'radio', 'page-header-image-position', __( 'Background Image Vertical Position', 'lisfinity-core' ) )
			              ->set_options( [
				              'top'    => __( 'Top', 'lisfinity-core' ),
				              'center' => __( 'Center', 'lisfinity-core' ),
				              'bottom' => __( 'Bottom', 'lisfinity-core' ),
			              ] )
			              ->set_default_value( 'center' )
			              ->set_help_text( __( 'Set the vertical position for header background image for the page.', 'lisfinity-core' ) ),
			         Field::make( 'color', 'page-header-overlay', __( 'Set Image Overlay', 'lisfinity-core' ) )
			              ->set_help_text( __( 'Set overlay of the image that is used as background.', 'lisfinity-core' ) ),
			         Field::make( 'text', 'page-overlay-opacity', __( 'Set Overlay Opacity', 'lisfinity-core' ) )
			              ->set_attribute( 'type', 'number' )
			              ->set_attribute( 'min', 0 )
			              ->set_attribute( 'max', 1 )
			              ->set_attribute( 'step', 0.1 )
			              ->set_default_value( 0.6 )
			              ->set_help_text( __( 'Set the opacity of the overlay used as background. 1 means full color while 0 means full transparent.', 'lisfinity-core' ) ),
		         ]
	         )
         );
