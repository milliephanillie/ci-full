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
$type = \Lisfinity\Models\Reports\ReportModel::$type;
Container::make( 'post_meta', __( 'Report Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', $type )
         ->add_fields(
	         apply_filters(
		         'lisfinity__report_meta_fields',
		         [
			         Field::make( 'select', 'report-product', __( 'Reported Product', 'lisfinity-core' ) )
			             ->set_options( 'lisfinity_format_products_select' )
			             ->set_classes( 'crb-select2' )
			             ->set_help_text( __( 'Choose the product that has been reported', 'lisfinity-core' ) ),
			         Field::make( 'select', 'report-user-id', __( 'Product Reported by: User ID', 'lisfinity-core' ) )
			             ->set_options( 'lisfinity_format_users_select' )
			             ->set_classes( 'crb-select2' )
			             ->set_help_text( __( 'Choose which user has reported the product.', 'lisfinity-core' ) ),
			         Field::make( 'text', 'report-user-email', __( 'Product Reported by: User email', 'lisfinity-core' ) )
			             ->set_attribute( 'type', 'email' )
			             ->set_help_text( __( 'Enter the email address of the user that has reported the product.', 'lisfinity-core' ) ),
			         Field::make( 'text', 'report-user-ip', __( 'Product Reported by: User IP Address', 'lisfinity-core' ) )
			             ->set_help_text( __( 'Enter the IP address of the user that has reported the product.', 'lisfinity-core' ) ),
			         Field::make( 'select', 'report-reason', __( 'Reason for Reporting', 'lisfinity-core' ) )
				         ->set_options( 'lisfinity_report_reasons' )
			             ->set_help_text( __( 'Reason why the product has been reported.', 'lisfinity-core' ) ),
			         Field::make( 'rich_text', 'report-message', __( 'Report Message', 'lisfinity-core' ) )
			             ->set_help_text( __( 'Message that explains a reason product has been reported.', 'lisfinity-core' ) ),
		         ]
	         )
         );
Container::make( 'post_meta', __( 'Report Status', 'lisfinity-core' ) )
         ->where( 'post_type', '=', $type )
         ->set_context( 'side' )
         ->add_fields(
	         apply_filters(
		         'lisfinity__report_meta_fields_status',
		         [
			         Field::make( 'select', 'report-status', __( 'Report Status', 'lisfinity-core' ) )
			              ->set_options( [
				              'pending' => __( 'Pending', 'lisfinity-core' ),
				              'stashed' => __( 'Stashed', 'lisfinity-core' ),
			              ] )
			              ->set_help_text( __( 'Set report status between `pending` and `stashed`.', 'lisfinity-core' ) ),
		         ]
	         )
         );
