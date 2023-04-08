<?php
/**
 * Model for our custom Safety Tips functionality
 *
 * @author pebas
 * @package lisfinity-tips
 * @version 1.0.0
 */

namespace Lisfinity\Models\Tips;


class TipsModel {

	public static $type = 'tips';

	/**
	 * Initialize methods
	 * ------------------
	 *
	 */
	public function init() {
		$this->register_post_type();
	}

	/**
	 * Register custom post type
	 * -------------------------
	 *
	 */
	protected function register_post_type() {
		$report_post_type = self::$type;
		if ( post_type_exists( $report_post_type ) ) {
			return;
		}

		$singular = __( 'Safety Tips', 'lisfinity-core' );
		$plural   = __( 'Safety Tips', 'lisfinity-core' );

		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-shield-alt',
			'query_var'          => true,
			'rewrite'            => false,
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'author' ),
			'labels'             => [
				'name'               => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'lisfinity-core' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'add_new'            => __( 'Add New', 'lisfinity-core' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'lisfinity-core' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'lisfinity-core' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'lisfinity-core' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'lisfinity-core' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'lisfinity-core' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'lisfinity-core' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'lisfinity-core' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'lisfinity-core' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'lisfinity-core' ), $plural ),
			],
		);

		register_post_type( $report_post_type, $args );
	}


}
