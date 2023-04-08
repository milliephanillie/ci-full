<?php

namespace Lisfinity\Models\Demo;


use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\REST_API\Options\OptionsRoute;
use Lisfinity\REST_API\SearchBuilder\SearchBuilderRoute;
use Lisfinity\REST_API\Taxonomies\GroupRoute;

class DemoImport {

	protected static $_instance = null;

	/**
	 * @return null|DemoImport
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function init() {
		// filters
		add_filter( 'pt-ocdi/import_files', [ $this, 'import_files' ] );
		add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
		//add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

		// actions
		add_action( 'pt-ocdi/before_content_import', [ $this, 'before_import_setup' ] );
		add_action( 'pt-ocdi/after_import', [ $this, 'after_import_setup' ] );
		add_action( 'demo_import_done', [ $this, 'mark_demo_as_imported' ] );
	}

	/**
	 * Import demo files
	 *
	 * @return array
	 */
	public function import_files() {
		return [
			[
				'type'                     => 'classic',
				'import_file_name'         => 'Classic Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/classic/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/classic/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/classic/classic.jpg',
				'import_notice'            => esc_html__( 'Classic demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://classic.lisfinity.com' ),
			],
			[
				'type'                     => 'boats',
				'import_file_name'         => 'Boats Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/boats/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/boats/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/boats/boats.jpg',
				'import_notice'            => esc_html__( 'Boats demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://boats.lisfinity.com' ),
			],
			[
				'type'                     => 'cars',
				'import_file_name'         => 'Cars Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/cars/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/cars/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/cars/cars.jpg',
				'import_notice'            => esc_html__( 'Cars demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://cars.lisfinity.com' ),
			],
			[
				'type'                     => 'realties',
				'import_file_name'         => 'Realties Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/realties/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/realties/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/realties/realties.jpg',
				'import_notice'            => esc_html__( 'Realties demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://realties.lisfinity.com' ),
			],
			[
				'type'                     => 'pets',
				'import_file_name'         => 'Pets Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/pets/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/pets/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/pets/pets.jpg',
				'import_notice'            => esc_html__( 'Pets demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://pets.lisfinity.com' ),
			],
			[
				'type'                     => 'small',
				'import_file_name'         => 'Small Demo Import',
				'categories'               => [],
				'local_import_file'        => LISFINITY_CORE_DIR . 'includes/demo/small/content.xml',
				'local_import_widget_file' => LISFINITY_CORE_DIR . 'includes/demo/small/widgets.json',
				'import_preview_image_url' => LISFINITY_CORE_URL . 'includes/demo/small/classic.jpg',
				'import_notice'            => esc_html__( 'Small demo import', 'lisfinity-core' ),
				'preview_url'              => esc_url( 'https://classic.lisfinity.com' ),
			],
		];
	}

	protected function disable_all_widgets( $sidebars_widgets ) {
		$sidebars_widgets = [ false ];

		return $sidebars_widgets;
	}

	/**
	 * Settings that are applied before the import has started
	 * -------------------------------------------------------
	 *
	 * @param $import
	 */
	public function before_import_setup( $import ) {
		if ( 'classic' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/classic/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/classic/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/classic/options.json';;
			$options = ob_get_clean();
		}
		if ( 'boats' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/boats/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();

			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/boats/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/boats/options.json';;
			$options = ob_get_clean();
		}
		if ( 'cars' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/cars/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();

			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/cars/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/cars/options.json';;
			$options = ob_get_clean();
		}
		if ( 'realties' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/realties/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();

			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/realties/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/realties/options.json';;
			$options = ob_get_clean();
		}
		if ( 'pets' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/pets/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();

			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/pets/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/pets/options.json';;
			$options = ob_get_clean();
		}
		if ( 'small' === $import['type'] ) {
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/small/groups.json'; // to add a file with the fields.
			$group_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/small/builder.json'; // to add a file with the fields.
			$search_builder_fields = ob_get_clean();
			ob_start();
			require LISFINITY_CORE_DIR . 'includes/demo/small/options.json';;
			$options = ob_get_clean();
		}

		// import group fields and taxonomies.
		if ( ! empty( $group_fields ) ) {
			$groups_import = new GroupRoute();
			$imported      = $groups_import->import_fields_demo( $group_fields );
			$tax_model     = new TaxonomiesAdminModel();
			$tax_model->register_new_taxonomies();
			$terms_imported = $groups_import->import_terms( json_decode( $group_fields, true )['terms'] );
		}
		if ( ! empty( $search_builder_fields ) ) {
			$search_builder_import = new SearchBuilderRoute();
			$search_builder_import->import_fields_demo( $search_builder_fields );
		}

		if ( ! empty( $options ) ) {
			$options_route = new OptionsRoute();
			$options_route->import_options_demo( $options );
		}
		flush_rewrite_rules( true );

		// import users
		//$this->import_users();
	}

