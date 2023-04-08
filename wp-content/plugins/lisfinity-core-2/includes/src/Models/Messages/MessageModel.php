<?php
/**
 * Model for our custom Messages Room Users with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package lisfinity-messages
 * @version 1.0.0
 */

namespace Lisfinity\Models\Messages;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class MessageModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class MessageModel extends Model {

	public $table = 'chat_message';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'chat_id'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'sender_id'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'message'    => [
				'type'  => 'varchar(255)',
				'value' => 'NULL',
			],
			'status'     => [
				'type'  => 'tinyint(2)',
				'value' => '0',
			],
		];

		return $this->fields;
	}

	/**
	 * Store message in the database
	 * -----------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_message( $data ) {
		$message_values = [
			// chat id of the room that the message is attached to.
			$data['chat_id'],
			// product id of the post that the message is attached to.
			$data['product_id'],
			// id of the user that sends the message.
			$data['sender_id'],
			// message content that is being send.
			$data['message'],
			// status of the message.
			0,
		];

		return $this->store( $message_values );
	}

	public function mark_as( $id, $type = 1 ) {
		$result = $this->update_wp( [ 'status' => $type ], [ 'id' => $id ] );

		return $result;
	}

	/**
	 * Get all messages for the chat room
	 * ----------------------------------
	 *
	 * @param $chat_id
	 *
	 * @return array|bool|mixed|object|string|void|null
	 */
	public function get_messages( $chat_id ) {
		$chat_model = new ChatModel();
		$messages   = $chat_model->join( $this->db, 'chat_id', 'id' )->where( 'id', $chat_id )->get();

		if ( empty( $messages ) ) {
			return false;
		}

		return $messages;
	}

	/**
	 * Get all messages by a user posted for a specific product
	 * --------------------------------------------------------
	 *
	 * @param $product_id
	 * @param $user_id
	 *
	 * @return array|bool|mixed|object|string|void|null
	 */
	public function get_user_messages( $product_id, $user_id ) {
		$messages = $this->where( [ [ 'product_id', $product_id ], [ 'sender_id', $user_id ] ] )->get();

		if ( empty( $messages ) ) {
			return false;
		}

		return $messages;
	}

	/**
	 * Check if user has any messages and return either true or chat_id
	 * ----------------------------------------------------------------
	 *
	 * @param $product_id
	 * @param $user_id
	 * @param bool $chat_id
	 *
	 * @return bool|int
	 */
	public function user_has_messages( $product_id, $user_id, $chat_id = false ) {
		$messages = $this->where( [
			[ 'product_id', $product_id ],
			[ 'sender_id', $user_id ]
		] )->value( 'chat_id', '', 1 );

		if ( ! empty( $messages ) ) {
			if ( $chat_id && $user_id ) {
				$message = ! empty( $messages ) ? array_shift( $messages ) : $messages;

				return absint( $message->chat_id );
			}

			return true;
		}

		return false;
	}

	/**
	 * Get all chat rooms for the given product_id
	 * -------------------------------------------
	 *
	 * @param $product_id
	 *
	 * @return array|bool|mixed|object|string|void|null
	 */
	public function get_product_chats( $product_id ) {
		$chat_model = new ChatModel();
		$chat_rooms = $chat_model->join( $this->db, 'chat_id', 'id' )->where( [
			[ 'product_id', $product_id ],
			[ 'status', 1 ],
		] )->get( '', "GROUP BY(chat_id) ORDER BY {$this->db}.id DESC", "chat_id, {$this->db}.product_id, owner_id, {$this->db}.sender_id" );

		if ( empty( $chat_rooms ) ) {
			return false;
		}


		return $chat_rooms;
	}

	/**
	 * Normalize message metadata
	 * --------------------------
	 *
	 * @param $message
	 *
	 * @return mixed
	 */
	public function format_message_data( $message ) {

		if ( empty( $message ) ) {
			return $message;
		}

		$user                   = get_post_meta( $message->sender_id, '_product-owner' );
		$message->created_human = human_time_diff( strtotime( $message->created_at ), current_time( 'timestamp' ) );
		$message->post_title    = get_the_title( $message->sender_id );
		$message->thumbnail     = has_post_thumbnail( $message->sender_id ) ? get_the_post_thumbnail_url( $message->sender_id ) : lisfinity_get_avatar_url( $user );
		$message->product_title = get_the_title( $message->product_id );
		$message->permalink     = get_permalink( $message->product_id );

		return $message;
	}

}
