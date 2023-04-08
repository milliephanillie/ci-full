<?php
/**
 * Template Name: Shortcodes | Search Listings
 * Description: The file that is being used to display found listings on the search page
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Sidebar Map -->
<?php

$icon = '';

if ( ! empty( $args['settings']['icon_author_badge']['value'] ) ) {
	$icon = $args['settings']['icon_author_badge']['value'];
}
$owner   = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$options = [
	'style'                        => $args['settings']['style'] ?? '1',
	'account_type'                 => carbon_get_user_meta( $owner, 'account-type' ),
	'full_content_height'          => $args['settings']['full_content_height'] ?? 'yes',
	'display_map'                  => $args['settings']['display_map'] ?? 'on',
	'hide_show_action_bookmark'    => $args['settings']['hide_show_action_bookmark'],
	'icon_bookmark'                => $args['settings']['icon_bookmark'],
	'hide_show_product_title'      => $args['settings']['hide_show_product_title'],
	'icon_default_price'           => $args['settings']['icon_default_price'],
	'icon_auction_price'           => $args['settings']['icon_auction_price'],
	'icon_on_call_price'           => $args['settings']['icon_on_call_price'],
	'icon_on_sale_price'           => $args['settings']['icon_on_sale_price'],
	'icon_free_price'              => $args['settings']['icon_free_price'],
	'display_product_owner_logo'   => $args['settings']['display_product_owner_logo'],
	'display_label_on_sale'        => $args['settings']['display_label_on_sale'],
	'label_icon_url'               => $args['settings']['label_icon_url'],
	'display_label_default'        => $args['settings']['display_label_default'],
	'display_product_countdown'    => $args['settings']['display_product_countdown'],
	'hide_show_product_info_mark'  => $args['settings']['hide_show_product_info_mark'],
	'hide_show_product_info_place' => $args['settings']['hide_show_product_info_place'],
	'product_owner_verified'       => $args['settings']['hide_show_product_info_place'],
	'product_description_displayed'       => $args['settings']['product_description_displayed'],
	'membership-address'           => 'always',
	'reviews'                      => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'icon'                         => $icon,
	'fallback_image'               => lisfinity_get_option( 'listing-fallback-image' )['url'] ?? '',
	'product-search-map-location'  => lisfinity_get_option( 'product-search-map-location' ),
];
if ( lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ) {
	$settings['membership-address'] = lisfinity_get_option( 'membership-address' );
}
?>

<div class="page-search-listings relative" id="search-listings" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
