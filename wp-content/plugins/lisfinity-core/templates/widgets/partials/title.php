<?php
/**
 * Template Name: Widget | Title
 * Description: The file that is being used to display title of the widget
 *
 * @author pebas
 * @package templates/widgets
 * @version 1.0.0
 *
 * @var $args
 * @var $instance
 */
?>

<h6 class="mb-0 text-xl font-semibold text-white"><?php echo esc_html( $instance['pages_title'] ?? $instance['pc_title'] ); ?></h6>
