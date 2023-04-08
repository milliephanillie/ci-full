<?php
/**
 * Template Name: Shortcodes | Search Page Sidebar Filter
 * Description: The file that is being used to display search page sidebar filter shortcode
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Sidebar Filter -->
<?php
$options = [
	'custom_icon' => false,
];

if ( 'yes' === $args['settings']['use_custom_icon'] && ! empty( $args['settings']['icon'] ) ) {
	$options['custom_icon'] = $args['settings']['icon'];
}
if ( 'yes' === $args['settings']['use_custom_reset_icon'] && ! empty( $args['settings']['reset_icon'] ) ) {
	$options['custom_reset_icon'] = $args['settings']['reset_icon'];
}
if ( 'yes' === $args['settings']['use_custom_button_icon'] && ! empty( $args['settings']['button_submit_icon'] ) ) {
	$options['custom_button_icon'] = $args['settings']['button_submit_icon'];
}
if ( ! empty( $args['settings']['reset_text'] ) ) {
	$options['reset_text'] = $args['settings']['reset_text'];
}
if ('yes' === $args['settings']['custom_button_text'] && ! empty( $args['settings']['button_text'] ) ) {
	$options['button_text'] = $args['settings']['button_text'];
}
if ('yes' === $args['settings']['custom_d_button_text'] && ! empty( $args['settings']['d_button_text'] ) ) {
	$options['detailed_button_text'] = $args['settings']['d_button_text'];
}
if ('yes' === $args['settings']['custom_filter_text'] && ! empty( $args['settings']['filter_text'] ) ) {
	$options['filter_text'] = $args['settings']['filter_text'];
}

?>

<div class="page-search-sidebar-filter relative" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
