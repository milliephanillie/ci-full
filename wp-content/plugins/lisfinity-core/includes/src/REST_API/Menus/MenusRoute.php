<?php


namespace Lisfinity\REST_API\Menus;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Helpers\WC_Helper;

class MenusRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_mobile_menu' => [
			'path'                => '/menus/mobile',
			'callback'            => 'get_mobile_menu',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
	];

	public function get_mobile_menu() {
		$result = [];
		$menu   = [];
		$items  = $this->get_menu_items_by_slug( 'mobile-menu' );
		if ( ! $items ) {
			$items = $this->get_menu_items_by_slug( 'main-menu' );
		}

		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$menu[ $item->ID ]['ID']        = $item->ID;
				$menu[ $item->ID ]['title']     = $item->title;
				$menu[ $item->ID ]['parent']    = (int) $item->menu_item_parent;
				$menu[ $item->ID ]['permalink'] = $item->url;
				$menu[ $item->ID ]['rel']       = $item->xfn;
				$menu[ $item->ID ]['order']     = $item->menu_order;
			}
		}

		$user['login_link']   = get_permalink( lisfinity_get_page_id( 'page-login' ) );
		$user['account_link'] = get_permalink( lisfinity_get_option( 'page-account' ) );
		if ( is_user_logged_in() ) {
			$user_data            = get_userdata( get_current_user_id() );
			$user['display_name'] = $user_data->display_name;
		}
		$user['avatar'] = lisfinity_get_avatar_url( get_current_user_id() );
		$result['user'] = $user;

		$result['menu'] = $menu;

		$result['socials'] = $this->get_social_networks();

		$result['cart'] = $this->get_cart();

		return $result;
	}

	public function get_cart() {
		$enable_cart = '1' === lisfinity_get_option( 'header-cart' );
		if ( ! $enable_cart ) {
			return false;
		}
		$wc_helper = new WC_Helper();
		$wc_helper->check_prerequisites();
		$cart          = [];
		$cart['count'] = WC()->cart->get_cart_contents_count();
		$cart['url']   = wc_get_cart_url();

		return $cart;
	}

	protected function get_social_networks() {
		$socials_formatted = [];
		$socials           = lisfinity_get_option( 'mobile-menu-social' );

		if ( ! empty( $socials ) ) {
			foreach ( $socials as $social ) {
				$url = lisfinity_get_option( "mobile-menu-{$social}" );
				if ( ! empty( $url ) ) {
					$socials_formatted[ $social ] = [
						'url'  => $url,
						'icon' => lisfinity_load_social_icon_svg( $social )
					];
				}
			}
		}

		return $socials_formatted;
	}

	public function get_menu_items_by_slug( $menu_slug ) {

		$menu_items = [];

		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_slug ] ) ) {
			$menu = get_term( $locations[ $menu_slug ] );

			$menu_items = wp_get_nav_menu_items( $menu->term_id );


		}

		return $menu_items;

	}

}
