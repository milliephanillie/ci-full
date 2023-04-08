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
<?php if ( ! empty( $args['categories'] ) ) : ?>
	<!-- Category Group | Container -->
	<div
		class="category-types relative flex flex-wrap -mb-16 bg:-mb-30 xl:px-44 overflow-x-hidden">
		<?php $count = 0; ?>
		<?php foreach ( $args['categories'] as $group => $taxonomies ) : ?>
			<?php $groups_model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel(); ?>
			<?php $group_options = $groups_model->get_group_options( $group ); ?>
			<?php $image = ! empty( $group_options['bg_image'] ) ? wp_get_attachment_image_url( $group_options['bg_image'], 'full' ) : false; ?>
			<?php $ads_count = lisfinity_get_group_products_count( $group ); ?>
			<!-- Category Group | Wrapper -->
			<div
				class="category-group px-16 w-full sm:w-1/2 bg:w-1/4 focus:outline-none h-category-type">
				<div class="category-group--inner relative h-full rounded overflow-hidden hover:shadow-xl ?>">

					<?php if ( 'yes' === $args['settings']['products_count'] ) : ?>
						<!-- Category Group | Count -->
						<div class="absolute top-20 right-20 flex-center w-30 h-30 rounded-full overflow-hidden z-20">
						<span
							class="absolute w-30 h-30 bg-grey-1000 opacity-50"></span>
							<span class="relative text-sm text-white"><?php echo esc_html( $ads_count ); ?></span>
						</div>
					<?php endif; ?>

					<!-- Category Group | Link -->
					<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
					<?php $search_page_permalink = get_permalink( $search_page_id ); ?>
					<?php
					if ( 'default' === lisfinity_get_option( 'permalink-category' ) ) {
						$url = get_site_url( null, '', 'relative' ) . '/' . lisfinity_get_slug( 'slug-category', 'ad-category' ) . '/' . $group;
					}
					?>
					<a href="<?php echo esc_url( $url ); ?>"
					   class="absolute w-full h-full z-10"
					   style="background-color: <?php echo esc_attr( $args['settings']['overlay'] ); ?>"></a>

					<!-- Category Group | Figure -->
					<?php if ( ! empty( $image ) ) : ?>
						<figure class="category-group--bg absolute w-full h-full">
							<?php if ( !empty($args['settings']['background_image_overlay_taxonomies']) && 'yes' === $args['settings']['background_image_overlay_taxonomies'] ) : ?>
								<span class="taxonomies-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
							<?php endif; ?>
							<img src="<?php echo esc_url( $image ); ?>"
								 alt="<?php echo esc_html( 'Category type image' ); ?>"
								 class="absolute w-full h-full object-cover">
						</figure>
					<?php endif; ?>

					<?php $terms = []; ?>
					<!-- Category Group | Content -->
					<div class="category-group--content absolute bottom-0 left-0 py-20 px-30 w-full z-20">
						<h5 class="font-semibold text-white text-2xl <?php echo ! is_wp_error( $terms ) ? esc_attr( '-mb-5' ) : ''; ?>">
							<a href="<?php echo esc_url( $url ); ?>">
								<?php echo esc_html( $group_options['plural_name'] ); ?>
							</a>
						</h5>
						<?php if ( ! is_wp_error( $terms ) && ! empty( $taxonomy ) ) : ?>
							<div class="category-group--content__taxonomies flex flex-wrap -mx-6">
								<?php foreach ( $terms as $term ) : ?>
									<a href="<?php echo esc_url( add_query_arg( [
										'category-type'    => $group,
										"tax[{$taxonomy}]" => $term->slug
									], $search_page_permalink ) ); ?>"
									   class="px-8 text-white hover:underline"><?php echo esc_html( $term->name ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

				</div>
			</div>
			<?php $count += 1; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
