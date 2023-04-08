<?php
/**
 * Template part for displaying posts
 *
 * @package WordPress
 * @subpackage Lisfinity
 * @since 1.0.0
 */
?>

<?php if ( is_search() ) : ?>
	<?php $query = get_search_query(); ?>
	<?php if ( ! empty( $query ) ) : ?>
		<header class="mb-30 px-col w-full lg:w-4/6">
			<h6 class="page-title font-normal"><?php printf( wp_kses_post( __( 'Displaying results for: <strong>%s</strong>', 'lisfinity' ) ), esc_html( $query ) ); ?></h6>
		</header><!-- .page-header -->
	<?php endif; ?>
<?php endif; ?>

