<?php

use Carbon_Fields\Field\Field;
use Elementor\Plugin;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\REST_API\Taxonomies\TermRoute;

if ( ! function_exists( 'lisfinity_is_demo' ) ) {
	/**
	 * Check if the demo mode of the site has been activated
	 * -----------------------------------------------------
	 *
	 * @return bool
	 */
	function lisfinty_is_demo() {
		return '1' === lisfinity_get_option( 'site-mode' ) && ! current_user_can( 'administrator' );
	}
}


if ( ! function_exists( 'lisfinity_delete_trashed_posts' ) ) {
	/**
	 * Delete the posts from the trash and all attachments connected with them.
	 * ------------------------------------------------------------------------
	 *
	 * @param string $site_title
	 *
	 * @return string
	 */
	function lisfinity_delete_trashed_posts( $site_title = '' ) {
		if ( ! empty( $site_title ) && $site_title !== get_option( 'blogname' ) ) {
			return __( 'Site title is wrong', 'lisfinity-core' );
		}
		$args  = [
			'post_type'      => 'product',
			'post_status'    => 'trash',
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
		];
		$posts = get_posts( $args );
		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$thumb_id = get_post_thumbnail_id( $post );
				wp_delete_attachment( $thumb_id );
				$gallery = get_post_meta( $post, '_product_image_gallery' );
				$gallery = explode( ',', $gallery[0] );
				foreach ( $gallery as $image ) {
					wp_delete_attachment( $image );
				}
				wp_delete_post( $post, true );
			}
		}

		return __( 'Successfully deleted', 'lisfinity-core' );
	}
}

if ( ! function_exists( 'lisfinity_get_locale' ) ) {
	/**
	 * Get the correct site locale from the value set in
	 * the WordPress database
	 * -------------------------------------------------
	 *
	 * @return mixed
	 */
	function lisfinity_get_locale() {
		$locale = get_locale();

		$result['wp'] = $locale;
		$result['js'] = str_replace( '_', '-', $locale );

		return $result;
	}
}

if ( ! function_exists( 'lisfinity_timezone' ) ) {
	/**
	 * Get the correct timezone value that can be used in the
	 * javascript files
	 * ------------------------------------------------------
	 *
	 * @return int|string
	 */
	function lisfinity_timezone() {
		$timezone = wp_timezone_string();
		if ( false === strpos( '/', $timezone ) ) {
			return (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		}

		return $timezone;
	}
}

if ( ! function_exists( 'lisfinity_set_direction' ) ) {
	/**
	 * Set the direction of the theme content
	 * --------------------------------------
	 */
	function lisfinity_set_direction() {
		global $wp_locale, $wp_styles;
		$site_direction = lisfinity_get_option( 'site-direction' );

		$_user_id = get_current_user_id();
		if ( 'rtl' !== $site_direction ) {
			$site_direction = 'ltr';
		}

		if ( 'rtl' === $site_direction ) {
			update_user_meta( $_user_id, 'rtladminbar', $site_direction );
		} else {
			$site_direction = get_user_meta( $_user_id, 'rtladminbar', true );
			if ( $site_direction ) {
				$site_direction = isset( $wp_locale->text_direction ) ? $wp_locale->text_direction : 'ltr';
			}
		}

		$wp_locale->text_direction = $site_direction;
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			$wp_styles = new WP_Styles();
		}
		$wp_styles->text_direction = $site_direction;
	}
}

if ( ! function_exists( 'lisfinity_get_posts' ) ) {
	/**
	 * Get formatted array of the posts
	 * --------------------------------
	 *
	 * @param array $args
	 *
	 * @return array|bool|mixed
	 */
	function lisfinity_get_posts( $args = [] ) {
		$default_args = [
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => - 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];

		$args = wp_parse_args( $args, $default_args );


		// Get from cache to prevent same queries.
		$last_changed = wp_cache_get_last_changed( "lisfinity--{$args['post_type']}" );
		$key          = md5( serialize( $args ) );
		$cache_key    = "$key:$last_changed";
		$options      = wp_cache_get( $cache_key, "lisfinity--{$args['post_type']}" );

		if ( false !== $options ) {
			return $options;
		}

		$query = new WP_Query( $args );

		// Cache the query.
		wp_cache_set( $cache_key, $options, "lisfinity--{$args['post_type']}" );

		return $query->posts;
	}
}

if ( ! function_exists( 'lisfinity_format_products_select' ) ) {
	/**
	 * Format and prepare products to display in select field
	 * ------------------------------------------------------
	 *
	 * @param bool $first_empty
	 *
	 * @return array
	 */
	function lisfinity_format_products_select( $first_empty = false ) {
		$args    = [
			'post_type' => 'product',
			'tax_query' => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
		];
		$posts   = lisfinity_get_posts( $args );
		$options = [];
		if ( $first_empty ) {
			$options[''] = __( 'No Product Set', 'lisfinity-core' );
		}

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		}

		return apply_filters( 'lisfinity__format_products_select', $options );
	}
}

if ( ! function_exists( 'lisfinity_format_post_select' ) ) {
	/**
	 * Format and prepare posts to display in select field
	 * ---------------------------------------------------
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function lisfinity_format_post_select( $args = [] ) {
		$posts   = lisfinity_get_posts( $args );
		$options = [];

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'lisfinity_get_users' ) ) {
	/**
	 * Get formatted array of the users
	 * --------------------------------
	 *
	 * @param array $args
	 *
	 * @return array|bool|mixed
	 */
	function lisfinity_get_users( $args = [] ) {
		$cache_key = md5( serialize( $args ) );
		$cache     = wp_cache_get( $cache_key, 'lisfinity--users' );

		// Load from cache if possible.
		if ( $cache ) {
			return $cache;
		}

		$cache = [];
		$users = get_users( $args );

		foreach ( $users as $user ) {
			$cache[ $user->ID ] = $user;
		}

		wp_cache_set( $cache_key, $cache, 'lisfinity--users' );

		return $cache;
	}
}

if ( ! function_exists( 'lisfinity_format_users_select' ) ) {
	/**
	 * Format and prepare users to display in select field
	 * ---------------------------------------------------
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function lisfinity_format_users_select( $args = [] ) {
		$users   = lisfinity_get_users( $args );
		$options = [];

		foreach ( $users as $user ) {
			$options[ $user->ID ] = $user->display_name;
		}

		return $options;
	}
}

if ( ! function_exists( 'lisfinity_get_terms_by_taxonomy_select' ) ) {
	/**
	 * Get terms by the given taxonomies for use in a select field
	 * -----------------------------------------------------------
	 *
	 * @param string $group
	 *
	 * @return array
	 * todo maybe consider caching this at some point - not needed atm.
	 */
	function lisfinity_get_terms_by_taxonomy_select( $group = 'common' ) {
		global $wpdb;
		$tax_model  = new TaxonomiesAdminModel();
		$taxonomies = $tax_model->get_taxonomies_by_group( $group );
		$taxes      = implode( "','", array_keys( $taxonomies ) );
		$terms      = $wpdb->get_results(
			"SELECT * FROM {$wpdb->terms}
                   LEFT JOIN {$wpdb->term_taxonomy}
				   ON {$wpdb->terms}.term_id={$wpdb->term_taxonomy}.term_id
				   WHERE {$wpdb->term_taxonomy}.taxonomy IN ('{$taxes}')"
		);

		$terms_formatted = [];
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$terms_formatted[ $term->term_id ] = "{$term->name} ({$term->taxonomy})";
			}
		}

		return $terms_formatted;
	}
}

if ( ! function_exists( 'lisfinity_format_group_taxonomies_select' ) ) {
	/**
	 * Format taxonomy groups for use in a select field
	 * ------------------------------------------------
	 *
	 * @return mixed
	 */
	function lisfinity_format_group_taxonomies_select( $first_empty = false ) {
		$model  = new GroupsAdminModel();
		$groups = $model->get_groups_with_taxonomies();
		$select = [];
		if ( $first_empty ) {
			$select['default'] = __( 'Not Specified', 'lisfinity-core' );
		}
		if ( ! empty( $groups ) ) {
			$select = array_merge( $select, $model->format_options_for_select( true ) );
		} else {
			$select[] = array_merge( $select, lisfinity_get_product_taxonomies_select() );
		}

		return apply_filters( 'lisfinity__format_group_taxonomies_select', $select );
	}
}

if ( ! function_exists( 'lisfinity_get_product_taxonomies_select' ) ) {
	/**
	 * Format product post taxonomies so that they can be used
	 * in a select field
	 * -------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_product_taxonomies_select() {
		$taxonomies = get_object_taxonomies( 'product', '' );

		$taxonomy_select = [];
		foreach ( $taxonomies as $taxonomy => $taxonomy_object ) {
			if ( ! in_array( $taxonomy, [
				'product_cat',
				'product_type',
				'product_tag',
				'product_visibility',
				'product_shipping_class'
			] ) ) {
				$taxonomy_select[ $taxonomy ] = $taxonomy_object->label;
			}
		}

		return $taxonomy_select;
	}
}

/**
 * Lisfinity core plugin functions
 *
 * @author pebas
 * @package lisfinity-core
 * @version 1.0.0
 */

if ( ! function_exists( 'lisfinity_get_all_pages' ) ) {
	/**
	 * Return all available pages
	 * --------------------------
	 *
	 * @return mixed
	 */
	function lisfinity_get_all_pages() {
		$pages_args = [
			'post_type'      => 'page',
			'posts_per_page' => - 1,
		];
		$cache_key  = md5( wp_json_encode( $pages_args ) );
		// Get from cache to prevent same queries.
		$pages_array = wp_cache_get( $cache_key, 'lisfinity-get-all-pages-cache' );
		if ( false !== $pages_array ) {
			return $pages_array;
		}
		$pages       = new WP_Query( apply_filters( 'lisfinity__get_all_pages_args', $pages_args ) );
		$pages_array = [];

		if ( $pages ) {
			foreach ( $pages->posts as $page ) {
				$pages_array[ $page->ID ] = $page->post_title;
			}
		}
		// Cache request so we don't have to query it all the time.
		wp_cache_set( $cache_key, $pages_array, 'lisfinity-get-all-pages-cache' );

		return apply_filters( 'lisfinity__get_all_pages', $pages_array );
	}
}

if ( ! function_exists( 'lisfinity_get_template_part' ) ) {
	/**
	 * Get template part
	 * ----------------------
	 *
	 * @param string $template Name of the template.
	 * @param string $folder Path to the folder from 'templates' folder.
	 * @param array $args Array of params that should be accessible from the template.
	 *
	 * @return string
	 */
	function lisfinity_get_template_part( $template, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // phpcs:ignore
		}

		if ( empty( $folder ) ) {
			$dir = LISFINITY_CORE_DIR . "templates/{$template}.php";
		} else {
			$dir = LISFINITY_CORE_DIR . "templates/{$folder}/{$template}.php";
		}

		return apply_filters( 'lisfinity__get_template_part', $dir );
	}
}

