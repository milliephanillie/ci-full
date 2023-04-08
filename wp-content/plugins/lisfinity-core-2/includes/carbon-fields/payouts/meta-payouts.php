<?php
/**
 * Meta Product Vendor Payouts
 *
 * Here are defined all vendor payout meta fields
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
$type = \Lisfinity\Models\Vendors\PayoutsModel::$type;
Container::make( 'post_meta', __( 'Payout Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', $type )
         ->add_fields(
	         apply_filters(
		         'lisfinity__payouts_meta_fields',
		         [
			         Field::make( 'select', 'payout-product', __( 'Purchased Product', 'lisfinity-core' ) )
			              ->set_options( 'lisfinity_format_products_select' )
			              ->set_classes( 'crb-select2' )
			              ->set_help_text( __( 'Product that has been purchased', 'lisfinity-core' ) ),
			         Field::make( 'select', 'payout-order', __( 'Order ID', 'lisfinity-core' ) )
			              ->set_options( 'lisfinity_format_orders_select' )
			              ->set_classes( 'crb-select2' )
			              ->set_help_text( __( 'WooCommerce Order ID.', 'lisfinity-core' ) ),
			         Field::make( 'select', 'payout-vendor', __( 'Vendor', 'lisfinity-core' ) )
			              ->set_options( 'lisfinity_format_users_select' )
			              ->set_classes( 'crb-select2' )
			              ->set_help_text( __( 'ID of the vendor that needs to be paid.', 'lisfinity-core' ) ),
			         Field::make( 'html', 'amount-due-info', __( 'Amount Due', 'lisfinity-core' ) )
			              ->set_html( sprintf( '<p>%d%s</p>', ! empty( $_GET['post'] ) ? get_post_meta( $_GET['post'], '_amount-due', true ) : 0, get_woocommerce_currency_symbol() ), 'number' )
			              ->set_help_text( __( 'The amount you own to the vendor', 'lisfinity-core' ) ),
			         Field::make( 'html', 'amount-earned-info', __( 'Amount Due', 'lisfinity-core' ) )
			              ->set_html( sprintf( '<p>%d%s</p>', ! empty( $_GET['post'] ) ? get_post_meta( $_GET['post'], '_amount-earned', true ) : 0, get_woocommerce_currency_symbol() ), 'number' )
			              ->set_help_text( __( 'The amount you earned from the sold listing', 'lisfinity-core' ) ),
		         ]
	         )
         );
Container::make( 'post_meta', __( 'Payout Status', 'lisfinity-core' ) )
         ->where( 'post_type', '=', $type )
         ->set_context( 'side' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__payouts_meta_fields_status',
		         [
			         Field::make( 'select', 'payout-status', __( 'Payout Status', 'lisfinity-core' ) )
			              ->set_options( [
				              'not_paid' => __( 'Not Paid', 'lisfinity-core' ),
				              'paid'     => __( 'Paid', 'lisfinity-core' ),
			              ] )
			              ->set_help_text( __( 'Set report status between `not paid` and `paid`.', 'lisfinity-core' ) ),
		         ]
	         )
         );
