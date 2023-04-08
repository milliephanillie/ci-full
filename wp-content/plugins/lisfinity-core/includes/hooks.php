<?php
/**
 * Declare all your actions and filters here.
 *
 * @author pebas
 * @package ager-core
 * @version 1.0.0
 */


use Lisfinity\Models\Auth\PasswordReset\PasswordResetModel;
use Lisfinity\Models\Auth\Register\RegisterModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\ProductImporter\Address;
use Lisfinity\Models\ProductImporter\Details;
use Lisfinity\Models\ProductImporter\General;
use Lisfinity\Models\ProductImporter\Media;
use Lisfinity\Models\PromotionsModel as PromotionsModel;

$promotion_model = new PromotionsModel();

/**
 * ------------------------------------------------------------------------
 * General hooks
 * ------------------------------------------------------------------------
 */
// in order to have gzip enabled we need to prevent wp shutdown flushing.
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
remove_theme_support( 'widgets-block-editor' );
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * ------------------------------------------------------------------------
 * WooCommerce Specifics & Overrides
 * ------------------------------------------------------------------------
 */
// deregister woocommerce styles.
add_action( 'wp_enqueue_scripts', 'lisfinity_deregister_wc_styles' );

/**
 * ------------------------------------------------------------------------
 * WooCommerce Custom Product Types Hooks
 * ------------------------------------------------------------------------
 */
$product_model = new \Lisfinity\Models\ProductModel();
add_action( 'init', [ $product_model, 'init' ] );
add_filter( "manage_edit-product_columns", [ $product_model, 'manage_columns' ] );
add_action( "manage_product_posts_custom_column", [ $product_model, 'manage_custom_column' ], 10, 2 );

/**
 * ------------------------------------------------------------------------
 * WooCommerce Custom Payment Gateways Hooks
 * ------------------------------------------------------------------------
 */
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-gateways/WC_Gateway_Stripe_Connect.php';
add_filter( 'woocommerce_payment_gateways', 'lisfinity_add_to_gateways' );
function lisfinity_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_Stripe_Connect';

	return $gateways;
}


/*
 * Have to create custom product types like this because composer
 * is loading them too fast for WooCommerce Product creation.
 * --------------------------------------------------------------
 */
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-product/WC_Product_Listing.php';
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-product/WC_Product_Payment_Package.php';
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-product/WC_Product_Payment_Subscription.php';
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-product/WC_Product_Promotion.php';
require_once LISFINITY_CORE_DIR . 'includes/woocommerce-src/woocommerce-product/WC_Product_Commission.php';

use Lisfinity\Models\Reports\ReportAdmin;
use Lisfinity\Models\Stats\StatModel;
use Lisfinity\Models\Testimonials\TestimonialModel;
use Lisfinity\Models\Users\ProfilesModel;
use Lisfinity\REST_API\Business\BusinessRoute;
use Lisfinity\REST_API\Menus\MenusRoute;
use Lisfinity\REST_API\SingleBuilder\SingleBuilderRoute;
use Lisfinity\REST_API\Stats\StatsRoute;
use Lisfinity\REST_API\Testimonial\TestimonialRoute;
use Lisfinity\REST_API\Tips\TipsRoute;
use Lisfinity\REST_API\WooCommerce\WooCommerceRoute;
use Lisfinity\WooCommerce\WC_Product_Listing_Model as Listing_Admin;
use Lisfinity\WooCommerce\WC_Payment_Package_Model as Package_Admin;
use Lisfinity\WooCommerce\WC_Payment_Subscription_Model as Subscription_Admin;
use Lisfinity\WooCommerce\WC_Promotion_Model as Promotion_Admin;

$wc_listing_admin       = new Listing_Admin();
$wc_package_admin       = new Package_Admin();
$wc_subscription_admin  = new Subscription_Admin();
$wc_promotion_admin     = new Promotion_Admin();
$wc_commission_model    = new \Lisfinity\WooCommerce\WC_Commission_Model();
$premium_profiles_model = new ProfilesModel();
add_action( 'init', [ $wc_listing_admin, 'init' ] );
add_action( 'init', [ $wc_package_admin, 'init' ] );
add_action( 'init', [ $wc_subscription_admin, 'init' ] );
add_action( 'init', [ $wc_promotion_admin, 'init' ] );
add_action( 'init', [ $wc_commission_model, 'init' ] );
add_action( 'init', [ $premium_profiles_model, 'init' ] );


