<?php
/**
 * Template Name: Shortcodes | Search Detailed
 * Description: The file that is being used to display detailed search page
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Detailed Search -->
<?php
$options = [
	'filter_text' => $args['settings']['filter_text'],
	'filter_text_sticky_header' => $args['settings']['filter_text_sticky_header'],
	'use_custom_icon' => $args['settings']['use_custom_icon'],
	'icon' => $args['settings']['icon'],
	'use_custom_icon_sticky_header' => $args['settings']['use_custom_icon_sticky_header'],
	'icon_sticky_header' => $args['settings']['icon_sticky_header'],
	'use_custom_button_icon_header_button' => $args['settings']['use_custom_button_icon_header_button'],
	'button_submit_icon_header_button' => $args['settings']['button_submit_icon_header_button'],
	'button_text_header_button' => $args['settings']['button_text_header_button'],
	'use_custom_button_icon_sticky_header_button' => $args['settings']['use_custom_button_icon_sticky_header_button'],
	'button_submit_icon_sticky_header_button' => $args['settings']['button_submit_icon_sticky_header_button'],
	'button_text_sticky_header_button' => $args['settings']['button_text_sticky_header_button'],
];
?>

<div id="page-search-detailed-elementor" class="relative" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