if ( ! function_exists( 'lisfinity_get_ip_address' ) ) {
	/**
	 * Get the ip address of the user.
	 * -------------------------------
	 * Function has been taken from WooCommerce
	 *
	 * @return string
	 * @see woocommerce/includes/class-wc-geolocation.php
	 */
	function lisfinity_get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) { // WPCS: input var ok, CSRF ok.
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );  // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: input var ok, CSRF ok.
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address(
				trim(
					current(
						preg_split(
							'/,/',
							sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
						)
					)
				)
			); // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // @codingStandardsIgnoreLine
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); // @codingStandardsIgnoreLine
		}

		return '';
	}
}

if ( ! function_exists( 'lisfinity_get_var' ) ) {
	/**
	 * Check the existence of the give var
	 * -----------------------------------
	 *
	 * @param string $var - variable that should be
	 * checked.
	 * @param bool $return_var - should variable be returned
	 *         or just a boolean.
	 *
	 * @return bool
	 */
	function lisfinity_get_var( $var, $return_var = false ) {
		if ( isset( $var ) && ! empty( $var ) ) {
			return $return_var ? $var : true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 *
	 * @return string|array
	 */
	function lisfinity_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'lisfinity_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

// @codingStandardsIgnoreStart
if ( ! function_exists( 'lisfinity_get_raw_referer' ) ) {
	/**
	 * Ger raw Referer
	 * ---------------
	 *
	 * @return array|bool|false|string
	 */
	function lisfinity_get_raw_referer() {
		if ( function_exists( 'wp_get_raw_referer' ) ) {
			return wp_get_raw_referer(); // phpcs: ignore.
		}
		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) { // phpcs: ignore.
			return wp_unslash( $_REQUEST['_wp_http_referer'] ); // phpcs: ignore.
		} elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) { // phpcs: ignore.
			return wp_unslash( $_SERVER['HTTP_REFERER'] ); // phpcs: ignore.
		}

		return false;
	}
}
// @codingStandardsIgnoreEnd

if ( ! function_exists( 'lisfinity_generate_pin' ) ) {
	/**
	 * Generate pin code from a given amount of digits
	 * -----------------------------------------------
	 *
	 * @param int $digits Generate random pin code.
	 *
	 * @return string
	 */
	function lisfinity_generate_pin( $digits = 4 ) {
		$i   = 0;
		$pin = '';
		while ( $i < $digits ) {
			$pin .= wp_rand( 0, 9 );
			$i ++;
		}

		return $pin;
	}
}

if ( ! function_exists( 'lisfinity_mime_types' ) ) {
	/**
	 * Allow custom mime types when using wp media uploader from wp-admin area
	 * -----------------------------------------------------------------------
	 *
	 * @param $mimes
	 *
	 * @return mixed|void
	 */
	function lisfinity_mime_types( $mimes ) {
		if ( ! is_admin() ) {
			return $mimes;
		}

		$mimes['svg'] = 'image/svg+xml';

		return apply_filters( 'lisfinity__mime_types', $mimes );
	}

	add_filter( 'upload_mimes', 'lisfinity_mime_types' );
}

if ( ! function_exists( 'lisfinity_editor_init' ) ) {
	/**
	 * Display the editor.
	 *
	 * Instead of enqueueing all required scripts and stylesheets and setting up TinyMCE,
	 * wp_editor() automatically enqueues and sets up everything.
	 */
	function lisfinity_editor_init() {
		// do not load below code if wp_editor is not required.
		// todo make sure that we change param to dynamic once we create permalink editor.
		if ( ! is_account_page() ) {
			return false;
		}
		?>
		<div style="display:none;">
			<?php
			$settings = [
				'tinymce'       => [
					'resize'           => true,
					'wp_autoresize_on' => true,
				],
				'teeny'         => true,
				'media_buttons' => false,
			];

			add_filter( 'user_can_richedit', '__return_true' );
			wp_editor( '', 'lisfinity_settings', apply_filters( 'lisfinity__editor_init_settings', $settings ) );
			remove_filter( 'user_can_richedit', '__return_true' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'lisfinity_format_groups_for_theme_options' ) ) {
	/**
	 * Format taxonomy groups based on a product type so we can list them
	 * in the theme options
	 * ------------------------------------------------------------------
	 *
	 * @param string $name_prefix
	 * @param array $group_fields
	 * @param array $conditional
	 *
	 * @return array
	 */
	function lisfinity_format_groups_for_theme_options( $name_prefix = '', $group_fields = [], $conditional = [] ) {
		$taxonomies_model = new TaxonomiesAdminModel();
		$groups_model     = new GroupsAdminModel();
		$options          = $groups_model->get_options();
		$groups           = $groups_model->get_groups_slugs();
		if ( ! is_array( $groups ) || ! is_array( $options ) ) {
			return [];
		}
		if ( empty( $options ) ) {
			$groups[]  = 'common';
			$options[] = [
				'single_name' => 'Common',
				'plural_name' => 'Commons',
				'slug'        => 'common',
			];
		}
		$slugs = array_column( $options, 'slug' );

		foreach ( $groups as $group ) {
			$group_key = ! empty( $groups_model->get_options() ) ? array_search( $group, $slugs ) : 0;
			if ( ! empty( $conditional ) ) {
				if ( ! empty( $options[ $group_key ] ) ) {
					$group_fields[] =
						Field::make( 'multiselect', "${name_prefix}-taxonomy--{$group}", sprintf( __( 'Choose %s Taxonomy to display', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ) )
							 ->set_options( call_user_func_array( [
								 $taxonomies_model,
								 'get_taxonomies_by_group'
							 ], [ $group, false ] ) )
							 ->set_conditional_logic( [
								 $conditional
							 ] )
							 ->set_help_text( sprintf( __( 'Choose ad info which will be compared per category: <strong>%s</strong>.', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ) );
				}
			} else {
				if ( ! empty( $options[ $group_key ] ) ) {
					$group_fields[] =
						Field::make( 'multiselect', "${name_prefix}-taxonomy--{$group}", sprintf( __( 'Choose %s Taxonomy to display', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ) )
							 ->set_options( call_user_func_array( [
								 $taxonomies_model,
								 'get_taxonomies_by_group'
							 ], [ $group, false ] ) )
							 ->set_help_text( sprintf( __( 'Choose ad info which will be compared per category: <strong>%s</strong>.', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ) );
				}
			}
		}

		return apply_filters( 'lisfinity__format_groups_for_theme_options', $group_fields );
	}
}

if ( ! function_exists( 'lisfinity_format_product_categories_select' ) ) {
	/**
	 * Format product categories so that they can be used in a select field
	 * --------------------------------------------------------------------
	 *
	 * @param string $default
	 *
	 * @return mixed|void
	 */
	function lisfinity_format_product_categories_select( $default = '' ) {
		$categories_model = new GroupsAdminModel();
		$groups           = $categories_model->get_options();
		$select           = empty( $default ) ? [ 'all' => __( 'All Categories', 'lisfinity-core' ) ] : $default;

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$select[ $group['slug'] ] = $group['single_name'];
			}
		}

		return apply_filters( 'lisfinity__format_product_categories_select', $select );
	}
}

if ( ! function_exists( 'lisfinity_get_organized_groups_with_taxonomies' ) ) {
	/**
	 * Get organized in array groups with taxonomy slugs
	 * -------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_organized_groups_with_taxonomies() {
		$model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();

		return apply_filters( 'lisfinity__get_organized_groups_with_taxonomies', $model->get_groups_with_taxonomies_slugs() );
	}
}

if ( ! function_exists( 'lisfinity_days_of_the_week' ) ) {
	/**
	 * Get formatted days of the week for the default WordPress language
	 * -----------------------------------------------------------------
	 *
	 * @param boolean $full_name
	 * @param boolean $english - return in english
	 *
	 * @return array
	 */
	function lisfinity_days_of_the_week( $full_name = false, $english = true ) {
		global $wp_locale;
		$start_of_week  = get_option( 'start_of_week' );
		$days_localized = $wp_locale->weekday;
		if ( $english ) {
			$days = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];
		} else {
			$days = $days_localized;
		}
		$new_days   = array_merge( array_slice( $days, $start_of_week ), array_slice( $days, 0, $start_of_week ) );
		$days_array = array();
		foreach ( $new_days as $index => $weekday ) {
			if ( ! $full_name ) {
				$days_array[ mb_strtolower( $weekday ) ] = esc_html( mb_substr( $days_localized[ $index ], 0, 3 ) );
			} else {
				$days_array[ mb_strtolower( $weekday ) ] = esc_html( $days_localized[ $index ] );
			}
		};

		return apply_filters( 'lisfinity__days_of_the_week', $days_array );
	}
}

if ( ! function_exists( 'lisfinity_get_first_day_of_the_week' ) ) {
	/**
	 * Get first day of the week in human readable way
	 * -----------------------------------------------
	 *
	 * @param boolean $english - return in english
	 *
	 * @return string
	 */
	function lisfinity_get_first_day_of_the_week( $english = false ) {
		global $wp_locale;
		$start_of_week = get_option( 'start_of_week' );
		if ( $english ) {
			$days = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];
		} else {
			$days = $wp_locale->weekday;
		}

		return strtolower( $days[ $start_of_week ] );
	}
}

if ( ! function_exists( 'lisfinity_get_current_weekday' ) ) {
	/**
	 * Get current weekday
	 * -------------------
	 * @return int
	 */
	//todo to consider what to do when the start of the week is different than Monday.
	function lisfinity_get_current_weekday() {
		return (int) date( 'N', current_time( 'timestamp' ) );
	}
}

if ( ! function_exists( 'lisfinity_days_of_the_week_normalize' ) ) {
	/**
	 * Get default weekdays by the start of the week set in
	 * the WordPress options
	 * ----------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_days_of_the_week_normalize() {
		$days          = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];
		$start_of_week = get_option( 'start_of_week' );
		$new_days      = array_merge( array_slice( $days, $start_of_week ), array_slice( $days, 0, $start_of_week ) );

		return apply_filters( 'lisfinity__days_of_the_week_normalize', $new_days );
	}
}

if ( ! function_exists( 'lisfinity_tiny_rich_text' ) ) {
	/**
	 * Disable media upload button for WYSIWYG editor on a given
	 * ---------------------------------------------------------
	 *
	 * @param $html
	 * @param $field_name
	 *
	 * @return mixed|void
	 */
	function lisfinity_tiny_rich_text( $html, $field_name ) {
		$field_names = apply_filters( 'lisfinity_tiny_rich_fields', [ 'report-reason' ] );
		if ( in_array( $field_name, $field_names ) ) {
			return apply_filters( 'lisfinity__tiny_rich_text', '' );
		}

		return $html;
	}

	add_filter( 'crb_media_buttons_html', 'lisfinity_tiny_rich_text', 10, 2 );
}

if ( ! function_exists( 'lisfinity_report_reasons' ) ) {
	/**
	 * List of report reasons a user can choose from
	 * ---------------------------------------------
	 *
	 * @return mixed|void
	 */
	function lisfinity_report_reasons() {
		$options = carbon_get_theme_option( 'report-reasons' );
		$reasons = [];

		if ( empty( $options ) ) {
			$reasons = [
				'general' => __( 'General', 'lisfinity-core' ),
			];
		} else {
			foreach ( $options as $option ) {
				$reasons[ sanitize_title( $option['report-reason'] ) ] = $option['report-reason'];
			}
		}

		return apply_filters( 'lisfinity__report_reasons', $reasons );
	}
}

if ( ! function_exists( 'lisfinity_ordinal_suffixes' ) ) {
	/**
	 * Add the ordinal suffixes
	 * ------------------------
	 *
	 * @param $number
	 *
	 * @return string
	 */
	function lisfinity_ordinal_suffixes( $number ) {
		$ends = [
			__( 'th', 'lisfinity-core' ),
			__( 'st', 'lisfinity-core' ),
			__( 'nd', 'lisfinity-core' ),
			__( 'rd', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' ),
			__( 'th', 'lisfinity-core' )
		];
		if ( ( ( $number % 100 ) >= 11 ) && ( ( $number % 100 ) <= 13 ) ) {
			return $number . __( 'th', 'lisfinity-core' );
		} else {
			return $number . $ends[ $number % 10 ];
		}
	}
}

if ( ! function_exists( 'lisfinity_notification_master_types' ) ) {
	/**
	 * List of notification master types
	 * ---------------------------------
	 *
	 * @return array
	 */
	function lisfinity_notification_master_types() {
		$types = [
			'default',
			'system',
			'global',
		];

		return apply_filters( 'lisfinity__notification_master_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_get_human_notification_master_type' ) ) {
	/**
	 * Get humanized version of the notification master type
	 * -----------------------------------------------------
	 *
	 * @param $notification_index
	 *
	 * @return mixed
	 */
	function lisfinity_get_human_notification_master_type( $notification_index ) {
		$types = lisfinity_notification_master_types();

		return $types[ $notification_index - 1 ];
	}
}

if ( ! function_exists( 'lisfinity_notification_types' ) ) {
	/**
	 * List of notification types
	 * --------------------------
	 *
	 * @return array
	 */
	function lisfinity_notification_types() {
		$types = [
			'message',
			'bid',
			'report',
			'bookmark',
			'like',
			'subscription',
			'system',
			'global',
		];

		return apply_filters( 'lisfinity__notification_types', $types );
	}
}

if ( ! function_exists( 'lisfinity_notification_types_titles' ) ) {
	/**
	 * List of notification types titles
	 * ---------------------------------
	 *
	 * @return array
	 */
	function lisfinity_notification_types_titles() {
		$types = [
			__( 'Message', 'lisfinity-core' ),
			__( 'Bids', 'lisfinity-core' ),
			__( 'Report', 'lisfinity-core' ),
			__( 'Bookmark', 'lisfinity-core' ),
			__( 'Like', 'lisfinity-core' ),
			__( 'Subscription', 'lisfinity-core' ),
			__( 'Bid Notice', 'lisfinity-core' ),
			__( 'System', 'lisfinity-core' ),
			__( 'Global', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__notification_types_titles', $types );
	}
}

if ( ! function_exists( 'lisfinity_get_human_notification_type' ) ) {
	/**
	 * Get humanized version of the notification type
	 * ----------------------------------------------
	 *
	 * @param $notification_index
	 *
	 * @return mixed
	 */
	function lisfinity_get_human_notification_type( $notification_index ) {
		$types = lisfinity_notification_types();

		return $types[ $notification_index - 1 ];
	}
}

if ( ! function_exists( 'lisfinity_get_human_notification_type_title' ) ) {
	/**
	 * Get humanized version of the notification type title
	 * ----------------------------------------------------
	 *
	 * @param $notification_index
	 *
	 * @return mixed
	 */
	function lisfinity_get_human_notification_type_title( $notification_index ) {
		$types = lisfinity_notification_types_titles();

		return $types[ $notification_index - 1 ];
	}
}

if ( ! function_exists( 'lisfinity_get_last_days' ) ) {
	/**
	 * Get the last given number of days
	 * ---------------------------------
	 *
	 * @param $days
	 * @param string $format
	 *
	 * @return array
	 */
	function lisfinity_get_last_days( $days, $format = 'Y-m-d' ) {
		$month      = date( 'm' );
		$day        = date( 'd' );
		$year       = date( 'Y' );
		$days_array = [];
		for ( $i = 0; $i <= $days - 1; $i ++ ) {
			$days_array[] = date( $format, mktime( 0, 0, 0, $month, ( $day - $i ), $year ) );
		}

		return array_reverse( $days_array );
	}
}

if ( ! function_exists( 'lisfinity_get_month_days' ) ) {
	/**
	 * Get days in a month
	 * -------------------
	 *
	 * @param $month
	 * @param $year
	 * @param string $format
	 *
	 * @return array
	 */
	function lisfinity_get_month_days( $month, $year, $format = 'Y-m-d' ) {
		$num         = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$dates_month = array();

		for ( $i = 1; $i <= $num; $i ++ ) {
			$mktime            = mktime( 0, 0, 0, $month, $i, $year );
			$date              = date( $format, $mktime );
			$date              = strtotime( $date );
			$dates_month[ $i ] = $date;
		}

		return $dates_month;
	}
}

if ( ! function_exists( 'lisfinity_page_body_class' ) ) {
	/**
	 * Get additional body classes for the given pages
	 * -----------------------------------------------
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function lisfinity_page_body_class( $classes = [] ) {
		if ( is_account_page() ) {
			$classes[] = 'lisfinity-page-account';
		}
		if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
			$classes[] = 'logged-in--admin';
		}
		$type = get_the_terms( get_the_ID(), 'product_type' );
		if ( is_singular( 'product' ) && in_array( $type[0]->slug, [ 'listing' ] ) ) {
			$classes[] = 'lisfinity--single-product';
		}
		if ( is_singular( 'premium_profile' ) ) {
			$page_id = lisfinity_get_option( 'page-business' );
			if ( get_queried_object_id() !== $page_id ) {
				$page_id = lisfinity_get_option( 'page-business-premium' );
			}
			$classes[] = "elementor-page-{$page_id}";
		}

		return $classes;
	}
}

if ( ! function_exists( 'lisfinity_get_all_months' ) ) {
	/**
	 * Get all months
	 * --------------
	 *
	 * @return array
	 */
	function lisfinity_get_all_months() {
		$months = [
			'january'   => __( 'January', 'lisfinity-core' ),
			'February'  => __( 'February', 'lisfinity-core' ),
			'March'     => __( 'March', 'lisfinity-core' ),
			'April'     => __( 'April', 'lisfinity-core' ),
			'May'       => __( 'May', 'lisfinity-core' ),
			'June'      => __( 'June', 'lisfinity-core' ),
			'July'      => __( 'July', 'lisfinity-core' ),
			'August'    => __( 'August', 'lisfinity-core' ),
			'September' => __( 'September', 'lisfinity-core' ),
			'October'   => __( 'October', 'lisfinity-core' ),
			'November'  => __( 'November', 'lisfinity-core' ),
			'December'  => __( 'December', 'lisfinity-core' ),
		];

		return $months;
	}
}

if ( ! function_exists( 'lisfinity_custom_rewrite_rules' ) ) {
	/**
	 * Custom rewrite rules that are adding new endpoints
	 * to various places on the site where we need them
	 * --------------------------------------------------
	 */
	function lisfinity_custom_rewrite_rules() {
		add_rewrite_rule( 'premium_profile/(.+?)/(.+?)(/page/([0-9]+))?/?$', 'index.php?post_type=premium_profile&name=$matches[1]&sub=$matches[2]&paged=$matches[3]', 'top' );
	}
}

if ( ! function_exists( 'lisfinity_cache_search_terms' ) ) {
	/**
	 * Get search terms from the database
	 * ----------------------------------
	 *
	 * @param bool $update
	 *
	 * @return array|mixed
	 */
	function lisfinity_cache_search_terms( $update = false ) {
		$cache = get_transient( 'lisfinity--terms-for-search' );
		if ( ! $update && ! empty( $cache ) ) {
			//return $cache;
		}

		$model      = new TermRoute();
		$taxonomies = $model->get_product_taxonomies();
		$args       = [
			'hide_empty' => false,
		];
		foreach ( $taxonomies as $taxonomy ) {
			if ( taxonomy_exists( $taxonomy ) ) {
				$args['taxonomy'][] = $taxonomy;
			}
		}
		$terms = get_terms( $args );

		if ( empty( $terms ) ) {
			return [];
		}

		// filters terms to exclude not custom related taxonomies.
		$terms_filtered = $model->filter_terms_for_search( $terms );

		set_transient( 'lisfinity--terms-for-search', $terms_filtered, 365 * DAY_IN_SECONDS );

		return $terms_filtered;
	}
}

if ( ! function_exists( 'lisfinity_reset_terms_cache' ) ) {
	function lisfinity_reset_terms_cache() {
		return lisfinity_cache_search_terms( true );
	}

	add_action( 'lisfinity__term_updated', 'lisfinity_reset_terms_cache' );
}

function lisfinity_cache_terms( $args, $update = false, $suffix = '' ) {
	$cache = get_transient( "lisfinity--get-terms${suffix}" );
	if ( ! $update && ! empty( $cache ) ) {
		//return $cache;
	}

	$terms = get_terms( $args );

	if ( empty( $terms ) ) {
		return [];
	}

	// filters terms to exclude not custom related taxonomies.
	$model          = new TermRoute();
	$terms_filtered = $model->filter_terms( $terms );

	set_transient( "lisfinity--get-terms${suffix}", $terms_filtered, 365 * DAY_IN_SECONDS );

	return $terms_filtered;
}

if ( ! function_exists( 'lisfinity_get_transient_version' ) ) {
	/**
	 * Get the specified transient version from the database or
	 * create a complete new one
	 * --------------------------------------------------------
	 *
	 * @param $group
	 * @param bool $refresh
	 *
	 * @return int|mixed
	 */
	function lisfinity_get_transient_version( $group, $refresh = false ) {
		$transient_name  = $group . '-transient-version';
		$transient_value = get_transient( $transient_name );

		if ( false === $transient_value || true === $refresh ) {
			lisfinity_delete_version_transients( $transient_value );
			set_transient( $transient_name, $transient_value = time() );
		}

		return $transient_value;
	}
}

if ( ! function_exists( 'lisfinity_delete_version_transients' ) ) {
	/**
	 * Delete the specified transients from the database
	 * -------------------------------------------------
	 *
	 * @param $version
	 */
	function lisfinity_delete_version_transients( $version ) {
		if ( ! wp_using_ext_object_cache() && ! empty( $version ) ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s;", '\_transient\_%' . $version ) );
		}
	}
}

if ( ! function_exists( 'lisfinity_deregister_wc_styles' ) ) {

	/**
	 * Deregister WooCommerce styles on pages where they aren't needed
	 * but still being applied like single product, dashboard etc.
	 * ---------------------------------------------------------------
	 *
	 */
	function lisfinity_deregister_wc_styles() {
		if ( is_singular( 'product' ) ) {
			$type = get_the_terms( get_the_ID(), 'product_type' );
			if ( in_array( $type[0]->slug, [ 'listing', 'payment_package', 'promotion' ] ) ) {
				add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
			}
		}
		if ( is_account_page() ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		}
	}

	add_action( 'template_redirect', 'lisfinity_deregister_wc_styles' );
}

if ( ! function_exists( 'lisfinity_get_js_translations' ) ) {
	/**
	 * Get all translatable strings used in js files
	 * ---------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_js_translations() {
		$js_strings = include LISFINITY_CORE_DIR . 'includes/helpers/js-translations.php';

		return apply_filters( 'lisfinity__get_js_translations', $js_strings );
	}
}

if ( ! function_exists( 'lisfinity_google_fonts' ) ) {
	/**
	 * Returns the list of google fonts
	 * --------------------------------
	 *
	 * @param bool $default_font
	 *
	 * @return array
	 */
	function lisfinity_google_fonts( $default_font = false ) {
		$google_fonts = include( LISFINITY_CORE_DIR . 'includes/helpers/google-fonts.php' );
		$font_list    = [];
		if ( $default_font ) {
			$font_list['custom'] = esc_html( '- Route 159 (Default Theme Font)' );
		}
		if ( isset( $google_fonts ) ) {
			foreach ( $google_fonts as $label => $name ) {
				$font_list[ $name ] = $label;
			}
		}


		return apply_filters( 'lisfinity__google_fonts', $font_list );
	}
}

if ( ! function_exists( 'lisfinity_country_codes' ) ) {
	/**
	 * Returns the list of country codes
	 * ---------------------------------
	 *
	 * @return array
	 */
	function lisfinity_country_codes() {
		$country_list = include( LISFINITY_CORE_DIR . 'includes/helpers/countries.php' );
		$countries    = [];
		foreach ( $country_list as $code => $country ) {
			$countries[ $code ] = $country;
		}

		return apply_filters( 'lisfinity__country_codes', $countries );
	}
}

if ( ! function_exists( 'lisfinity_check_color' ) ) {
	function lisfinity_check_color( $color, $submit_text ) {
		$color_uppercase = strtoupper( $color );
		if ( isset( $submit_text ) && "{$color}" !== $submit_text && "{$color_uppercase}" !== $submit_text && "#{$color}" !== $submit_text && "#{$color_uppercase}" !== $submit_text && "#{$color_uppercase}FF" !== $submit_text ) {
			return true;
		}

		return false;
	}
}

function lisfinity_footer_middle() {
	$email             = lisfinity_get_option( 'footer-email' );
	$phone             = lisfinity_get_option( 'footer-phone' );
	$socials_formatted = [];
	$socials           = lisfinity_get_option( 'footer-social-icons' );
	$elements          = [];

	if ( ! empty( $email ) ) {
		$elements['email'] = $email;
	}
	if ( ! empty( $phone ) ) {
		$elements['phone'] = $phone;
	}
	if ( ! empty( $socials ) ) {
		foreach ( $socials as $social ) {
			$url = lisfinity_get_option( "footer-social-{$social}" );
			if ( ! empty( $url ) ) {
				$socials_formatted[ $social ] = [
					'url'  => $url,
					'icon' => lisfinity_load_social_icon_svg( $social )
				];
			}
		}
		$elements['socials'] = $socials;
	}

	if ( ! empty( $elements ) ) {
		ob_start();
		?>
		<footer
			class="copyrights flex items-center justify-between py-30 px-10 bg-header text-grey-500">
			<div class="container flex flex-wrap items-center justify-between">
				<?php if ( ! empty( $email ) || ! empty( $phone ) ) : ?>
					<div class="px-col flex flex-wrap items-center">
						<?php if ( ! empty( $email ) ) : ?>
							<div class="flex-center mr-20">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
									 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;"
									 xml:space="preserve" class="relative top-1 w-24 h-24 fill-green-800">
								 	<path d="M93.7,22.5H6.3c-2.6,0-4.8,2.1-4.8,4.8v45.5c0,2.6,2.1,4.8,4.8,4.8h87.5c2.6,0,4.8-2.1,4.8-4.8V27.3
									C98.5,24.6,96.4,22.5,93.7,22.5z M50,48.7L13.7,28h72.6L50,48.7z M37.9,48.2L7,69.5v-39L37.9,48.2z M43.2,51.2l4.4,2.5
									c0.7,0.4,1.5,0.6,2.4,0.6c0.8,0,1.6-0.2,2.4-0.6l4.4-2.5L86.8,72H13.1L43.2,51.2z M62,48.2l31-17.7v39.1L62,48.2z"/>
								</svg>
								<a href="mailto:<?php echo rawurlencode( htmlspecialchars_decode( $email ) ); ?>"
								   class="ml-10 text-white">
									<?php echo $email; ?>
								</a>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $phone ) ) : ?>
							<div class="flex items-center w-full mt-10 sm:mt-0 sm:w-auto">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
									 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;"
									 xml:space="preserve" class="relative top-1 w-20 h-20 fill-green-800">
										<g>
											<path d="M44.8,0H19.2c-3.2,0-5.9,2.9-5.9,6.2v51.7c0,3.5,2.7,6.2,5.9,6.2h25.7c3.2,0,5.9-2.9,5.9-6.2V6.2C50.7,2.9,48,0,44.8,0z
		 								M45.4,58.1c0,0.5-0.3,0.8-0.5,0.8H19.2c-0.3,0-0.5-0.3-0.5-0.8V6.2c0-0.5,0.3-0.8,0.5-0.8h25.7c0.3,0,0.5,0.3,0.5,0.8V58.1z"/>
											<ellipse cx="32" cy="53.6" rx="2.7" ry="2.7"/>
											<path
												d="M34.7,10.7h-5.3c-1.6,0-2.7,1.1-2.7,2.7s1.1,2.7,2.7,2.7h5.3c1.6,0,2.7-1.1,2.7-2.7S36.3,10.7,34.7,10.7z"/>
										</g>
									</svg>
								<a href="<?php echo esc_url( "tel:$phone" ); ?>"
								   class="ml-10 text-white">
									<?php echo $phone; ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $socials_formatted ) ) : ?>
					<?php $social_text = lisfinity_get_option( 'footer-social-text' ); ?>
					<div class="flex px-col text-grey-800 mt-20 bg:mt-0">
						<?php if ( ! empty( $social_text ) ) : ?>
							<span class="mr-20 mt-6 whitespace-no-wrap"><?php echo esc_html( $social_text ); ?></span>
						<?php endif; ?>
						<ul class="socials flex flex-wrap -mt-10">
							<?php foreach ( $socials_formatted as $name => $social ) : ?>
								<li class="mt-10">
									<a
										href="<?php echo esc_url( $social['url'] ); ?>"
										target="_blank"
										rel="nofollow"
										class="flex-center mr-8 p-6 border border-text-grey-800 rounded-full text-grey-800 hover:text-white hover:border-white"
										title="<?php echo esc_attr( $name ); ?>"
									><?php echo lisfinity_kses_svg( $social['icon'] ); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</footer>
		<?php
		$text = ob_get_contents();
		ob_get_clean();
	}

	if ( ! empty( $text ) ) {
		echo $text;
	}
}

function lisfinity_footer_copyrights() {
	$copyrights   = lisfinity_get_option( 'footer-copyrights' );
	$page_terms   = lisfinity_get_option( 'page-terms' );
	$page_privacy = lisfinity_get_option( 'page-privacy-policy' );
	$text         = '';

	if ( ! empty( $copyrights ) ) {
		ob_start();
		?>
		<footer
			class="copyrights flex items-center justify-between py-60 px-10 bg-grey-1100 text-grey-500">
			<div class="container flex flex-wrap items-center justify-between -mt-10">
				<div class="px-col mt-10">
					<?php _e( $copyrights, 'lisfinity-core' ); ?>
				</div>
				<?php if ( ! empty( $page_terms ) || ! empty( $page_privacy ) ) : ?>
					<div class="px-col mt-10">
						<?php if ( ! empty( $page_terms ) ) : ?>
							<a href="<?php echo esc_url( get_permalink( $page_terms ) ); ?>"
							   class="hover:text-white"><?php echo esc_html( get_the_title( $page_terms ) ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $page_privacy ) ) : ?>
							<?php if ( ! empty( $page_terms ) ) : ?>
								<?php echo esc_html( '-' ); ?>
							<?php endif; ?>
							<a href="<?php echo esc_url( get_permalink( $page_privacy ) ); ?>"
							   class="hover:text-white"><?php echo esc_html( get_the_title( $page_privacy ) ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</footer>
		<?php
		$text = ob_get_contents();
		ob_end_clean();
	}

	echo wp_kses_post( $text );
}

if ( ! function_exists( 'lisfinity_get_youtube_id_from_src' ) ) {
	/**
	 * Get YouTube video id from the url
	 * ---------------------------------
	 *
	 * @param $src
	 *
	 * @return bool|mixed
	 */
	function lisfinity_get_youtube_id_from_src( $src ) {
		if ( empty( $src ) ) {
			return false;
		}
		$video_id = explode( "?v=", $src );

		return apply_filters( 'lisfinity__get_youtube_id_from_src', $video_id[1] );
	}
}

if ( ! function_exists( 'lisfinity_theme_colors' ) ) {
	/**
	 * Add theme colors and custom fonts
	 * ---------------------------------
	 */
	function lisfinity_theme_colors() {
		ob_start();
		require_once lisfinity_get_template_part( 'fonts', 'options', [] );
		require_once lisfinity_get_template_part( 'colors', 'options/colors', [] );
		require_once lisfinity_get_template_part( 'css', 'options', [] );
		$css = ob_get_clean();
		//lisfinity_dd( $css );
		echo '<style type="text/css">' . $css . '</style>';
	}

	add_action( 'wp_head', 'lisfinity_theme_colors' );
}

function lisfinity_google_analytics() {
	$analytics = lisfinity_get_option( 'code-analytics' );

	if ( empty( $analytics ) ) {
		return false;
	}

	echo $analytics;
}

if ( ! function_exists( 'lisfinity_additional_js' ) ) {
	function lisfinity_additional_js() {
		require_once lisfinity_get_template_part( 'js', 'options', [] );
	}
}

if ( ! function_exists( 'lisfinity_format_keyword_search_settings' ) ) {
	/**
	 * Format keyword search settings
	 * ------------------------------
	 *
	 * @return array
	 */
	function lisfinity_format_keyword_search_settings() {
		$settings = get_option( 'lisfinity--search-builder-fields' )['home']['keywordOptions'];

		$settings_formatted = [];
		if ( empty( $settings ) ) {
			$settings_formatted['titles'] = true;
		} else {
			foreach ( $settings as $key => $value ) {
				if ( $value === 'true' ) {
					$settings_formatted[ $key ] = true;
				} else if ( $value === 'false' ) {
					$settings_formatted[ $key ] = false;
				} else {
					$settings_formatted[ $key ] = $value;
				}
			}
		}

		return $settings_formatted;
	}
}

if ( ! function_exists( 'lisfinity_is_elementor' ) ) {
	/**
	 * Check if the page is built with elementor
	 * -----------------------------------------
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	function lisfinity_is_elementor( $post_id = '' ) {
		$post_id = ! empty( $post_id ) ? $post_id : get_queried_object_id();

		return Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
	}
}

function lisfinity_format_upload_size_for_settings() {
	$size = str_replace( [ 'b', 'kb', 'm', 'gb' ], [
		'',
		'',
		'',
		''
	], strtolower( ini_get( 'upload_max_filesize' ) ) );

	return $size;
}

if ( ! function_exists( 'lisfinity_get_available_social_networks' ) ) {
	/**
	 * Get the list of available social networks
	 * -----------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_available_social_networks() {
		$social = [
			'facebook'  => __( 'Facebook', 'lisfinity-core' ),
			'twitter'   => __( 'Twitter', 'lisfinity-core' ),
			'instagram' => __( 'Instagram', 'lisfinity-core' ),
			'dribbble'  => __( 'Dribbble', 'lisfinity-core' ),
			'linkedin'  => __( 'Linkedin', 'lisfinity-core' ),
			'youtube'   => __( 'YouTube', 'lisfinity-core' ),
			'reddit'    => __( 'Reddit', 'lisfinity-core' ),
			'pinterest' => __( 'Pinterest', 'lisfinity-core' ),
			'medium'    => __( 'Medium', 'lisfinity-core' ),
			'vk'        => __( 'vKontakte', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__get_available_social_networks', $social );
	}
}

if ( ! function_exists( 'lisfinity_format_terms_and_policy_label' ) ) {
	/**
	 * Format html for terms & conditions and privacy policy links
	 * -----------------------------------------------------------
	 *
	 * @return string
	 */
	function lisfinity_format_terms_and_policy_label() {
		$page_terms          = lisfinity_get_page_id( 'page-terms' );
		$page_privacy_policy = lisfinity_get_page_id( 'page-privacy-policy' );
		$label               = false;
		if ( ! empty( $page_terms ) ) {
			$label = sprintf( __( 'I agree to %s', 'lisfinity-core' ), '<a href="' . esc_url( get_permalink( $page_terms ) ) . '" class="font-semibold text-blue-800 hover:underline" target="_blank">' . esc_html( get_the_title( $page_terms ) ) . '</a>' );
		}
		if ( empty( $page_terms ) && ! empty( $page_privacy_policy ) ) {
			$label = sprintf( __( 'I agree to %s', 'lisfinity-core' ), '<a href="' . esc_url( get_permalink( $page_privacy_policy ) ) . '" class="font-semibold text-blue-800 hover:underline" target="_blank">' . esc_html( get_the_title( $page_privacy_policy ) ) . '</a>' );
		}
		if ( ! empty( $page_terms ) && ! empty( $page_privacy_policy ) ) {
			$label .= sprintf( __( ' and %s', 'lisfinity-core' ), '<a href="' . esc_url( get_permalink( $page_privacy_policy ) ) . '" class="font-semibold text-blue-800 hover:underline" target="_blank">' . esc_html( get_the_title( $page_privacy_policy ) ) . '</a>' );
		}

		return apply_filters( 'lisfinity__format_terms_and_policy_label', $label );
	}
}

// Include admin.css fixed to the elementor editor.
add_action( 'elementor/editor/after_enqueue_styles', function () {
	wp_enqueue_style( 'hello-elementor-child-style', LISFINITY_CORE_URL . 'dist/statics/styles/admin.css', null, '', 'all' );
} );

add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin()         // Only target front end,
		 && $q->is_main_query() // Only target the main query
		 && $q->is_search()     // Only target the search page
	) {
		$q->set( 'post_type', [ 'my_custom_post_type', 'post' ] );
	}
} );


