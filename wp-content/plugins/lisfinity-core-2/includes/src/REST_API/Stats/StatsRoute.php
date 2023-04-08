<?php


namespace Lisfinity\REST_API\Stats;

use Lisfinity\Models\Stats\StatModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class StatsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_stats'    => [
			'path'                => '/stats/get',
			'callback'            => 'get_stats',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'update_stats' => [
			'path'                => '/stats/store',
			'callback'            => 'store_stat',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
	];

	public function get_stats( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		$product = ! empty( $data['product_id'] ) ? $data['product_id'] : false;

		switch ( $data['days'] ) {
			case 'month':
				$chart_data = $this->get_last_month_data( $data, $product );
				break;
			case 'year':
				$chart_data = $this->get_last_year_data( $data, $product );
				break;
			default:
				$chart_data = $this->get_last_week_data( $data, $product );
		}

		return $chart_data;
	}

	protected function get_last_year_data( $data, $product ) {
		$chart_data = [];
		$year       = date( 'Y' );
		$days       = lisfinity_get_all_months();
		$model      = new StatModel();

		if ( $product ) {
			$result = $model->where( [
				[ 'product_id', $product ],
				[ 'YEAR(FROM_UNIXTIME(date))', $year ],
			], '', false )->get();
		} else {
			$result = $model->where( [
				[ 'user_id', '=', $data['user_id'] ],
				[ 'YEAR(FROM_UNIXTIME(date))', $year ],
			], '', false )->get();
		}

		if ( ! empty( $result ) ) {
			foreach ( $days as $slug => $day ) {
				foreach ( $result as $row ) {
					if ( date( 'F', $row->date ) === $day ) {
						if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
							$chart_data['conversions'][ $day ] = 0;
						}
						if ( ! isset( $chart_data['leads'][ $day ] ) ) {
							$chart_data['leads'][ $day ] = 0;
						}
						if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
							$chart_data[ $row->type ][ $day ] = 0;
						}
						$chart_data[ $row->type ][ $day ] += (int) $row->count;
						if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[2][ $day ] ) && $chart_data[2][ $day ] !== 0 ) {
							$chart_data['conversions'][ $day ] = number_format_i18n( ( (int) $chart_data[2][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
						}
						if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[3][ $day ] ) && $chart_data[3][ $day ] !== 0 ) {
							$chart_data['leads'][ $day ] = number_format_i18n( ( (int) $chart_data[3][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
						}
					} else {
						if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
							$chart_data[ $row->type ][ $day ] = 0;
						}
						if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
							$chart_data['conversions'][ $day ] = 0;
						}
						if ( ! isset( $chart_data['leads'][ $day ] ) ) {
							$chart_data['leads'][ $day ] = 0;
						}
					}
				}
			}
		}

		return $chart_data;
	}

	protected function get_last_month_data( $data, $product ) {
		$chart_data = [];
		$month      = date( 'm' );
		$year       = date( 'Y' );
		$days       = lisfinity_get_month_days( $month, $year );
		$model      = new StatModel();

		if ( $product ) {
			$result = $model->where( [
				[ 'product_id', $product ],
				[ 'MONTH(FROM_UNIXTIME(date))', $month ],
			], '', false )->get();
		} else {
			$result = $model->where( [
				[ 'user_id', '=', $data['user_id'] ],
				[ 'MONTH(FROM_UNIXTIME(date))', $month ],
			], '', false )->get();
		}

		if ( ! empty( $result ) ) {
			foreach ( $days as $day => $interval ) {
				foreach ( $result as $row ) {
					if ( $row->date == $interval ) {
						if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
							$chart_data['conversions'][ $day ] = 0;
						}
						if ( ! isset( $chart_data['leads'][ $day ] ) ) {
							$chart_data['leads'][ $day ] = 0;
						}
						if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
							$chart_data[ $row->type ][ $day ] = 0;
						}
						$chart_data[ $row->type ][ $day ] += (int) $row->count;
						if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[2][ $day ] ) && $chart_data[2][ $day ] !== 0 ) {
							$chart_data['conversions'][ $day ] = number_format_i18n( ( (int) $chart_data[2][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
						}
						if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[3][ $day ] ) && $chart_data[3][ $day ] !== 0 ) {
							$chart_data['leads'][ $day ] = number_format_i18n( ( (int) $chart_data[3][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
						}
					} else {
						if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
							$chart_data[ $row->type ][ $day ] = 0;
						}
						if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
							$chart_data['conversions'][ $day ] = 0;
						}
						if ( ! isset( $chart_data['leads'][ $day ] ) ) {
							$chart_data['leads'][ $day ] = 0;
						}
					}
				}
			}
		}

		return $chart_data;
	}

	protected function get_last_week_data( $data, $product ) {
		$chart_data = [];
		$days       = lisfinity_get_last_days( 7 );
		$model      = new StatModel();

		if ( $product ) {
			$result = $model->where( [
				[ 'product_id', $product ],
				[ 'date', '>=', 'UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY))' ],
			] )->get();
		} else {
			$result = $model->where( [
				[ 'user_id', '=', $data['user_id'] ],
				[ 'date', '>=', 'UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY))' ],
			] )->get();
		}

		if ( empty( $result ) ) {
			return false;
		}

		foreach ( $days as $day ) {
			foreach ( $result as $row ) {
				if ( $row->date == strtotime( $day ) ) {
					if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
						$chart_data['conversions'][ $day ] = 0;
					}
					if ( ! isset( $chart_data['leads'][ $day ] ) ) {
						$chart_data['leads'][ $day ] = 0;
					}
					if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
						$chart_data[ $row->type ][ $day ] = 0;
					}
					$chart_data[ $row->type ][ $day ] += (int) $row->count;
					if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[2][ $day ] ) && $chart_data[2][ $day ] !== 0 ) {
						$chart_data['conversions'][ $day ] = number_format_i18n( ( (int) $chart_data[2][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
					}
					if ( isset( $chart_data[1][ $day ] ) && $chart_data[1][ $day ] !== 0 && isset( $chart_data[3][ $day ] ) && $chart_data[3][ $day ] !== 0 ) {
						$chart_data['leads'][ $day ] = number_format_i18n( ( (int) $chart_data[3][ $day ] / (int) $chart_data[1][ $day ] ) * 100, 2 );
					}
				} else {
					if ( ! isset( $chart_data[ $row->type ][ $day ] ) ) {
						$chart_data[ $row->type ][ $day ] = 0;
					}
					if ( ! isset( $chart_data['conversions'][ $day ] ) ) {
						$chart_data['conversions'][ $day ] = 0;
					}
					if ( ! isset( $chart_data['leads'][ $day ] ) ) {
						$chart_data['leads'][ $day ] = 0;
					}
				}
			}
		}

		return $chart_data;
	}

	public function store_stat( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();

		if ( empty( $data['type'] ) || empty( $data['product_id'] ) ) {
			return false;
		}

		$data['user_id'] = get_post_meta( $data['product_id'], '_product-business', true );

		$model = new StatModel();
		$model->update_stat( $data );

		return $data;
	}

}
