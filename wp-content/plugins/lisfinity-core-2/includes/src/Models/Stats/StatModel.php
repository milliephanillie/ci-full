<?php
/**
 * Model for our custom Stats functionality.
 *
 * @author pebas
 * @package lisfinity-bids
 * @version 1.0.0
 */

namespace Lisfinity\Models\Stats;

use Lisfinity\Abstracts\Model as Model;
use Lisfinity\Models\ProductModel;

/**
 * Class NotificationModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class StatModel extends Model {

	public $table = 'stats';

	public $version = '1.0.0';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'user_id'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'type'       => [
				'type'  => 'tinyint(2)',
				'value' => 'NULL',
			],
			'count'      => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'date'       => [
				'type'  => 'varchar(255)',
				'value' => 'NULL',
			],
		];

		return $this->fields;
	}

	/**
	 * Store stat to the database
	 * --------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_stat( $data ) {
		$values = [
			$data['user_id'],
			$data['product_id'],
			$data['type'],
			$data['count'],
			$data['date'],
		];

		return $this->store( $values );
	}

	public function update_stat( $data ) {
		$today           = date( 'Y-m-d' );
		$today_timestamp = strtotime( $today );

		// check if we already have data in the table.
		$row = $this->where( [
			[ 'user_id', $data['user_id'] ],
			[ 'product_id', $data['product_id'] ],
			[ 'date', $today_timestamp ],
			[ 'type', $data['type'] ],
		] )->get( '1', '', 'id, count' );

		if ( ! empty( $row ) ) {
			$count = $row[0]->count;
			$count ++;

			$this->set( 'count', $count )->where( 'id', $row[0]->id )->update();
		} else {
			if ( ! isset( $data['count'] ) ) {
				$data['count'] = 1;
			}
			if ( ! isset( $data['date'] ) ) {
				$data['date'] = $today_timestamp;
			}
			$this->store_stat( $data );
		}
	}

	public function get_business_products( $user_id ) {
		$model = new ProductModel();
		$args  = [
			'post_status'   => 'publish, sold',
			'owner'         => $user_id,
			'fields'        => 'ids',
			'cache_results' => false,
		];

		$query = $model->get_products_query( $args, true );

		if ( ! $query->found_posts ) {
			return false;
		}

		return $query->posts;
	}

	public function get_product_views( $id ) {
		$views = $this->where( [ [ 'product_id', $id ], [ 'type', 1 ] ] )->get( '', '', 'SUM(count)', 'col' );

		return array_shift( $views ) ?? 0;
	}

}
