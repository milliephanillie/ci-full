<?php

// include composer auto loads.
if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
	require_once get_parent_theme_file_path( 'includes/class-tgm-plugin-activation.php' );
}
require get_parent_theme_file_path( 'vendor/autoload.php' );


/// url rewrite
/// /type/cf/taxonomy
///
///



add_action('init', 'rewrite_rule_example');
function rewrite_rule_example() {
    add_rewrite_rule(
        'type/(.+)/?',
        'index.php?pagename=search&category-type=$matches[1]',
        'top'
    );
}

add_filter('query_vars', 'add_query_vars_example');
function add_query_vars_example($query_vars) {
    $query_vars[] = 'category-type';
    return $query_vars;
}



//$taxonomy = 'placing-equipment-type';
//$term = 'laser-screeds';
//$id = 2620;
//$update = wp_set_object_terms( $id, $term, $taxonomy );
//var_dump("UPDATE!!!!!");
//var_dump( $term_query = new WP_Term_Query( array(
//    'taxonomy' => 'placing-equipment-type', // <-- Custom Taxonomy name..
//    'orderby'                => 'name',
//    'order'                  => 'ASC',
//    'child_of'               => 0,
//    'parent' => 0,
//    'fields'                 => 'all',
//    'hide_empty'             => false,
//) ) );
//die();

if ( !function_exists( 'is_rest' ) ) {
    /**
     * Checks if the current request is a WP REST API request.
     *
     * Case #1: After WP_REST_Request initialisation
     * Case #2: Support "plain" permalink settings and check if `rest_route` starts with `/`
     * Case #3: It can happen that WP_Rewrite is not yet initialized,
     *          so do this (wp-settings.php)
     * Case #4: URL Path begins with wp-json/ (your REST prefix)
     *          Also supports WP installations in subfolders
     *
     * @returns boolean
     * @author matzeeable
     */
    function is_rest() {
        if (defined('REST_REQUEST') && REST_REQUEST // (#1)
            || isset($_GET['rest_route']) // (#2)
            && strpos( $_GET['rest_route'], '/', 0 ) === 0)
            return true;

        // (#3)
        global $wp_rewrite;
        if ($wp_rewrite === null) $wp_rewrite = new WP_Rewrite();

        // (#4)
        $rest_url = wp_parse_url( trailingslashit( rest_url( ) ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        return strpos( $current_url['path'] ?? '/', $rest_url['path'], 0 ) === 0;
    }
}

add_action('pre_get_posts', 'remove_posts_with_no_image');
function remove_posts_with_no_image($query) {
    if(! is_object($query->tax_query)) {
        return;
    }

    if( ! is_admin() && ! $query->is_main_query() && ! is_search() && ! is_page('search') && ! is_page('page-search') && ! is_rest()) {
    if(array_key_exists('product_type', $query->tax_query->queried_terms)) {
            if ( $query->tax_query->queried_terms['product_type']['terms'][0] === 'listing' ) {
                $query->set('meta_key', '_thumbnail_id');
            }
        }
    }
}

$login_page_id = get_option('lisfinity-options')['_page-login'];

//var_dump(get_the_title($login_page_id));
//die();
//global $lisfinity_options;
//var_dump($lisfinity_options);
//die();

//add_filter( 'wp_setup_nav_menu', function( \stdClass $item ) {
//    # Check conditionals, and invalidate an item in case
//    $login_page_id = get_option('lisfinity-options')['_page-login'];
//    $item->_invalid = __return_true();
//        # && … whatever you need to check for your invalidation of an item
//
//    return $item;
//} );
//add_action('init', 'dcc_rewrite_rules');
//function dcc_rewrite_rules() {
//    $slug  = lisfinity_get_slug( 'slug-category', 'ad-category' );
//    $slug = lisfinity_get_slug( 'slug-category', 'ad-category' );
//    add_rewrite_rule(
//        '^' . $slug . '/([^/]*)/type?',
//        'index.php?' . $slug . '=$matches[1]',
//        'top' );
//    add_rewrite_rule('^alternatywy/([^/]+)/?$','index.php?page_id=8286&id=$matches[1]','top');
//}

add_filter('lisfinity__google_fonts', function($fonts) {
    $ci_font = [
        'Manrope' => '-- Custom CI Font (Manrope)',
    ];

    return array_merge($ci_font, $fonts);
});

add_filter('lisfinity__get_page_template', function($template_name) {
    $basename = basename($template_name);

    if ($basename !==  'page-home.php') {
        return $template_name;
    }

    return trailingslashit(get_template_directory()) . 'templates/pages/page-home-custom.php';
});

add_filter('wp_mail','disabling_emails', 10,1);
function disabling_emails( $args ){
    unset ( $args['to'] );
    return $args;
}

require get_parent_theme_file_path() . '/includes/hooks.php';
require get_parent_theme_file_path() . '/includes/assets.php';
require get_parent_theme_file_path() . '/includes/data.php';
add_action(
	'after_setup_theme',
	function () {
		/**
		 * Load textdomain.
		 */
		load_theme_textdomain( 'lisfinity', get_parent_theme_file_path() . '/languages' );

		/**
		 * Load theme setup
		 */
		require get_parent_theme_file_path( '/includes/merlin/vendor/autoload.php' );
		require get_parent_theme_file_path( '/includes/functions/functions-theme.php' );
		if ( is_admin() ) {
			require get_parent_theme_file_path( '/includes/merlin/class-merlin.php' );
			require get_parent_theme_file_path( '/includes/merlin/includes/wizard-config.php' );
		}
		require get_parent_theme_file_path( '/includes/helpers/functions-templates.php' );
		require get_parent_theme_file_path( '/includes/functions/functions-posts.php' );
		require get_parent_theme_file_path( '/includes/functions/functions-user.php' );
		require get_parent_theme_file_path( '/includes/helpers/helper-theme.php' );
		require get_parent_theme_file_path( '/includes/setup/theme-support.php' );
		require get_parent_theme_file_path( '/includes/setup/menus.php' );
		require get_parent_theme_file_path( '/includes/setup/sidebars.php' );
	}
);

add_filter('wp_nav_menu_objects', 'ad_filter_menu', 10, 2);

function ad_filter_menu($sorted_menu_objects, $args) {
    // check for the right menu to remove the menu item from
    // here we check for theme location of 'secondary-menu'
    // alternatively you can check for menu name ($args->menu == 'menu_name')

    if($args->menu->slug !== 'header-menu') {
        return $sorted_menu_objects;


    }

    if(is_user_logged_in() ) {
        foreach ($sorted_menu_objects as $key => $sorted_menu_object) {
            if(strtolower($sorted_menu_object->post_title) == 'login') {
                unset($sorted_menu_objects[$key]);
                break;
            }
        }

        return $sorted_menu_objects;
    }

    foreach ($sorted_menu_objects as $key => $menu_object) {

        // can also check for $menu_object->url for example
        // see all properties to test against:
        // print_r($menu_object); die();
        if (strtolower($menu_object->title) == 'my account' || strtolower($menu_object->title) == 'sell') {
            unset($sorted_menu_objects[$key]);
            break;
        }
    }

    return $sorted_menu_objects;
}
