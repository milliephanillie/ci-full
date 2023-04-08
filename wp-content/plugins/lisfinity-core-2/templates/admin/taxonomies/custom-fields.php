<?php
/**
 * Template: Admin / Ads custom fields
 * Description: Template for creating custom taxonomies for ad post type
 *
 * @author pebas
 * @package ager-core/admin
 * @version 1.0.0
 *
 * @var $options - @see includes/Admin/Admin_Taxonomies.php
 * @var $options_list - @see includes/Admin/Admin_Taxonomies.php
 */
?>

<?php $options = [
	'hide_child_terms' => '0' !== lisfinity_get_option( 'site-fields-builder-terms-hide' ),
	'terms_limit'      => lisfinity_get_option( 'site-fields-builder-terms-limit' ) ?? 30,
]; ?>

<div class="lisfinity-wrapper lisfinity-wrapper--builder md:py-20 md:px-20">
	<input type="hidden" id="field_group" name="field_group"
		   data-options="<?php echo esc_attr( json_encode( $options ) ); ?>"
		   data-name="<?php echo esc_attr( ucwords( str_replace( [ '-', '_' ], [
			   ' ',
			   ' '
		   ], $args['fields_group'] ) ) ); ?>"
		   data-taxonomies="<?php echo esc_attr( json_encode( $args['taxonomies'], true ) ); ?>"
		   value="<?php echo esc_attr( $args['fields_group'] ); ?>">
	<div id="lisfinity-dashboard"></div>
</div>
