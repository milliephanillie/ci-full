<?php
/**
 * Template Name: Shortcodes | Premium Profile Banner Image
 * Description: The file that is being used to display product banner Image shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php
$post_id = lisfinity_get_product_id();
if ( $args['settings']['select_fields'] !== 'default' ) {
	$thumbnail_id = get_post_meta( $post_id, $args['settings']['select_fields'], true );
}
if ( wp_get_attachment_image_url( $thumbnail_id, 'full' ) ) {
	$image = wp_get_attachment_image_url( $thumbnail_id, 'full' );
} else if ($args['settings']['select_fields'] === 'fallback-image') {
	$image = $args['settings']['fallback_image']['url'] ?? '';
} else {
	$image = $args['settings']['fallback_image']['url'] ?? '';
}
?>

<div class="elementor-premium-banner-image">
	<figure class="premium-banner--image relative w-full h-full">
		<!-- Post | Link -->
		<span class="image-overlay absolute top-0 left-0 w-full h-full z-1"></span>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>"
				 alt="<?php esc_attr_e( 'Banner Image', 'lisfinity-core' ); ?>"
				 class="absolute w-full h-full object-cover">
		<?php endif; ?>
	</figure>
</div>
