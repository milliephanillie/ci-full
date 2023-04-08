<?php
/**
 * Merlin WP configuration file.
 *
 * @package   Merlin WP
 * @version   @@pkg.version
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin(

	$config = array(
		'directory'            => 'includes/merlin',
		// Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'lisfinity-install',
		// The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php',
		// The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options',
		// The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes',
		// URL for the 'child-action-link'.
		'dev_mode'             => true,
		// Enable development mode for testing.
		'license_step'         => false,
		// EDD license activation step.
		'license_required'     => false,
		// Require the license activation step.
		'license_help_url'     => '',
		// URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '',
		// EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '',
		// EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '',
		// EDD_Theme_Updater_Admin item_slug.
		'ready_big_button_url' => get_site_url( '/' ),
		// Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'          => esc_html__( 'Theme Setup', 'lisfinity' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'       => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'lisfinity' ),
		'return-to-dashboard' => esc_html__( 'Return to the dashboard', 'lisfinity' ),
		'ignore'              => esc_html__( 'Disable this wizard', 'lisfinity' ),

		'btn-skip'                 => esc_html__( 'Skip', 'lisfinity' ),
		'btn-next'                 => esc_html__( 'Next', 'lisfinity' ),
		'btn-start'                => esc_html__( 'Start', 'lisfinity' ),
		'btn-no'                   => esc_html__( 'Cancel', 'lisfinity' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'lisfinity' ),
		'btn-child-install'        => esc_html__( 'Install', 'lisfinity' ),
		'btn-content-install'      => esc_html__( 'Install', 'lisfinity' ),
		'btn-import'               => esc_html__( 'Import', 'lisfinity' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'lisfinity' ),
		'btn-license-skip'         => esc_html__( 'Later', 'lisfinity' ),

		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'lisfinity' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'lisfinity' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'lisfinity' ),
		'license-label'            => esc_html__( 'License key', 'lisfinity' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'lisfinity' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'lisfinity' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'lisfinity' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'lisfinity' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'lisfinity' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content.', 'lisfinity' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'lisfinity' ),

		'child-header'         => esc_html__( 'Install Child Theme', 'lisfinity' ),
		'child-header-success' => esc_html__( 'You\'re good to go!', 'lisfinity' ),
		'child'                => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'lisfinity' ),
		'child-success%s'      => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'lisfinity' ),
		'child-action-link'    => esc_html__( 'Learn about child themes', 'lisfinity' ),
		'child-json-success%s' => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'lisfinity' ),
		'child-json-already%s' => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'lisfinity' ),

		'plugins-header'         => esc_html__( 'Install Plugins', 'lisfinity' ),
		'plugins-header-success' => esc_html__( 'You\'re up to speed!', 'lisfinity' ),
		'plugins'                => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'lisfinity' ),
		'plugins-success%s'      => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'lisfinity' ),
		'plugins-action-link'    => esc_html__( 'Advanced', 'lisfinity' ),

		'import-header'      => esc_html__( 'Import Content', 'lisfinity' ),
		'import'             => esc_html__( 'Let\'s import content to your website, please choose:', 'lisfinity' ),
		'import-action-link' => esc_html__( 'Advanced', 'lisfinity' ),

		'ready-header'      => esc_html__( 'All done. Have fun!', 'lisfinity' ),

		/* translators: Theme Author */
		'ready%s'           => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'lisfinity' ),
		'ready-action-link' => esc_html__( 'Extras', 'lisfinity' ),
		'ready-big-button'  => esc_html__( 'View your website', 'lisfinity' ),
		'ready-link-1'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://support.pebas.rs', esc_html__( 'Get Theme Support', 'lisfinity' ) ),
		'ready-link-2'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://app.gitbook.com/@pebas/s/lisfinity-documentation/', esc_html__( 'Theme Documentation', 'lisfinity' ) ),
		//'ready-link-3'      => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'lisfinity' ) ),
	)
);

