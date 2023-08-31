<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>

<div class="listing-links">
    <div class="listing-links-inner">
        <?php
        global $wpdb;


        // Your given order_id
        $order_id = $order->get_id();  // Replace this with your actual order_id value

        // Prepared statement to retrieve the id based on the order_id
        $sql = $wpdb->prepare("SELECT id FROM {$wpdb->prefix}lisfinity_packages WHERE order_id = %s", $order_id);

        // Execute the query and get the id value
        $id = $wpdb->get_var($sql);

        // Fetch the post with the matching post meta
        $args = array(
            'post_type'      => 'product',   // Replace 'post' with your custom post type if different
            'meta_key'       => '_payment-package',
            'meta_value'     => $id,
            'posts_per_page' => 1,  // Fetch only one post
        );

        $posts = get_posts($args);

        if (!empty($posts)) {
            $post = $posts[0];  // Get the first (and only) post
            $permalink = get_permalink($post->ID);

            // Create a hyperlink to the post
            echo '<div style="display:flex; justify-content: center;">';
            echo '<a class="button" style="margin-right: 20px;" href="' . esc_url($permalink) . '">View Listing</a>';
            echo '<a class="button" href="' . esc_url(untrailingslashit(home_url()) .'/my-account/ads') . '">View All Listings</a>';
            echo '</div>';
        } else {
            echo "No post found with matching _payment-package meta value.";
        }




        ?>
    </div>
</div>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">

	<td class="woocommerce-table__product-name product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

		echo $item->get_name(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$qty          = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

		if ( $refunded_qty ) {
			$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * - 1 ) ) . '</ins>';
		} else {
			$qty_display = esc_html( $qty );
		}

		echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

		wc_display_item_meta( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
		?>
	</td>

	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</td>

</tr>

<?php if ( $show_purchase_note && $purchase_note ) : ?>

	<tr class="woocommerce-table__product-purchase-note product-purchase-note">

		<td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>

	</tr>

<?php endif; ?>
