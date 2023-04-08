<?php
/**
 * Custom template tags for this theme
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

if ( ! function_exists( 'lisfinity_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function lisfinity_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		printf(
			'<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_permalink() ),
			$time_string
		);
	}
endif;

if ( ! function_exists( 'lisfinity_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function lisfinity_posted_by() {
		?>
		<?php
		printf(
		/* translators: 1: post author, only visible to screen readers. 2: author link, 3: author name. */
			'<a href="%1$s" class="post-author--avatar"><img src="%2$s" alt="' . esc_attr__( 'Post Author', 'lisfinity' ) . '"/></a><span class="post-author-name">%3$s</span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			lisfinity_get_avatar_url( get_the_author_meta( 'ID' ) ),
			get_the_author_meta( 'display_name' )
		);
	}
endif;

if ( ! function_exists( 'lisfinity_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function lisfinity_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';

			/* translators: %s: Name of current post. Only visible to screen readers. */
			comments_popup_link( sprintf( esc_html__( 'Leave a comment on %s', 'lisfinity' ), get_the_title() ) );

			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'lisfinity_post_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function lisfinity_post_footer() {

		// Hide author, post date, category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			?>
			<div class="entry-footer--posted">
				<?php
				// Posted by
				lisfinity_posted_by();

				// Posted on
				lisfinity_posted_on();
				?>
			</div>
			<?php

			lisfinity_read_more();
		}

	}
endif;

if ( ! function_exists( 'lisfinity_read_more' ) ) :
	/**
	 * Displays a read more link that leads to a single page of the post
	 */
	function lisfinity_read_more() {
		printf(
			'<a href="%1$s" class="post--read-more"><span class="read-more-text">%2$s</span> <span class="icon">%3$s</span></a>',
			get_permalink(),
			esc_html__( 'Read More', 'lisfinity' ),
			'<svg version="1.1" height="24px" width="24px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
<path d="M5.1,52.9H88L78,62.8c-1.1,1.1-1.1,2.8,0,3.9c1.1,1.1,2.8,1.1,3.9,0l16.6-16.6l-1.9-1.9c0,0,0,0,0,0L81.9,33.6
	c-0.5-0.5-1.2-0.8-1.9-0.8s-1.4,0.3-1.9,0.8c-1.1,1.1-1.1,2.8,0,3.9l9.9,9.9H5.1c-1.5,0-2.7,1.2-2.7,2.8C2.4,51.7,3.6,52.9,5.1,52.9
	z"/>
</svg>'
		);
	}
endif;

if ( ! function_exists( 'lisfinity_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function lisfinity_post_thumbnail() {
		?>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="post-thumbnail">
				<a class="post-thumbnail-inner relative" href="<?php the_permalink(); ?>" aria-hidden="true"
				   tabindex="-1">
					<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					<?php $caption = get_the_post_thumbnail_caption(); ?>
					<?php if ( ! empty( $caption ) ) : ?>
						<span
							class="post-thumbnail-caption absolute bottom-20 left-40 font-semibold text-white"><?php echo esc_html( $caption ); ?></span>
					<?php endif; ?>
				</a>
			</figure>
		<?php else: ?>
			<?php $post_format = get_post_format(); ?>
			<?php
			if ( 'video' === $post_format ) :
				lisfinity_get_video_thumbnail();
			endif;
			?>
		<?php endif; ?>

		<?php
	}
endif;

function lisfinity_get_video_thumbnail() {
	$content = apply_filters( 'the_content', get_the_content() );
	// Only get video from the content if a playlist isn't present.
	if ( false === strpos( $content, 'wp-playlist-script' ) ) {
		$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
	}
	?>
	<?php if ( ! empty( $video ) ) : ?>
		<figure class="post-media-video">
			<?php echo wp_kses_post( $video[0] ); ?>
		</figure>
	<?php endif; ?>
	<?php
}

function lisfinity_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}

	return $tags;
}

add_filter( 'wp_kses_allowed_html', 'lisfinity_wpkses_post_tags', 10, 2 );

if ( ! function_exists( 'lisfinity_comment_avatar' ) ) :
	/**
	 * Returns the HTML markup to generate a user avatar.
	 */
	function lisfinity_get_user_avatar_markup( $id_or_email = null ) {

		if ( ! isset( $id_or_email ) ) {
			$id_or_email = get_current_user_id();
		}

		return sprintf( '<div class="comment-user-avatar comment-author vcard">%s</div>', get_avatar( $id_or_email, lisfinity_get_avatar_size() ) );
	}
