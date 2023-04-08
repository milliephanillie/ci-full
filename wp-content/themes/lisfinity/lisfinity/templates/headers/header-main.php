<?php
/**
 * Template Part: Header Main
 * Description: Template part used for displaying main version of the header
 *
 * @author pebas
 * @package templates/header
 * @version 1.0.0
 */

use Lisfinity\Models\Users\ProfilesModel;

?>

<?php $logo_and_title = lisfinity_is_core_active() ? lisfinity_get_option( 'identity-logo-title' ) : false; ?>
<?php $logo = lisfinity_is_core_active() ? lisfinity_get_option( 'identity-logo' ) : get_theme_mod( 'custom_logo' ); ?>
<?php $logo_padding = lisfinity_is_core_active() ? lisfinity_get_option( 'identity-logo-padding' ) : ''; ?>
<?php $logo_size = lisfinity_is_core_active() ? lisfinity_get_option( 'identity-logo-size' ) : ''; ?>
<?php $header_taxonomy = lisfinity_is_core_active() ? lisfinity_get_option( 'header-taxonomy' ) : ''; ?>
<?php $header_keyword = lisfinity_is_core_active() ? lisfinity_get_option( 'header-keyword' ) : ''; ?>

<div
	class="relative flex flex-wrap items-start w-1/2 sm:w-auto <?php echo ! empty( $header_keyword ) ? esc_attr( 'mr-20' ) : ''; ?>">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
	   class="logo <?php echo '1' === $logo_and_title ? esc_attr( 'flex items-center mr-1' ) : ''; ?>"
	   style="<?php echo ! empty( $logo_padding ) ? esc_attr( "padding: {$logo_padding};" ) : esc_attr( 'padding: 0' ); ?>">
		<?php if ( ! empty( $logo['url'] ) ) : ?>
			<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( get_option( 'blogname' ) ); ?>"
				 style="<?php echo ! empty( $logo_size ) ? esc_attr( "width: {$logo_size}px" ) : ''; ?>"
				 class="<?php echo '1' === $logo_and_title ? esc_attr( 'mr-20' ) : ''; ?>">
		<?php endif; ?>
		<?php if ( empty( $logo['url'] ) || '1' === $logo_and_title ): ?>
			<div class="flex flex-col">
				<h2 class="site-title font-bold text-4xl text-white"><?php echo esc_html( get_option( 'blogname' ) ); ?></h2>
				<?php if ( ! empty( get_option( 'blogdescription' ) ) ) : ?>
					<p class="text-white"><?php echo esc_html( get_option( 'blogdescription' ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</a>
</div>
<?php if ( lisfinity_is_core_active() && ( $header_taxonomy || '1' === $header_keyword ) ) : ?>
	<?php if ( $header_taxonomy ) : ?>
		<?php $taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel(); ?>
		<?php $taxonomy_options = $taxonomy_model->get_taxonomy_options( $header_taxonomy ); ?>
		<?php $data_icon = ! empty( $taxonomy_options['icon'] ) ? 'data-icon=' . esc_url( wp_get_attachment_image_url( $taxonomy_options['icon'], 'full' ) ) : ''; ?>
		<?php $options = [
			'custom_category_pages' => lisfinity_is_enabled( lisfinity_get_option( 'custom-category-pages' ) ),
			'avatar'                => lisfinity_get_avatar_url()
		]; ?>
		<!-- Header | Location -->
		<div id="header-taxonomy" data-taxonomy="<?php echo esc_attr( $header_taxonomy ); ?>"
			 data-options="<?php echo esc_attr( json_encode( $options ) ); ?>"
			<?php echo esc_attr( $data_icon ); ?>
			 class="hidden sm:flex sm:mx-10 sm:-mt-6"></div>
	<?php endif; ?>
	<?php if ( '1' === $header_keyword && lisfinity_is_core_active() && ! lisfinity_is_page_template( 'page-home' ) ) : ?>
		<div id="header-keyword" class="header-keyword"></div>
	<?php endif; ?>
<?php endif; ?>
<?php if ( lisfinity_is_core_active() && ( lisfinity_is_page_template( 'page-search' ) || lisfinity_is_category_page_template() ||  is_singular( ProfilesModel::$post_type_name ) ) ) : ?>
	<div id="mobile-menu--search" class="relative ml-auto"></div>
<?php endif; ?>
<?php if ( lisfinity_is_core_active() ) :
	$is_search = lisfinity_is_page_template( 'page-search' ) || is_singular( ProfilesModel::$post_type_name );
	$options = [
		'enable_packages' => lisfinity_is_core_active() && lisfinity_packages_enabled( get_current_user_id() ),
		'enable_cart'     => '' === lisfinity_get_option( 'header-cart' ),
		'social_text'     => lisfinity_get_option( 'mobile-menu-social-text' ),
		'avatar'          => lisfinity_get_avatar_url()
	];
	?>
	<div id="mobile-menu--wrapper" class="relative ml-auto"
		 data-options="<?php echo esc_attr( json_encode( $options ) ); ?>"
	></div>
<?php endif; ?>

<?php if ( ! lisfinity_is_core_active() ) : ?>
	<button type="button" class="menu--trigger lg:hidden ml-auto cursor-pointer">
		<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
			 x="0px" y="0px"
			 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
			 class="h-32 w-32 fill-white"
		>
		<g>
			<path d="M5.5,31.7h89c1.5,0,2.8-1.2,2.8-2.8s-1.2-2.8-2.8-2.8h-89c-1.5,0-2.8,1.2-2.8,2.8S4,31.7,5.5,31.7z"/>
			<path
				d="M94.5,47.3h-89c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h89c1.5,0,2.8-1.2,2.8-2.8S96,47.3,94.5,47.3z"/>
			<path
				d="M94.5,68.3h-89c-1.5,0-2.8,1.2-2.8,2.8s1.2,2.8,2.8,2.8h89c1.5,0,2.8-1.2,2.8-2.8S96,68.3,94.5,68.3z"/>
		</g>
	</svg>
	</button>
<?php endif; ?>
<div class="menu--lisfinity ml-auto hidden lg:flex">
	<?php if ( ! lisfinity_is_core_active() ) : ?>
		<div class="menu--close relative inline-flex cursor-pointer lg:hidden">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
				 x="0px" y="0px"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
				 class="h-24 w-24 fill-black"
			>
		<path d="M53.9,50L96.9,6.9c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L50,46.1L6.9,3.1C5.9,2,4.1,2,3.1,3.1C2,4.1,2,5.9,3.1,6.9
			L46.1,50L3.1,93.1c-1.1,1.1-1.1,2.8,0,3.9c0.5,0.5,1.2,0.8,1.9,0.8s1.4-0.3,1.9-0.8L50,53.9l43.1,43.1c0.5,0.5,1.2,0.8,1.9,0.8
			s1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L53.9,50z"/>
		</svg>
		</div>
	<?php endif; ?>
	<?php
	wp_nav_menu( [
		'theme_location'  => 'main-menu',
		'container'       => 'nav',
		'container_id'    => 'menu-main',
		'container_class' => wp_is_mobile() ? esc_attr( ' mobile-nav' ) : esc_attr( 'flex' ),
		'items_wrap'      => '<ul class="flex flex-col items-center lg:flex-row z-10">%3$s</ul>',
		'walker'          => new Lisfinity\Menus\Walker_Main_Menu(),
	] );
	?>
</div>
