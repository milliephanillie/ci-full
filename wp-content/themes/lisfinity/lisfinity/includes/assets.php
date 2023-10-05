<?php
/**
 * Load all theme assets.
 *
 * @package Pebas
 */

if ( ! function_exists( 'lisfinity_enqueue_assets' ) ) {
	/**
	 * Enqueueing theme scripts and styles
	 * -----------------------------------
	 */
	function lisfinity_enqueue_assets() {
		// enqueue scripts.
		$language = get_locale();
		$language = explode( '_', $language );

		/**
		 * Enqueue the built-in comment-reply script for singular pages.
		 */
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( ! lisfinity_is_core_active() ) {
			// enqueue scripts.
			wp_enqueue_script( 'lisfinity-script-theme', PBS_THEME_URL . 'dist/scripts/theme.js', [ 'jquery' ], PBS_THEME_VERSION, true );

			// enqueue styles.
			wp_enqueue_style( 'lisfinity-main-style', PBS_THEME_URL . "dist/styles/main.css", '', PBS_THEME_VERSION, 'all' );
		}
		wp_enqueue_style( 'lisfinity-style', PBS_THEME_URL . '/style.css', '', PBS_THEME_VERSION, 'all' );
        // added by Philip
        wp_enqueue_style( 'lisfinity-custom-style', PBS_THEME_URL . "custom/dist/main.css", '', false, 'all' );
        wp_enqueue_script( 'lisfinity-custom-script', PBS_THEME_URL . 'custom/dist/main.js', [ 'jquery' ], PBS_THEME_VERSION, true );

		wp_localize_script(
			'lisfinity-script-theme',
			'l_data',
			[
				'is_user_logged_in' => is_user_logged_in(),
			]
		);
	}
}

if ( ! function_exists( 'lisfinity_admin_enqueue_assets' ) ) {
	/**
	 * Enqueueing theme scripts and styles
	 * -----------------------------------
	 */
	function lisfinity_admin_enqueue_assets() {
	}
}
