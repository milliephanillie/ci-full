<?php
/**
 * Model for our custom Bids functionality.
 *
 * @author pebas
 * @package lisfinity-bids
 * @version 1.0.0
 */

namespace Lisfinity\Models\Notifications;

use Lisfinity\Abstracts\Model as Model;
use Lisfinity\Models\Bids\BidModel;
use Lisfinity\Models\Messages\ChatModel;
use Lisfinity\Models\Messages\MessageModel;

/**
 * Class NotificationModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class NotificationModel extends Model {

	public $table = 'notifications';

	public $version = '1.0.0';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'user_id'     => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'type'        => [
				'type'  => 'tinyint(2)',
				'value' => 'NULL',
			],
			'business_id' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'parent_id'   => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'parent_type' => [
				'type'  => 'tinyint(2)',
				'value' => 'NULL',
			],
			'status'      => [
				'type'  => 'tinyint(2)',
				'value' => 'NULL',
			],
		];

		return $this->fields;
	}

	/**
	 * Store notification to the database
	 * ----------------------------------
	 *
	 * @param $data
	 *
	 * @return false|int|void
	 */
	public function store_notification( $data ) {
		$bid_values = [
			$data['user_id'],
			$data['type'],
			$data['business_id'],
			$data['product_id'],
			$data['parent_id'],
			$data['parent_type'],
			$data['status'],
		];

		return $this->store( $bid_values );
	}

	public function get_notifications( $data ) {
		$notifications = [];

		if ( empty( $data['business'] ) ) {
			$notifications['error']   = true;
			$notifications['message'] = __( 'The business ID has not been provided.', 'lisfinity-core' );

			return $notifications;
		}

		$model = new NotificationModel();
		if ( ! isset( $data['business'] ) ) {
			$data['business'] = $data['business_id'];
		}
		$notifications = $model->where( [
			[ 'business_id', $data['business'] ],
			[ 'status', 0 ],
		] )->get( '', 'ORDER BY created_at DESC' );

		return ! empty( $notifications ) ? $this->format_notifications_data( $notifications ) : [];
	}

	protected function format_notifications_data( $notifications ) {
		foreach ( $notifications as $notification ) {
			$notification->created_human     = human_time_diff( strtotime( $notification->created_at ), current_time( 'timestamp' ) );
			$notification->type_human        = lisfinity_get_human_notification_master_type( $notification->type );
			$notification->parent_type_human = lisfinity_get_human_notification_type( $notification->parent_type );
			$notification->parent_type_title = lisfinity_get_human_notification_type_title( $notification->parent_type );

			if ( $notification->parent_type == 1 ) {
				$chat_model    = new ChatModel();
				$message_model = new MessageModel();
				$my_chats      = $chat_model->where( 'owner_id', $notification->business_id )->orWhere( [
					[ 'sender_id', '=', $notification->business_id ],
				] )->get( '' );
				$chats         = [];
				foreach ( $my_chats as $chat ) {
					$chats[] = $chat->id;
				}
				$my_chats = implode( ',', $chats );
				$message  = $message_model->where( [
					[ 'sender_id', '<>', $notification->business_id ],
					[ 'status', '<>', 2 ],
					[ 'chat_id', 'IN', "({$my_chats})" ],
					[ 'created_at', $notification->created_at ],
				], '', false )->get();

				$notification->data = $message_model->format_message_data( array_shift( $message ) );
			} else if ( $notification->parent_type == 2 ) {
				$bid_model          = new BidModel();
				$bid                = $bid_model->where( [
					[ 'bidder_id', $notification->user_id ],
					[ 'product_id', $notification->product_id ],
					[ 'created_at', $notification->created_at ],
				] )->get();
				$notification->data = $bid_model->format_bid_data( array_shift( $bid ) );
			} else {
				if ( 0 !== $notification->user_id ) {
					$user_data                  = get_userdata( $notification->user_id );
					$premium_profile            = lisfinity_get_premium_profile( $notification->user_id );
					$meta_data['post_title']    = $premium_profile ? $premium_profile->post_title : $user_data->display_name;
					$meta_data['thumbnail']     = has_post_thumbnail( $premium_profile->ID ) ? get_the_post_thumbnail_url( $premium_profile->ID ) : lisfinity_get_avatar_url( $notification->user_id );
					$meta_data['product_title'] = get_the_title( $notification->parent_id );
					$notification->data         = $meta_data;
				}
			}
		}

		return $notifications;
	}

}
