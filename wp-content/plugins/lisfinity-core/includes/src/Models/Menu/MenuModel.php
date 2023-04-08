<?php


namespace Lisfinity\Models\Menu;


class MenuModel {

	/**
	 * Add submit ad button to the main menu
	 * -------------------------------------
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function add_submit_button( $items, $args ) {
		if ( empty( $args->walker ) || $args->menu_id === 'elementor-menu' ) {
			return $items;
		}
		$text = lisfinity_get_option( 'button-submit-text' );
		$link = lisfinity_get_correct_submit_button_permalink();

		$add_listing = '<li class="menu-item menu-item-submit ml-10 rounded">';
		$add_listing .= '<a class="btn__load flex-center py-12 px-30 rounded font-semibold text-white whitespace-no-wrap"  href="' . esc_url( $link ) . '" style="height: 45px;">';
		$add_listing .= '<svg id="submit-icon" version="1.1" width="15px" height="15px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 100 100" style="margin-right: 10px; enable-background:new 0 0 100 100; fill: #fff;" xml:space="preserve" class="pointer-events-none btn-submit-icon">
						 <path d="M88.3,48.2H52.8V12.8c0-1.6-1.3-2.8-2.8-2.8s-2.8,1.2-2.8,2.8v35.5H11.8C10.2,48.2,9,49.5,9,51s1.2,2.8,2.8,2.8h35.5v35.5
						 c0,1.5,1.2,2.8,2.8,2.8c1.6,0,2.8-1.3,2.8-2.8V53.8h35.5c1.5,0,2.8-1.2,2.8-2.8S89.8,48.2,88.3,48.2z"/>
						 </svg>';

		$add_listing .= '<img src="' . esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings-white.5e9e252706.svg' ) . '" class="hidden" alt="' . esc_html__( 'Ad Loader', 'lisfinity-core' ) . '" style="margin-right: 8px; margin-left: -10px; zoom: 0.6;"/>';
		$add_listing .= esc_html( $text );
		$add_listing .= '</a></li>';

		if ( '0' !== lisfinity_get_option( 'button-submit-enable' ) ) {
			$items = $items . $add_listing;
		}

		return $items;
	}

	/**
	 * Add login button to the main menu
	 * ---------------------------------
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function add_login_button( $items, $args ) {
		if ( empty( $args->walker ) || $args->menu_id === 'elementor-menu' ) {
			return $items;
		}
		if ( is_user_logged_in() || ! lisfinity_get_option( 'auth-enabled' ) ) {
			return $items;
		}

		$link  = get_permalink( lisfinity_get_page_id( 'page-login' ) );
		$login = '<li class="menu-item menu-item-login ml-10 rounded">';
		$login .= '<a class="flex-center py-12 px-30 rounded font-semibold text-white" href="' . esc_url( $link ) . '" title="' . esc_html__( 'Sign In', 'lisfinity-core' ) . '">';
		$login .= '<svg version="1.1" width="16px" height="16px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100; fill:#65d6ad;" xml:space="preserve">
								<path class="st0" d="M8.5,87.7"/>
								<g>
									<path d="M50,57c13.2,0,24-10.8,24-24S63.2,9,50,9C36.8,9,26,19.7,26,33S36.8,57,50,57z M50,14.5c10.2,0,18.5,8.3,18.5,18.5
										S60.2,51.5,50,51.5S31.5,43.2,31.5,33S39.8,14.5,50,14.5z"/>
									<path d="M97.9,86.2C84.7,74.5,67.7,68,50,68S15.3,74.5,2.1,86.2c-1.1,1-1.2,2.7-0.2,3.9c1,1.1,2.7,1.2,3.9,0.2
										C17.9,79.5,33.7,73.5,50,73.5s32.1,6,44.3,16.8c0.5,0.5,1.2,0.7,1.8,0.7c0.8,0,1.5-0.3,2.1-0.9C99.2,89,99.1,87.2,97.9,86.2z"/>
								</g>
							</svg>';
		$login .= '</a></li>';

		$items = $items . $login;

		return $items;
	}

	/**
	 * Add notifications, user avatar and compare buttons to the main menu
	 * -------------------------------------------------------------------
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function add_notifications_button( $items, $args ) {
		if ( empty( $args->walker ) || $args->menu_id === 'elementor-menu' ) {
			return $items;
		}
		$compare_enabled = 'custom' !== lisfinity_get_option( 'header-type' ) && ( '1' === lisfinity_get_option( 'header-compare' ) && '0' !== lisfinity_get_option( 'ads-compare' ) );
		$avatar_enabled  = 'custom' !== lisfinity_get_option( 'header-type' ) && '1' === lisfinity_get_option( 'header-avatar' );

		if ( is_user_logged_in() ) {
			if ( $avatar_enabled ) {
				$page_account_id   = lisfinity_get_page_id( 'page-account' );
				$account_permalink = get_permalink( $page_account_id );
				// add user avatar.
				$avatar = lisfinity_get_avatar_url();
				$items  .= '<li><a href="' . esc_url( $account_permalink ) . '" class="relative flex ml-20 w-44 h-44 rounded-xl overflow-hidden" title="' . esc_html__( 'My Account', 'lisfinity-core' ) . '"><img src="' . esc_url( $avatar ) . '" alt="' . esc_attr__( 'Agent', 'lisfinity-core' ) . '" class="absolute top-0 left-0 w-full h-full object-cover" /></a></li>';
			}
			// add compare button.
			if ( $compare_enabled ) {
				$items .= '<li id="compare--wrapper" class="relative leading-none compare--wrapper" title="' . esc_html__( 'Compare Listings', 'lisfinity-core' ) . '"></li>';
			}
			// add notifications button.
			$items .= '<li id="notifications--wrapper" class="relative leading-none notifications--wrapper" title="' . esc_html__( 'Notifications', 'lisfinity-core' ) . '"></li>';
		}

		return $items;
	}

	public function add_cart_button( $items, $args ) {
		if ( empty( $args->walker ) || $args->menu_id === 'elementor-menu' ) {
			return $items;
		}
		$enable_cart = '1' === lisfinity_get_option( 'header-cart' );

		if ( $enable_cart ) {
			ob_start();
			$count = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : '';
			?>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
			   id="cart--wrapper"
			   class="relative ml-20"
			   style="top: -1px;"
			   title="<?php echo esc_html__( 'Cart', 'lisfinity-core' ); ?>"
			>
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
					 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
					 y="0px"
					 viewBox="0 0 100 100"
					 style="enable-background:new 0 0 100 100;"
					 xml:space="preserve"
					 width="24px"
					 height="24px"
					 fill="#199473">
					<g>
						<path d="M90.6,2.5h-8c-1.9,0-3.5,1.4-3.7,3.4L77,24.9H11.4c-1.5,0-2.8,0.7-3.8,1.8c-0.9,1.2-1.2,2.7-0.8,4.1l10.7,41.9
							c0.5,2.1,2.4,3.6,4.6,3.6H73c0.3,0,0.7,0,1-0.1c0.2,0,0.3,0.1,0.5,0.1c0.1,0,0.2,0,0.3,0c1.4,0,2.6-1.1,2.7-2.5l0.2-1.7
							c0,0,0,0,0,0l4.9-47.1h-0.1L84.2,8h6.4c1.5,0,2.8-1.2,2.8-2.8S92.2,2.5,90.6,2.5z M12.3,30.4h64.1l-4.1,40.4H22.7L12.3,30.4z"/>
						<circle cx="31.2" cy="89.4" r="8"/>
						<circle cx="64.2" cy="89.4" r="8"/>
					</g>
				</svg>
				<span
					class="cart-count absolute flex-center w-16 h-16 bg-grey-200 rounded-full text-xs text-grey-1100 pointer-events-none <?php echo $count === 0 ? esc_attr( 'hidden' ) : ''; ?>"
					style="top: -8px; right: -10px"><?php echo esc_attr( $count ); ?></span>
			</a>
			<?php
			$items .= ob_get_clean();
		}

		return $items;
	}

}
