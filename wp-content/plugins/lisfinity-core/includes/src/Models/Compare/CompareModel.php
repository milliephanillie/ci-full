<?php
/**
 * Model for our custom Products Comparing functionality.
 *
 * @author pebas
 * @package lisfinity-bids
 * @version 1.0.0
 */

namespace Lisfinity\Models\Compare;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class CompareModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class CompareModel extends Model {

	public $table = 'compare';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'user_id'      => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id'   => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_type' => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
		];

		return $this->fields;
	}

	/**
	 * Store compare product to the database
	 * -------------------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_compare( $data ) {
		$compare_data = [
			// id of the user
			$data['user_id'],
			// product id of the post that is being stored.
			$data['product_id'],
			// type of the product that is being stored.
			$data['product_type'],
		];

		return $this->store( $compare_data );
	}

	/**
	 * Get all compare products from the database
	 * ------------------------------------------
	 *
	 * @param $type
	 * @param $user_id
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_compare_products( $user_id = '', $type = 'common' ) {
		$products = $this->where( [
			[ 'user_id', $user_id ],
			[ 'product_type', $type ]
		] )->get( '', 'ORDER BY created_at DESC', 'product_id' );
		$filter   = [];
		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				$filter[] = $product->product_id;
			}
		}

		return $filter;
	}

	public function get_first_compare_product( $user_id = '', $type = 'common' ) {
		$product = $this->where( [ [ 'user_id', $user_id ], [ 'product_type', $type ] ] )->get( '1', '', '', 'col' );

		return ! empty( $product ) ? $product[0] : false;
	}

	public function get_last_compare_product( $user_id = '' ) {
		$product = $this->where( 'user_id', $user_id )->get( '1' );

		return ! empty( $product ) ? $product[0] : false;
	}

}
