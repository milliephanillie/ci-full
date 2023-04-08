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
 * @version 1.0
 */

get_header(); ?>

<?php $has_sidebar = is_active_sidebar( 'default-sidebar' ); ?>

	<div id="primary" class="content-area">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<div class="container">
			<main id="main" class="site-main py-40 sm:py-86"
				  role="main">

				<div class="flex flex-wrap mx-auto w-full">

					<?php get_template_part( 'templates/template-parts/title/title' ); ?>

					<section
						class="mx-auto px-col w-full lg:w-4/6 clearfix">

						<?php
						if ( have_posts() ) {

							// Load posts loop.
							while ( have_posts() ) {
								the_post();
								get_template_part( 'templates/template-parts/content/content' );
							}

							// Previous/next page navigation.
							lisfinity_the_posts_navigation();

						} else {

							// If no content, include the "No posts found" template.
							get_template_part( 'templates/template-parts/content/content', 'none' );
						}
						?>

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

			</main><!-- #main -->


		</div><!-- .wrap -->
	</div><!-- #primary -->

<?php get_footer();
