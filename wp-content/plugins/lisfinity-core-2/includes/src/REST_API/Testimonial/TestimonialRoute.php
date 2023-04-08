<?php


namespace Lisfinity\REST_API\Testimonial;

use Lisfinity\Models\Testimonials\TestimonialModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class TestimonialRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_reviews'   => [
			'path'                => '/testimonial/get/(?P<business>\d+)',
			'rest_path'           => '/testimonial/get',
			'callback'            => 'get_testimonials',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'submit_review' => [
			'path'                => '/testimonial/store',
			'callback'            => 'submit_review',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function get_testimonials( WP_REST_Request $request_data ) {
		global $wpdb;
		$data = $request_data->get_params();
		$args = [
			'post_id' => $data['business'],
			'status'  => '1',
			'type'    => 'review',
			'number'  => 12,
		];

		$args['page'] = ! empty( $data['offset'] ) ? $data['offset'] : 1;

		if ( ! empty( $data['offset'] ) ) {
			$offset         = ( absint( $args['page'] ) - 1 ) * $args['number'];
			$args['offset'] = $offset;
		}

		$testimonials = new \WP_Comment_Query( $args );

		$comments = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM {$wpdb->comments} WHERE comment_post_ID={$data['business']} AND comment_type='review'" );

		$result['comments']      = $this->prepare_comments_meta( $testimonials->comments );
		$result['max_num_pages'] = ceil( $comments / $args['number'] );
		$result['page']          = $args['page'];

		return $result;
	}

	protected function prepare_comments_meta( $comments ) {
		if ( empty ( $comments ) ) {
			return $comments;
		}

		$model = new TestimonialModel();
		foreach ( $comments as $comment ) {
			$user = get_user_by( 'id', $comment->user_id );

			$author             = $comment->comment_author;
			$comment->thumbnail = lisfinity_get_avatar_url( $user->user_id );
			$comment->title     = $author !== $user->user_nicename ? $author : ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ? "$user->first_name $user->last_name" : $user->display_name );
			$comment->date_year = date( 'Y', strtotime( $comment->comment_date ) );
			$comment->rating    = $model->calculate_single_review_average( $comment->comment_ID );
		}

		return $comments;
	}

	public function submit_review( WP_REST_Request $request_data ) {
		global $wpdb;
		$data   = $request_data->get_params();
		$result = [];

		$userdata = get_userdata( $data['author'] );

		$comments = $wpdb->get_var( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID={$data['post']} AND comment_author_email='{$userdata->user_email}'" );

		if ( ! empty( $comments ) ) {
			$result['error']   = true;
			$result['message'] = __( 'You already posted a review for this business', 'lisfinity-core' );

			return $result;
		}

		$data['date'] = current_time( 'mysql' );

		$comment_data                     = $this->prepare_item_for_database( $data );
		$comment_data['comment_agent']    = $request_data->get_header( 'user_agent' );
		$comment_data['comment_approved'] = 1;
		$comment_data['comment_type']     = 'review';

		$comment_id = wp_insert_comment( $comment_data );

		foreach ( $data as $key => $value ) {
			$slug = sanitize_title( $key );
			if ( false !== strpos( $slug, 'rating' ) ) {
				update_comment_meta( $comment_id, $slug, $value );
			}
		}

		$result['success'] = true;
		$result['message'] = __( 'Thank you! You have successfully submitted a review.', 'lisfinity-core' );

		return $result;
	}

	protected function prepare_item_for_database( $request ) {
		$prepared_comment = [];

		/*
		 * Allow the comment_content to be set via the 'content' or
		 * the 'content.raw' properties of the Request object.
		 */
		if ( isset( $request['message'] ) && is_string( $request['message'] ) ) {
			$prepared_comment['comment_content'] = $request['message'];
		} elseif ( isset( $request['message']['raw'] ) && is_string( $request['message']['raw'] ) ) {
			$prepared_comment['comment_content'] = $request['message']['raw'];
		}

		if ( isset( $request['post'] ) ) {
			$prepared_comment['comment_post_ID'] = (int) $request['post'];
		}

		if ( isset( $request['parent'] ) ) {
			$prepared_comment['comment_parent'] = $request['parent'];
		}

		if ( isset( $request['author'] ) ) {
			$user = new \WP_User( $request['author'] );

			if ( $user->exists() ) {
				$prepared_comment['user_id']              = $user->ID;
				$prepared_comment['comment_author']       = $user->display_name;
				$prepared_comment['comment_author_email'] = $user->user_email;
				$prepared_comment['comment_author_url']   = $user->user_url;
			} else {
				return new WP_Error( 'rest_comment_author_invalid', __( 'Invalid comment author ID.', 'lisfinity-core' ), array( 'status' => 400 ) );
			}
		}

		if ( isset( $request['author_name'] ) ) {
			$prepared_comment['comment_author'] = $request['author_name'];
		}

		if ( isset( $request['author_email'] ) ) {
			$prepared_comment['comment_author_email'] = $request['author_email'];
		}

		if ( isset( $request['author_url'] ) ) {
			$prepared_comment['comment_author_url'] = $request['author_url'];
		}

		if ( isset( $request['author_ip'] ) && current_user_can( 'moderate_comments' ) ) {
			$prepared_comment['comment_author_IP'] = $request['author_ip'];
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) && rest_is_ip_address( $_SERVER['REMOTE_ADDR'] ) ) {
			$prepared_comment['comment_author_IP'] = $_SERVER['REMOTE_ADDR'];
		} else {
			$prepared_comment['comment_author_IP'] = '127.0.0.1';
		}

		if ( ! empty( $request['date'] ) ) {
			$date_data = rest_get_date_with_gmt( $request['date'] );

			if ( ! empty( $date_data ) ) {
				list( $prepared_comment['comment_date'], $prepared_comment['comment_date_gmt'] ) = $date_data;
			}
		} elseif ( ! empty( $request['date_gmt'] ) ) {
			$date_data = rest_get_date_with_gmt( $request['date_gmt'], true );

			if ( ! empty( $date_data ) ) {
				list( $prepared_comment['comment_date'], $prepared_comment['comment_date_gmt'] ) = $date_data;
			}
		}

		return $prepared_comment;
	}

}
