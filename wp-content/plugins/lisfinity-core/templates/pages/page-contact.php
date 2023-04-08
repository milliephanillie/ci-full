<?php
/**
 * Template: Default Page Template
 * Description: Default WordPress page template.
 *
 * @author pebas
 * @package Lisfinity
 * @version 1.0.0
 */
?>
<?php get_header(); ?>
<?php $has_sidebar = is_active_sidebar( 'default-sidebar' ) && ! lisfinity_is_elementor(); ?>
<?php $has_shop_sidebar = lisfinity_is_woocommerce_active() && is_active_sidebar( 'sidebar-shop' ) && ! lisfinity_is_elementor(); ?>
<?php the_post(); ?>

<?php if ( lisfinity_is_core_active() && is_account_page() ) : ?>
	<?php the_content(); ?>
<?php else: ?>

	<section id="primary" class="page--lisfinity content-area">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<main id="main"
			  class="site-main py-40 sm:py-60 <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() || is_product_category() || is_product_tag() ) ? esc_attr( 'bg-grey-100' ) : ''; ?>">

			<div class="container">

				<div
					class="flex flex-wrap mx-auto w-full <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() ) ? esc_attr( 'flex-row-reverse' ) : ''; ?><?php echo lisfinity_is_woocommerce_active() && is_checkout() ? esc_attr( ' p-30 pb-60 bg-white shadow-theme' ) : ''; ?>">

					<?php $section_class = ( $has_shop_sidebar && ( lisfinity_is_woocommerce_active() && ( is_shop() || is_product() ) ) ) || ( $has_sidebar && ( ( lisfinity_is_woocommerce_active() && ! is_shop() && ! is_product() && ! is_cart() && ! is_checkout() ) || ! lisfinity_is_woocommerce_active() ) ) ? 'lg:w-4/6' : 'is-full'; ?>
					<section class="mx-auto px-col w-full <?php echo esc_attr( $section_class ); ?>">

						<div class="<?php echo ! lisfinity_is_elementor() ? esc_attr( 'px-col' ) : ''; ?>">

							<?php if ( ( ! lisfinity_is_woocommerce_active() ) || ( lisfinity_is_woocommerce_active() && ! is_cart() ) && ! lisfinity_is_elementor() ) : ?>
								<h1 class="page--title <?php echo lisfinity_is_woocommerce_active() && is_shop() ? esc_attr( 'page--title-shop' ) : ''; ?>">
									<?php the_title(); ?>
								</h1>
							<?php endif; ?>

							<div
								class="page--content clearfix <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() ) ? esc_attr( 'page--content-shop' ) : ''; ?><?php echo lisfinity_is_elementor() ? esc_attr( ' elementor-content' ) : ''; ?>">
								<?php the_content(); ?>
							</div>

							<?php
							// Previous/next page navigation.
							lisfinity_the_pages_navigation();
							?>

							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
							?>
						</div>

					</section>

					<?php if ( ( $has_shop_sidebar && ( is_shop() || is_product() ) ) || ( ! lisfinity_is_elementor() && $has_sidebar && ( ( lisfinity_is_woocommerce_active() && ! is_cart() && ! is_checkout() && ! is_shop() && ! is_product() ) || ! lisfinity_is_woocommerce_active() ) ) ) : ?>
						<!-- Sidebar -->
						<aside class="blog--sidebar px-col mt-60 lg:mt-0 w-full lg:w-2/6">
							<div class="widget--wrapper">
								<?php get_sidebar(); ?>
							</div>
						</aside>
					<?php endif; ?>

				</div>

			</div>

		</main><!-- #main -->
	</section><!-- #primary -->
<?php endif; ?>

<?php get_footer(); ?>
