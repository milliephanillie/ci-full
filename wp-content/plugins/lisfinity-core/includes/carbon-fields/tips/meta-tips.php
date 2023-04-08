<?php
/**
 * Meta Product Payment Packages.
 *
 * Here are defined all payment packages fields for our custom
 * WooCommerce product type.
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
$type = \Lisfinity\Models\Tips\TipsModel::$type;
Container::make( 'post_meta', __( 'Report Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', $type )
         ->add_fields(
	         apply_filters(
		         'lisfinity__tips_meta_fields',
		         [
			         Field::make( 'select', 'tips-category', __( 'Tips For Category', 'lisfinity-core' ) )
			              ->set_options( 'lisfinity_format_product_categories_select' )
			              ->set_classes( 'crb-select2' )
			              ->set_help_text( __( 'Choose the category for which the safety tips will apply.', 'lisfinity-core' ) ),
			         Field::make( 'complex', 'tips', __( 'Safety Tips', 'lisfinity-core' ) )
			              ->add_fields( [
				              Field::make( 'textarea', 'tip', __( 'Safety Tip', 'lisfinity-core' ) )
				                   ->set_help_text( 'Enter the Safety tip for the given category' )
			              ] )
			              ->set_help_text( __( 'Enter safety tips for the given category.', 'lisfinity-core' ) ),
		         ]
	         )
         );