if ( ! function_exists( 'lisfinity_leave_only_original_images' ) ) {
	/**
	 * Remove not needed image sizes to preserve server space
	 * ------------------------------------------------------
	 *
	 * @param $sizes
	 *
	 * @return mixed
	 */
	function lisfinity_leave_only_original_images( $sizes ) {
		unset( $sizes['thumbnail'] );
		unset( $sizes['medium'] );
		unset( $sizes['medium_large'] );
		unset( $sizes['large'] );
		unset( $sizes['full'] );
		unset( $sizes['2048x2048'] );
		unset( $sizes['1536x1536'] );
		unset( $sizes['woocommerce_thumbnail'] );
		unset( $sizes['woocommerce_single'] );
		unset( $sizes['woocommerce_gallery_thumbnail'] );
		unset( $sizes['shop_catalog'] );
		unset( $sizes['shop_single'] );
		unset( $sizes['shop_thumbnail'] );
		unset( $sizes['product-thumbnail-placeholder'] );
		unset( $sizes['product-thumbnail'] );
		unset( $sizes['premium-profile-image'] );
		unset( $sizes['product-slider'] );

		//unset( $sizes['product-slider-thumb'] );

		return $sizes;
	}
}

if ( ! function_exists( 'lisfinity_reorganize_field_groups' ) ) {
	/**
	 * Reorganize taxonomies and the field groups
	 * ------------------------------------------
	 *
	 * @param string $from_group The group from where we wish to move the fields.
	 * @param string $to_group The group where we wish to put the fields to.
	 * @param array $keep Array of the groups that we wish to keep on the site, we can leave this empty
	 * to keep the groups configuration as it is.
	 * @param string $site_title Site title to make sure that the changes will be done only
	 * on a given site installation.
	 *
	 * @return array
	 */
	function lisfinity_reorganize_field_groups( $from_group = '', $to_group = '', $keep = [], $site_title = '' ) {
		$taxonomies_admin = new TaxonomiesAdminModel();
		$groups_admin     = new GroupsAdminModel();
		$single_groups    = get_option( 'lisfinity--single-fields' );

		$field_groups = $groups_admin->get_options();
		$groups       = $taxonomies_admin->get_options();
		$change_made  = false;

		// Check if we're doing this on a specific site.
		if ( ! empty( $site_title ) && $site_title !== get_option( 'blogname' ) ) {
			return $groups;
		}

		if ( ! empty( $from_group ) && ! empty( $to_group ) ) {
			// Move the fields to the given group.
			foreach ( $groups as $group_name => $fields ) {
				if ( $from_group === $group_name ) {
					foreach ( $fields as $index => $field ) {
						$field['field_group']  = $to_group;
						$groups[ $to_group ][] = $field;
						unset( $groups[ $from_group ][ $index ] );
					}
				}
			}
			$change_made = true;
		}


		// Delete unnecessary fields.
		if ( ! empty( $keep ) ) {
			foreach ( $groups as $group_name => $fields ) {
				if ( ! in_array( $group_name, $keep ) ) {
					unset( $groups[ $group_name ] );
				}
			}
			$change_made = true;
		}
		// Delete unnecessary groups.
		if ( ! empty( $keep ) ) {
			foreach ( $field_groups as $index => $fields ) {
				if ( $fields['slug'] !== $keep ) {
					unset( $field_groups[ $index ] );
				}
			}
		}

		// Check if there was any changes and save the fields and groups.
		if ( $change_made ) {
			$taxonomies_admin->set_options( $groups );
			$groups_admin->set_options( $field_groups );
		}

		return [ $groups, $field_groups ];
	}
}

