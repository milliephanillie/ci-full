<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Messenger Setup', 'lisfinity-core' ),
		'id'     => 'messenger-settings',
		'desc'   => __( 'Setting to adjust various options for the theme messenger.', 'lisfinity-core' ),
		'icon'   => 'fa fa-commenting',
		'fields' => [
			[
				'id'      => '_messenger',
				'type'    => 'switch',
				'title'   => __( 'Messenger', 'lisfinity-core' ),
				'desc'    => __( 'Allow direct messages between the advertisers and the buyer.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_messenger-limit',
				'type'     => 'text',
				'title'    => __( 'Messenger Chars', 'lisfinity-core' ),
				'desc'     => __( 'Type the allowed number of characters in a message.', 'lisfinity-core' ),
				'default'  => '300',
				'required' => [ '_messenger', '=', '1' ],
			],
			[
				'id'          => '_messenger-note',
				'type'        => 'text',
				'title'       => __( 'Messenger Disclaimer', 'lisfinity-core' ),
				'desc'        => __( 'Type disclaimer for members connected to messenger. Type <strong>FAQ</strong> to display a link to the actual faq page.', 'lisfinity-core' ),
				'placeholder' => __( 'You can be banned for violent messages.', 'lisfinity-core' ),
				'default'     => __( 'You can be banned for violent messages.', 'lisfinity-core' ),
				'required'    => [ '_messenger', '=', '1' ],
			],
			[
				'id'          => '_messenger-note-translation',
				'type'        => 'text',
				'title'       => __( 'Messenger Disclaimer FAQ Translation', 'lisfinity-core' ),
				'desc'        => __( 'Type the FAQ word in your language of choice as that is how the link name will be displayed to the users.', 'lisfinity-core' ),
				'placeholder' => __( 'FAQ', 'lisfinity-core' ),
				'default'     => __( 'FAQ', 'lisfinity-core' ),
				'required'    => [ '_messenger', '=', '1' ],
			],
		],
	]
);