/**
 * ------------------------------------------------------------------------
 * Package Hooks
 * ------------------------------------------------------------------------
 */

use Lisfinity\Models\PackageModel as PackageModel;
use Lisfinity\Controllers\PackageController as PackageController;

$package = new PackageModel();
add_action( 'init', [ $package, 'init' ], 10 );
add_action( 'wp', [ $package, 'add_to_cart' ] );

$subscription = new \Lisfinity\Models\SubscriptionModel();
add_action( 'init', [ $subscription, 'init' ], 10 );
add_action( 'wp', [ $subscription, 'add_to_cart' ] );
add_action( 'woocommerce_order_status_completed', [ $subscription, 'order_paid' ] );
add_action( 'woocommerce_checkout_create_order_line_item', [ $subscription, 'add_product_to_order' ], 10, 4 );
add_filter( "manage_edit-{$subscription::$type}_columns", [ $subscription, 'manage_columns' ] );
add_action( "manage_{$subscription::$type}_posts_custom_column", [ $subscription, 'manage_custom_column' ], 10, 2 );

use Lisfinity\Models\PromotionsModel as PromotionModel;
use Lisfinity\Controllers\PromotionController as PromotionController;
use Lisfinity\Models\Messages\ChatModel as ChatModel;
use Lisfinity\Models\Messages\MessageModel as MessageModel;
use Lisfinity\Models\Bids\BidModel as BidModel;
use Lisfinity\Models\Reports\ReportModel as ReportModel;
use Lisfinity\Models\Tips\TipsModel as TipModel;

// promotions.
$promotion = new PromotionModel();
add_action( 'init', [ $promotion, 'init' ] );

// agents.
$agent_model = new \Lisfinity\Models\Agents\AgentModel();
add_action( 'init', [ $agent_model, 'init' ] );

// messages.
$chat          = new ChatModel();
$message_model = new MessageModel();
add_action( 'init', [ $chat, 'init' ], 10 );
add_action( 'init', [ $message_model, 'init' ], 10 );

// bids.
$bids_model = new BidModel();
add_action( 'init', [ $bids_model, 'init' ], 10 );

// product compare.
$compare_model = new \Lisfinity\Models\Compare\CompareModel();
add_action( 'init', [ $compare_model, 'init' ], 10 );

// notification.
$notifications_model = new NotificationModel();
add_action( 'init', [ $notifications_model, 'init' ], 10 );

// stats.
$stats_model = new StatModel();
add_action( 'init', [ $stats_model, 'init' ], 10 );

// packages controller.
$package_controller = new PackageController();
add_action( 'wp_ajax_store_package', [ $package_controller, 'store' ], 10 );
add_action( 'wp_ajax_buy_package', [ $package_controller, 'add_to_cart' ], 10 );
add_action( 'woocommerce_order_status_processing', [ $package_controller, 'order_processing' ] );
add_action( 'woocommerce_order_status_completed', [ $package_controller, 'order_paid' ] );

// promotions controller.
$promotion_controller = new PromotionController();
add_action( 'woocommerce_order_status_completed', [ $promotion_controller, 'order_paid' ] );
add_action( 'woocommerce_checkout_create_order_line_item', [ $promotion_model, 'add_product_to_order' ], 10, 4 );

// product controller.
$product_controller = new \Lisfinity\Controllers\ProductController();
add_action( 'woocommerce_order_status_completed', [ $product_controller, 'order_paid' ] );

// reports model.
$reports_model       = new ReportModel();
$reports_admin_model = new ReportAdmin();
$reports_type        = $reports_model::$type;
add_action( 'init', [ $reports_model, 'init' ] );

// payouts model.
$payouts_model = new \Lisfinity\Models\Vendors\PayoutsModel();
add_action( 'init', [ $payouts_model, 'init' ] );
add_action( 'woocommerce_order_status_completed', [ $payouts_model, 'insert_payout' ] );
add_filter( "manage_edit-{$payouts_model::$type}_columns", [ $payouts_model, 'manage_columns' ] );
add_action( "manage_{$payouts_model::$type}_posts_custom_column", [ $payouts_model, 'manage_custom_column' ], 10, 2 );
add_filter( "views_edit-{$payouts_model::$type}", [ $payouts_model, 'add_process_payments_button_to_views' ] );

