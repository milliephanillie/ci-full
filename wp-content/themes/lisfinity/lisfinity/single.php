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
?>

<?php get_header(); ?>
<?php the_post(); ?>
<?php global $numpages; ?>

<?php if ( ( ( lisfinity_is_core_active() && ! is_singular( \Lisfinity\Models\Elements\HeaderModel::$type ) ) && ( lisfinity_is_core_active() && ! is_singular( \Lisfinity\Models\Elements\FooterModel::$type ) ) && ( lisfinity_is_core_active() && ! is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) ) || ! lisfinity_is_core_active() ) : ?>
	<?php
	$has_sidebar = is_active_sidebar( 'default-sidebar' );
	if ( lisfinity_is_woocommerce_active() && is_product() ) {
		$has_sidebar = is_active_sidebar( 'sidebar-shop' );
	}
	?>
	<?php $categories = get_the_category(); ?>
	<?php $tags = get_the_tags(); ?>

	<div id="primary"
		 class="content-area <?php echo lisfinity_is_woocommerce_active() && is_product() ? esc_attr( 'bg-grey-100' ) : ''; ?>">
		<?php get_template_part( 'templates/template-parts/breadcrumbs/breadcrumbs' ); ?>
		<div class="container">
			<main id="main"
				  class="site-main py-40 sm:py-60"
				  role="main">

				<div
					class="flex flex-wrap mx-auto w-full <?php echo lisfinity_is_woocommerce_active() && is_product() ? esc_attr( 'flex-row-reverse' ) : ''; ?>">

					<?php $section_class = $has_sidebar ? 'lg:w-4/6' : ''; ?>
					<article
						class="post-single mx-auto px-col w-full <?php echo esc_attr( $section_class ); ?>">

						<?php if ( has_post_thumbnail() ) : ?>
							<!-- Post | Thumbnail -->
							<figure class="post-single--thumbnail">
								<?php the_post_thumbnail(); ?>
							</figure>
						<?php endif; ?>

						<?php if ( lisfinity_is_woocommerce_active() && is_product() ) : ?>
							<?php the_content(); ?>
						<?php else: ?>
							<!-- Post | Header -->
							<div class="post-single--head">
								<?php if ( ! empty( $categories ) ) : ?>
									<!-- Post | Category -->
									<div class="post--category mb-10">
										<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
										   class="text-grey-600"><?php echo esc_html( $categories[0]->name ); ?></a>
									</div>
								<?php endif; ?>
								<!-- Post | Title -->
								<h1 class="title"><?php the_title(); ?></h1>
								<div
									class="post-single--posted flex items-center">
									<div class="flex items-center">
										<?php
										// Posted by
										lisfinity_posted_by();

										?>
										<span><?php echo esc_html( '-' ); ?></span>
										<?php

										// Posted on
										lisfinity_posted_on();
										?>
									</div>

									<?php if ( comments_open() ): ?>
										<button
											class="action--leave-comment flex items-center link ml-auto hover:no-underline text-grey-500"
											title="<?php esc_html_e( 'Leave a Comment', 'lisfinity' ); ?>"
										>
											<svg version="1.1"
												 xmlns="http://www.w3.org/2000/svg"
												 class="relative top-2 fill-grey-700 w-16 h-16 min-w-16"
												 xmlns:xlink="http://www.w3.org/1999/xlink"
												 x="0px" y="0px"
												 viewBox="0 0 64 64"
												 style="enable-background:new 0 0 64 64;"
												 xml:space="preserve">
												<g>
													<path d="M2.7,58.7c-0.5,0-0.8,0-1.3-0.3C0.5,57.9,0,57.1,0,56V8c0-1.6,1.1-2.7,2.7-2.7h58.7C62.9,5.3,64,6.4,64,8v37.3
													c0,1.6-1.1,2.7-2.7,2.7H22.1L4,58.4C3.5,58.7,3.2,58.7,2.7,58.7z M5.3,10.7v40.8L20,43.2c0.5-0.5,0.8-0.5,1.3-0.5h37.3v-32H5.3z"/>
													<circle cx="16" cy="26.7"
															r="5.3"/>
													<circle cx="32" cy="26.7"
															r="5.3"/>
													<circle cx="48" cy="26.7"
															r="5.3"/>
												</g>
											</svg>
											<span
												class="ml-4 text-grey-700"><?php echo esc_html( get_comments_number() ); ?></span>
										</button>
									<?php endif; ?>
								</div>
							</div>

							<!-- Post | Content -->
							<div class="post-single--content mt-40 clearfix">
								<?php the_content(); ?>
							</div>

							<?php if ( $numpages > 1 ) : ?>
								<div class="navigation pagination">
									<div class="pagination--wrapper">
										<div class="nav-links">
											<?php wp_link_pages( [
												'before' => '',
												'after'  => '',
											] ); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $categories ) || ! empty( $tags ) ) : ?>
								<div class="cats-and-tags">
									<?php if ( ! empty( $categories ) ) : ?>
										<!-- Post | Category -->
										<div
											class="post-single--categories flex">
											<?php $category_title = _n_noop( 'Category:', 'Categories:', 'lisfinity' ); ?>
											<span><?php echo translate_nooped_plural( $category_title, count( $categories ), 'lisfinity-core' ); ?></span>
											<ul class="flex flex-wrap">
												<?php foreach ( $categories as $category ): ?>
													<li>
														<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $tags ) ) : ?>
										<!-- Post | Tags -->
										<div
											class="post-single--categories post-single--categories__tags flex">
											<?php $tags_title = _n_noop( 'Tag:', 'Tags:', 'lisfinity' ); ?>
											<span><?php echo translate_nooped_plural( $tags_title, count( $tags ), 'lisfinity-core' ); ?></span>
											<ul class="flex flex-wrap">
												<?php foreach ( $tags as $tag ): ?>
													<li>
														<a href="<?php echo esc_url( get_category_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>


							<!-- Post | Author -->
							<?php $author_description = get_the_author_meta( 'description' ); ?>
							<?php if ( ! empty( $author_description ) ) : ?>
								<div class="author-box">
									<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>"
									   class="author-box--avatar">
										<img
											src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ) ) ) ?>"/>
									</a>
									<div>
										<span><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></span>
										<p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
									</div>
								</div>
							<?php endif; ?>

							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
							?>
						<?php endif; ?>

					</article>

					<?php if ( $has_sidebar ) : ?>
						<!-- Sidebar -->
						<aside
							class="blog--sidebar px-col mt-60 lg:mt-0 w-full lg:w-2/6">
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
	<?php if ( lisfinity_is_core_active() && is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) : ?>
		<main id="page-single-business-elementor"
			  data-options="<?php echo esc_attr( json_encode( [] ) ); ?>">
		</main>
	<?php endif; ?>
	<?php the_content(); ?>
<?php endif; ?>

<?php get_footer();
