<?php
/**
 * Template Name: Page | Product Single Page Template
 * Description: Page template that is being used as the template for product single
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
$owner           = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$location_format = lisfinity_get_option( 'format-location' );
$product_id      = get_the_ID();
$owner           = carbon_get_post_meta( $product_id, 'product-owner' );
$account_type    = lisfinity_get_account_type( $owner );
$options         = [
	'page_search'                 => get_permalink( lisfinity_get_page_id( 'page-search' ) ),
	'messenger'                   => lisfinity_is_enabled( lisfinity_get_option( 'messenger' ) ),
	'messenger_limit'             => lisfinity_get_option( 'messenger-limit' ),
	'messenger_note'              => lisfinity_get_option( 'messenger-note' ),
	'messenger_note_translation'  => lisfinity_get_option( 'messenger-note-translation' ),
	'report'                      => carbon_get_theme_option( 'report' ),
	'report_reasons'              => carbon_get_theme_option( 'report-reasons-enable' ),
	'ad_likes'                    => lisfinity_get_option( 'ad-likes' ),
	'ad_visits'                   => lisfinity_get_option( 'ad-visits' ),
	'ad_similar'                  => lisfinity_get_option( 'ad-similar' ),
	'ad_compare'                  => lisfinity_get_option( 'ads-compare' ),
	'safety_tips'                 => lisfinity_get_option( 'display-safety-tips' ),
	'calculator_position'         => lisfinity_get_option( 'calculator-position' ),
	'hide_bidding'                => '1' === lisfinity_get_option( 'site-hide-bidding' ),
	'bidding_description'         => '1' === lisfinity_get_option( 'site-bidding-description' ),
	'random_bidding'              => '1' === lisfinity_get_option( 'product-enable-random-bidding' ),
	'hours_enabled'               => lisfinity_is_enabled( lisfinity_get_option( 'business-working-hours-enabled' ) ),
	'reviews'                     => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'send_details'                => lisfinity_is_enabled( lisfinity_get_option( 'send-details' ) ),
	'product-search-map-location' => lisfinity_get_option( 'product-search-map-location' ),
	'product-owner-verified'      => lisfinity_get_option( 'product-owner-verified' ),
	'membership-name'             => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-name' ) : 'always',
	'membership-phone'            => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-phone' ) : 'always',
	'membership-address'          => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-address' ) : 'always',
	'membership-specification'    => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-specification' ) : 'always',
	'membership-description'      => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-description' ) : 'always',
	'membership-safety-tips'      => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-safety-tips' ) : 'always',
	'membership-listings-visits'  => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-visits' ) : 'always',
	'membership-listings-bids'    => lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ? lisfinity_get_option( 'membership-listings-bids' ) : 'always',
	'login_url'                   => get_permalink( lisfinity_get_page_id( 'page-login' ) ),
	'qr_download_enabled'         => lisfinity_is_enabled( lisfinity_get_option( 'enable-qr-promotion-download' ) ),
	'published_date'              => lisfinity_is_enabled( lisfinity_get_option( 'ad-published-date' ) ) ? get_the_date( 'd.m.Y', lisfinity_get_product_id() ) : '',
	'display_product_map'         => lisfinity_is_enabled( lisfinity_get_option( 'display-product-map' ) ),
	'display_email'               => lisfinity_is_enabled( lisfinity_get_option( 'display-product-email' ) ) ?? false,
	'display_website'             => lisfinity_is_enabled( lisfinity_get_option( 'display-product-website' ) ) ?? false,
	'display_sidebar_promotion'   => 'never' !== lisfinity_get_option( 'display-sidebar-promotion' ),
	'address'                     => lisfinity_format_location( get_the_ID(), 'full' === $location_format ),
	'fallback_image'              => lisfinity_get_option( 'listing-fallback-image' )['thumbnail'] ?? '',
	'account_type'                => $account_type,
	'promoted_options'            => lisfinity_get_option( 'display-sidebar-promotion' ),
	'restricted_modal_type'       => lisfinity_get_option( 'site-fields-builder-premium-type' ) ?? 'member',
	'restricted_modal_image'      => lisfinity_get_option( 'restricted-modal-image' ),
	'listings_for_premium_only'   => lisfinity_show_listings_to_premium_only(),
];

?>
<main id="page-single" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</main>
<div id="loader" class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
	 style="z-index: 99999;">

	<div class="flex-center flex-col">
		<?php $icon_args = [
			'zoom' => 1,
		]; ?>
		<img src="<?php echo esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings.4bcf82c529.svg' ); ?>"
			 alt="<?php esc_attr_e( 'Listing Loader', 'lisfinity-core' ) ?>"/>
		<p class="mt-20 text-lg text-grey-900"><?php esc_html_e( 'Loading listing...', 'lisfinity-core' ); ?></p>
	</div>
</div>
