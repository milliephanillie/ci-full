<?php
/**
 * All dynamically set theme colors styles are based in this file.
 *
 * @author pebas
 * @package Lisfinity
 * @version 1.0.0
 */
/*$absolute_path = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
$wp_load       = $absolute_path[0] . 'wp-load.php';
require_once( $wp_load );

header( 'Content-type: text/css' );
header( 'Cache-control: must-revalidate' );
header( "Content-type: text/css; charset: UTF-8" );*/

global $lisfinity_options;

$css = '';
// theme fonts.
$font = $lisfinity_options['_site-font'];
if ( isset( $font ) && $font !== 'custom' ) {
	$font = str_replace( '+', ' ', $font );
	$css  .= <<<css
	body,
	.font-sans,
	.font-serif {
		font-family: $font;
	}
css;
}

// Header colors.
$header_home_bg = $lisfinity_options['_header-home-bg'];
$header_bg      = $lisfinity_options['_header-bg'];

if ( isset( $header_home_bg ) && lisfinity_check_color( 'transparent', $header_home_bg['rgba'] ) ) {
	$css .= <<<css
		.home #header--main {
		  background-color: {$header_home_bg['rgba']};
		}
css;
}
if ( isset( $header_bg['rgba'] ) && lisfinity_check_color( '1c1c1c', $header_bg['rgba'] ) ) {
	$css .= <<<css
		#header--main:not(.header--home) {
		  background-color: {$header_bg['rgba']};
		}
css;
}

// submit ad button in the header.
$submit_bg         = $lisfinity_options['_button-submit-bg'];
$submit_bg_hover   = $lisfinity_options['_button-submit-bg-hover'];
$submit_text       = $lisfinity_options['_button-submit-text-color'];
$submit_text_hover = $lisfinity_options['_button-submit-text-color-hover'];

if ( isset( $submit_bg['rgba'] ) && 'rgba(68, 68, 68, 0.7)' !== $submit_bg['rgba'] ) {
	$css .= <<<css
		.menu-item-submit a {
		  background-color: {$submit_bg['rgba']};
		}
css;
}
if ( lisfinity_check_color( '0967d2', $submit_bg_hover ) ) {
	$css .= <<<css
		.menu-item-submit a:hover {
		  background-color: $submit_bg_hover;
		}
css;
}
if ( isset( $submit_text ) && lisfinity_check_color( 'ffffff', $submit_text ) ) {
	$css .= <<<css
		.menu-item-submit a svg,
		.menu-item-submit a {
		  fill: $submit_text !important;
		  color: $submit_text;
		}
css;
}
if ( isset( $submit_text_hover ) && lisfinity_check_color( 'ffffff', $submit_text_hover ) ) {
	$css .= <<<css
		.menu-item-submit a:hover svg,
		.menu-item-submit a:hover {
		  fill: $submit_text_hover !important;
		  color: $submit_text_hover !important;
		}
css;
}

// Header menu.
$header_menu_dropdown       = $lisfinity_options['_header-menu-dropdown'];
$header_menu_dropdown_hover = $lisfinity_options['_header-menu-dropdown-hover'];
$menu_text_color            = $lisfinity_options['_header-menu-text-color'];
$menu_text_color_hover      = $lisfinity_options['_header-menu-text-color-hover'];
$submenu_text_color         = $lisfinity_options['_header-menu-dropdown-color'];
$submenu_text_color_hover   = $lisfinity_options['_header-menu-dropdown-color-hover'];
$menu_mobile_bg             = $lisfinity_options['_header-menu-mobile-bg'];
$menu_mobile_close          = $lisfinity_options['_header-menu-mobile-close'];
$header_menu_action         = $lisfinity_options['_header-menu-action'];

