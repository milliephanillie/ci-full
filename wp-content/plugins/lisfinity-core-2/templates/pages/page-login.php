<?php
/**
 * Template Name: Page | Login
 * Description: Page template that is being used for users login to the site.
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */
?>

<?php get_header(); ?>
<?php the_post(); ?>
<?php if ( ! lisfinity_is_elementor() && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) : ?>
	<main id="page-auth">

	</main>
<?php else : ?>
	<?php the_content(); ?>
<?php endif; ?>
<?php get_footer(); ?>


