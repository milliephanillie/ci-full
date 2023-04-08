<?php

if ( ! function_exists( 'lisfinity_slugs_help_html' ) ) {
	function lisfinity_slugs_help_html() {
		ob_start();
		?>
		<p style="margin-top: -4px; font-size: 12px;"><?php esc_html_e( 'In this page you can change the permalinks and slugs for the various pages created by the Lisfinity theme.', 'lisfinity-core' ); ?></p>
		<p style="margin-top: -4px; font-size: 12px;"><?php _e( 'If the permalinks are not applied on the site or you are getting a 404 error go to <a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '" target="_blank" style="color: #00a0d2; text-decoration: underline;"><strong>Permalinks Page</strong></a> and click <strong>Save</strong>.', 'lisfinity-core' ); ?></p>
		<p style="margin-top: -4px; font-size: 12px;"><?php _e( 'Default WordPress and WooCommerce Permalinks can be changed from: <a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '" target="_blank"  style="color: #00a0d2; text-decoration: underline;"><strong>Permalinks Page</strong></a>.', 'lisfinity-core' ); ?></p>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'lisfinity_verification_help_html' ) ) {
	function lisfinity_verification_help_html() {
		ob_start();
		?>
		<p style="margin-bottom: -10px; font-size: 13px;"><?php esc_html_e( 'There are two possible options for user verification:', 'lisfinity-core' ); ?></p>
		<p style="margin-bottom: -10px; font-size: 13px;">
			<strong><?php esc_html_e( 'Default (Email Verification):', 'lisfinity-core' ); ?></strong>
			<?php esc_html_e( 'This is default way to verify users when user verification is enabled. After registration an email with verification link will be sent to a user in order to verify account.', 'lisfinity-core' ); ?>
		<p><strong><?php esc_html_e( 'SMS Verification:', 'lisfinity-core' ); ?></strong>
			<?php esc_html_e( 'For this type of verification Twillio account has to be created. During registration a user will be prompted to enter a unique SMS code that was sent to the phone number he provided.', 'lisfinity-core' ); ?>
			<?php _e( '<a href="https://www.twilio.com/try-twilio" target="_blank">Twillio Registration</a>', 'lisfinity-core' ); ?>
		</p>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'lisfinity_reviews_help_html' ) ) {
	function lisfinity_reviews_help_html() {
		ob_start();
		?>
		<h2 style="margin-left: 0; margin-bottom: -10px; padding-left: 0; font-size: 20px;"><?php echo esc_html__( 'Create testimonial reviews criteria', 'lisfinity-core' ); ?></h2>
		<p style="margin-bottom: -10px;">
		<p>
			<?php
			_e( 'Click on the <strong>Testimonials Builder</strong> tab to create testimonials reviews criteria so the users can leave ratings along with testimonial message.', 'lisfinity-core' );
			?>
		</p>
		<p style="margin-top: -10px;">
			<?php
			_e( 'If it is empty users will not be able to leave testimonials as at least one review criteria is required.', 'lisfinity-core' );
			?>
		</p>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'lisfinity_currency_help_html' ) ) {
	function lisfinity_currency_help_html() {
		ob_start();
		?>
		<h4 style="margin: 0 0 10px; line-height: 1.4; font-size: 16px;"><?php _e( 'Information about default currency', 'lisfinity-core' ); ?></h4>
		<p style="margin-top: -4px; font-size: 12px;"><?php echo sprintf( __( 'Default site currency is: <strong>%s</strong>', 'lisfinity-core' ), get_option( 'woocommerce_currency' ) ); ?></p>
		<p style="margin-top: -4px; margin-bottom: 0; font-size: 12px;"><?php _e( 'You can change default currency from <a href="' . esc_url( admin_url( 'admin.php?page=wc-settings' ) ) . '" target="_blank" style="color: #00a0d2; text-decoration: underline;"><strong>WooCommerce Settings</strong></a>.', 'lisfinity-core' ); ?></p>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'lisfinity_builder_help_html' ) ) {
	function lisfinity_builder_help_html() {
		ob_start();
		?>
		<h4 style="margin: 0 0 10px; line-height: 1.4; font-size: 16px;"><?php _e( 'Lisfinity Builders', 'lisfinity-core' ); ?></h4>
		<p style="margin-top: 0; margin-bottom: 0; font-size: 12px;"><?php _e( 'You can manage your own currencies by clicking on <a href="' . esc_url( admin_url( 'admin.php?page=builder-options' ) ) . '" style="color: #00a0d2; text-decoration: underline;"><strong>Multicurrency Builder</strong></a>.', 'lisfinity-core' ); ?></p>
		<p style="margin-top: 0; margin-bottom: 0; font-size: 12px;"><?php _e( 'You can manage your own testimonials criteria by clicking on <a href="' . esc_url( admin_url( 'admin.php?page=crb_carbon_fields_container_testimonials_builder.php' ) ) . '" style="color: #00a0d2; text-decoration: underline;"><strong>Testimonials Builder</strong></a>.', 'lisfinity-core' ); ?></p>
		<p style="margin-top: 0; margin-bottom: 0; font-size: 12px;"><?php _e( 'You can manage your own reports builder by clicking on <a href="' . esc_url( admin_url( 'admin.php?page=crb_carbon_fields_container_flagreports_builder.php' ) ) . '" style="color: #00a0d2; text-decoration: underline;"><strong>Flag/Reports Builder</strong></a>.', 'lisfinity-core' ); ?></p>
		<?php
		return ob_get_clean();
	}
}
