<?php
/**
 * Form submit fields for a business model
 *
 * @author pebas
 * @package forms/submit
 * @version 1.0.0
 */

namespace Lisfinity\Models\Forms;

/**
 * Class FormBusinessSubmitModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class FormBusinessSubmitModel {

	protected $fields;

	private $reserved_field_names = [
		'title',
		'description',
		'_price',
		'_sale_price',
	];

	public function __construct() {
		$this->set_fields();
	}

	/**
	 * Get taxonomy form fields
	 * ------------------------
	 *
	 * @return mixed
	 */
	public function get_fields() {
		return $this->fields;
	}

	public function get_reserved_field_names() {
		$this->reserved_field_names;
	}

	/**
	 * Set terms form fields
	 * --------------------------
	 *
	 * @return array
	 */
	protected function set_fields() {
		$business_id = lisfinity_get_premium_profile_id( get_current_user_id() );
		$latitude    = lisfinity_get_option( 'map-default-latitude' );
		$longitude   = lisfinity_get_option( 'map-default-longitude' );
		$zoom        = lisfinity_get_option( 'map-default-zoom' );
		$count       = 1;
		$fields      = [
			// fields | general.
			'basic_info' => [
				'_featured_image'       => [
					'key'            => $count ++,
					'label'          => __( 'Profile Image/Logo', 'lisfinity-core' ),
					'value'          => '',
					'description'    => __( 'Choose your profile image', 'lisfinity-core' ),
					'type'           => 'single_image',
//					'type_filter'    => 'business_image',
					'post_thumbnail' => true,
					'size_limit'     => lisfinity_get_maximum_upload_size_setting()['output'],
					'default_save'   => true,
					'props'          => [
						'type'               => __( 'Images', 'lisfinity-core' ),
						'button_label'       => __( 'Upload Banner Image', 'lisfinity-core' ),
						'media_button_label' => __( 'Choose Banner Image', 'lisfinity-core' ),
						'media_title'        => __( 'Banner Image', 'lisfinity-core' ),
					],
					'settings'       => [
						'basic' => true,
					],
				],
				'_profile-banner'       => [
					'key'         => $count ++,
					'label'       => __( 'Main Banner', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Choose main banner image of your premium profile', 'lisfinity-core' ),
					'type'        => 'single_image',
//					'type_filter' => 'business_image',
					'size_limit'  => lisfinity_get_maximum_upload_size_setting()['output'],
					'props'       => [
						'type'               => __( 'Images', 'lisfinity-core' ),
						'button_label'       => __( 'Upload Banner Image', 'lisfinity-core' ),
						'media_button_label' => __( 'Choose Banner Image', 'lisfinity-core' ),
						'media_title'        => __( 'Banner Image', 'lisfinity-core' ),
					],
					'settings'    => [
						'basic' => true,
					],
				],
				'title'                 => [
					'key'         => $count ++,
					'label'       => __( 'Business name', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Please type the name of your business', 'lisfinity-core' ),
					'type'        => 'text',
					'attributes'  => [
						'placeholder' => __( 'Type title...', 'lisfinity-core' )
					],
					'additional'  => [
						'class' => 'w-full mb-40'
					],
				],
				'_profile-website'      => [
					'key'         => $count ++,
					'label'       => __( 'Business website', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Please type your website', 'lisfinity-core' ),
					'type'        => 'url',
					'attributes'  => [
						'placeholder' => __( 'Website address...', 'lisfinity-core' )
					],
					'additional'  => [
						'class' => 'w-full mb-40'
					],
				],
				'_profile-email'        => [
					'key'         => $count ++,
					'label'       => __( 'Business email', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Please type your email', 'lisfinity-core' ),
					'type'        => 'email',
					'attributes'  => [
						'placeholder' => __( 'Email address...', 'lisfinity-core' )
					],
					'additional'  => [
						'class' => 'w-full mb-40'
					],
				],
				'description'           => [
					'key'         => $count ++,
					'label'       => __( 'Additional Info', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Type any additional info.. 300 characters maximum.', 'lisfinity-core' ),
					'type'        => 'rich-text',
					'options'     => [
						'rich_editing' => true,
						'hide_buttons' => true,
						'max_chars'    => 10000,
					],
					'attributes'  => [
						'placeholder' => __( 'Type additional info...', 'lisfinity-core' ),
					],
					'additional'  => [
						'class' => 'w-full mb-40',
					],
				],
				'_profile-location'     => [
					'key'         => $count ++,
					'label'       => __( 'Location', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Brand new watch Rolex - 2017', 'lisfinity-core' ),
					'type'        => 'location',
					'taxonomy'    => false,
					'props'       => [
						'google'    => '1' === lisfinity_get_option( 'location-autogenerate' ),
						'api'       => lisfinity_get_option( 'map-api' ),
						'address'   => get_post_meta( (int) $business_id, '_profile-location|||0|address', true ) ?? '',
						'latitude'  => ! empty( get_post_meta( (int) $business_id, '_profile-location|||0|lat', true ) ) ? get_post_meta( (int) $business_id, '_profile-location|||0|lat', true ) : ( ! empty( $latitude ) ? $latitude : '' ),
						'longitude' => ! empty( get_post_meta( (int) $business_id, '_profile-location|||0|lng', true ) ) ? get_post_meta( (int) $business_id, '_profile-location|||0|lng', true ) : ( ! empty( $longitude ) ? $longitude : '' ),
						'zoom'      => ! empty( $zoom ) ? $zoom : 14,
						'coords'    => true,
					],
				],
				'_profile-phones'       => [
					'key'    => $count ++,
					'type'   => 'complex',
					'fields' => [
						'profile-phone'      => [
							'key'         => $count ++,
							'label'       => __( 'Phone Number', 'lisfinity-core' ),
							'value'       => '',
							'description' => __( 'Please type your phone number', 'lisfinity-core' ),
							'type'        => 'text',
							'attributes'  => [
								'placeholder' => __( 'Phone number...', 'lisfinity-core' )
							],
							'additional'  => [
								'class' => 'mb-10',
							],
						],
						'profile-phone-apps' => [
							'key'         => $count ++,
							'label'       => __( 'Phone Apps', 'lisfinity-core' ),
							'value'       => '',
							'description' => __( 'Choose apps to enable for this number', 'lisfinity-core' ),
							'type'        => 'custom',
							'attributes'  => [
								'placeholder' => __( 'Phone number...', 'lisfinity-core' )
							],
						],
					],
				],
				'_profile-telegram'     => [
					'key'         => $count ++,
					'label'       => __( 'Telegram Account', 'lisfinity-core' ),
					'value'       => '',
					'description' => __( 'Add your telegram username without @ sign so the buyers can contact you on it.', 'lisfinity-core' ),
					'type'        => 'text',
					'additional'  => [
						'class' => 'w-full mb-40',
					],
				],

				// working hours.
				'_working-hours-title'  => [
					'key'   => $count ++,
					'type'  => 'title',
					'label' => __( 'Working Hours', 'lisfinity-core' ),
				],
				'_profile-hours'        => [
					'key'         => $count ++,
					'type'        => 'working_hours',
					'description' => __( 'Set up your working hours', 'lisfinity-core' ),
					'options'     => [
						'currentDay'     => lisfinity_get_first_day_of_the_week( true ),
						'days'           => lisfinity_days_of_the_week( false, true ),
						'days_localized' => lisfinity_days_of_the_week( false, false ),
						'enableTime'     => true,
						'minDate'        => 'today',
						'time_24hr'      => true,
						'dateFormat'     => 'G:i K',
//						'altInput' => true,
						'altFormat'      => 'G:i K',
						'defaultDate'    => '12:00',
						'noCalendar'     => true,
					],
				],
				// social networks.
				'social-networks-title' => [
					'key'   => $count ++,
					'type'  => 'title',
					'label' => __( 'Social Networks', 'lisfinity-core' ),
				],

			],
		];

		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'business-telegram' ) ) ) {
			unset( $fields['basic_info']['_profile-telegram'] );
		}
		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'business-working-hours-enabled' ) ) ) {
			unset( $fields['basic_info']['_working-hours-title'] );
			unset( $fields['basic_info']['_profile-hours'] );
		}
		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'business-phone-enabled' ) ) ) {
			unset( $fields['basic_info']['_profile-phones'] );
		}

		if ( lisfinity_get_option( 'business-social-networks' ) ) {
			$social_networks = lisfinity_get_option( 'business-social-networks' );
			foreach ( $social_networks as $key => $network ) {
				if ( $network === 'facebook' ) {
					$fields['basic_info']['_profile-social-facebook'] = [
						'key'         => $count ++,
						'label'       => __( 'Facebook', 'lisfinity-core' ),
						'value'       => '',
						'description' => __( 'Add a link to your facebook account', 'lisfinity-core' ),
						'type'        => 'text',
						'attributes'  => [
							'placeholder' => __( 'Link to facebook...', 'lisfinity-core' )
						],
						'additional'  => [
							'class' => 'w-full mb-40',
						],
					];
				} else if ( $network === 'twitter' ) {
					$fields['basic_info']['_profile-social-twitter'] = [
						'key'         => $count ++,
						'label'       => __( 'Twitter', 'lisfinity-core' ),
						'value'       => '',
						'description' => __( 'Add a link to your twitter account', 'lisfinity-core' ),
						'type'        => 'text',
						'attributes'  => [
							'placeholder' => __( 'Link to twitter...', 'lisfinity-core' )
						],
						'additional'  => [
							'class' => 'w-full mb-40',
						],
					];
				} else if ( $network === 'instagram' ) {
					$fields['basic_info']['_profile-social-instagram'] = [
						'key'         => $count ++,
						'label'       => __( 'Instagram', 'lisfinity-core' ),
						'value'       => '',
						'description' => __( 'Add a link to your instagram account', 'lisfinity-core' ),
						'type'        => 'text',
						'attributes'  => [
							'placeholder' => __( 'Link to instagram...', 'lisfinity-core' )
						],
						'additional'  => [
							'class' => 'w-full mb-40',
						],
					];
				} else if ( $network === 'v_kontakte' ) {
					$fields['basic_info']['_profile-social-vk'] = [
						'key'         => $count ++,
						'label'       => __( 'VKontakte', 'lisfinity-core' ),
						'value'       => '',
						'description' => __( 'Add a link to your vk account', 'lisfinity-core' ),
						'type'        => 'text',
						'attributes'  => [
							'placeholder' => __( 'Link to vk...', 'lisfinity-core' )
						],
						'additional'  => [
							'class' => 'w-full mb-40',
						],
					];
				} else if ( $network === 'youtube' ) {
					$fields['basic_info']['_profile-social-youtube'] = [
						'key'         => $count ++,
						'label'       => __( 'Youtube', 'lisfinity-core' ),
						'value'       => '',
						'description' => __( 'Add a link to your youtube account', 'lisfinity-core' ),
						'type'        => 'text',
						'attributes'  => [
							'placeholder' => __( 'Link to youtube...', 'lisfinity-core' )
						],
						'additional'  => [
							'class' => 'w-full mb-40',
						],
					];
				}
			}

		} else {
			unset( $fields['basic_info']['social-networks-title'] );
		}

		$this->fields = apply_filters( 'lisfinity__submit_business_form_fields', $fields );

		return $this->fields;
	}

}
