<?php
/**
 * Additional theme functions
 *
 * @author pebas
 * @package functions/theme
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_body_class' ) ) {
	/**
	 * Additional body classes
	 * -----------------------
	 *
	 * @param array $classes
	 *
	 * @return mixed
	 */
	function lisfinity_body_class( $classes ) {
		$classes[] = 'font-sans';
		if ( ! lisfinity_is_core_active() ) {
			$classes[] = 'theme-lisfinity-blog';
		}

		return apply_filters( 'lisfinity__body_class', $classes );
	}

	add_filter( 'body_class', 'lisfinity_body_class' );
}

if ( ! function_exists( 'lisfinity_is_elementor' ) ) {
	/**
	 * Has page been created by the elementor plugin
	 * ---------------------------------------------
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	function lisfinity_is_elementor( $post_id = '' ) {
		$post_id = ! empty( $post_id ) ? $post_id : get_queried_object_id();

		if ( ! function_exists( 'elementor_load_plugin_textdomain' ) || ! isset( $post_id ) ) {
			return false;
		}

        $element = \Elementor\Plugin::$instance->documents->get( $post_id );

        if(! $element ) {
            return false;
        }

		return $element->is_built_with_elementor();
	}
}

if ( ! function_exists( 'lisfinity_required_plugins' ) ) {
	function lisfinity_required_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__( 'WooCommerce', 'lisfinity' ),
				'slug'               => 'woocommerce',
				'required'           => true,
				'version'            => '4.1.1',
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Elementor', 'lisfinity' ),
				'slug'               => 'elementor',
				'required'           => true,
				'version'            => '2.9.9',
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Redux Lite', 'lisfinity' ),
				'slug'               => 'redux-framework',
				'required'           => true,
				'version'            => '4.1.11',
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Lisfinity Core', 'lisfinity' ),
				'slug'               => 'lisfinity-core',
				'source'             => get_template_directory() . '/lib/lisfinity-core.zip',
				'required'           => true,
				'version'            => '1.3.2',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Envato Market', 'lisfinity' ),
				'slug'               => 'envato-market',
				'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
				'required'           => false,
				'version'            => '2.0.3',
				'force_activation'   => false,
				'force_deactivation' => false,
			),
		);

		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'id'           => 'lisfinity',
			'default_path' => '',
			'menu'         => 'lisfinity-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
			'strings'      => array(
				'page_title' => esc_html__( 'Install Required Plugins', 'lisfinity' ),
				'menu_title' => esc_html__( 'Install Required Plugins', 'lisfinity' ),
				// <snip>...</snip>
				'nag_type'   => 'updated',
				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);

		tgmpa( $plugins, $config );
	}
}

function lisfinity_merlin_import_files() {
	return [
		[
			'type'                     => 'classic',
			'import_file_name'         => 'Classic - All Categories',
			'categories'               => [],
			'local_import_file'        => get_parent_theme_file_path() . '/includes/demo/classic/content.xml',
			'local_import_widget_file' => get_parent_theme_file_path() . '/includes/demo/classic/widgets.json',
			'import_preview_image_url' => get_parent_theme_file_uri() . '/includes/demo/classic/classic.jpg',
			'import_notice'            => esc_html__( 'Classic demo import', 'lisfinity' ),
			'local_import_redux'       => [
				[
					'file_path'   => get_parent_theme_file_path() . '/includes/demo/classic/options.json',
					'option_name' => 'lisfinity-options',
				],
			],
			'preview_url'              => esc_url( 'https://classic.lisfinity.com' ),
		],
		[
			'type'                     => 'boats',
			'import_file_name'         => 'Boats - Only Boat Category',
			'categories'               => [],
			'local_import_file'        => get_parent_theme_file_path() . '/includes/demo/boats/content.xml',
			'local_import_widget_file' => get_parent_theme_file_path() . '/includes/demo/boats/widgets.json',
			'import_preview_image_url' => get_parent_theme_file_uri() . '/includes/demo/boats/boats.jpg',
			'import_notice'            => esc_html__( 'Boats demo import', 'lisfinity' ),
			'local_import_redux'       => [
				[
					'file_path'   => get_parent_theme_file_path() . '/includes/demo/boats/options.json',
					'option_name' => 'lisfinity-options',
				],
			],
			'preview_url'              => esc_url( 'https://boats.lisfinity.com' ),
		],
		[
			'type'                     => 'cars',
			'import_file_name'         => 'Cars - Only Car Category',
			'categories'               => [],
			'local_import_file'        => get_parent_theme_file_path() . '/includes/demo/cars/content.xml',
			'local_import_widget_file' => get_parent_theme_file_path() . '/includes/demo/cars/widgets.json',
			'import_preview_image_url' => get_parent_theme_file_uri() . '/includes/demo/cars/cars.jpg',
			'import_notice'            => esc_html__( 'Cars demo import', 'lisfinity' ),
			'local_import_redux'       => [
				[
					'file_path'   => get_parent_theme_file_path() . '/includes/demo/cars/options.json',
					'option_name' => 'lisfinity-options',
				],
			],
			'preview_url'              => esc_url( 'https://cars.lisfinity.com' ),
		],
		[
			'type'                     => 'realties',
			'import_file_name'         => 'Realties - Only Realty Category',
			'categories'               => [],
			'local_import_file'        => get_parent_theme_file_path() . '/includes/demo/realties/content.xml',
			'local_import_widget_file' => get_parent_theme_file_path() . '/includes/demo/realties/widgets.json',
			'import_preview_image_url' => get_parent_theme_file_uri() . '/includes/demo/realties/realties.jpg',
			'import_notice'            => esc_html__( 'Realties demo import', 'lisfinity' ),
			'local_import_redux'       => [
				[
					'file_path'   => get_parent_theme_file_path() . '/includes/demo/realties/options.json',
					'option_name' => 'lisfinity-options',
				],
			],
			'preview_url'              => esc_url( 'https://realties.lisfinity.com' ),
		],
		[
			'type'                     => 'pets',
			'import_file_name'         => 'Pets - Only Pet Category',
			'categories'               => [],
			'local_import_file'        => get_parent_theme_file_path() . '/includes/demo/pets/content.xml',
			'local_import_widget_file' => get_parent_theme_file_path() . '/includes/demo/pets/widgets.json',
			'import_preview_image_url' => get_parent_theme_file_uri() . '/includes/demo/pets/pets.jpg',
			'import_notice'            => esc_html__( 'Pets demo import', 'lisfinity' ),
			'local_import_redux'       => [
				[
					'file_path'   => get_parent_theme_file_path() . '/includes/demo/pets/options.json',
					'option_name' => 'lisfinity-options',
				],
			],
			'preview_url'              => esc_url( 'https://pets.lisfinity.com' ),
		],
	];
}

add_filter( 'merlin_import_files', 'lisfinity_merlin_import_files' );


function lisfinity_import_fields_demo( $fields = '' ) {
	if ( empty( $fields ) ) {
		return [ 'error' => true, 'message' => esc_html__( 'No fields to import', 'lisfinity' ) ];
	}
	$fields = json_decode( $fields, true );

	update_option( 'lisfinity_groups', $fields['groups'] );
	update_option( 'lisfinity_custom_fields', $fields['taxonomies'] );

	return true;
}


function lisfinity_get_all_taxonomy_options() {
	$all_fields = [];
	$options    = get_option( 'lisfinity_custom_fields' );
	if ( ! empty( $options ) ) {
		foreach ( $options as $group ) {
			foreach ( $group as $fields ) {
				if ( ! empty( $fields ) ) {
					$all_fields[] = $fields;
				}
			}
		}
	}

	return $all_fields;
}

function lisfinity_import_search_fields_demo( $fields = '' ) {
	if ( ! empty( $fields ) ) {
		$fields = json_decode( $fields, true );
	}

	update_option( 'lisfinity--search-builder-groups', $fields['groups'] );
	update_option( 'lisfinity--single-fields', $fields['single'] );
	update_option( 'lisfinity--search-builder-fields', $fields['fields'] );

	return $fields;
}

function lisfinity_import_options_demo( $fields ) {
	if ( empty( $fields ) ) {
		return false;
	}

	$fields = json_decode( $fields, true );

	foreach ( $fields as $key => $value ) {
		update_option( $key, $value, false );
	}

	return true;
}

function lisfinity_update_listing_expiring_dates() {
	global $wpdb;
	// remove all business profiles except for one.
	$businesses       = get_posts(
		[
			'post_type'      => 'premium_profile',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		]
	);
	$business_to_save = get_page_by_title( 'Demo Business', OBJECT, 'premium_profile' );;
	if ( ! empty( $businesses ) ) {
		foreach ( $businesses as $business ) {
			if ( $business_to_save->ID !== $business ) {
				wp_delete_post( $business, true );
			}
		}
	}
	if ( ! empty( $business_to_save ) ) {
		$wpdb->update( $wpdb->posts, [ 'post_author' => 1 ], [ 'ID' => $business_to_save->ID ] );
	}

	// set up the ads.
	$ad_ids = get_posts(
		[
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
			'fields'         => 'ids',
		]
	);

	if ( $ad_ids ) {
		foreach ( $ad_ids as $ad_id ) {
			carbon_set_post_meta( $ad_id, 'product-expiration', strtotime( '+30 days', (int) current_time( 'timestamp' ) ) );
			carbon_set_post_meta( $ad_id, 'product-listed', (int) current_time( 'timestamp' ) );
			carbon_set_post_meta( $ad_id, 'product-owner', 1 );
			carbon_set_post_meta( $ad_id, 'product-business', $business_to_save->ID );
		}
	}
}


function lisfinity_is_elements_template() {
	if ( ! lisfinity_is_core_active() ) {
		return false;
	}

	if ( is_singular( \Lisfinity\Models\Elements\HeaderModel::$type ) || is_singular( \Lisfinity\Models\Elements\FooterModel::$type ) || is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) {
		return true;
	}

	return false;
}
