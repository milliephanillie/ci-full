<?php
/**
 * Template Name: Shortcodes | Product Partials | Product Taxonomies
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 * @var $product_id
 */
?>

<?php if ( ! empty( $args['taxonomies'] ) ) : ?>
	<?php $taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel(); ?>
	<?php $category = carbon_get_post_meta( $product_id, 'product-category' ); ?>
	<?php $field_options = get_option( 'lisfinity--search-builder-fields' )['sidebar']; ?>
	<?php foreach ( $args['taxonomies'] as $taxonomy ) : ?>
		<?php $options = $taxonomy_model->get_taxonomy_options( $taxonomy ); ?>
		<?php $terms = get_the_terms( $product_id, $taxonomy ); ?>
		<?php $has_icon = ! empty( $options['icon'] ); ?>
		<?php if ( ! is_wp_error( $terms ) ) : ?>
			<?php
			if ( empty( $terms[0] ) ) :
				return;
			endif;
			?>
			<?php $search_page = lisfinity_get_page_id( 'page-search' ); ?>
			<?php $link = add_query_arg( "tax[{$taxonomy}]", $terms[0]->slug, get_permalink( $search_page ) ); ?>
			<?php $enable_taxonomy_labels = '1' === lisfinity_get_option( 'ad-taxonomy-labels' ); ?>
			<?php $taxonomy_label = lisfinity_get_option( 'ad-taxonomy-labels-select' ); ?>
			<?php $term_label = lisfinity_get_option( 'ad-term-labels-select' ); ?>
			<?php $prefix = $field_options[ $category ]['options'][ $taxonomy ]['prefix'] ?? ''; ?>
			<?php $suffix = $field_options[ $category ]['options'][ $taxonomy ]['suffix'] ?? ''; ?>

			<a href="<?php echo esc_url( $link ); ?>"
			   class="flex-center mt-1 mr-1 py-2 px-8 bg-grey-100 rounded text-sm text-grey-800">
				<?php if ( true === $enable_taxonomy_labels && empty( $prefix ) && empty( $suffix ) && ( 'icon_and_label' === $taxonomy_label || 'display_label' === $taxonomy_label ) ) : ?>
					<span><?php printf( '%s:', esc_html( lisfinity_convert_slug_to_name( $taxonomy ) ) ); ?></span>
				<?php endif; ?>
				<?php if ( $has_icon && ( 'icon_and_label' === $taxonomy_label || 'display_icon' === $taxonomy_label || 'icon_and_term' === $term_label || 'display_icon' === $term_label ) ) : ?>
					<?php
					$icon_size_default = lisfinity_get_option( 'ad-taxonomy-icon-size' );
					$icon_size         = $options['icon-size'] ?? $icon_size_default;
					if ( 'display_icon' === $term_label || 'icon_and_term' === $term_label ) {
						$icon_size_default = lisfinity_get_option( 'ad-taxonomy-icon-size' );
						$icon_size         = $options['icon-size'] ?? $icon_size_default;
						$icon              = wp_get_attachment_image_url( get_term_meta( $terms[0]->term_id, 'icon', true ), 'full' );
						if ( empty ( $icon ) ) {
							$icon = wp_get_attachment_image_url( $options['icon'], 'full' );
						}
					} else {
						$icon = wp_get_attachment_image_url( $options['icon'], 'full' );
					}
					?>
					<img src="<?php echo esc_url( $icon ); ?>"
						 alt="<?php esc_attr_e( 'Taxonomy Icon', 'lisfinity-core' ); ?>"
						 class="mr-2 injectable fill-taxonomy-icon <?php echo ! empty( $icon_size ) ? esc_attr( "w-{$icon_size} h-{$icon_size}" ) : 'w-12 h-12'; ?>">
				<?php endif; ?>
				<span class="ml-1 font-semibold">
					<?php if ( ! empty( $prefix ) ) : ?>
						<span class="taxonomy--prefix"><?php echo esc_html( $prefix ); ?></span>
					<?php endif; ?>
					<?php if ( empty( $term_label ) || 'icon_and_term' === $term_label || 'display_term' === $term_label ) : ?>
						<span><?php echo esc_html( $terms[0]->name ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $suffix ) ) : ?>
						<span class="taxonomy--suffix -ml-3"><?php echo esc_html( $suffix ); ?></span>
					<?php endif; ?>
                </span>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
