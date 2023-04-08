<?php
/**
 * Template Name: Shortcodes | Taxonomies | 2
 * Description: The file that is being used to display taxonomies widget
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php if ( ! empty( $args['terms'] ) ) : ?>
	<?php $count = 1; ?>
	<?php $terms_count = ! empty( $args['terms'] ) && is_array( $args['terms'] ) ? count( $args['terms'] ) : []; ?>

	<!-- Taxonomies | Container -->
	<div class="taxonomies flex flex-wrap">
		<div class="container px-0">
			<div
				class="row -mx-8 flex-wrap bg:flex-no-wrap <?php echo 6 < $terms_count ? esc_attr( 'taxonomies--slider' ) : 'xl:justify-between'; ?>">

				<?php if ( ! empty( $args['terms'] ) && is_array( $args['terms'] ) ): ?>
					<?php foreach ( $args['terms'] as $term ) : ?>

						<?php $width_class = 'w-full xs:w-1/2 sm:w-1/3 md:w-1/4 xl:w-full'; ?>
						<!-- Term -->
						<div class="px-8 <?php echo esc_attr( $width_class ); ?>">
							<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
							<?php $search_page_permalink = get_permalink( $search_page_id ); ?>
							<a href="<?php echo esc_url( add_query_arg( "tax[{$args['taxonomy']}]", $term->slug, $search_page_permalink ) ); ?>">
								<div
									class="taxonomy--term relative flex flex-col items-center mt-24 py-30 bg-grey-100 rounded shadow-partners overflow-hidden hover:shadow-lg">

									<?php $image_id = get_term_meta( $term->term_id, 'bg_image', true ); ?>
									<?php $image = ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'full' ) : ''; ?>
									<?php if ( ! empty( $image ) ) : ?>
										<figure class="taxonomy--image relative h-86 w-86">
											<?php if ( 'yes' === $args['settings']['background_image_overlay_taxonomies'] ) : ?>
												<span class="taxonomies-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
											<?php endif; ?>
											<img src="<?php echo esc_url( $image ); ?>"
												 alt="<?php esc_attr_e( 'Term Image', 'lisfinity-core' ); ?>"
												 class="absolute w-full h-full object-contain">
										</figure>
									<?php endif; ?>

									<h5 class="mt-20 font-semibold text-base"><?php echo esc_html( $term->name ); ?></h5>
									<?php if ( ! empty( $args['settings']['products_count'] ) && 'yes' === $args['settings']['products_count'] ) : ?>
										<?php $singular = ! empty( $args['settings']['suffix'] ) ? $args['settings']['suffix'] : __( 'Ad', 'lisfinity-core' ); ?>
										<?php $plural = ! empty( $args['settings']['suffix_plural'] ) ? $args['settings']['suffix_plural'] : __( 'Ads', 'lisfinity-core' ); ?>
										<?php $message = _n_noop( '%d ' . $singular, '%d ' . $plural, 'lisfinity-core' ); ?>
										<?php printf( translate_nooped_plural( $message, $term->count, 'lisfinity-core' ), $term->count ); ?>
									<?php endif; ?>

								</div>
							</a>
						</div>

						<?php $count += 1; ?>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div>
	</div>
<?php endif; ?>