if ( lisfinity_check_color( '199473', $header_menu_action ) ) {
	$css .= <<<css
		.menu-item-login a svg {
		  fill: $header_menu_action !important;
		}
		#compare--wrapper .fill-icon-reset svg,
		#notifications--wrapper .fill-icon-reset svg,
		#cart--wrapper svg {
		  fill: $header_menu_action;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $header_menu_dropdown ) ) {
	$css .= <<<css
		.sub-menu .sub-menu-wrapper {
		  background-color: $header_menu_dropdown;
		}
css;
}
if ( lisfinity_check_color( 'efefef', $header_menu_dropdown_hover ) ) {
	$css .= <<<css
		.sub-menu li a:hover {
		  background-color: $header_menu_dropdown_hover;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $menu_text_color ) ) {
	$css .= <<<css
		.flex:not(.sub-menu-wrapper) li.menu-item:not(.menu-item-submit) a {
		  color: $menu_text_color;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $menu_text_color_hover ) ) {
	$css .= <<<css
		.flex:not(.sub-menu-wrapper) li.menu-item:not(.menu-item-submit) a:hover {
		  color: $menu_text_color_hover;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $submenu_text_color ) ) {
	$css .= <<<css
	.sub-menu ul.sub-menu-wrapper li.menu-item:not(.menu-item-submit) a {
	  color: $submenu_text_color;
	}
css;
}
if ( lisfinity_check_color( 'ffffff', $submenu_text_color_hover ) ) {
	$css .= <<<css
	.sub-menu ul.sub-menu-wrapper li.menu-item:not(.menu-item-submit) a:hover {
		color: $submenu_text_color_hover;
	}
	css;
}

if ( lisfinity_check_color( 'ffffff', $menu_mobile_bg ) ) {
	$css .= <<<css
	@media (max-width: 1024px) {
		.menu--lisfinity {
			background-color: $menu_mobile_bg;
		}
	}
css;
}
if ( lisfinity_check_color( '000000', $menu_mobile_close ) && '000' !== $menu_mobile_close ) {
	$css .= <<<css
	.menu--close .fill-black {
	  	fill: $menu_mobile_close;
	}
css;
}

// Mobile Menu.
$mobile_menu_bg               = $lisfinity_options['_mobile-menu-bg'] ?? '#ffffff';
$mobile_menu_close            = $lisfinity_options['_mobile-menu-close'];
$mobile_menu_open             = $lisfinity_options['_mobile-menu-open'];
$mobile_menu_items            = $lisfinity_options['_mobile-menu-items'];
$mobile_menu_social_color     = $lisfinity_options['_mobile-menu-social-color'];
$mobile_menu_dropdown_icon_bg = $lisfinity_options['_mobile-menu-dropdown-icon-bg'];
$mobile_menu_dropdown_icon    = $lisfinity_options['_mobile-menu-dropdown-icon'];
$mobile_menu_submit           = $lisfinity_options['_mobile-menu-submit'];
$mobile_menu_action           = $lisfinity_options['_mobile-menu-action'];
$mobile_menu_border           = $lisfinity_options['_mobile-menu-border'];

