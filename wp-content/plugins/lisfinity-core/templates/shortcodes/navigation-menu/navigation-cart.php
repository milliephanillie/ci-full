<?php
/**
 * Template Name: Shortcodes | Navigation Cart
 * Description: The file that is being used to display user cart
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */
?>

<?php
if($args['settings']['selected_icon_cart']) {
	$icon = $args['settings']['selected_icon_cart']['value'];
}
?>
<?php $count = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''; ?>
<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
   id="cart--wrapper"
   class="relative ml-20"
   style="top: -1px;"
   title="<?php echo esc_html__( 'Cart', 'lisfinity-core' ); ?>"
>
	<?php if (empty($args['settings']['place_icon_cart']) || (!empty($args['settings']['place_icon_cart'] && empty($icon)))) : ?>
		<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
			 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
			 class="cart--icon"
			 y="0px"
			 viewBox="0 0 100 100"
			 style="enable-background:new 0 0 100 100;"
			 xml:space="preserve"
			 width="24px"
			 height="24px"
			 fill="#199473">
					<g>
						<path d="M90.6,2.5h-8c-1.9,0-3.5,1.4-3.7,3.4L77,24.9H11.4c-1.5,0-2.8,0.7-3.8,1.8c-0.9,1.2-1.2,2.7-0.8,4.1l10.7,41.9
							c0.5,2.1,2.4,3.6,4.6,3.6H73c0.3,0,0.7,0,1-0.1c0.2,0,0.3,0.1,0.5,0.1c0.1,0,0.2,0,0.3,0c1.4,0,2.6-1.1,2.7-2.5l0.2-1.7
							c0,0,0,0,0,0l4.9-47.1h-0.1L84.2,8h6.4c1.5,0,2.8-1.2,2.8-2.8S92.2,2.5,90.6,2.5z M12.3,30.4h64.1l-4.1,40.4H22.7L12.3,30.4z"/>
						<circle cx="31.2" cy="89.4" r="8"/>
						<circle cx="64.2" cy="89.4" r="8"/>
					</g>
				</svg>
	<?php elseif (is_array($icon)) : ?>

		<img src=" <?php echo( $icon['url'] ); ?>"
			 alt=" <?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>"
			 class="w-20 h-20 fill-icon-reset pointer-events-none cart--icon"/>
	<?php else: ?>
		<i class="<?php echo esc_html__($icon, 'lisfinity-core') ?> cart--icon" aria-hidden="true"></i>
	<?php endif; ?>
	<span
		class="cart-count absolute flex-center w-16 h-16 bg-grey-200 rounded-full text-xs text-grey-1100 pointer-events-none <?php echo $count === 0 ? esc_attr( 'hidden' ) : ''; ?>"
		style="top: -8px; right: -10px"><?php echo esc_attr( $count ); ?></span>
</a>
