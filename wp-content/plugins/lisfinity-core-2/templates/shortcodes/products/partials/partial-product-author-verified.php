<?php
/**
 * Template Name: Shortcodes | Product Partials | Verified
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$product_id = get_the_ID();
$user_id    = carbon_get_post_meta( $product_id, 'product-owner' );
$verified   = carbon_get_user_meta( $user_id, 'verified' );
$icon       = '';

if ( ! empty( $args['settings']['icon_author_badge']['value'] ) ) {
	$icon = $args['settings']['icon_author_badge']['value'];
}
?>
<!-- Product | Verified -->
<div class="absolute top-20 left-20 flex-center w-32 h-32 rounded-full author-verified-container" title="<?php esc_attr_e( 'Verified User', 'lisfinity-core' ); ?>">
		<span class="flex-center w-full h-full bg-green-500 rounded-full author-verified-wrapper">
			<?php if ( empty( $icon ) ) : ?>
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 class="w-14 h-14 fill-white"
					 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
					<g>
						<path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
							c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z"/>
					</g>
				</svg>
			<?php elseif ( is_array( $icon ) ) : ?>
				<img class="author-verified-icon h-14 w-14"
					 src="<?php echo esc_url( $icon['url'] ); ?>"
					 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
			<?php else : ?>
				<i class="<?php echo esc_html( $icon ) ?> author-verified-icon h-14 w-14"
				   aria-hidden="true"></i>
			<?php endif; ?>
		</span>
</div>
