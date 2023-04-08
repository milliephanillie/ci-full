<?php
/**
 * Template Name: Shortcodes | Product Partials | Content
 * Description: The main area of the product box used to display product content
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>

<!-- Product | Content -->
<?php if ( 'yes' === $args['options']['promoted_sign'] ) : ?>
	<?php $is_promoted = true; ?>
<?php else: ?>
	<?php $is_promoted = lisfinity_is_promoted_product( $product_id, 'home-ads' ); ?>
<?php endif; ?>
<div class="lisfinity-product--image relative flex flex-col z-1 <?php echo in_array( $args['settings']['style'], [
	'3',
	'4',
	'custom'
] ) ? '' : esc_attr( 'py-30 px-24' ); ?>">

	<!-- Product | Meta -->

	<div class="lisfinity-product--meta-wrapper flex items-center justify-between mb-10">
		<?php $meta_args = [
			'wc_product'   => $wc_product,
			'product_id'   => $product_id,
			'product_type' => $args['product_type'],
			'text_color'   => $args['text_color'],
		]; ?>
		<?php include lisfinity_get_template_part( "product-meta-{$args['product_type']}", 'shortcodes/products', $meta_args ); ?>
	</div>

</div>

<!-- Product | Content -->
<div class="lisfinity-product--content">

	<!-- Product | Title -->
	<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] ) && 'yes' === $args['settings']['hide_show_product_title'] ) : ?>
		<div class="lisfinity-product--title <?php echo 'custom' === $args['settings']['style'] ? esc_attr( 'mb-14' ) : ''; ?>">
			<h6 class="product--title font-semibold text-<?php echo esc_attr( $args['text_color'] ); ?>">
				<a href="<?php the_permalink(); ?>" class="flex items-start ajax--lead">
					<?php if ( $is_promoted ) : ?>

						<?php $font_size = $args['settings']['promoted_icon_size']['size'] ?? ''; ?>
						<?php $font_unit = $args['settings']['promoted_icon_size']['unit'] ?? ''; ?>

						<span
							class="label--promoted relative -top-2 mr-4 py-2 px-4 border rounded text-xs text-yellow-600"><?php echo ! empty( $args['settings']['promoted_text'] ) ? esc_html( $args['settings']['promoted_text'] ) : esc_html__( 'AD', 'lisfinity-core' );; ?></span>
					<?php endif; ?>
					<?php the_title(); ?>
				</a>
			</h6>
		</div>
	<?php endif; ?>
</div>
