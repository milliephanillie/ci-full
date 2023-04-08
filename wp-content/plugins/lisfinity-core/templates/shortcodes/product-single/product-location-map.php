<?php
/**
 * Template Name: Shortcodes | Product Location Map
 * Description: The file that is being used to display product location map shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>
<?php
$location_format = lisfinity_get_option( 'format-location' );
$product_id = get_the_ID();
$settings = [
	'location' => $args['settings']['select_location'],
	'membership_address' => 'always',
	'address' => lisfinity_format_location( $product_id, 'full' === $location_format ),
	'product-search-map-location' => lisfinity_get_option( 'product-search-map-location' ),
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_address'] = lisfinity_get_option('membership-address');
}

?>

<div class="elementor-product-location-map" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
