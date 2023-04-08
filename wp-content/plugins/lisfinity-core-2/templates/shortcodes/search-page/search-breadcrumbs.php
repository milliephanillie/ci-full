<?php
/**
 * Template Name: Shortcodes | Search Breadcrumbs
 * Description: The file that is being used to display search page breadcrumbs
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Sidebar Breadcrumbs -->
<?php
$options = [];
if ( 'yes' === $args['settings']['use_custom_icon'] && ! empty( $args['settings']['home_icon'] ) ) {
	$options['home_icon'] = $args['settings']['home_icon'];
}
?>

<div class="page-search-breadcrumbs relative" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
