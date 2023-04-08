<?php


namespace Lisfinity\REST_API\Agents;


use Lisfinity\Abstracts\Route;
use Lisfinity\Models\Agents\AgentModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\ProductModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;

class AgentsRoute extends Route {
	/**
	 * Register Taxonomy Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'agent_action' => [
			'rest_path'           => '/agent',
			'path'                => '/agent/(?P<action>\S+)',
			'callback'            => 'agent_action',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];


	/**
	 * Do a defined action for a current user
	 * --------------------------------------
	 *
	 * @param \WP_REST_Request $request_data
	 *
	 * @return array|bool
	 */
	public function agent_action( \WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['action'] ) ) {
			return false;
		}

		if ( $data['action'] === 'new_agent' ) {
			$result = $this->new_agent( get_current_user_id(), $data );
		}

		if ( $data['action'] === 'delete' ) {
			$result = $this->delete_agent( $data );
		}

		return $result;
	}

	/**
	 * Save user search to the database
	 * --------------------------------
	 *
	 * @param $user_id
	 * @param $data
	 *
	 * @return int[]|mixed|\WP_Post[]
	 */
	protected function new_agent( $user_id, $data ) {
		$result = [];

		if ( empty( $user_id ) ) {
			$result['errors']['global'] = __( 'User ID cannot be found', 'lisfinity-core' );
		}

		if ( empty( $data['first_name'] ) ) {
			$result['errors']['first_name'] = __( 'First Name cannot be empty', 'lisfinity-core' );
		}

		if ( empty( $data['last_name'] ) ) {
			$result['errors']['last_name'] = __( 'Last Name cannot be empty', 'lisfinity-core' );
		}

		if ( empty( $data['username'] ) ) {
			$result['errors']['username'] = __( 'Username cannot be empty', 'lisfinity-core' );
		}

		if ( username_exists( $data['username'] ) ) {
			$result['errors']['username'] = __( 'Username already exists', 'lisfinity-core' );
		}

		if ( empty( $data['email'] ) ) {
			$result['errors']['email'] = __( 'Email cannot be empty', 'lisfinity-core' );
		}

		if ( email_exists( $data['email'] ) ) {
			$result['errors']['email'] = __( 'Email already exists', 'lisfinity-core' );
		}

		if ( empty( $data['password'] ) ) {
			$result['errors']['password'] = __( 'Password cannot be empty', 'lisfinity-core' );
		}

		if ( ! empty( $result['errors'] ) ) {
			$result['error'] = true;

			return $result;
		}

		$user_data = apply_filters(
			'lisfinity__new_agent_data', [
				'user_login' => $data['username'],
				'user_pass'  => $data['password'],
				'user_email' => $data['email'],
				'role'       => 'editor',
			]
		);

		$agent_id = wp_insert_user( $user_data );

		update_user_meta( $agent_id, 'first_name', $data['first_name'] );
		update_user_meta( $agent_id, 'last_name', $data['last_name'] );

		$agent_model = new AgentModel();

		$business_id = lisfinity_get_premium_profile_id( $user_id );
		$agent_model->store_agent( [
			'user_id'     => $agent_id,
			'business_id' => $business_id,
			'owner_id'    => $user_id,
			'permissions' => 'all',
		] );

		carbon_set_user_meta( $agent_id, 'verified', true );

		//todo implement email system
		$to      = $user_data['user_email'];
		$subject = sprintf( esc_html__( 'Welcome to %s', 'lisfinity-core' ), get_option( 'blogname' ) );
		$body    = sprintf( __( 'Your agent account has been created on %1$s for the business: %2$s. <br /> Your username is: %3$s <br />',
			'lisfinity-core' ), get_option( 'blogname' ), get_the_title( $business_id ), $user_data['user_login'] );
		$body    .= sprintf( esc_html__( 'Your password is: %s', 'lisfinity-core' ), $user_data['user_pass'] );

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );

		$result['success'] = true;
		$result['message'] = __( 'New agent has been successfully added', 'lisfinity-core' );

		return $result;
	}

	protected function delete_agent( $data ) {
		$result = [];

		if ( ! empty( $data['id'] ) && ! empty( $data['user_id'] ) ) {
			$model = new AgentModel();

			$model->where( 'id', $data['id'] )->destroy();

			if ( ! function_exists( 'wp_delete_user' ) ) {
				require_once ABSPATH . 'wp-admin/includes/user.php';
			}

			wp_delete_user( $data['user_id'], 0 );
		}

		$result['success'] = true;

		return $result;
	}

}
