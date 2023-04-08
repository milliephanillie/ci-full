<?php
/**
 * Template Name: Shortcodes | Keyword Search
 * Description: The file that is being used to display theme's keyword search fields
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */
?>

<?php
$icon = null;
if($args['settings']['selected_icon_search_keyword']) {
	$icon = $args['settings']['selected_icon_search_keyword']['value'];
}
if (is_array($icon)) {
	$settings = [
		'custom_icon'     => 'yes' === $args['settings']['place_icon_search_keyword'],
		'custom_icon_url' => $icon['url'],
		'display_label' => $args['settings']['display_label'],
		'text' => $args['settings']['text'],
		'display_button_text' => $args['settings']['display_button_text'],
		'display_button_icon' => $args['settings']['display_button_icon'],
	];
} else {
	$settings = [
		'custom_icon'         => 'yes' === $args['settings']['place_icon_search_keyword'],
		'custom_icon_font'    => $icon,
		'display_label'       => $args['settings']['display_label'],
		'text'                => $args['settings']['text'],
		'display_button_text' => $args['settings']['display_button_text'],
		'display_button_icon' => $args['settings']['display_button_icon'],
	];
}
?>

<div id="header-keyword" class="header-keyword" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
