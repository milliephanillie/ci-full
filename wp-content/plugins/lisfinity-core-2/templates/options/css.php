<?php
$css = lisfinity_get_option( 'code-css' );
if ( empty( $css ) ) {
	return;
}

echo $css;
