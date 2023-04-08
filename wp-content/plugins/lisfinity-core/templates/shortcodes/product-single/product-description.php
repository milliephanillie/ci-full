<?php
/**
 * Template Name: Shortcodes | Product Description
 * Description: The file that is being used to display product description shortcode
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
	'membership_description' => 'always',
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_description'] = lisfinity_get_option('membership-description');
}
?>
<div class="elementor-product-description" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
