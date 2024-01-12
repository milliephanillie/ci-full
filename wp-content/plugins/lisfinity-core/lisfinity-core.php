<?php
/**
 * Plugin Name: Lisfinity Core
 * Plugin URI: https://www.themeforest.net/user/pebas/lisfinity-core
 * Description: Lisfinity Core plugin used for pebas® Lisfinity WordPress theme
 * Version: 1.3.6
 * Author: pebas
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 5.0
 * Tested up to: 5.5.1
 * Text Domain: lisfinity-core
 * Domain Path: /languages/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use App\Carbon\Datasore\EagerLoadingPostMetaDatastore;
use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Datastore\Post_Meta_Datastore;
use Lisfinity\Models\Demo\DemoImport;
use Lisfinity\REST_API\Business\BusinessRoute;
use Lisfinity\REST_API\Menus\MenusRoute;
use Lisfinity\REST_API\SingleBuilder\SingleBuilderRoute;
use Lisfinity\REST_API\Stats\StatsRoute;
use Lisfinity\REST_API\Taxonomies\GroupRoute as GroupRoute;
use Lisfinity\REST_API\Taxonomies\TaxonomyRoute as TaxonomyRoute;
use Lisfinity\REST_API\Taxonomies\TermRoute as TermRoute;
use Lisfinity\REST_API\Forms\FormSubmitRoute as FormSubmitRoute;
use Lisfinity\REST_API\Products\ProductsRoute as ProductsRoute;
use Lisfinity\REST_API\Options\OptionsRoute as OptionsRoute;
use Lisfinity\REST_API\Messages\MessagesRoute as MessagesRoute;
use Lisfinity\REST_API\Notifications\NotificationsRoute as NotificationsRoute;
use Lisfinity\REST_API\Bids\BidsRoute as BidsRoute;
use Lisfinity\REST_API\SearchBuilder\SearchBuilderRoute as SearchBuilderRoute;
use Lisfinity\REST_API\Search\SearchRoute as SearchRoute;
use Lisfinity\REST_API\Testimonial\TestimonialRoute;
use Lisfinity\REST_API\Tips\TipsRoute;
use Lisfinity\REST_API\Users\UserRoute as UserRoute;
use Lisfinity\REST_API\Reports\ReportsRoute as ReportRoute;
use Lisfinity\REST_API\Authentication\AuthenticationRoute as AuthRoute;
use Lisfinity\REST_API\Dashboard\DashboardRoute as DashboardRoute;
use Lisfinity\REST_API\WooCommerce\WooCommerceRoute;

/**
 * Lisfinity_Core class.
 */
class Lisfinity_Core {

	protected static $_instance = null;

