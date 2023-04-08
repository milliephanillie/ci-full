<?php
/**
 * Custom comment walker for this theme
 *
 * @package Lisfinity
 * @author pebas
 * @since 1.0.0
 */

namespace Lisfinity\Walkers;

/**
 * This class outputs custom comment walker for HTML5 friendly WordPress comment and threaded replies.
 *
 * @since 1.0.0
 */
class Walker_Comment extends \Walker_Comment {
	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int $depth Depth of the current comment.
	 * @param array $args An array of arguments.
	 *
	 * @see wp_list_comments()
	 *
	 */
	protected function html5_comment( $comment, $depth, $args ) {

		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body flex flex-wrap">
			<footer class="comment-meta w-full">
				<div class="comment-author vcard">
					<?php
					$comment_author_url = get_comment_author_url( $comment );
					$comment_author     = get_comment_author( $comment );
					$avatar             = get_avatar( $comment, $args['avatar_size'] );
					if ( ! $avatar ) {
						$avatar_url = get_avatar_url( lisfinity_get_comment_user_id( $comment->comment_ID ) );
						$img        = '<figure class="avatar--figure"><img src="' . esc_url( $avatar_url ) . '" class="avatar avatar-default" /></figure>';
						echo wp_kses_post( $img );
					}
					if ( 0 != $args['avatar_size'] ) {
						if ( empty( $comment_author_url ) ) {
							echo wp_kses_post( $avatar );
						} else {
							printf( '<a href="%s" rel="external nofollow" class="url">', $comment_author_url );
							echo wp_kses_post( $avatar );
						}
					}
					/*
					 * Using the `check` icon instead of `check_circle`, since we can't add a
					 * fill color to the inner check shape when in circle form.
					 */
					if ( lisfinity_is_comment_by_post_author( $comment ) ) {
						printf( '<span class="post-author-badge" aria-hidden="true">%s</span>', '' );
					}

					/*
					 * Using the `check` icon instead of `check_circle`, since we can't add a
					 * fill color to the inner check shape when in circle form.
					 */
					if ( lisfinity_is_comment_by_post_author( $comment ) ) {
						printf( '<span class="post-author-badge" aria-hidden="true">%s</span>', '' );
					}

					printf(
					/* translators: %s: comment author link */
						wp_kses(
							__( '%s <span class="screen-reader-text says">says:</span>', 'lisfinity' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						'<span class="comment--author">' . $comment_author . '</span>'
					);

					if ( ! empty( $comment_author_url ) ) {
						echo '</a>';
					}
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<?php
						/* translators: 1: comment date, 2: comment time */
						$comment_timestamp = sprintf( esc_html__( '%1$s at %2$s', 'lisfinity' ), get_comment_date( '', $comment ), get_comment_time() );
						?>
						<time datetime="<?php comment_time( 'c' ); ?>" title="<?php echo esc_attr( $comment_timestamp ); ?>">
							<?php echo sprintf( esc_html__( '%s ago', 'lisfinity' ), human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) ); ?>
						</time>
					</a>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'lisfinity' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content w-full">
				<?php comment_text(); ?>
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below'  => 'div-comment',
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'before'     => '<div class="comment-reply">',
							'after'      => '</div>',
							'reply_text' => sprintf( esc_html__( 'Leave reply %s', 'lisfinity' ),
								'<span class="icon"><svg version="1.1" width="24px" height="24px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
									<path d="M5.1,52.9H88L78,62.8c-1.1,1.1-1.1,2.8,0,3.9c1.1,1.1,2.8,1.1,3.9,0l16.6-16.6l-1.9-1.9c0,0,0,0,0,0L81.9,33.6
									c-0.5-0.5-1.2-0.8-1.9-0.8s-1.4,0.3-1.9,0.8c-1.1,1.1-1.1,2.8,0,3.9l9.9,9.9H5.1c-1.5,0-2.7,1.2-2.7,2.8C2.4,51.7,3.6,52.9,5.1,52.9
									z"/>
									</svg></span>' ),
						)
					)
				);
				?>
			</div><!-- .comment-content -->

		</article><!-- .comment-body -->
		<?php
	}
}
