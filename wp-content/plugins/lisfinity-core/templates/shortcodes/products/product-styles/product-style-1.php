<?php
/**
 * Template Name: Shortcodes | Products
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

<?php if ( ! empty( $args['products'] ) && $args['products']->have_posts() ) : ?>
	<?php $args['bookmarks'] = lisfinity_get_bookmarks(); ?>
	<div class="lisfinity-products">
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
			<?php $args['text_color'] = 'white' ?>
			<!-- Product -->
			<article
				class="product-col mt-32 px-8 w-full sm:px-16 sm:w-1/2 bg:w-1/3 <?php echo $count <= 3 ? esc_attr( 'bg:mt-0' ) : ''; ?><?php echo $count < 3 ? esc_attr( ' sm:mt-0' ) : ''; ?>"
				data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			>
				<?php
				$field_value_name        = get_post_meta( $product_id, 'title', true );
				$field_value_description = get_post_meta( $product_id, 'description', true );
				$field_value_from_who    = get_post_meta( $product_id, 'from-who', true );
				?>
				<div class="lisfinity-product relative rounded shadow-theme overflow-hidden">
					<?php if ( 'yes' === $args['settings']['display_description'] ) : ?>
						<a href="<?php the_permalink(); ?>" class="flex items-start ajax--lead">
							<div class="elementor-product-description-field flex h-full w-full absolute bg-white p-20"
								 style="justify-content: center; z-index: 100; align-items: center; border: 8px solid #818286;">
								<div class="product-custom-description" style="color: #5e5e5e;">
									<?php if ( ! empty( $field_value_name ) ) : ?>
										<h2 class="custom-description-title pb-20"
											style="text-align: center; font-size: 20px;"><?php echo esc_html( $field_value_name ); ?></h2>
									<?php endif; ?>
									<?php if ( ! empty( $field_value_description ) ) : ?>
										<p class="custom-description-content p-20"
										   style="text-align: center; font-size: 14px;"><?php echo esc_html( $field_value_description ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $field_value_from_who ) ) : ?>
										<h4 class="custom-description-from-who p-20"
											style="text-align: center; font-size: 22px;"><?php echo esc_html( $field_value_from_who ); ?></h4>
									<?php endif; ?>
								</div>
							</div>
						</a>
					<?php endif; ?>
					<?php $args['style'] = 1; ?>

					<?php if ( 'sold' === get_post_status() ) : ?>
						<span class="product__sold-out"><?php esc_html_e( 'Sold Out!', 'lisfinity-core' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $verified ) && lisfinity_is_enabled( lisfinity_get_option( 'product-owner-verified' ) ) ) : ?>
						<?php include lisfinity_get_template_part( 'partial-product-author-verified', 'shortcodes/products/partials', $args ); ?>
					<?php endif; ?>
					<?php include lisfinity_get_template_part( 'partial-product-views', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-bookmark', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-main', 'shortcodes/products/partials', $args ); ?>

				</div>
			</article>
			<?php $count += 1; ?>
		<?php endwhile; ?>
	</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
