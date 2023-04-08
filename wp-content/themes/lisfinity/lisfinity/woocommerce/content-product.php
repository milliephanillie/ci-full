<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 5.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	?>

    <div class="woocommerce-product--wrapper">
        <div class="woocommerce-product--thumbnail">
			<?php
			/**
			 * Hook: woocommerce_before_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
        </div>

        <div class="woocommerce-product--content">
            <div class="woocommerce-product--title">
                <div class="woocommerce-product--meta flex items-center justify-between">
					<?php $category = get_the_terms( get_the_ID(), 'product_cat' ); ?>
					<?php if ( ! empty( $category ) && ! is_wp_error( $category ) ): ?>
                        <span class="woocommerce-product--category text-grey-700"><?php echo esc_html( $category[0]->name ); ?></span>
					<?php endif; ?>
					<?php if ( $product->is_in_stock() ): ?>
                        <span class="text-green-800"><?php esc_html_e( 'In stock', 'lisfinity' ); ?></span>
					<?php else: ?>
                        <span class="text-red-700"><?php esc_html_e( 'Out of stock', 'lisfinity' ); ?></span>
					<?php endif; ?>
                </div>
				<?php
				/**
				 * Hook: woocommerce_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_product_title - 10
				 * @hooked woocommerce_show_product_loop_sale_flash - 15
				 */
				do_action( 'woocommerce_shop_loop_item_title' );
				?>

            </div>
			<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			?>
            <div class="woocommerce-product--price">
				<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
            </div>
        </div>
		<?php
		?>
    </div>
</li>
