<?php
/**
 * Template Name: Shortcodes | Product Partial | Meta
 * Description: The file that is being used to display products meta fields
 *
 * @author pebas
 * @package templates/shortcodes/products
 * @version 1.0.0
 *
 * @var $meta_args
 * @var $args
 */
?>

<?php
if ($args['settings']['label_icon_url']) {
	$image_on_sale_label_icon = $args['settings']['label_icon_url']['value'];
}
?>

<?php $price_type = carbon_get_post_meta($meta_args['product_id'], 'product-price-type'); ?>
<?php $sale_price = $meta_args['wc_product']->get_sale_price(); ?>
<?php $price_type = !empty($sale_price) ? 'on-sale' : $price_type; ?>
<?php $start_price = carbon_get_post_meta($meta_args['product_id'], 'product-auction-start-price'); ?>
<?php
$price = $meta_args['wc_product']->get_price();
if (is_numeric($price)) {
	$price = $price * lisfinity_get_chosen_currency_rate();
}
$price = lisfinity_get_price_html($price);
$price_option = lisfinity_get_option('product-start-price-default');
if ('last' === $price_option) {
	$bids_model = new \Lisfinity\Models\Bids\BidModel();
	$last_bid = $bids_model->where('product_id', get_the_ID())->get('1', 'ORDER BY id DESC', 'amount', 'col');
	if (!empty($last_bid[0])) {
		$price = lisfinity_get_price_html($last_bid[0] * lisfinity_get_chosen_currency_rate());
	}
}
if ('start' === $price_option && !empty($start_price) && !empty($last_bid[0])) {
	$price = lisfinity_get_price_html($start_price);
}
?>
<?php if ('custom' !== $args['settings']['style'] ||
	('custom' === $args['settings']['style'])
	&& (
		('yes' === $args['settings']['display_fixed_price'] && 'fixed' === $price_type) ||
		('yes' === $args['settings']['display_auction_price'] && 'auction' === $price_type) ||
		('yes' === $args['settings']['display_price_free'] && 'free' === $price_type) ||
		('yes' === $args['settings']['display_price_on_call'] && 'price_on_call' === $price_type) ||
		('yes' === $args['settings']['display_negotiable_price'] && 'negotiable' === $price_type) ||
		('yes' === $args['settings']['display_price_on_sale'] && 'on-sale' === $price_type) || ( 'per_week' === $price_type || 'per_month' === $price_type || 'per_day' === $price_type || 'per_hour' === $price_type)
	)
) : ?>
	<div class="lisfinity-product--meta flex items-baseline">
		<?php include lisfinity_get_template_part('product-type-icon', 'shortcodes/products/partials', $price_type); ?>
		<?php if ('price_on_call' === $price_type) : ?>
			<?php if (in_array($args['settings']['style'], ['3', '4', 'custom'])) : ?>
				<span
					class="lisfinity-product--meta__price text-blue-600 font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php esc_html_e('Price on call', 'lisfinity-core'); ?></span>
			<?php else: ?>
				<span
					class="lisfinity-product--meta__price text-white <?php echo esc_attr($args['text_color']); ?> font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php esc_html_e('Price on call', 'lisfinity-core'); ?></span>
			<?php endif; ?>
		<?php elseif ('free' === $price_type) : ?>
			<?php if (in_array($args['settings']['style'], ['3', '4', 'custom'])) : ?>
				<span
					class="lisfinity-product--meta__price text-green-900 font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php esc_html_e('Free', 'lisfinity-core'); ?></span>
			<?php else: ?>
				<span
					class="lisfinity-product--meta__price text-white font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php esc_html_e('Free', 'lisfinity-core'); ?></span>
			<?php endif; ?>
		<?php else : ?>
			<?php if (in_array($args['settings']['style'], ['3', '4', 'custom'])) : ?>
				<?php if (!empty($sale_price)) : ?>
					<span
						class="lisfinity-product--meta__price price-on-sale flex flex-row-reverse text-red-1100 font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php echo $meta_args['wc_product']->get_price_html() ; ?></span>
				<?php else: ?>
					<span
						class="lisfinity-product--meta__price flex flex-row-reverse <?php echo esc_attr("price-{$price_type}"); ?> text-<?php echo esc_attr($args['text_color']); ?> font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php echo wp_kses_post($price); ?></span>
				<?php endif; ?>
			<?php else: ?>
				<span
					class="lisfinity-product--meta__price flex flex-row-reverse <?php echo esc_attr("price-{$price_type}"); ?> text-<?php echo esc_attr($args['text_color']); ?> font-<?php echo '3' === $args['settings']['style'] ? esc_attr('regular') : esc_attr('semibold'); ?>"><?php echo wp_kses_post($price); ?></span>
			<?php endif; ?>

			<?php if ('per_week' === $price_type) : ?>
				<span
					class="ml-4 text-<?php echo esc_attr($args['text_color']); ?>"><?php esc_html_e(' / week', 'lisfinity-core'); ?></span>
			<?php elseif ('per_hour' === $price_type) : ?>
				<span
					class="ml-4 text-<?php echo esc_attr($args['text_color']); ?>"><?php esc_html_e(' / hour', 'lisfinity-core'); ?></span>
			<?php elseif ('per_day' === $price_type) : ?>
				<span
					class="ml-4 text-<?php echo esc_attr($args['text_color']); ?>"><?php esc_html_e(' / day', 'lisfinity-core'); ?></span>
			<?php elseif ('per_month' === $price_type): ?>

				<span
					class="ml-4 text-<?php echo esc_attr($args['text_color']); ?>"><?php esc_html_e(' / month', 'lisfinity-core'); ?></span>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ('on-sale' === $price_type) : ?>
	<?php if (in_array($args['settings']['style'], ['3', '4', 'custom'])) : ?>
		<?php if ('custom' !== $args['settings']['style'] || ('custom' === $args['settings']['style'] && 'yes' === $args['settings']['display_label_on_sale'])) : ?>

			<div class="lisfinity-product--meta flex absolute label--sale">
				<div
					class="lisfinity-product--meta__icon label-on-sale-icon-wrapper flex items-center justify-center pr-6 h-24 w-product-label-sale rounded bg-red-600 text-sm text-white">
					<?php if ('custom' !== $args['settings']['style'] || 'yes' !== $args['settings']['label_icon'] || ('custom' === $args['settings']['style'] && "" === $image_on_sale_label_icon && 'yes' === $args['settings']['label_icon'])) : ?>
						<?php $icon_args = [
							'width' => 'w-14',
							'height' => 'h-14',
							'fill' => 'fill-white',
							'margin' => 'mr-6',
						]; ?>
						<?php include lisfinity_get_template_part('tag', 'partials/icons', $icon_args); ?>
					<?php elseif (is_array($image_on_sale_label_icon)) : ?>
						<img class="label-on-sale-icon"
							 src="<?php echo esc_url($image_on_sale_label_icon['url']); ?>"
							 alt="<?php echo esc_html__('icon', 'lisfinity-core'); ?>">
					<?php else : ?>
						<i class="<?php echo esc_html__($image_on_sale_label_icon, 'lisfinity-core') ?> label-on-sale-icon"
						   aria-hidden="true"></i>
					<?php endif; ?>
					<?php esc_html_e('Sale', 'lisfinity-core'); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php elseif ('negotiable' === $price_type) : ?>
	<?php if ('custom' !== $args['settings']['style'] || ('custom' === $args['settings']['style'] && 'yes' === $args['settings']['display_label_negotiable'])) : ?>
		<div class="lisfinity-product--meta flex">

			<div
				class="lisfinity-product--meta__icon flex items-center text-sm <?php echo 'white' === $meta_args['text_color'] ? esc_attr('text-white') : esc_attr('text-grey-600'); ?>">
				<?php esc_html_e('Negotiable', 'lisfinity-core'); ?>
			</div>

		</div>
	<?php endif; ?>
