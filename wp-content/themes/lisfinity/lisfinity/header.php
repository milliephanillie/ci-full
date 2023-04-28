<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
    <link rel="shortcut icon" href="<?php echo trailingslashit(home_url()); ?>wp-content/themes/lisfinity/lisfinity/custom/images/favicon.png" type="image/x-icon" />
	<?php do_action( 'lisfinity__head_functions' ); ?>
	<?php wp_head(); ?>
</head>
<?php do_action( 'lisfinity__header_functions' ); ?>
<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

<?php wp_body_open(); ?>
<?php global $lisfinity_options; ?>

<?php if ( is_singular( 'lisfinity_header' ) || is_singular( 'lisfinity_footer' ) || is_singular( 'lisfinity-elements' ) ) : ?>
	<?php return; ?>
<?php endif; ?>

<?php if ( ! lisfinity_is_core_active() || ( ! empty( $lisfinity_options ) && get_queried_object_id() !== (int) $lisfinity_options['_page-account'] ) ) : ?>
	<?php if ( ( lisfinity_is_core_active() && 'custom' === lisfinity_get_option( 'header-type' ) ) ) : ?>
		<header id="header--main" class="relative">
			<?php
			$header      = lisfinity_get_option( 'header-post' );
			$header_page = carbon_get_post_meta( get_queried_object_id(), 'page-header' );
			if ( ! empty( $header_page ) ) {
				$header = $header_page;
			}
			echo lisfinity_get_elementor_content( $header );
			if ( lisfinity_is_core_active() && ( lisfinity_is_page_template( 'page-search' ) || lisfinity_is_category_page_template() ||  is_singular( \Lisfinity\Models\Users\ProfilesModel::$post_type_name ) ) ) : ?>
			<div id="mobile-menu--search" class="relative ml-auto"></div>
			<?php endif; ?>
		</header>
		<?php $sticky_header = lisfinity_get_option( 'header-sticky-post' ); ?>
		<?php if ( ! empty( $sticky_header ) ) : ?>
			<header id="header--main-sticky" class="relative">
				<?php echo lisfinity_get_elementor_content( $sticky_header ); ?>
			</header>
		<?php endif; ?>
	<?php else: ?>
		<header
			id="header--main"
			class="relative flex flex-wrap py-24 px-30 w-full <?php echo lisfinity_is_core_active() && is_front_page() ? 'header--home bg-transparent' : 'bg-header'; ?>">
			<?php get_template_part( 'templates/headers/header-main' ); ?>
		</header>
	<?php endif; ?>
<?php endif; ?>
