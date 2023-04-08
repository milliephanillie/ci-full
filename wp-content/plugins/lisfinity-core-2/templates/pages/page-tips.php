<?php
/**
 * Template Name: Page | Safety Tips Template
 * Description: Page template that is being used for displaying security tips
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */
?>
<?php get_header(); ?>
<?php the_post(); ?>

<?php $category = ! empty( $_GET['t'] ) ? $_GET['t'] : ''; ?>
<!-- Tips -->
<main id="page-tips">
</main>
<div id="loader" class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
     style="z-index: 9999;">

	<div class="flex-center flex-col">
		<?php $icon_args = [
			'zoom' => 1,
		]; ?>
		<img src="<?php echo esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings.4bcf82c529.svg' ); ?>"
		     alt="<?php echo esc_html__( 'Loader', 'lisfinity-core' ) ?>"/>
		<p class="mt-20 text-lg text-grey-900"><?php _e( 'Loading...', 'lisfinity-core' ); ?></p>
	</div>

</div>


<?php get_footer(); ?>
