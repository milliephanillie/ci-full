<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();

$has_sidebar = is_active_sidebar( 'default-sidebar' );
?>

	<section id="primary" class="content-area">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<main id="main" class="site-main py-40 sm:py-86">

			<div class="container">

				<div class="flex flex-wrap mx-auto w-full">

					<section
						class="mx-auto px-col w-full lg:w-4/6">

						<div class="error-404 not-found p-30 bg-grey-100 rounded">
							<header class="page-header">
								<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'lisfinity' ); ?></h1>
							</header><!-- .page-header -->

							<div class="page-content clearfix">
								<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'lisfinity' ); ?></p>
								<?php get_search_form(); ?>
							</div><!-- .page-content -->
						</div><!-- .error-404 -->

					</section>

					<?php if ( $has_sidebar ) : ?>
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

<?php
get_footer();
