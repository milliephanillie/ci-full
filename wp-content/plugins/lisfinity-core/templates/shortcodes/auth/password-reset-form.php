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
	'reset_password_button_text'        => $args['settings']['reset_password_button_text'],
	'reset_password_button_submit_icon' => $args['settings']['reset_password_button_submit_icon'],
	'reset_password_input_submit_icon' => $args['settings']['reset_password_input_submit_icon'],
	'back_to_login_text'        => $args['settings']['back_to_login_text'],
	'success_message_text'        => $args['settings']['success_message_text'],
	'success_message_icon'        => $args['settings']['success_message_icon'],
	'error_message_text'        => $args['settings']['error_message_text'],
	'error_message_icon'        => $args['settings']['error_message_icon'],
];

?>

<div class="elementor-password-reset-form" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
