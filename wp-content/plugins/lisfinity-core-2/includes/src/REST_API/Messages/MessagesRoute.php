<?php


namespace Lisfinity\REST_API\Messages;

use Lisfinity\Models\Messages\ChatModel;
use Lisfinity\Models\Notifications\NotificationModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Messages\MessageModel as MessageModel;

class MessagesRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'chats'          => [
			'path'                => '/chats/(?P<product>\d+)',
			'rest_path'           => '/chats',
			'callback'            => 'get_chats',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'GET',
		],
		'messages'       => [
			'path'                => '/messages',
			'callback'            => 'get_messages',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'messages_chat'  => [
			'rest_path'           => '/messages',
			'path'                => '/messages/(?P<chat>\d+)',
			'callback'            => 'get_chat_messages',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'GET',
		],
		'message_submit' => [
			'path'                => '/messages/store',
			'callback'            => 'submit_message',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'message_update' => [
			'path'                => '/messages/update',
			'callback'            => 'update_message',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	/**
	 * Get messages for the given chat id. Used when owner or the agents
	 * are opening chat from their dashboard.
	 * -----------------------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return mixed
	 */
	public function get_chat_messages( WP_REST_Request $request_data ) {
		$data          = $request_data->get_params();
		$message_model = new MessageModel();
		$user_id       = get_current_user_id();
		$business      = lisfinity_get_premium_profile_id( $user_id );

		// mark messages as seen before loading them.
		$message_model->set( 'status', 1 )
		              ->where( [ [ 'chat_id', (int) $data['chat'] ], [ 'sender_id', '<>', $business ] ], '', false )
		              ->update();

		// mark notifications as read too.
		$notification_model = new NotificationModel();
		$chat_model         = new ChatModel();
		// $data['chat'] === product_id here!
		$chat_data = $chat_model->where( 'id', $data['chat'] )->get( '1', '', 'product_id' );
		$chat      = array_shift( $chat_data );

		$messages = $chat_model->join( $message_model->db, 'chat_id', 'id' )->where( 'id', $data['chat'] )->get( '', "order by {$message_model->db}.created_at ASC" );

		$message_ids = join( ',', array_column( $messages, 'id' ) );

		$notification_model->set( 'status', 1 )->where( [
			[ 'parent_type', 1 ],
			[ 'product_id', $chat->product_id ],
			[ 'business_id', $business ],
			[ 'parent_id', 'IN', "({$message_ids})" ],
		] )->update();

		return $this->prepare_chat_messages( $messages, $business );
	}

	/**
	 * Prepare chat messages with the additional information
	 * that will be used when the messenger is loaded.
	 * -----------------------------------------------------
	 *
	 * @param $messages
	 * @param $user_id
	 *
	 * @return mixed
	 */
	protected function prepare_chat_messages( $messages, $user_id ) {
		if ( empty( $messages ) ) {
			return $messages;
		}

		$agent         = lisfinity_get_agent( get_current_user_id() );
		$current_user  = $agent->owner_id ?? get_current_user_id();
		$user_business = lisfinity_get_premium_profile_id( $current_user );
		$is_blocked    = carbon_get_post_meta( $user_business, 'blocked-profiles' );
		$blocked_users = array_column( $is_blocked, 'id' );

		foreach ( $messages as $message ) {
			$sender_id           = get_post_meta( $message->sender_id, '_product-owner', true );
			$message->is_blocked = in_array( $message->sender_id, $blocked_users );
			$message->is_author  = $message->sender_id == $user_business;

			// format human readable time differences
			$created_time      = human_time_diff( strtotime( $message->created_at ), current_time( 'timestamp' ) );
			$created_time_text = sprintf( __( '%s ago', 'lisfinity-core' ), $created_time );
			$message->created  = $created_time_text;

			if ( $message->created_at !== $message->updated_at ) {
				$edited_time      = human_time_diff( strtotime( $message->updated_at ), current_time( 'timestamp' ) );
				$edited_time_text = sprintf( __( 'Edited %s ago', 'lisfinity-core' ), $edited_time );
				$message->edited  = $edited_time_text;
			}

			$sender                   = get_userdata( $sender_id );
			$sender_data['sender_id'] = $message->sender_id;
			$sender_data['avatar']    = lisfinity_get_avatar_url( $sender_id );
			$message->sender_data     = $sender_data;
		}

		$messages = $this->format_messages_meta( $messages );

		return $messages;
	}

	/**
	 * Get available chats when product owner or the agent
	 * are visiting their product messages page or when the buyer
	 * is loading chat messages from the notifications board in dashboard.
	 * -------------------------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return mixed
	 */
	public function get_chats( WP_REST_Request $request_data ) {
		$data          = $request_data->get_params();
		$message_model = new MessageModel();

		$agent             = lisfinity_get_agent( $data['sender_id'] );
		$data['sender_id'] = $agent->owner_id ?? $data['sender_id'];
		// if the user is not owner or agent.
		if ( ! empty( $data['sender_id'] ) ) {
			$message_model = new MessageModel();
			$chat_model    = new ChatModel();

			// load messages.
			$sender_business = lisfinity_get_premium_profile_id( $data['sender_id'] );
			$messages        = $message_model->join( $chat_model->db, 'id', 'chat_id' )
			                                 ->where( [
				                                 [ "{$chat_model->db}.product_id", $data['product'] ],
				                                 [ "{$chat_model->db}.sender_id", $sender_business ],
			                                 ], '', false )
			                                 ->get( '', "ORDER BY {$message_model->db}.id ASC",
				                                 "{$message_model->db}.id,
				                                 owner_id,
				                                 chat_id,
				                                 message,
				                                 {$message_model->db}.status,
				                                 {$message_model->db}.sender_id,
				                                 {$message_model->db}.created_at,
				                                 {$message_model->db}.updated_at"
			                                 );

			// mark messages as seen before loading them.
			$message_model->set( 'status', 1 )
			              ->where( [
				              [ 'chat_id', $messages[0]->chat_id ],
				              [ 'sender_id', $sender_business ]
			              ], '', false )
			              ->update();

			// mark notifications as read too.
			$notification_model = new NotificationModel();
			$chat_data          = $chat_model->where( 'id', $messages[0]->chat_id )->get( '1', '', 'product_id' );
			$chat               = array_shift( $chat_data );
			$notification_model->set( 'status', 1 )->where( [
				[ 'parent_type', 1 ],
				[ 'product_id', $chat->product_id ],
				[ 'business_id', $sender_business ],
			] )->update();

			return $this->prepare_chat_and_users_for_buyer( $messages, $data['sender_id'], $data['product'] );

		} else { // if user is owner or agent
			$chats = $message_model->get_product_chats( $data['product'] );

			return $this->prepare_chats_and_users_for_owner( $chats );
		}

	}

	/**
	 * Prepare chat messages when the buyer is accessing them
	 * from its dashboard page.
	 * ------------------------------------------------------
	 *
	 * @param $messages
	 * @param $user_id
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function prepare_chat_and_users_for_buyer( $messages, $user_id, $product_id ) {
		$owner_id      = get_post_meta( $product_id, '_product-owner', true );
		$business      = get_post_meta( $product_id, '_product-business', true );
		$blocked       = carbon_get_post_meta( $business, 'blocked-profiles' );
		$blocked_users = array_column( $blocked, 'id' );

		foreach ( $messages as $message ) {

			// format human readable time differences
			$created_time      = human_time_diff( strtotime( $message->created_at ), current_time( 'timestamp' ) );
			$created_time_text = sprintf( __( '%s ago', 'lisfinity-core' ), $created_time );
			$message->created  = $created_time_text;

			if ( $message->created_at !== $message->updated_at ) {
				$edited_time      = human_time_diff( strtotime( $message->updated_at ), current_time( 'timestamp' ) );
				$edited_time_text = sprintf( __( 'Edited %s ago', 'lisfinity-core' ), $edited_time );
				$message->edited  = $edited_time_text;
			}

			$premium_profile         = lisfinity_get_premium_profile_id( $user_id );
			$is_blocked              = in_array( $message->sender_id, $blocked_users );
			$message->is_blocked     = $is_blocked;
			$message->is_author      = $premium_profile == $message->sender_id;
			$message->sender_profile = $premium_profile;
			$message->business_title = get_the_title( $message->sender_id );
			$message->thumbnail      = has_post_thumbnail( $message->sender_id ) ? get_the_post_thumbnail_url( $message->sender_id ) : false;
		}

		return $messages;
	}

	/**
	 * Prepare chat messages when the product owner or agent
	 * is accessing them from their product messages page.
	 * -----------------------------------------------------
	 *
	 * @param $chats
	 * @param $business_id
	 *
	 * @return mixed
	 */
	public function prepare_chats_and_users_for_owner( $chats ) {
		$messages_model = new MessageModel();

		// if there are no available chats return false.
		if ( empty( $chats ) ) {
			return $chats;
		}

		foreach ( $chats as $chat ) {
			$message_count       = $messages_model->where( [
				[ 'chat_id', $chat->chat_id ],
				[ 'status', 0 ],
				[ 'sender_id', '<>', $chat->owner_id ],
			] )->value( 'id', 'count' );
			$chat->message_count = $message_count[0]->{'count(id)'};

			$sender_data['sender_id'] = $chat->sender_id;
			$chat->sender_data        = $sender_data;
			$chat->business_title     = get_the_title( $chat->sender_id );
			$chat->thumbnail          = has_post_thumbnail( $chat->sender_id ) ? get_the_post_thumbnail_url( $chat->sender_id ) : lisfinity_get_avatar_url( $chat->sender_id );
		}

		return $chats;
	}

	/**
	 * Get all messages for the given chat room id
	 * -------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|bool|mixed|object|string|void|null
	 */
	public function get_messages( WP_REST_Request $request_data ) {
		$data           = $request_data->get_params();
		$messages_model = new MessageModel();

		// return if chat room is not specified.
		$sender_business = lisfinity_get_premium_profile_id( $data['sender_id'] );
		if ( ! isset( $data['chat_id'] ) && ! empty( $data['sender_id'] ) ) {
			// check if user has any messages.
			$data['chat_id'] = $messages_model->user_has_messages( $data['product_id'], $sender_business, true );
		}

		// get messages.
		$messages = $messages_model->get_messages( $data['chat_id'] );

		if ( $messages ) {
			$messages = $this->format_messages_meta( $messages );
			$messages = $this->prepare_chat_messages( $messages, $data['receiver_id'] );
		}

		$receiver_business = lisfinity_get_premium_profile_id( $data['receiver_id'] );
		$blocked_users     = carbon_get_post_meta( $receiver_business, 'blocked-profiles' );
		$blocked_users     = array_column( $blocked_users, 'id' );
		$is_blocked        = in_array( $sender_business, $blocked_users );
		if ( $is_blocked ) {
			return 'blocked';
		}

		return $messages;
	}

	/**
	 * Format message metadata
	 * -----------------------
	 *
	 * @param $messages
	 *
	 * @return mixed
	 */
	protected function format_messages_meta( $messages ) {
		foreach ( $messages as $message ) {
			$userdata            = get_userdata( get_post_meta( $message->sender_id, '_product-owner' ) );
			$message->post_title = get_the_title( $message->sender_id );
			$message->thumbnail  = has_post_thumbnail( $message->sender_id ) ? get_the_post_thumbnail_url( $message->sender_id ) : ( $userdata ? lisfinity_get_avatar_url( $userdata->ID ) : false );
		}

		return $messages;
	}

	/**
	 * Mark message as seen
	 * --------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|int|void
	 */
	public function update_message( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		// Error | if message data hasn't been set.
		if ( empty( $data ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Data cannot be empty.', 'lisfinity-core' );

			return wp_send_json( $result );
		}

		// Error | if chat id hasn't been specified.
		if ( empty( $data['id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Chat id is not set.', 'lisfinity-core' );

			return wp_send_json( $result );
		}

		$user_id = get_current_user_id();
		// update status of the messages to 'seen'.
		if ( 'status' === $data['type'] ) {
			$message_model = new MessageModel();

			// mark messages as seen before loading them.
			$business = lisfinity_get_premium_profile_id( $user_id );
			$message_model->set( 'status', 1 )
			              ->where( [ [ 'chat_id', (int) $data['id'] ], [ 'sender_id', '<>', $business ] ], '', false )
			              ->update();

			// mark notifications as read too.
			$notification_model = new NotificationModel();
			$chat_model         = new ChatModel();
			$chat_data          = $chat_model->where( 'id', $data['id'] )->get( '1', '', 'product_id' );
			$chat               = array_shift( $chat_data );
			$notification_model->set( 'status', 1 )->where( [
				[ 'parent_type', 1 ],
				[ 'parent_id', $chat->product_id ],
				[ 'business_id', $business ],
			] )->update();

			$result['success'] = true;
			$result['message'] = __( 'Message marked as read.', 'lisfinity-core' );
		}

		// block a user.
		if ( 'block' === $data['type'] ) {
			$chat_model      = new ChatModel();
			$business        = lisfinity_get_premium_profile_id( $user_id );
			$user_to_block   = $chat_model->where( 'id', $data['id'] )->value( 'sender_id', '', '1' );
			$blocked_users   = carbon_get_post_meta( $business, 'blocked-profiles' );
			$block_data      = [
				'id'      => $user_to_block[0]->sender_id,
				'subtype' => 'premium_profile',
				'type'    => 'post',
				'value'   => "post:premium_profile:{$user_to_block[0]->sender_id}"
			];
			$blocked_users[] = $block_data;
			carbon_set_post_meta( $business, 'blocked-profiles', $blocked_users );

			$result['success'] = true;
			$result['message'] = __( 'User has been successfully blocked.', 'lisfinity-core' );
		}

		return wp_send_json( $result );
	}

	// todo below method should be moved to message model.

	/**
	 * Submit a message handler
	 * ------------------------
	 *
	 * @param WP_REST_Request $request_data
	 */
	public function submit_message( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		// Error | if message data hasn't been set.
		if ( empty( $data ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Message data cannot be empty.', 'lisfinity-core' );

			return wp_send_json( $result );
		}

		// Error | if product id hasn't been set.
		if ( empty( $data['product_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Message product_id cannot be empty.', 'lisfinity-core' );

			return wp_send_json( $result );
		}

		// Error | if user_id is not set.
		if ( empty( $data['sender_id'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Message user is not set.', 'lisfinity-core' );

			return wp_send_json( $result );
		}
		$agent             = lisfinity_get_agent( $data['sender_id'] );
		$user_id           = $data['sender_id'];
		$data['sender_id'] = $agent->owner_id ?? $data['sender_id'];

		// Error | if user_id is not set.
		if ( empty( $data['message'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'Message content cannot be empty.', 'lisfinity-core' );

			return wp_send_json( $result );
		}

		// if chat_id is not set create one.
		$sender_business = lisfinity_get_premium_profile( $data['sender_id'] );
		if ( empty( $sender_business ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The message could not be sent because there is no Business Profile connected to the user.', 'lisfinity-core' );
			wp_send_json( $result );
		}
		$data['sender_id']  = $sender_business->ID;
		$chat_model         = new ChatModel();
		$notification_owner = false;
		if ( empty( $data['chat_id'] ) ) {

			// Error | if product owner tries to send message to himself through product single page form.
			$product_owner = carbon_get_post_meta( $data['product_id'], 'product-business' );
			if ( $product_owner == $data['sender_id'] ) {
				$result['error']   = true;
				$result['message'] = __( 'You cannot send a message directly to your own product.', 'lisfinity-core' );

				return wp_send_json( $result );
			}

			// assign message to a chat room.
			$room_data       = [
				'product_id' => $data['product_id'],
				'owner_id'   => $product_owner,
				'sender_id'  => $data['sender_id'],
				'topic'      => sprintf( __( 'Inquiry about: %s', 'lisfinity-core' ), get_the_title( $data['product_id'] ) ),
			];
			$data['chat_id'] = $chat_model->store_message_room( $room_data );

			// Error | test once again for chat id
			if ( ! isset( $data['chat_id'] ) || 0 === $data['chat_id'] ) {
				$result['error']   = true;
				$result['message'] = __( 'Chat room is not existent.', 'lisfinity-core' );
				wp_send_json( $result );
			}
			$notification_owner = true;
		}

		// store message
		$message_model     = new MessageModel();
		$message_id        = $message_model->store_message( $data );
		$result['success'] = true;
		$result['message'] = __( 'Message sent.', 'lisfinity-core' );

		$notification_business = $chat_model->where( 'id', $data['chat_id'] )->get( '1', '', 'owner_id, sender_id' );
		$business              = array_shift( $notification_business );
		$business              = isset( $data['to_chat'] ) ? $business->owner_id : $business->sender_id;
		// todo integrate emails and notifications here!.
		$notification_data = [
			'user_id'     => $user_id,
			'type'        => 1,
			'product_id'  => $data['product_id'],
			'business_id' => $notification_owner ? $product_owner : $business,
			'parent_id'   => $message_id,
			'parent_type' => 1,
			'status'      => 0,
		];

		$notification_model = new NotificationModel();
		$notification_model->store_notification( $notification_data );

		$owner_user_id = carbon_get_post_meta( $data['product_id'], 'product-owner' );
		if ( lisfinity_is_enabled( lisfinity_get_option( 'email-new-message' ) ) && get_user_meta( $owner_user_id, '_email_subscription|new_message', true ) ) {
			// send an email.
			$my_account_id     = lisfinity_get_page_id( 'page-account' );
			$product_permalink = get_permalink( $my_account_id ) . 'ad/' . $data['product_id'] . '/messages';
			$subject           = sprintf( __( '%s | New Message Received', 'lisfinity-core' ), get_option( 'blogname' ) );
			$body              = sprintf( __( 'You have receive a new message for the listing %s <br />', 'lisfinity-core' ), '<a href="' . esc_url( $product_permalink ) . '">' . get_the_title( $data['product_id'] ) . '</a>' );
			$body              .= sprintf( __( 'Message: %s', 'lisfinity-core' ), $data['message'] );

			$business_post = get_post( $business );
			if ( ! is_wp_error( $business_post ) ) {
				$user_data = get_userdata( $business_post->post_author );
				$headers   = [ 'Content-Type: text/html; charset=UTF-8' ];
				$mail      = wp_mail( $user_data->user_email, $subject, $body, $headers );
			}

		}

		// send results.
		wp_send_json( $result );
	}

}

