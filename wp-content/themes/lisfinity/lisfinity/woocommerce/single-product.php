<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 9.9.9
 */
?>

<?php get_header(); ?>
<?php the_post(); ?>
<?php global $numpages; ?>

<?php if ( ! is_singular( 'lisfinity_header' ) && ! is_singular( 'lisfinity_footer' ) ) : ?>
	<?php
	$has_sidebar = is_active_sidebar( 'default-sidebar' );
	if ( lisfinity_is_woocommerce_active() && is_product() ) {
		$has_sidebar = is_active_sidebar( 'sidebar-shop' );
	}
	?>

	<div id="primary"
		 class="content-area <?php echo lisfinity_is_woocommerce_active() && is_product() ? esc_attr( 'bg-grey-100' ) : ''; ?>">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<div class="container">
			<main id="main"
				  class="woocommerce site-main py-40 sm:py-60"
				  role="main">

				<div
					class="flex flex-wrap mx-auto w-full <?php echo lisfinity_is_woocommerce_active() && is_product() ? esc_attr( 'flex-row-reverse' ) : ''; ?>">

					<?php $section_class = $has_sidebar ? 'lg:w-4/6' : ''; ?>
					<article class="post-single mx-auto px-col w-full <?php echo esc_attr( $section_class ); ?>">

						<?php wc_get_template_part( 'content', 'single-product' ); ?>

					</article>

					<?php if ( $has_sidebar ) : ?>
						<!-- Sidebar -->
						<aside class="blog--sidebar px-col mt-60 lg:mt-0 w-full lg:w-2/6">
							<div class="widget--wrapper">
								<?php get_sidebar(); ?>
							</div>
						</aside>
					<?php endif; ?>

				</div>

			</main><!-- #main -->


		</div><!-- .wrap -->
	</div><!-- #primary -->
<?php else: ?>
	<?php the_content(); ?>
<?php endif; ?>

<?php get_footer();
