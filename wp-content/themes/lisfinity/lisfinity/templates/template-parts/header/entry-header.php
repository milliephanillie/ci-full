<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Lisfinity
 * @since 1.0.0
 */

$discussion = ! is_page() && lisfinity_can_show_post_thumbnail() ? lisfinity_get_discussion_data() : null; ?>

<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

<?php if ( ! is_page() ) : ?>
	<div class="entry-meta">
		<?php lisfinity_posted_by(); ?>
		<?php lisfinity_posted_on(); ?>
		<span class="comment-count">
		<?php
		if ( ! empty( $discussion ) ) {
			lisfinity_discussion_avatars_list( $discussion->authors );
		}
		?>
		<?php lisfinity_comment_count(); ?>
	</span>
	</div><!-- .entry-meta -->
<?php endif; ?>
