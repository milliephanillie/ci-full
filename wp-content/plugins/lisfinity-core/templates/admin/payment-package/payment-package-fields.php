<?php
/**
 * Template Name: WooCommerce Custom Product type additional fields
 *
 * Description: Here we can register our own custom meta fields that
 * will be displayed in WooCommerce's General tab along with price field.
 *
 * @param $args - product_type, post_id
 *
 * @package woocommerce-listing
 * @version 1.0.0
 * @author pebas
 */
?>
<div class="options_group show_if_<?php echo esc_html( $args['product_type'] ); ?>">
    <?php //fixme _listing_duration should be part of the product package and promotions not this one! ?>
	<?php woocommerce_wp_text_input( array(
		'id'                => "_listing_duration",
		'label'             => __( 'Listing Duration', 'lisfinity-core' ),
		'description'       => __( 'Number of days a listing will be active. Leave empty for unlimited.', 'lisfinity-core' ),
		'value'             => ( $limit = get_post_meta( $args['post_id'], '_listing_duration', true ) ) ? $limit : '',
		'placeholder'       => __( 'Unlimited', 'lisfinity-core' ),
		'type'              => 'number',
		'desc_tip'          => true,
		'custom_attributes' => array(
			'min'  => '0',
			'step' => '1',
		),
	) ); ?>
</div>
