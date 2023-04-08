<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
*/


if ( post_password_required() ) {
	return;
}

global $numpages;
$discussion = lisfinity_get_discussion_data();
?>

<div id="comments"
     class="<?php echo comments_open() ? esc_attr( 'comments-area' ) : esc_attr( 'comments-area comments-closed' ); ?>">
	<?php $div_class = $discussion->responses > 0 ? esc_attr( 'comments-title-wrap' ) : esc_attr( 'comments-title-wrap no-responses' ); ?>
    <div
            class="<?php echo esc_attr( $div_class ); ?>">
        <h5 class="comments-title">
			<?php
			$count = get_comment_count( get_the_ID() );
			?>
			<?php if ( comments_open() ) : ?>
				<?php printf( esc_html__( 'Comments (%s)', 'lisfinity' ), $count['approved'] ) ?>
			<?php endif; ?>
        </h5><!-- .comments-title -->
    </div><!-- .comments-title-flex -->
	<?php
	if ( have_comments() ) :

		?>
        <ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'walker'            => new \Lisfinity\Walkers\Walker_Comment(),
					'avatar_size'       => lisfinity_get_avatar_size(),
					'short_ping'        => true,
					'style'             => 'ol',
					'reverse_top_level' => true,
				)
			);
			?>
        </ol><!-- .comment-list -->
		<?php

		// Show comment navigation
		if ( have_comments() ) :
			$comments_text = esc_html__( 'Comments', 'lisfinity' );
			?>
			<?php if ( get_comment_pages_count() > 1 ) : ?>
            <div class="pagination--wrapper">
				<?php
				the_comments_navigation();
				?>
            </div>
		<?php endif; ?>
		<?php
		endif;

		// Show comment form at bottom if showing newest comments at the bottom.
		if ( comments_open() ) :
			?>
			<?php lisfinity_comment_form(); ?>
		<?php
		endif;

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
            <p class="no-comments">
				<?php esc_html_e( 'Comments are closed.', 'lisfinity' ); ?>
            </p>
		<?php
		endif;

	else :

		// Show comment form.
		lisfinity_comment_form();

	endif; // if have_comments();
	?>
</div><!-- #comments -->