	public function import_users() {
		$main_fields = [
			'ID',
			'user_login',
			'user_pass',
			'user_nicename',
			'user_email',
			'user_url',
			'user_registered',
			'user_activation_key',
			'user_status',
			'display_name'
		];
		$csv         = array_map( 'str_getcsv', file( LISFINITY_CORE_DIR . 'includes/demo/classic/users.csv' ) );
		array_walk( $csv, function ( &$a ) use ( $csv ) {
			$a = array_combine( $csv[0], $a );
		} );
		array_shift( $csv );

		foreach ( $csv as $user ) {
			$id = wp_insert_user( [
				'user_login'      => $user['user_login'],
				'user_pass'       => $user['user_pass'],
				'user_nicename'   => $user['user_nicename'],
				'user_email'      => $user['user_email'],
				'user_url'        => $user['user_url'],
				'user_registered' => $user['user_registered'],
				'display_name'    => $user['display_name'],
			] );

			foreach ( $user as $key => $value ) {
				if ( ! in_array( $key, $main_fields ) ) {
					update_user_meta( $id, $key, $value );
				}
			}
		}
	}

	/**
	 * Do after demo import is done
	 * ----------------------------
	 */
	public function after_import_setup() {
		$this->delete_wp_sample(); // delete default wp page & post
		$this->assign_menus(); // assign demo menus to proper menu locations
		$this->assign_demo_pages(); // assign demo pages
		$this->set_permalinks_after_import(); // set permalinks to postname after demo has been imported
		$this->update_listing_expiring_dates(); // update listing expiring dates for 30 days more
		$this->remove_unnecessary_widgets(); // remove all unnecessary widgets from the sidebars
	}

	public function remove_unnecessary_widgets() {
		$sidebars_widgets = get_option( 'sidebars_widgets' );

		if ( $sidebars_widgets['footer-sidebar-1'] ) {
			foreach ( $sidebars_widgets['footer-sidebar-1'] as $key => $widget ) {
				if ( $widget !== 'text-2' ) {
					unset( $sidebars_widgets['footer-sidebar-1'][ $key ] );
				}
			}
		}

		update_option( 'sidebars_widgets', $sidebars_widgets );
	}

