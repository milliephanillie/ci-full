<?php
/**
 * Template Name: Shortcodes | Product Partials | Views
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php if ( 'yes' === $args['settings']['display_views'] ) : ?>
	<?php
	$product_id    = get_the_ID();
	$product_views = carbon_get_post_meta( $product_id, 'product-views' );
	$icon = '';

	if ( ! empty ( $product_views ) ) {
		$product_views = carbon_get_post_meta( $product_id, 'product-views' );
	} else {
		$model         = new \Lisfinity\Models\Stats\StatModel();
		$product_views = $model->get_product_views( $product_id );
	}
	if ( ! empty( $args['settings']['icon_views'] ) ) {
		$icon = $args['settings']['icon_views']['value'];
	}
	?>
	<!-- Product | Views -->
	<div class="absolute top-20 left-0 flex-center w-30 h-30 rounded-full z-20 views-counts-container">
						<span
							class="absolute w-30 h-30 bg-grey-1000 opacity-50 views-counts-wrapper"></span>
		<?php if ( ! empty( $args['settings']['display_icon'] ) && is_array( $icon ) ) : ?>
			<img class="views-icon h-18 w-18"
				 src="<?php echo esc_url( $icon['url'] ); ?>"
				 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
		<?php elseif ( ! empty( $args['settings']['display_icon'] ) ) : ?>
			<i class="<?php echo esc_html__( $icon, 'lisfinity-core' ) ?> views-icon h-18 w-18"
			   aria-hidden="true"></i>
		<?php endif; ?>
		<span class="relative text-sm text-white views-counts"><?php echo esc_html( $product_views ); ?></span>
	</div>
<?php endif; ?>
