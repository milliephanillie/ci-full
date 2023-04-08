<?php
/**
 * Template Name: Shortcodes | Navigation Menu
 * Description: The file that is being used to display theme navigation
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */
?>

<?php if ( lisfinity_is_page_template( 'page-search' ) || is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name ) ) : ?>
	<div id="mobile-menu--search" class="relative ml-auto"></div>
<?php endif; ?>
<?php
$is_search = lisfinity_is_page_template( 'page-search' ) || is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name );
$options   = [
	'is_elementor'           => true,
	'enable_avatar'          => empty( $args['settings']['display_header_avatar'] ) || 'no' !== $args['settings']['display_header_avatar'],
	'enable_packages'        => lisfinity_packages_enabled( get_current_user_id() ),
	'enable_cart'            => ! empty( $args['settings']['header_icons_cart_display'] ) && 'yes' === $args['settings']['header_icons_cart_display'],
	'enable_notifications'   => ! empty( $args['settings']['header_icons_notification_display'] ) && 'yes' === $args['settings']['header_icons_notification_display'],
	'enable_compare'         => ! empty( $args['settings']['header_icons_compare_display'] ) && 'yes' === $args['settings']['header_icons_compare_display'],
	'notifications_interval' => lisfinity_is_enabled( lisfinity_get_option( 'site-refresh-notifications' ) ) && lisfinity_get_option( 'site-refresh-notifications-interval' ) > 0 ? lisfinity_get_option( 'site-refresh-notifications-interval' ) : 0,
];
?>
<div id="mobile-menu--wrapper"
	 class="relative <?php echo ! $is_search ? esc_attr( 'ml-auto' ) : ''; ?>"
	 data-options="<?php echo esc_attr( json_encode( $options ) ); ?>"
></div>

<div class="menu--lisfinity ml-auto hidden lg:flex">
	<?php
	wp_nav_menu( [
		'theme_location'  => $args['settings']['nav_menu'] ?? 'main-menu',
		'menu_id'         => 'elementor-menu',
		'container'       => 'nav',
		'container_id'    => 'menu-main',
		'container_class' => wp_is_mobile() ? esc_attr( ' mobile-nav' ) : esc_attr( 'flex' ),
		'items_wrap'      => '<ul id="main-menu-ul" class="flex flex-col items-center lg:flex-row z-10" data-options="' . esc_attr( json_encode( $options ) ) . '">%3$s</ul>',
		'walker'          => new \Lisfinity\Menus\Walker_Main_Menu(),
	] );
	?>
</div>
