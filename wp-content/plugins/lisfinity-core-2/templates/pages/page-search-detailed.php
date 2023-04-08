<?php
/**
 * Template Name: Page | Search Page Template
 * Description: Page template that is being used as the main search page template across the site
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

?>
<?php get_header(); ?>
<?php
if ( empty( get_query_var( 'type' ) ) ) {
	the_post();
}
?>
<?php $group_model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel(); ?>
<?php $slug = lisfinity_get_slug( 'slug-category', 'ad-category' ); ?>
<?php $options = [
	'hide_child_categories'   => lisfinity_is_enabled( lisfinity_get_option( 'hide-child-categories' ) ),
	'display_category_search' => lisfinity_is_enabled( lisfinity_get_option( 'search-display-category-select' ) ),
	'show_map'                => lisfinity_get_option( 'product-search-map' ),
	'detailed_search'         => lisfinity_get_option( 'site-detailed-search' ),
	'ads_sortby'              => lisfinity_get_option( 'search-products-sort' ),
	'chosen_labels'           => lisfinity_get_option( 'search-chosen-labels' ),
	'breadcrumbs'             => '0' !== lisfinity_get_option( 'pages-breadcrumbs' ),
	'has_groups'              => ! empty( $group_model->get_options() ),
	'enable_taxonomy_labels'  => '1' === lisfinity_get_option( 'ad-taxonomy-labels' ),
	'display_ads_count'       => '1' === lisfinity_get_option( 'search-display-count' ),
	'type'                    => get_query_var( $slug ),
	'different_scrollbars'    => lisfinity_is_enabled( lisfinity_get_option( 'listing-search-custom-scroll' ) ),
]; ?>

<?php if ( lisfinity_is_elementor() ) : ?>
	<div id="page-search-elementor" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
<?php else: ?>
	<div id="page-search" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
<?php endif; ?>

<?php the_content(); ?>

<?php get_footer(); ?>
