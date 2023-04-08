<?php
/**
 * Template Name: Shortcodes | Navigation Compare
 * Description: The file that is being used to display user compared items
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
if($args['settings']['selected_icon_compare']) {
	$icon = $args['settings']['selected_icon_compare']['value'];
}
if (is_array($icon)) {
	$settings = [
		'custom_icon'     => 'yes' === $args['settings']['place_icon_compare'],
		'custom_icon_url' => $icon['url'],
	];
} else {
	$settings = [
		'custom_icon'     => 'yes' === $args['settings']['place_icon_compare'],
		'custom_icon_font' => $icon,
	];
}
?>


<?php if ( is_user_logged_in() ) : ?>
	<div id="compare--wrapper" class="relative compare--wrapper"
		 title="<?php echo esc_html__( 'Compare Listings', 'lisfinity-core' ); ?>"
		 data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"
	></div>
<?php else: ?>
	<div id="compare--wrapper"></div>
	<script>
		const element = document.getElementById("compare--wrapper");
		if(element) {
			const elementsColumn = element.closest('.elementor-column');
			elementsColumn.style.display = 'none';
		}
	</script>
<?php endif; ?>
