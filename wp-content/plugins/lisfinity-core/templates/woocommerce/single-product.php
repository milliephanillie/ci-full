<?php get_header(); ?>
<?php $elementor_page_id = lisfinity_get_option( 'page-single-listing' ); ?>

<?php do_action( 'lisfinity__single_product', get_the_ID() ); ?>

<?php if ( ! empty( $elementor_page_id ) && lisfinity_is_elementor( $elementor_page_id ) ): ?>
	<?php include lisfinity_get_template_part( 'page-single-elementor', 'pages' ); ?>
<?php else: ?>
	<?php include lisfinity_get_template_part( 'page-single', 'pages' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
