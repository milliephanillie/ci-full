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
<?php if ( ! empty( $args['category'] ) && ! empty( $args['taxonomies'] ) ) : ?>
	<?php $request = lisfinity_get_taxonomy_and_term(); ?>
	<!-- Category Group | Container -->
	<?php $count = 0; ?>
	<?php $group_model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel(); ?>
	<?php $group_options = $group_model->get_group_options( $args['category'] ); ?>
	<?php $taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel(); ?>
	<?php $taxonomy_options = $taxonomy_model->get_options()[ $args['category'] ]; ?>
	<?php $image = $args['settings']['background-image-url']; ?>
	<?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
	<?php $search_page_permalink = get_permalink( $search_page_id ); ?>

	<div class="category-box relative overflow-hidden">
		<?php if ( $image && 'yes' === $args['settings']['background-image'] && 'top' === $args['settings']['background-image-position'] ) : ?>
			<figure class="category-box--bg relative overflow-hidden">
				<?php if ( 'yes' === $args['settings']['background-image-overlay'] ) : ?>
					<span class="category-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
				<?php endif; ?>
				<!-- Category Box | Image -->
				<img class="category-box--image absolute top-0 left-0 w-full h-full"
					 src="<?php echo esc_url( $image['url'] ); ?>"
					 alt="<?php echo esc_html__( 'Taxonomy background image', 'lisfinity-core' ); ?>">
			</figure>
		<?php endif; ?>
		<?php if ( ! empty( $args['title'] ) ) : ?>
			<!-- Category Box | Title -->
			<div
				class="category-box--title relative flex items-center <?php echo empty( $args['title-color'] ) ? esc_attr( 'text-grey-1000' ) : ''; ?>">
				<?php echo $args['title']; ?>
				<?php if ( 'yes' === $args['settings']['title-ads-count'] ): ?>
					<span
						class="category-box--title-ads-count relative"><?php echo esc_html( lisfinity_get_group_products_count( $args['category'] ) ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $args['taxonomies'] as $taxonomy ) : ?>
			<?php $slugs = array_column( $taxonomy_options, 'slug' ); ?>
			<?php $key = array_search( $taxonomy, $slugs ); ?>
			<?php
			$terms_args = [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			];
			if ( ! empty( $settings["taxonomy-hide-empty[{$args['category']}]"] ) ) {
				$terms_args['hide_empty'] = 'yes' === $settings["taxonomy-hide-empty[{$args['category']}]"];
			}
			if ( ! empty( $settings["taxonomy-limit[{$args['category']}]"] ) ) {
				$terms_args['number'] = $settings["taxonomy-limit[{$args['category']}]"];
			}
			?>
			<?php $terms = get_terms( $terms_args ); ?>
			<!-- Category Box | Wrapper -->
			<?php //echo $taxonomy_options[ $key ]['single_name']; ?>

			<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<!-- Category Box | Terms -->
				<div class="category-box--terms">
					<ul class="flex flex-wrap">
						<?php foreach ( $terms as $term ) : ?>
							<?php
							$query_args = [ 'category-type' => $args['category'], "tax[{$taxonomy}]" => $term->slug ];
							if ( ! empty( $request[1] ) ) {
								$query_args["tax[{$request[0]}]"] = $request[1];
							}
							?>
							<?php $term_permalink = add_query_arg(
								$query_args,
								$search_page_permalink
							); ?>
							<li class="flex items-center">
								<a href="<?php echo esc_url( $term_permalink ); ?>"
								   class="relative flex items-center <?php echo empty( $args['title-color'] ) ? esc_attr( 'text-grey-900' ) : ''; ?>"
								><?php echo esc_html( $term->name ); ?>
								</a>
								<?php if ( 'yes' === $args['settings']['terms-count'] ) : ?>
									<span
										class="category-box--terms-count relative"><?php echo esc_html( $term->count ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php $count += 1; ?>
		<?php endforeach; ?>
		<?php if ( $image && 'yes' === $args['settings']['background-image'] && 'bottom' === $args['settings']['background-image-position'] ) : ?>
			<figure class="category-box--bg relative overflow-hidden">
				<?php if ( 'yes' === $args['settings']['background-image-overlay'] ) : ?>
					<span class="category-box--overlay absolute top-0 left-0 w-full h-full z-1"></span>
				<?php endif; ?>
				<!-- Category Box | Image -->
				<img class="category-box--image absolute top-0 left-0 w-full h-full"
					 src="<?php echo esc_url( $image['url'] ); ?>"
					 alt="<?php echo esc_html__( 'Taxonomy background image', 'lisfinity-core' ); ?>">
			</figure>
		<?php endif; ?>
		<?php if ( 'yes' === $args['settings']['display_show_more_button'] ) : ?>
			<div>
				<a href="<?php echo esc_url( $args['settings']['show_more_button_url']['url'] ) ?>" class="category--button">
					<?php echo esc_html( $args['settings']['show_more_button_text'] ) ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

<?php endif; ?>