	/**
	 * Assign demo theme menus
	 */
	public function assign_menus() {
		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', [
			'main-menu'   => $main_menu->term_id,
			'mobile-menu' => $main_menu->term_id,
		] );
	}

	/**
	 * Assign demo theme pages
	 */
	public function assign_demo_pages() {
		$front_page_id   = get_page_by_title( 'Home 2' );
		$register        = get_page_by_title( 'Register' );
		$login           = get_page_by_title( 'Login' );
		$reset           = get_page_by_title( 'Password Reset' );
		$search          = get_page_by_title( 'Search' );
		$account         = get_page_by_title( 'My Account' );
		$vendors         = get_page_by_title( 'Authors' );
		$terms           = get_page_by_title( 'Terms & Conditions' );
		$privacy_policy  = get_page_by_title( 'Privacy Policy' );
		$tips            = get_page_by_title( 'Safety Tips' );
		$contact_page_id = get_page_by_title( 'Contact' );
		$blog_page       = get_page_by_title( 'News' );
		$shop_page       = get_page_by_title( 'Shop' );
		$checkout        = get_page_by_title( 'Checkout' );
		$cart            = get_page_by_title( 'Cart' );

		// set default pages
		\Redux::set_option( 'lisfinity-options', '_page-home', $front_page_id->ID );
		\Redux::set_option( 'lisfinity-options', '_page-register', $register->ID );
		\Redux::set_option( 'lisfinity-options', '_page-login', $login->ID );
		\Redux::set_option( 'lisfinity-options', '_page-reset', $reset->ID );
		\Redux::set_option( 'lisfinity-options', '_page-search', $search->ID );
		\Redux::set_option( 'lisfinity-options', '_page-account', $account->ID );
		\Redux::set_option( 'lisfinity-options', '_page-vendors', $vendors->ID );
		\Redux::set_option( 'lisfinity-options', '_page-terms', $terms->ID );
		\Redux::set_option( 'lisfinity-options', '_page-privacy-policy', $privacy_policy->ID );
		\Redux::set_option( 'lisfinity-options', '_page-tips', $tips->ID );
		\Redux::set_option( 'lisfinity-options', '_page-contact', $contact_page_id->ID );
		\Redux::set_option( 'lisfinity-options', '_page-shop', $shop_page->ID );
		\Redux::set_option( 'lisfinity-options', '_page-checkout', $checkout->ID );
		\Redux::set_option( 'lisfinity-options', '_page-cart', $cart->ID );

		// woocommerce pages
		update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		update_option( 'woocommerce_cart_page_id', $cart->ID );
		update_option( 'woocommerce_checkout_page_id', $checkout->ID );
		update_option( 'woocommerce_myaccount_page_id', $account->ID );
		update_option( 'woocommerce_terms_page_id', $terms->ID );

		// set homepage
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page->ID );

		update_option( 'users_can_register', true );
	}

	/**
	 * Delete WordPress default page and post
	 */
	public function delete_wp_sample() {
		$defaultPage = get_page_by_title( 'Sample Page' );
		wp_delete_post( $defaultPage->ID, $bypass_trash = true );
		$privacy_policy = get_page_by_title( 'Privacy Policy' );
		wp_delete_post( $privacy_policy->ID, $bypass_trash = true );

		// Find and delete the WP default 'Hello world!' post
		$defaultPost = get_posts( array( 'title' => 'Hello World!' ) );
		wp_delete_post( $defaultPost[0]->ID, $bypass_trash = true );
	}

	/**
	 * Mark demo as imported
	 */
	public function mark_demo_as_imported() {
		update_option( 'lisfinity_demo_imported', 'yes' ); // mark demo as imported
	}

	/**
	 * Set theme permalinks to post name
	 */
	public function set_permalinks_after_import() {
		global $wp_rewrite;
		//Write the rule
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		//Set the option
		update_option( "rewrite_rules", false );
		//Flush the rules and tell it to write htaccess
		$wp_rewrite->flush_rules( true );
	}

	/**
	 * Update listings expiring dates for 30 days more
	 * -----------------------------------------------
	 */
	public function update_listing_expiring_dates() {
		global $wpdb;
		// remove all business profiles except for one.
		$businesses       = get_posts(
			[
				'post_type'      => 'premium_profile',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'fields'         => 'ids',
			]
		);
		$business_to_save = get_page_by_title( 'Demo Business', OBJECT, 'premium_profile' );;
		if ( ! empty( $businesses ) ) {
			foreach ( $businesses as $business ) {
				if ( $business_to_save->ID !== $business ) {
					wp_delete_post( $business, true );
				}
			}
		}
		$wpdb->update( $wpdb->posts, [ 'post_author' => 1 ], [ 'ID' => $business_to_save->ID ] );

		// set up the ads.
		$ad_ids = get_posts(
			[
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'tax_query'      => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'name',
						'terms'    => 'listing',
						'operator' => 'IN',
					],
				],
				'fields'         => 'ids',
			]
		);

		if ( $ad_ids ) {
			foreach ( $ad_ids as $ad_id ) {
				carbon_set_post_meta( $ad_id, 'product-expiration', strtotime( '+30 days', (int) current_time( 'timestamp' ) ) );
				carbon_set_post_meta( $ad_id, 'product-listed', (int) current_time( 'timestamp' ) );
				carbon_set_post_meta( $ad_id, 'product-owner', 1 );
				carbon_set_post_meta( $ad_id, 'product-business', $business_to_save->ID );
			}
		}
	}

}


function lisfinity_demo() {
	return DemoImport::instance();
}

// instantiate class.
//lisfinity_demo();
