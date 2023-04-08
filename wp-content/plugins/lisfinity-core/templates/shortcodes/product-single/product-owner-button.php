<?php
/**
 * Template Name: Shortcodes | Product Owner Button
 * Description: The file that is being used to display product owner button shortcode
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
	'place_icon_visit_store_button' => $args['settings']['place_icon_visit_store_button'],
	'selected_icon_visit_store_button' => $args['settings']['selected_icon_visit_store_button'],
	'place_icon_messages_button' => $args['settings']['place_icon_messages_button'],
	'selected_icon_messages_button' => $args['settings']['selected_icon_messages_button'],
	'place_icon_messages_button_listing_owner' => $args['settings']['place_icon_messages_button_listing_owner'],
	'selected_icon_messages_button_listing_owner' => $args['settings']['selected_icon_messages_button_listing_owner'],
	'display_visit_store_button' => $args['settings']['display_visit_store_button'],
	'display_messages_button' => $args['settings']['display_messages_button'],
];

?>
<div class="elementor-product-owner-button"  data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
