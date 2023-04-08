<?php
/**
 * Template: Default Page Template
 * Description: Default WordPress page template.
 *
 * @author pebas
 * @package Lisfinity
 * @version 9.9.9
 */
?>
<?php get_header(); ?>
<?php $has_sidebar = is_active_sidebar( 'default-sidebar' ) && ! lisfinity_is_elementor(); ?>
<?php $has_shop_sidebar = lisfinity_is_woocommerce_active() && is_active_sidebar( 'sidebar-shop' ) && ! lisfinity_is_elementor(); ?>

	<section id="primary" class="page--lisfinity content-area">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<main id="main"
		      class="woocommerce site-main py-40 sm:py-60 <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() || is_product_category() || is_product_tag() ) ? esc_attr( 'bg-grey-100' ) : ''; ?>">

			<div class="container">

				<div
					class="flex flex-wrap mx-auto w-full <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() ) ? esc_attr( 'flex-row-reverse' ) : ''; ?><?php echo lisfinity_is_woocommerce_active() && is_checkout() ? esc_attr( ' p-30 pb-60 bg-white shadow-theme' ) : ''; ?>">

					<?php $section_class = ( $has_shop_sidebar && ( lisfinity_is_woocommerce_active() && ( is_shop() || is_product() ) ) ) || ( $has_sidebar && ( ( lisfinity_is_woocommerce_active() && ! is_shop() && ! is_product() && ! is_cart() && ! is_checkout() ) || ! lisfinity_is_woocommerce_active() ) ) ? 'lg:w-4/6' : 'is-full'; ?>
					<section class="mx-auto px-col w-full <?php echo esc_attr( $section_class ); ?>">

						<div class="<?php echo ! lisfinity_is_elementor() ? esc_attr( 'px-col' ) : ''; ?>">

							<?php if ( ( ! lisfinity_is_woocommerce_active() ) || ( lisfinity_is_woocommerce_active() && ! is_cart() ) && ! lisfinity_is_elementor() ) : ?>
								<h1 class="page--title <?php echo lisfinity_is_woocommerce_active() && is_shop() ? esc_attr( 'page--title-shop' ) : ''; ?>">
									<?php woocommerce_page_title(); ?>
								</h1>
							<?php endif; ?>

							<div
								class="page--content clearfix <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_cart() || is_checkout() ) ? esc_attr( 'page--content-shop' ) : ''; ?><?php echo lisfinity_is_elementor() ? esc_attr( ' elementor-content' ) : ''; ?>">
								<?php
								if ( woocommerce_product_loop() ) {

									/**
									 * Hook: woocommerce_before_shop_loop.
									 *
									 * @hooked woocommerce_output_all_notices - 10
									 * @hooked woocommerce_result_count - 20
									 * @hooked woocommerce_catalog_ordering - 30
									 */
									do_action( 'woocommerce_before_shop_loop' );

									woocommerce_product_loop_start();

									if ( wc_get_loop_prop( 'total' ) ) {
										while ( have_posts() ) {
											the_post();

											/**
											 * Hook: woocommerce_shop_loop.
											 */
											do_action( 'woocommerce_shop_loop' );

											wc_get_template_part( 'content', 'product' );
										}
									}

									woocommerce_product_loop_end();

									/**
									 * Hook: woocommerce_after_shop_loop.
									 *
									 * @hooked woocommerce_pagination - 10
									 */
									do_action( 'woocommerce_after_shop_loop' );
								} else {
									/**
									 * Hook: woocommerce_no_products_found.
									 *
									 * @hooked wc_no_products_found - 10
									 */
									do_action( 'woocommerce_no_products_found' );
								}
								?>

							</div>

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

<?php get_footer(); ?>

