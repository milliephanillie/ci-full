<?php
global $opt_name;

Redux::set_section( $opt_name, [
		'title'  => __( 'Dashboard Setup', 'lisfinity-core' ),
		'id'     => 'dashboard-settings',
		'desc'   => __( 'User dashboard settings used for adjusting that part of the theme.', 'lisfinity-core' ),
		'icon'   => 'fa fa-address-card',
		'fields' => [
			[
				'id'      => '_widget-expiring-listings',
				'type'    => 'switch',
				'title'   => __( 'Widget Expiring Listings', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display expiring listings widget,', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_widget-expiring-promotions',
				'type'    => 'switch',
				'title'   => __( 'Widget Expiring Promotions', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display expiring promotions widget,', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_sorting-pricing-packages',
				'type'    => 'select',
				'title'   => __( 'Select the sorting value of the pricing packages', 'lisfinity-core' ),
				'desc'    => __( 'Select the value by which sorting of the pricing packages will be done.', 'lisfinity-core' ),
				'options' => lisfinity_sorting_payment_packages(),
				'default' => 'price',
			],
			[
				'id'      => '_enable-agents',
				'type'    => 'switch',
				'title'   => __( 'Enable Agents', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable usage of the agents', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_enable-user-verification',
				'type'    => 'switch',
				'title'   => __( 'Enable User Verification', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable documented user verification', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Business Setup', 'lisfinity-core' ),
		'id'         => 'business-settings',
		'desc'       => __( 'Setting to adjust various options used for the business profiles.', 'lisfinity-core' ),
		'icon'       => 'fa fa-suitcase',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_pay-for-premium',
				'type'    => 'switch',
				'title'   => __( 'Pay to become premium business profile', 'lisfinity-core' ),
				'desc'    => __( 'Enable if you wish that personal accounts have to pay in order to become businesses', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'    => '_banner-fallback-image',
				'type'  => 'media',
				'title' => __( 'Set the banner fallback image', 'lisfinity-core' ),
				'desc'  => __( 'Set the banner fallback image for the business profiles without banner images.', 'lisfinity-core' )
			],
			[
				'id'      => '_business-phone-enabled',
				'type'    => 'switch',
				'title'   => __( 'Enable Phone Number', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable phone number', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_business-phone-apps',
				'type'     => 'select',
				'title'    => __( 'Available Phone Apps', 'lisfinity-core' ),
				'desc'     => __( 'Phone number applications', 'lisfinity-core' ),
				'options'  => lisfinity_business_phone_apps(),
				'default'  => "",
				'multi'    => true,
				'required' => [ "_business-phone-enabled", '=', '1' ],
			],
			[
				'id'      => '_business-working-hours-enabled',
				'type'    => 'switch',
				'title'   => __( 'Enable Working Hours', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable working hours fields', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_business-telegram',
				'type'    => 'switch',
				'title'   => __( 'Enable Telegram Field', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable telegram field', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_business-social-networks',
				'type'    => 'select',
				'title'   => __( 'Available Social Networks', 'lisfinity-core' ),
				'desc'    => __( 'Social Network applications', 'lisfinity-core' ),
				'options' => lisfinity_business_social_networks(),
				'default' => lisfinity_business_social_networks(),
				'multi'   => true,
			],
		],
	]
);

Redux::set_section( $opt_name, [
	'title'      => __( 'WooCommerce', 'lisfinity-core' ),
	'id'         => 'woocommerce-settings',
	'desc'       => __( 'Setting to adjust the checkout fields.', 'lisfinity-core' ),
	'icon'       => 'fa fa-suitcase',
	'subsection' => true,
	'fields'     => [
		[
			'id'      => '_checkout-first-name',
			'type'    => 'switch',
			'title'   => __( 'Enable First Name', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the first name field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-last-name',
			'type'    => 'switch',
			'title'   => __( 'Enable Last Name', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the last name field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-company-name',
			'type'    => 'switch',
			'title'   => __( 'Enable Company Name', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the company name field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-country',
			'type'    => 'switch',
			'title'   => __( 'Enable Country/Region', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the country/region field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-street-address',
			'type'    => 'switch',
			'title'   => __( 'Enable Street Address', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the street address field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-street-address-two',
			'type'    => 'switch',
			'title'   => __( 'Enable Street Address 2', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the street address 2 field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-apartment',
			'type'    => 'switch',
			'title'   => __( 'Enable Apartment/Suite', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the apartment/suite field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-town',
			'type'    => 'switch',
			'title'   => __( 'Enable Town/City', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the town/city field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-state',
			'type'    => 'switch',
			'title'   => __( 'Enable State', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the state field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-zip',
			'type'    => 'switch',
			'title'   => __( 'Enable ZIP Code', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the ZIP code field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-phone',
			'type'    => 'switch',
			'title'   => __( 'Enable Phone', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the phone field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-email-address',
			'type'    => 'switch',
			'title'   => __( 'Enable Email Address', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the email address field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-order-notes',
			'type'    => 'switch',
			'title'   => __( 'Enable Order Notes', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the order notes field', 'lisfinity-core' ),
			'default' => true,
		],
		[
			'id'      => '_checkout-vat',
			'type'    => 'switch',
			'title'   => __( 'Enable VAT field', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the VAT field', 'lisfinity-core' ),
			'default' => false,
		],
		[
			'id'      => '_checkout-sdi-code',
			'type'    => 'switch',
			'title'   => __( 'Enable SDI Code field', 'lisfinity-core' ),
			'desc'    => __( 'Choose if you wish to enable the SDI Code field', 'lisfinity-core' ),
			'default' => false,
		],
	]
] );