if ( lisfinity_check_color( 'ffffff', $mobile_menu_bg ) ) {
	$css .= <<<css
		.menu-mobile.bg-white {
		  background-color: $mobile_menu_bg;
		}
css;
}
if ( lisfinity_check_color( '199473', $mobile_menu_action ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .fill-icon-reset svg {
		  fill: $mobile_menu_action;
		}
css;
}
if ( lisfinity_check_color( 'f6f6f6', $mobile_menu_border ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .border-grey-100 {
		  border-color: $mobile_menu_border;
		}
css;
}
if ( lisfinity_check_color( '262626', $mobile_menu_close ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .menu-mobile--header .fill-grey-1100 svg {
		  fill: $mobile_menu_close;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $mobile_menu_open ) ) {
	$css .= <<<css
		#mobile-menu--wrapper > button .fill-white svg {
		  fill: $mobile_menu_open;
		}
css;
}
if ( lisfinity_check_color( '4c4c4c', $mobile_menu_items ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .text-grey-900 {
		  color: $mobile_menu_items;
		}
css;
}
if ( lisfinity_check_color( '7a8593', $mobile_menu_social_color ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .menu-mobile--socials p {
		  color: $mobile_menu_social_color;
		}
		.menu-mobile.bg-white .menu-mobile--socials a {
		  color: $mobile_menu_social_color;
		  border-color: $mobile_menu_social_color;
		}
		.menu-mobile.bg-white .menu-mobile--socials a svg {
		  fill: $mobile_menu_social_color;
		}
css;
}
if ( lisfinity_check_color( 'efefef', $mobile_menu_dropdown_icon_bg ) ) {
	$css .= <<<css
		.menu-mobile.bg-white ul li button .bg-grey-200 {
		  background-color: $mobile_menu_dropdown_icon_bg;
		}
css;
}
if ( lisfinity_check_color( '262626', $mobile_menu_dropdown_icon ) ) {
	$css .= <<<css
		.menu-mobile.bg-white ul li button .bg-grey-200 svg {
		  fill: $mobile_menu_dropdown_icon;
		}
css;
}
if ( lisfinity_check_color( '0967d2', $mobile_menu_submit ) ) {
	$css .= <<<css
		.menu-mobile.bg-white .text-blue-700 span {
		  color: $mobile_menu_submit;
		}
		.menu-mobile.bg-white .border-blue-700 {
		  border-color: $mobile_menu_submit;
		}
		.menu-mobile.bg-white .border-blue-700 svg {
		  fill: $mobile_menu_submit;
		}
css;
}

// Header Fields.
$header_fields_bg                  = $lisfinity_options['_header-fields-bg'];
$header_fields_text                = $lisfinity_options['_header-fields-text'];
$header_fields_icon                = $lisfinity_options['_header-fields-icon'];
$header_fields_dropdown_bg         = $lisfinity_options['_header-fields-dropdown'];
$header_fields_dropdown_bg_hover   = $lisfinity_options['_header-fields-dropdown-hover'];
$header_fields_dropdown_text       = $lisfinity_options['_header-fields-dropdown-text'];
$header_fields_dropdown_text_hover = $lisfinity_options['_header-fields-dropdown-text-hover'];

if ( lisfinity_check_color( 'ffff', $header_fields_bg ) && 'transparent' !== $header_fields_bg ) {
	$css .= <<<css
		#header-taxonomy .select-transparent {
		  padding: 0 10px;
		  background-color: $header_fields_bg;
		  border-radius: 4px;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $header_fields_text ) ) {
	$css .= <<<css
		header .select-transparent div[class*=css-0] div[class*=placeholder], .home .select-transparent div[class*=css-0] div[class*=placeholder] {
			color: $header_fields_text;
		}
		header .select-transparent div[class*=css-0] div[class*=indicatorContainer] svg, .home .select-transparent div[class*=css-0] div[class*=indicatorContainer] svg {
			fill: $header_fields_text;
		}
css;
}
if ( lisfinity_check_color( 'efefef', $header_fields_icon ) ) {
	$css .= <<<css
		#header-taxonomy .taxonomy-icon {
			fill: $header_fields_icon;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $header_fields_dropdown_bg ) ) {
	$css .= <<<css
		#header-taxonomy div[class*="-menu"] {
			background-color: $header_fields_dropdown_bg;
		}
css;
}
if ( lisfinity_check_color( 'efefef', $header_fields_dropdown_bg_hover ) ) {
	$css .= <<<css
		#header-taxonomy .css-xo7z33-option,
		#header-taxonomy .css-dpec0i-option {
			background-color: $header_fields_dropdown_bg_hover;
		}
css;
}
if ( lisfinity_check_color( '2d2d2d', $header_fields_dropdown_text ) ) {
	$css .= <<<css
		#header-taxonomy .css-fk865s-option {
			color: $header_fields_dropdown_text;
		}
css;
}
if ( lisfinity_check_color( '2d2d2d', $header_fields_dropdown_text_hover ) ) {
	$css .= <<<css
		#header-taxonomy .css-dpec0i-option {
			color: $header_fields_dropdown_text_hover;
		}
css;
}

// Footer settings.
$footer_bg              = $lisfinity_options['_footer-bg'];
$footer_text            = $lisfinity_options['_footer-text-color'];
$footer_text_hover      = $lisfinity_options['_footer-text-color-hover'];
$footer_share           = $lisfinity_options['_footer-share-color'];
$footer_share_hover     = $lisfinity_options['_footer-share-color-hover'];
$footer_copyrights_bg   = $lisfinity_options['_footer-copyrights-bg'];
$footer_copyrights_text = $lisfinity_options['_footer-copyrights-text'];

