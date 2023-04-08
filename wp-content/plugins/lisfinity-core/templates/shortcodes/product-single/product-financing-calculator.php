<?php
/**
 * Template Name: Shortcodes | Product Financing Calculator
 * Description: The file that is being used to display product financing Calculator shortcode
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
		'title_display' => $args['settings']['title_display'],
		'button_text' => $args['settings']['button_text'],
		'place_icon' => $args['settings']['place_icon'],
		'icon_url' => $args['settings']['icon_url'],
		'button_text_display' => $args['settings']['button_text_display'],
		'button_icon_display' => $args['settings']['button_icon_display']
	]
?>
<div class="elementor-product-financing-calculator" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
