<?php

namespace Lisfinity\Models\ProductImporter;

use Lisfinity\Abstracts\Importer;

class Address extends Importer {

	protected $meta_key = 'product-location';

	protected $fields = [
		'_product_location'         => 'location',
	];

	protected function set_name() {
		return __( 'Ad Address Settings', 'lisfinity-core' );
	}

	protected function set_fields() {
		$this->addon->add_field(
			'_product_location',
			__( 'Location', 'lisfinity-core' ),
			'radio',
			[
				'search_by_address'     => [
					__( 'Search by Address (Google API Required)', 'lisfinity-core' ),
					$this->addon->add_options(
						$this->addon->add_field(
							'product_address',
							__( 'Listing Address', 'lisfinity-core' ),
							'text'
						),
						__( 'Google Geocode API Settings', 'lisfinity-core' ),
						[
							$this->addon->add_field(
								'address_geocode',
								__( 'Request Method', 'lisfinity-core' ),
								'radio',
								[
									'address_google_developers' => [
										__( 'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>', 'lisfinity-core' ),
										$this->addon->add_field(
											'address_google_developers_api_key',
											__( 'API Key', 'lisfinity-core' ),
											'text'
										),
										__( 'Up to 2500 requests per day and 5 requests per second.', 'lisfinity-core' )
									],
									'address_google_for_work'   => [
										__( 'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>', 'lisfinity-core' ),
										$this->addon->add_field(
											'address_google_for_work_client_id',
											__( 'Google Maps Premium Client ID', 'lisfinity-core' ),
											'text'
										),
										$this->addon->add_field(
											'address_google_for_work_digital_signature',
											__( 'Google Maps Premium Digital Signature', 'lisfinity-core' ),
											'text'
										),
										__( 'Up to 100,000 requests per day and 10 requests per second', 'lisfinity-core' ),
									]
								] // end Request Method options array
							), // end Request Method nested radio field

						] // end Google Geocode API Settings fields
					) // end Google Gecode API Settings options panel
				], // end Search by Address radio field
				'search_by_coordinates' => [
					__( 'Search by Coordinates (Google API Required)', 'lisfinity-core' ),
					$this->addon->add_field(
						'coords_latitude',
						__( 'Latitude', 'lisfinity-core' ),
						'text',
						null,
						__( 'Example: 34.0194543', 'lisfinity-core' )
					),
					$this->addon->add_options(
						$this->addon->add_field(
							'coords_longitude',
							__( 'Longitude', 'lisfinity-core' ),
							'text',
							null,
							__( 'Example: -118.4911912', 'lisfinity-core' )
						),
						__( 'Google Geocode API Settings', 'lisfinity-core' ),
						array(
							$this->addon->add_field(
								'coord_geocode',
								__( 'Request Method', 'lisfinity-core' ),
								'radio',
								[
									'coord_google_developers' => [
										__( 'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>', 'lisfinity-core' ),
										$this->addon->add_field(
											'coord_google_developers_api_key',
											__( 'API Key', 'lisfinity-core' ),
											'text'
										),
										__( 'Up to 2500 requests per day and 5 requests per second.', 'lisfinity-core' ),
									],
									'coord_google_for_work'   => [
										__( 'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>', 'lisfinity-core' ),
										$this->addon->add_field(
											'coord_google_for_work_client_id',
											__( 'Google Maps Premium Client ID', 'lisfinity-core' ),
											'text'
										),
										$this->addon->add_field(
											'coord_google_for_work_digital_signature',
											__( 'Google Maps Premium Digital Signature', 'lisfinity-core' ),
											'text'
										),
										__( 'Up to 100,000 requests per day and 10 requests per second', 'lisfinity-core' ),
									]
								] // end Geocode API options array
							), // end Geocode nested radio field

						) // end Geocode settings
					) // end coordinates Option panel
				], // end Search by Coordinates radio field
				'search_manually'       => [
					__( 'Manually Set Address', 'lisfinity-core' ),
					$this->addon->add_field( 'displayed_address', __( 'Displayed Address', 'lisfinity-core' ), 'text', null, __( 'Type the address that will be displayed to a user.', 'lisfinity-core' ), false, null ),
					$this->addon->add_field( 'latitude', __( 'Latitude', 'lisfinity-core' ), 'text', null, __( 'Latitude of the address.', 'lisfinity-core' ), false, null ),
					$this->addon->add_field( 'longitude', __( 'Longitude', 'lisfinity-core' ), 'text', null, __( 'Longitude of the address.', 'lisfinity-core' ), false, null ),
				]
			] // end Location radio field
		);
	}

