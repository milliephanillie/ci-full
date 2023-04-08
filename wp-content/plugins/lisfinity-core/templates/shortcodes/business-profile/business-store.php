<?php
/**
 * Template Name: Shortcodes | Login Form
 * Description: The file that is being used to display login form shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>
<?php
$icon = '';

if ( ! empty( $args['settings']['icon_author_badge']['value'] ) ) {
	$icon = $args['settings']['icon_author_badge']['value'];
}

$owner    = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$settings = [
	'filter_icon'                 => $args['settings']['filter_icon'],
	'account_type'                => carbon_get_user_meta( $owner, 'account-type' ),
	'filter_text'                 => $args['settings']['filter_text'],
	'custom_reset_icon'           => $args['settings']['reset_icon'],
	'reset_text'                  => $args['settings']['reset_text'],
	'business_button_text'        => $args['settings']['business_button_text'],
	'business_button_submit_icon' => $args['settings']['business_button_submit_icon'],
	'business_items_submit_icon'  => $args['settings']['business_items_submit_icon'],
	'd_button_text'               => $args['settings']['d_button_text'],
	'style'                       => $args['settings']['style'],
	'hide_show_action_bookmark'   => $args['settings']['hide_show_action_bookmark'],
	'icon_bookmark'               => $args['settings']['icon_bookmark'],
	'hide_show_product_title'     => $args['settings']['hide_show_product_title'],
	'icon_default_price'          => $args['settings']['icon_default_price'],
	'icon_auction_price'          => $args['settings']['icon_auction_price'],
	'icon_on_call_price'          => $args['settings']['icon_on_call_price'],
	'icon_on_sale_price'          => $args['settings']['icon_on_sale_price'],
	'icon_free_price'             => $args['settings']['icon_free_price'],
	'display_product_owner_logo'  => $args['settings']['display_product_owner_logo'],
	'display_label_on_sale'       => $args['settings']['display_label_on_sale'],
	'label_icon_url'              => $args['settings']['label_icon_url'],
	'display_label_default'       => $args['settings']['display_label_default'],
	'display_product_countdown'   => $args['settings']['display_product_countdown'],
	'product-owner-verified'      => lisfinity_get_option( 'product-owner-verified' ),
	'icon'                        => $icon,
	'membership-address'          => 'always',
	'reviews'                     => 'no' !== carbon_get_theme_option( 'business-reviews-enable' ),
	'product-search-map-location' => lisfinity_get_option( 'product-search-map-location' ),
	'listings_per_page'           => lisfinity_get_option( 'search-products-per-page' ),
];
if ( lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ) {
	$settings['membership-address'] = lisfinity_get_option( 'membership-address' );
}

?>

<div class="elementor-business-store" id="business-store"
	 data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
