<?php
/**
 * Template Name: Shortcodes | Product Specification
 * Description: The file that is being used to display product specification shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>

<?php
$settings = [
	'membership_specification' => 'always',
];
if ( lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ) {
	$settings['membership_specification'] = lisfinity_get_option( 'membership-specification' );
}
?>
<div class="elementor-product-specification" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
