<?php
/**
 * Template Name: Shortcodes | Product Partials | Main
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$bg_image = get_the_post_thumbnail_url( $product_id );
if ( false === $bg_image ) {
	$bg_image = lisfinity_get_option( 'listing-fallback-image' )['url'] ?? '';
}
?>
<?php $promoted_bump_color = lisfinity_is_promoted_product( $product_id, 'bump-color' ); ?>
<!-- Product | Main -->

<div class="lisfinity-product--main relative flex items-end h-product-thumb">
	<?php
	$overlay = '';
	if ( $promoted_bump_color && ! empty( $args['style'] ) && 1 === $args['style'] ) {
		$overlay = "background: linear-gradient(0deg, rgba(100,109,90,1) 0%, rgba(255,255,255,0) 100%)";
	} else if ( ! empty( $args['settings']['overlay'] ) ) {
		$overlay = "background: linear-gradient(0deg, {$args['settings']['overlay']} 0%, rgba(255,255,255,0) 100%)";
	}
	?>
	<a href="<?php the_permalink(); ?>" class="ajax--lead absolute w-full h-full z-1"
	></a>
	<?php if ( ! empty( $bg_image ) ) : ?>
		<!-- Product | Thumbnail -->
		<img src="<?php echo esc_url( $bg_image ); ?>"
			 alt="<?php esc_attr_e( 'Product Image', 'lisfinity-core' ); ?>"
			 class="absolute w-full h-full object-cover">
	<?php endif; ?>
	<!-- Product | Content -->
	<?php if ( 'yes' === $args['options']['promoted_sign'] ) : ?>
		<?php $is_promoted = true; ?>
	<?php else: ?>
		<?php $is_promoted = lisfinity_is_promoted_product( $product_id, $args['options']['promo_type'] ?? 'home-ads' ); ?>
	<?php endif; ?>
	<div class="lisfinity-product--content relative flex flex-col py-30 px-24 w-full z-9">

		<!-- Product | Meta -->
		<div class="lisfinity-product--meta-wrapper flex items-center justify-between mb-16">
			<?php $meta_args = [
				'wc_product'   => $wc_product,
				'product_id'   => $product_id,
				'product_type' => $args['product_type'],
				'text_color'   => $args['text_color'],
			]; ?>
			<?php include lisfinity_get_template_part( "product-meta-{$args['product_type']}", 'shortcodes/products', $meta_args ); ?>
		</div>

		<!-- Product | Title -->

		<div class="lisfinity-product--title">

			<h6 class="product--title font-semibold text-2xl text-white">
				<a href="<?php the_permalink(); ?>" class="flex items-start ajax--lead">
					<?php if ( $is_promoted ) : ?>
						<span
							class="label--promoted relative -top-2 mr-4 py-2 px-4 border rounded text-xs text-yellow-600"><?php esc_html_e( 'AD', 'lisfinity-core' ); ?></span>
					<?php endif; ?>
					<?php the_title(); ?>
				</a>
			</h6>
		</div>

	</div>
</div>


