<?php
global $opt_name;
$taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
Redux::set_section( $opt_name, [
		'title'  => __( 'Footer Setup', 'lisfinity-core' ),
		'id'     => 'footer-settings',
		'desc'   => __( 'Setting to adjust various footer options', 'lisfinity-core' ),
		'icon'   => 'fa fa-address-book',
		'fields' => [
			[
				'id'         => '_footer-type',
				'type'       => 'select',
				'title'      => __( 'Footer Type', 'lisfinity-core' ),
				'desc'       => __( 'Choose the type of the footer you wish to use.', 'lisfinity-core' ),
				'options'    => [
					'default' => __( 'Default', 'lisfinity-core' ),
					'custom'  => __( 'Custom (Elementor)', 'lisfinity-core' ),
				],
				'default'    => 'default',
				'select2'    => [
					'allowClear' => false,
				],
				'customizer' => true,
			],
			[
				'id'       => '_footer-post',
				'type'     => 'select',
				'title'    => __( 'Footer Post', 'lisfinity-core' ),
				'desc'     => __( 'Choose the footer you wish to display from the list of created ones.', 'lisfinity-core' ),
				'options'  => lisfinity_get_post_type_select( 'lisfinity_footer' ),
				'default'  => '',
				'required' => [ '_footer-type', '=', 'custom' ],
			],
			[
				'id'          => '_footer-email',
				'type'        => 'text',
				'title'       => __( 'Footer Email Address', 'lisfinity-core' ),
				'desc'        => __( 'Ad an email address that will be displayed in the footer', 'lisfinity-core' ),
				'default'     => get_option( 'admin_email' ),
				'placeholder' => get_option( 'admin_email' ),
				'required' => [ '_footer-type', '=', 'default' ],
			],
			[
				'id'    => '_footer-phone',
				'type'  => 'text',
				'title' => __( 'Footer Phone Number', 'lisfinity-core' ),
				'desc'  => __( 'Ad a phone number that will be displayed in the footer.', 'lisfinity-core' ),
				'required' => [ '_footer-type', '=', 'default' ],
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Social Networks', 'lisfinity-core' ),
		'id'         => 'footer-social-settings',
		'desc'       => __( 'Setting to adjust various footer social networks', 'lisfinity-core' ),
		'icon'       => 'fa fa-share',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_footer-social-text',
				'type'    => 'text',
				'title'   => __( 'Footer Social Icons Title', 'lisfinity-core' ),
				'desc'    => __( 'Enter the text that will be displayed just before social icons.', 'lisfinity-core' ),
				'default' => __( 'FOLLOW US', 'lisfinity-core' ),
				'required' => [ '_footer-type', '=', 'default' ],
			],
			[
				'id'      => '_footer-social-icons',
				'type'    => 'select',
				'title'   => __( 'Mobile Menu Social Icons', 'lisfinity-core' ),
				'desc'    => __( 'Set the social icons you wish to display in the mobile menu', 'lisfinity-core' ),
				'options' => lisfinity_get_available_social_networks(),
				'default' => [],
				'multi'   => true,
				'required' => [ '_footer-type', '=', 'default' ],
			],
			[
				'id'          => '_footer-social-facebook',
				'type'        => 'text',
				'title'       => __( 'Facebook URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your facebook account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://facebook.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'facebook' ],
			],
			[
				'id'          => '_footer-social-twitter',
				'type'        => 'text',
				'title'       => __( 'Twitter URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your twitter account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://twitter.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'twitter' ],
			],
			[
				'id'          => '_footer-social-instagram',
				'type'        => 'text',
				'title'       => __( 'Instagram URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your instagram account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://instagram.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'instagram' ],
			],
			[
				'id'          => '_footer-social-dribbble',
				'type'        => 'text',
				'title'       => __( 'Dribbble URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your dribbble account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://dribbble.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'dribbble' ],
			],
			[
				'id'          => '_footer-social-linkedin',
				'type'        => 'text',
				'title'       => __( 'Linkedin URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your linkedin account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://linkedin.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'linkedin' ],
			],
			[
				'id'          => '_footer-social-youtube',
				'type'        => 'text',
				'title'       => __( 'YouTube URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your youtube account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://youtube.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'youtube' ],
			],
			[
				'id'          => '_footer-social-reddit',
				'type'        => 'text',
				'title'       => __( 'Reddit URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your reddit account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://reddit.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'reddit' ],
			],
			[
				'id'          => '_footer-social-pinterest',
				'type'        => 'text',
				'title'       => __( 'Pinterest URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your pinterest account.', 'lisfinity-core' ),
				'placeholder' => esc_html( 'https://pinterest.com/lisfinity' ),
				'required'    => [ '_footer-social-icons', 'contains', 'pinterest' ],
			],
			[
				'id'          => '_footer-social-medium',
				'type'        => 'text',
				'title'       => __( 'Medium URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your medium account.', 'lisfinity-core' ),
				'placeholder' => 'https://medium.com/lisfinity',
				'required'    => [ '_footer-social-icons', 'contains', 'medium' ],
			],
			[
				'id'          => '_footer-social-vk',
				'type'        => 'text',
				'title'       => __( 'VKontakte URL', 'lisfinity-core' ),
				'desc'        => __( 'Enter the link to your vk account.', 'lisfinity-core' ),
				'placeholder' => 'https://vk.com/lisfinity',
				'required'    => [ '_footer-social-icons', 'contains', 'vk' ],
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Copyrights', 'lisfinity-core' ),
		'id'         => 'footer-copyrights-settings',
		'desc'       => __( 'Setting to adjust copyrights displayed in the footer', 'lisfinity-core' ),
		'icon'       => 'fa fa-copyright',
		'subsection' => true,
		'fields'     => [
			[
				'id'          => '_footer-copyrights',
				'type'        => 'text',
				'title'       => __( 'Copyrights', 'lisfinity-core' ),
				'desc'        => __( 'Enter text for the site copyrights. Leave empty to disable section.', 'lisfinity-core' ),
				'placeholder' => 'Made with 💙 by pebas - Copyrights 2020. All rights reserved.',
				'required' => [ '_footer-type', '=', 'default' ],
			],
		],
	]
);
