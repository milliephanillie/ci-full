<?php
/**
 * Model for our custom Messages Room type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package lisfinity-messages
 * @version 1.0.0
 */

namespace Lisfinity\Models\Messages;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class MessageRoomModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class ChatModel extends Model {

	public $table = 'chats';

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
			'sender_id'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'topic'      => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'status'     => [
				'type'  => 'tinyint(2)',
				'value' => '1',
			],
		];

		return $this->fields;
	}

	/**
	 * Create a new chat message room
	 * ------------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_message_room( $data ) {
		$message_values = [
			// product id of the post that the message is attached to.
			$data['product_id'],
			// product id of the post that the message is attached to.
			$data['owner_id'],
			// product id of the post that the message is attached to.
			$data['sender_id'],
			// id of the user that sends the message.
			$data['topic'],
			// status of the message.
			1,
		];

		return $this->store( $message_values );
	}

}
