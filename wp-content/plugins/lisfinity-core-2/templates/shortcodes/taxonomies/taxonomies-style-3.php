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
	<div
		class="taxonomies taxonomies--style-3 flex flex-wrap mx-16 xl:mx-86 <?php echo $terms_count > 6 ? esc_attr( 'taxonomies--slider' ) : ''; ?>">
		<?php if ( ! empty( $args['terms'] ) && is_array( $args['terms'] ) ): ?>
			<?php foreach ( $args['terms'] as $term ) : ?>

				<?php $width_class = 'w-full xs:w-1/2 sm:w-1/3 md:w-1/4 xl:w-1/6'; ?>
				<!-- Term -->
				<div class="taxonomy px-col <?php echo esc_attr( $width_class ); ?>">
					<div
						class="taxonomy--term relative flex flex-col justify-end items-center mt-24 py-30 h-300 bg-grey-100 rounded shadow-partners overflow-hidden hover:shadow-lg">
						<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
						<?php $search_page_permalink = get_permalink( $search_page_id ); ?>
						<a href="<?php echo esc_url( add_query_arg( "tax[{$args['taxonomy']}]", $term->slug, $search_page_permalink ) ); ?>"
						   class="absolute w-full h-full z-20">
						</a>

						<?php $image_id = get_term_meta( $term->term_id, 'bg_image', true ); ?>
						<?php $image = ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'full' ) : ''; ?>
						<?php if ( ! empty( $image ) ) : ?>
							<figure class="taxonomy--image relative">
								<?php if ( 'yes' === $args['settings']['background_image_overlay_taxonomies'] ) : ?>
									<span class="taxonomies-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
								<?php endif; ?>
								<img src="<?php echo esc_url( $image ); ?>"
								     alt="<?php esc_attr_e( 'Term Image', 'lisfinity-core' ); ?>"
								     class="w-full h-full object-cover">
							</figure>
						<?php endif; ?>

						<?php if ( 'yes' === $args['settings']['products_count'] ) : ?>
							<!-- Category Group | Count -->
							<a href="<?php echo esc_url( add_query_arg( "tax[{$args['taxonomy']}]", $term->slug, $search_page_permalink ) ); ?>"
							   class="category-group--count absolute flex-center top-16 right-16 w-32 h-32 bg-grey-900 rounded-full text-sm text-white z-10"><?php echo esc_html( $term->count ); ?></a>
						<?php endif; ?>

						<h5 class="px-24 self-start font-regular text-2xl z-10"><?php echo esc_html( $term->name ); ?></h5>
					</div>
				</div>

				<?php $count += 1; ?>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>
<?php endif; ?>
