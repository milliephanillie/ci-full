<?php


namespace Lisfinity\REST_API\Reports;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\Reports\ReportModel;

class ReportsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'report_submit'  => [
			'path'                => '/report/store',
			'callback'            => 'submit_report',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'report_options' => [
			'path'                => '/report/options',
			'callback'            => 'get_report_options',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
	];

	public function submit_report( \WP_REST_Request $request_data ) {
		$data      = $request_data;
		$user_id   = get_current_user_id();
		$user_data = get_userdata( $user_id );
		$result    = [];

		if ( empty( $data['product'] ) ) {
			$result['error']               = true;
			$result['errors']['general'][] = __( 'Product has to be provided.', 'lisfinity-core' );
		}

		if ( $this->is_reported_by_the_user( $data['product'] ) ) {
			$result['error']               = true;
			$result['errors']['general'][] = __( 'You have already reported this product.', 'lisfinity-core' );
		}

		if ( empty( $data['reason'] ) && 'no' !== carbon_get_theme_option( 'report-reasons-enable' ) ) {
			$result['error']            = true;
			$result['errors']['reason'] = __( 'A report reasons has to be selected.', 'lisfinity-core' );
		}

		if ( empty( $data['message'] ) ) {
			$result['error']             = true;
			$result['errors']['message'] = __( 'A report message has to be provided.', 'lisfinity-core' );
		}

		$args = [
			'post_status' => 'publish',
			'post_type'   => ReportModel::$type,
			'post_author' => $user_id,
		];

		if ( ! empty ( $result['error'] ) ) {
			return $result;
		}

		$report_id = wp_insert_post( $args );
		if ( is_wp_error( $report_id ) ) {
			$result['error']    = true;
			$result['wp_error'] = __( 'WordPress error', 'lisfinity-core' );
		}
		wp_update_post( [
			'ID'         => $report_id,
			'post_title' => sprintf( __( 'Report: %s', 'lisfinity-core' ), $report_id ),
		] );

		carbon_set_post_meta( $report_id, 'report-product', $data['product'] );
		carbon_set_post_meta( $report_id, 'report-user-id', $user_id );
		carbon_set_post_meta( $report_id, 'report-user-email', $user_data->user_email );
		carbon_set_post_meta( $report_id, 'report-user-ip', lisfinity_get_ip_address() );
		carbon_set_post_meta( $report_id, 'report-reason', sanitize_text_field( $data['reason'] ) );
		carbon_set_post_meta( $report_id, 'report-message', sanitize_textarea_field( $data['message'] ) );
		carbon_set_post_meta( $report_id, 'report-status', 'pending' );

		// todo create admin email notification for a new report.
		$business = carbon_get_post_meta( $data['product'], 'product-business' );
		// todo integrate emails and notifications here!.
		$notification_data = [
			'user_id'     => $user_id,
			'type'        => 1,
			'product_id'  => $data['product'],
			'business_id' => $business,
			'parent_id'   => $report_id,
			'parent_type' => 3,
			'status'      => 0,
		];

		$notification_model = new NotificationModel();
		$notification_model->store_notification( $notification_data );

		$result['success'] = true;
		$result['message'] = __( 'Report has been successfully submitted.', 'lisfinity-core' );

		return $result;
	}

	public function get_report_options() {
		$result['reasons'] = lisfinity_report_reasons();

		return $result;
	}

	protected function is_reported_by_the_user( $id ) {
		global $wpdb;
		$sql     = "select post_id from $wpdb->postmeta where meta_key='_report-user-id' and meta_value=%d";
		$results = $wpdb->get_col( $wpdb->prepare( $sql, get_current_user_id() ) );
		$reports = [];

		foreach ( $results as $result ) {
			$report = carbon_get_post_meta( $result, 'report-product' );
			if ( $id == $report ) {
				$reports[] = $report;
			}
		}
		if ( ! empty( $reports ) ) {
			return true;
		}

		return false;
	}

}
