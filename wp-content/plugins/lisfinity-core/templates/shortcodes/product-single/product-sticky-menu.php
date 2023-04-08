<?php
/**
 * Template Name: Shortcodes | Product Id
 * Description: The file that is being used to display product id shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php
$settings = [
	'membership-listings-bids' => 'always',
	'login_url'                   => get_permalink( lisfinity_get_page_id( 'page-login' ) ),
	'fallback_image'              => lisfinity_get_option( 'listing-fallback-image' )['thumbnail'] ?? '',
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_listings_bids'] = lisfinity_get_option('membership-listings-bids');
}

?>
<div class="elementor-product-sticky-menu" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