if ( ! function_exists( 'lisfinity_get_product_slug' ) ) {
	/**
	 * Get WooCommerce product slug
	 * ----------------------------
	 * @return string|string[]
	 */
	function lisfinity_get_product_slug() {
		$permalinks   = wc_get_permalink_structure();
		$product_base = ! empty( $permalinks['product_base'] ) ? $permalinks['product_base'] : 'product';
		$product_base = str_replace( '/', '', $product_base );

		return $product_base;
	}
}

if ( ! function_exists( 'lisfinity_settings_init' ) ) {
	/**
	 * Register settings page in the default WordPress
	 * settings section
	 * -----------------------------------------------
	 */
	function lisfinity_settings_init() {
		add_settings_section( 'lisfinity-permalink', __( 'Lisfinity permalinks', 'lisfinity-core' ), 'lisfinity_settings', 'permalink' );
	}
}

if ( ! function_exists( 'lisfinity_settings' ) ) {
	/**
	 * Display Lisfinity settings on the default
	 * WordPress settings page
	 * -----------------------------------------------
	 */
	function lisfinity_settings() {
		?>
		<div>
			<?php echo wp_kses_post( wpautop( sprintf( 'If you wish to change the permalinks created by the Lisfinity theme please go to %s page and click on the Permalinks tab.', '<a href="' . esc_url( 'http://lisfinity.test/wp-admin/admin.php?page=lisfinity-theme-options.php' ) . '" target="_blank">Lisfinity Permalinks</a>' ) ) ) ?>
		</div>
		<?php
	}
}

