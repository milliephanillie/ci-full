<?php
global $opt_name;

Redux::set_section( $opt_name, [
	'title'  => __( 'Theme Setup', 'lisfinity-core' ),
	'id'     => 'overall-settings',
	'desc'   => __( 'Overall settings used for adjusting the general parts of the theme.', 'lisfinity-core' ),
	'icon'   => 'fa fa-home',
	'fields' => [
		[
			'id'       => '_site-direction',
			'type'     => 'select',
			'title'    => __( 'Site Direction', 'lisfinity-core' ),
			'desc'     => __( 'Choose the site direction between LTR and RTL.', 'lisfinity-core' ),
			'subtitle' => __( 'Sets the direction of the site', 'lisfinity-core' ),
			'options'  => [
				'ltr'       => esc_html__( 'LTR', 'lisfinity-core' ),
				'rtl'       => esc_html__( 'RTL', 'lisfinity-core' ),
				'rtl_front' => esc_html__( 'RTL Only Front', 'lisfinity-core' ),
			],
			'default'  => 'ltr',
			'select2'  => [
				'allowClear' => false,
			]
		],
		[
			'id'       => '_site-literation',
			'type'     => 'select',
			'title'    => __( 'Site Literation', 'lisfinity-core' ),
			'desc'     => __( 'Set to cyrillic if you are planning to use it so the fields builder can work correctly.', 'lisfinity-core' ),
			'subtitle' => __( 'Set the literation of the site', 'lisfinity-core' ),
			'options'  => [
				'latin'    => esc_html__( 'Latin', 'lisfinity-core' ),
				'cyrillic' => esc_html__( 'Cyrillic', 'lisfinity-core' ),
				'other'    => esc_html__( 'Other', 'lisfinity-core' ),
			],
			'default'  => 'latin',
			'select2'  => [
				'allowClear' => false,
			]
		],
		[
			'id'       => '_site-font',
			'type'     => 'select',
			'title'    => __( 'Site Font', 'lisfinity-core' ),
			'desc'     => __( 'Choose the font of the website. Default font is first on the list.', 'lisfinity-core' ),
			'subtitle' => __( 'Site font family', 'lisfinity-core' ),
			'options'  => call_user_func_array( 'lisfinity_google_fonts', [ true ] ),
			'default'  => 'custom',
			'select2'  => [
				'allowClear' => false,
			]
		],
		[
			'id'       => '_format-time',
			'type'     => 'select',
			'title'    => __( 'Time Format', 'lisfinity-core' ),
			'desc'     => __( 'Choose the time format you wish to use', 'lisfinity-core' ),
			'subtitle' => __( 'Site time format', 'lisfinity-core' ),
			'options'  => [
				'24'   => esc_html__( '24 Hour Format', 'lisfinity-core' ),
				'ampm' => esc_html__( 'AM/PM Format', 'lisfinity-core' ),
			],
			'default'  => '24',
			'select2'  => [
				'allowClear' => false,
			]
		],
		[
			'id'       => '_use-ordinal',
			'type'     => 'select',
			'title'    => __( 'Use Ordinal Suffixes', 'lisfinity-core' ),
			'desc'     => __( 'Choose if you wish to use ordinal suffixes like in English: 1st, 2nd or just a dot after ordinal number.', 'lisfinity-core' ),
			'subtitle' => __( 'Suffixes after number', 'lisfinity-core' ),
			'options'  => [
				'yes' => esc_html__( 'Yes', 'lisfinity-core' ),
				'no'  => esc_html__( 'No', 'lisfinity-core' ),
			],
			'default'  => 'yes',
			'select2'  => [
				'allowClear' => false,
			]
		],
		[
			'id'         => '_site-media-limit',
			'type'       => 'text',
			'title'      => __( 'Maximum upload size', 'lisfinity-core' ),
			'desc'       => sprintf( __( 'Limit the size of images and files that can be uploaded to your site where maximum allowed amount is %s defined in your php.ini file. Field value is a representation of MegaBytes and it will not have an effect on the site administrators.', 'lisfinity-core' ), @ini_get( 'upload_max_filesize' ) ),
			'subtitle'   => __( 'Sets media upload limit', 'lisfinity-core' ),
			'attributes' => [
				'type' => 'number',
				'min'  => 0.2,
				'max'  => (int) call_user_func( 'lisfinity_format_upload_size_for_settings' ),
				'step' => 0.1,
			],
			'default'    => 2,
		],
	]
] );

