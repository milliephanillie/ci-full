<?php
/**
 * Template Name: Page | Detailed Search Page Template
 * Description: Page template that is being used as the detailed search page template across the site
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
<?php
$icon = 'fas fa-certificate';

if ( ! empty( $args['settings']['icon_author_badge']['value'] ) ) {
	$icon = $args['settings']['icon_author_badge']['value'];
}
$options = [
	'hide_child_categories'       => lisfinity_is_enabled( lisfinity_get_option( 'hide-child-categories' ) ),
	'display_category_search'     => lisfinity_is_enabled( lisfinity_get_option( 'search-display-category-select' ) ),
	'show_map'                    => lisfinity_get_option( 'product-search-map' ),
	'detailed_search'             => lisfinity_get_option( 'site-detailed-search' ),
	'ads_sortby'                  => lisfinity_get_option( 'search-products-sort' ),
	'chosen_labels'               => lisfinity_get_option( 'search-chosen-labels' ),
	'breadcrumbs'                 => '0' !== lisfinity_get_option( 'pages-breadcrumbs' ),
	'has_groups'                  => ! empty( $group_model->get_options() ),
	'enable_taxonomy_labels'      => '1' === lisfinity_get_option( 'ad-taxonomy-labels' ),
	'display_ads_count'           => '1' === lisfinity_get_option( 'search-display-count' ),
	'category_slug'               => $slug,
	'type'                        => get_query_var( $slug ),
	'different_scrollbars'        => lisfinity_is_enabled( lisfinity_get_option( 'listing-search-custom-scroll' ) ),
	'listings_per_page'           => lisfinity_get_option( 'search-products-per-page' ),
	'product-owner-verified'      => lisfinity_get_option( 'product-owner-verified' ),
	'icon'                        => $icon,
	'membership-name'             => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-name' ) : 'always',
	'membership-phone'            => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-phone' ) : 'always',
	'membership-address'          => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-address' ) : 'always',
	'membership-specification'    => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-specification' ) : 'always',
	'membership-description'      => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-description' ) : 'always',
	'membership-safety-tips'      => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-safety-tips' ) : 'always',
	'membership-listings-visits'  => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-visits' ) : 'always',
	'membership-listings-bids'    => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-bids' ) : 'always',
	'reviews'                     => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'fallback_image'              => lisfinity_get_option( 'listing-fallback-image' )['url'] ?? '',
	'product-search-map-location' => lisfinity_get_option( 'product-search-map-location' ),
	'restricted_modal_type'       => lisfinity_get_option( 'site-fields-builder-premium-type' ) ?? 'member',
	'restricted_modal_image'      => lisfinity_get_option( 'restricted-modal-image' ),
	'listings_for_premium_only'   => lisfinity_show_listings_to_premium_only(),
	'save_searches'               => lisfinity_is_enabled( lisfinity_get_option( 'site-new-products-notifications' ) ),
];


?>

<?php if ( lisfinity_is_elementor() ) : ?>
	<div id="loader"
		 class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
		 style="z-index: 99999;">

		<div class="flex-center flex-col">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 style="margin: auto; display: block; shape-rendering: auto; zoom: .4;" width="200px" height="200px"
				 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
				<path class="ll" fill="none" stroke="#333" stroke-width="8"
					  stroke-dasharray="42.76482137044271 42.76482137044271"
					  d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z"
					  stroke-linecap="round"
					  style="transform:scale(0.8);transform-origin:50px 50px">
					<animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1"
							 values="0;256.58892822265625"></animate>
				</path>
			</svg>
			<p class="text-lg text-grey-900"><?php esc_html_e( 'Loading Page...', 'lisfinity-core' ); ?></p>
		</div>
	</div>
	<div id="page-search-elementor" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
	<?php the_content(); ?>
<?php else: ?>
	<div id="page-search" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
<?php endif; ?>


<?php get_footer(); ?>
