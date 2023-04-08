<?php
/**
 * Template Name: Page | Business Single Page Template
 * Description: Page template that is being used as the template for premium_profile CPT
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php $options = [
	'show_map'        => lisfinity_get_option( 'product-search-map' ),
	'detailed_search' => '0' !== lisfinity_get_option( 'site-detailed-search' ),
	'reviews'         => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'reviews_length'  => carbon_get_theme_option( 'business-reviews-characters-limit' ),
	'ads_sortby'      => lisfinity_get_option( 'search-products-sort' ),
	'fallback_image' => lisfinity_get_option('listing-fallback-image')['url'] ?? '',
	'listings_per_page'           => lisfinity_get_option( 'search-products-per-page' ),
]; ?>

<?php $elementor_page_id = lisfinity_get_option( 'page-business-premium' ); ?>
<?php if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	the_content();
} ?>
<main id="page-single-business-premium-elementor" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</main>
<?php if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	get_header();
	the_post();
	echo lisfinity_get_elementor_content( $elementor_page_id );
	get_footer();
} ?>
