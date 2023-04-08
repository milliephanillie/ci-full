<?php
/**
 * Template Name: Shortcodes | Product Partial | Icon
 * Description: The file that is being used to display products meta fields
 *
 * @author pebas
 * @package templates/shortcodes/products/partials
 * @version 1.0.0
 *
 * @var $price_type
 */

?>

<?php
$image_on_sale    = $args['settings']['on_sale_icon_url'] ?? '';
$image_auction    = $args['settings']['auction_icon_url'] ?? '';
$image_on_call    = $args['settings']['on_call_icon_url'] ?? '';
$image_free       = $args['settings']['free_icon_url'] ?? '';
$image_fixed      = $args['settings']['fixed_icon_url'] ?? '';
$image_negotiable = $args['settings']['negotiable_icon_url'] ?? '';
?>

<span class="lisfinity-product--meta__icon mr-8">
<?php switch ( $price_type ) : ?>
<?php case 'on-sale': ?>

		<?php $icon_args = [
			'width'  => 'w-14',
			'height' => 'h-14',
			'fill'   => 'white' === $args['text_color'] ? 'fill-white' : 'fill-icon-sale',

		]; ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && empty( $image_on_sale['url'] ) ) ) : ?>
			<?php include lisfinity_get_template_part( 'tag', 'partials/icons', $icon_args ); ?>

		<?php else: ?>
			<img class="fill-icon-sale"
				 src="<?php echo esc_url( $image_on_sale['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">

		<?php endif; ?>
		<?php break; ?>
	<?php case 'auction': ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && ( empty( $image_auction['url'] ) ) ) ) : ?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="w-14 h-14 auction-products-icon fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'field-icon' ); ?>">
				<path d="M94.6,45.2L55.4,6c-1.1-1.1-2.8-1.4-4.1-0.8l-22.8,9.2c-1.1,0.5-2,1.5-2.2,2.8c-0.2,1.2,0.2,2.5,1,3.4l19.1,19.1L5.3,80.8
					c-0.7,0.7-1.1,1.7-1.1,2.7c0,1,0.4,1.9,1.1,2.6l8,8h0c0.7,0.7,1.7,1.1,2.7,1.1c1,0,1.9-0.4,2.7-1.1l41.1-41.1l13.6,13.6l0,0
					c0.7,0.7,1.7,1.1,2.7,1.1c1,0,1.9-0.4,2.7-1.1l15.9-15.9c0.7-0.7,1.1-1.7,1.1-2.7S95.4,45.9,94.6,45.2z M16,88.9l-5.5-5.5l39-39
					l5.5,5.5L16,88.9z M76,61.4l-43-43l19.3-7.8l37.2,37.2L76,61.4z"/>
			</svg>
		<?php else: ?>
			<img class="auction-products-icon"
				 src="<?php echo esc_url( $image_auction['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
		<?php endif; ?>
		<?php break; ?>

	<?php case 'price_on_call': ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && ( empty( $image_on_call['url'] ) ) ) ) : ?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="w-14 h-14 on-call-products-icon fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'icon-call' ); ?>">
				<g>
					<path d="M74.8,98.6h-1.3c-7-0.3-18.9-5.3-19.4-5.5H54c-10-4.7-19.2-11.3-27.4-19.5c-2-2-4-4.1-6.1-6.5c-8-9.6-13.1-18.3-16.3-27.4
						c-0.9-2.4-1.5-4.6-2-6.6c-1.4-5.9-0.6-12.1,2-17.5C5,14,5.8,12.9,6.6,12l6.8-6.8c2.1-2.4,5.3-3.8,8.6-3.8h0.3
						c3.4,0.2,6.2,1.4,8.6,3.8l11.4,11.4c5.3,5.3,5.3,12.2,0,17.6l-4.6,4.6L61,62.1l4.8-4.8c0.9-0.9,3.8-3.8,8.8-3.8
						c3.9,0,6.8,2.2,8.6,3.8l0.1,0.1l5.7,5.7c2.9,2.9,5.7,5.6,5.8,5.6c4.5,4.5,4.9,10.9,1.1,16.9C91.2,92.5,81.4,98.6,74.8,98.6z
						 M73.7,93.1h1.2c4.7,0,12.8-5.2,16.4-10.5c2.4-3.7,2.3-7.3-0.3-9.9l0,0c0,0-2.9-2.7-5.8-5.7l-5.6-5.6c-0.8-0.7-2.7-2.4-4.9-2.4
						c-2.8,0-4.2,1.5-5,2.2L64.8,66c-1,1-2.4,1.6-3.9,1.6l0,0c-1.5,0-2.8-0.6-3.9-1.6L33.8,42.6c-2.1-2.1-2.1-5.6,0-7.7l4.6-4.6
						c3.1-3.2,3.1-6.8,0-9.9L27.1,9.1c-1.3-1.3-2.9-2.1-4.9-2.2h-0.1c-1.8,0-3.4,0.7-4.5,2l-0.1,0.2l-7,6.9c-0.2,0.2-0.7,0.8-1.4,2.1
						C7,22.3,6.5,27.2,7.5,31.9c0.4,1.8,1,3.7,1.8,5.9c3,8.5,7.9,16.6,15.4,25.7c1.9,2.1,3.9,4.3,5.8,6.2c7.7,7.7,16.4,13.9,25.8,18.4
						C59.5,89.4,68.7,92.9,73.7,93.1z"/>
				</g>
			</svg>

		<?php else: ?>
			<img class="on-call-products-icon"
				 src="<?php echo esc_url( $image_on_call['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
		<?php endif; ?>
		<?php break; ?>
	<?php case 'free': ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && ( empty( $image_free['url'] ) ) ) ) : ?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="w-14 h-14 free-products-icon fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'icon-gift' ); ?>">
				<path d="M87.2,28H71.3c5.3-1.6,9.9-4,11.2-7.7c1.3-3.4-0.1-7-4-10.7C74.2,5.5,69.8,4,65.4,5.2c-8,2.1-13.2,12.5-15.5,18.4
					c-2.3-5.9-7.5-16.3-15.5-18.4C29.9,4,25.5,5.5,21.2,9.6h0c-3.9,3.7-5.3,7.3-4,10.7c1.4,3.7,6,6.1,11.2,7.7H12.8
					C10.7,28,9,29.7,9,31.9v19c0,1.6,0.9,2.9,2.3,3.5v36.9c0,2.2,1.6,3.8,3.8,3.8h70c2.2,0,3.8-1.6,3.8-3.8v-37c1.3-0.6,2.2-1.9,2.2-3.5
					v-19C91,29.7,89.4,28,87.2,28z M85.5,49.2H85H52.9V33.5h32.7V49.2z M66.7,10.5c0.5-0.1,1-0.2,1.5-0.2c2,0,4.2,1.1,6.5,3.3
					c1.2,1.2,3.2,3.3,2.6,4.8c-1.3,3.4-11.8,6-22.3,6.9C57.4,19.7,61.6,11.9,66.7,10.5z M22.3,18.4c-0.6-1.5,1.4-3.6,2.6-4.8h0
					c2.9-2.7,5.5-3.7,8-3.1c5.1,1.3,9.4,9.2,11.7,14.8C34.2,24.4,23.6,21.8,22.3,18.4z M14.5,33.5h32.9v15.7H15.1h-0.6V33.5z M16.7,54.7
					h30.6v35H16.7V54.7z M83.4,89.7H52.9v-35h30.5V89.7z"/>
			</svg>
		<?php else: ?>
			<img class="free-products-icon"
				 src="<?php echo esc_url( $image_free['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">

		<?php endif; ?>
		<?php break; ?>
	<?php case 'negotiable': ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && ( empty( $image_negotiable['url'] ) ) ) ) : ?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="w-14 h-14 negotiable-products-icon fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'icon-gift' ); ?>">
				<path
					d="m92.5 31.7h-11.3v-17.9c0-1.2-.6-2.3-1.5-3s-2.2-.9-3.3-.6l-71.2 20.8c-.9.2-1.4 1-1.4 1.9v27.9 25.3c0 2.1 1.7 3.8 3.8 3.8h85c2.1 0 3.8-1.7 3.8-3.8v-50.6c-.1-2.1-1.8-3.8-3.9-3.8zm-16.8 0h-53.4l53.4-15.6zm-66.4 52.7v-47.2h81.5v13.3h-25.1c-6.5 0-11.8 5.3-11.8 11.8s5.3 11.8 11.8 11.8h25.1v10.3zm81.5-28.3v12.5h-25.1c-3.4 0-6.3-2.8-6.3-6.3s2.8-6.3 6.3-6.3h25.1z"/>

			</svg>
		<?php else: ?>
			<img class="negotiable-products-icon"
				 src="<?php echo esc_url( $image_negotiable['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">

		<?php endif; ?>
		<?php break; ?>
	<?php case 'fixed': ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && empty( $image_fixed['url'] ) ) ) : ?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="w-14 h-14 fixed-products-icon fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'icon-gift' ); ?>">
				<path
					d="m92.5 31.7h-11.3v-17.9c0-1.2-.6-2.3-1.5-3s-2.2-.9-3.3-.6l-71.2 20.8c-.9.2-1.4 1-1.4 1.9v27.9 25.3c0 2.1 1.7 3.8 3.8 3.8h85c2.1 0 3.8-1.7 3.8-3.8v-50.6c-.1-2.1-1.8-3.8-3.9-3.8zm-16.8 0h-53.4l53.4-15.6zm-66.4 52.7v-47.2h81.5v13.3h-25.1c-6.5 0-11.8 5.3-11.8 11.8s5.3 11.8 11.8 11.8h25.1v10.3zm81.5-28.3v12.5h-25.1c-3.4 0-6.3-2.8-6.3-6.3s2.8-6.3 6.3-6.3h25.1z"/>

			</svg>

		<?php else: ?>
			<img class="fixed-products-icon"
				 src="<?php echo esc_url( $image_fixed['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
		<?php endif; ?>
		<?php break; ?>
	<?php default: ?>
		<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] && ( empty( $image_fixed['url'] ) ) ) ) : ?>
			<svg enable-background="new 0 0 100 100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"
				 class="w-14 h-14 fill-<?php echo 'white' === $args['text_color'] ? esc_attr( 'white' ) : esc_attr( 'field-icon' ); ?>">
            <path
				d="m92.5 31.7h-11.3v-17.9c0-1.2-.6-2.3-1.5-3s-2.2-.9-3.3-.6l-71.2 20.8c-.9.2-1.4 1-1.4 1.9v27.9 25.3c0 2.1 1.7 3.8 3.8 3.8h85c2.1 0 3.8-1.7 3.8-3.8v-50.6c-.1-2.1-1.8-3.8-3.9-3.8zm-16.8 0h-53.4l53.4-15.6zm-66.4 52.7v-47.2h81.5v13.3h-25.1c-6.5 0-11.8 5.3-11.8 11.8s5.3 11.8 11.8 11.8h25.1v10.3zm81.5-28.3v12.5h-25.1c-3.4 0-6.3-2.8-6.3-6.3s2.8-6.3 6.3-6.3h25.1z"/>
        </svg>

		<?php else: ?>
			<img class="products-icon"
				 src="<?php echo esc_url( $image_fixed['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
		<?php endif; ?>
		<?php break; ?>
	<?php endswitch; ?>
</span>
