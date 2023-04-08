<?php
/**
 * Model for our custom Testimonials functionality
 *
 * @author pebas
 * @package lisfinity-reports
 * @version 1.0.0
 */

namespace Lisfinity\Models\Testimonials;


class TestimonialModel {

	public function init() {
		add_action( 'comment_text', array( $this, 'admin_comments_style' ) );
	}

	public function get_all_business_reviews( $args = [] ) {
		$comments_args['type'] = 'review';
		if ( ! empty( $args ) ) {
			$comments_args = wp_parse_args( $args, $comments_args );
		}
		$comments = get_comments( $comments_args );

		return $comments;
	}

	public function get_business_reviews( $id ) {
		$comments = get_comments( [
			'post_id' => $id,
			'type'    => 'review',
			'fields'  => 'ids',
		] );

		return $comments;
	}

	public function calculate_business_average_rating( $id ) {
		$reviews = $this->get_business_reviews( $id );

		$to_hash         = json_encode( count( $reviews ) ) . $id;
		$reviews_to_hash = 'lisfinity_' . md5( $to_hash ) . lisfinity_get_transient_version( 'business-reviews', false );

		if ( false === ( $average_rating = get_transient( $reviews_to_hash ) ) ) {
			if ( empty( $reviews ) ) {
				return carbon_get_theme_option( 'business-reviews-default-rating' );
			}

			$avg = 0;
			foreach ( $reviews as $review ) {
				$avg += $this->calculate_single_review_average( $review );
			}

			$average_rating = $avg / count( $reviews );
			update_post_meta( $id, '_business_average_rating', $average_rating );

			set_transient( $reviews_to_hash, $average_rating, DAY_IN_SECONDS * 30 );
		}

		return $average_rating;
	}

	public function calculate_single_review_average( $comment_id ) {
		$review_options = carbon_get_theme_option( 'business-reviews-options' );
		$options        = array_column( $review_options, 'review-option' );
		$ratings        = [];
		foreach ( $options as $option ) {
			$slug               = sanitize_title( $option );
			$ratings[ $option ] = get_comment_meta( $comment_id, "rating-{$slug}", true );
		}

		$count = 0;
		$avg   = 0;
		if ( ! empty( $ratings ) ) {
			foreach ( $ratings as $label => $value ) {
				$avg   += (int) $value;
				$count += 1;
			}
		}

		$count = ( is_int( $count ) && 0 !== $count ) ? $count : 1;
		$avg   = is_float( $avg ) || is_int( $avg ) ? $avg : carbon_get_theme_option( 'business-reviews-default-rating' );

		return ( $avg / $count );
	}

	public function admin_comments_style( $comment = '' ) {
		$review_options = carbon_get_theme_option( 'business-reviews-options' );
		$options        = array_column( $review_options, 'review-option' );
		$comment_id     = get_comment_ID();
		if ( 'review' !== get_comment_type( $comment_id ) ) {
			return $comment;
		}
		$ratings = [];
		foreach ( $options as $option ) {
			$slug               = sanitize_title( $option );
			$ratings[ $option ] = get_comment_meta( $comment_id, "rating-{$slug}", true );
		}
		if ( ! empty( $ratings ) ) {
			$count   = 0;
			$avg     = 0;
			$comment .= '<div class="review--ratings">';
			foreach ( $ratings as $label => $value ) {
				$comment .= '<div class="review--rating"><div class="review--label">' . $label . '</div><div class="review--stars">' . self::generate_review_starts( $comment_id, $value ) . '</div></div>';
				$avg     += is_numeric( $value ) ? $value : 0;
				$count   += 1;
			}
			$comment .= '<div class="review--avg"><div class="review--label">' . __( 'Rating', 'lisfinity-core' ) . '</div><div class="review--rating">' . number_format_i18n( ( (int) $avg / $count ), 1 ) . '</div></div>';
			$comment .= '</div>';
		}

		return $comment;
	}

	public function generate_review_starts( $id = '', $rating = '' ) {
		$id = ! empty( $id ) ? $id : get_the_ID();
		ob_start();
		?>
		<?php echo lisfinity_load_star_icon_svg( 'star-filled' ); ?>
		<?php echo 1.8 <= $rating ? lisfinity_load_star_icon_svg( 'star-filled' ) : ( 1.2 <= $rating ? lisfinity_load_star_icon_svg( 'star-half' ) : lisfinity_load_star_icon_svg( 'star' ) ); ?>
		<?php echo 2.8 <= $rating ? lisfinity_load_star_icon_svg( 'star-filled' ) : ( 2.2 <= $rating ? lisfinity_load_star_icon_svg( 'star-half' ) : lisfinity_load_star_icon_svg( 'star' ) ); ?>
		<?php echo 3.8 <= $rating ? lisfinity_load_star_icon_svg( 'star-filled' ) : ( 3.2 <= $rating ? lisfinity_load_star_icon_svg( 'star-half' ) : lisfinity_load_star_icon_svg( 'star' ) ); ?>
		<?php echo 4.8 <= $rating ? lisfinity_load_star_icon_svg( 'star-filled' ) : ( 4.2 <= $rating ? lisfinity_load_star_icon_svg( 'star-half' ) : lisfinity_load_star_icon_svg( 'star' ) ); ?>
		<?php
		$stars = ob_get_clean();

		return $stars;
	}

}