function lisfinity_redirect_logged_user_from_auth_pages() {
	if ( is_user_logged_in() && ! lisfinity_is_elementor() && ! \Elementor\Plugin::$instance->preview->is_preview_mode() && + ( lisfinity_is_page_template( 'page-register' ) || lisfinity_is_page_template( 'page-login' ) || lisfinity_is_page_template( 'page-reset' ) ) ) :
		wp_safe_redirect( get_permalink( lisfinity_get_page_id( 'page-account' ) ) );
		exit;
	endif;
}

function lisfinity_get_slug( $name, $default ) {
	$slug = lisfinity_get_option( $name );
	if ( empty( $slug ) ) {
		return $default;
	}

	return $slug;
}

// custom urls.
if ( ! function_exists( 'lisfinity_category_rewrites_init' ) ) {
	/**
	 * Add custom rewrite rules for targeting custom
	 * created categories/groups
	 * ---------------------------------------------
	 *
	 */
	function lisfinity_category_rewrites_init() {
		$slug = lisfinity_get_slug( 'slug-category', 'ad-category' );
		add_rewrite_rule(
			'^' . $slug . '/([^/]*)/?',
			'index.php?' . $slug . '=$matches[1]',
			'top' );
	}
}

if ( ! function_exists( 'lisfinity_category_query_vars' ) ) {
	/**
	 * Add custom query var for the custom created groups
	 * so we can list the products by them on their page
	 * --------------------------------------------------
	 *
	 * @param $query_vars
	 *
	 * @return mixed
	 */
	function lisfinity_category_query_vars( $query_vars ) {
		$slug         = lisfinity_get_slug( 'slug-category', 'ad-category' );
		$query_vars[] = $slug;

		return $query_vars;
	}
}

