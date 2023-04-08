<?php
/**
 * Declare theme functionality support.
 *
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
 *
 * @author pebas
 * @package themeSetup
 * @version 1.0.0
 */

use Lisfinity\Configuration as config;

/**
 * Support custom logo.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
add_theme_support( 'custom-logo' );

/**
 * Support automatic feed links.
 *
 * @link https://codex.wordpress.org/Automatic_Feed_Links
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Support post thumbnails.
 *
 * @link https://codex.wordpress.org/Post_Thumbnails
 */
add_theme_support( 'post-thumbnails' );

/**
 * Support document title tag.
 *
 * @link https://codex.wordpress.org/Title_Tag
 */
add_theme_support( 'title-tag' );

/**
 * Support HTML5 markup.
 *
 * @link https://codex.wordpress.org/Theme_Markup
 */
add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );

/**
 * Manually select Post Formats to be supported.
 *
 * @link http://codex.wordpress.org/Post_Formats
 */
// phpcs:ignore
add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ] );

/**
 * Support default editor block styles.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
add_theme_support( 'wp-block-styles' );

/**
 * Support wide alignment for editor blocks.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
add_theme_support( 'align-wide' );

/**
 * WooCommerce support
 *
 */
add_theme_support( 'woocommerce' );

/**
 * Support custom editor block color palette.
 * Don't forget to edit resources/styles/shared/variables.scss when you update these.
 * Uses Material Design colors.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
add_theme_support(
	'editor-color-palette',
	[
		[
			'name'  => esc_html__( 'Red', 'lisfinity' ),
			'slug'  => 'material-red',
			'color' => config::instance()->get( 'variables.color.material-red', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Pink', 'lisfinity' ),
			'slug'  => 'material-pink',
			'color' => config::instance()->get( 'variables.color.material-pink', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Purple', 'lisfinity' ),
			'slug'  => 'material-purple',
			'color' => config::instance()->get( 'variables.color.material-purple', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Deep Purple', 'lisfinity' ),
			'slug'  => 'material-deep-purple',
			'color' => config::instance()->get( 'variables.color.material-deep-purple', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Indigo', 'lisfinity' ),
			'slug'  => 'material-indigo',
			'color' => config::instance()->get( 'variables.color.material-indigo', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Blue', 'lisfinity' ),
			'slug'  => 'material-blue',
			'color' => config::instance()->get( 'variables.color.material-blue', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Light Blue', 'lisfinity' ),
			'slug'  => 'material-light-blue',
			'color' => config::instance()->get( 'variables.color.material-light-blue', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Cyan', 'lisfinity' ),
			'slug'  => 'material-cyan	',
			'color' => config::instance()->get( 'variables.color.material-cyan	', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Teal', 'lisfinity' ),
			'slug'  => 'material-teal',
			'color' => config::instance()->get( 'variables.color.material-teal', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Green', 'lisfinity' ),
			'slug'  => 'material-green',
			'color' => config::instance()->get( 'variables.color.material-green', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Light Green', 'lisfinity' ),
			'slug'  => 'material-light-green',
			'color' => config::instance()->get( 'variables.color.material-light-green', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Lime', 'lisfinity' ),
			'slug'  => 'material-lime',
			'color' => config::instance()->get( 'variables.color.material-lime', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Yellow', 'lisfinity' ),
			'slug'  => 'material-yellow',
			'color' => config::instance()->get( 'variables.color.material-yellow', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Amber', 'lisfinity' ),
			'slug'  => 'material-amber',
			'color' => config::instance()->get( 'variables.color.material-amber', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Orange', 'lisfinity' ),
			'slug'  => 'material-orange',
			'color' => config::instance()->get( 'variables.color.material-orange', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Deep Orange', 'lisfinity' ),
			'slug'  => 'material-deep-orange',
			'color' => config::instance()->get( 'variables.color.material-deep-orange', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Brown', 'lisfinity' ),
			'slug'  => 'material-brown',
			'color' => config::instance()->get( 'variables.color.material-brown', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Grey', 'lisfinity' ),
			'slug'  => 'material-grey',
			'color' => config::instance()->get( 'variables.color.material-grey', '#000000' ),
		],
		[
			'name'  => esc_html__( 'Blue Grey', 'lisfinity' ),
			'slug'  => 'material-blue-grey',
			'color' => config::instance()->get( 'variables.color.material-blue-grey', '#000000' ),
		],
	]
);

/**
 * Support color pallette enforcement.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
// phpcs:ignore
// add_theme_support( 'disable-custom-colors' );

/**
 * Support custom editor block font sizes.
 * Don't forget to edit resources/styles/shared/variables.scss when you update these.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
add_theme_support(
	'editor-font-sizes',
	[
		[
			'name'      => esc_html__( 'extra small', 'lisfinity' ),
			'shortName' => esc_html__( 'XS', 'lisfinity' ),
			'size'      => config::instance()->get( 'variables.font-size.xs', 12 ),
			'slug'      => 'extra-small',
		],
		[
			'name'      => esc_html__( 'small', 'lisfinity' ),
			'shortName' => esc_html__( 'S', 'lisfinity' ),
			'size'      => config::instance()->get( 'variables.font-size.s', 16 ),
			'slug'      => 'small',
		],
		[
			'name'      => esc_html__( 'regular', 'lisfinity' ),
			'shortName' => esc_html__( 'M', 'lisfinity' ),
			'size'      => config::instance()->get( 'variables.font-size.m', 20 ),
			'slug'      => 'regular',
		],
		[
			'name'      => esc_html__( 'large', 'lisfinity' ),
			'shortName' => esc_html__( 'L', 'lisfinity' ),
			'size'      => config::instance()->get( 'variables.font-size.l', 28 ),
			'slug'      => 'large',
		],
		[
			'name'      => esc_html__( 'extra large', 'lisfinity' ),
			'shortName' => esc_html__( 'XL', 'lisfinity' ),
			'size'      => config::instance()->get( 'variables.font-size.xl', 36 ),
			'slug'      => 'extra-large',
		],
	]
);
