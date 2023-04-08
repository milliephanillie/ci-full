<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Pages Setup', 'lisfinity-core' ),
		'id'     => 'pages-settings',
		'desc'   => __( 'Setting to adjust various page options', 'lisfinity-core' ),
		'icon'   => 'fa fa-file',
		'fields' => [
			[
				'id'      => '_pages-title',
				'type'    => 'switch',
				'title'   => __( 'Page Title', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether the page title should be displayed on inner pages.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_pages-breadcrumbs',
				'type'    => 'switch',
				'title'   => __( 'Page Breadcrumbs', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether the breadcrumbs should be displayed on inner pages.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_custom-category-pages',
				'type'    => 'switch',
				'title'   => __( 'Custom Category Pages', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to create and use custom category page templates or you wish that the search page is used instead.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_to-custom-category',
				'type'    => 'switch',
				'title'   => __( 'Home Search Redirect', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether the homepage search should redirect to the custom category pages when possible', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Default Pages', 'lisfinity-core' ),
		'id'         => 'pages-default-settings',
		'desc'       => __( 'Setting to adjust default pages necessary for the proper working of the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-file-archive',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_page-home',
				'type'    => 'select',
				'title'   => __( 'Page: Home Page', 'lisfinity-core' ),
				'desc'    => __( 'Choose the homepage template that you wish to use as the main page of the site.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-single-listing',
				'type'    => 'select',
				'title'   => __( 'Page: Single Listing', 'lisfinity-core' ),
				'desc'    => __( 'Choose the single listing page template created in Elementor that will display listings uploaded by the users. Leave empty to use default design.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-register',
				'type'    => 'select',
				'title'   => __( 'Page: Register', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used for registering on the site.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-login',
				'type'    => 'select',
				'title'   => __( 'Page: Login', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used for login on the site.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-reset',
				'type'    => 'select',
				'title'   => __( 'Page: Password Reset', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used for passwords resetting.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-search',
				'type'    => 'select',
				'title'   => __( 'Page: Main Search', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used for ads searching.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-search-detailed',
				'type'    => 'select',
				'title'   => __( 'Page: Detailed Search', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used for the detailed ads searching.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-account',
				'type'    => 'select',
				'title'   => __( 'Page: User Account', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will be used as the user dashboard page.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-business',
				'type'    => 'select',
				'title'   => __( 'Page: Single Author', 'lisfinity-core' ),
				'desc'    => __( 'Choose the single listing page template created in Elementor that will display listing author. Leave empty to use default design.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-business-premium',
				'type'    => 'select',
				'title'   => __( 'Page: Single Author Premium', 'lisfinity-core' ),
				'desc'    => __( 'Choose the single listing page template created in Elementor that will display listing author. Leave empty to use default design.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-vendors',
				'type'    => 'select',
				'title'   => __( 'Page: All Authors', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will display all available advertisers on the site.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-terms',
				'type'    => 'select',
				'title'   => __( 'Page: Terms & Conditions', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will contain the terms & conditions of your site', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-privacy-policy',
				'type'    => 'select',
				'title'   => __( 'Page: Privacy Policy', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will contain the privacy policy of your site', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
			[
				'id'      => '_page-tips',
				'type'    => 'select',
				'title'   => __( 'Page: Safety Tips', 'lisfinity-core' ),
				'desc'    => __( 'Choose the page that will display safety tips for the buyers and advertisers. You can create them from the Safety Tips section.', 'lisfinity-core' ),
				'options' => lisfinity_get_all_pages(),
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Dashboard Pages', 'lisfinity-core' ),
		'id'         => 'pages-dashboard-settings',
		'desc'       => __( 'Setting to adjust various options for the pages used in user dashboard', 'lisfinity-core' ),
		'icon'       => 'fa fa-file-pdf',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_dashboard-account-billing',
				'type'    => 'switch',
				'title'   => __( 'Billing Details', 'lisfinity-core' ),
				'desc'    => __( 'Enable billing details page in the user dashboard', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_dashboard-account-shipping',
				'type'    => 'switch',
				'title'   => __( 'Shipping Details', 'lisfinity-core' ),
				'desc'    => __( 'Enable shipping details page in the user dashboard', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_dashboard-download-page',
				'type'    => 'switch',
				'title'   => __( 'Enable Downloads', 'lisfinity-core' ),
				'desc'    => __( 'Enable downloads page in the user dashboard', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_dashboard-bookmarks-page',
				'type'    => 'switch',
				'title'   => __( 'Enable Bookmarks Page', 'lisfinity-core' ),
				'desc'    => __( 'Enable bookmarks page in the user dashboard', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_dashboard-orders-page',
				'type'    => 'switch',
				'title'   => __( 'Enable Orders Page', 'lisfinity-core' ),
				'desc'    => __( 'Enable orders page in the user dashboard', 'lisfinity-core' ),
				'default' => true,
			],
		],
	]
);
