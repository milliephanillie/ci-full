<?php
/**
 * Template Name: Shortcodes | Author Box
 * Description: The file that is being used to display author box
 *
 * @author pebas
 * @package templates/shortcodes/authors
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$settings = [];
if ( 'yes' === $args['settings']['use_custom_icon'] && ! empty( $args['settings']['icon'] ) ) {
	$settings['icon'] = $args['settings']['icon'];
}


?>

<div class="elementor-author-search" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>">
</div>
