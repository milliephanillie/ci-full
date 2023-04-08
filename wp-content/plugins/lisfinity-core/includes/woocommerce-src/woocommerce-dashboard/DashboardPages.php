<?php
/**
 * Class for our custom endpoints used
 * in a user dashboard.
 *
 * @author pebas
 * @package lisfinity/endpoint
 * @version 1.0.0
 */

/**
 * Class DashboardPages
 * ---------------------
 *
 * @package Lisfinity
 */
class DashboardPages {

	/**
	 * Variable that stores the registered pages
	 * -----------------------------------------
	 *
	 * @var array $pages
	 */
	public $pages = [];
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'add_endpoints' ] );
		add_action( 'after_setup_theme', [ $this, 'register_pages' ] );
		add_action( 'query_vars', [ $this, 'add_query_vars' ], 0 );
		add_action( 'woocommerce_account_menu_items', [ $this, 'new_menu_items' ] );
	}

	/**
	 * Register pages for a user dashboard
	 * -----------------------------------
	 */
	public function register_pages() {
		$order = 0;
		// ads listing page.
		$this->set_page(
			[
				'endpoint'     => 'ads',
				'title'        => __( 'Ads', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'ad',
				'title'        => __( 'Ad', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'edit',
				'title'        => __( 'Edit', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		// ad submission page.
		$this->set_page(
			[
				'endpoint'     => 'submit',
				'title'        => __( 'Ad Submit', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		// packages page.
		$this->set_page(
			[
				'endpoint'     => 'packages',
				'title'        => __( 'Packages', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		// notification page.
		$this->set_page(
			[
				'endpoint'     => 'notifications',
				'title'        => __( 'Notifications', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		// messages.
		$this->set_page(
			[
				'endpoint'     => 'messages',
				'title'        => __( 'Messages', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/dashboard/messages/messages.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		// profile page.
		$this->set_page(
			[
				'endpoint'     => 'business',
				'title'        => __( 'Business Details', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'premium-profile',
				'title'        => __( 'Premium Profile', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);

		// user subscriptions
		$this->set_page(
			[
				'endpoint'     => 'subscriptions',
				'title'        => __( 'Subscriptions', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);

		// earnings.
		$this->set_page(
			[
				'endpoint'     => 'earnings',
				'title'        => __( 'Earnings', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'commissions',
				'title'        => __( 'Pending Commissions', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);

		// agents.
		$this->set_page(
			[
				'endpoint'     => 'agents',
				'title'        => __( 'Agents', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);

		// WooCommerce Specific
		$this->set_page(
			[
				'endpoint'     => 'account-edit',
				'title'        => __( 'account-edit', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'account-orders',
				'title'        => __( 'Orders', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
		$this->set_page(
			[
				'endpoint'     => 'account-downloads',
				'title'        => __( 'Downloads', 'lisfinity-core' ),
				'template'     => LISFINITY_CORE_DIR . 'templates/woocommerce/myaccount/my-account.php',
				'show_in_menu' => true,
				'order'        => $order += 1,
			]
		);
	}

	/**
	 * Register page to pages variable
	 * -------------------------------
	 *
	 * @param array $page
	 */
	public function set_page( $page ) {
		$this->pages[ $page['endpoint'] ] = $page;
	}

	/**
	 * Add custom dashboard pages query variables to
	 * WordPress query variables
	 * ---------------------------------------------
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		return array_merge( $vars, array_column( $this->pages, 'endpoint', 'endpoint' ) );
	}

	/**
	 * Add custom page endpoints to WooCommerce dashboard
	 * --------------------------------------------------
	 */
	public function add_endpoints() {
		if ( empty( $this->pages ) ) {
			return;
		}

		foreach ( $this->pages as $page ) {
			add_rewrite_endpoint( $page['endpoint'], EP_ROOT | EP_PAGES );
			add_action(
				"woocommerce_account_{$page['endpoint']}_endpoint",
				function () use ( $page ) {
					require_once $page['template'];
				}
			);
		}
	}

	/**
	 * Add custom pages to WooCommerce
	 * dashboard menu items
	 * --------------------------------
	 *
	 * @param $items
	 *
	 * @return array
	 */
	public function new_menu_items( $items ) {
		// Remove the logout menu item.
		unset( $items['orders'] );
		unset( $items['downloads'] );
		unset( $items['edit-address'] );
		unset( $items['edit-account'] );
		unset( $items['customer-logout'] );

		// Insert custom endpoints.
		$items += array_column(
			array_filter(
				$this->pages,
				function ( $page ) {
					return $page['show_in_menu'];
				}
			),
			'title',
			'endpoint'
		);

		// Sort items.
		foreach ( $items as $item_key => $item ) {
			if ( in_array( $item_key, array_keys( $this->pages ) ) ) {
				$items[ $item_key ] = $this->pages[ $item_key ];
			}

			if ( $item_key == 'dashboard' ) {
				$items['dashboard'] = array(
					'title' => __( 'My Account', 'lisfinity-core' ),
					'order' => 1,
				);
			}
		}

		$items = $this->sort_by_prop( $items, 'order' );

		foreach ( $items as $item_key => $item ) {
			if ( is_array( $item ) && ! empty( $item['title'] ) ) {
				$items[ $item_key ] = $item['title'];
			}
		}

		return $items;
	}

	/**
	 * Custom sorting of WooCommerce menu items
	 * ----------------------------------------
	 *
	 * @param $array
	 * @param $prop_name
	 * @param bool $reverse
	 *
	 * @return array
	 */
	public function sort_by_prop( $array, $prop_name, $reverse = false ) {
		$sorted = array();
		foreach ( $array as $item_key => $item ) {
			if ( ! is_array( $item ) ) {
				$item = array(
					'title'    => $item,
					'order'    => 25,
					'endpoint' => $item_key,
				);
			}

			if ( ! isset( $item[ $prop_name ] ) ) {
				$item[ $prop_name ] = 25;
			}

			if ( ! isset( $item['endpoint'] ) ) {
				$item['endpoint'] = $item_key;
			}

			$sorted[ $item[ $prop_name ] ][] = $item;
		}

		$reverse ? krsort( $sorted ) : ksort( $sorted );

		$result = [];
		foreach ( $sorted as $subArray ) {
			foreach ( $subArray as $item ) {
				$result[ $item['endpoint'] ] = $item;
			}
		}

		return $result;
	}

}

function lisfinity_dashboard() {
	return DashboardPages::instance();
}
