<?php
/**
 * Template Name: Shortcodes | Category Types
 * Description: The file that is being used to display category types widget
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php if ( ! empty( $args['terms'] ) ) : ?>
	<!-- Category Group | Container -->
	<div
		class="category-types relative flex flex-wrap -mb-16 bg:-mb-30 xl:px-44 overflow-x-hidden">
		<?php $count = 0; ?>
		<?php foreach ( $args['terms'] as $term ) : ?>
			<?php $image_id = get_term_meta( $term->term_id, 'bg_image', true ); ?>
			<?php $image = ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'full' ) : ''; ?>
			<!-- Category Group | Wrapper -->
			<div
				class="category-group px-16 w-full sm:w-1/2 bg:w-1/4 focus:outline-none h-category-type">
				<div class="category-group--inner relative h-full rounded overflow-hidden hover:shadow-xl ?>">

					<?php if ( 'yes' === $args['settings']['products_count'] ) : ?>
						<!-- Category Group | Count -->
						<div class="absolute top-20 right-20 flex-center w-30 h-30 rounded-full overflow-hidden z-1">
						<span
							class="absolute w-30 h-30 bg-grey-1000 opacity-50"></span>
							<span class="relative text-sm text-white"><?php echo esc_html( $count ); ?></span>
						</div>
					<?php endif; ?>

					<!-- Category Group | Link -->
					<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
					<?php $search_page_permalink = get_permalink( $search_page_id ); ?>
					<a href="<?php echo esc_url( add_query_arg( "tax[{$term->taxonomy}]", $term->slug, $search_page_permalink ) ); ?>"
					   class="absolute w-full h-full z-10"
					   style="background-color: <?php echo esc_attr( $args['settings']['overlay'] ); ?>"></a>

					<!-- Category Group | Figure -->
					<?php if ( ! empty( $image ) ) : ?>
						<figure class="category-group--bg absolute w-full h-full">
							<?php if ( 'yes' === $args['settings']['background_image_overlay_taxonomies'] ) : ?>
								<span class="taxonomies-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
							<?php endif; ?>
							<img src="<?php echo esc_url( $image ); ?>"
								 alt="<?php echo esc_html( 'Taxonomy image' ); ?>"
								 class="absolute w-full h-full object-cover">
						</figure>
					<?php endif; ?>

					<?php $terms = []; ?>
					<!-- Category Group | Content -->
					<div class="category-group--content absolute bottom-0 left-0 py-20 px-30 w-full z-20">
						<h5 class="font-semibold text-white text-2xl <?php echo ! is_wp_error( $terms ) ? esc_attr( '-mb-5' ) : ''; ?>">
							<a href="<?php echo esc_url( add_query_arg( "tax[{$term->taxonomy}]", $term->slug, $search_page_permalink ) ); ?>">
								<?php echo esc_html( $term->name ); ?>
							</a>
						</h5>
						<?php if ( ! empty( $args['settings']['products_count'] ) && 'yes' === $args['settings']['products_count'] ) : ?>
							<?php $singular = ! empty( $args['settings']['suffix'] ) ? $args['settings']['suffix'] : __( 'Product', 'lisfinity-core' ); ?>
							<?php $plural = ! empty( $args['settings']['suffix_plural'] ) ? $args['settings']['suffix_plural'] : __( 'Products', 'lisfinity-core' ); ?>
							<?php $message = _n_noop( '%d ' . $singular, '%d ' . $plural, 'lisfinity-core' ); ?>
							<?php printf( translate_nooped_plural( $message, $term->count, 'lisfinity-core' ), $term->count ); ?>
						<?php endif; ?>
					</div>

				</div>
			</div>
			<?php $count += 1; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
