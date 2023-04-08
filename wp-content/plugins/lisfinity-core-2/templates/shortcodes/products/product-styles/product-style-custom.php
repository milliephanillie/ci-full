<?php
/**
 * Template Name: Shortcodes | Products | Style Custom
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
$is_query = true;
if ( ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] && get_the_ID() === (int) lisfinity_get_option( 'page-single-listing' ) && carbon_get_post_meta( get_the_ID(), 'elementor-mockup-product' ) ) || is_singular( 'product' ) ) {
	$is_query = false;
}
?>

<?php if ( $args['products']->have_posts() ) : ?>

	<?php $args['bookmarks'] = lisfinity_get_bookmarks(); ?>
<div class="lisfinity-products <?php echo esc_attr( "lisfinity-products--{$args['settings']['style']}" ); ?>">
	<?php if ( ! $is_query ) : ?>
		<?php $header_size = $args['settings']['header_size'] ?? 'h2'; ?>
		<?php if ( ! empty ( $args['settings']['title_single_ad'] ) ): ?>
			<div class="row single-ad-products-title-row">
			<<?php echo esc_attr( $header_size ); ?> class="single-ad-products-title
			elementor-heading-title elementor-inline-editing">
			<?php echo esc_attr( $args['settings']['title_single_ad'] ) ?>
			</<?php echo esc_attr( $header_size ) ?>>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<div class="row <?php echo 'yes' === $args['settings']['carousel'] ? esc_attr( 'product--carousel' ) : ''; ?>">
		<?php $count = 1; ?>
		<?php while ( $args['products']->have_posts() ) : ?>
			<?php $args['products']->the_post(); ?>
			<?php $product_id = get_the_ID(); ?>
			<?php $wc_product = wc_get_product( $product_id ); ?>
			<?php
			$user_id  = carbon_get_post_meta( $product_id, 'product-owner' );
			$verified = carbon_get_user_meta( $user_id, 'verified' );
			?>
			<?php $args['product_type'] = carbon_get_post_meta( $product_id, 'product-type' ); ?>
			<?php $args['text_color'] = 'grey-900' ?>
			<?php $promoted_bump_color = lisfinity_is_promoted_product( $product_id, 'bump-color' ); ?>
			<!-- Product -->
			<article
				class="product-col mt-32 px-8 w-full sm:px-col sm:w-1/2 bg:w-1/3 <?php echo $count <= 3 ? esc_attr( 'bg:mt-0' ) : ''; ?><?php echo $count < 3 ? esc_attr( ' sm:mt-0' ) : ''; ?>"
				data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			>
				<div
					class="lisfinity-product relative h-full bg-white rounded shadow-theme overflow-hidden <?php echo $promoted_bump_color ? esc_attr( 'bg-bump-color' ) : esc_attr( 'bg-white' ); ?>">
					<?php if ( ! empty( $verified ) && lisfinity_is_enabled( lisfinity_get_option( 'product-owner-verified' ) ) ) : ?>
						<?php include lisfinity_get_template_part( 'partial-product-author-verified', 'shortcodes/products/partials', $args ); ?>
					<?php endif; ?>
					<?php include lisfinity_get_template_part( 'partial-product-views', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-bookmark', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-main-style-3', 'shortcodes/products/partials', $args ); ?>
					<div
						class="lisfinity-product--content flex flex-col py-22 px-24 w-full z-1 <?php echo $promoted_bump_color ? esc_attr( 'bg-bump-color' ) : esc_attr( 'bg-white' ); ?>">
						<!--							<div class="mb-14">-->
						<?php include lisfinity_get_template_part( 'partial-product-content', 'shortcodes/products/partials', $args ); ?>
						<!--							</div>-->
						<?php $category_type = carbon_get_post_meta( $product_id, 'product-category' );?>
						<?php $taxonomies = ! empty( $category_type ) ? $args['settings']["taxonomy[{$category_type}]"] : ''; ?>
						<?php if ( ! empty( $taxonomies ) ) : ?>
							<?php $args['taxonomies'] = $taxonomies; ?>

							<?php if ( 'custom' !== $args['settings']['style'] || ( 'custom' === $args['settings']['style'] ) && 'yes' === $args['settings']['hide_show_product_custom_fields'] ) : ?>
								<div class="flex flex flex-wrap items-center mb-20">
									<?php include lisfinity_get_template_part( 'partial-product-taxonomies', 'shortcodes/products/partials', $args ); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<div
							class="flex items-center justify-between">
							<?php include lisfinity_get_template_part( 'partial-product-owner', 'shortcodes/products/partials', $args ); ?>
						</div>

					</div>

				</div>
			</article>
			<?php $count += 1; ?>
		<?php endwhile; ?>
	</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
