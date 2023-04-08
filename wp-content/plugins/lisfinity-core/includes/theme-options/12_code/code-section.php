<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Additional Code', 'lisfinity-core' ),
		'id'     => 'code-settings',
		'desc'   => __( 'Setting to add any additional code to the theme.', 'lisfinity-core' ),
		'icon'   => 'fa fa-code',
		'fields' => [
			[
				'id'    => '_code-css',
				'type'  => 'ace_editor',
				'mode'  => 'css',
				'title' => __( 'Additional CSS', 'lisfinity-core' ),
				'desc'  => __( 'ANy additional css code can be added here or in Appearance -> Customizer -> Custom CSS', 'lisfinity-core' ),
			],
			[
				'id'    => '_code-js',
				'type'  => 'ace_editor',
				'mode'  => 'javascript',
				'title' => __( 'Additional JS', 'lisfinity-core' ),
				'desc'  => __( 'ANy additional js code can be added here', 'lisfinity-core' ),
			],
			[
				'id'    => '_code-analytics',
				'type'  => 'ace_editor',
				'mode'  => 'javascript',
				'title' => __( 'Google Analytics JS', 'lisfinity-core' ),
				'desc'  => __( 'Google analytics javascript code can be added here. Remove the code if some third-party plugin is used for the analytics to avoid conflicts.', 'lisfinity-core' ),
			],
		],
	]
);
