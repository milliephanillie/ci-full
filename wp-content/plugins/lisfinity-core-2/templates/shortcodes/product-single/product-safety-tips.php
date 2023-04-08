<?php
/**
 * Template Name: Shortcodes | Product Safety Tips
 * Description: The file that is being used to display product safety tips shortcode
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
	'place_icon' => $args['settings']['place_icon'],
	'selected_icon' => $args['settings']['selected_icon'],
	'title_text' => $args['settings']['title_text'],
	'text_text' => $args['settings']['text_text'],
	'link_text' => $args['settings']['link_text'],
	'link_url' => $args['settings']['link_url'],
	'different_link' => $args['settings']['different_link'],
	'membership_safety_tips' => 'always',
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_safety_tips'] = lisfinity_get_option('membership-safety-tips');
}

?>
<div class="elementor-product-safety-tips" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
