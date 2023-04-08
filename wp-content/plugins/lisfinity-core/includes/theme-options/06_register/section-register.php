<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Register Setup', 'lisfinity-core' ),
		'id'     => 'register-settings',
		'desc'   => __( 'Setting to adjust various options used for the user registration on site', 'lisfinity-core' ),
		'icon'   => 'fa fa-user-plus',
		'fields' => [
			[
				'id'      => '_auth-enabled',
				'type'    => 'switch',
				'title'   => __( 'Allow User Registration?', 'lisfinity-core' ),
				'desc'    => __( 'Enable users to register on your site so they can become members, submit ads and so on.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_auth-default-account-type',
				'type'    => 'select',
				'title'   => __( 'Default Account Type After Registration', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether newly registered users will have seller, business or buyer account type. Users can upgrade their account at any time.', 'lisfinity-core' ),
				'options' => [
					'personal' => __( 'Seller', 'lisfinity-core' ),
					'business' => __( 'Vendor', 'lisfinity-core' ),
					'buyer'    => __( 'Buyer', 'lisfinity-core' ),
				],
				'default' => 'personal',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_all-businesses-premium',
				'type'    => 'switch',
				'title'   => __( 'All Businesses Premium', 'lisfinity-core' ),
				'desc'    => __( 'Force all businesses to be premium accounts by default with no expiration date.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_premium-page-banner-bg',
				'type'     => 'media',
				'title'    => __( 'Fallback Banner Image', 'lisfinity-core' ),
				'desc'     => __( 'Set the fallback banner image for the premium page banner.', 'lisfinity-core' ),
				'required' => [ '_all-businesses-premium', '=', true ],
			],
			[
				'id'          => '_auth-business-name',
				'type'        => 'text',
				'title'       => __( 'Default Business Title', 'lisfinity-core' ),
				'desc'        => __( 'Choose default name of the Business Profile that is assigned to a newly registered user. "%s" sign will be changed with the name of the user. This is not relevant for the regular (not business) profiles.', 'lisfinity-core' ),
				'default'     => 'Business: %s',
				'placeholder' => 'Business: %s',
			],
			[
				'id'       => '_auth-default-packages',
				'type'     => 'select',
				'title'    => __( 'Assign Default Pricing Package Upon Registration', 'lisfinity-core' ),
				'desc'     => __( 'Choose default pricing packages that should be assign to the newly registered users. Leave empty if none should be assigned.', 'lisfinity-core' ),
				'default'  => '',
				'required' => [ '_auth-enabled', '=', '1' ],
				'data'     => 'posts',
				'args'     => [
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'tax_query'      => [
						[
							'taxonomy' => 'product_type',
							'field'    => 'name',
							'terms'    => 'payment_package',
							'operator' => 'IN',
						],
					],
				],
				'multi'    => true,
			],
			[
				'id'      => '_custom-logout',
				'type'    => 'switch',
				'title'   => __( 'Custom Logout Redirection', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to set up custom page where the users will be redirected after logging out of the site.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_custom-logout-url',
				'type'     => 'text',
				'title'    => __( 'Custom Logout Redirection URL', 'lisfinity-core' ),
				'desc'     => __( 'Choose whether you wish to set up custom page where the users will be redirected after logging out of the site.', 'lisfinity-core' ),
				'default'  => get_home_url( '/' ),
				'required' => [ '_custom-logout', '=', '1' ],
			],
		],
	]
);

// verification.
Redux::set_section( $opt_name, [
		'title'      => __( 'User Verification', 'lisfinity-core' ),
		'id'         => 'register-verification-settings',
		'desc'       => __( 'Setting to adjust various options used for the user verification', 'lisfinity-core' ),
		'icon'       => 'fa fa-address-card',
		'subsection' => true,
		'fields'     => [
			[
				'id'    => 'auth-verification-info',
				'type'  => 'info',
				'title' => esc_html__( 'User Verification Explained', 'lisfinity-core' ),
				'desc'  => call_user_func_array( 'lisfinity_verification_help_html', [ true ] ),
				'style' => 'info',
			],
			[
				'id'      => '_auth-verification',
				'type'    => 'switch',
				'title'   => __( 'Allow User Verification?', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to allow user verification on the site. If enabled, by default users are being verified by email but you can set up SMS verification instead.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_auth-verification-sms',
				'type'    => 'switch',
				'title'   => __( 'Use SMS Verification?', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to use Twillio SMS Verification', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_auth-verification-sms-sid',
				'type'     => 'text',
				'title'    => __( 'Twillio Account SID', 'lisfinity-core' ),
				'desc'     => __( 'Enter your Twilio retrieved from: <a href="https://www.twilio.com/try-twilio" target="_blank">Twilio Registration</a>.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_auth-verification-sms', '=', '1' ],
			],
			[
				'id'       => '_auth-verification-sms-token',
				'type'     => 'text',
				'title'    => __( 'Twillio Auth Token', 'lisfinity-core' ),
				'desc'     => __( 'Enter your Twilio retrieved from: <a href="https://www.twilio.com/try-twilio" target="_blank">Twilio Registration</a>.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_auth-verification-sms', '=', '1' ],
			],
			[
				'id'          => '_auth-verification-sms-number',
				'type'        => 'text',
				'title'       => __( 'Twillio Phone Number', 'lisfinity-core' ),
				'desc'        => __( 'A Twilio number you own with SMS capabilities. Make sure that this number is the one connected on your Twilio account.', 'lisfinity-core' ),
				'default'     => false,
				'placeholder' => '+15017122661',
				'required'    => [ '_auth-verification-sms', '=', '1' ],
			],
		],
	]
);

// reCaptcha.
Redux::set_section( $opt_name, [
		'title'      => __( 'reCaptcha', 'lisfinity-core' ),
		'id'         => 'register-recaptcha-settings',
		'desc'       => __( 'Setting to adjust various options used for the user reCaptcha verification', 'lisfinity-core' ),
		'icon'       => 'fa fa-repeat',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_auth-captcha-enabled',
				'type'    => 'switch',
				'title'   => esc_html__( '', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to enable Google\'s reCAPTCHA field to prevent bots registrations.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_auth-captcha-label',
				'type'     => 'text',
				'title'    => esc_html__( 'Google reCAPTCHA Label', 'lisfinity-core' ),
				'desc'     => __( 'Enter your desired Google\'s reCAPTCHA label.', 'lisfinity-core' ),
				'default'  => __( 'Are you human?', 'lisfinity-core' ),
				'required' => [ '_auth-captcha-enabled', '=', '1' ],
			],
			[
				'id'       => '_auth-captcha-site-key',
				'type'     => 'text',
				'title'    => esc_html__( 'Google reCAPTCHA Site Key', 'lisfinity-core' ),
				'desc'     => __( 'Enter your site key retrieved from: <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google\'s reCAPTCHA admin dashboard</a>.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_auth-captcha-enabled', '=', '1' ],
			],
			[
				'id'       => '_auth-captcha-secret-key',
				'type'     => 'text',
				'title'    => esc_html__( 'Google reCAPTCHA Secret Key', 'lisfinity-core' ),
				'desc'     => __( 'Enter your site key retrieved from: <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google\'s reCAPTCHA admin dashboard</a>.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_auth-captcha-enabled', '=', '1' ],
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Additional Fields', 'lisfinity-core' ),
		'id'         => 'register-fields-settings',
		'desc'       => __( 'Setting to adjust additional fields', 'lisfinity-core' ),
		'icon'       => 'fa fa-plus',
		'subsection' => true,
		'fields'     => [
			[
				'id'       => '_choose-profile-register-form',
				'type'     => 'checkbox',
				'title'    => __( 'Choose Profile Field: Register Form', 'lisfinity-core' ),
				'subtitle' => __( 'Select if you want to include the profile selection field in the register form', 'lisfinity-core' ),
				'default'  => false,
				'label'    => __( 'Include the choose profile field in the register form', 'lisfinity-core' ),
			],
			[
				'id'       => '_first-name-register-form',
				'type'     => 'checkbox',
				'title'    => __( 'First Name Field: Register Form', 'lisfinity-core' ),
				'subtitle' => __( 'Select if you want to include the first name field in the register form', 'lisfinity-core' ),
				'default'  => false,
				'label'    => __( 'Include the first name in the register form', 'lisfinity-core' ),
			],
			[
				'id'       => '_last-name-register-form',
				'type'     => 'checkbox',
				'title'    => __( 'Last Name Field: Register Form', 'lisfinity-core' ),
				'subtitle' => __( 'Select if you want to include the last name field in the register form', 'lisfinity-core' ),
				'default'  => false,
				'label'    => __( 'Include the last name in the register form', 'lisfinity-core' ),
			],
			[
				'id'       => '_phone-number-register-form',
				'type'     => 'checkbox',
				'title'    => __( 'Phone Number Field: Register Form', 'lisfinity-core' ),
				'subtitle' => __( 'Select if you want to include the phone number in the register form', 'lisfinity-core' ),
				'default'  => false,
				'label'    => __( 'Include the phone number in the register form', 'lisfinity-core' ),
			],
			[
				'id'       => '_phone-number-listing-submission-form',
				'type'     => 'checkbox',
				'title'    => __( 'Phone Number Field: Listing Submission Form', 'lisfinity-core' ),
				'label'    => __( 'Include the phone number in the listing submission form', 'lisfinity-core' ),
				'default'  => false,
				'subtitle' => __( 'Select if you want to include the phone number in the listing submission form', 'lisfinity-core' ),
			],
			[
				'id'       => '_website-register-form',
				'type'     => 'checkbox',
				'title'    => __( 'Website Link Field: Register Form', 'lisfinity-core' ),
				'label'    => __( 'Include the website in the register form', 'lisfinity-core' ),
				'default'  => false,
				'subtitle' => __( 'Select if you want to include the link to the website field in the register form', 'lisfinity-core' ),
			],
			[
				'id'       => '_website-listing-submission-form',
				'type'     => 'checkbox',
				'title'    => __( 'Website Link Field: Listing Submission Form', 'lisfinity-core' ),
				'label'    => __( 'Include the website in the listing submission form', 'lisfinity-core' ),
				'default'  => false,
				'subtitle' => __( 'Select if you want to include the link to the website field in the listing submission form', 'lisfinity-core' ),
			],
			[
				'id'       => '_email-listing-submission-form',
				'type'     => 'checkbox',
				'title'    => __( 'Email Field Field: Listing Submission Form', 'lisfinity-core' ),
				'label'    => __( 'Include the email field in the listing submission form', 'lisfinity-core' ),
				'default'  => false,
				'subtitle' => __( 'Select if you want to include the email field in the listing submission form', 'lisfinity-core' ),
			],
		],
	]
);
