<?php
/**
 * Meta Taxonomies | Product.
 *
 * Here are defined all ads fields for our custom Taxonomies
 * and product types.
 *
 * @link https://carbonfields.net/docs/containers-term-meta/
 *
 * @author pebas
 * @package meta-fields-taxonomies
 * @version 1.0.0
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Meta Product / Fields.
Container::make( 'term_meta', __( 'Additional Information', 'lisfinity-core' ) )
		->where( 'term_taxonomy', '=', 'product_cat' )
		->add_fields(
			[
				Field::make( 'multiselect', 'displayed-on', __( 'Choose Product Types', 'lisfinity-core' ) )
					->add_options( 'lisfinity_get_chosen_product_types' )
					->set_help_text( __( 'Choose for what product types this term will be applicable. Leaving this empty will make it work only with default WooCommerce product types.', 'lisfinity-core' ) ),
			]
		);