if ( ! function_exists( 'lisfinity_category_wp_title' ) ) {
	/**
	 * Add the custom page title that will be displayed in the
	 * browser tab for our custom categories/groups
	 * -------------------------------------------------------
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	function lisfinity_category_wp_title( $title ) {
		$model      = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$categories = $model->get_groups_slugs();
		$groups     = $model->get_options();
		$slug       = lisfinity_get_slug( 'slug-category', 'ad-category' );
		$type       = get_query_var( $slug );
		if ( empty( $title['title'] ) && ! empty( $type ) && in_array( $type, $categories ) ) {
			$key            = array_search( $type, array_column( $groups, 'slug' ) );
			$title['title'] = $groups[ $key ]['plural_name'];
		}

		return $title;
	}
}

if ( ! function_exists( 'lisfinity_is_visible' ) ) {
	/**
	 * Checks if the field should be visible to a user
	 * -----------------------------------------------
	 *
	 * @param $option_name
	 *
	 * @return bool
	 */
	function lisfinity_is_visible( $option_name ) {
		$option = apply_filters( 'lisfinity__is_visible_option', lisfinity_get_option( $option_name ) );

		if ( 'never' === $option ) {
			return false;
		}

		return true;
	}
}

function lisfinity_change_key( $array, $old_key, $new_key ) {

	if ( ! array_key_exists( $old_key, $array ) ) {
		return $array;
	}

	$keys                                    = array_keys( $array );
	$keys[ array_search( $old_key, $keys ) ] = $new_key;

	return array_combine( $keys, $array );
}

function lisfinity_translate_object_id( $object_id, $type, $default_language = false ) {
	if ( $default_language ) {
		$current_language = wpml_get_default_language();
	} else {
		$current_language = apply_filters( 'wpml_current_language', null );
	}
	if ( is_array( $object_id ) ) {
		$translated_object_ids = array();
		foreach ( $object_id as $id ) {
			$translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true, $current_language );
		}

		return $translated_object_ids;
	} elseif ( is_string( $object_id ) ) {
		// check if we have a comma separated ID string
		$is_comma_separated = strpos( $object_id, "," );

		if ( $is_comma_separated !== false ) {
			// explode the comma to create an array of IDs
			$object_id = explode( ',', $object_id );

			$translated_object_ids = array();
			foreach ( $object_id as $id ) {
				$translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true, $current_language );
			}

			// make sure the output is a comma separated string (the same way it came in!)
			return implode( ',', $translated_object_ids );
		} else {
			return apply_filters( 'wpml_object_id', intval( $object_id ), $type, true, $current_language );
		}
	} else {
		return apply_filters( 'wpml_object_id', $object_id, $type, true, $current_language );
	}
}

function lisfinity_get_site_path() {
	$path = wp_parse_url( get_site_url(), PHP_URL_PATH );
	if ( $path ) {
		return rtrim( $path, '/' ) . '/';
	}

	return '/';
}

function lisfinity_format_orders_select() {
	$args = [
		'limit'  => - 1,
		'status' => 'completed',
	];

	// Get from cache to prevent same queries.
	$last_changed = wp_cache_get_last_changed( 'lisfinity--get-orders' );
	$key          = md5( serialize( $args ) );
	$cache_key    = "$key:$last_changed";
	$formatted    = wp_cache_get( $cache_key, 'lisfinity--get-orders' );

	if ( false !== $formatted ) {
		return $formatted;
	}


	$orders    = wc_get_orders( $args );
	$formatted = [];
	foreach ( $orders as $order ) {
		$formatted[ $order->get_id() ] = sprintf( __( 'Order #%s', 'lisfinity-core' ), $order->get_id() );
	}

	// Cache the query.
	wp_cache_set( $cache_key, $formatted, 'lisfinity--get-orders' );

	return $formatted;
}

if ( ! function_exists( 'lisfinity_confirm_stripe_payment' ) ) {
	/**
	 * Confirm that the stripe connect payment has been through
	 * --------------------------------------------------------
	 *
	 * @throws \Stripe\Exception\ApiErrorException
	 * todo should be done with the stripe javascript and webhooks
	 */
	function lisfinity_confirm_stripe_payment() {
		global $wp;
		if ( ! empty( $_GET['gateway'] ) && 'stripe' === $_GET['gateway'] && isset( $wp->query_vars['order-received'] ) ) {
			$order_id = get_query_var( 'order-received' );
			$order    = wc_get_order( $order_id );

			$payment_intent  = get_post_meta( $order_id, '_stripe-payment-intent', true );
			$completed       = get_post_meta( $order_id, '_stripe-payment-completed', true );
			$payment_gateway = WC()->payment_gateways->payment_gateways()['stripe_connect'];
			\Stripe\Stripe::setApiKey( $payment_gateway->secret_key );

			$intent  = \Stripe\PaymentIntent::retrieve( $payment_intent );
			$charges = $intent->charges->data;
			// if payment has succeeded
			if ( 'succeeded' === $charges[0]->status && ! $completed ) {

				$order->update_status( 'completed' );
				wc_reduce_stock_levels( $order_id );

				update_post_meta( $order_id, '_stripe-payment-completed', true );
			}
			// if payment has failed
			if ( 'failed' === $charges[0]->status && ! $completed ) {
				$order->update_status( 'failed' );
				update_post_meta( $order_id, '_stripe-payment-completed', $charges[0]->status );
			}
		}
	}

	add_action( 'template_redirect', 'lisfinity_confirm_stripe_payment' );
}

if ( ! function_exists( 'lisfinity_is_stripe_connect_enabled' ) ) {
	/**
	 * Check if the Stripe Connect payment gateway has been enabled
	 * ------------------------------------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_stripe_connect_enabled() {
		$stripe_gateway = WC()->payment_gateways->payment_gateways()['stripe_connect'];

		return 'yes' === $stripe_gateway->enabled;
	}
}

if ( ! function_exists( 'lisfinity_slug' ) ) {
	/**
	 * Create a slug to use with cyrillic fonts
	 * ----------------------------------------
	 *
	 * @param $str
	 * @param null $limit
	 *
	 * @return false|string|string[]
	 */
	function lisfinity_slug( $str, $limit = null ) {
		if ( $limit ) {
			$str = mb_substr( $str, 0, $limit, "utf-8" );
		}
		$text = html_entity_decode( $str, ENT_QUOTES, 'UTF-8' );
		// replace non letter or digits by -
		$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );
		// trim
		$text = trim( $text, '-' );

		return mb_strtolower( $text );
	}
}

if ( ! function_exists( 'lisfinity_is_cyrillic' ) ) {
	/**
	 * Check whether the site is using cyrillic or some other font
	 * -----------------------------------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_cyrillic() {
		if ( 'cyrillic' === lisfinity_get_option( 'site-literation' ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_create_slug' ) ) {
	/**
	 * Create slug depending on the chosen font type
	 * ---------------------------------------------
	 *
	 * @param $title
	 *
	 * @return false|string|string[]
	 */
	function lisfinity_create_slug( $title ) {

		return sanitize_title( lisfinity_convert_to_readable_slug( $title ) );
	}
}

function lisfinity_sender_email( $original_email_address ) {
	$email = lisfinity_get_option( 'email-from-address' );
	if ( empty( $email ) ) {
		$email = $original_email_address;
	}

	return $email;
}

add_filter( 'wp_mail_from', 'lisfinity_sender_email' );

function lisfinity_sender_name( $original_email_from ) {
	$from = lisfinity_get_option( 'email-from-name' );

	if ( empty( $from ) ) {
		$from = $original_email_from;
	}

	return $from;
}

add_filter( 'wp_mail_from_name', 'lisfinity_sender_name' );

function lisfinity_get_post_type_select( $post_type ) {
	//Get all header posts
	$args = [
		'post_type'        => $post_type,
		'post_status'      => 'publish',
		'numberposts'      => - 1,
		'orderby'          => 'title',
		'order'            => 'ASC',
		'suppress_filters' => false
	];

	// Get from cache to prevent same queries.
	$last_changed = wp_cache_get_last_changed( "lisfinity--{$args['post_type']}-select" );
	$key          = md5( serialize( $args ) );
	$cache_key    = "$key:$last_changed";
	$select       = wp_cache_get( $cache_key, "lisfinity--{$args['post_type']}-select" );

	if ( false !== $select ) {
		return $select;
	}

	// Cache the query.
	$types      = get_posts( $args );
	$select     = [];
	$select[''] = __( 'Use Default', 'lisfinity-core' );

	if ( ! empty( $types ) ) {
		foreach ( $types as $type ) {
			$select[ $type->ID ] = $type->post_title;
		}
	}

	wp_cache_set( $cache_key, $select, "lisfinity--{$args['post_type']}-select" );

	return $select;
}

// Options that are relevant only for the version 1.1.5 & will be deleted later.
function lisfinity_force_header_on_version_115() {
	$option = get_option( 'lisfinity_115_header_reverted' );
	if ( empty( $option ) && 'yes' !== $option ) {
		Redux::set_option( 'lisfinity-options', '_header-type', 'default' );
		update_option( 'lisfinity_115_header_reverted', 'yes' );
	}
}

add_action( 'admin_init', 'lisfinity_force_header_on_version_115' );

function lisfinity_redux_header_notice() {
	$option = get_option( 'lisfinity_115_dismissed' );
	if ( empty( $option ) && 'yes' !== $option ) {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Header has been reverted to default one in the Lisfinity Options. <br /> This is done to prevent any possible errors due to separation of the navigation menu Elementor elements. <br /> If you have been using a header created in Elementor you can recreate it from <strong>Lisfinity Elements -> Headers</strong>. <br /> To reassign it go to <strong>Lisfinity Options -> Header Setup.</strong>', 'lisfinity-core' ); ?></p>
		</div>
		<?php
	}
	update_option( 'lisfinity_115_dismissed', 'yes' );
}

add_action( 'admin_notices', 'lisfinity_redux_header_notice' );

if ( ! function_exists( 'lisfinity_correct_taxonomy_terms_after_demo' ) ) {
	function lisfinity_correct_taxonomy_terms_after_demo() {
		$model = new TaxonomiesAdminModel();
		$model->correct_taxonomies_terms_order_for_fields_builder_change();
	}
}

add_action( 'lisfinity_demo_completed', 'lisfinity_correct_taxonomy_terms_after_demo' );


if ( ! function_exists( 'lisfinity_pages_post_states' ) ) {
	/**
	 * Add a post display state for special Lisfinity pages in the page list table.
	 *
	 * @param $post_states
	 * @param $post
	 *
	 * @return mixed
	 */
	function lisfinity_pages_post_states( $post_states, $post ) {
		if ( (int) lisfinity_get_option( 'page-single-listing' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Single Listing Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-search' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Search Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-register' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Register Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-login' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Login Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-reset' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Password Reset Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-vendors' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Vendors Page', 'lisfinity-core' );
		}

		if ( (int) lisfinity_get_option( 'page-tips' ) === $post->ID ) {
			$post_states['lisfinity_page-single'] = __( 'Safety Tips Page', 'lisfinity-core' );
		}

		return $post_states;
	}

	add_filter( 'display_post_states', 'lisfinity_pages_post_states', 10, 2 );
}

