<?php
/**
 * Template Name: Shortcodes | Product Owner Phone
 * Description: The file that is being used to display product owner phone shortcode
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
	'membership_phone' => 'always',
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_phone'] = lisfinity_get_option('membership-phone');
}

?>
<div class="elementor-product-owner-phone" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
