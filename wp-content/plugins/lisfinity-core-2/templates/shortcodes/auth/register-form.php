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
	'fields'                           => $args['settings']['fields_tabs'],
	'register_form_button_text'        => $args['settings']['register_form_button_text'],
	'register_form_button_submit_icon' => $args['settings']['register_form_button_submit_icon'],
	'have_account_text'                => $args['settings']['have_account_text'],
	'have_account_link_text'           => $args['settings']['have_account_link_text'],
	'auth-enabled'                     => lisfinity_is_enabled( lisfinity_get_option( 'auth-captcha' ) ),
	'auth-captcha-enabled'             => lisfinity_is_enabled( lisfinity_get_option( 'auth-captcha-enabled' ) ),
	'auth-captcha-label'               => lisfinity_get_option( 'auth-captcha-label' ),
	'auth-captcha-site-key'            => lisfinity_get_option( 'auth-captcha-site-key' ),
	'page-terms-label'                 => lisfinity_format_terms_and_policy_label(),
	'page-terms'                       => lisfinity_get_option( 'page-terms' )
];

?>

<div class="elementor-register-form" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