if ( lisfinity_check_color( '262626', $footer_bg ) ) {
	$css .= <<<css
		footer.footer {
			background-color: $footer_bg;
		}
css;
}
if ( lisfinity_check_color( '959595', $footer_text ) ) {
	$css .= <<<css
		footer.footer,
		footer.footer div,
		footer.footer .widget ul li,
		footer.footer .widget ul li a,
		footer.footer .widget ol li,
		footer.footer .widget ol li a,
		footer.footer .widget span,
		footer.footer .widget h1,
		footer.footer .widget h2,
		footer.footer .widget h3,
		footer.footer .widget h4,
		footer.footer .widget h5,
		footer.footer .widget h6,
		footer.footer .widget p {
			color: $footer_text;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $footer_text_hover ) ) {
	$css .= <<<css
		body footer.footer .widget a:hover {
			color: $footer_text_hover;
		}
css;
}
if ( lisfinity_check_color( '5e5e5e', $footer_share ) ) {
	$css .= <<<css
		body footer.footer .widget.carbon_fields_social_widget ul li a {
			color: $footer_share;
			border-color: $footer_share;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $footer_share_hover ) ) {
	$css .= <<<css
		body footer.footer .widget.carbon_fields_social_widget ul li a:hover {
			color: $footer_share_hover;
			border-color: $footer_share_hover;
		}
css;
}
if ( lisfinity_check_color( '262626', $footer_copyrights_bg ) ) {
	$css .= <<<css
		footer.copyrights {
			background-color: $footer_copyrights_bg;
		}
css;
}
if ( lisfinity_check_color( '959595', $footer_copyrights_text ) ) {
	$css .= <<<css
		footer.copyrights {
			color: $footer_copyrights_text;
		}
css;
}

// Hero settings.
$home_fields_bg                  = $lisfinity_options['_home-fields-bg'];
$home_fields_text                = $lisfinity_options['_home-fields-text'];
$home_fields_dropdown            = $lisfinity_options['_home-fields-dropdown'];
$home_fields_dropdown_hover      = $lisfinity_options['_home-fields-dropdown-hover'];
$home_fields_dropdown_text       = $lisfinity_options['_home-fields-dropdown-text'];
$home_fields_dropdown_text_hover = $lisfinity_options['_home-fields-dropdown-text-hover'];

if ( lisfinity_check_color( 'ffffff', $home_fields_bg ) ) {
	$css .= <<<css
		#home-search .bg-white,
		#home-search .bg-white input {
			background-color: $home_fields_bg;
		}
css;
}
if ( lisfinity_check_color( '2d2d2d', $home_fields_text ) ) {
	$css .= <<<css
		header .select-banner div[class*=css-0] div[class*=placeholder], .home .select-banner div[class*=css-0] div[class*=placeholder],
		#home-search .bg-white input,
		#home-search .bg-white label {
			color: $home_fields_text;
		}
		header .select-banner div[class*=css-0] div[class*=indicatorContainer] svg, .home .select-banner div[class*=css-0] div[class*=indicatorContainer] svg,
		#home-search .fill-field-icon {
			fill: $home_fields_text;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $home_fields_dropdown ) ) {
	$css .= <<<css
		header .select-banner div[class*=menu], .home .select-banner div[class*=menu],
		#home-search .results {
			background-color: $home_fields_dropdown;
		}
css;
}
if ( lisfinity_check_color( 'efefef', $home_fields_dropdown_hover ) ) {
	$css .= <<<css
		#home-search .css-xo7z33-option,
		#home-search .css-dpec0i-option,
		#home-search .results .result a:hover {
			background-color: $home_fields_dropdown_hover;
		}
css;
}
if ( lisfinity_check_color( '2d2d2d', $home_fields_dropdown_text ) ) {
	$css .= <<<css
		#home-search .css-fk865s-option,
		#home-search .results .result a {
			color: $home_fields_dropdown_text;
		}
css;
}
if ( lisfinity_check_color( '2d2d2d', $home_fields_dropdown_text_hover ) ) {
	$css .= <<<css
		#home-search .css-dpec0i-option,
		#home-search .results .result a:hover {
			color: $home_fields_dropdown_text_hover;
		}
css;
}

$primary_button_bg         = $lisfinity_options['_primary-button-background'];
$primary_button_bg_hover   = $lisfinity_options['_primary-button-background-hover'];
$primary_button_text       = $lisfinity_options['_primary-button-color'];
$primary_button_text_hover = $lisfinity_options['_primary-button-color-hover'];

