<?php
/**
 * Template Name: Shortcodes | Product Mobile Menu
 * Description: The file that is being used to display product mobile menu shortcode
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
		'place_icon' => $args['settings']['place_icon'],
		'icon_url' => $args['settings']['icon_url']
	]
?>
<div class="elementor-product-mobile-menu" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
