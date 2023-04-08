<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Hero Setup', 'lisfinity-core' ),
		'id'     => 'hero-settings',
		'desc'   => __( 'Setting to adjust various hero section on the homepage options', 'lisfinity-core' ),
		'icon'   => 'fa fa-image',
		'fields' => [
			[
				'id'       => '_hero-disable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Disable Hero Section', 'lisfinity-core' ),
				'subtitle' => esc_html__( 'Choose if you wish to disable hero section', 'lisfinity-core' ),
				'default'  => false,
			],
			[
				'id'      => '_hero-type',
				'type'    => 'select',
				'title'   => __( 'Banner Type', 'lisfinity-core' ),
				'desc'    => __( 'Choose what you wish to use as the background for the home hero section.', 'lisfinity-core' ),
				'options' => [
					'image' => __( 'Image', 'lisfinity-core' ),
					'video' => __( 'Video', 'lisfinity-core' ),
				],
				'default' => 'image',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_home-banner-style',
				'type'    => 'select',
				'title'   => __( 'Banner Style', 'lisfinity-core' ),
				'desc'    => __( 'Choose different appearance for the home hero banner section', 'lisfinity-core' ),
				'options' => [
					'1' => __( 'Banner Style 1', 'lisfinity-core' ),
					'2' => __( 'Banner Style 2', 'lisfinity-core' ),
				],
				'default' => '1',
				'select2' => [
					'allowClear' => false,
				],
			],
			// image type.
			[
				'id'       => '_home-banner-bg',
				'type'     => 'media',
				'title'    => __( 'Background Image', 'lisfinity-core' ),
				'desc'     => __( 'Set the background image for the homepage banner.', 'lisfinity-core' ),
				'required' => [ '_hero-type', '=', 'image' ],
			],
			[
				'id'       => '_home-banner-bg-position-x',
				'type'     => 'select',
				'title'    => __( 'Background Image', 'lisfinity-core' ),
				'desc'     => __( 'Set the horizontal position of the background image.', 'lisfinity-core' ),
				'options'  => [
					'center' => __( 'Center', 'lisfinity-core' ),
					'left'   => __( 'Left', 'lisfinity-core' ),
					'right'  => __( 'Right', 'lisfinity-core' ),
				],
				'default'  => 'center',
				'required' => [ '_hero-type', '=', 'image' ],
				'select2'  => [
					'allowClear' => false,
				],
			],
			[
				'id'       => '_home-banner-bg-position-y',
				'type'     => 'select',
				'title'    => __( 'Background Image', 'lisfinity-core' ),
				'desc'     => __( 'Set the vertical position of the background image.', 'lisfinity-core' ),
				'options'  => [
					'center' => __( 'Center', 'lisfinity-core' ),
					'top'    => __( 'Top', 'lisfinity-core' ),
					'bottom' => __( 'Bottom', 'lisfinity-core' ),
				],
				'default'  => 'center',
				'required' => [ '_hero-type', '=', 'image' ],
				'select2'  => [
					'allowClear' => false,
				],
			],
			// video type.
			[
				'id'          => '_home-banner-video',
				'type'        => 'text',
				'title'       => __( 'Background Video', 'lisfinity-core' ),
				'desc'        => __( 'Enter the URL to the background video. YouTube, Vimeo...', 'lisfinity-core' ),
				'placeholder' => 'https://www.youtube.com/watch?v=gBZobrNm50k',
				'required'    => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'       => '_home-video-mobiles',
				'type'     => 'switch',
				'title'    => __( 'Display Video on Mobiles', 'lisfinity-core' ),
				'desc'     => __( 'Displaying the video on mobile phones can have costly effects on performance of the site.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'       => '_home-video-loop',
				'type'     => 'switch',
				'title'    => __( 'Loop Video', 'lisfinity-core' ),
				'desc'     => __( 'Choose whether you wish to repeat the video once it is done playing.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'       => '_home-video-starts',
				'type'     => 'text',
				'title'    => __( 'Video Start Time', 'lisfinity-core' ),
				'desc'     => __( 'Enter the video start time in seconds or leave empty to start from the beginning.', 'lisfinity-core' ),
				'default'  => '',
				'required' => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'       => '_home-video-ends',
				'type'     => 'text',
				'title'    => __( 'Video End Time', 'lisfinity-core' ),
				'desc'     => __( 'Enter the video end time in seconds or leave empty to end regularly.', 'lisfinity-core' ),
				'default'  => '',
				'required' => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'       => '_home-video-fallback',
				'type'     => 'media',
				'title'    => __( 'Video Fallback Image', 'lisfinity-core' ),
				'desc'     => __( 'Set the fallback image that will be displayed before the video starts on small screens.', 'lisfinity-core' ),
				'required' => [ '_hero-type', '=', 'video' ],
			],
			[
				'id'      => '_home-overlay',
				'type'    => 'color_rgba',
				'title'   => __( 'Banner Background Overlay', 'lisfinity-core' ),
				'desc'    => __( 'Choose the color for the overlay of the home banner background.', 'lisfinity-core' ),
				'default' => [
					'color' => '#000000',
					'alpha' => .7,
				],
			],
			[
				'id'      => '_home-banner-text',
				'type'    => 'editor',
				'title'   => __( 'Banner Text', 'lisfinity-core' ),
				'desc'    => __( 'Enter the text content will be displayed at the center of the homepage banner.', 'lisfinity-core' ),
				'default' => '<p style="text-align: center;">More than 63 ads in 5 categories</p><h1 style="text-align: center;">List or find anything, literally.</h1>',
				'args'    => [
					'teeny'         => true,
					'textarea_rows' => 10
				],
			],
			[
				'id'      => '_home-fields-style',
				'type'    => 'select',
				'title'   => __( 'Search Fields Style', 'lisfinity-core' ),
				'desc'    => __( 'Choose different appearance for the home hero search fields', 'lisfinity-core' ),
				'options' => [
					'1' => __( 'Fields Style 1', 'lisfinity-core' ),
					'2' => __( 'Fields Style 2', 'lisfinity-core' ),
				],
				'default' => '1',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_home-fields-wrapper-width',
				'type'    => 'select',
				'title'   => __( 'Fields Container Width', 'lisfinity-core' ),
				'desc'    => __( 'Set the width of the fields container', 'lisfinity-core' ),
				'options' => [
					'25'  => __( '25%', 'lisfinity-core' ),
					'50'  => __( '50%', 'lisfinity-core' ),
					'75'  => __( '75%', 'lisfinity-core' ),
					'100' => __( '100%', 'lisfinity-core' ),
				],
				'default' => '75',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_home-fields-columns',
				'type'    => 'text',
				'title'   => __( 'Fields Columns', 'lisfinity-core' ),
				'desc'    => __( 'Choose the number of column in which the fields should be broken', 'lisfinity-core' ),
				'default' => '1',
			],
			[
				'id'      => '_home-fields-padding',
				'type'    => 'text',
				'title'   => __( 'Fields Padding', 'lisfinity-core' ),
				'desc'    => __( 'Separate the fields by the given spacing value.', 'lisfinity-core' ),
				'default' => '4',
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Featured Categories', 'lisfinity-core' ),
		'id'         => 'hero-featured-settings',
		'desc'       => __( 'Setting to adjust various options for the featured categories displayed on the home banner.', 'lisfinity-core' ),
		'icon'       => 'fa fa-folder',
		'subsection' => true,
	]
);

// Add categories field.
Redux::set_field( $opt_name, 'hero-featured-settings', [
	'id'      => '_home-banner-taxonomies-color',
	'type'    => 'color',
	'title'   => __( 'Banner Featured Categories Text Color', 'lisfinity-core' ),
	'desc'    => __( 'Choose the color for the banner featured categories titles.', 'lisfinity-core' ),
	'default' => '#bcbcbc',
] );
Redux::set_field( $opt_name, 'hero-featured-settings', [
	'id'      => '_home-banner-taxonomies-icon-size',
	'type'    => 'text',
	'title'   => __( 'Banner Featured Categories Icons Size', 'lisfinity-core' ),
	'desc'    => __( 'Set the home banner featured categories icons size for the big screens.', 'lisfinity-core' ),
	'default' => '24',
] );
Redux::set_field( $opt_name, 'hero-featured-settings', [
	'id'      => '_home-banner-taxonomies-icon-size-mobile',
	'type'    => 'text',
	'title'   => __( 'Banner Featured Categories Icons Size Mobile', 'lisfinity-core' ),
	'desc'    => __( 'Set the home banner featured categories icons size for the small screens', 'lisfinity-core' ),
	'default' => '32',
] );
$model  = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
$groups = $model->get_groups_with_taxonomies();
if ( ! class_exists( 'Redux' ) ) {
	return;
}
if ( ! empty( $groups ) ) {
	$model  = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
	$groups = $model->get_groups_with_taxonomies();
	Redux::set_field( $opt_name, 'hero-featured-settings', [
		'id'       => '_home-banner-taxonomies',
		'type'     => 'select',
		'title'    => __( 'Banner Featured Categories', 'lisfinity-core' ),
		'desc'     => __( 'Choose the categories that you wish to display below search on a homepage banner.', 'lisfinity-core' ),
		'options'  => $model->format_options_for_select( true ),
		'multi'    => true,
		'sortable' => true,
	] );
} else {
	Redux::set_field( $opt_name, 'hero-featured-settings', [
		'id'       => '_home-banner-terms',
		'type'     => 'select',
		'title'    => __( 'Banner Featured Terms', 'lisfinity-core' ),
		'desc'     => __( 'Choose the terms from the Common group in the fields builder that you wish to display below search on a homepage banner.', 'lisfinity-core' ),
		'options'  => lisfinity_get_terms_by_taxonomy_select(),
		'multi'    => true,
		'sortable' => true,
	] );
}
