<?php
/**
 * Model for our custom Elementor Elements Header functionality
 *
 * @author pebas
 * @package lisfinity-elements
 * @version 1.0.0
 */

namespace Lisfinity\Models\Elements;


class HeaderModel {

	public static $type = 'lisfinity_header';

	public function init() {
		$this->register_post_type();
		add_post_type_support( self::$type, 'elementor' );
	}

	public function admin_menu() {
		$post_type = $this::$type;
		$obj       = get_post_type_object( $post_type );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'lisfinity-elements',
			$page_title = $obj->labels->name,
			$menu_title = $obj->labels->menu_name,
			$capability = 'edit_posts',
			$menu_slug = "edit.php?post_type={$post_type}"
		);
	}

	protected function register_post_type() {
		$post_type = self::$type;
		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Header', 'lisfinity-core' );
		$plural   = __( 'Headers', 'lisfinity-core' );

		$args = [
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => false,
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => [ 'title', 'author' ],
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
		];

		register_post_type( $post_type, $args );
	}


}
