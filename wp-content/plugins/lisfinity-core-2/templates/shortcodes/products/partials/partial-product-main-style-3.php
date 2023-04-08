<?php
/**
 * Template Name: Shortcodes | Product Partials | Main Style 3
 * Description: The main area of the product box used for a style 3
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
if ( ! $bg_image ) {
	$bg_image = lisfinity_get_option( 'listing-fallback-image' )['url'] ?? '';
}
?>
<!-- Product | Main -->

<div class="lisfinity-product--main relative flex items-end h-product-2-thumb"
	 style="<?php echo 'custom' === $args['settings']['style'] && ! empty( $bg_image ) && 'custom' === $args['settings']['_image_width'] ? esc_attr( 'background-image: url(' . $bg_image . ')' ) : ''; ?>"
>
	<?php $overlay = ! empty( $args['settings']['overlay'] ) ? esc_attr( "background: linear-gradient(0deg, {$args['settings']['overlay']} 0%, rgba(255,255,255,0) 100%)" ) : ''; ?>
	<a href="<?php the_permalink(); ?>" class="ajax--lead absolute w-full h-full z-1"
	   style="<?php echo esc_attr( $overlay ); ?>"
	></a>
	<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' !== $args['settings']['_image_width'] && ! empty( $bg_image ) ) ) : ?>
		<!-- Product | Thumbnail -->
		<img src="<?php echo esc_url( $bg_image ); ?>"
			 alt="<?php esc_attr_e( 'Product Image', 'lisfinity-core' ); ?>"
			 class="absolute w-full h-full object-cover">
	<?php endif; ?>
</div>

