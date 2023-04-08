<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Location & Map Setup', 'lisfinity-core' ),
		'id'     => 'location-map-settings',
		'desc'   => __( 'Setting to adjust various options used for location and the map configuration', 'lisfinity-core' ),
		'icon'   => 'fa fa-map-marker',
		'fields' => [
			[
				'id'      => '_format-location',
				'type'    => 'select',
				'title'   => __( 'Choose Location Format', 'lisfinity-core' ),
				'desc'    => __( 'Choose the way location is being formatted. Display full format from the locations created in the Fields Builder or just a single Field like Country or City for example.', 'lisfinity-core' ),
				'options' => [
					'full'    => __( 'Full Location', 'lisfinity-core' ),
					'partial' => __( 'Only Last', 'lisfinity-core' ),
				],
				'default' => 'partial',
				'select2' => [
					'allowClear' => false,
				]
			],
/*			[
				'id'       => '_format-location-taxonomies',
				'type'     => 'select',
				'title'    => __( 'Choose Location Format', 'lisfinity-core' ),
				'desc'     => __( 'Choose the way location is being formatted. Display full format from the locations created in the Fields Builder or just a single Field like Country or City for example.', 'lisfinity-core' ),
				'options'  => lisfinity_format_location_taxonomies_in_select(),
				'default'  => [
					'city',
				],
				'multi'    => true,
				'sortable' => true,
				'select2'  => [
					'allowClear' => false,
				],
				'required' => [ '_format-location', '=', 'partial' ],
			],*/
			[
				'id'      => '_location-autogenerate',
				'type'    => 'switch',
				'title'   => __( 'Auto Generate Location (Google API Required)', 'lisfinity-core' ),
				'desc'    => __( 'Auto generate latitude and longitude when submitting ads or editing business profile. (Needs a Google API key)', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_map-api',
				'type'     => 'text',
				'title'    => __( 'Google API key', 'lisfinity-core' ),
				'desc'     => __( 'Enter your google map api key. <a href="https://www.youtube.com/watch?v=9ImLCQBj9SE" target="_blank" style="text-decoration: underline;">How to create Google API key</a>', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_location-autogenerate', '=', '1' ],
			],
			[
				'id'       => '_map-country-restriction',
				'type'     => 'select',
				'title'    => __( 'Country Restriction', 'lisfinity-core' ),
				'desc'     => __( 'Restrict google address finder to the specific country', 'lisfinity-core' ),
				'options'  => lisfinity_country_codes(),
				'default'  => '',
				'required' => [ '_location-autogenerate', '=', '1' ],
			],
			[
				'id'      => '_map-default-longitude',
				'type'    => 'text',
				'title'   => __( 'Map Default Longitude', 'lisfinity-core' ),
				'desc'    => __( 'Set the longitude of the default location that you wish to display on the map.', 'lisfinity-core' ),
				'default' => '-101.645507',
			],
			[
				'id'      => '_map-default-latitude',
				'type'    => 'text',
				'title'   => __( 'Map Default Latitude', 'lisfinity-core' ),
				'desc'    => __( 'Set the latitude of the default location that you wish to display on the map.', 'lisfinity-core' ),
				'default' => '40.346544',
			],
			[
				'id'      => '_map-default-zoom',
				'type'    => 'text',
				'title'   => __( 'Map Default Zoom', 'lisfinity-core' ),
				'desc'    => __( 'Set the default zoom level that you wish to set the map to.', 'lisfinity-core' ),
				'default' => '8',
			],
			// map provider.
			[
				'id'      => '_map-style',
				'type'    => 'select',
				'title'   => __( 'Map Style', 'lisfinity-core' ),
				'desc'    => __( 'Set the default zoom level that you wish to set the map to.', 'lisfinity-core' ),
				'options' => [
					'default' => __( 'OpenStreet', 'lisfinity-core' ),
					'mapbox'  => __( 'Map Box', 'lisfinity-core' ),
				],
				'default' => 'default',
				'select2' => [
					'allowClear' => false,
				],
			],
			// openstreet leaflet.
			[
				'id'       => '_map-leaflet-style',
				'type'     => 'text',
				'title'    => __( 'OpenStreet Map Style', 'lisfinity-core' ),
				'desc'     => __( 'Enter OpenStreet or some other free map style or leave empty to use default one: <a href="https://openmaptiles.org/styles/" target="_blank">Examples</a>', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_map-style', '=', 'default' ],
			],
			// mapbox.
			[
				'id'       => '_map-mapbox-id',
				'type'     => 'text',
				'title'    => __( 'MapBox Style ID', 'lisfinity-core' ),
				'desc'     => __( 'Enter MapBox style ID you obtained from your MapBox profile.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_map-style', '=', 'mapbox' ],
			],
			[
				'id'       => '_map-mapbox-api',
				'type'     => 'text',
				'title'    => __( 'MapBox API', 'lisfinity-core' ),
				'desc'     => __( 'Enter MapBox Style API key obtained from your MapBox profile.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_map-style', '=', 'mapbox' ],
			],
			[
				'id'       => '_map-mapbox-username',
				'type'     => 'text',
				'title'    => __( 'MapBox Username', 'lisfinity-core' ),
				'desc'     => __( 'Enter your MapBox username.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_map-style', '=', 'mapbox' ],
			],
		],
	]
);
