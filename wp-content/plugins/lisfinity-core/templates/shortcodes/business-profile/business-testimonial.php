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
	'reviews'         => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'reviews_length'  => carbon_get_theme_option( 'business-reviews-characters-limit' ),
	'display_icon' => $args['settings']['display_icon'],
	'text' => $args['settings']['text'],
	'display_text' => $args['settings']['text']
];
if ( ! empty( $args['settings']['icon'] ) ) {
	$settings['icon'] = $args['settings']['icon'];
}

?>

<div class="elementor-business-testimonial" id="business-store"
	 data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