	/**
	 * Class instance method
	 * ---------------------
	 *
	 * @return null|Lisfinity_Core
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 * ------------------------------------------------
	 */
	public function __construct() {
		if ( ! function_exists( 'add_action' ) ) {
			header( 'Status: 403 Forbidden' );
			header( 'HTTP/1.1 403 Forbidden' );
			exit();
		}

		//   Do not load this plugin on WP heartbeat since we don't do anything with it.
		//
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] === 'heartbeat' ) ) {
			return;
		}

		// Define constants
		define( 'LISFINITY_CORE_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'LISFINITY_CORE_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'LISFINITY_CORE_VERSION', $this::plugin_data( 'Version' ) );
		define( 'LISFINITY_CORE_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'LISFINITY_CORE_URL',
			untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		$this->install();

		// instantiating WooCommerce dashboard.
		$this->autoload();

		// Plugins loaded
		add_action( 'admin_notices', [ $this, 'no_parent_plugin_notice' ], 10 );
		add_action( 'init', [ $this, 'set_script_translations' ] );
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ], 12 );
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ], 12 );
	}

	/**
	 * Display notice if the required plugins
	 * are not activated
	 * --------------------------------------
	 */
	public function no_parent_plugin_notice() {
		//todo change this before submitting theme
		if ( ! class_exists( 'WooCommerce' ) ) {
			?>
			<div class="error notice">
				<p><?php _e( '<strong>Lisfinity Core</strong> requires <strong>WooCommerce</strong> plugin to be installed in order to be used.',
						'lisfinity-core' ); ?></p>
			</div>
			<?php
		}
	}

	public function install() {
		if ( ! class_exists( 'Lisfinity_Core' ) || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Activation - works with symlinks.
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'activate'
		) );
		register_deactivation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'deactivate'
		) );
	}

	/**
	 * Initialize the plugin
	 * ---------------------
	 */
	public function init_plugin() {
		if ( ! class_exists( 'Lisfinity_Core' ) || ! class_exists( 'WooCommerce' ) || ! class_exists( 'Redux' ) ) {
			return;
		}
		// Switch theme.
		add_action( 'after_switch_theme', 'flush_rewrite_rules', 15 );

		// Actions.
		add_action( 'admin_init', [ $this, 'updater' ] );
		add_action( 'after_setup_theme', [ $this, 'load_dependencies' ], 20 );
		add_action( 'after_setup_theme', [ $this, 'include_functions' ], 30 );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'carbon_fields_fields_registered', [ $this, 'carbon_functions' ], 10 );
	}

	/**
	 * It is used to create additional WooCommerce dashboard pages
	 * as default composer autoloader is running too late for
	 * Carbon Fields to be invoked on after_theme_setup hook.
	 * -----------------------------------------------------------
	 */
	private function autoload() {
		// register cron jobs.
		require_once LISFINITY_CORE_DIR . 'includes/src/Schedules/SchedulesModel.php';
		require LISFINITY_CORE_DIR . 'includes/Slugs.php';
		require LISFINITY_CORE_DIR . 'vendor/autoload.php';
		require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-dashboard/DashboardPages.php';
		$slugs = new Slugs();
		lisfinity_dashboard();

		// initialize demo import class.
		$demo_import = new DemoImport();
		$demo_import->init();

		require LISFINITY_CORE_DIR . 'includes/datastore.php';

		add_action( 'carbon_fields_fields_registered', function () {
			$repo = Carbon_Fields::resolve( 'container_repository' );
			foreach ( $repo->get_containers() as $container ) {
				$datastore = $container->get_datastore();
				if ( $datastore instanceof Post_Meta_Datastore ) {
					$container->set_datastore( new EagerLoadingPostMetaDatastore() );
				}
			}
		} );
	}

	/**
	 * Called on plugin activation
	 * ---------------------------
	 */
	public function activate() {
		flush_rewrite_rules();
	}

	public function deactivate() {
		wp_clear_scheduled_hook( 'lisfinity__demo_cron_daily' );
		wp_clear_scheduled_hook( 'lisfinity__demo_cron_hourly' );
		wp_clear_scheduled_hook( 'lisfinity__demo_cron_half_hourly' );
	}

	/**
	 * Handle Plugin Updates
	 * ---------------------
	 */
	public function updater() {
		$version = get_option( 'lisfinity__version' );
		if ( version_compare( (string) LISFINITY_CORE_VERSION, $version, '>' ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Load plugin dependencies
	 * ------------------------
	 */
	function load_dependencies() {
		\Carbon_Fields\Carbon_Fields::boot();
	}

	/**
	 * Load necessary functions
	 * ------------------------
	 */
	public function include_functions() {
		// default functions.
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-carbon-fields.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-product.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-promotion.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-templates.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-user.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-import.php';
		include LISFINITY_CORE_DIR . '/includes/functions/lisfinity-functions-taxonomies.php';

		// helper functions.
		require_once LISFINITY_CORE_DIR . '/includes/helpers/helper-functions.php';
		require_once LISFINITY_CORE_DIR . '/includes/helpers/helper-theme-options.php';

		// Product.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/demo/Lisfinity.php';
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/meta-product-listing-ad.php';
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/meta-product-listing-discount.php';
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/meta-product-listing-event.php';
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/meta-product-listing-rental.php';

		// Premium Profile.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/premium-profile/meta-premium-profile.php';

		// Taxonomies.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/taxonomies/meta-taxonomy-product.php';

		// Payment Package.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/payment-packages/meta-product-payment-package.php';

		// Payment Subscription.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/payment-subscriptions/meta-product-payment-subscription.php';

		// Product Media.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/promotions/meta-product-promotion.php';

		// User Meta.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/user/meta-user.php';

		// Report Meta.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/reports/meta-reports.php';

		// Tips Meta.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/tips/meta-tips.php';

		// Payouts Meta.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/payouts/meta-payouts.php';

		// include theme options.
		require LISFINITY_CORE_DIR . 'includes/theme-options/theme-options-config.php';

		// theme options.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/theme-options/builders-settings.php';

		include LISFINITY_CORE_DIR . '/includes/hooks.php';
	}

	/**
	 * Functions that can be invoked on
	 * carbon_fields_fields_registered hook
	 * ------------------------------------
	 */
	public function carbon_functions() {
		// has to be loaded here as after_setup_theme hook is too late for it to run.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/products/meta-product-listing-common.php';

		// page options.
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/page-options/meta-page-home.php';
		require_once LISFINITY_CORE_DIR . '/includes/carbon-fields/page-options/meta-page-archive.php';
	}

	/**
	 * Widgets initialization
	 * ----------------------
	 */
	public function widgets_init() {
	}

	/**
	 * Load plugin textdomain
	 * ----------------------
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'lisfinity-core', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Get REST_API custom routes to include them for
	 * localization
	 * ----------------------------------------------
	 *
	 * @param $route
	 *
	 * @return array
	 */
	private function get_routes( $route ) {
		$router   = $route;
		$rest_url = get_rest_url() . $router->get_vendor() . '/v' . $router->get_version();

		return [ 'rest_url' => $rest_url, 'routes' => $router->get_routes() ];
	}

	protected function localized_vars() {
		global $wp;

		$taxonomy_router       = $this->get_routes( new TaxonomyRoute() );
		$term_router           = $this->get_routes( new TermRoute() );
		$group_router          = $this->get_routes( new GroupRoute() );
		$form_submit_router    = $this->get_routes( new FormSubmitRoute() );
		$products_router       = $this->get_routes( new ProductsRoute() );
		$options_router        = $this->get_routes( new OptionsRoute() );
		$messages_router       = $this->get_routes( new MessagesRoute() );
		$bids_router           = $this->get_routes( new BidsRoute() );
		$notifications_router  = $this->get_routes( new NotificationsRoute() );
		$search_builder_router = $this->get_routes( new SearchBuilderRoute() );
		$single_builder_router = $this->get_routes( new SingleBuilderRoute() );
		$search_router         = $this->get_routes( new SearchRoute() );
		$user_router           = $this->get_routes( new UserRoute() );
		$report_router         = $this->get_routes( new ReportRoute() );
		$testimonial_router    = $this->get_routes( new TestimonialRoute() );
		$auth_router           = $this->get_routes( new AuthRoute() );
		$dashboard_router      = $this->get_routes( new DashboardRoute() );
		$stats_router          = $this->get_routes( new StatsRoute() );
		$business_router       = $this->get_routes( new BusinessRoute() );
		$wc_router             = $this->get_routes( new WooCommerceRoute() );
		$tips_router           = $this->get_routes( new TipsRoute() );
		$menus_router          = $this->get_routes( new MenusRoute() );
		$payouts_router        = $this->get_routes( new \Lisfinity\REST_API\Payouts\PayoutsRoute() );
		$agent_router          = $this->get_routes( new \Lisfinity\REST_API\Agents\AgentsRoute() );
		$licence_router        = $this->get_routes( new Lisfinity\REST_API\Other\LicenceRoute() );

		$theme = wp_get_theme();
		$slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $theme->template ) );

		// admin models.
		$groups_admin = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();

		$home_url  = home_url( '/' );
		$myaccount = str_replace( $home_url, '', get_permalink( lisfinity_get_option( 'page-account' ) ) );

		$map_style           = lisfinity_get_option( 'map-style' );
		$mapbox_style        = lisfinity_get_option( 'map-mapbox-id' );
		$mapbox_api          = lisfinity_get_option( 'map-mapbox-api' );
		$mapbox_username     = lisfinity_get_option( 'map-mapbox-username' );
		$mapbox_url          = 'mapbox' === $map_style && ! empty( $mapbox_style ) && $mapbox_api ? "https://api.mapbox.com/styles/v1/{$mapbox_username}/{$mapbox_style}/tiles/256/{z}/{x}/{y}?access_token={$mapbox_api}" : '';
		$country_restriction = lisfinity_get_option( 'map-country-restriction' );
		$site_relative_path  = get_home_url( null, '', 'relative' );
		$site_url            = '/';
		if ( ! empty( $site_relative_path ) && false === strpos( $site_relative_path, '?' ) ) {
			$site_url = $site_relative_path . '/';
		}

		$site_direction = lisfinity_get_option( 'site-direction' );

		$user_id = get_current_user_id();

		// todo move endpoints to a custom method.
		$vars = [
			'dir'                           => esc_url( LISFINITY_CORE_URL ),
			'is_demo'                       => lisfinty_is_demo(),
			'demo_product'                  => get_permalink( lisfinity_get_option( 'demo-product-example' ) ),
			'is_ssl'                        => is_ssl(),
			'rtl'                           => 'rtl' === $site_direction,
			'currency_symbol'               => get_woocommerce_currency_symbol( get_woocommerce_currency() ),
			'is_front'                      => is_front_page(),
			'is_search'                     => lisfinity_is_page_template( 'page-search' ),
			'is_business'                   => is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name ),
			'domain'                        => lisfinity_get_domain(),
			'url'                           => esc_url( home_url( '/' ) ),
			'site_url'                      => $site_url,
			'admin_url'                     => esc_url( admin_url( '' ) ),
			'ajaxurl'                       => esc_url( admin_url( 'admin-ajax.php' ) ),
			'wp_resturl'                    => rest_url(),
			'resturl'                       => $taxonomy_router['rest_url'],
			'request'                       => ! empty( $wp->request ) ? explode( '/', $wp->request ) : '',
			'submit_ad_link'                => lisfinity_get_correct_submit_button_permalink(),
			'category_search_url'           => $site_url . lisfinity_get_slug( 'slug-category', 'ad-category' ),
			'locale'                        => lisfinity_get_locale(),
			'timezone'                      => lisfinity_timezone(),
			'site_title'                    => get_option( 'blogname' ),
			'endpoint-business'             => lisfinity_get_option( 'slug-business' ),
			'is_admin'                      => is_admin() ? 1 : 0,
			'user_admin'                    => current_user_can( 'administrator' ),
			'logged_in'                     => is_user_logged_in(),
			'page_id'                       => get_queried_object_id(),
			'is_user_verification_enabled'  => lisfinity_is_enabled( lisfinity_get_option( 'enable-user-verification' ) ),
			'current_user_id'               => $user_id,
			'is_agent'                      => ! empty( lisfinity_get_agent( $user_id ) ),
			'category_select'               => lisfinity_format_categories_select(),
			'is_elementor_search'           => defined( 'ELEMENTOR_VERSION' ) ? \Elementor\Plugin::$instance->preview->is_preview_mode( lisfinity_get_page_id( 'page-search' ) ) : false,
			'is_business_page'              => is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name ),
			'bookmarks'                     => lisfinity_rearrange_bookmarks(),
			'business_id'                   => lisfinity_get_premium_profile_id( get_current_user_id() ),
			'check_for_premium'             => lisfinity_check_for_premium(),
			'taxonomies'                    => json_encode( lisfinity_get_organized_groups_with_taxonomies() ),
			'user_has_business'             => lisfinity_user_has_business(),
			'slug_category'                 => lisfinity_get_slug( 'slug-category', 'ad-category' ),
			'current_listing_id'            => lisfinity_get_single_page_product_id(),
			'current_business_id'           => lisfinity_get_single_page_business_id(),
			'notifications_interval'        => lisfinity_is_enabled( lisfinity_get_option( 'site-refresh-notifications' ) ) && lisfinity_get_option( 'site-refresh-notifications-interval' ) > 0 ? lisfinity_get_option( 'site-refresh-notifications-interval' ) : 0,
			'current_product_id'            => is_singular( [
				'product',
				'premium_profile'
			] ) ? get_queried_object_id() : get_query_var( 'products' ),
			'product_owner'                 => is_singular( [
				'product',
				'premium_profile'
			] ) ? get_post_meta( get_queried_object_id(), '_product-owner', true ) : 1,
			'product_business'              => is_singular( [
				'product',
				'premium_profile'
			] ) ? get_post_meta( get_queried_object_id(), '_product-business', true ) : false,
			'is_owner'                      => get_post_meta( get_query_var( 'ad' ), '_product-owner' ) === get_current_user_id(),
			'user_ip'                       => lisfinity_get_ip_address(),
			'nonce'                         => wp_create_nonce( 'wp_rest' ),
			'current_time'                  => current_time( 'timestamp' ),
			'product_permalink'             => get_option( 'woocommerce_permalinks' ),
			// header options.
			'sticky_header'                 => lisfinity_get_option( 'header-sticky' ),
			// compare.
			'compare'                       => get_transient( "{$user_id}_products_compare" ),
			'country_restriction'           => ! empty( $country_restriction ) ? $country_restriction : false,
			// mapbox.
			'mapbox_url'                    => $mapbox_url,
			// pages && endpoints.
			'myaccount'                     => $myaccount,
			'page_account'                  => esc_url( get_permalink( lisfinity_get_page_id( 'page-account' ) ) ),
			'page_search'                   => esc_url( get_permalink( lisfinity_get_page_id( 'page-search' ) ) ),
			'page_endpoint'                 => ! empty( get_queried_object() ) && isset( get_queried_object()->post_name ) ? get_queried_object()->post_name : false,
			'page_search_detailed'          => esc_url( get_permalink( lisfinity_get_page_id( 'page-search-detailed' ) ) ),
			'page_vendors'                  => esc_url( get_permalink( lisfinity_get_page_id( 'page-vendors' ) ) ),
			'page_register'                 => esc_url( get_permalink( lisfinity_get_page_id( 'page-register' ) ) ),
			'page_login'                    => esc_url( get_permalink( lisfinity_get_page_id( 'page-login' ) ) ),
			'page_reset'                    => esc_url( get_permalink( lisfinity_get_page_id( 'page-reset' ) ) ),
			'page_register_endpoint'        => get_post( lisfinity_get_page_id( 'page-register' ) )->post_name ?? false,
			'page_login_endpoint'           => get_post( lisfinity_get_page_id( 'page-login' ) )->post_name ?? false,
			'page_reset_endpoint'           => get_post( lisfinity_get_page_id( 'page-reset' ) )->post_name ?? false,
			'page_search_endpoint'          => get_post( lisfinity_get_page_id( 'page-search' ) )->post_name ?? false,
			'page_search_detailed_endpoint' => get_post( lisfinity_get_page_id( 'page-search-detailed' ) )->post_name ?? false,
			// search options.
			'search_product_style'          => lisfinity_get_option( 'search-product-style' ),
			'location_format'               => lisfinity_get_option( 'format-location' ),
			// custom product types.
			'product_listing'               => WC_Product_Listing::$type,
			'payment_package'               => WC_Product_Payment_Package::$type,
			'payment_subscription'          => WC_Product_Payment_Subscription::$type,
			'promotion'                     => WC_Product_Promotion::$type,
			'commission'                    => WC_Product_Commission::$type,
			// default routes
			'attachment_data'               => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['attachment_data']['path'],
			'get_groups'                    => $groups_admin->get_options(),
			// group routes.
			'group_fields'                  => $group_router['rest_url'] . $group_router['routes']['group_fields']['path'],
			'groups'                        => $group_router['rest_url'] . $group_router['routes']['groups']['path'],
			'groups_by_term'                => $group_router['rest_url'] . $group_router['routes']['groups_by_term']['path'],
			'group_store'                   => $group_router['rest_url'] . $group_router['routes']['group_store']['path'],
			'group_edit'                    => $group_router['rest_url'] . $group_router['routes']['group_edit']['path'],
			'group_delete'                  => $group_router['rest_url'] . $group_router['routes']['group_delete']['path'],
			'group_edit_order'              => $group_router['rest_url'] . $group_router['routes']['group_edit_order']['path'],
			// import/export.
			'export_fields'                 => $group_router['rest_url'] . $group_router['routes']['export_fields']['path'],
			'import_fields'                 => $group_router['rest_url'] . $group_router['routes']['import_fields']['path'],
			'import_terms'                  => $group_router['rest_url'] . $group_router['routes']['import_terms']['path'],
			'search_export_fields'          => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_export_fields']['path'],
			'search_import_fields'          => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_import_fields']['path'],
			// taxonomy routes.
			'taxonomy_fields'               => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_fields']['path'],
			'taxonomy_options'              => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_options']['path'],
			'taxonomy_location_options'     => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_location_options']['path'],
			'taxonomy_group_options'        => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_group_options']['path'],
			'taxonomy_options_store'        => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_options_store']['path'],
			'taxonomy_options_edit'         => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_options_edit']['path'],
			'taxonomy_options_delete'       => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_options_delete']['path'],
			'get_cf_versions'               => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['get_cf_versions']['path'],
			'reset_cf_version'              => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['reset_cf_version']['path'],
			'save_cf_version'               => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['save_cf_version']['path'],
			'taxonomy_import'               => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_import']['path'],
			'taxonomy_refresh'              => $taxonomy_router['rest_url'] . $taxonomy_router['routes']['taxonomy_refresh']['path'],
			// term routes.
			'term'                          => $term_router['rest_url'] . $term_router['routes']['term']['rest_path'],
			'term_fields'                   => $term_router['rest_url'] . $term_router['routes']['term_fields']['path'],
			'terms_get'                     => $term_router['rest_url'] . $term_router['routes']['terms']['rest_path'],
			'terms_by_group'                => $term_router['rest_url'] . $term_router['routes']['terms_by_group']['rest_path'],
			'terms_edit'                    => $term_router['rest_url'] . $term_router['routes']['terms_edit']['rest_path'],
			'terms_by_taxonomy'             => $term_router['rest_url'] . $term_router['routes']['terms_by_taxonomy']['path'],
			'terms_for_search'              => $term_router['rest_url'] . $term_router['routes']['terms_for_search']['path'],
			'term_store'                    => $term_router['rest_url'] . $term_router['routes']['term_store']['path'],
			'term_edit'                     => $term_router['rest_url'] . $term_router['routes']['term_edit']['rest_path'],
			'term_remove'                   => $term_router['rest_url'] . $term_router['routes']['term_remove']['path'],
			// forms routes.
			'business_fields'               => $form_submit_router['rest_url'] . $form_submit_router['routes']['business_fields']['path'],
			'package_fields'                => $form_submit_router['rest_url'] . $form_submit_router['routes']['package_fields']['path'],
			'product_fields'                => $form_submit_router['rest_url'] . $form_submit_router['routes']['product_fields']['path'],
			'product_submit'                => $form_submit_router['rest_url'] . $form_submit_router['routes']['product_submit']['path'],
			// products routes.
			'product'                       => $products_router['rest_url'] . $products_router['routes']['product']['rest_path'],
			'get_product_method'            => $products_router['rest_url'] . $products_router['routes']['get_product_method']['path'],
			'product_action'                => $products_router['rest_url'] . $products_router['routes']['product_action']['rest_path'],
			'packages'                      => $products_router['rest_url'] . $products_router['routes']['packages']['rest_path'],
			'package_and_promotions'        => $products_router['rest_url'] . $products_router['routes']['package_and_promotions']['path'],
			'product_data'                  => $products_router['rest_url'] . $products_router['routes']['product_data']['path'],
			'check_package'                 => $products_router['rest_url'] . $products_router['routes']['check_package']['path'],
			'compare_products'              => $products_router['rest_url'] . $products_router['routes']['compare_products']['path'],
			'compare_remove'                => $products_router['rest_url'] . $products_router['routes']['compare_remove']['path'],
			'request_call'                  => $products_router['rest_url'] . $products_router['routes']['request_call']['path'],
			'send_message'                  => $products_router['rest_url'] . $products_router['routes']['send_message']['path'],
			'query_attachments'             => $products_router['rest_url'] . $products_router['routes']['query_attachments']['path'],
			// business routes.
			'business'                      => $business_router['rest_url'] . $business_router['routes']['business']['rest_path'],
			'business_type'                 => $business_router['rest_url'] . $business_router['routes']['business_type']['rest_path'],
			'business_archive'              => $business_router['rest_url'] . $business_router['routes']['business_archive']['path'],
			// options routes.
			'options'                       => $options_router['rest_url'] . $options_router['routes']['options']['path'],
			'option'                        => $options_router['rest_url'] . $options_router['routes']['option']['rest_path'],
			'options_export'                => $options_router['rest_url'] . $options_router['routes']['options_export']['path'],
			'options_import'                => $options_router['rest_url'] . $options_router['routes']['options_import']['path'],
			// messages routes.
			'chats'                         => $messages_router['rest_url'] . $messages_router['routes']['chats']['rest_path'],
			'messages'                      => $messages_router['rest_url'] . $messages_router['routes']['messages']['path'],
			'messages_chat'                 => $messages_router['rest_url'] . $messages_router['routes']['messages_chat']['rest_path'],
			'message_submit'                => $messages_router['rest_url'] . $messages_router['routes']['message_submit']['path'],
			'message_update'                => $messages_router['rest_url'] . $messages_router['routes']['message_update']['path'],
			// bids routes.
			'bids'                          => $bids_router['rest_url'] . $bids_router['routes']['bids']['rest_path'],
			'submit_bid'                    => $bids_router['rest_url'] . $bids_router['routes']['submit_bid']['path'],
			'update_bid'                    => $bids_router['rest_url'] . $bids_router['routes']['update_bid']['path'],
			'buy_bid'                       => $bids_router['rest_url'] . $bids_router['routes']['buy_bid']['path'],
			// notifications routes.
			'update_notifications'          => $notifications_router['rest_url'] . $notifications_router['routes']['update_notifications']['rest_path'],
			'update_all_notifications'      => $notifications_router['rest_url'] . $notifications_router['routes']['update_all_notifications']['rest_path'],
			// stats routes.
			'get_stats'                     => $stats_router['rest_url'] . $stats_router['routes']['get_stats']['path'],
			'update_stats'                  => $stats_router['rest_url'] . $stats_router['routes']['update_stats']['path'],
			// search routes.
			'search_keyword'                => $search_router['rest_url'] . $search_router['routes']['search_keyword']['rest_path'],
			'search'                        => $search_router['rest_url'] . $search_router['routes']['search']['path'],
			// search builder routes.
			'search_builder_options'        => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_options']['path'],
			'search_builder_fields'         => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_fields']['rest_path'],
			'search_builder_submit'         => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_submit']['path'],
			'search_builder_group_get'      => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_group_get']['path'],
			'search_builder_group_submit'   => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_group_submit']['path'],
			'search_builder_order_edit'     => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_order_edit']['path'],
			'search_builder_groups'         => $search_builder_router['rest_url'] . $search_builder_router['routes']['search_builder_groups']['path'],
			// single builder routes.
			'single_builder_options'        => $single_builder_router['rest_url'] . $single_builder_router['routes']['single_builder_options']['path'],
			'single_builder_add_group'      => $single_builder_router['rest_url'] . $single_builder_router['routes']['single_builder_add_group']['path'],
			'single_builder_delete_group'   => $single_builder_router['rest_url'] . $single_builder_router['routes']['single_builder_delete_group']['path'],
			'single_builder_change_group'   => $single_builder_router['rest_url'] . $single_builder_router['routes']['single_builder_change_group']['path'],
			'single_builder_order_group'    => $single_builder_router['rest_url'] . $single_builder_router['routes']['single_builder_order_group']['path'],
			// user route.
			'user'                          => $user_router['rest_url'] . $user_router['routes']['user']['path'],
			'user_action'                   => $user_router['rest_url'] . $user_router['routes']['user_action']['rest_path'],
			// agents route.
			'agent_action'                  => $agent_router['rest_url'] . $agent_router['routes']['agent_action']['rest_path'],
			// authentication route.
			'auth_options'                  => $auth_router['rest_url'] . $auth_router['routes']['auth_options']['path'],
			'register'                      => $auth_router['rest_url'] . $auth_router['routes']['register']['path'],
			'login'                         => $auth_router['rest_url'] . $auth_router['routes']['login']['path'],
			'sms_verify'                    => $auth_router['rest_url'] . $auth_router['routes']['sms_verify']['path'],
			'sms_resend'                    => $auth_router['rest_url'] . $auth_router['routes']['sms_resend']['path'],
			'forgot'                        => $auth_router['rest_url'] . $auth_router['routes']['forgot']['path'],
			'reset'                         => $auth_router['rest_url'] . $auth_router['routes']['reset']['path'],
			'login_demo'                    => $auth_router['rest_url'] . $auth_router['routes']['login_demo']['path'],
			// report route.
			'report_submit'                 => $report_router['rest_url'] . $report_router['routes']['report_submit']['path'],
			'report_options'                => $report_router['rest_url'] . $report_router['routes']['report_options']['path'],
			// testimonial route.
			'submit_review'                 => $testimonial_router['rest_url'] . $testimonial_router['routes']['submit_review']['path'],
			'get_reviews'                   => $testimonial_router['rest_url'] . $testimonial_router['routes']['get_reviews']['rest_path'],
			// dashboard routes.
			'get_business'                  => $dashboard_router['rest_url'] . $dashboard_router['routes']['get_business']['path'],
			'get_all_ads'                   => $dashboard_router['rest_url'] . $dashboard_router['routes']['get_all_ads']['path'],
			'get_product'                   => $dashboard_router['rest_url'] . $dashboard_router['routes']['get_product']['path'],
			'get_notifications'             => $dashboard_router['rest_url'] . $dashboard_router['routes']['get_notifications']['path'],
			'purchase_package'              => $dashboard_router['rest_url'] . $dashboard_router['routes']['purchase_package']['path'],
			'purchase_promotion'            => $dashboard_router['rest_url'] . $dashboard_router['routes']['purchase_promotion']['path'],
			'purchase_premium'              => $dashboard_router['rest_url'] . $dashboard_router['routes']['purchase_premium']['path'],
			'purchase_ad_renewal'           => $dashboard_router['rest_url'] . $dashboard_router['routes']['purchase_ad_renewal']['path'],
			'purchase_commission'           => $dashboard_router['rest_url'] . $dashboard_router['routes']['purchase_commission']['path'],
			'upgrade_profile'               => $dashboard_router['rest_url'] . $dashboard_router['routes']['upgrade_profile']['path'],
			// woocommerce routes.
			'get_wc_profile'                => $wc_router['rest_url'] . $wc_router['routes']['get_profile']['path'],
			'update_wc_profile'             => $wc_router['rest_url'] . $wc_router['routes']['update_profile']['path'],
			'get_country_states'            => $wc_router['rest_url'] . $wc_router['routes']['get_country_states']['path'],
			'get_wc_orders'                 => $wc_router['rest_url'] . $wc_router['routes']['get_orders']['path'],
			'get_wc_order'                  => $wc_router['rest_url'] . $wc_router['routes']['get_order']['path'],
			'get_wc_downloads'              => $wc_router['rest_url'] . $wc_router['routes']['get_downloads']['path'],
			'get_cart_count'                => $wc_router['rest_url'] . $wc_router['routes']['get_cart_count']['path'],
			'set_currency'                  => $wc_router['rest_url'] . $wc_router['routes']['set_currency']['path'],
			// tips routes.
			'get_tips'                      => $tips_router['rest_url'] . $tips_router['routes']['get_tips']['path'],
			// menus routes.
			'get_mobile_menu'               => $menus_router['rest_url'] . $menus_router['routes']['get_mobile_menu']['path'],
			// payouts routes.
			'payouts_settings'              => $payouts_router['rest_url'] . $payouts_router['routes']['payouts_settings']['path'],
			'payout_process'                => $payouts_router['rest_url'] . $payouts_router['routes']['payout_process']['path'],
			'payout_process_mass'           => $payouts_router['rest_url'] . $payouts_router['routes']['payout_process_mass']['path'],
			'stripe_create_account'         => $payouts_router['rest_url'] . $payouts_router['routes']['stripe_create_account']['path'],
			// e-settings
			'e_verify'                      => 'https://classic.lisfinity.com/wp-json/lisfinity/v1/other/verify',
			'e_store'                       => $licence_router['rest_url'] . $licence_router['routes']['e_store']['path'],
			'key'                           => LisfinityBase::GetRegisterInfo(),
			'l'                             => get_option( 'Lisfinity_lic_Key' ),
			// javascript translations.
			'jst'                           => lisfinity_get_js_translations(),
		];

		return apply_filters( 'lisfinity__localized_vars', $vars );

	}

	/**
	 * Localize lisfinity-core plugin vars
	 * ------------------------------
	 */
	public function localize_vars() {
		$localized_vars = $this->localized_vars();
		wp_localize_script( 'lisfinity-products', 'lc_data', $localized_vars );
		wp_localize_script( 'lisfinity-submit', 'lc_data', $localized_vars );
		wp_localize_script( 'lisfinity-core-theme', 'lc_data', $localized_vars );
		wp_localize_script( 'lisfinity-dashboard', 'lc_data', $localized_vars );
		if ( is_singular( [
				'product',
				'premium_profile'
			] ) || lisfinity_is_page_template( 'page-home' ) || lisfinity_is_page_template( 'page-search' ) ) {
			wp_localize_script( 'lisfinity-theme-components', 'lc_data', $localized_vars );
		}
	}

	/**
	 * Register and enqueue scripts and styles
	 * ---------------------------------------
	 */
	public function frontend_scripts() {
		global $wp;
		$language       = get_locale();
		$language       = explode( '_', $language );
		$language       = isset( $language[0] ) ? $language[0] : 'en';
		$api            = lisfinity_get_option( 'map-api' );
		$site_direction = lisfinity_get_option( 'site-direction' );
		$font           = lisfinity_get_option( 'site-font' );

		// load fonts.
		wp_enqueue_style( 'google-material-icons', 'https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp', '', '', 'all' );

		if ( isset( $font ) && $font !== 'custom' ) {
			wp_enqueue_style( 'pbs-theme-fonts',
				"https://fonts.googleapis.com/css?family={$font}:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i",
				'', LISFINITY_CORE_VERSION, 'all' );
		}

		// check if my account page and if we're on submission form.
		// todo make sure that we change param to dynamic once we create permalink editor.
		if ( is_account_page() || lisfinity_is_page_template( 'page-search' ) || is_singular( [
				'product',
				'premium_profile'
			] ) ) {

			if ( ! empty( $api ) ) {
				wp_enqueue_script( 'google-maps',
					"https://maps.googleapis.com/maps/api/js?key={$api}&libraries=places&language={$language}",
					'', '', true );
			}
			wp_enqueue_media();
		}

		if ( is_front_page() && ! empty( lisfinity_get_option( 'home-banner-video' ) ) ) {
			wp_enqueue_script( 'lisfinity-youtube', 'https://www.youtube.com/iframe_api', [ 'wp-i18n' ], PBS_THEME_VERSION );
		}

		if ( is_account_page() ) {
			wp_enqueue_script( 'lisfinity-dashboard', LISFINITY_CORE_URL . 'dist/scripts/dashboard.js', [ 'wp-i18n' ], PBS_THEME_VERSION, true );
		}

		if ( is_singular( 'product' ) && has_term( 'listing', 'product_type' ) ) {
			wp_enqueue_style( 'slick-slider-css', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css', '', '', 'all' );
			wp_enqueue_style( 'slick-slider-theme-css', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css', '', '', 'all' );
		}

		wp_enqueue_script( 'lisfinity-core-theme', LISFINITY_CORE_URL . 'dist/scripts/theme.js', [
			'wc-checkout',
			'jquery',
			'wp-i18n',
			'masonry',
		], LISFINITY_CORE_VERSION, true );
		$this->localize_vars();

		if ( 'rtl' === $site_direction || 'rtl_front' === $site_direction ) {
			wp_enqueue_style( 'style-lisfinity-core', LISFINITY_CORE_URL . 'dist/styles/theme-rtl.css', '', '', 'all' );
		} else {
			wp_enqueue_style( 'style-lisfinity-core', LISFINITY_CORE_URL . 'dist/styles/theme.css', '', '', 'all' );
		}
		//wp_enqueue_style( 'style-lisfinity-core-colors', LISFINITY_CORE_URL . 'templates/options/colors/colors.php', '' );
	}

	/**
	 * Register and enqueue scripts and styles
	 * ---------------------------------------
	 */
	public function admin_scripts() {
		$admin_groups        = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
		$screen              = get_current_screen();
		$localize_args       = $this->localized_vars();
		$fields_builder      = translate( 'Fields Builder', 'lisfinity-core' );
		$fields_builder_slug = sanitize_title( $fields_builder );

		$cf_page_ids         = [
			'toplevel_page_custom-fields',
			"{$fields_builder_slug}_page_custom-fields-common",
			'toplevel_page_lisfinity-theme-options',
			'lisfinity-options_page_crb_carbon_fields_container_theme_setup',
			'lisfinity-options_page_crb_carbon_fields_container_pages_settings',
			'lisfinity-settings_page_crb_carbon_fields_container_pages_settings',
			'lisfinity-settings_page_crb_carbon_fields_container_importexport',
			\Lisfinity\Models\Vendors\PayoutsModel::$type,
		];
		$cf_page_ids         = array_merge( $cf_page_ids, $admin_groups->get_groups_slugs( "{$fields_builder_slug}_page_custom-fields-" ) );
		$search_builder_page = 'toplevel_page_search-builder';
		$type_ids            = [
			'product',
			\Lisfinity\Models\Tips\TipsModel::$type,
			\Lisfinity\Models\Reports\ReportModel::$type,
			'toplevel_page_search-builder',
			'lisfinity-builders_page_builder-options',
		];
		$screen_ids          = array_merge( $cf_page_ids, [ $search_builder_page ] );
		$screen_ids          = array_merge( $screen_ids, $type_ids );

		// deregister scripts on the important pages that are causing conflicts with other third-party plugins.
		if ( in_array( $screen->id, $screen_ids ) || in_array( $screen->id, $cf_page_ids ) || $screen->id === $search_builder_page ) {
			wp_deregister_script( 'yoast-seo-admin-global-script' );
		}

		// styles.
		$elementor_id = ! empty( $_GET['post'] ) ? $_GET['post'] : false;
		if ( in_array( $screen->id, $screen_ids ) || lisfinity_get_option( 'page-single-listing' ) === $elementor_id || $screen->id === \Lisfinity\Models\Elements\ElementsGlobalModel::$type ) {
			wp_enqueue_style( 'lisfinity-theme-admin', LISFINITY_CORE_URL . 'dist/styles/admin.css', '', '1.0.0', 'all' );
		}
		wp_enqueue_style( 'lisfinity-theme-admin-additional', LISFINITY_CORE_URL . 'dist/statics/styles/admin.css', '', '1.0.0', 'all' );

		// scripts.
		if ( in_array( $screen->id, $cf_page_ids ) ) {
			wp_enqueue_script( 'lisfinity-taxonomies' );
		}
		if ( $screen->id === $search_builder_page ) {
			wp_enqueue_script( 'lisfinity-search-builder', LISFINITY_CORE_URL . 'dist/scripts/admin-search-builder.js', [ 'wp-i18n' ], '1.0.0', true );
		}

		// import only on WooCommerce product screen.
		$payout_type = \Lisfinity\Models\Vendors\PayoutsModel::$type;
		if ( "edit-{$payout_type}" === $screen->id ) {
			wp_enqueue_script( 'lisfinity-payouts', LISFINITY_CORE_URL . 'dist/scripts/payouts.js', [
				'jquery',
				'wp-i18n'
			], '1.0.0', true );
		}
		if ( 'product' === $screen->id ) {
			wp_enqueue_script( 'lisfinity-products', LISFINITY_CORE_URL . 'dist/scripts/admin-products.js', [
				'jquery',
				'wp-i18n'
			], '1.0.0', true );
		}
		wp_enqueue_script( 'lisfinity-admin', LISFINITY_CORE_URL . 'dist/scripts/admin.js', [
			'jquery',
			'wp-i18n'
		], '1.0.0', true );

		// localize vars so we can use theme in our scripts.
		wp_localize_script( 'lisfinity-payouts', 'lc_data', $localize_args );
		wp_localize_script( 'lisfinity-search-builder', 'lc_data', $localize_args );
		wp_localize_script( 'lisfinity-taxonomies', 'lc_data', $localize_args );
		wp_localize_script( 'lisfinity-products', 'lc_data', $localize_args );
		wp_localize_script( 'lisfinity-admin', 'lc_data', $localize_args );
	}

	public function set_script_translations() {
		wp_register_script( 'lisfinity-taxonomies', LISFINITY_CORE_URL . 'dist/scripts/taxonomies.js', [ 'wp-i18n' ], '1.0.0', true );
		wp_set_script_translations( 'lisfinity-core-theme', 'lisfinity-core', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'lisfinity-dashboard', 'lisfinity-core', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'lisfinity-admin', 'lisfinity-core', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'lisfinity-search-builder', 'lisfinity-core', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'lisfinity-products', 'lisfinity-core', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'lisfinity-taxonomies', 'lisfinity-core', LISFINITY_CORE_DIR . 'languages' );
	}

	/**
	 * Path to the plugin
	 * ------------------
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get specific plugin data
	 * ------------------------
	 *
	 * @param $name
	 *
	 * @return array
	 */
	public static function plugin_data( $name ) {
		$data = get_file_data( __FILE__, array( $name ), 'plugin' );

		return array_shift( $data );
	}

}

/**
 * Instantiate the class
 * ---------------------
 *
 * @return Lisfinity_Core
 */
function lisfinity_core() {
	return Lisfinity_Core::instance();
}

lisfinity_core();
