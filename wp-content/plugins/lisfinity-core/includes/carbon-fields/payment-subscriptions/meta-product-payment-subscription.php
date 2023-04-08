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
Container::make( 'post_meta', 'payment_subscription_information', __( 'Subscription Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'product' )
	// tab | options.
	     ->add_tab(
		__( 'Package Options', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__package_meta_fields_options',
			[
				Field::make( 'text', 'subscription-products-limit', __( 'Listings Submission Limit per Month', 'lisfinity-core' ) )
				     ->set_width( 25 )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', '1' )
				     ->set_attribute( 'placeholder', __( 'Unlimited', 'lisfinity-core' ) )
				     ->set_help_text( __( 'Set the number of products that can be submitted with this package. Leave empty for unlimited.', 'lisfinity-core' ) ),
				Field::make( 'text', 'subscription-products-duration', __( 'Listings Duration', 'lisfinity-core' ) )
				     ->set_width( 25 )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', '1' )
				     ->set_attribute( 'placeholder', __( 'Unlimited', 'lisfinity-core' ) )
				     ->set_help_text( __( 'Set the number of days submitted product will be active.', 'lisfinity-core' ) ),
				Field::make( 'text', 'subscription-promotions-limit', __( 'Promotions Limit per Month', 'lisfinity-core' ) )
				     ->set_width( 25 )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', '1' )
				     ->set_attribute( 'placeholder', __( 'Unlimited', 'lisfinity-core' ) )
				     ->set_help_text( __( 'Set the number of products that can be submitted with this package. Leave empty for unlimited.', 'lisfinity-core' ) ),
				Field::make( 'text', 'subscription-free-trial-period', __( 'Free Trial Period / months', 'lisfinity-core' ) )
				     ->set_width( 25 )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', '0' )
				     ->set_default_value( 0 )
				     ->set_help_text( __( 'Set the free trial period for this subscription package. Time is calculated in months.', 'lisfinity-core' ) ),
				Field::make( 'text', 'subscription-product-price', __( 'Additional Listing Price', 'lisfinity-core' ) )
				     ->set_width( 33 )
				     ->set_default_value( 0 )
				     ->set_help_text( __( 'Set the price for submitting additional listing over the monthly limit', 'lisfinity-core' ) ),
				Field::make( 'text', 'subscription-transaction-fee', __( 'Transaction Fee Percentage', 'lisfinity-core' ) )
				     ->set_width( 33 )
				     ->set_attribute( 'type', 'number' )
				     ->set_attribute( 'min', '0' )
				     ->set_default_value( 0 )
				     ->set_help_text( __( 'Set transaction fee percentage that your site will get from the listings available on sale through your site.', 'lisfinity-core' ) ),
				Field::make( 'radio', 'subscription-include-premium-profile', __( 'Include Premium Profile', 'lisfinity-core' ) )
				     ->set_width( 33 )
				     ->set_options( [
					     true  => __( 'Enable', 'lisfinity-core' ),
					     false => __( 'Disable', 'lisfinity-core' ),
				     ] )
				     ->set_default_value( false )
				     ->set_help_text( __( 'Include premium profile promotion along with the package', 'lisfinity-core' ) ),
				Field::make( 'radio', 'subscription-discounts-enable', __( 'Enable Subscription Discounts', 'lisfinity-core' ) )
				     ->set_width( 33 )
				     ->set_options( [
					     true  => __( 'Enable', 'lisfinity-core' ),
					     false => __( 'Disable', 'lisfinity-core' ),
				     ] )
				     ->set_default_value( false )
				     ->set_width( '100' )
				     ->set_help_text( 'Choose if you wish to set up premium profile discounts.', 'lisfinity-core' ),
				Field::make( 'complex', 'subscription-discounts', __( 'Subscription Discounts', 'lisfinity-core' ) )
				     ->set_width( 100 )
				     ->set_help_text( __( 'Set discount options for subscription purchase.', 'lisfinity-core' ) )
				     ->set_conditional_logic(
					     [
						     [
							     'field'   => 'subscription-discounts-enable',
							     'value'   => true,
							     'compare' => '=',
						     ],
					     ]
				     )
				     ->add_fields( [
					     Field::make( 'text', 'duration', __( 'Minimum Months to Buy', 'lisfinity-core' ) )
					          ->set_width( 50 )
					          ->set_attribute( 'type', 'number' )
					          ->set_attribute( 'min', 1 )
					          ->set_default_value( 1 )
					          ->set_help_text( __( 'Enter minimum amount of months a member has to buy for the discount to be enabled.', 'lisfinity-core' ) ),
					     Field::make( 'text', 'discount', __( 'Discount Percentage', 'lisfinity-core' ) )
					          ->set_width( 50 )
					          ->set_attribute( 'type', 'number' )
					          ->set_attribute( 'min', 1 )
					          ->set_attribute( 'max', 100 )
					          ->set_default_value( 1 )
					          ->set_help_text( __( 'Enter percentage of a discount that will be applied to total price calculation.', 'lisfinity-core' ) ),
				     ] )
			]
		)
	)
	// tab | Price per Category.
	->add_tab(
		__( 'Price Per Category', 'lisfinity-core' ),
		apply_filters( 'lisfinity__package_subscriptions_meta_fields_price_per_category', [
			Field::make( 'radio', 's-package-categories-exclusive', __( 'Limit Package to Selected Categories Only', 'lisfinity-core' ) )
			     ->set_options( [
				     'no'  => __( 'No', 'lisfinity-core' ),
				     'yes' => __( 'Yes', 'lisfinity-core' )
			     ] )
			     ->set_help_text( __( 'Allow ads to be submitted only in the selected packages', 'lisfinity-core' ) ),
			Field::make( 'complex', 's-package-categories', __( 'Set Different Prices per Category', 'lisfinity-core' ) )
			     ->add_fields( [
				     Field::make( 'select', 'category', __( 'Choose Category', 'lisfinity-core' ) )
				          ->set_options( 'lisfinity_format_product_categories_select' ),
				     Field::make( 'text', 'price', __( 'Category Price', 'lisfinity-core' ) )
				          ->set_help_text( __( 'Set the price for submitting an ad to this category. Leave empty if the default package price should be used instead.', 'lisfinity-core' ) )
			     ] )
		] )
	)
	// tab | Package Display.
	     ->add_tab(
		__( 'Package Features Display', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__subscription_package_meta_fields_display',
			[
				Field::make( 'select', 's-package-style', __( 'Package Style', 'lisfinity-core' ) )
				     ->set_options(
					     [
						     '1' => __( 'Style 1', 'lisfinity-core' ),
						     '2' => __( 'Style 2', 'lisfinity-core' ),
					     ]
				     )
				     ->set_help_text( 'Choose the style of the package that will be presented on site.', 'lisfinity-core' ),
				Field::make( 'complex', 's-package-features', __( 'Package Features', 'lisfinity-core' ) )
				     ->set_width( '70%' )
				     ->add_fields(
					     [
						     Field::make( 'text', 'package-feature', __( 'Package Feature', 'lisfinity-core' ) )
						          ->set_help_text( __( 'Enter package feature.', 'lisfinity-core' ) ),
						     Field::make( 'text', 'package-footnote', __( 'Package Footnote', 'lisfinity-core' ) )
						          ->set_help_text( __( 'Here you can add footnotes for this feature that will be displayed below pricing package. That is usually some small additional information that is needed to be clearly stated.', 'lisfinity-core' ) ),
					     ]
				     )
				     ->set_help_text( 'Enter features related to this package that will explain all its options to a customer.', 'lisfinity-core' ),
				Field::make( 'html', 's-package-feature-explain', __( 'Package Tags', 'lisfinity-core' ) )
				     ->set_width( '30%' )
				     ->set_html(
					     '<div class="package-feature-tags">
						    <div class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="[products-limit]">' . __( 'Products Limit', 'lisfinity-core' ) . '</button><span class="package-feature-value">[products-limit]</span></div>
				    	    <div class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="[products-duration]">' . __( 'Products Duration', 'lisfinity-core' ) . '</button><span class="package-feature-value">[products-duration]</span></div>
					     	<div class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="[package-price]">' . __( 'Package Price', 'lisfinity-core' ) . '</button><span class="package-feature-value">[package-price]</span></div>
					     	<div class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="<strong>' . __( 'YOUR_TEXT_HERE', 'lisfinity-core' ) . '</strong>">' . __( 'Bold Text', 'lisfinity-core' ) . '</button></div>
					     	<div class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="<italic>' . __( 'YOUR_TEXT_HERE', 'lisfinity-core' ) . '</italic>">' . __( 'Italic Text', 'lisfinity-core' ) . '</button></div>
					     	<span class="package-feature-tag"><button type="button" class="click-to-copy button" data-value="<span>' . __( 'YOUR_TEXT_HERE', 'lisfinity-core' ) . '</span>">' . __( 'Disabled Text', 'lisfinity-core' ) . '</button></div>
					     </div>'
				     )
				     ->set_help_text( __( 'List of available tags that you can use in a package feature. <strong>You can manually type them or click to copy to clipboard</strong>', 'lisfinity-core' ) ),
			]
		)
	)
	// tab | Package Promotions.
	     ->add_tab(
		__( 'Package Promotions', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__package_meta_fields_promotions',
			[
				Field::make( 'complex', 's-package-promotions', __( 'Package Promotions', 'lisfinity-core' ) )
				     ->add_fields(
					     'addon',
					     [
						     Field::make( 'select', 'package-promotions-product', __( 'Additional Media', 'lisfinity-core' ) )
						          ->set_width( '50%' )
						          ->set_options( 'lisfinity_available_promotion_addon_types' ),
						     Field::make( 'text', 'package-promotions-product-value', __( 'Promotion Free Items', 'lisfinity-core' ) )
						          ->set_width( '50%' )
						          ->set_attribute( 'type', 'number' )
						          ->set_attribute( 'min', 0 )
						          ->set_attribute( 'placeholder', __( 'Unlimited', 'lisfinity-core' ) )
						          ->set_help_text( 'Set the amount of free items (images/videos/docs) that will be available. Maximum allowed number is defined in the theme options.' ),
					     ]
				     ),
			]
		)
	);