// reports admin model.
add_filter( 'admin_menu', [ $reports_admin_model, 'report_admin_menu' ] );
add_filter( 'parent_file', [ $reports_admin_model, 'admin_menu_parent_file' ] );
add_filter( 'submenu_file', [ $reports_admin_model, 'admin_menu_submenu_file' ] );
add_filter( "manage_edit-{$reports_type}_columns", [ $reports_admin_model, 'manage_columns' ] );
add_action( "manage_{$reports_type}_posts_custom_column", [ $reports_admin_model, 'manage_custom_column' ], 10, 2 );
add_action( 'restrict_manage_posts', [ $reports_admin_model, 'manage_column_filter_status_dropdown' ] );
add_action( 'pre_get_posts', [ $reports_admin_model, 'manage_column_status_filter' ] );
add_filter( 'post_updated_messages', [ $reports_admin_model, 'post_updated_messages' ] );

// testimonials model.
$testimonials_model = new TestimonialModel();
add_action( 'init', [ $testimonials_model, 'init' ] );

// tips model.
$tips_model = new TipModel();
add_action( 'init', [ $tips_model, 'init' ] );

// auth models.
$register_model = new RegisterModel();
add_action( 'init', [ $register_model, 'verify_user' ] );
add_action( 'woocommerce_created_customer', [ $register_model, 'lisfinity_create_business_profile_for_wc_customer' ], 10, 3 );

// importer models.
$importer_model = new \Lisfinity\Models\Taxonomies\ImporterModel();
add_action( 'saved_term', [ $importer_model, 'add_term_to_taxonomy_order' ], 10, 3 );

/**
 * ------------------------------------------------------------------------
 * WooCommerce Hooks
 * ------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------
 * Ajax Hooks
 * ------------------------------------------------------------------------
 */
add_action( 'wp_ajax_buy_promotion', [ $promotion_model, 'buy_promotion_checkout_prepare' ] );

/**
 * ------------------------------------------------------------------------
 * Admin Hooks
 * ------------------------------------------------------------------------
 */

use Lisfinity\Models\Taxonomies\GroupsAdminModel as GroupsAdmin;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel as TaxonomiesAdmin;
use Lisfinity\Models\SearchBuilder\SearchBuilderModel as SearchBuilderAdmin;

$admin_taxonomies = new TaxonomiesAdmin();
if ( '0' !== lisfinity_get_option( 'site-fields-builder' ) ) {
	add_action( 'admin_menu', [ $admin_taxonomies, 'admin_menu' ], 10 );
}
add_action( 'init', [ $admin_taxonomies, 'register_new_taxonomies' ], 1 );

$admin_groups = new GroupsAdmin();
if ( '0' !== lisfinity_get_option( 'site-fields-builder' ) ) {
	add_action( 'admin_menu', [ $admin_groups, 'admin_menu' ], 20 );
}

$admin_search_builder = new SearchBuilderAdmin();
if ( '0' !== lisfinity_get_option( 'site-search-builder' ) ) {
	add_action( 'admin_menu', [ $admin_search_builder, 'admin_menu' ] );
}

/**
 * -----------------------------------------------------------------------
 * Lisfinity Elements
 * -----------------------------------------------------------------------
 */
$elements_model = new \Lisfinity\Models\Elements\ElementsModel();
add_action( 'admin_menu', [ $elements_model, 'admin_menu' ] );
$header_model = new \Lisfinity\Models\Elements\HeaderModel();
add_action( 'init', [ $header_model, 'init' ] );
add_action( 'admin_menu', [ $header_model, 'admin_menu' ], 20 );
$footer_model = new \Lisfinity\Models\Elements\FooterModel();
add_action( 'init', [ $footer_model, 'init' ] );
add_action( 'admin_menu', [ $footer_model, 'admin_menu' ], 20 );
$elements_global_model = new \Lisfinity\Models\Elements\ElementsGlobalModel();
add_action( 'init', [ $elements_global_model, 'init' ] );
add_action( 'admin_menu', [ $elements_global_model, 'admin_menu' ], 30 );

/**
 * -----------------------------------------------------------------------
 * Template Redirects
 * -----------------------------------------------------------------------
 */
$reset_password_model = new PasswordResetModel();
add_action( 'template_redirect', [ $reset_password_model, 'redirect_reset_password_link' ] );