// Logo options.
Redux::set_section( $opt_name, [
	'title'      => esc_html__( 'Logo', 'lisfinity-core' ),
	'id'         => 'logo-settings',
	'desc'       => esc_html__( 'Settings that are used to setup and adjust the logo for the theme', 'lisfinity-core' ),
	'icon'       => 'fa fa-circle',
	'subsection' => true,
	'fields'     => [
		[
			'id'       => '_identity-logo-title',
			'type'     => 'switch',
			'title'    => esc_html__( 'Display Logo and Title', 'lisfinity-core' ),
			'subtitle' => esc_html__( 'Choose if you wish to display both logo and the site title', 'lisfinity-core' ),
			'default'  => false,
		],
		[
			'id'       => '_identity-logo',
			'type'     => 'media',
			'title'    => esc_html__( 'Main Logo', 'lisfinity-core' ),
			'desc'     => esc_html__( 'Visible to all visitors (format: jpg, png), (recommended size: 200x40)', 'lisfinity-core' ),
			'subtitle' => esc_html__( 'Main logo of the site', 'lisfinity-core' ),
		],
		[
			'id'       => '_identity-logo-admin',
			'type'     => 'media',
			'title'    => esc_html__( 'Dashboard Logo', 'lisfinity-core' ),
			'desc'     => esc_html__( 'Visible to members in dashboard (format: jpt, png), (recommended size: 200x40)', 'lisfinity-core' ),
			'subtitle' => esc_html__( 'Logo in the user dashboard', 'lisfinity-core' ),
		],
		[
			'id'         => '_identity-logo-size',
			'type'       => 'text',
			'title'      => __( 'Logo Size (Width)', 'lisfinity-core' ),
			'desc'       => esc_html__( 'Set the fixed width of the logo', 'lisfinity-core' ),
			'subtitle'   => __( 'Maximum logo width', 'lisfinity-core' ),
			'attributes' => [
				'type' => 'number',
				'min'  => 0,
				'max'  => 1000,
				'step' => 1,
			],
			'default'    => 120,
		],
		[
			'id'          => '_identity-logo-padding',
			'type'        => 'text',
			'title'       => esc_html__( 'Logo Padding', 'lisfinity-core' ),
			'desc'        => esc_html__( 'Reposition logo using padding to match the header. Values are in this order: top - right - bottom - left. Accepted formats are: 10px or 10em or 10rem or 10%', 'lisfinity-core' ),
			'subtitle'    => esc_html__( 'Reposition logo in the header', 'lisfinity-core' ),
			'placeholder' => '0px 0px 0px 0px',
			'default'     => '0px 0px 0px 0px',
		],
	]
] );


