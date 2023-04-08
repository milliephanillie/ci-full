<?php
/**
 * Template Name: Shortcodes | Search Filter Top
 * Description: The file that is being used to display search filter top
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Sidebar Actions -->
<?php
$options = [];
if ( 'yes' === $args['settings']['use_custom_sort_icon'] && ! empty( $args['settings']['sort_icon'] ) ) {
	$options['sort_icon'] = $args['settings']['sort_icon'];
}
$options['display_sortby'] = $args['settings']['display_sortby'];
if ( 'yes' === $args['settings']['use_custom_map_icon'] && ! empty( $args['settings']['map_icon'] ) ) {
	$options['map_icon'] = $args['settings']['map_icon'];
}
$options['display_map'] = $args['settings']['display_map'];
?>

<div class="page-search-filter-top relative px-10" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
