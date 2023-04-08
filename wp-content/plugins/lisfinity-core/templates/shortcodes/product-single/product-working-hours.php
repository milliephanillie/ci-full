<?php
/**
 * Template Name: Shortcodes | Product Owner Working Hours
 * Description: The file that is being used to display product id shortcode
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
	'work_time_label' => $args['settings']['label_text'],
	'remove_icon_action' => $args['settings']['remove_icon_action'],
];

if ( 'yes' === $args['settings']['place_icon_action'] && ! empty( $args['settings']['selected_icon_action'] ) ) {
	$settings['home_icon'] = $args['settings']['selected_icon_action'];
}

?>
<div class="elementor-product-working-hours" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
