<?php
/**
 * Model for our custom WooCommerce product type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

namespace Lisfinity\Models;

/**
 * Class PostModel
 * ------------------------------
 *
 * @package Lisfinity\Product
 */
class PostModel {

	public function get_posts_query( $args = [] ) {
		$default_args = [
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
		];

		$args = array_merge( $default_args, $args );

		$posts = new \WP_Query( $args );

		return $posts;
	}

	public function format_posts_select( $args = [] ) {
		$select = [];
		$posts  = $this->get_posts_query( $args );

		if ( $posts->have_posts() ) {
			foreach ( $posts->posts as $post ) {
				$select[ $post->ID ] = $post->post_title;
			}
		}

		return $select;
	}

}
