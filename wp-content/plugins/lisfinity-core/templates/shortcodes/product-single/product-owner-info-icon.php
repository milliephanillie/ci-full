<?php
/**
 * Template Name: Shortcodes | Product Info Icon
 * Description: The file that is being used to display product info icon shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php
$owner   = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$location_format = lisfinity_get_option( 'format-location' );
$product_id = get_the_ID();
$settings = [
	'membership_address' => 'always',
	'actions' => $args['settings']['select_icon'],
	'place_icon' => $args['settings']['place_icon'],
	'selected_icon' => $args['settings']['selected_icon'],
	'address' => lisfinity_format_location( $product_id, 'full' === $location_format ),
	'product-search-map-location' => lisfinity_get_option( 'product-search-map-location' )
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_address'] = lisfinity_get_option('membership-address');
}

?>
<div class="elementor-product-owner-info-icon" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
