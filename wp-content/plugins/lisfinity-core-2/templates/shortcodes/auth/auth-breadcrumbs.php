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
$settings = [];
if ( 'yes' === $args['settings']['place_icon_breadcrumbs'] && ! empty( $args['settings']['home_icon'] ) ) {
	$settings['home_icon'] = $args['settings']['home_icon'];
}
?>

<div class="elementor-auth-breadcrumbs" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