endif;

if ( ! function_exists( 'lisfinity_discussion_avatars_list' ) ) :
	/**
	 * Displays a list of avatars involved in a discussion for a given post.
	 */
	function lisfinity_discussion_avatars_list( $comment_authors ) {
		if ( empty( $comment_authors ) ) {
			return;
		}
		echo '<ol class="discussion-avatar-list">', "\n";
		foreach ( $comment_authors as $id_or_email ) {
			printf(
				"<li>%s</li>\n",
				lisfinity_get_user_avatar_markup( $id_or_email )
			);
		}
		echo '</ol><!-- .discussion-avatar-list -->', "\n";
	}
endif;

if ( ! function_exists( 'lisfinity_comment_form' ) ) :
	/**
	 * Documentation for function.
	 */
	function lisfinity_comment_form() {
		comment_form(
			array(
				'logged_in_as' => null,
			)
		);
	}
endif;

if ( ! function_exists( 'lisfinity_the_posts_navigation' ) ) :
	/**
	 * Documentation for function.
	 *
	 * @param $post
	 */
	function lisfinity_the_posts_navigation( $post = false ) {
		global $wp_query;
		?>
		<?php if ( $wp_query->max_num_pages > 1 ) : ?>
			<div class="pagination--wrapper">
				<div class="post--prev-next">
					<?php previous_posts_link( '<span class="icon"><svg version="1.1" height="30px" width="30px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
					<path d="M95.8,47.4H12.9l9.9-9.9c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L2.4,50.1l1.9,1.9c0,0,0,0,0,0l14.6,14.6
					c0.5,0.5,1.2,0.8,1.9,0.8s1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9l-9.9-9.9h82.9c1.5,0,2.8-1.2,2.8-2.8
					C98.5,48.6,97.3,47.4,95.8,47.4z"/>
					</svg></span>' ); ?>
					<?php
					the_posts_pagination( [
						'mid_size'           => 2,
						'prev_text'          => '',
						'next_text'          => '',
						'screen_reader_text' => '',
					] );
					?>
					<?php next_posts_link( '<span class="icon"><svg version="1.1" height="30px" width="30px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 			viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
				<path d="M5.1,52.9H88L78,62.8c-1.1,1.1-1.1,2.8,0,3.9c1.1,1.1,2.8,1.1,3.9,0l16.6-16.6l-1.9-1.9c0,0,0,0,0,0L81.9,33.6
				c-0.5-0.5-1.2-0.8-1.9-0.8s-1.4,0.3-1.9,0.8c-1.1,1.1-1.1,2.8,0,3.9l9.9,9.9H5.1c-1.5,0-2.7,1.2-2.7,2.8C2.4,51.7,3.6,52.9,5.1,52.9
				z"/>
				</svg></span>' ); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
endif;

if ( ! function_exists( 'lisfinity_the_pages_navigation' ) ) :
	/**
	 * Documentation for function.
	 *
	 * @param $post
	 */
	function lisfinity_the_pages_navigation() {
		global $numpages;
		?>
		<?php if ( $numpages > 1 ) : ?>
			<div class="navigation pagination">
				<div class="pagination--wrapper">
					<?php
					wp_link_pages( [
						'mid_size'           => 2,
						'prev_text'          => '',
						'next_text'          => '',
						'screen_reader_text' => '',
					] );
					?>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
endif;

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function lisfinity_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}

add_action( 'wp_head', 'lisfinity_pingback_header' );

/**
 * Changes comment form default fields.
 */
function lisfinity_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}

add_filter( 'comment_form_defaults', 'lisfinity_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function lisfinity_get_the_archive_title() {
	if ( is_category() ) {
		$title = esc_html__( 'Category Archives: ', 'lisfinity' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = esc_html__( 'Tag Archives: ', 'lisfinity' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = esc_html__( 'Author Archives: ', 'lisfinity' ) . '<span class="page-description">' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = esc_html__( 'Yearly Archives: ', 'lisfinity' ) . '<span class="page-description">' . get_the_date( _x( 'Y', 'yearly archives date format', 'lisfinity' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = esc_html__( 'Monthly Archives: ', 'lisfinity' ) . '<span class="page-description">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'lisfinity' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = esc_html__( 'Daily Archives: ', 'lisfinity' ) . '<span class="page-description">' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = esc_html__( 'Post Type Archives: ', 'lisfinity' ) . '<span class="page-description">' . post_type_archive_title( '', false ) . '</span>';
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: %s: Taxonomy singular name */
		$title = sprintf( esc_html__( '%s Archives:', 'lisfinity' ), $tax->labels->singular_name );
	} else {
		$title = esc_html__( 'Archives:', 'lisfinity' );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'lisfinity_get_the_archive_title' );

/**
 * Returns the size for avatars used in the theme.
 */
function lisfinity_get_avatar_size() {
	return 60;
}

/**
 * Returns true if comment is by author of the post.
 *
 * @see get_comment_class()
 */
function lisfinity_is_comment_by_post_author( $comment = null ) {
	if ( is_object( $comment ) && $comment->user_id > 0 ) {
		$user = get_userdata( $comment->user_id );
		$post = get_post( $comment->comment_post_ID );
		if ( ! empty( $user ) && ! empty( $post ) ) {
			return $comment->user_id === $post->post_author;
		}
	}

	return false;
}

/**
 * Returns information about the current post's discussion, with cache support.
 */
function lisfinity_get_discussion_data() {
	static $discussion, $post_id;

	$current_post_id = get_the_ID();
	if ( $current_post_id === $post_id ) {
		return $discussion; /* If we have discussion information for post ID, return cached object */
	} else {
		$post_id = $current_post_id;
	}

	$comments = get_comments(
		array(
			'post_id' => $current_post_id,
			'orderby' => 'comment_date_gmt',
			'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
			'status'  => 'approve',
			'number'  => 20, /* Only retrieve the last 20 comments, as the end goal is just 6 unique authors */
		)
	);

	$authors = array();
	foreach ( $comments as $comment ) {
		$authors[] = ( (int) $comment->user_id > 0 ) ? (int) $comment->user_id : $comment->comment_author_email;
	}

	$authors    = array_unique( $authors );
	$discussion = (object) array(
		'authors'   => array_slice( $authors, 0, 6 ),           /* Six unique authors commenting on the post. */
		'responses' => get_comments_number( $current_post_id ), /* Number of responses. */
	);

	return $discussion;
}

if ( ! function_exists( 'lisfinity_display_gallery' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function lisfinity_display_gallery( $images ) {
		if ( empty( $images ) ) {
			return;
		}
		?>
		<?php $img_count = count( $images ); ?>
		<div class="flex flex-wrap <?php echo esc_attr( "post-gallery-{$img_count}" ); ?>">
			<?php $count = 1; ?>
			<?php foreach ( $images as $image_id ) : ?>
				<figure class="post-gallery-image <?php echo esc_attr( "image-{$count}" ); ?>">
					<?php $image = wp_get_attachment_url( $image_id ); ?>
					<img src="<?php echo esc_url( $image ); ?>"
						 alt="<?php echo esc_attr__( 'Post Gallery Image', 'lisfinity' ); ?>"
						 class="rounded"
					>
				</figure>
				<?php $count += 1; ?>
			<?php endforeach; ?>
		</div>

		<?php
	}
endif;

if ( ! function_exists( 'lisfinity_get_post_galleries' ) ) :
	function lisfinity_get_post_galleries( $post ) {
		$post = get_post( $post );
		$ids  = [];
		if ( has_block( 'gallery', $post->post_content ) ) {
			$post_blocks = parse_blocks( $post->post_content );
			if ( ! empty( $post_blocks ) ) {
				foreach ( $post_blocks as $block ) {
					if ( $block['blockName'] === 'core/gallery' ) {
						$ids = $block['attrs']['ids'];
					}
				}
			}
		} // if there is not a gallery block do this
		else {
			// gets the gallery info
			$gallery = get_post_gallery( $post->ID, false );
			// makes an array of image ids
			if ( ! empty( $gallery ) && ! empty( $gallery['ids'] ) ) {
				$ids = explode( ",", $gallery['ids'] );
			}
		}

		return ! empty( $ids ) ? $ids : false;
	}
endif;

if ( ! function_exists( 'lisfinity_change_add_to_cart_button' ) ) :
	function lisfinity_change_add_to_cart_button( $text ) {
		global $product;
		if ( ! lisfinity_is_core_active() && lisfinity_is_woocommerce_active() && 'external' === $product->get_type() ) {
			return __( 'Buy Product', 'lisfinity' );
		}

		return $text;
	}

	add_filter( 'woocommerce_product_add_to_cart_text', 'lisfinity_change_add_to_cart_button', 10, 1 );
endif;
