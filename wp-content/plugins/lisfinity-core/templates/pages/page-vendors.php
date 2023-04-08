<?php
/**
 * Template Name: Page | All vendors page template
 * Description: Page template that is being used as the template for premium_profile CPT archive
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php $options = [
]; ?>

<?php get_header(); ?>
<?php the_post(); ?>
<?php if ( lisfinity_is_elementor() ) : ?>
<!--	<div id="loader"-->
<!--		 class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"-->
<!--		 style="z-index: 99999;">-->
<!---->
<!--		<div class="flex-center flex-col">-->
<!--			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"-->
<!--				 style="margin: auto; display: block; shape-rendering: auto; zoom: .4;" width="200px" height="200px"-->
<!--				 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">-->
<!--				<path class="ll" fill="none" stroke="#333" stroke-width="8"-->
<!--					  stroke-dasharray="42.76482137044271 42.76482137044271"-->
<!--					  d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z"-->
<!--					  stroke-linecap="round"-->
<!--					  style="transform:scale(0.8);transform-origin:50px 50px">-->
<!--					<animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1"-->
<!--							 values="0;256.58892822265625"></animate>-->
<!--				</path>-->
<!--			</svg>-->
<!--			<p class="text-lg text-grey-900">--><?php //esc_html_e( 'Loading Page...', 'lisfinity-core' ); ?><!--</p>-->
<!--		</div>-->
<!--	</div>-->
	<div id="page-vendors-elementor" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
	<?php the_content(); ?>
<?php else: ?>
<main id="page-archive-vendors" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</main>
<div id="loader"
     class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col h-full w-full bg-white"
     style="z-index: 9999;">

	<div class="flex-center flex-col">
		<?php $icon_args = [
			'zoom' => 1,
		]; ?>
		<img src="<?php echo esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings.4bcf82c529.svg' ); ?>"
		     alt="<?php echo esc_html__( 'Ad Loader', 'lisfinity-core' ) ?>"/>
		<p class="mt-20 text-lg text-grey-900">Loading...</p>
	</div>

</div>
<?php endif; ?>
<?php get_footer(); ?>

