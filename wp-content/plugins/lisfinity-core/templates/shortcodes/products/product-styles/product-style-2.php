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
<?php
$owner        = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$account_type = carbon_get_user_meta( $owner, 'account-type' );
$author       = get_post_field( 'post_author', get_the_ID() );
$profile_id   = lisfinity_get_premium_profile_id( $author );
?>

<?php if ( $args['products']->have_posts() ) : ?>
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
			<?php $promoted_bump_color = lisfinity_is_promoted_product( $product_id, 'bump-color' ); ?>
			<!-- Product -->
			<article
				class="product-col mt-32 px-8 w-full sm:px-16 sm:w-1/2 bg:w-1/3 <?php echo $count <= 3 ? esc_attr( 'bg:mt-0' ) : ''; ?><?php echo $count < 3 ? esc_attr( ' sm:mt-0' ) : ''; ?>"
				data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			>
				<div class="lisfinity-product relative rounded shadow-theme overflow-hidden">
					<?php if ( 'sold' === get_post_status() ) : ?>
						<span class="product__sold-out"><?php esc_html_e( 'Sold Out!', 'lisfinity-core' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $verified ) && lisfinity_is_enabled( lisfinity_get_option( 'product-owner-verified' ) ) ) : ?>
						<?php include lisfinity_get_template_part( 'partial-product-author-verified', 'shortcodes/products/partials', $args ); ?>
					<?php endif; ?>
					<?php include lisfinity_get_template_part( 'partial-product-views', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-bookmark', 'shortcodes/products/partials', $args ); ?>
					<?php include lisfinity_get_template_part( 'partial-product-main', 'shortcodes/products/partials', $args ); ?>
					<?php $location_format = lisfinity_get_option( 'format-location' ); ?>
					<?php if ( 'business' === $account_type || ( 'personal' === $account_type && carbon_get_post_meta( $product_id, 'product-location' )['address'] && lisfinity_format_location( $product_id, 'full' === $location_format ) ) ) : ?>
						<div
							class="lisfinity-product--owner flex flex-wrap items-center justify-between p-24 <?php echo $promoted_bump_color ? esc_attr( 'bg-bump-color' ) : esc_attr( 'bg-white' ); ?>">
							<?php include lisfinity_get_template_part( 'partial-product-owner', 'shortcodes/products/partials', $args ); ?>
						</div>
					<?php endif; ?>

				</div>
			</article>
			<?php $count += 1; ?>
		<?php endwhile; ?>
	</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
