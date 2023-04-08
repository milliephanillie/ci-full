<?php
/**
 * Template Name: Shortcodes | Posts
 * Description: The file that is being used to display default WordPress posts
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php $posts = $args['posts']; ?>
<?php if ( $posts->have_posts() ) : ?>
	<!-- Section | Posts -->
	<div class="posts">
		<div class="container px-0">
			<div class="row">

				<?php $count = 1; ?>
				<?php while ( $posts->have_posts() ) : ?>
					<?php $posts->the_post(); ?>
					<?php $post_id = get_the_ID(); ?>
					<?php $category = get_the_category( $post_id ); ?>

					<?php $margin_class[] = $count > 1 ? esc_attr( 'mt-0' ) : ''; ?>
					<?php $margin_class[] = $count > 2 ? esc_attr( 'sm:mt-0' ) : ''; ?>
					<?php $margin_class[] = $count > 3 ? esc_attr( 'lg:mt-0' ) : ''; ?>
					<!-- Post -->
					<div
						class="px-col w-full sm:w-1/2 lg:w-1/3 sm:mt-0 lg:mt-0 <?php echo esc_attr( implode( ' ', $margin_class ) ); ?>">
						<article class="post bg-white rounded shadow-theme overflow-hidden">

							<?php $image = get_the_post_thumbnail_url( $post_id, 'full' ); ?>
							<?php if ( ! empty( $image ) ) : ?>

								<?php if ('yes' === $args['settings']['display_image']) :  ?>
								<!-- Post | Image -->
								<figure class="post--image relative h-product-2-thumb">
									<!-- Post | Link -->
									<a href="<?php the_permalink(); ?>"
									   class="absolute w-full h-full z-10"
									   style="background-color: <?php echo esc_attr( ! empty( $args['settings']['overlay'] ) ? $args['settings']['overlay'] : 'rgba(0,0,0,0)' ); ?>"></a>

									<img src="<?php echo esc_url( $image ); ?>"
										 alt="<?php esc_attr_e( 'News Image', 'lisfinity-core' ); ?>"
										 class="absolute w-full h-full object-cover">

								</figure>
							<?php endif; ?>
							<?php endif; ?>

							<!-- Post | Content -->
							<div class="post--content py-32 px-30">
								<?php if ('yes' === $args['settings']['display_post_category']) :  ?>
									<?php if ( ! empty( $category ) ) : ?>
										<div class="post--category mb-10">
											<a href="<?php echo esc_url( get_category_link( $category[0]->term_id ) ); ?>"
											   class="text-grey-600"><?php echo esc_html( $category[0]->name ); ?></a>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ('yes' === $args['settings']['display_post_title']) :  ?>
									<h5 class="font-semibold text-grey-1000 text-2xl m-0"><a
											href="<?php the_permalink() ?>"><?php the_title(); ?></a></h5>
								<?php endif; ?>
								<?php $excerpt = get_the_excerpt(); ?>
								<?php if ( ! empty( $excerpt ) ) : ?>
								<?php if ('yes' === $args['settings']['display_post_content']) :  ?>
									<div
										class="post--content-excerpt my-10 mb-20 text-grey-500"><?php echo esc_html( wp_trim_words( $excerpt, '15' ) ); ?></div>
								<?php endif; ?>
								<?php endif; ?>
								<?php if ('yes' === $args['settings']['display_author']) :  ?>
								<div class="post--meta">
									<div class="post--author flex items-center mt-10">
										<?php
										$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
										if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
											$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
										}

										$time_string = sprintf(
											$time_string,
											esc_attr( get_the_date( DATE_W3C ) ),
											esc_html( get_the_date( 'M d, Y' ) )
										);
										?>
										<?php if ('yes' === $args['settings']['display_author_image']) :  ?>
											<?php printf(
											/* translators: 1: post author, only visible to screen readers. 2: author link, 3: author name, 4: time permalink, 5: time string. */
											'<a href="%1$s" class="post-author--img flex mr-10 w-32 h-32 rounded-full"><img src="%2$s" class="rounded-full" alt="' . esc_attr__( 'Post Author', 'lisfinity-core' ) . '"/></a><div class="flex flex-col"><span>%3$s</span><span class="posted-on text-sm text-grey-500"><a href="%4$s" rel="bookmark">%5$s</a></span></div>',
												esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
												lisfinity_get_avatar_url( get_the_author_meta( 'ID' ) ),
												get_the_author_meta( 'display_name' ),
												esc_url( get_permalink() ),
												$time_string
											); ?>
										<?php else: ?>
											<?php printf(
											/* translators: 1: author name, 2: time permalink, 3: time string. */
												'<div class="flex flex-col"><span>%1$s</span><span class="posted-on text-sm text-grey-500"><a href="%2$s" rel="bookmark">%3$s</a></span></div>',
												get_the_author_meta( 'display_name' ),
												esc_url( get_permalink() ),
												$time_string
											); ?>
										<?php endif; ?>
									</div>
								</div>
								<?php endif; ?>
							</div>

						</article>
					</div>

					<?php $count += 1; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>

			</div>
		</div>
	</div>
<?php endif; ?>
