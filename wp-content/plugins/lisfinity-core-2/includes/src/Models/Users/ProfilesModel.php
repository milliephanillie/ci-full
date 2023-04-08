<?php


namespace Lisfinity\Models\Users;


class ProfilesModel {

	/**
	 * Name of the post type that is being created
	 * -------------------------------------------
	 *
	 * @var string
	 */
	public static $post_type_name = 'premium_profile';

	/**
	 * Initialize necessary class methods
	 * ----------------------------------
	 */
	public function init() {
		$this->register_post_type();
		$this->image_sizes();
	}


	/**
	 * Create image sizes for the post type
	 * ------------------------------------
	 */
	protected function image_sizes() {
		add_image_size( 'premium-profile-image', 99999, 48 );
	}

	/**
	 * Register report post type
	 * -------------------------
	 */
	protected function register_post_type() {
		$post_type = self::$post_type_name;
		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Business Profile', 'lisfinity-core' );
		$plural   = __( 'Business Profiles', 'lisfinity-core' );

		$args = [
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => [ 'title', 'thumbnail', 'author', 'editor' ],
			'menu_icon'          => 'dashicons-businessman',
			'menu_position'      => 28,
			'labels'             => [
				'name'               => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'lisfinity-core' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'add_new'            => sprintf( __( 'Add New %s', 'lisfinity-core' ), $singular ),
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
			'rewrite'            => [
				'slug'       => lisfinity_get_option( 'slug-business' ),
				'with_front' => false,
			]
		];

		register_post_type( $post_type, $args );
	}

	public function get_vendors_query( $additional_args = [] ) {
		$args = [
			'post_status'    => 'publish',
			'post_type'      => $this::$post_type_name,
			'posts_per_page' => 18,
			'cache_results'  => false,
		];
		$args = wp_parse_args( $additional_args, $args );

		return new \WP_Query( $args );
	}

	public function get_premium_profile( $user_id ) {
		// todo what if the user is an agent?.

		$premium_profile = new \WP_Query(
			[
				'post_status'    => 'publish',
				'post_type'      => $this::$post_type_name,
				'posts_per_page' => 1,
				'author'         => $user_id,
				'no_found_rows'  => true,
				'cache_results'  => false,
			]
		);

		$posts = $premium_profile->get_posts();

		return array_shift( $posts );
	}

	public function get_premium_profile_id( $user_id ) {
		// todo what if the user is an agent?.

		$premium_profile = new \WP_Query(
			[
				'post_status'    => 'publish',
				'post_type'      => $this::$post_type_name,
				'posts_per_page' => 1,
				'author'         => $user_id,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'cache_results'  => false,
			]
		);

		$posts = $premium_profile->get_posts();

		return array_shift( $posts );
	}

}
