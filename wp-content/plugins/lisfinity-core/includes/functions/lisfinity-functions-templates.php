<?php
/**
 * Lisfinity core template functions
 *
 * Main template functions are based in this file.
 *
 * @author pebas
 * @package lisfinity-core
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_get_page_id' ) ) {
	/**
	 * Get the id of the requested page from the
	 * theme options.
	 * -----------------------------------------
	 *
	 * @param string $page_name - Name of the page that we
	 * wish to get the id for.
	 *
	 * @return int|null
	 */
	function lisfinity_get_page_id( $page_name ) {
		global $lisfinity_options;
		if ( isset( $lisfinity_options["_$page_name"] ) ) {
			$page_id = (int) $lisfinity_options["_$page_name"];
		} else {
			$page_id = 0;
		}

		if ( lisfinity_is_wpml() ) {
			$page_id = lisfinity_translate_object_id( $page_id, 'page' );
		}

		return $page_id ? $page_id : null;
	}
}

if ( ! function_exists( 'lisfinity_get_page_name' ) ) {
	/**
	 * Get the name of the requested page from the
	 * theme options.
	 * -------------------------------------------
	 *
	 * @param string $page_name - Name of the page that we
	 * wish to get the id for.
	 *
	 * @return array
	 */
	function lisfinity_get_page_name( $page_name ) {
		global $lisfinity_options;
		if ( isset( $lisfinity_options["_$page_name"] ) ) {
			$page_id = (int) $lisfinity_options["_$page_name"];
		} else {
			$page_id = 0;
		}

		$result['title'] = get_the_title( $page_id );
		$result['slug']  = sanitize_title( $result['title'] );

		return $result;
	}
}

