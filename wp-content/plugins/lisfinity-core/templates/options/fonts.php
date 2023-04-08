<?php
$font = lisfinity_get_option( 'site-font' );
if ( isset( $font ) && $font === 'custom' ) {
	return;
}
$font = str_replace( '+', ' ', $font );
$css = '';
$css .= <<<css
	body,
	.font-sans,
	.font-serif {
		font-family: $font;
	}
css;

echo $css;
