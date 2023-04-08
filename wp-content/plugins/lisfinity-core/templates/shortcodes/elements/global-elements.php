<?php
/**
 * Template Name: Shortcodes | Global Elements
 * Description: The file that is being used to display site how it works section
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$settings = $args['settings'];
?>
<?php if ( ! empty( $settings['widget'] ) ) : ?>
	<?php echo lisfinity_get_elementor_content( $settings['widget'] ); ?>
<?php endif; ?>
