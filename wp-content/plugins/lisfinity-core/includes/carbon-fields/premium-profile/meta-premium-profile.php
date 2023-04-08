<?php
/**
 * Meta Premium Profile.
 *
 * Here are defined all fields necessary for setting up
 * a premium profile account.
 *
 * @link https://carbonfields.net/docs/containers-post-meta/
 *
 * @author pebas
 * @package meta-fields-profile
 * @version 1.0.0
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Meta Product / Fields.
Container::make( 'post_meta', __( 'Product Information', 'lisfinity-core' ) )
         ->where( 'post_type', '=', 'premium_profile' )
	// tab | general information.
	     ->add_tab(
		__( 'General', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__profile_meta_fields_general',
			[
				Field::make( 'radio', 'can-post-listings', __( 'Can Post Listings', 'lisfinity-core' ) )
				     ->add_options( [
					     '1' => __( 'Yes', 'lisfinity-core' ),
					     '0' => __( 'No', 'lisfinity-core' ),
				     ] )
				     ->set_width( 100 )
				     ->set_default_value( '1' )
				     ->set_help_text( __( 'Choose if the vendor can post listings. Seller Approval option has to be checked in the theme options in order for this one to be valid.', 'lisfinity-core' ) ),
				Field::make( 'image', 'profile-banner', __( 'Main Banner Image', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Choose main banner image when premium profile is enabled.', 'lisfinity-core' ) ),
				Field::make( 'association', 'blocked-profiles', __( 'Blocked Profiles', 'lisfinity-core' ) )
				     ->set_types( [
					     [
						     'type'      => 'post',
						     'post_type' => 'premium_profile',
					     ]
				     ] )
				     ->set_help_text( __( 'List of blocked profiles by this account. Owner and agents from that profile will not be able to write messages or make bids for the products of this account.', 'lisfinity-core' ) ),
			]
		)
	)
	// tab | Contact.
	     ->add_tab(
		__( 'Contact', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__profile_fields_contact',
			[
				Field::make( 'complex', 'profile-phones', __( 'Phone Numbers', 'lisfinity-core' ) )
				     ->add_fields( [
					     Field::make( 'text', 'profile-phone', __( 'Phone Number', 'lisfinity-core' ) )
					          ->set_help_text( __( 'Enter the phone number that potential customers can reach you on.', 'lisfinity-core' ) ),
					     Field::make( 'multiselect', 'profile-phone-apps', __( 'Phone Apps', 'lisfinity-core' ) )
					          ->set_options( [
						          'viber'    => __( 'Viber', 'lisfinity-core' ),
						          'whatsapp' => __( 'WhatsApp', 'lisfinity-core' ),
						          'skype'    => __( 'Skype', 'lisfinity-core' ),
					          ] )
					          ->set_help_text( __( 'Choose the apps this phone number will be connected to.', 'lisfinity-core' ) ),

				     ] )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter the phone numbers for your profile.', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-telegram', __( 'Telegram Username', 'lisfinity-core' ) )
				     ->set_help_text( __( 'Enter your telegram username without @ sign.', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-website', __( 'Website', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter the website of your account.', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-email', __( 'Email', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter the email of your account', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-social-facebook', __( 'Facebook', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter link to your facebook account', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-social-twitter', __( 'Twitter', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter link to your twitter account', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-social-instagram', __( 'Instagram', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter link to your instagram account', 'lisfinity-core' ) ),
				Field::make( 'text', 'profile-social-vk', __( 'VKontakte', 'lisfinity-core' ) )
				     ->set_width( '33%' )
				     ->set_help_text( __( 'Enter link to your vkontakte account', 'lisfinity-core' ) ),
				Field::make( 'map', 'profile-location', __( 'Location', 'lisfinity-core' ) )
				     ->set_position( apply_filters( 'lisfinity__product_meta_fields_default_map_latitude', 40 ), apply_filters( 'lisfinity__product_meta_fields_default_map_longitude', 40 ), apply_filters( 'lisfinity__product_meta_fields_default_map_zoom', 8 ) )
				     ->set_help_text( __( 'Drag and drop the pin on the map to select product location.', 'lisfinity-core' ) ),
			]
		)
	)
	// tab | Contact.
	     ->add_tab(
		__( 'Working Hours', 'lisfinity-core' ),
		apply_filters(
			'lisfinity__profile_fields_working_hours',
			[
				Field::make( 'radio', 'profile-hours-enable', __( 'Enable Working Hours', 'lisfinity-core' ) )
				     ->set_options( [
					     'yes' => __( 'Enable', 'lisfinity-core' ),
					     'no'  => __( 'Disable', 'lisfinity-core' ),
				     ] )
				     ->set_width( '100' )
				     ->set_default_value( false ),
				// Monday
				Field::make( 'radio', 'profile-hours-monday-type', __( 'Working Hours: Monday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-monday-hours', __( 'Working Hours: Monday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Monday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Monday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Monday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Monday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-monday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Tuesday
				Field::make( 'radio', 'profile-hours-tuesday-type', __( 'Working Hours: Tuesday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-tuesday-hours', __( 'Working Hours: Tuesday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Tuesday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Tuesday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Tuesday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Tuesday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-tuesday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Wednesday
				Field::make( 'radio', 'profile-hours-wednesday-type', __( 'Working Hours: Wednesday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-wednesday-hours', __( 'Working Hours: Wednesday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Wednesday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Wednesday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Wednesday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Wednesday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-wednesday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Thursday
				Field::make( 'radio', 'profile-hours-thursday-type', __( 'Working Hours: Thursday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-thursday-hours', __( 'Working Hours: Thursday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Thursday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Thursday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Thursday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Thursday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-thursday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Friday
				Field::make( 'radio', 'profile-hours-friday-type', __( 'Working Hours: Friday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-friday-hours', __( 'Working Hours: Friday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Friday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Friday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Friday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Friday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-friday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Saturday
				Field::make( 'radio', 'profile-hours-saturday-type', __( 'Working Hours: Saturday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-saturday-hours', __( 'Working Hours: Saturday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Saturday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Saturday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Saturday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Saturday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-saturday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
				// Sunday
				Field::make( 'radio', 'profile-hours-sunday-type', __( 'Working Hours: Sunday Type', 'lisfinity-core' ) )
				     ->set_options( [
					     'working'     => __( 'Working day', 'lisfinity-core' ),
					     'full'        => __( 'Open all day', 'lisfinity-core' ),
					     'not_working' => __( 'Closed', 'lisfinity-core' ),
				     ] )
				     ->set_width( '50' )
				     ->set_default_value( 'working' ),
				Field::make( 'complex', 'profile-hours-sunday-hours', __( 'Working Hours: Sunday', 'lisfinity-core' ) )
				     ->set_duplicate_groups_allowed( false )
				     ->add_fields( [
					     Field::make( 'time', 'open', __( 'Sunday Open Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the opening time for Sunday', 'lisfinity-core' ) ),
					     Field::make( 'time', 'close', __( 'Sunday Close Time', 'lisfinity-core' ) )
					          ->set_picker_options(
						          [
							          'time_24hr' => true,
						          ]
					          )
					          ->set_help_text( __( 'Enter the closing time for Sunday', 'lisfinity-core' ) ),
				     ] )
				     ->set_help_text( __( 'Set the working hours of your business', 'lisfinity-core' ) )
				     ->set_conditional_logic( [
					     [
						     'field'   => 'profile-hours-sunday-type',
						     'value'   => 'working',
						     'compare' => '=',
					     ]
				     ] )
				     ->set_width( '50%' ),
			]
		)
	);
