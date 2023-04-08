<?php

if ( ! function_exists( 'lisfinity_store_premium_taxonomies' ) ) {
	/**
	 * Store premium taxonomies
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_store_premium_taxonomies( $data ) {
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $data['slug'] ) || 'yes' !== $data['premium'] || in_array( $data['slug'], $premiums ) ) {
			return false;
		}
		if ( empty( $premiums ) || ! is_array( $premiums ) ) {
			$premiums = [];
		}
		$premiums[] = $data['slug'];

		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__taxonomy_updated', 'lisfinity_store_premium_taxonomies' );
	add_action( 'lisfinity__group_updated', 'lisfinity_store_premium_taxonomies' );
}

if ( ! function_exists( 'lisfinity_store_premium_terms' ) ) {
	/**
	 * Store premium taxonomies
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_store_premium_terms( $data ) {
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $data['slug'] ) || 'yes' !== $data['meta']['premium'] || in_array( "{$data['taxonomy']}|{$data['slug']}", $premiums ) ) {
			return false;
		}
		if ( empty( $premiums ) || ! is_array( $premiums ) ) {
			$premiums = [];
		}
		$premiums[] = "{$data['taxonomy']}|{$data['slug']}";

		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__term_updated', 'lisfinity_store_premium_terms' );
}

if ( ! function_exists( 'lisfinity_remove_premium_taxonomy' ) ) {
	/**
	 * Remove taxonomies from the list of premiums
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_remove_premium_taxonomy( $data ) {
		if ( empty( $data['slug'] ) || 'yes' === $data['premium'] ) {
			return false;
		}
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $premiums ) || ! is_array( $premiums ) || ! in_array( $data['slug'], $premiums ) ) {
			return false;
		}

		$key = array_search( $data['slug'], $premiums );
		array_splice( $premiums, $key, 1 );
		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__taxonomy_updated', 'lisfinity_remove_premium_taxonomy' );
	add_action( 'lisfinity__group_updated', 'lisfinity_remove_premium_taxonomy' );
}

if ( ! function_exists( 'lisfinity_remove_premium_term' ) ) {
	/**
	 * Remove taxonomies from the list of premiums
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_remove_premium_term( $data ) {
		if ( empty( $data['slug'] ) || 'yes' === $data['meta']['premium'] ) {
			return false;
		}
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $premiums ) || ! is_array( $premiums ) || ! in_array( "{$data['taxonomy']}|{$data['slug']}", $premiums ) ) {
			return false;
		}

		$key = array_search( "{$data['taxonomy']}|{$data['slug']}", $premiums );
		array_splice( $premiums, $key, 1 );
		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__term_updated', 'lisfinity_remove_premium_term' );
}

if ( ! function_exists( 'lisfinity_remove_premium_taxonomy_deleted' ) ) {
	/**
	 * Remove taxonomies from the list of premiums
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_remove_premium_taxonomy_deleted( $data ) {
		if ( empty( $data['slug'] ) ) {
			return false;
		}
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $premiums ) || ! is_array( $premiums ) || ! in_array( $data['slug'], $premiums ) ) {
			return false;
		}

		$key = array_search( $data['slug'], $premiums );
		array_splice( $premiums, $key, 1 );
		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__taxonomy_deleted', 'lisfinity_remove_premium_taxonomy_deleted' );
	add_action( 'lisfinity__group_deleted', 'lisfinity_remove_premium_taxonomy_deleted' );
}

if ( ! function_exists( 'lisfinity_remove_premium_term_deleted' ) ) {
	/**
	 * Remove taxonomies from the list of premiums
	 *
	 * @param $data
	 *
	 * @return false
	 */
	function lisfinity_remove_premium_term_deleted( $data ) {
		if ( empty( $data['slug'] ) ) {
			return false;
		}
		$premiums = get_option( 'lisfinity__premium_taxonomies' );
		if ( empty( $premiums ) || ! is_array( $premiums ) || ! in_array( "{$data['taxonomy']}|{$data['slug']}", $premiums ) ) {
			return false;
		}

		$key = array_search( "{$data['taxonomy']}|{$data['slug']}", $premiums );
		array_splice( $premiums, $key, 1 );
		update_option( 'lisfinity__premium_taxonomies', $premiums );
	}

	add_action( 'lisfinity__term_deleted', 'lisfinity_remove_premium_term_deleted' );
}
