<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Theme Colors', 'lisfinity-core' ),
		'id'     => 'color-settings',
		'desc'   => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'   => 'fa fa-braille',
		'fields' => [
			// primary button colors.
			[
				'id'       => '_header-menu-action',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Action Close Icon Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the main menu action actions like: notifications, compare and cart icons.', 'lisfinity-core' ),
				'default'  => '#199473',
			],
			[
				'id'     => 'colors-primary-button-background',
				'type'   => 'info',
				'title'  => __( 'Primary Button Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the themes primary buttons', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_primary-button-background',
				'type'     => 'color',
				'title'    => __( 'Primary Button: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color of the primary buttons.', 'lisfinity-core' ),
				'default'  => '#0967D2',
			],
			[
				'id'       => '_primary-button-background-hover',
				'type'     => 'color',
				'title'    => __( 'Primary Button: Background Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color on hover of the primary buttons.', 'lisfinity-core' ),
				'default'  => '#03449e',
			],
			[
				'id'       => '_primary-button-color',
				'type'     => 'color',
				'title'    => __( 'Primary Button: Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the text color of the primary buttons.', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_primary-button-color-hover',
				'type'     => 'color',
				'title'    => __( 'Primary Button: Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the text color of the primary buttons.', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			// secondary button colors.
			[
				'id'     => 'colors-secondary-button-background',
				'type'   => 'info',
				'title'  => __( 'Secondary Button Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the themes secondary buttons', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_secondary-button-background',
				'type'     => 'color',
				'title'    => __( 'Secondary Button: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color of the secondary buttons.', 'lisfinity-core' ),
				'default'  => '#3ebd93',
			],
			[
				'id'       => '_secondary-button-background-hover',
				'type'     => 'color',
				'title'    => __( 'Secondary Button: Background Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color on hover of the secondary buttons.', 'lisfinity-core' ),
				'default'  => '#199473',
			],
			[
				'id'       => '_secondary-button-color',
				'type'     => 'color',
				'title'    => __( 'Secondary Button: Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the text color of the secondary buttons.', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_secondary-button-color-hover',
				'type'     => 'color',
				'title'    => __( 'Secondary Button: Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the text color of the secondary buttons.', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
		],
	]
);

// header colors.
Redux::set_section( $opt_name, [
		'title'      => __( 'Header Colors', 'lisfinity-core' ),
		'id'         => 'color-header-settings',
		'desc'       => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-list-alt',
		'subsection' => true,
		'fields'     => [
			// header background colors.
			[
				'id'     => 'colors-header-background',
				'type'   => 'info',
				'title'  => __( 'Header Background Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the header background', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_header-home-bg',
				'type'     => 'color_rgba',
				'title'    => __( 'Header Homepage: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color of the homepage header.', 'lisfinity-core' ),
				'default'  => [
					'color' => '#1c1c1c',
					'alpha' => 0,
				],
			],
			[
				'id'       => '_header-bg',
				'type'     => 'color_rgba',
				'title'    => __( 'Header Inner Pages: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the background color of the header on pages other than homepage.', 'lisfinity-core' ),
				'default'  => [
					'color' => '#1c1c1c',
					'alpha' => 1,
				],
			],
			// header background colors.
			[
				'id'     => 'colors-header-menu-background',
				'type'   => 'info',
				'title'  => __( 'Header Menu Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the header menu', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_header-menu-text-color',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu text color', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-menu-text-color-hover',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu text color', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-menu-dropdown',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Dropdown Background', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu dropdown background color', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-menu-dropdown-hover',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Dropdown Color Background on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu dropdown background color on hover', 'lisfinity-core' ),
				'default'  => '#efefef',
			],
			[
				'id'       => '_header-menu-dropdown-color',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Dropdown Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu dropdown text color', 'lisfinity-core' ),
				'default'  => '#4c4c4c',
			],
			[
				'id'       => '_header-menu-dropdown-color-hover',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Dropdown Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu dropdown text color on hover', 'lisfinity-core' ),
				'default'  => '#4c4c4c',
			],
			[
				'id'     => 'colors-header-submit-button',
				'type'   => 'info',
				'title'  => __( 'Submit Button Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the submit button in the menu', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_button-submit-bg',
				'type'     => 'color_rgba',
				'title'    => __( 'Header Menu: Submit Button Background', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu submit button background color', 'lisfinity-core' ),
				'default'  => [
					'color' => '#4c4c4c',
					'alpha' => .7
				],
			],
			[
				'id'       => '_button-submit-bg-hover',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Submit Button Background on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu submit button background color on hover', 'lisfinity-core' ),
				'default'  => '#0967d2',
			],
			[
				'id'       => '_button-submit-text-color',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Submit Button Text', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu submit text background color', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_button-submit-text-color-hover',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Submit Button Text on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu submit text background color on hover', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
		],
	]
);

// header colors on mobiles
Redux::set_section( $opt_name, [
		'title'      => __( 'Header Mobile Colors', 'lisfinity-core' ),
		'id'         => 'color-header-mobile-settings',
		'desc'       => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-list-alt',
		'subsection' => true,
		'fields'     => [
			// header mobile colors.
			[
				'id'     => 'colors-header-mobile-background',
				'type'   => 'info',
				'title'  => __( 'Header Colors on Mobiles', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the header background on mobiles', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_header-menu-mobile-bg',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Mobile Background', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu background color on mobiles', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-menu-mobile-close',
				'type'     => 'color',
				'title'    => __( 'Header Menu: Mobile Close Icon Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose header menu close icon color on mobiles', 'lisfinity-core' ),
				'default'  => '#000000',
			],
			// header mobile menu colors.
			[
				'id'     => 'colors-header-mobile-menu-background',
				'type'   => 'info',
				'title'  => __( 'Header Menu Colors on Mobiles', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the header menu on mobiles', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_mobile-menu-bg',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu background', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_mobile-menu-close',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Close Icon', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu closing icon', 'lisfinity-core' ),
				'default'  => '#262626',
			],
			[
				'id'       => '_mobile-menu-open',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Open Icon (Hamburger Icon)', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu open icon', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_mobile-menu-items',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Menu Items', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu items', 'lisfinity-core' ),
				'default'  => '#4c4c4c',
			],
			[
				'id'       => '_mobile-menu-dropdown-icon-bg',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Dropdown Icon Background', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu dropdown icon background', 'lisfinity-core' ),
				'default'  => '#efefef',
			],
			[
				'id'       => '_mobile-menu-dropdown-icon',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Dropdown Icon', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu dropdown icon', 'lisfinity-core' ),
				'default'  => '#262626',
			],
			[
				'id'     => 'colors-header-mobile-submit-button',
				'type'   => 'info',
				'title'  => __( 'Submit Button Colors', 'lisfinity-core' ),
				'desc'   => __( 'Set the colors of the mobile submit button in the menu', 'lisfinity-core' ),
				'style'  => 'normal',
				'notice' => false,
			],
			[
				'id'       => '_mobile-menu-submit',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Submit Button Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu submit button background', 'lisfinity-core' ),
				'default'  => '#0967d2',
			],
			[
				'id'       => '_mobile-menu-social-color',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Social Icons Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu social icons and text', 'lisfinity-core' ),
				'default'  => '#7a8593',
			],
			[
				'id'       => '_mobile-menu-action',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Action Icons Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu action icons like notifications, cart etc.', 'lisfinity-core' ),
				'default'  => '#199473',
			],
			[
				'id'       => '_mobile-menu-border',
				'type'     => 'color',
				'title'    => __( 'Mobile Menu: Border Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the mobile menu border', 'lisfinity-core' ),
				'default'  => '#f6f6f6',
			],
		],
	]
);

// header colors on mobiles
Redux::set_section( $opt_name, [
		'title'      => __( 'Header Fields Colors', 'lisfinity-core' ),
		'id'         => 'color-header-fields-settings',
		'desc'       => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-list-alt',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_header-fields-bg',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields background', 'lisfinity-core' ),
				'default'  => 'transparent',
			],
			[
				'id'       => '_header-fields-text',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields text', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-fields-icon',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Icon Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields icon', 'lisfinity-core' ),
				'default'  => '#e12d39',
			],
			[
				'id'       => '_header-fields-dropdown',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Dropdown Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields dropdown', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_header-fields-dropdown-hover',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Dropdown Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields dropdown on hover', 'lisfinity-core' ),
				'default'  => '#efefef',
			],
			[
				'id'       => '_header-fields-dropdown-text',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Dropdown Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields dropdown text', 'lisfinity-core' ),
				'default'  => '#2d2d2d',
			],
			[
				'id'       => '_header-fields-dropdown-text-hover',
				'type'     => 'color',
				'title'    => __( 'Header Fields: Dropdown Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the header fields dropdown text on hover', 'lisfinity-core' ),
				'default'  => '#2d2d2d',
			],
		],
	]
);

// footer colors on mobiles
Redux::set_section( $opt_name, [
		'title'      => __( 'Footer Colors', 'lisfinity-core' ),
		'id'         => 'color-footer-settings',
		'desc'       => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-list-alt',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_footer-bg',
				'type'     => 'color',
				'title'    => __( 'Footer: Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer background', 'lisfinity-core' ),
				'default'  => '#262626',
			],
			[
				'id'       => '_footer-text-color',
				'type'     => 'color',
				'title'    => __( 'Footer: Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer text', 'lisfinity-core' ),
				'default'  => '#959595',
			],
			[
				'id'       => '_footer-text-color-hover',
				'type'     => 'color',
				'title'    => __( 'Footer: Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer text on hover', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_footer-share-color',
				'type'     => 'color',
				'title'    => __( 'Footer: Share Icons Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer share icons', 'lisfinity-core' ),
				'default'  => '#5e5e5e',
			],
			[
				'id'       => '_footer-share-color-hover',
				'type'     => 'color',
				'title'    => __( 'Footer: Share Icons Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer share icons on hover', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_footer-copyrights-bg',
				'type'     => 'color',
				'title'    => __( 'Footer: Copyrights Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer copyrights background color', 'lisfinity-core' ),
				'default'  => '#262626',
			],
			[
				'id'       => '_footer-copyrights-text',
				'type'     => 'color',
				'title'    => __( 'Footer: Copyrights Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the footer copyrights text color', 'lisfinity-core' ),
				'default'  => '#959595',
			],
		],
	]
);

// hero colors
Redux::set_section( $opt_name, [
		'title'      => __( 'Home Hero Colors', 'lisfinity-core' ),
		'id'         => 'color-hero-settings',
		'desc'       => __( 'Setting to adjust various colors for the theme.', 'lisfinity-core' ),
		'icon'       => 'fa fa-list-alt',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_home-fields-bg',
				'type'     => 'color',
				'title'    => __( 'Hero: Fields Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields background', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_home-fields-text',
				'type'     => 'color',
				'title'    => __( 'Hero: Fields Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields text', 'lisfinity-core' ),
				'default'  => '#2d2d2d',
			],
			[
				'id'       => '_home-fields-dropdown',
				'type'     => 'color',
				'title'    => __( 'Hero: Dropdown Background Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields dropdown background', 'lisfinity-core' ),
				'default'  => '#ffffff',
			],
			[
				'id'       => '_home-fields-dropdown-hover',
				'type'     => 'color',
				'title'    => __( 'Hero: Dropdown Background Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields dropdown background on hover', 'lisfinity-core' ),
				'default'  => '#efefef',
			],
			[
				'id'       => '_home-fields-dropdown-text',
				'type'     => 'color',
				'title'    => __( 'Hero: Dropdown Text Color', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields dropdown text', 'lisfinity-core' ),
				'default'  => '#2d2d2d',
			],
			[
				'id'       => '_home-fields-dropdown-text-hover',
				'type'     => 'color',
				'title'    => __( 'Hero: Dropdown Text Color on Mouseover', 'lisfinity-core' ),
				'desc'     => __( 'Choose the color for the homepage hero section fields dropdown text on hover', 'lisfinity-core' ),
				'default'  => '#2d2d2d',
			],
		],
	]
);