/**
 * ------------------------------------------------------------------------
 * Theme Options Hooks
 * ------------------------------------------------------------------------
 */
add_action( 'carbon_fields_theme_options_container_saved', 'lisfinity_update_wc_option' );
add_action( 'updated_option', 'lisfinity_update_theme_option', 10, 3 );

add_action( 'save_post_product', 'lisfinity_update_meta', 100, 3 );

/**
 * ------------------------------------------------------------------------
 * REST API Hooks
 * ------------------------------------------------------------------------
 */

// taxonomy routes.
use Lisfinity\REST_API\Taxonomies\GroupRoute as GroupRoute;
use Lisfinity\REST_API\Taxonomies\TaxonomyRoute as TaxonomyRoute;
use Lisfinity\REST_API\Taxonomies\TermRoute as TermRoute;

$group_route = new GroupRoute();
add_action( 'rest_api_init', [ $group_route, 'register_routes' ], 10 );

$taxonomy_route = new TaxonomyRoute();
add_action( 'rest_api_init', [ $taxonomy_route, 'register_routes' ], 10 );

$term_route = new TermRoute();
add_action( 'rest_api_init', [ $term_route, 'register_routes' ], 10 );

// forms routes.
use Lisfinity\REST_API\Forms\FormSubmitRoute as FormSubmitRoute;

$form_submit_route = new FormSubmitRoute();
add_action( 'rest_api_init', [ $form_submit_route, 'register_routes' ], 10 );

// products routes.
use Lisfinity\REST_API\Products\ProductsRoute as ProductsRoute;

$products_route = new ProductsRoute();
add_action( 'rest_api_init', [ $products_route, 'register_routes' ], 10 );

// business routes.
$business_route = new BusinessRoute();
add_action( 'rest_api_init', [ $business_route, 'register_routes' ], 10 );

// options routes.
use Lisfinity\REST_API\Options\OptionsRoute as OptionsRoute;

$options_route = new OptionsRoute();
add_action( 'rest_api_init', [ $options_route, 'register_routes' ], 10 );

// message routes.
use Lisfinity\REST_API\Messages\MessagesRoute as MessagesRoute;

$messages_route = new MessagesRoute();
add_action( 'rest_api_init', [ $messages_route, 'register_routes' ], 10 );

// bids routes.
use Lisfinity\REST_API\Bids\BidsRoute as BidsRoute;

$bids_route = new BidsRoute();
add_action( 'rest_api_init', [ $bids_route, 'register_routes' ], 10 );

// notifications routes.
use Lisfinity\REST_API\Notifications\NotificationsRoute as NotificationsRoute;

$notifications_route = new NotificationsRoute();
add_action( 'rest_api_init', [ $notifications_route, 'register_routes' ], 10 );

// stats routes.
$stats_route = new StatsRoute();
add_action( 'rest_api_init', [ $stats_route, 'register_routes' ], 10 );

// search builder routes.
use Lisfinity\REST_API\SearchBuilder\SearchBuilderRoute as SearchBuilderRoute;

$search_builder_routes = new SearchBuilderRoute();
add_action( 'rest_api_init', [ $search_builder_routes, 'register_routes' ], 10 );

// single builder routes.
$single_builder_routes = new SingleBuilderRoute();
add_action( 'rest_api_init', [ $single_builder_routes, 'register_routes' ], 10 );

// search builder routes.
use Lisfinity\REST_API\Search\SearchRoute as SearchRoute;

$search_routes = new SearchRoute();
add_action( 'rest_api_init', [ $search_routes, 'register_routes' ], 10 );

// user routes.
use Lisfinity\REST_API\Users\UserRoute as UserRoute;

$user_routes = new UserRoute();
add_action( 'rest_api_init', [ $user_routes, 'register_routes' ], 10 );

// agent routes.
$agent_routes = new \Lisfinity\REST_API\Agents\AgentsRoute();
add_action( 'rest_api_init', [ $agent_routes, 'register_routes' ], 10 );

// report routes.
use Lisfinity\REST_API\Reports\ReportsRoute as ReportRoute;

$report_routes = new ReportRoute();
add_action( 'rest_api_init', [ $report_routes, 'register_routes' ], 10 );

// testimonial routes.
$testimonial_routes = new TestimonialRoute();
add_action( 'rest_api_init', [ $testimonial_routes, 'register_routes' ], 10 );

