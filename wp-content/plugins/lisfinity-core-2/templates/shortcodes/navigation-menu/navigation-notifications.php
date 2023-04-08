<?php
/**
 * Template Name: Shortcodes | Navigation Notifications
 * Description: The file that is being used to display user notifications
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */
?>

<?php

$icon = '';
if ( $args['settings']['selected_icon_notification'] ) {
	$icon = $args['settings']['selected_icon_notification']['value'];
}
if ( is_array( $icon ) ) {
	$settings = [
		'custom_icon'     => 'yes' === $args['settings']['place_icon_notification'],
		'custom_icon_url' => $icon['url'],
		'text'            => $args['settings']['text'],
	];
} else {
	$settings = [
		'custom_icon'            => 'yes' === $args['settings']['place_icon_notification'],
		'custom_icon_font'       => $icon,
		'text'                   => $args['settings']['text'],
	];
}
?>

<?php if ( is_user_logged_in() ) : ?>
	<div id="notifications--wrapper" class="relative notifications--wrapper"
		 title="<?php echo esc_html__( 'Notifications', 'lisfinity-core' ); ?>"
		 data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"
	></div>
<?php else: ?>
	<div id="notifications--wrapper"></div>
	<script>
		const elementNotifications = document.getElementById('notifications--wrapper');
		if (elementNotifications) {
			const elementNotificationsColumn = elementNotifications.closest('.elementor-column');
			elementNotificationsColumn.style.display = 'none';
		}
	</script>
<?php endif; ?>
