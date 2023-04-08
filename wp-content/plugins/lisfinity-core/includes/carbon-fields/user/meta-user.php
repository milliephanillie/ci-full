<?php
/**
 * Meta User
 *
 * Here are declared all fields that are attached to a user
 *
 * @link https://docs.carbonfields.net/#/containers/user-meta
 *
 * @author pebas
 * @package meta-fields-user
 * @version 1.0.0
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Meta Product / Fields.
Container::make( 'user_meta', __( 'User Verification Settings', 'lisfinity-core' ) )
         ->add_fields( apply_filters( 'lisfinity__user_meta_fields_verification', [
	         Field::make( 'image', 'license', __( 'Driver\'s License', 'lisfinity-core' ) )
	              ->set_help_text( __( 'User driver\'s license', 'lisfinity-core' ) ),
	         Field::make( 'image', 'residence', __( 'Proof of Residence', 'lisfinity-core' ) )
	              ->set_help_text( __( 'User proof of residence', 'lisfinity-core' ) ),
         ] ) );
Container::make( 'user_meta', __( 'Additional User Settings', 'lisfinity-core' ) )
         ->add_fields( apply_filters( 'lisfinity__user_meta_fields', [
	         Field::make( 'radio', 'account-type', __( 'Account Type', 'lisfinity-core' ) )
	              ->set_options( [
		              'personal' => __( 'Seller Account', 'lisfinity-core' ),
		              'business' => __( 'Business Account', 'lisfinity-core' ),
		              'buyer'    => __( 'Buyer Account', 'lisfinity-core' )
	              ] )
	              ->set_default_value( 'personal' )
	              ->set_help_text( __( 'Choose whether the user has been verified.', 'lisfinity-core' ) ),
	         Field::make( 'radio', 'verified', __( 'Verified', 'lisfinity-core' ) )
	              ->set_options( [
		              false => __( 'Not verified', 'lisfinity-core' ),
		              true  => __( 'Verified', 'lisfinity-core' ),
	              ] )
	              ->set_help_text( __( 'Choose whether the user has been verified.', 'lisfinity-core' ) ),
	         Field::make( 'image', 'avatar', __( 'Profile Picture', 'lisfinity-core' ) )
	              ->set_help_text( __( 'Upload user profile picture', 'lisfinity-core' ) ),
	         Field::make( 'association', 'blocked-users', __( 'Blocked Users', 'lisfinity-core' ) )
	              ->set_types( [
		              [
			              'type' => 'user',
		              ]
	              ] )
	              ->set_help_text( __( 'List of blocked users by this account. Those users will not be able to write messages or make bids for the products of this account.', 'lisfinity-core' ) ),
	         Field::make( 'association', 'bookmarks', __( 'Bookmarks', 'lisfinity-core' ) )
	              ->set_types( [
		              [
			              'type'      => 'post',
			              'post_type' => 'product',
		              ],
	              ] )
	              ->set_help_text( __( 'List of bookmarked products by the user.', 'lisfinity-core' ) ),
         ] ) );
