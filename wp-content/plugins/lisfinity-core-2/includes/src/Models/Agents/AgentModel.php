<?php
/**
 * Model for our custom Messages Room type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package lisfinity-messages
 * @version 1.0.0
 */

namespace Lisfinity\Models\Agents;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class MessageRoomModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class AgentModel extends Model {

	public $table = 'agents';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'user_id'   => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'business_id' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'owner_id'   => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'permissions'      => [
				'type'  => 'varchar(100)',
				'value' => "'all'",
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
	public function store_agent( $data ) {
		$agent_values = [
			$data['user_id'],
			$data['business_id'],
			$data['owner_id'],
			$data['permissions'] ?? 'all',
		];

		return $this->store( $agent_values );
	}

}
