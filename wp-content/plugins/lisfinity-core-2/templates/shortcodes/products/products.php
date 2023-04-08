<?php
/**
 * Template Name: Shortcodes | Products Style Loader
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */

?>

<div class="lisfinity-product-tabs">
	<?php if ( ! empty( $args['tab_titles'] ) && count( $args['tab_titles'] ) > 1 ): ?>
		<?php $tab_products_count = 0; ?>
		<div class="product-tabs inline-flex flex-wrap sm:mb-48 shadow-theme overflow-hidden">
			<?php foreach ( $args['tab_titles'] as $title ) : ?>
				<?php $slug = sanitize_title( $title ); ?>
				<div class="product-tabs--header w-full xs:w-auto">
					<button type="button"
							class="product-tab flex items-center sm:justify-center py-14 px-32 w-full h-full text-sm sm:text-base bg-white sm:bg-transparent <?php echo 0 === $tab_products_count ? esc_attr( 'active' ) : ''; ?>"
							data-tab="<?php echo esc_html( $slug ); ?>">
						<?php echo esc_html( $title ); ?>
					</button>
				</div>
				<?php $tab_products_count += 1; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $args['tab_products'] ) ): ?>
		<?php $tab_products_count = 0; ?>


		<?php foreach ( $args['tab_products'] as $product ) : ?>
			<?php $args['options'] = $args['settings']['product_tabs'][ $tab_products_count ]; ?>
			<?php if ( lisfinity_is_elementor_preview() && 'yes' === ( $args['options']['visited'] ) && empty( get_user_meta( get_current_user_id(), 'recent-listings' ) ) ) : ?>
				<div class="flex mb-10 p-20 rounded bg-blue-100 border border-blue-300 text-blue-500 font-semibold">
					<?php esc_html_e( 'At the moment you haven\'t visited any of the listings and the latest listings will be shown to you. This message is only visible in the Elementor preview mode.', 'lisfinity-core' ); ?>
				</div>
			<?php endif; ?>
			<div
				class="product-tabs--content elementor-repeater-item-<?php echo $args['options']['_id']; ?> <?php echo esc_attr( "listing-style__{$args['settings']['style']}" ); ?> <?php echo 0 !== $tab_products_count ? esc_attr( 'hidden' ) : 'block'; ?>"
				data-content="<?php echo esc_html( sanitize_title( $args['tab_titles'][ $tab_products_count ] ) ); ?>">
				<?php $args['products'] = $product; ?>
				<?php include lisfinity_get_template_part( "product-style-{$args['settings']['style']}", 'shortcodes/products/product-styles', $args ); ?>
			</div>


			<?php $tab_products_count += 1; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