// authentication routes.
use Lisfinity\REST_API\Authentication\AuthenticationRoute as AuthRoute;

$auth_routes = new AuthRoute();
add_action( 'rest_api_init', [ $auth_routes, 'register_routes' ], 10 );

// dashboard routes.
use Lisfinity\REST_API\Dashboard\DashboardRoute as DashboardRoute;

$dashboard_routes = new DashboardRoute();
add_action( 'rest_api_init', [ $dashboard_routes, 'register_routes' ], 10 );

// woocommerce routes.
$wc_routes = new WooCommerceRoute();
add_action( 'rest_api_init', [ $wc_routes, 'register_routes' ], 10 );

// security tips routes.
$tips_routes = new TipsRoute();
add_action( 'rest_api_init', [ $tips_routes, 'register_routes' ], 10 );

// menus routes.
$menus_routes = new MenusRoute();
add_action( 'rest_api_init', [ $menus_routes, 'register_routes' ], 10 );

// payouts routes.
$payouts_routes = new \Lisfinity\REST_API\Payouts\PayoutsRoute();
add_action( 'rest_api_init', [ $payouts_routes, 'register_routes' ], 10 );

// other routes.
$other_routes = new \Lisfinity\REST_API\Other\OtherRoute();
add_action( 'rest_api_init', [ $other_routes, 'register_routes' ], 10 );
$licence_routes = new \Lisfinity\REST_API\Other\LicenceRoute();
add_action( 'rest_api_init', [ $licence_routes, 'register_routes' ], 10 );

/**
 * -------------------------------------------------------------------------
 * Menu Hooks
 * -------------------------------------------------------------------------
 */
$menu_model = new \Lisfinity\Models\Menu\MenuModel();
add_filter( 'wp_nav_menu_items', [ $menu_model, 'add_submit_button' ], 10, 2 );
add_filter( 'wp_nav_menu_items', [ $menu_model, 'add_login_button' ], 10, 2 );
add_filter( 'wp_nav_menu_items', [ $menu_model, 'add_notifications_button' ], 10, 2 );
add_filter( 'wp_nav_menu_items', [ $menu_model, 'add_cart_button' ], 10, 2 );

/**
 * -------------------------------------------------------------------------
 * Functions Hooks
 * -------------------------------------------------------------------------
 */
add_action( 'init', 'lisfinity_register_product_statuses' );
add_action( 'wp_head', 'lisfinity_editor_init' );
add_action( 'wp_trash_post', 'lisfinity_transition_to_rejected', 10, 1 );

/**
 * -------------------------------------------------------------------------
 * Shortcodes Hooks
 * -------------------------------------------------------------------------
 */

use Lisfinity\Shortcodes\Shortcodes as shortcodes;

$shortcodes = new shortcodes();
add_action( 'init', [ $shortcodes, 'init' ] );
add_action( 'elementor/controls/controls_registered', [ $shortcodes, 'register_controls' ] );

/**
 * -------------------------------------------------------------------------
 * Widgets Hooks
 * -------------------------------------------------------------------------
 */

use Lisfinity\Widgets\Widgets as widgets;

$widgets = new widgets();
$widgets->init();

/**
 * -------------------------------------------------------------------------
 * Theme Functions Hooks
 * -------------------------------------------------------------------------
 */

add_action( 'template_redirect', 'lisfinity_redirect_logged_user_from_auth_pages' );
add_action( 'init', 'lisfinity_custom_rewrite_rules' );
add_action( 'init', 'lisfinity_set_direction' );
add_action( 'lisfinity__store_impression', 'lisfinity_store_impression', 10, 1 );
add_filter( 'body_class', 'lisfinity_page_body_class' );
add_action( 'lisfinity__header_functions', 'lisfinity_google_analytics' );
add_action( 'lisfinity__footer_functions', 'lisfinity_footer_middle' );
add_action( 'lisfinity__footer_functions', 'lisfinity_footer_copyrights' );
add_action( 'lisfinity__footer_functions', 'lisfinity_additional_js' );
// url rewriting custom categories creating in the fields builder.
add_filter( 'query_vars', 'lisfinity_category_query_vars' );
add_action( 'init', 'lisfinity_category_rewrites_init' );
add_filter( 'document_title_parts', 'lisfinity_category_wp_title' );

