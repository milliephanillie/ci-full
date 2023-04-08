<?php
/**
 * Template Name: Page | Detailed Archive Page Template
 * Description: Page template that is being used as the archive page template across the site
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 *
 * @var $settings
 */
?>
<?php get_header(); ?>
<?php the_post(); ?>
<?php
$request = lisfinity_get_taxonomy_and_term();
$page    = lisfinity_load_correct_page_template( $request[1] );
?>

<?php echo lisfinity_get_elementor_content( $page ); ?>

<?php get_footer(); ?>
