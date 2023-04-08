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
	'login_form_button_text'        => $args['settings']['login_form_button_text'],
	'login_form_button_submit_icon' => $args['settings']['login_form_button_submit_icon'],
	'forgot_password_text'          => $args['settings']['forgot_password_text'],
	'create_account_text'           => $args['settings']['create_account_text'],
	'create_account_link_text'      => $args['settings']['create_account_link_text'],
	'fields' => $args['settings']['fields_tabs']
];

?>

<div class="elementor-login-form" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