function lisfinity_get_product_id() {
	$product_id = get_queried_object_id();

	if ( is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) {
		$product_id = carbon_get_post_meta( get_the_ID(), 'elementor-mockup-product' );

		return $product_id;
	}

	$page_id = ! empty( $_GET['post'] ) ? $_GET['post'] : ( ! empty( $_REQUEST['editor_post_id'] ) ? $_REQUEST['editor_post_id'] : $product_id );
	if ( (int) $page_id === (int) lisfinity_get_page_id( 'page-single-listing' ) ) {

		$elementor_id = lisfinity_get_option( 'page-single-listing' );
		if ( ! empty( $elementor_id ) ) {
			$product_id = carbon_get_post_meta( $elementor_id, 'elementor-mockup-product' );
		} else {
			$listing_args = [
				'post_type' => 'product',
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			];

			$listings = lisfinity_get_posts( $listing_args );
			if ( ! empty( $listings ) ) {
				return $listings[0]->ID;
			}
		}
	}

	return $product_id;
}

function lisfinity_get_single_page_product_id() {
	$product_id = get_queried_object_id();

	if ( ! is_singular( [ 'product', 'premium_profile' ] ) ) {
		$product_id = get_query_var( 'products' );
	}

	if ( is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) {
		$product_id = carbon_get_post_meta( get_the_ID(), 'elementor-mockup-product' );

		return $product_id;
	}

	if ( defined( 'ELEMENTOR_VERSION' ) && Plugin::$instance->preview->is_preview_mode() && get_queried_object_id() === (int) lisfinity_get_page_id( 'page-single-listing' ) ) {
		$elementor_id = lisfinity_get_option( 'page-single-listing' );
		if ( ! empty( $elementor_id ) ) {
			$product_id = carbon_get_post_meta( $elementor_id, 'elementor-mockup-product' );
		} else {
			$listing_args = [
				'post_type' => 'product',
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			];

			$listings = lisfinity_get_posts( $listing_args );
			if ( ! empty( $listings ) ) {
				return $listings[0]->ID;
			}
		}
	}

	return $product_id;
}

function lisfinity_get_single_page_business_id() {
	$product_id = get_queried_object_id();

	if ( ! is_singular( [ 'product', 'premium_profile' ] ) ) {
		$product_id = get_query_var( 'products' );
	}

	if ( is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) {
		$product_id = carbon_get_post_meta( get_the_ID(), 'elementor-mockup-business' );

		return $product_id;
	}

	if ( defined( 'ELEMENTOR_VERSION' ) && Plugin::$instance->preview->is_preview_mode() && in_array( get_queried_object_id(), [
			lisfinity_get_page_id( 'page-business' ),
			lisfinity_get_page_id( 'page-business-premium' )
		] ) ) {
		$elementor_id         = lisfinity_get_option( 'page-business' );
		$elementor_premium_id = lisfinity_get_option( 'page-business-premium' );

		if ( ! empty( $elementor_id ) ) {
			$product_id = carbon_get_post_meta( $elementor_id, 'elementor-mockup-business' );
		} else if ( ! empty( $elementor_premium_id ) ) {
			$product_id = carbon_get_post_meta( $elementor_premium_id, 'elementor-mockup-business' );
		} else {
			$listing_args = [
				'post_type'      => \Lisfinity\Models\Users\ProfilesModel::$post_type_name,
				'posts_per_page' => - 1
			];

			$listings = lisfinity_get_posts( $listing_args );
			if ( ! empty( $listings ) ) {
				return $listings[0]->ID;
			}
		}
	}

	return $product_id;
}

if ( ! function_exists( 'lisfinity_business_is_premium' ) ) {
	/**
	 * Check whether the business is of type premium
	 * ---------------------------------------------
	 *
	 * @param $business_id
	 *
	 * @return bool
	 */
	function lisfinity_business_is_premium( $business_id ): bool {
		$model = new \Lisfinity\Models\PromotionsModel();

		$premium = $model->where( [
			[ 'product_id', $business_id ],
			[ 'status', 'active' ],
			[ 'expires_at', '>', 'NOW()' ],
		] )->get( '1', '', '' );

		return ! empty( $premium );
	}
}

if ( ! function_exists( 'lisfinity_format_categories_select' ) ) {
	/**
	 * Format categories from the fields builder so that they can be
	 * used in a React select field
	 * -------------------------------------------------------------
	 *
	 * @param false $include_common
	 *
	 * @return array
	 */
	function lisfinity_format_categories_select( $include_common = false ) {
		$model      = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$categories = $model->get_options( $include_common );

		$categories_select = [];
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$categories_select[] = [ 'value' => $category['slug'], 'label' => $category['single_name'] ];
			}
		}

		return $categories_select;
	}
}

if ( ! function_exists( 'lisfinity_refresh_fields_builder_terms' ) ) {
	/**
	 * Refresh the terms when they are not visible in the fields
	 * builder after the import
	 * ---------------------------------------------------------
	 *
	 */
	function lisfinity_refresh_fields_builder_terms() {
		$model = new TaxonomiesAdminModel();
		$model->correct_taxonomies_terms_order_for_fields_builder_change();
	}
}

if ( ! function_exists( 'lisfinity_get_product_taxonomies' ) ) {
	/**
	 * Get all taxonomies registered for the product object type
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_get_product_taxonomies(): array {
		$model = new TermRoute();

		return $model->get_product_taxonomies();
	}
}

if ( ! function_exists( 'lisfinity_format_location_taxonomies_in_select' ) ) {
	/**
	 * Create location taxonomies select fields
	 * ----------------------------------------
	 *
	 * @return mixed
	 */
	function lisfinity_format_location_taxonomies_in_select() {
		$taxonomies_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$taxonomies       = $taxonomies_model->get_taxonomies_options_by_type( 'location' );
		$select           = [];

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$select[ $taxonomy['slug'] ] = $taxonomy['single_name'];
			}
		}

		return apply_filters( 'lisfinity__lisfinity_format_location_taxonomies_in_select', $select );
	}
}