$secondary_button_bg         = $lisfinity_options['_secondary-button-background'];
$secondary_button_bg_hover   = $lisfinity_options['_secondary-button-background-hover'];
$secondary_button_text       = $lisfinity_options['_secondary-button-color'];
$secondary_button_text_hover = $lisfinity_options['_secondary-button-color-hover'];

// primary colors.
if ( lisfinity_check_color( '0967d2', $primary_button_bg ) ) {
	$css .= <<<css
		a.bg-blue-600,
		a.bg-blue-700,
		button.bg-blue-600,
		button.bg-blue-700 {
			background-color: $primary_button_bg;
			border-color: $primary_button_bg;
		}
css;
}
if ( lisfinity_check_color( '0967d2', $primary_button_bg_hover ) ) {
	$css .= <<<css
		a.bg-blue-600:hover,
		a.bg-blue-700:hover,
		button.bg-blue-600:hover,
		button.bg-blue-700:hover {
			background-color: $primary_button_bg_hover;
			border-color: $primary_button_bg_hover;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $primary_button_text ) ) {
	$css .= <<<css
		a.bg-blue-600,
		a.bg-blue-700,
		button.bg-blue-600,
		button.bg-blue-700 {
			color: $primary_button_text;
		}
		a.bg-blue-600 svg,
		a.bg-blue-700 svg,
		button.bg-blue-600 svg,
		button.bg-blue-700 svg {
			fill: $primary_button_text;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $primary_button_text_hover ) ) {
	$css .= <<<css
		a.bg-blue-600:hover,
		a.bg-blue-700:hover,
		button.bg-blue-600:hover,
		button.bg-blue-700:hover {
			color: $primary_button_text_hover;
		}
		a.bg-blue-600:hover svg,
		a.bg-blue-700:hover svg,
		button.bg-blue-600:hover svg,
		button.bg-blue-700:hover svg {
			fill: $primary_button_text_hover;
		}
css;
}

// secondary colors.
if ( lisfinity_check_color( '3ebd93', $secondary_button_bg ) ) {
	$css .= <<<css
		a.bg-green-600,
		a.bg-green-700,
		button.bg-green-600,
		button.bg-green-700 {
			background-color: $secondary_button_bg;
			border-color: $secondary_button_bg;
		}
css;
}
if ( lisfinity_check_color( '199473', $secondary_button_bg_hover ) ) {
	$css .= <<<css
		a.bg-green-600:hover,
		a.bg-green-700:hover,
		button.bg-green-600:hover,
		button.bg-green-700:hover {
			background-color: $secondary_button_bg_hover;
			border-color: $secondary_button_bg_hover;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $secondary_button_text ) ) {
	$css .= <<<css
		a.bg-green-600,
		a.bg-green-700,
		button.bg-green-600,
		button.bg-green-700 {
			color: $secondary_button_text;
		}
		a.bg-green-600 svg,
		a.bg-green-700 svg,
		button.bg-green-600 svg,
		button.bg-green-700 svg {
			fill: $secondary_button_text;
		}
css;
}
if ( lisfinity_check_color( 'ffffff', $secondary_button_text_hover ) ) {
	$css .= <<<css
		a.bg-green-600:hover,
		a.bg-green-700:hover,
		button.bg-green-600:hover,
		button.bg-green-700:hover {
			color: $secondary_button_text_hover;
		}
		a.bg-green-600:hover svg,
		a.bg-green-700:hover svg,
		button.bg-green-600:hover svg,
		button.bg-green-700:hover svg {
			fill: $secondary_button_text_hover;
		}
css;
}

$banner_featured_text_color = $lisfinity_options['_home-banner-taxonomies-color'];
if ( lisfinity_check_color( 'bcbcbc', $banner_featured_text_color ) ) {
	$css .= <<<css
		.banner--taxonomies h5 {
			color: $banner_featured_text_color;
		}
css;
}

$icon_size        = $lisfinity_options['_home-banner-taxonomies-icon-size'] ?? 48;
$icon_size_mobile = $lisfinity_options['_home-banner-taxonomies-icon-size-mobile'] ?? 48;

$css .= <<<css
	.banner--taxonomy__icon img {
		width: {$icon_size}px;
		height: {$icon_size}px;
	}
	@media(max-width: 1024px) {
		.banner--taxonomy__icon img {
			width: {$icon_size_mobile}px;
			height: {$icon_size_mobile}px;
		}
	}
css;

echo $css;
