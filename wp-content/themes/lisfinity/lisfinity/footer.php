<?php global $lisfinity_options; ?>
<?php if ( ( lisfinity_is_core_active() && 'custom' === lisfinity_get_option( 'footer-type' ) ) && ! in_array( get_queried_object_id(), [
		(int) $lisfinity_options['_page-search'],
		(int) $lisfinity_options['_page-account'],
	] ) ) : ?>
	<?php
	$footer      = lisfinity_get_option( 'footer-post' );
	$footer_page = carbon_get_post_meta( get_queried_object_id(), 'page-footer' );
	if ( ! empty( $footer_page ) ) {
		$footer = $footer_page;
	}
	echo lisfinity_get_elementor_content( $footer );
	?>
<?php elseif ( ( ! lisfinity_is_elements_template() && ( ! empty( $lisfinity_options ) && ! in_array( get_queried_object_id(), [
				(int) $lisfinity_options['_page-search'],
				(int) $lisfinity_options['_page-account'],
			] ) ) ) || ( ! lisfinity_is_core_active() || empty( $lisfinity_options ) ) ): ?>
	<footer
		class="footer flex flex-wrap pt-64 sm:pt-86 px-20 lg:px-86 pb-44 bg-grey-1100">
		<div class="container flex flex-wrap">
			<div
				class="flex flex-col px-col w-full sm:w-1/2 <?php echo lisfinity_is_core_active() ? 'lg:w-4/12' : 'lg:w-3/12'; ?>">
				<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
			</div>
			<div
				class="flex flex-col px-col w-full sm:w-1/2 lg:w-3/12">
				<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
			</div>
			<div
				class="flex flex-col px-col w-full sm:w-1/2 lg:w-3/12">
				<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
			</div>
			<div
				class="flex flex-col px-col w-full sm:w-1/2 <?php echo lisfinity_is_core_active() ? 'lg:w-2/12' : 'lg:w-3/12'; ?>">
				<?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
			</div>
		</div>
	</footer>
	<?php do_action( 'lisfinity__footer_functions' ); ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
