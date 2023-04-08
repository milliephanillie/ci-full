<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Lisfinity
 * @since 1.0.0
 */

?>

<?php $has_thumbnail = has_post_thumbnail(); ?>
<?php $formats = [ 'gallery', 'video' ]; ?>
<article
	id="post-<?php the_ID(); ?>" <?php post_class( ! $has_thumbnail && ! in_array( get_post_format(), $formats ) ? esc_attr( 'post-alternate' ) : '' ); ?>>

	<?php $media = lisfinity_get_post_galleries( get_the_ID() ); ?>
	<?php if ( 'gallery' !== get_post_format() || empty( $media ) ) : ?>
		<?php lisfinity_post_thumbnail(); ?>
	<?php else: ?>
		<?php $media = 6 <= count( $media ) ? array_slice( $media, 0, 6 ) : $media; ?>
		<?php lisfinity_display_gallery( $media ); ?>
	<?php endif; ?>

	<div class="entry-wrapper">
		<header class="entry-header <?php echo empty( get_the_title() ) ? esc_attr( 'post-no-title' ) : ''; ?>">
			<?php $categories = get_the_category( get_the_ID() ); ?>
			<?php if ( ! empty( $categories ) ) : ?>
				<!-- Post | Category -->
				<div class="post--category mb-10">
					<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
					   class="text-grey-600"><?php echo esc_html( $categories[0]->name ); ?></a>
				</div>
			<?php endif; ?>
			<?php
			if ( is_sticky() && is_home() && ! is_paged() ) {
				printf( '<span class="sticky-post">%s</span>', _x( 'Featured', 'post', 'lisfinity' ) );
			}
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
			?>
		</header><!-- .entry-header -->

		<?php $excerpt = get_the_excerpt(); ?>
		<?php if ( ! empty( $excerpt ) ) : ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-footer">
			<?php lisfinity_post_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
