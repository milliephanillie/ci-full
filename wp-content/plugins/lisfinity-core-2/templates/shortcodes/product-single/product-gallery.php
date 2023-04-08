<?php
/**
 * Template Name: Shortcodes | Product Gallery
 * Description: The file that is being used to display product gallery shortcode
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
	'action' => $args['settings']['actions'] ?? [],
];?>
<div class="elementor-product-gallery" data-settings="<?php echo esc_attr( json_encode( $settings) ); ?>"></div>
