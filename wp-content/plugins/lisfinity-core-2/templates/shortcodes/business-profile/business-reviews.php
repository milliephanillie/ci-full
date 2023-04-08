<?php
/**
 * Template Name: Shortcodes | Login Form
 * Description: The file that is being used to display login form shortcode
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
	'selected_icon' => $args['settings']['selected_icon'],
	'selected_icon_footer' => $args['settings']['selected_icon_footer']
];

?>

<div class="elementor-business-reviews" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
