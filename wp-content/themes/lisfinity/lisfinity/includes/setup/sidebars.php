<?php
/**
 * Register sidebars.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @author pebas
 * @package themeSetup
 * @version 1.0.0
 */

function lisfinity_register_sidebars() {
	/**
	 * Array of default options.
	 *
	 * @var array
	 */
	$default_options = [
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h6 class="mb-0 text-xl font-semibold text-white">',
		'after_title'   => '</h6>',
	];

	/**
	 * Default sidebar.
	 */
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Default Sidebar', 'lisfinity' ),
				'id'   => 'default-sidebar',
			]
		)
	);

	/**
	 * Footer sidebars.
	 */
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Footer Sidebar | Col 1', 'lisfinity' ),
				'id'   => 'footer-sidebar-1',
			]
		)
	);
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Footer Sidebar | Col 1', 'lisfinity' ),
				'id'   => 'footer-sidebar-1',
			]
		)
	);
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Footer Sidebar | Col 2', 'lisfinity' ),
				'id'   => 'footer-sidebar-2',
			]
		)
	);
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Footer Sidebar | Col 3', 'lisfinity' ),
				'id'   => 'footer-sidebar-3',
			]
		)
	);
	register_sidebar(
		array_merge(
			$default_options,
			[
				'name' => esc_html__( 'Footer Sidebar | Col 4', 'lisfinity' ),
				'id'   => 'footer-sidebar-4',
			]
		)
	);

// WooCommerce Sidebar
	if ( lisfinity_is_woocommerce_active() ) {
		register_sidebar(
			array_merge(
				$default_options,
				[
					'name' => esc_html__( 'Shop Sidebar', 'lisfinity' ),
					'id'   => 'sidebar-shop',
				]
			)
		);
	}
}
