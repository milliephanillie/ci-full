<?php
/**
 * Template Name: Shortcodes | Product Actions
 * Description: The file that is being used to display product actions shortcode
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
		'actions' => $args['settings']['actions_tabs'],
		'membership_listings_visits' => 'always',
	];


if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_listings_visits'] = lisfinity_get_option('membership-listings-visits');
}

?>
<div class="elementor-product-actions" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