if ( ! function_exists( 'lisfinity_is_page_template' ) ) {
	/**
	 * Check whether the current page is the page
	 * template we want.
	 * ------------------------------------------
	 *
	 * @param string $template - template that we wish to
	 * check on the current page.
	 *
	 * @param int $page_id
	 *
	 * @return bool
	 */
	function lisfinity_is_page_template( $template, $page_id = 0 ) {
		$page_id          = 0 != $page_id ? $page_id : get_queried_object_id();
		$template_page_id = lisfinity_get_page_id( $template );
		if ( lisfinity_is_wpml() ) {
			$page_id = lisfinity_translate_object_id( $page_id, 'page' );
		}

		if ( $template_page_id === $page_id ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_woocommerce_locate_template' ) ) {
	/**
	 * Intercept WooCommerce templating
	 * --------------------------------
	 *
	 * @param string $template - Template that we wish to check for.
	 * @param string $template_name - The name of the template that we wish to check for.
	 * @param string $template_path - The path of the template we're checking for.
	 *
	 * @return string
	 */
	function lisfinity_woocommerce_locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = LISFINITY_CORE_DIR . 'templates/woocommerce/';

		// Look within passed path within the theme - this is priority.
		$template = locate_template(

			[
				$template_path . $template_name,
				$template_name,
			]
		);

		// Modification: Get the template from this plugin, if it exists.
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template.
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found.
		return $template;
	}

	add_filter( 'woocommerce_locate_template', 'lisfinity_woocommerce_locate_template', 10, 3 );
}

if ( ! function_exists( 'lisfinity_check_dashboard_page' ) ) {
	/**
	 * Check if we're on the correct dashboard page or endpoint of a page
	 * ------------------------------------------------------------------
	 *
	 * @param array $pages - the array of page names we're looking for.
	 * @param string $endpoint - the endpoint of a given page we're looking for.
	 *
	 * @return bool
	 */
	function lisfinity_check_dashboard_page( $pages = [], $endpoint = '' ) {
		global $wp;
		$page_current_id    = get_queried_object_id();
		$page_my_account_id = lisfinity_get_page_id( 'page-account' );
		if ( lisfinity_is_wpml() ) {
			$page_my_account_id = lisfinity_translate_object_id( $page_my_account_id, 'page' );
		}

		// check if we're on correct dashboard page.
		if ( $page_current_id == $page_my_account_id && array_intersect( $pages, array_keys( $wp->query_vars ) ) ) {

			// check if we're looking for custom endpoint like 'https://lisfinity.com/my-account/ad/23'.
			if ( ! empty( $endpoint ) ) {
				foreach ( $pages as $page ) {
					if ( $wp->query_vars[ $page ] === $endpoint ) {
						return true;
					} else {
						return false;
					}
				}
			}

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_get_dashboard_page' ) ) {
	/**
	 * Load the correct dashboard page
	 * -------------------------------
	 *
	 * @param $page
	 * @param string $endpoint
	 *
	 * @return mixed|string
	 */
	function lisfinity_get_dashboard_page( $page, $endpoint = '' ) {
		$page_my_account_id = lisfinity_get_page_id( 'page-account' );
		if ( lisfinity_is_wpml() ) {
			$page_my_account_id = lisfinity_translate_object_id( $page_my_account_id, 'page' );
		}
		$permalink = get_permalink( $page_my_account_id );

		$page_link = "{$permalink}{$page}/{$endpoint}";

		return esc_url( $page_link );
	}
}

if ( ! function_exists( 'lisfinity_single_product_template_override' ) ) {
	/**
	 * Override default WooCommerce single product template when using
	 * custom product types
	 * ---------------------------------------------------------------
	 *
	 * @param $template
	 *
	 * @return string
	 */
	function lisfinity_single_product_template_override( $template ) {
		if ( is_singular( 'product' ) ) {
			$type = get_the_terms( get_the_ID(), 'product_type' );
			if ( in_array( $type[0]->slug, [ 'listing' ] ) ) {
				$template = lisfinity_get_template_part( 'single-product', 'woocommerce' );
			} else {
				return $template;
			}
		}

		return $template;
	}

	add_filter( 'template_include', 'lisfinity_single_product_template_override', 50, 1 );
}

if ( ! function_exists( 'lisfinity_get_page_template' ) ) {
	/**
	 * Load the correct page template if exists
	 * ----------------------------------------
	 *
	 * @param $template
	 *
	 * @return mixed|string
	 * @since 1.20.0 Included strict type checking to properly display 404 page
	 * with code from Aaron Weatherall - themeforest.net/user/aweatherall
	 *
	 */
	function lisfinity_get_page_template( $template ) {
		$pages           = [
			'page-home'            => lisfinity_get_page_id( 'page-home' ),
			'page-register'        => lisfinity_get_page_id( 'page-register' ),
			'page-login'           => lisfinity_get_page_id( 'page-login' ),
			'page-reset'           => lisfinity_get_page_id( 'page-reset' ),
			'page-search'          => lisfinity_get_page_id( 'page-search' ),
			'page-search-detailed' => lisfinity_get_page_id( 'page-search-detailed' ),
			'page-account'         => lisfinity_get_page_id( 'page-account' ),
			'page-vendors'         => lisfinity_get_page_id( 'page-vendors' ),
			'page-terms'           => lisfinity_get_page_id( 'page-terms' ),
			'page-privacy-policy'  => lisfinity_get_page_id( 'page-privacy-policy' ),
			'page-tips'            => lisfinity_get_page_id( 'page-tips' ),
			'page-contact'         => lisfinity_get_page_id( 'page-contact' ),
		];
		$current_page_id = get_queried_object_id();
		$page_ids        = array_values( $pages );
		//todo not needed after switching to Redux.io
		/*	if ( lisfinity_is_wpml() ) {
				$current_page_id = lisfinity_translate_object_id( $current_page_id, 'page', true );
			}*/

		if ( in_array( $current_page_id, $page_ids, true ) && ! is_account_page() ) {
			$position = array_search( $current_page_id, $page_ids );
			$pages    = array_keys( $pages );

			return apply_filters( 'lisfinity__get_page_template', lisfinity_get_template_part( $pages[ $position ], 'pages' ) );
		}

		return $template;
	}

	add_filter( 'template_include', 'lisfinity_get_page_template' );
}

if ( ! function_exists( 'lisfinity_redirect_to_login' ) ) {
	/**
	 * Redirect non logged user to a login page when trying
	 * to access account page
	 * ----------------------------------------------------
	 *
	 */
	function lisfinity_redirect_to_login() {
		if ( is_account_page() && ! is_user_logged_in() ) {
			wp_safe_redirect( get_permalink( lisfinity_get_page_id( 'page-login' ) ) );
		}
	}

	add_action( 'template_redirect', 'lisfinity_redirect_to_login' );
}

if ( ! function_exists( 'lisfinity_single_business_template_override' ) ) {
	/**
	 * Override default single page template for premium_profile CPT
	 * -------------------------------------------------------------
	 *
	 * @param $template
	 *
	 * @return string
	 */
	function lisfinity_single_business_template_override( $template ) {
		if ( is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name ) ) {
			$template = lisfinity_get_template_part( 'page-single-premium_profile', 'pages' );
		}

		return $template;
	}

	add_filter( 'single_template', 'lisfinity_single_business_template_override', 10, 1 );
}
if ( ! function_exists( 'lisfinity_is_category_page_template' ) ) {
	function lisfinity_is_category_page_template() {
		$model      = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$categories = $model->get_groups_slugs();
		$slug       = lisfinity_get_slug( 'slug-category', 'ad-category' );
		$type       = get_query_var( $slug );

		if ( ! empty( $type ) && in_array( $type, $categories ) ) {
			return true;
		}

		return false;
	}
}
if ( ! function_exists( 'lisfinity_load_category_page_template' ) ) {
	function lisfinity_load_category_page_template( $template ) {
		$model      = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$categories = $model->get_groups_slugs();
		$slug       = lisfinity_get_slug( 'slug-category', 'ad-category' );
		$type       = get_query_var( $slug );

		if ( ! empty( $type ) && in_array( $type, $categories ) ) {
			if ( lisfinity_is_enabled( lisfinity_get_option( 'custom-category-pages' ) ) ) {
				$template = lisfinity_get_template_part( 'page-archive', 'pages' );
			} else {
				$search_page = lisfinity_get_option( 'page-search' );
				if ( ! empty( $search_page ) ) {
					$template = lisfinity_get_template_part( 'page-search', 'pages' );
				}
			}
		}

		return $template;
	}

	add_filter( 'template_include', 'lisfinity_load_category_page_template' );
}

if ( ! function_exists( 'lisfinity_get_elementor_content' ) ) {
	function lisfinity_get_elementor_content( $content = '' ) {
		if ( class_exists( "\\Elementor\\Plugin" ) ) {
			$elementor         = \Elementor\Plugin::instance();
			$elementor_content = $elementor->frontend->get_builder_content( $content, true );

			return $elementor_content;
		}

		return '';
	}
}

function lisfinity_custom_archive_template( $template ) {
	$request = lisfinity_get_taxonomy_and_term();
	if ( $request && taxonomy_exists( $request[0] ) && term_exists( $request[1], $request[0] ) && is_product_taxonomy() ) {
		$settings = [
			'taxonomy' => $request[0],
			'term'     => $request[1],
		];
		if ( lisfinity_is_enabled( lisfinity_get_option( 'custom-category-pages' ) ) ) {
			$template = lisfinity_get_template_part( 'page-archive', 'pages', $settings );
		} else {
			$template = lisfinity_get_template_part( 'page-search', 'pages', $settings );
		}

		require $template;
		exit;
	}

	return $template;
}

add_filter( 'archive_template', 'lisfinity_custom_archive_template' );

function lisfinity_get_taxonomy_and_term() {
	global $wp;
	$request = $wp->request;
	if ( empty( $request ) ) {
		return false;
	}
	$request = explode( '/', $request );

	return $request;
}

if ( ! function_exists( 'lisfinity_add_custom_page_template_options' ) ) {
	function lisfinity_add_custom_page_template_options( $page_templates ) {
		$page_templates['lisfinity_archive'] = esc_html( 'Lisfinity Category Template' );

		return $page_templates;
	}

	add_filter( 'theme_page_templates', 'lisfinity_add_custom_page_template_options' );
}

function lisfinity_load_correct_page_template( $category ) {
	$args  = [
		'post_type'      => 'page',
		'posts_per_page' => - 1,
		'meta_query'     => [
			'relation' => 'OR',
			[
				'key'   => 'category',
				'value' => 'default',
			],
			[
				'key'   => 'category',
				'value' => $category,
			],
		],
		'fields'         => 'ids',
	];
	$pages = get_posts( $args );
	$id    = 0;
	if ( ! empty( $pages ) ) {
		foreach ( $pages as $page ) {
			$cat = carbon_get_post_meta( $page, 'category' );
			if ( $category === $cat ) {
				$id = $page;
				break;
			}

			$id = $page;
		}
	}

	return $id;
}

if ( ! function_exists( 'lisfinity_get_currency_switcher' ) ) {
	/**
	 * Load currency switcher
	 * ----------------------
	 *
	 */
	function lisfinity_get_currency_switcher() {
		if ( 'yes' === carbon_get_theme_option( 'multicurrency-enabled' ) ) {
			include lisfinity_get_template_part( 'partial-currency-switcher', 'partials' );
		}
	}

	add_action( 'wp_footer', 'lisfinity_get_currency_switcher' );
}
