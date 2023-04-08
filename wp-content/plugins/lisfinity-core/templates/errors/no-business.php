<?php

/**
 * Template Name: No Business Content
 * Description: Page template that is being used as the error page content when there is no business connected to a user.
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */
?>
<div>
	<h1><?php esc_html_e( 'No Business Profile Set', 'lisfinity-core' ); ?></h1>
	<p>Business Profile has not been set for the user. If you are an admin please create a Business Profile and set the
		current user as an author, otherwise please contact the site administrator to report the issue.</p>
	<p><a class="button button-large"
	      href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Back to homepage', 'lisfinity-core' ); ?></a></p>

</div>