<?php elseif ('fixed' === $price_type) : ?>
	<?php if ('custom' !== $args['settings']['style'] || ('custom' === $args['settings']['style'] && 'yes' === $args['settings']['display_label_fixed'])) : ?>
		<div class="lisfinity-product--meta flex">
			<div
				class="lisfinity-product--meta__icon flex items-center text-sm <?php echo 'white' === $meta_args['text_color'] ? esc_attr('text-white') : esc_attr('text-grey-600'); ?>">
				<?php esc_html_e('Fixed', 'lisfinity-core'); ?>
			</div>
		</div>
	<?php endif; ?>
<?php elseif ('auction' === $price_type) : ?>
	<?php $auction_status = carbon_get_post_meta($meta_args['product_id'], 'product-auction-status'); ?>
	<?php $auction_ends = carbon_get_post_meta($meta_args['product_id'], 'product-auction-ends'); ?>
	<?php $current_time = current_time('timestamp'); ?>
	<?php if ('active' === $auction_status && $current_time < $auction_ends) : ?>

		<?php if ('custom' !== $args['settings']['style'] || ('custom' === $args['settings']['style'] && 'yes' === $args['settings']['display_product_countdown'])) : ?>
			<div class="lisfinity-product--meta flex">
				<div class="lisfinity-product--meta__icon flex items-center">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
						 xmlns:xlink="http://www.w3.org/1999/xlink"
						 x="0px" y="0px"
						 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
						 class="mr-4 w-16 h-16 <?php echo 'white' === $meta_args['text_color'] ? esc_attr('fill-white') : esc_attr('fill-field-icon'); ?>">
					<g>
						<path d="M83.4,33.2c2.9-2.5,4.7-6.2,4.7-10.1c0-7.4-6-13.4-13.4-13.4c-5.4,0-10,3.2-12.1,7.9c-3.1-1-6.4-1.7-9.9-1.9v-7h5.2
							c1.5,0,2.8-1.2,2.8-2.8s-1.2-2.8-2.8-2.8H42.1c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h5.2v7c-3.1,0.2-6.2,0.8-9.1,1.7
							c-2.2-4.5-6.8-7.5-12-7.5c-7.4,0-13.4,6-13.4,13.4c0,3.6,1.5,7,4.2,9.5c-4.7,6.6-7.5,14.7-7.5,23.4c0,22.4,18.2,40.6,40.6,40.6
							c22.4,0,40.6-18.2,40.6-40.6C90.6,47.7,87.9,39.8,83.4,33.2z M74.7,15.2c4.4,0,7.9,3.5,7.9,7.9c0,2.2-1,4.3-2.6,5.8
							c-3.5-3.8-7.6-6.9-12.3-9.2C69,17,71.7,15.2,74.7,15.2z M18.2,23.3c0-4.4,3.5-7.9,7.9-7.9c2.9,0,5.5,1.6,6.8,4
							c-4.8,2.2-9,5.3-12.6,9.1C19,27.1,18.2,25.2,18.2,23.3z M50,91.3c-19.3,0-35.1-15.7-35.1-35.1c0-8.2,2.8-15.8,7.6-21.7
							c0.3-0.2,0.5-0.4,0.7-0.7c0-0.1,0.1-0.1,0.1-0.2c3.7-4.3,8.3-7.7,13.6-9.9c0,0,0.1,0,0.1,0c0.4-0.1,0.7-0.2,1-0.4
							c3.5-1.3,7.3-2,11.2-2.1c0.2,0.1,0.5,0.1,0.8,0.1s0.5-0.1,0.8-0.1c4.4,0.1,8.5,1,12.3,2.5c0.2,0.1,0.3,0.2,0.5,0.2
							c5.3,2.2,9.9,5.7,13.5,10.1c0.1,0.2,0.3,0.4,0.5,0.6c4.7,6,7.5,13.5,7.5,21.6C85.1,75.5,69.3,91.3,50,91.3z"/>
						<path d="M70.6,55.7H52.7V34.9c0-1.5-1.2-2.8-2.8-2.8s-2.8,1.2-2.8,2.8v20.8h-4.2c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h4.2v4.2
							c0,1.5,1.2,2.8,2.8,2.8s2.8-1.2,2.8-2.8v-4.2h17.8c1.5,0,2.8-1.2,2.8-2.8S72.1,55.7,70.6,55.7z"/>
					</g>
				</svg>
					<span class="countdown <?php echo esc_attr("text-{$meta_args['text_color']}"); ?> font-regular"
						  data-auction-ends="<?php echo esc_attr($auction_ends); ?>"><?php esc_html_e('0:00:00', 'lisfinity-core'); ?></span>
				</div>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="lisfinity-product--meta flex">
			<div class="lisfinity-product--meta__icon flex items-center">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
					 xmlns:xlink="http://www.w3.org/1999/xlink"
					 x="0px" y="0px"
					 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
					 class="mr-4 w-16 h-16 <?php echo 'white' === $meta_args['text_color'] ? esc_attr('fill-white') : esc_attr('fill-field-icon'); ?>">
					<g>
						<path d="M83.4,33.2c2.9-2.5,4.7-6.2,4.7-10.1c0-7.4-6-13.4-13.4-13.4c-5.4,0-10,3.2-12.1,7.9c-3.1-1-6.4-1.7-9.9-1.9v-7h5.2
							c1.5,0,2.8-1.2,2.8-2.8s-1.2-2.8-2.8-2.8H42.1c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h5.2v7c-3.1,0.2-6.2,0.8-9.1,1.7
							c-2.2-4.5-6.8-7.5-12-7.5c-7.4,0-13.4,6-13.4,13.4c0,3.6,1.5,7,4.2,9.5c-4.7,6.6-7.5,14.7-7.5,23.4c0,22.4,18.2,40.6,40.6,40.6
							c22.4,0,40.6-18.2,40.6-40.6C90.6,47.7,87.9,39.8,83.4,33.2z M74.7,15.2c4.4,0,7.9,3.5,7.9,7.9c0,2.2-1,4.3-2.6,5.8
							c-3.5-3.8-7.6-6.9-12.3-9.2C69,17,71.7,15.2,74.7,15.2z M18.2,23.3c0-4.4,3.5-7.9,7.9-7.9c2.9,0,5.5,1.6,6.8,4
							c-4.8,2.2-9,5.3-12.6,9.1C19,27.1,18.2,25.2,18.2,23.3z M50,91.3c-19.3,0-35.1-15.7-35.1-35.1c0-8.2,2.8-15.8,7.6-21.7
							c0.3-0.2,0.5-0.4,0.7-0.7c0-0.1,0.1-0.1,0.1-0.2c3.7-4.3,8.3-7.7,13.6-9.9c0,0,0.1,0,0.1,0c0.4-0.1,0.7-0.2,1-0.4
							c3.5-1.3,7.3-2,11.2-2.1c0.2,0.1,0.5,0.1,0.8,0.1s0.5-0.1,0.8-0.1c4.4,0.1,8.5,1,12.3,2.5c0.2,0.1,0.3,0.2,0.5,0.2
							c5.3,2.2,9.9,5.7,13.5,10.1c0.1,0.2,0.3,0.4,0.5,0.6c4.7,6,7.5,13.5,7.5,21.6C85.1,75.5,69.3,91.3,50,91.3z"/>
						<path d="M70.6,55.7H52.7V34.9c0-1.5-1.2-2.8-2.8-2.8s-2.8,1.2-2.8,2.8v20.8h-4.2c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h4.2v4.2
							c0,1.5,1.2,2.8,2.8,2.8s2.8-1.2,2.8-2.8v-4.2h17.8c1.5,0,2.8-1.2,2.8-2.8S72.1,55.7,70.6,55.7z"/>
					</g>
				</svg>
				<span
					class="text-<?php echo esc_attr($meta_args['text_color']); ?> font-regular"><?php esc_html_e('Auction Ended', 'lisfinity-core'); ?></span>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<?php

$product_id = get_the_ID();
$earlier = strtotime(get_post($product_id)->post_date);
$now = strtotime(date("Y-m-d h:i:s"));
$datediff = $now - $earlier;
$hours = round($datediff / (60 * 60));
$message = '';

if ($hours > 1 && $hours < 24) {
	$message = "$hours hours ago";
}
if ($hours > 24) {
	$days = round($hours / 24);
	$message = "$days days ago";
} else {
	$message = "Less then an hour ago";
}
?>
<?php if ('yes' === $args['settings']['display_date']) : ?>
	<div class="due-date"><?php echo esc_html(get_the_date($args['settings']['date_format'])); ?></div>
<?php elseif ('yes' === $args['settings']['display_date_message']) : ?>
	<div class="due-date"><?php echo esc_html($message); ?></div>
<?php endif; ?>
