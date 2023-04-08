<?php

if ( ! function_exists( 'lisfinity_get_avatar_url' ) ) {
	/**
	 * Get custom user avatar url
	 * --------------------------
	 *
	 * @param string $user_id
	 *
	 * @return false|string
	 */
	function lisfinity_get_avatar_url( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$avatar_id = get_user_meta( $user_id, '_avatar', true );

		if ( empty( $avatar_id ) ) {
			$avatar_url = get_avatar_url( $user_id );
		} else {
			$avatar_url = wp_get_attachment_image_url( $avatar_id );
		}

		return apply_filters( 'lisfinity__get_avatar_url', $avatar_url );
	}
}

if ( ! function_exists( 'lisfinity_get_comment_user_id' ) ) {
	/**
	 * Get the user_id value from the wp_comments table
	 * ------------------------------------------------
	 *
	 * @param string $comment_id
	 *
	 * @return bool|int
	 */
	function lisfinity_get_comment_user_id( $comment_id = '' ) {
		global $wpdb;

		if ( empty( $comment_id ) ) {
			return false;
		}

		$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->comments} WHERE comment_ID=%d", $comment_id ) );

		return (int) $user_id;

	}
}
