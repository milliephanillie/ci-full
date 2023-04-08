<?php
/**
 * Template Name: Shortcodes | Product Description Field
 * Description: The file that is being used to display product description field shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php
$post_id     = lisfinity_get_product_id();
$field_value_name = get_post_meta( $post_id, 'title', true );
$field_value_description = get_post_meta( $post_id, 'description', true );
$field_value_from_who = get_post_meta( $post_id, 'from-who', true );
?>
<div class="elementor-product-description-field flex">
	<div class="product-custom-description">
		<h2 class="custom-description-title"><?php echo esc_html( $field_value_name ); ?></h2>
		<p class="custom-description-content"><?php echo esc_html( $field_value_description ); ?></p>
		<h4 class="custom-description-from-who"><?php echo esc_html( $field_value_from_who ); ?></h4>
	</div>
</div>
