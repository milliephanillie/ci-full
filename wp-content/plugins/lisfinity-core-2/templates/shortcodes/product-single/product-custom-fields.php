<?php
/**
 * Template Name: Shortcodes | Product Owner Name
 * Description: The file that is being used to display product owner name shortcode
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
$field_value = get_post_meta( $post_id, $args['settings']['select_fields'], true );
?>
<div class="elementor-product-custom-fields">
	<div class="product-custom-fields">
		<?php if ( 'published-date' === $args['settings']['select_fields'] ) : ?>
			<?php echo esc_html( get_the_date( $args['settings']['date_format'], $post_id ) ); ?>
		<?php else : ?>
			<?php echo esc_html( $field_value ); ?>
		<?php endif; ?>
	</div>
</div>

