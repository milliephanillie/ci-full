<?php
/**
 * The template for displaying Breadcrumbs
 *
 * @package WordPress
 * @subpackage Lisfinity
 * @since 1.0.0
 * @author pebas
 */
?>

<!-- Breadcrumbs -->
<?php if ( ( lisfinity_is_core_active() && lisfinity_is_enabled( lisfinity_get_option( 'pages-breadcrumbs' ) ) ) || ! lisfinity_is_core_active() ) : ?>
	<div
		class="breadcrumbs post-breadcrumbs flex py-30 px-20 <?php echo lisfinity_is_woocommerce_active() && ( is_shop() || is_product() || is_cart() || is_checkout() ) ? esc_attr( 'bg-white' ) : esc_attr( 'bg-grey-100' ); ?>">

		<nav aria-label="breadcrumb" class="single-listing-breadcrumb breadcrumb container sm:px-30">
			<ol class="breadcrumb flex items-center font-semibold text-grey-700">
				<li class="breadcrumb-item home-cat">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center">
						<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
							 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve"
							 class="fill-red-500 w-16 h-16"
						>
						<path d="M56.5,19.5L36,1.6c-2.4-2.1-5.9-2.1-8,0L7.5,19.5c-1.3,1.3-2.1,2.9-2.1,4.8v33.6c0,3.5,2.7,6.1,6.1,6.1h9.1
						c3.5,0,6.1-2.7,6.1-6.1V46.1c0-0.5,0.3-0.8,0.8-0.8h9.1c0.5,0,0.8,0.3,0.8,0.8v11.7c0,3.5,2.7,6.1,6.1,6.1h9.1
						c3.5,0,6.1-2.7,6.1-6.1V24.3C58.7,22.4,57.9,20.8,56.5,19.5z M53.3,57.9c0,0.5-0.3,0.8-0.8,0.8h-9.1c-0.5,0-0.8-0.3-0.8-0.8V46.1
						c0-3.5-2.7-6.1-6.1-6.1h-9.1c-3.5,0-6.1,2.7-6.1,6.1v11.7c0,0.5-0.3,0.8-0.8,0.8h-9.1c-0.5,0-0.8-0.3-0.8-0.8V24.3
						c0-0.3,0-0.5,0.3-0.5L31.5,5.9c0.3-0.3,0.8-0.3,1.1,0L53,23.8c0.3,0,0.3,0.3,0.3,0.5L53.3,57.9L53.3,57.9z"/>
					</svg>
						<span class="ml-6"><?php echo esc_html( get_option( 'blogname' ) ); ?></span>
					</a>
				</li>
				<?php if ( is_404() ): ?>
					<li class="breadcrumb-item"><?php esc_html_e( 'Error 404', 'lisfinity' ); ?></li>
				<?php elseif ( is_archive() ) : ?>
					<li class="breadcrumb-item"><?php the_archive_title(); ?></li>
				<?php elseif ( is_search() ) : ?>
					<li class="breadcrumb-item"><?php the_search_query(); ?></li>
				<?php elseif ( get_queried_object_id() ) : ?>
					<li class="breadcrumb-item"><?php echo wp_kses_post( _( get_the_title( get_queried_object_id() ) ) ); ?></li>
				<?php endif; ?>
			</ol>
		</nav>
	</div>
<?php endif; ?>
