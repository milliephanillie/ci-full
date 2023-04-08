<?php
$js = lisfinity_get_option( 'code-js' );
if ( empty( $js ) ) {
	return;
}

echo '<script type="text/javascript">' . $js . '</script>';
