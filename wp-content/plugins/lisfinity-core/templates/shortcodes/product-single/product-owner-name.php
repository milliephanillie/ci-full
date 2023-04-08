<?php
/**
 * Template Name: Shortcodes | Product Owner Name
 * Description: The file that is being used to display product owner name shortcode
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
	'membership_name' => 'always',
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_name'] = lisfinity_get_option('membership-name');
}
?>
<div class="elementor-product-owner-name" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