// Field Builders options.
Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Field Builders', 'lisfinity-core' ),
		'id'         => 'field-builders-settings',
		'desc'       => esc_html__( 'Set up the field builders on the site.', 'lisfinity-core' ),
		'icon'       => 'fa fa-pie-chart',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_site-fields-builder',
				'type'     => 'switch',
				'title'    => __( 'Enable Fields Builder', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to disable fields builder once you are done creating it.', 'lisfinity-core' ),
				'subtitle' => __( 'Fields builder enabler', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'      => '_site-fields-builder-ui',
				'type'    => 'switch',
				'title'   => __( 'Show Taxonomies on Edit Product Screen', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to show custom taxonomies on edit product screen in wp-admin area. Disabling this will make that page run much faster and the product taxonomies would be editable only from the listing submission form.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_site-fields-builder-premium-type',
				'type'     => 'select',
				'title'    => __( 'Choose How to Restrict Premium Fields', 'lisfinity-core' ),
				'desc'     => __( 'Fields builder fields can be restricted for premium users or for members of the site depending on this option.', 'lisfinity-core' ),
				'subtitle' => __( 'Who can see restricted fields?', 'lisfinity-core' ),
				'options'  => [
					'premium' => __( 'Premium (Purchased Premium Package)', 'lisfinity-core' ),
					'members' => __( 'Members (Logged in Users)', 'lisfinity-core' ),
				],
				'select2'  => [
					'allowClear' => false,
				],
				'default'  => 'premium',
			],
			[
				'id'    => '_restricted-modal-image',
				'type'  => 'media',
				'title' => __( 'Restricted Fields Modal Image', 'lisfinity-core' ),
				'desc'  => __( 'The image that is used on the modal when it is displayed on restricted categories, taxonomy, terms, listings.', 'lisfinity-core' ),
			],
			[
				'id'       => '_site-fields-builder-terms-limit',
				'type'     => 'text',
				'title'    => __( 'Limit Terms Per Page', 'lisfinity-core' ),
				'desc'     => __( 'Limit number of terms that will be displayed per page in the fields builder', 'lisfinity-core' ),
				'subtitle' => __( 'Fields builder terms per page', 'lisfinity-core' ),
				'default'  => 30,
			],
			[
				'id'       => '_site-search-builder',
				'type'     => 'switch',
				'title'    => __( 'Enable Search Builder', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to disable search builder once you are done creating it.', 'lisfinity-core' ),
				'subtitle' => __( 'Search builder enabler', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'      => '_empty-category',
				'type'    => 'switch',
				'title'   => __( 'Empty Category by default', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish category to be empty by default in the listing submission form.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_hide-empty-terms',
				'type'     => 'switch',
				'title'    => __( 'Hide Empty Fields', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to hide empty fields in the search forms on the site.', 'lisfinity-core' ),
				'subtitle' => __( 'Hide terms that do not have any attached listings', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'      => '_hide-child-categories',
				'type'    => 'switch',
				'title'   => __( 'Hide Child Categories', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to hide child categories while their parent is not yet selected', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);
// Theme Functionality options.
Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Theme Functionality', 'lisfinity-core' ),
		'id'         => 'theme-functionality-settings',
		'desc'       => esc_html__( 'Set up the field builders on the site.', 'lisfinity-core' ),
		'icon'       => 'fa fa-cubes',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_site-restrict-listings',
				'type'     => 'select',
				'options'  => [
					'no'      => __( 'No restrictions', 'lisfinity-core' ),
					'members' => __( 'Restricted to site members', 'lisfinity-core' ),
					'premium' => __( 'Restricted to premium users', 'lisfinity-core' ),
				],
				'title'    => __( 'Restrict Listings', 'lisfinity-core' ),
				'desc'     => __( 'Show listings only to premium users or members of the site. If the user is not logged in or has not purchased the premium package he/she will not be able to see the listings.', 'lisfinity-core' ),
				'subtitle' => __( 'Restrict listing to logged/premium users', 'lisfinity-core' ),
				'default'  => 'no',
			],
			[
				'id'       => '_site-detailed-search',
				'type'     => 'switch',
				'title'    => __( 'Detailed Search', 'lisfinity-core' ),
				'desc'     => __( 'Enable the use of detailed search page', 'lisfinity-core' ),
				'subtitle' => __( 'Search page detailed search option', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'       => '_site-packages',
				'type'     => 'select',
				'title'    => __( 'Enable Pricing Packages', 'lisfinity-core' ),
				'desc'     => __( 'Choose what type of user would have to buy a package in order to submit a listing. Only "Disabled" option will completely disable the packages.', 'lisfinity-core' ),
				'subtitle' => __( 'Pricing packages enabler', 'lisfinity-core' ),
				'options'  => [
					'enabled'  => __( 'Enabled', 'lisfinity-core' ),
					'personal' => __( 'Personal Accounts Only', 'lisfinity-core' ),
					'business' => __( 'Business Accounts Only', 'lisfinity-core' ),
					'disabled' => __( 'Disabled', 'lisfinity-core' ),
				],
				'default'  => 'enabled',
				'select2'  => [
					'allowClear' => false,
				],
			],
			[
				'id'       => '_site-promotions',
				'type'     => 'switch',
				'title'    => __( 'Enable Promotions', 'lisfinity-core' ),
				'desc'     => __( 'Enable the use of ad promotions on the site', 'lisfinity-core' ),
				'subtitle' => __( 'Ad promotions enabler', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'       => '_site-premium-profiles',
				'type'     => 'switch',
				'title'    => __( 'Enable Premium Profiles', 'lisfinity-core' ),
				'desc'     => __( 'Enable the use of premium profiles', 'lisfinity-core' ),
				'subtitle' => __( 'Premium Profiles promotions enabler', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'       => '_site-disable-bidding',
				'type'     => 'switch',
				'title'    => __( 'Enable Bidding', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to enable the bids.', 'lisfinity-core' ),
				'subtitle' => __( 'Bidding enabler', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'       => '_site-hide-bidding',
				'type'     => 'switch',
				'title'    => __( 'Hide Bidding', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to hide the bids from the other bidders.', 'lisfinity-core' ),
				'subtitle' => __( 'Hide bidding from the other bidders', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'       => '_site-bidding-description',
				'type'     => 'switch',
				'title'    => __( 'Enable Bidding Description', 'lisfinity-core' ),
				'subtitle' => __( 'Choose if you wish to add textarea field besides bidding price', 'lisfinity-core' ),
				'default'  => false,
			],
		],
	]
);

// Permalinks options.
Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Slugs', 'lisfinity-core' ),
		'id'         => 'permalinks-settings',
		'desc'       => esc_html__( 'Set up the field builders on the site.', 'lisfinity-core' ),
		'icon'       => 'fa fa-map-signs',
		'subsection' => true,
		'fields'     => [
			[
				'id'    => 'permalinks-info',
				'type'  => 'info',
				'title' => esc_html__( 'Slugs & Permalinks Information', 'lisfinity-core' ),
				'desc'  => call_user_func_array( 'lisfinity_slugs_help_html', [ true ] ),
				'style' => 'info',
			],
			[
				'id'          => '_slug-product',
				'type'        => 'text',
				'title'       => esc_html__( 'Ads Slug', 'lisfinity-core' ),
				'desc'        => esc_html__( 'Set the ads slug if you want to differentiate it from the WooCommerce products, otherwise it should be empty.', 'lisfinity-core' ),
				'default'     => lisfinity_get_product_slug(),
				'placeholder' => esc_html__( 'ad', 'lisfinity-core' ),
			],
			[
				'id'          => '_slug-category',
				'type'        => 'text',
				'title'       => esc_html__( 'Ad Categories Slug', 'lisfinity-core' ),
				'desc'        => esc_html__( 'Set the slug for the custom categories created in the fields builder', 'lisfinity-core' ),
				'default'     => 'ad-category',
				'placeholder' => esc_html__( 'ad-category', 'lisfinity-core' ),
			],
			[
				'id'          => '_slug-business',
				'type'        => 'text',
				'title'       => esc_html__( 'Premium Profiles Slug', 'lisfinity-core' ),
				'desc'        => esc_html__( 'Set the slug for the premium profiles', 'lisfinity-core' ),
				'default'     => \Lisfinity\Models\Users\ProfilesModel::$post_type_name,
				'placeholder' => esc_html__( 'premium_profile', 'lisfinity-core' ),
			],
			[
				'id'      => '_permalink-category',
				'type'    => 'select',
				'title'   => esc_html__( 'Ad Categories Permalink', 'lisfinity-core' ),
				'desc'    => esc_html__( 'Choose what page will open when a user click on the category created in the fields builder. Shortcodes can be set individually in Elementor.', 'lisfinity-core' ),
				'options' => [
					'default' => __( 'Category Page', 'lisfinity-core' ),
					'search'  => __( 'Search Page', 'lisfinity-core' )
				],
				'default' => 'default',
				'select2' => [
					'allowClear' => false,
				],
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Membership', 'lisfinity-core' ),
		'id'         => 'membership-settings',
		'desc'       => esc_html__( 'Manage the site membership settings', 'lisfinity-core' ),
		'icon'       => 'fa fa-users',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_members_listings_details',
				'type'    => 'switch',
				'title'   => esc_html__( 'Manage listing and business information visibility', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_membership-name',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Advertiser Name', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the advertiser name to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-phone',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Advertiser Phone Number', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the advertiser phone number to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-address',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Advertiser Address', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the advertiser address to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-safety-tips',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Advertiser Safety Tips', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the advertiser safety tips to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-specification',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Single Listing Specification', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the single listing specification to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-description',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Single Listing Description', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the single listing description to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-listings-visits',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Single Listing Number of the Visits', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the single listing number of the visits to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
			[
				'id'       => '_membership-listings-bids',
				'type'     => 'select',
				'title'    => esc_html__( 'Show Single Listing Auction Details', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose when to show the single listing auction details to users.', 'lisfinity-core' ),
				'options'  => [
					'always'    => __( 'Always', 'lisfinity-core' ),
					'never'     => __( 'Never', 'lisfinity-core' ),
					'logged_in' => __( 'Only to logged in users', 'lisfinity-core' )
				],
				'default'  => 'always',
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_members_listings_details', '=', '1' ]
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Vendors Setup', 'lisfinity-core' ),
		'id'         => 'vendors-settings',
		'desc'       => esc_html__( 'Manage the various settings for the vendors', 'lisfinity-core' ),
		'icon'       => 'fa fa-shopping-bag',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_vendors-enabled',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Vendors?', 'lisfinity-core' ),
				'subtitle' => esc_html__( 'Become a marketplace', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Choose if you wish to enable vendors and direct selling of their products on your site.', 'lisfinity-core' ),
				'default'  => true,
			],
			[
				'id'       => '_site-vendor-approval',
				'type'     => 'switch',
				'title'    => __( 'Seller Approval', 'lisfinity-core' ),
				'desc'     => __( 'Enable vendor approval', 'lisfinity-core' ),
				'subtitle' => __( 'If switched on then every seller would have to be manually approved before they can post the ads.', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'       => '_send-details',
				'type'     => 'switch',
				'title'    => esc_html__( 'Arrange a Visit Instead of Buy Now', 'lisfinity-core' ),
				'subtitle' => esc_html__( 'Switch buy now button to arrange a visit', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Buy now button on the listing single page will be switched with arrange a visit and when a user clicks on it it will send details of the user to the listing owner.', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'       => '_vendors-only',
				'type'     => 'switch',
				'title'    => esc_html__( 'Vendors Only Site?', 'lisfinity-core' ),
				'subtitle' => esc_html__( 'Every listing must be sold through the site', 'lisfinity-core' ),
				'desc'     => esc_html__( 'Enabling this option will force the listing owners to sell every listing directly through your site.', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'         => '_vendors-site-commission',
				'type'       => 'text',
				'title'      => esc_html__( 'Site percentage', 'lisfinity-core' ),
				'desc'       => esc_html__( 'Type the percentage your site earns from every sold listing.', 'lisfinity-core' ),
				'default'    => 10,
				'required'   => [ '_vendors-enabled', '=', '1' ],
				'attributes' => [
					'type' => 'number',
					'min'  => 0,
					'max'  => 100,
				],
			],
			[
				'id'       => '_vendors-payouts-info',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Payouts Information', 'lisfinity-core' ),
				'desc'     => esc_html__( 'The text in the earnings section of the user dashboard where you can explain when the payments are sent etc. Put the word in brackets [] in order to display it as bold.', 'lisfinity-core' ),
				'default'  => __( 'The site will gather {10%} from each booking sale that is made on one of your listings. {PayPal fees for sending payouts are not included in the tax.} Payouts are sent once per day except on the weekends.', 'lisfinity-core' ),
				'required' => [ '_vendors-enabled', '=', '1' ],
			],
		],
	]
);

// Demo Settings.
if ( false !== strpos( get_site_url(), 'lisfinity' ) ) {
	Redux::set_field( $opt_name, 'overall-settings', [
		'id'      => '_site-mode',
		'type'    => 'switch',
		'title'   => __( 'Is Demo', 'lisfinity-core' ),
		'desc'    => __( 'Choose the site mode', 'lisfinity-core' ),
		'default' => false,
	] );
	Redux::set_field( $opt_name, 'overall-settings', [
		'id'      => '_demo-product-example',
		'type'    => 'select',
		'title'   => __( 'Demo Product', 'lisfinity-core' ),
		'desc'    => __( 'Choose the demo product that will be used as example', 'lisfinity-core' ),
		'data'    => 'posts',
		'args'    => [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
		],
		'default' => '',
	] );
}

// Intervals Settings.
Redux::set_section( $opt_name, [
		'title'      => esc_html__( 'Intervals', 'lisfinity-core' ),
		'id'         => 'intervals-settings',
		'desc'       => esc_html__( 'Set up the various intervals on the site.', 'lisfinity-core' ),
		'icon'       => 'fa fa-clock',
		'subsection' => true,
		'fields'     => [
			[
				'id'    => '_site-refresh-notifications',
				'type'  => 'switch',
				'title' => __( 'Auto Refresh Notifications', 'lisfinity-core' ),
				'desc'  => __( 'Choose if you wish that the notifications are automatically refreshed', 'lisfinity-core' ),
			],
			[
				'id'         => '_site-refresh-notifications-interval',
				'type'       => 'text',
				'title'      => __( 'Refresh Notifications Interval', 'lisfinity-core' ),
				'subtitle'   => __( 'Interval is based in seconds', 'lisfinity-core' ),
				'desc'       => __( 'Choose the interval upon which the notifications would be refreshed', 'lisfinity-core' ),
				'attributes' => [
					'min'  => 3,
					'max'  => 10000,
					'type' => 'number',
				],
				'default'    => 10,
				'required'   => [ '_site-refresh-notifications', '=', '1' ],
			],
			[
				'id'    => '_site-new-products-notifications',
				'type'  => 'switch',
				'title' => __( 'New Listings Notification', 'lisfinity-core' ),
				'desc'  => __( 'Choose if you wish that the notifications of new listings are send to subscribed users', 'lisfinity-core' ),
				'default' => '0'
			],
			[
				'id'         => '_site-saved-search-interval',
				'type'       => 'text',
				'title'      => __( 'Saved Search Emails Interval', 'lisfinity-core' ),
				'subtitle'   => __( 'Number of hours between email notifications', 'lisfinity-core' ),
				'desc'       => __( 'Choose how many hours should pass when the next email notification is sent with new listings in saved search query', 'lisfinity-core' ),
				'attributes' => [
					'min'  => 1,
					'type' => 'number',
				],
				'default'    => 22,
				'required'   => [ '_site-new-products-notifications', '=', '1' ],
			],
		],
	]
);
