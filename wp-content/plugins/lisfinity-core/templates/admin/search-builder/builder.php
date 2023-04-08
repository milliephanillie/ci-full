<?php
/**
 * Template: Admin / Search Builder
 * Description: Template for creating search form for
 * various places on the site
 *
 * @author pebas
 * @package ager-core/admin
 * @version 1.0.0
 *
 * @var $options - @see includes/src/SearchBuilder/SearchBuilderModel.php
 */
?>
<?php $options = [
	'detailed_search' => '0' !== lisfinity_get_option( 'site-detailed-search' ),
]; ?>

<div class="lisfinity-wrapper md:py-20 md:px-20">
	<div id="lisfinity-search-builder" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>"></div>
</div>
