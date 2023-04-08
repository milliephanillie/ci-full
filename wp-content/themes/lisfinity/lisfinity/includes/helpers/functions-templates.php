<?php
/**
 * File for core functions used across the theme
 *
 * @author pebas
 * @package helpers
 * @version 1.0.0
 */

if ( ! function_exists( 'lt_get_var' ) ) {
	/**
	 * Check the existence of the give var
	 * -----------------------------------
	 *
	 * @param string $var - variable that should be
	 * checked.
	 * @param bool $return_var - should variable be returned
	 *   or just a boolean.
	 *
	 * @return bool
	 */
	function lt_get_var( $var, $return_var = false ) {
		if ( isset( $var ) && ! empty( $var ) ) {
			if ( $return_var ) {
				return $var;
			} else {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'lt_get_page_permalink' ) ) {
	/**
	 * Get page permalink
	 * ------------------
	 *
	 * @param string $page - Accepts the string used for finding out the page id and essentially
	 * returning a template for the given value.
	 *
	 * @return mixed
	 */
	function lt_get_page_permalink( $page ) {
		$page_id   = lt_get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();

		return apply_filters( 'lt_get_' . $page . '_page_permalink', $permalink );
	}
}

if ( ! function_exists( 'lt_get_page_id' ) ) {
	/**
	 * Get the id of the given page
	 * ----------------------------
	 *
	 * @param string $page - Returning the id for the given page.
	 *
	 * @return int
	 */
	function lt_get_page_id( $page ) {
		$page = get_page_by_path( $page );
		$page = $page ? $page->ID : '';
		$page = apply_filters( 'lt_get_' . $page . '_page_id', $page );

		return $page ? absint( $page ) : - 1;
	}
}

if ( ! function_exists( 'lt_get_template' ) ) {
	/**
	 * Get template
	 * ------------
	 *
	 * @param string $template - Template name that should be returned.
	 * to the user.
	 *
	 * @param array $args - Arguments that should be passed.
	 *
	 * @return string
	 */
	function lt_get_template( $template, $args = array() ) {
		return PBS_THEME . "templates/{$template}.php";
	}
}

if ( ! function_exists( 'lt_get_template_part' ) ) {
	/**
	 * Get template part
	 * -----------------
	 *
	 * @param string $template - Template name that should be returned.
	 * @param string $folder - Path to the folder that has the template.
	 * @param array $args - Additional arguments that should be passed to template part.
	 *
	 * @return string
	 */
	function lt_get_template_part( $template, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			//phpcs:ignore
			extract( $args );
		}

		if ( empty( $folder ) ) {
			$dir = PBS_THEME . "templates/{$template}.php";
		} else {
			$dir = PBS_THEME . "{$folder}/{$template}.php";
		}

		return apply_filters( 'lt__get_template_part', $dir );
	}
}

if ( ! function_exists( 'lisfinity_before_cart' ) ) {
	function lisfinity_before_cart() {
		?>
        <h1 class="page--title mb-20">
			<?php the_title(); ?>
        </h1>
		<?php
	}
}
add_action( 'woocommerce_before_cart', 'lisfinity_before_cart' );