	public function import_fields( $post_id, $data, $import_options, $article ) {
		$location_data = [];
		$search        = '';
		$api_key       = '';

		// if address search is used.
		if ( 'search_by_address' === $data['_product_location'] ) {
			$address = $data['product_address'];
			$search  = ( ! empty( $address ) ? 'address=' . rawurlencode( $address ) : null );
			if ( $data['address_geocode'] == 'address_google_developers' && ! empty( $data['address_google_developers_api_key'] ) ) {
				$api_key = '&key=' . $data['address_google_developers_api_key'];
			} elseif ( $data['address_geocode'] == 'address_google_for_work' && ! empty( $data['address_google_for_work_client_id'] ) && ! empty( $data['address_google_for_work_signature'] ) ) {
				$api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];
			}
		}

		// if coordinates search is used.
		if ( 'search_by_coordinates' === $data['_product_location'] ) {
			$address = $data['latitude'] . ',' . $data['longitude'];
			$lat     = $data['coords_latitude'];
			$long    = $data['coords_longitude'];
			$search  = ( ! empty( $lat ) && ! empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );
			if ( $data['coord_geocode'] == 'coord_google_developers' && ! empty( $data['coord_google_developers_api_key'] ) ) {
				$api_key = '&key=' . $data['coord_google_developers_api_key'];
			} elseif ( $data['coord_geocode'] == 'coord_google_for_work' && ! empty( $data['coord_google_for_work_client_id'] ) && ! empty( $data['coord_google_for_work_signature'] ) ) {
				$api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];
			}
		}

		if ( ! empty( $api_key ) ) {
			$location_data            = $this->geolocate_address( $search, $api_key );
			$location_data[0]['zoom'] = 8;
		}

		// if no api is being used.
		if ( 'search_manually' === $data['_product_location'] ) {
			$location_data[0]['lat']     = $data['latitude'];
			$location_data[0]['lng']     = $data['longitude'];
			$location_data[0]['address'] = $data['displayed_address'];
			$location_data[0]['zoom']    = 8;
		}

		carbon_set_post_meta( $post_id, $this->meta_key, $location_data );
	}

	protected function geolocate_address( $search, $api_key ) {
		$address = [];

		// build $request_url for api call
		$request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
		$result = wp_remote_get(
			$request_url,
			array(
				'timeout'     => 5,
				'redirection' => 1,
				'httpversion' => '1.1',
				'user-agent'  => 'WordPress/Lisfinity-' . LISFINITY_CORE_VERSION . '; ' . get_bloginfo( 'url' ),
				'sslverify'   => false,
			)
		);
		$json   = wp_remote_retrieve_body( $result );

		if ( ! empty( $json ) ) {
			$details = json_decode( $json );

			if ( ! empty( $details->results[0]->address_components ) ) {

				$geo_status = ( $details->status == "ZERO_RESULTS" ) ? 0 : 1;
				$loc_data   = $details->results[0]->address_components;

				$address[0]['lat'] = sanitize_text_field( $details->results[0]->geometry->location->lat );
				$address[0]['lng'] = sanitize_text_field( $details->results[0]->geometry->location->lng );
				//$address[0]['formatted_address'] = sanitize_text_field( $details->results[0]->formatted_address );

				foreach ( $loc_data as $loc ) {
					switch ( $loc->types[0] ) {
						case 'sublocality_level_1':
						case 'locality':
						case 'postal_town':
							$address[0]['city'] = sanitize_text_field( $loc->long_name );
							break;
						case 'country':
							$address[0]['country'] = sanitize_text_field( $loc->long_name );
							break;
					}
				}
				$address[0]['address'] = $address[0]['city'] . ', ' . $address[0]['country'];
			}
		} else {
			$this->addon->log( __( '<b>GOOGLE API WARNING:</b> Could not retrieve response data from Google Maps API.', 'lisfinity-core' ) );
		}

		return $address;
	}

}
