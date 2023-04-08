<?php
/**
 * Model for our custom Bids functionality.
 *
 * @author pebas
 * @package lisfinity-bids
 * @version 1.0.0
 */

namespace Lisfinity\Models\Bids;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class BidModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class BidModel extends Model {

	public $table = 'bids';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'product_id' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'owner_id'   => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'bidder_id'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'amount'     => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'message'    => [
				'type'  => 'varchar(255)',
				'value' => 'NULL',
			],
			'status'     => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
		];

		return $this->fields;
	}

	/**
	 * Store bid to the database
	 * -------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_bid( $data ) {
		$bid_values = [
			// product id of the post that the bid is attached to.
			$data['product_id'],
			// id of the user that is owner of the product
			$data['owner_id'],
			// id of the user that sends the bid.
			$data['bidder_id'],
			// bid amount
			$data['amount'],
			// bid content that is being send.
			$data['message'],
			// status of the message.
			$data['status'],
		];

		return $this->store( $bid_values );
	}

	/**
	 * Get all bids connected to a specified product
	 * ---------------------------------------------
	 *
	 * @param $product_id
	 * @param $user_id
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_product_bids( $product_id, $user_id = '' ) {
		$bids = $this->where( 'product_id', $product_id )->get( '', 'ORDER BY created_at DESC' );

		return $this->prepare_bids_data( $bids, $user_id );
	}

	/**
	 * Prepare bids data for loading on the page
	 * -----------------------------------------
	 *
	 * @param $bids
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public function prepare_bids_data( $bids, $user_id = '' ) {
		if ( empty( $bids ) ) {
			return $bids;
		}

		foreach ( $bids as $bid ) {
			$bidder          = get_userdata( $bid->bidder_id );
			$premium_profile = lisfinity_get_premium_profile( $bid->bidder_id );
			$bid->post_title = ! empty( $premium_profile ) ? $premium_profile->post_title : $bidder->display_name;
			$bid->thumbnail  = has_post_thumbnail( $premium_profile->ID ) ? get_the_post_thumbnail_url( $premium_profile->ID ) : lisfinity_get_avatar_url( $bid->bidder_id );
			// format human readable time differences
			$created_time          = human_time_diff( strtotime( $bid->created_at ), current_time( 'timestamp' ) );
			$created_time_text     = sprintf( __( '%s ago', 'lisfinity-core' ), $created_time );
			$bid->type             = 'bid';
			$bid->created          = $created_time_text;
			$bid->created_human    = $created_time;
			$bid->amount_html      = lisfinity_get_price_html( $bid->amount );
			$bid->bidder_permalink = get_permalink( $bid->bidder_id );

			// bidder contact information.
			$bid->user_email = $bidder->user_email;
			$business_email  = carbon_get_post_meta( $premium_profile->ID, 'profile-email' );
			$business_phones = carbon_get_post_meta( $premium_profile->ID, 'profile-phones' );
			if ( ! empty( $business_email ) ) {
				$bid->email = $business_email;
			}
			if ( ! empty( $business_phones ) ) {
				$bid->phones = $business_phones;
			}
		}

		return $bids;
	}

	/**
	 * Normalize bid metadata
	 * ----------------------
	 *
	 * @param $bid
	 *
	 * @return mixed
	 */
	public function format_bid_data( $bid ) {

		if ( empty( $bid ) ) {
			return $bid;
		}

		$user_data               = get_userdata( $bid->bidder_id );
		$premium_profile         = lisfinity_get_premium_profile( $bid->bidder_id );
		$bid->created_human      = human_time_diff( strtotime( $bid->created_at ), current_time( 'timestamp' ) );
		$bid->post_title         = $premium_profile ? $premium_profile->post_title : $user_data->display_name;
		$bid->thumbnail          = has_post_thumbnail( $premium_profile->ID ) ? get_the_post_thumbnail_url( $premium_profile->ID ) : lisfinity_get_avatar_url( $bid->bidder_id );
		$bid->product_title      = get_the_title( $bid->product_id );
		$bid->currency           = get_woocommerce_currency_symbol();
		$bid->decimals           = wc_get_price_decimals();
		$bid->decimal_separator  = wc_get_price_decimal_separator();
		$bid->thousand_separator = wc_get_price_thousand_separator();
		$bid->price_format       = get_woocommerce_price_format();
		$bid->permalink          = get_permalink( $premium_profile->ID );

		return $bid;
	}

}
