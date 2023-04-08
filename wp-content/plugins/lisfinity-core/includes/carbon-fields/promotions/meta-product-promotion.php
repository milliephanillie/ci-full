<?php
/**
 * Meta Product Promotions.
 *
 * Here are defined all promotion fields for our custom
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
Container::make( 'post_meta', 'promotion_information', __( 'Promotion Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'product' )
	// tab | options.
	     ->add_tab(
		__( 'Promotion Options', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__promotion_meta_fields_options',
			[
				Field::make( 'select', 'promotion-type', __( 'Promotion Type', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_options( 'lisfinity_available_promotion_types' )
				     ->set_help_text( 'Select promotion type that you wish to create.', 'lisfinity-core' ),
				Field::make( 'select', 'promotion-product-type', __( 'Promotion Product Type', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_options( 'lisfinity_available_promotion_product_types' )
				     ->set_help_text( 'Select promotion product type that you wish to create.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'product',
							     'compare' => '=',
						     ],
					     ]
				     ),
				Field::make( 'select', 'promotion-addon-type', __( 'Promotion Addon Type', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_options( 'lisfinity_available_promotion_addon_types' )
				     ->set_help_text( 'Select promotion addon type that you wish to create.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'addon',
							     'compare' => '=',
						     ],
					     ]
				     ),
				Field::make( 'text', 'promotion-duration', __( 'Promotion Duration', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', 1 )
				     ->set_default_value( 1 )
				     ->set_help_text( 'Set number of days promotion will be active.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'product',
							     'compare' => '=',
						     ],
						     [
							     'field'   => 'promotion-product-type',
							     'value'   => 'bump-up',
							     'compare' => '!=',
						     ],
					     ]
				     ),
				Field::make( 'text', 'promotion-duration-min-value', __( 'Promotion Duration Min Value', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', 1 )
				     ->set_default_value( 1 )
				     ->set_help_text( 'Set the promotion duration min value.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'product',
							     'compare' => '=',
						     ],
						     [
							     'field'   => 'promotion-product-type',
							     'value'   => 'bump-up',
							     'compare' => '!=',
						     ],
					     ]
				     ),
				Field::make( 'text', 'promotion-duration-max-value', __( 'Promotion Duration Max Value', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', 1 )
				     ->set_default_value( 1 )
				     ->set_help_text( 'Set the promotion duration max value.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'product',
							     'compare' => '=',
						     ],
						     [
							     'field'   => 'promotion-product-type',
							     'value'   => 'bump-up',
							     'compare' => '!=',
						     ],
					     ]
				     ),
				Field::make( 'text', 'promotion-duration-step', __( 'Promotion Duration Step', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', 1 )
				     ->set_default_value( 1 )
				     ->set_help_text( 'Set the step for the promotion duration.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'product',
							     'compare' => '=',
						     ],
						     [
							     'field'   => 'promotion-product-type',
							     'value'   => 'bump-up',
							     'compare' => '!=',
						     ],
					     ]
				     ),
				Field::make( 'select', 'promotion-cost-type', __( 'Calculate Price Per Day or Month', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_options( [
					     'month' => __( 'Price per month', 'lisfinity-core' ),
					     'day'   => __( 'Price per day', 'lisfinity-core' ),
				     ] )
				     ->set_default_value( 'month' )
				     ->set_help_text( 'Choose if you wish to enable premium profile duration on daily or monthly basis. Users will be able to choose how many days or months they wish to activate premium profile based on this setting.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'premium_profile',
							     'compare' => '=',
						     ],
					     ]
				     ),
				Field::make( 'radio', 'promotion-discounts-enable', __( 'Enable Premium Profile Discounts', 'lisfinity-core' ) )
				     ->set_options( [
					     true  => __( 'Enable', 'lisfinity-core' ),
					     false => __( 'Disable', 'lisfinity-core' ),
				     ] )
				     ->set_default_value( true )
				     ->set_width( '100' )
				     ->set_help_text( 'Choose if you wish to set up premium profile discounts.', 'lisfinity-core' )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'premium_profile',
							     'compare' => '=',
						     ],
					     ]
				     ),
				Field::make( 'complex', 'promotion-discounts', __( 'Premium Profile Discounts', 'lisfinity-core' ) )
				     ->set_width( '100' )
				     ->set_help_text( __( 'Set discount options for premium profile purchase.', 'lisfinity-core' ) )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'promotion-type',
							     'value'   => 'premium_profile',
							     'compare' => '=',
						     ],
						     [
							     'field'   => 'promotion-discounts-enable',
							     'value'   => true,
							     'compare' => '=',
						     ],
					     ]
				     )
				     ->add_fields( [
					     Field::make( 'text', 'duration', __( 'Minimum Days/Months to Buy', 'lisfinity-core' ) )
					          ->set_width( '50' )
					          ->set_attribute( 'type', 'number' )
					          ->set_attribute( 'min', 1 )
					          ->set_default_value( 1 )
					          ->set_help_text( __( 'Enter minimum amount of days/months a member has to buy for discount to be enabled.', 'lisfinity-core' ) ),
					     Field::make( 'text', 'discount', __( 'Discount Percentage', 'lisfinity-core' ) )
					          ->set_width( '50' )
					          ->set_attribute( 'type', 'number' )
					          ->set_attribute( 'min', 1 )
					          ->set_attribute( 'max', 100 )
					          ->set_default_value( 1 )
					          ->set_help_text( __( 'Enter percentage of a discount that will be applied to total price calculation.', 'lisfinity-core' ) ),
				     ] ),
			]
		)
	);
