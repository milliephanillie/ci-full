<?php
/**
 * Template Name: Shortcodes | Taxonomies | 1
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
	<!-- Taxonomies | Container -->
	<div class="taxonomies flex flex-wrap">
		<div class="container px-0">
			<div class="row flex-wrap">

				<?php $count = 1; ?>
				<?php if ( ! empty( $args['terms'] ) && is_array( $args['terms'] ) ): ?>
					<?php foreach ( $args['terms'] as $term ) : ?>

						<!-- Term -->
						<div class="mt-16 px-8 w-full bg:mt-0 bg:px-16 bg:w-full">
							<div
								class="taxonomy--term relative flex items-end h-taxonomy-thumb rounded overflow-hidden">

								<!-- Term | Link -->
								<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
								<?php $search_page_permalink = get_permalink( $search_page_id ); ?>
								<a href="<?php echo esc_url( add_query_arg( "tax[{$args['taxonomy']}]", $term->slug, $search_page_permalink ) ); ?>"
								   class="absolute w-full h-full z-10"
								   style="background-color: <?php echo esc_attr( $args['settings']['overlay'] ); ?>"></a>

								<?php $image_id = get_term_meta( $term->term_id, 'bg_image', true ); ?>
								<?php $image = ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'full' ) : ''; ?>
								<?php if ( ! empty( $image ) ) : ?>
									<?php if ( 'yes' === $args['settings']['background_image_overlay_taxonomies'] ) : ?>
										<span class="taxonomies-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
									<?php endif; ?>
									<img src="<?php echo esc_url( $image ); ?>"
										 alt="<?php esc_attr_e( 'Term Image', 'lisfinity-core' ); ?>"
										 class="absolute w-full h-full object-cover">
								<?php endif; ?>

								<a href="<?php echo esc_url( add_query_arg( "tax[{$args['taxonomy']}]", $term->slug, $search_page_permalink ) ); ?>"
								   class="taxonomy--content p-30 w-full text-white z-10">
									<h5 class="font-semibold text-white"><?php echo esc_html( $term->name ); ?></h5>
									<?php if ( ! empty( $args['settings']['products_count'] ) && 'yes' === $args['settings']['products_count'] ) : ?>
										<?php $singular = ! empty( $args['settings']['suffix'] ) ? $args['settings']['suffix'] : __( 'Product', 'lisfinity-core' ); ?>
										<?php $plural = ! empty( $args['settings']['suffix_plural'] ) ? $args['settings']['suffix_plural'] : __( 'Products', 'lisfinity-core' ); ?>
										<?php $message = _n_noop( '%d ' . $singular, '%d ' . $plural, 'lisfinity-core' ); ?>
										<?php printf( translate_nooped_plural( $message, $term->count, 'lisfinity-core' ), $term->count ); ?>
									<?php endif; ?>
								</a>

							</div>
						</div>
						<?php echo $count % 3 === 0 && $count !== 1 ? '</div><div class="row flex-wrap bg:mt-24 bg:flex-no-wrap">' : ''; ?>

						<?php $count += 1; ?>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div>
	</div>
<?php endif; ?>