// admin.
add_action( 'admin_head', 'lisfinity_menu_pending_count' );

// user hooks.
add_filter( 'option_show_avatars', '__return_false' );
add_filter( 'ajax_query_attachments_args', 'lisfinity_restrict_media_attachments' );
if ( ! current_user_can( 'administrator' ) ) {
	add_filter( 'upload_size_limit', 'lisfinity_upload_size_limit' );
}

// ads import hooks.
$importer_general = new General();
$importer_details = new Details();
$importer_address = new Address();
$importer_media   = new Media();
add_action( 'init', [ $importer_general, 'init' ], 10 );
add_action( 'init', [ $importer_details, 'init' ], 10 );
add_action( 'init', [ $importer_address, 'init' ], 10 );
add_action( 'init', [ $importer_media, 'init' ], 10 );

/**
 * -------------------------------------------------------------------------
 * Theme Cron Jobs
 * -------------------------------------------------------------------------
 */
$schedules_model = new \Lisfinity\Schedules\SchedulesModel();
add_action( 'init', [ $schedules_model, 'init' ] );
add_action( 'lisfinity__demo_cron_daily', [ $schedules_model, 'relist_ads' ] );
add_action( 'lisfinity__demo_cron_daily', [ $schedules_model, 'relist_promotions' ] );
add_action( 'lisfinity__demo_cron_hourly', [ $schedules_model, 'restart_ads_promotions' ] );
add_action( 'lisfinity__cron_twice_daily', [ $schedules_model, 'send_ad_expiration_email' ] );
add_action( 'lisfinity__cron_twice_daily', [ $schedules_model, 'notify_users_of_new_products' ] );
add_action( 'lisfinity__cron_daily', [ $schedules_model, 'delete_expired_listings' ] );
add_action( 'lisfinity__cron_hourly', [ $schedules_model, 'change_expired_ads_status' ] );
add_action( 'lisfinity__cron_hourly', [ $schedules_model, 'change_expired_promotions_status' ] );

/**
 * -------------------------------------------------------------------------
 * User Functions Hooks
 * -------------------------------------------------------------------------
 */
add_action( 'init', 'lisfinity_disable_admin_bar' );
add_action( 'admin_init', 'lisfinity_no_admin_access' );
add_filter( 'intermediate_image_sizes_advanced', 'lisfinity_leave_only_original_images' );
add_action( 'admin_init', 'lisfinity_settings_init' );
add_action( 'nsl_register_new_user', 'lisfinity_create_business_post_after_social_login', 10 );
// make sure that the avatar is updated with the one from the social page.
add_filter( 'nsl_avatar_store', '__return_true' );
// delete agent
add_action( 'delete_user', 'lisfinity_on_user_delete', 10, 3 );

function lisfinity_wp_head() {
	if ( is_singular( 'product' ) ) {
		$type = get_the_terms( get_the_ID(), 'product_type' );
		if ( in_array( $type[0]->slug, [ 'listing' ] ) ) {
			$product_slug = lisfinity_get_option( 'slug-product' );
			?>
			<meta property="title" content="<?php echo esc_attr( get_the_title() ); ?>"/>
			<meta property="description" content="<?php echo esc_attr( wp_strip_all_tags( get_the_content() ) ); ?>"/>
			<link rel="image_src" href="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"/>
			<meta property="og:type" content="<?php echo esc_attr( ! empty( $product_slug ) ? $product_slug : 'product' ); ?>"/>
			<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
			<meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>"/>
			<meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( get_the_content() ) ); ?>"/>
			<meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>"/>
			<meta property="og:image" content="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"/>
			<?php
		}
	}
}

add_action( 'wp_head', 'lisfinity_wp_head' );


/**
 * -------------------------------------------------------------------------
 * General Functions Hooks
 * -------------------------------------------------------------------------
 */
if ( ! function_exists( 'lisfinity_dequeue_scripts' ) ) {
	/**
	 * Dequeue conflicting scripts from the third-party plugins.
	 * ---------------------------------------------------------
	 *
	 */
	function lisfinity_dequeue_scripts() {
		if ( lisfinity_is_page_template( 'page-account' ) ) {
			wp_dequeue_script( 'contact-form-7' );
		}
	}

	add_action( 'wp_head', 'lisfinity_dequeue_scripts', 1 );
}
