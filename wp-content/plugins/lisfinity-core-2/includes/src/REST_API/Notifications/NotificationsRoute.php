<?php


namespace Lisfinity\REST_API\Notifications;

use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Messages\MessageRoomUsersModel;
use Lisfinity\Models\Messages\MessageRoomUsersModel as MessageUsersModel;
use Lisfinity\Models\Notifications\NotificationModel;
use phpDocumentor\Reflection\Types\Object_;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Messages\MessageModel as MessageModel;
use Lisfinity\Models\Messages\ChatModel as MessageRoomModel;

class NotificationsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'update_notifications'     => [
			'path'                => '/notifications/(?P<action>\S+)',
			'rest_path'           => '/notifications',
			'callback'            => 'update_notification',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'update_all_notifications' => [
			'path'                => '/notifications-all/(?P<action>\S+)',
			'rest_path'           => '/notifications-all',
			'callback'            => 'update_all_notifications',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function update_notification( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];
		$model  = new NotificationModel();

		if ( empty( $data['action'] ) ) {
			return false;
		}

		if ( $data['action'] === 'mark_as_read' ) {
			$result = $this->mark_as_read( $data, $model );
		}

		return $model->get_notifications( $data );
	}

	public function update_all_notifications( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];
		$model  = new NotificationModel();

		if ( empty( $data['action'] ) ) {
			return false;
		}

		if ( $data['action'] === 'mark_selected_as_read' ) {
			foreach ( $data['notifications'] as $notification ) {
				$result = $this->mark_as_read( $notification, $model );
			}
		}

		return $model->get_notifications( $data );
	}

	protected function mark_as_read( $data, $notification_model ) {
		$result = [];

		$notification_model->set( 'status', 1 )->where( 'id', $data['id'] )->update();
		if ( 1 == $data['type'] ) {
			$message_model = new MessageModel();
			if ( ! isset( $data['parent'] ) ) {
				$data['parent'] = $data['parent_id'];
			}
			$message_model->set( 'status', 1 )->where( 'id', $data['parent'] )->update();

			$result['success'] = true;
			$result['message'] = __( 'Message and notification marked as read', 'lisfinity-core' );
		}

		return $result;
	}

}
