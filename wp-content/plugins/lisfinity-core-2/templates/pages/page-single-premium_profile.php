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

<?php $elementor_page_id = lisfinity_get_option( 'page-business' ); ?>
<?php $elementor_premium_page_id = lisfinity_get_option( 'page-business-premium' ); ?>
<?php $is_premium_business = lisfinity_business_is_premium( get_the_ID() ); ?>

<?php

$options = [
	'show_map'                   => lisfinity_get_option( 'product-search-map' ),
	'detailed_search'            => '0' !== lisfinity_get_option( 'site-detailed-search' ),
	'reviews'                    => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'reviews_length'             => carbon_get_theme_option( 'business-reviews-characters-limit' ),
	'hours_enabled'              => lisfinity_is_enabled( lisfinity_get_option( 'business-working-hours-enabled' ) ),
	'ads_sortby'                 => lisfinity_get_option( 'search-products-sort' ),
	'membership-name'            => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-name' ) : 'always',
	'membership-phone'           => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-phone' ) : 'always',
	'membership-address'         => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-address' ) : 'always',
	'membership-specification'   => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-specification' ) : 'always',
	'membership-description'     => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-description' ) : 'always',
	'membership-safety-tips'     => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-safety-tips' ) : 'always',
	'membership-listings-visits' => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-visits' ) : 'always',
	'membership-listings-bids'   => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-bids' ) : 'always',
	'banner_fallback_image'      => lisfinity_get_option( 'banner-fallback-image' )['url'] ?? '',
	'fallback_image'             => lisfinity_get_option( 'listing-fallback-image' )['url'] ?? '',
	'account_type'               => $is_premium_business
];
?>

<?php if ( ! empty( $elementor_page_id ) && lisfinity_is_elementor( $elementor_page_id ) && ! $is_premium_business ): ?>
	<?php include lisfinity_get_template_part( 'page-single-business-elementor', 'pages' ); ?>
<?php elseif ( ! empty( $elementor_premium_page_id ) && lisfinity_is_elementor( $elementor_premium_page_id ) && $is_premium_business ): ?>
	<?php include lisfinity_get_template_part( 'page-single-business-premium-elementor', 'pages' ); ?>
<?php else: ?>
	<?php get_header(); ?>
	<?php the_post(); ?>
	<main id="page-single-business" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</main>
	<div id="loader"
		 class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
		 style="z-index: 99999;">

		<div class="flex-center flex-col">
			<?php $icon_args = [
				'zoom' => 1,
			]; ?>
			<img src="<?php echo esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings.4bcf82c529.svg' ); ?>"
				 alt="<?php echo esc_html__( 'Dashboard Loader', 'lisfinity-core' ) ?>"/>
			<p class="mt-20 text-lg text-grey-900"><?php _e( 'Preparing page...', 'lisfinity-core' ); ?></p>
		</div>

	</div>

	<?php get_footer(); ?>
<?php endif; ?>
