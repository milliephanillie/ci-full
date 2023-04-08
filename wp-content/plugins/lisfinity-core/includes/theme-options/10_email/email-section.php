<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Emails Setup', 'lisfinity-core' ),
		'id'     => 'email-settings',
		'desc'   => __( 'Setting to adjust various options for the theme emails.', 'lisfinity-core' ),
		'icon'   => 'fa fa-envelope',
		'fields' => [
			[
				'id'          => '_email-from-address',
				'type'        => 'text',
				'title'       => __( 'Email Sent From', 'lisfinity-core' ),
				'desc'        => __( 'Type the email address that will be displayed on the outgoing emails', 'lisfinity-core' ),
				'placeholder' => esc_html( get_option( 'admin_email' ) ),
				'default'     => esc_html( get_option( 'admin_email' ) ),
				'attribute'   => [
					'type' => 'email',
				],
			],
			[
				'id'          => '_email-from-name',
				'type'        => 'text',
				'title'       => __( 'Email Sent From Name', 'lisfinity-core' ),
				'desc'        => __( 'Type the name that will be displayed on the outgoing emails', 'lisfinity-core' ),
				'placeholder' => esc_html( get_option( 'blogname' ) ),
				'default'     => esc_html( get_option( 'blogname' ) ),
				'attribute'   => [
					'type' => 'email',
				],
			],
			[
				'id'          => '_email-ad-expires',
				'type'        => 'text',
				'title'       => __( 'Ad Expiration', 'lisfinity-core' ),
				'desc'        => __( 'How many days before an ad expires you would like to notify members?', 'lisfinity-core' ),
				'placeholder' => '1',
				'default'     => '1',
			],
			[
				'id'          => '_email-promotion-expires',
				'type'        => 'text',
				'title'       => __( 'Promotion Expiration', 'lisfinity-core' ),
				'desc'        => __( 'How many days before a promotion expires you would like to notify members?', 'lisfinity-core' ),
				'placeholder' => '1',
				'default'     => '1',
			],
			[
				'id'      => '_email-subscriptions-by-default',
				'type'    => 'switch',
				'title'   => __( 'Send user subscriptions by default', 'lisfinity-core' ),
				'desc'    => __( 'When a new user is registered his/her account will be set to receive all emails by default', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_email-listing-submitted',
				'type'    => 'switch',
				'title'   => __( 'Listing Submitted', 'lisfinity-core' ),
				'desc'    => __( 'Send an email notification when a new listing has been submitted', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_email-listing-edited',
				'type'    => 'switch',
				'title'   => __( 'Listing Edited', 'lisfinity-core' ),
				'desc'    => __( 'Send an email notification when a new listing has been edited', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_email-new-message',
				'type'    => 'switch',
				'title'   => __( 'Message Emails', 'lisfinity-core' ),
				'desc'    => __( 'Send an email notification for advertiser/buyer when someone sends to them.', 'lisfinity-core' ),
				'default' => true,
			],
		],
	]
);
