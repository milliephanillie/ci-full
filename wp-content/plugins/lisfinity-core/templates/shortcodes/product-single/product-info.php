<?php
/**
 * Template Name: Shortcodes | Product Info
 * Description: The file that is being used to display product info shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php $settings = [
	'membership_listings_bids' => 'always',
	'text_place_bid' => $args['settings']['text_place_bid'],
	'buy_now_text' => $args['settings']['buy_now_text'],
	'buy_now_text_hover' => $args['settings']['buy_now_text_hover'],
	'display_price' => $args['settings']['display_price'],
	'place_icon_price' => $args['settings']['place_icon_price'],
	'icon_price' => $args['settings']['icon_price'],
	'display_countdown' => $args['settings']['display_countdown'],
	'place_icon_countdown_price' => $args['settings']['place_icon_countdown_price'],
	'icon_price_countdown' => $args['settings']['icon_price_countdown'],
	'place_icon_negotiable_label_icon_price' => $args['settings']['place_icon_negotiable_label_icon_price'],
	'icon_price_negotiable_label_icon' => $args['settings']['icon_price_negotiable_label_icon'],
	'place_price_on_call_icon_price' => $args['settings']['place_price_on_call_icon_price'],
	'icon_price_on_call_icon' => $args['settings']['icon_price_on_call_icon'],
	'place_price_free_icon_price' => $args['settings']['place_price_free_icon_price'],
	'icon_price_free_icon' => $args['settings']['icon_price_free_icon'],
];
if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_listings_bids'] = lisfinity_get_option('membership-listings-bids');
}

?>
<div class="elementor-product-info"  data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