if ( ! function_exists( 'lisfinity_is_elements_template' ) ) {
	/**
	 * Check if we're on the elements template
	 *
	 * @return bool
	 */
	function lisfinity_is_elements_template() {
		if ( ! lisfinity_is_core_active() ) {
			return false;
		}

		if ( is_singular( \Lisfinity\Models\Elements\HeaderModel::$type ) || is_singular( \Lisfinity\Models\Elements\FooterModel::$type ) || is_singular( \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'lisfinity_is_elementor_preview' ) ) {
	/**
	 * Check if it is the elementor preview page
	 * -----------------------------------------
	 *
	 * @return bool
	 */
	function lisfinity_is_elementor_preview() {
		return isset( $_GET['action'] ) && 'elementor' === $_GET['action'];
	}
}

if ( ! function_exists( 'lisfinity_sorting_payment_packages' ) ) {
	/**
	 * The way payment packages are sorted by default
	 * ----------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_sorting_payment_packages() {
		return [
			'price'     => __( 'Price', 'lisfinity-core' ),
			'post_date' => __( 'Date', 'lisfinity-core' )
		];
	}
}

if ( ! function_exists( 'lisfinity_business_phone_apps' ) ) {
	/**
	 * Business phone Apps
	 * ----------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_business_phone_apps() {
		return [
			'viber'    => __( 'Viber', 'lisfinity-core' ),
			'whatsapp' => __( 'WhatsApp', 'lisfinity-core' ),
			'skype'    => __( 'Skype', 'lisfinity-core' )
		];
	}
}

if ( ! function_exists( 'lisfinity_business_social_networks' ) ) {
	/**
	 * The business social network
	 * ----------------------------------------------
	 *
	 * @return array
	 */
	function lisfinity_business_social_networks() {
		return [
			'facebook'   => __( 'Facebook', 'lisfinity-core' ),
			'twitter'    => __( 'Twitter', 'lisfinity-core' ),
			'instagram'  => __( 'Instagram', 'lisfinity-core' ),
			'v_kontakte' => __( 'VKontakte', 'lisfinity-core' ),
			'youtube'    => __( 'Youtube', 'lisfinity-core' ),
		];
	}
}


if ( ! function_exists( 'lisfinity_check_default_pages' ) ) {
	/**
	 * Assign default pages if the required ones are empty
	 * ---------------------------------------------------
	 */
	function lisfinity_check_default_pages() {
		if ( empty( lisfinity_get_option( 'page-account' ) ) ) {
			$account_page_id = wc_get_page_id( 'myaccount' );
			\Redux::set_option( 'lisfinity-options', '_page-account', $account_page_id );
		}
	}

	add_action( 'admin_init', 'lisfinity_check_default_pages' );
}

/**
 * Get the correct currency based on the user input
 * ------------------------------------------------
 *
 * @return mixed|string
 */
function lisfinity_get_chosen_currency() {
	$currency = 'yes' === get_option( '_multicurrency-enabled' ) && ! empty( $_COOKIE['currency'] ) ? $_COOKIE['currency'] : get_option( 'woocommerce_currency' );

	return $currency;
}

/**
 * Get the correct currency rate based on the user input
 * -----------------------------------------------------
 *
 * @return float|int
 */
function lisfinity_get_chosen_currency_rate() {
	$rate     = 1;
	$currency = lisfinity_get_chosen_currency();
	if ( 'yes' === get_option( '_multicurrency-enabled' ) && $currency !== get_option( 'woocommerce_currency' ) ) {
		$currencies = carbon_get_theme_option( 'currencies' );
		$key        = array_search( $currency, array_column( $currencies, 'country' ) );

		if ( ! empty( $currencies[ $key ]['country'] ) ) {
			$rate = floatval( $currencies[ $key ]['rate'] );
		}
	}

	return $rate;
}

/**
 * Set the correct currency symbol depending on the user choice
 * ------------------------------------------------------------
 *
 * @return mixed|string
 */
function lisfinity_set_currency() {
	return lisfinity_get_chosen_currency();
}

add_filter( 'woocommerce_currency', 'lisfinity_set_currency' );

// Hook in
add_filter( 'woocommerce_checkout_fields', 'lisfinity_custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function lisfinity_custom_override_checkout_fields( $fields ) {
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-first-name' ) ) ) {
		unset( $fields['billing']['billing_first_name'] );
		unset( $fields['shipping']['shipping_first_name'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-last-name' ) ) ) {
		unset( $fields['billing']['billing_last_name'] );
		unset( $fields['shipping']['shipping_last_name'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-company-name' ) ) ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['shipping']['shipping_company'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-country' ) ) ) {
		unset( $fields['billing']['billing_country'] );
		unset( $fields['shipping']['shipping_country'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-street-address' ) ) ) {
		unset( $fields['billing']['billing_address_1'] );
		unset( $fields['shipping']['shipping_address_1'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-street-address-two' ) ) ) {
		unset( $fields['billing']['billing_address_2'] );
		unset( $fields['shipping']['shipping_address_2'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-zip' ) ) ) {
		unset( $fields['billing']['billing_postcode'] );
		unset( $fields['shipping']['shipping_postcode'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-state' ) ) ) {
		unset( $fields['billing']['billing_state'] );
		unset( $fields['shipping']['shipping_state'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-town' ) ) ) {
		unset( $fields['billing']['billing_city'] );
		unset( $fields['shipping']['shipping_city'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-email-address' ) ) ) {
		unset( $fields['billing']['billing_email'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-phone' ) ) ) {
		unset( $fields['billing']['billing_phone'] );
	}
	if ( ! lisfinity_is_enabled( lisfinity_get_option( 'checkout-order-notes' ) ) ) {
		unset( $fields['order']['order_comments'] );
	}

	return $fields;
}

if ( ! function_exists( 'lisfinity_custom_logout' ) ) {
	/**
	 * Custom logout functionality
	 * ---------------------------
	 *
	 * @param $action
	 * @param $result
	 */
	function lisfinity_custom_logout( $action, $result ) {
		$redirect = get_home_url( '/' );
		if ( lisfinity_is_enabled( lisfinity_get_option( 'custom-logout' ) ) && ! empty( lisfinity_get_option( 'custom-logout-url' ) ) ) {
			$redirect = lisfinity_get_option( 'custom-logout-url' );
		}
		if ( $action == "log-out" && ! isset( $_GET['_wpnonce'] ) ) {
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $redirect;
			$location    = str_replace( '&amp;', '&', wp_logout_url( $redirect_to ) );
			header( "Location: $location" );
			die;
		}
	}

	add_action( 'check_admin_referer', 'lisfinity_custom_logout', 10, 2 );
}

if ( ! function_exists( 'lisfinity_packages_enabled' ) ) {
	/**
	 * Check if the pricing packages are enabled
	 * -----------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	function lisfinity_packages_enabled( $user_id ) {
		$feature = lisfinity_get_option( 'site-packages' );

		if ( empty( $feature ) || 'enabled' === $feature ) {
			return true;
		}

		if ( 'disabled' === $feature ) {
			return false;
		}

		if ( ! empty( $user_id ) ) {
			$account_type = carbon_get_user_meta( $user_id, 'account-type' );

			if ( ! empty( $account_type ) && $feature !== $account_type ) {
				return false;
			}
		}

		return true;
	}
}

function lisfinity_populate_taxonomies_from_csv() {
	$file = '';
	ob_start();
	if ( file_exists( LISFINITY_CORE_DIR . 'sheet.csv' ) ) {
		require LISFINITY_CORE_DIR . 'sheet.csv';
		$file = ob_get_clean();
	}

	if ( ! empty ( $file ) ) {
		$file = str_getcsv( $file, PHP_EOL );

		$headers      = array_shift( $file );
		$headers      = explode( ',', $headers );
		$header_slugs = [];
		if ( ! empty( $headers ) && is_array( $headers ) ) {
			foreach ( $headers as $header ) {
				$header_slugs[] = sanitize_title( $header );
			}
		}

		$file = array_splice( $file, 1, count( $file ) );
		foreach ( $file as $items ) {
			$terms = explode( ',', $items );
			if ( ! empty( $terms ) ) {
				$term_parent = 0;
				foreach ( $terms as $index => $term ) {

					$existing_term = get_term_by( 'name', rtrim( $term ), $header_slugs[ $index ] );

					if ( $existing_term ) {
						$term_parent = (int) $existing_term->term_id;
					} else {
						$term_result = wp_insert_term( rtrim( $term ), $header_slugs[ $index ], [
							'parent' => $term_parent,
						] );

						if ( ! is_wp_error( $term_result ) ) {
							if ( $term_parent ) {
								$parent_term = get_term_by( 'term_id', (int) $term_parent, $header_slugs[ $index - 1 ] );
								update_term_meta( $term_result['term_id'], 'parent_name', $parent_term->name );
								update_term_meta( $term_result['term_id'], 'parent_slug', $parent_term->slug );
								update_term_meta( $term_result['term_id'], 'parent_taxonomy', $parent_term->taxonomy );

								$taxonomy_model = new TaxonomiesAdminModel();
								$tax_options    = $taxonomy_model->get_options();
								$taxonomies     = $tax_options['common'];
								$taxonomy_slugs = array_column( $tax_options['common'], 'slug' );
								$tax_key        = array_search( $header_slugs[ $index ], $taxonomy_slugs );
								if ( ! in_array( $header_slugs[ $index ] . '-' . $term_result['term_id'], $taxonomies[ $tax_key ]['term_ids'] ) ) {
									$taxonomies[ $tax_key ]['term_ids'][] = $header_slugs[ $index ] . '-' . $term_result['term_id'];
								}
								$tax_options['common'] = $taxonomies;

								$taxonomy_model->set_options( $tax_options );

							}
							$term_parent = (int) $term_result['term_id'];
						}
					}
				}
			}
		}
	}
}

function lisfinity_get_domain() {
	if ( function_exists( "site_url" ) ) {
		return site_url();
	}
	if ( defined( "WPINC" ) && function_exists( "get_bloginfo" ) ) {
		return get_bloginfo( 'url' );
	} else {
		$base_url = ( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == "on" ) ? "https" : "http" );
		$base_url .= "://" . $_SERVER['HTTP_HOST'];
		$base_url .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), "", $_SERVER['SCRIPT_NAME'] );

		return $base_url;
	}
}

function lisfinity_add_admin_body_class( $classes ) {
	if ( ! \LisfinityBase::GetRegisterInfo() ) {
		$classes .= ' lisfinity-inactive ';
	}

	return $classes;
}

add_action( 'admin_body_class', 'lisfinity_add_admin_body_class' );

if ( ! function_exists( 'lisfinity_get_correct_submit_button_permalink' ) ) {
	/**
	 * Get the correct link for the submit ad button
	 * ---------------------------------------------
	 *
	 * @return string
	 */
	function lisfinity_get_correct_submit_button_permalink() {
		$myaccount = get_permalink( lisfinity_get_page_id( 'page-account' ) );
		if ( lisfinity_packages_enabled( get_current_user_id() ) ) {
			$link = $myaccount . 'packages';
		} else {
			$link = $myaccount . 'submit';
		}

		if ( ! is_user_logged_in() ) {
			$link = get_permalink( lisfinity_get_page_id( 'page-login' ) );
		}

		return apply_filters( 'lisfinity__get_correct_submit_button_permalink', $link );
	}
}

function lisfinity_update_settings_based_on_the_theme_version() {
	$option = get_option( '1.1.19-settings' );
	if ( 'updated' !== $option && LISFINITY_CORE_VERSION === '1.1.19' ) {
		Redux::set_option( 'lisfinity-options', '_site-packages', 'enabled' );
		$users = get_users( [
			'role'   => [ 'editor', 'administrator' ],
			'fields' => 'ids',
		] );
		if ( ! empty( $users ) ) {
			foreach ( $users as $user_id ) {
				carbon_set_user_meta( $user_id, 'account-type', 'business' );
			}
		}

		update_option( '1.1.19-settings', 'updated' );
	}

	// make sure that the account page has been set.
	$page_account_option = get_option( 'woocommerce_myaccount_page_id' ) === lisfinity_get_option( 'page-account' );
	if ( ! $page_account_option ) {
		\Redux::set_option( 'lisfinity-options', '_page-account', get_option( 'woocommerce_myaccount_page_id' ) );
	}
}

add_action( 'init', 'lisfinity_update_settings_based_on_the_theme_version' );

function lisfinity_transliterate( $name ) {
	$arr = [
		"Љ" => "Lj",
		"Њ" => "Nj",
		"Џ" => "Dž",
		"џ" => "dž",
		"ш" => "š",
		"ђ" => "đ",
		"ч" => "č",
		"ћ" => "ć",
		"ж" => "ž",
		"љ" => "lj",
		"њ" => "nj",
		"Ш" => "Š",
		"Ђ" => "Đ",
		"Ч" => "Č",
		"Ћ" => "Ć",
		"Ж" => "Ž",
		"Ц" => "C",
		"ц" => "c",
		"а" => "a",
		"б" => "b",
		"в" => "v",
		"г" => "g",
		"д" => "d",
		"е" => "e",
		"ё" => "io",
		"з" => "z",
		"и" => "i",
		"й" => "y",
		"к" => "k",
		"л" => "l",
		"м" => "m",
		"н" => "n",
		"о" => "o",
		"п" => "p",
		"р" => "r",
		"с" => "s",
		"т" => "t",
		"у" => "u",
		"ф" => "f",
		"х" => "h",
		"щ" => "št",
		"ъ" => "a",
		"ы" => "i",
		"ь" => "y",
		"э" => "e",
		"ю" => "yu",
		"я" => "ya",
		"А" => "A",
		"Б" => "B",
		"В" => "V",
		"Г" => "G",
		"Д" => "D",
		"Е" => "E",
		"Ё" => "Io",
		"З" => "Z",
		"И" => "I",
		"Й" => "Y",
		"К" => "K",
		"Л" => "L",
		"М" => "M",
		"Н" => "N",
		"О" => "O",
		"П" => "P",
		"Р" => "R",
		"С" => "S",
		"Т" => "T",
		"У" => "U",
		"Ф" => "F",
		"Х" => "H",
		"Щ" => "Sht",
		"Ъ" => "A",
		"Ы" => "I",
		"Ь" => "Y",
		"Э" => "e",
		"Ю" => "Yu",
		"Я" => "Ya"
	];

	$transliterated = str_replace( array_keys( $arr ), $arr, $name );

	return $transliterated;
}

function lisfinity_cyr_to_latin() {
	$path   = LISFINITY_CORE_DIR . 'a/lisfinity-core-sr_RS.po';
	$result = array( 'status' => 'error', 'message' => '' );
	if ( file_exists( $path ) === true ) {
		if ( is_writeable( $path ) ) {
			try {
				$FileContent = file_get_contents( $path );
				$FileContent = lisfinity_transliterate( $FileContent );
				if ( file_put_contents( $path, $FileContent ) > 0 ) {
					$result["status"] = 'success';
				} else {
					$result["message"] = 'Error while writing file';
				}
			} catch ( Exception $e ) {
				$result["message"] = 'Error : ' . $e;
			}
		} else {
			$result["message"] = 'File ' . $path . ' is not writable !';
		}
	} else {
		$result["message"] = 'File ' . $path . ' does not exist !';
	}

	return $result;
}

//todo to be remove in v1.3.5
function lisfinity_force_packages_to_be_virtual() {
	$already_done = get_option( 'force_virtual' );

	// bail if already performed.
	if ( ! empty( $already_done ) ) {
		return;
	}

	$args = [
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => - 1,
		'ignore_sticky_posts' => true,
		'tax_query'           => [
			[
				'taxonomy' => 'product_type',
				'field'    => 'name',
				'terms'    => [ 'payment_package', 'promotion', 'payment_subscription', 'commission' ],
				'operator' => 'IN',
			],
		],
		'fields'              => 'ids',
	];

	$posts = get_posts( $args );

	if ( ! empty( $posts ) ) {
		foreach ( $posts as $id ) {
			update_post_meta( $id, '_virtual', 'yes' );
		}
	}

	update_option( 'force_virtual', 'yes' );
}

add_action( 'admin_init', 'lisfinity_force_packages_to_be_virtual' );
