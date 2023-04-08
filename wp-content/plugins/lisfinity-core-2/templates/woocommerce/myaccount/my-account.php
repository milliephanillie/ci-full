<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
?>
<?php $has_business = lisfinity_user_has_business(); ?>
<?php $stripe_connect_options = WC()->payment_gateways->payment_gateways()['stripe_connect']; ?>
<?php
$user_id     = get_current_user_id();
$business_id = lisfinity_get_premium_profile_id( $user_id );


$account_type = lisfinity_get_account_type( get_current_user_id() );
$options      = [
	'account_type'                 => $account_type,
	'is_agents_enabled'            => lisfinity_is_enabled( lisfinity_get_option( 'enable-agents' ) ),
	'is_user_verification_enabled' => lisfinity_is_enabled( lisfinity_get_option( 'enable-user-verification' ) ),
	'is_business_account'          => lisfinity_is_business_account( get_current_user_id() ),
	'vendor_approved'              => lisfinity_is_vendor_approved() && 'buyer' != $account_type,
	'packages'                     => lisfinity_packages_enabled( get_current_user_id() ),
	'promotions'                   => lisfinity_is_enabled( lisfinity_get_option( 'site-promotions' ) ),
	'empty_category'               => lisfinity_is_enabled( lisfinity_get_option( 'empty-category' ) ),
	'premium_profiles'             => lisfinity_is_enabled( lisfinity_get_option( 'site-premium-profiles' ) ),
	'disable_bidding'              => lisfinity_is_enabled( lisfinity_get_option( 'site-disable-bidding' ) ),
	'site_hide_bidding'            => lisfinity_is_enabled( lisfinity_get_option( 'site-hide-bidding' ) ),
	'messenger'                    => lisfinity_is_enabled( lisfinity_get_option( 'messenger' ) ),
	'messenger_limit'              => lisfinity_get_option( 'messenger-limit' ),
	'messenger_note'               => lisfinity_get_option( 'messenger-note' ),
	'messenger_note_translation'   => lisfinity_get_option( 'messenger-note-translation' ),
	'logo_size'                    => (int) lisfinity_get_option( 'identity-logo-size' ),
	// dashboard pages.
	'page_billing'                 => lisfinity_is_enabled( lisfinity_get_option( 'dashboard-account-billing' ) ),
	'page_shipping'                => lisfinity_is_enabled( lisfinity_get_option( 'dashboard-account-shipping' ) ),
	'page_download'                => lisfinity_is_enabled( lisfinity_get_option( 'dashboard-download-page' ) ),
	'page_bookmarks'               => lisfinity_is_enabled( lisfinity_get_option( 'dashboard-bookmarks-page' ) ),
	'page_orders'                  => lisfinity_is_enabled( lisfinity_get_option( 'dashboard-orders-page' ) ),
	'vendors_enabled'              => lisfinity_is_enabled( lisfinity_get_option( 'vendors-enabled' ) ),
	'save_searches'                => lisfinity_is_enabled( lisfinity_get_option( 'site-new-products-notifications' ) ),
	'stripe_connect_enabled'       => lisfinity_is_stripe_connect_enabled(),
	'stripe_connect_ca'            => $stripe_connect_options->client_id,
	'stripe_connect_redirect_uri'  => $stripe_connect_options->redirect_uri,
	// shipping and billing pages
	'checkout_first_name'          => lisfinity_is_enabled( lisfinity_get_option( 'checkout-first-name' ) ),
	'checkout_last_name'           => lisfinity_is_enabled( lisfinity_get_option( 'checkout-last-name' ) ),
	'checkout_company_name'        => lisfinity_is_enabled( lisfinity_get_option( 'checkout-company-name' ) ),
	'checkout_country'             => lisfinity_is_enabled( lisfinity_get_option( 'checkout-country' ) ),
	'checkout_street_address'      => lisfinity_is_enabled( lisfinity_get_option( 'checkout-street-address' ) ),
	'checkout_street_address_two'  => lisfinity_is_enabled( lisfinity_get_option( 'checkout-street-address-two' ) ),
	'checkout_apartment'           => lisfinity_is_enabled( lisfinity_get_option( 'checkout-apartment' ) ),
	'checkout_town'                => lisfinity_is_enabled( lisfinity_get_option( 'checkout-town' ) ),
	'checkout_state'               => lisfinity_is_enabled( lisfinity_get_option( 'checkout-state' ) ),
	'checkout_zip'                 => lisfinity_is_enabled( lisfinity_get_option( 'checkout-zip' ) ),
	'checkout_phone'               => lisfinity_is_enabled( lisfinity_get_option( 'checkout-phone' ) ),
	'vat_number'                   => lisfinity_is_enabled( lisfinity_get_option( 'checkout-vat' ) ),
	'sdi_code'                     => lisfinity_is_enabled( lisfinity_get_option( 'checkout-sdi-code' ) ),
	'checkout_email_address'       => lisfinity_is_enabled( lisfinity_get_option( 'checkout-email-address' ) ),
	'user_phone'                   => carbon_get_post_meta( $business_id, 'profile-phones' )[0]['profile-phone'] ?? '',
	'user_website'                 => carbon_get_post_meta( $business_id, 'profile-website' ) ?? '',
	'user_email'                   => carbon_get_post_meta( $business_id, 'profile-email' ) ?? '',
	// form submission.
	'common_first'                 => lisfinity_is_enabled( lisfinity_get_option( 'product-common-first' ) ),
	// business form.
	'phone_apps'                   => lisfinity_get_option( 'business-phone-apps' ),
	// widgets.
	'widget_expiring_listings'     => lisfinity_is_enabled( lisfinity_get_option( 'widget-expiring-listings' ) ),
	'widget_expiring_promotions'   => lisfinity_is_enabled( lisfinity_get_option( 'widget-expiring-promotions' ) ),
	'sorting_pricing_packages'     => lisfinity_get_option( 'sorting-pricing-packages' ),
	'product_mark_as_sold'         => lisfinity_is_enabled( lisfinity_get_option( 'product-mark-as-sold' ) ),
	'is_collapsable'               => lisfinity_is_enabled( lisfinity_get_option( 'collapsable-multiple-choice-lists' ) ) ?? false,
	'fallback_image'               => lisfinity_get_option( 'listing-fallback-image' )['thumbnail'] ?? '',
	'phone-number-register-form'   => lisfinity_is_enabled( lisfinity_get_option( 'phone-number-register-form' ) ) ? lisfinity_get_option( 'phone-number-register-form' ) : false,
	'website-register-form'        => lisfinity_is_enabled( lisfinity_get_option( 'website-register-form' ) ) ? lisfinity_get_option( 'website-register-form' ) : false,
	'first-name-register-form'     => lisfinity_is_enabled( lisfinity_get_option( 'first-name-register-form' ) ) ? lisfinity_get_option( 'first-name-register-form' ) : false,
	'last-name-register-form'      => lisfinity_is_enabled( lisfinity_get_option( 'last-name-register-form' ) ) ? lisfinity_get_option( 'last-name-register-form' ) : false,
	'enable-reserve-price'         => lisfinity_is_enabled( lisfinity_get_option( 'product-enable-reserve-price' ) ),
]; ?>

<div id="page-dashboard" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	<?php
	/**
	 * My Account content.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_content' );
	?>
</div>
<?php if ( $has_business ) : ?>
	<div id="loader"
		 class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
		 style="z-index: 9999;">

		<div class="flex-center flex-col">
			<?php $icon_args = [
				'zoom' => 1,
			]; ?>
			<img src="<?php echo esc_url( LISFINITY_CORE_URL . 'dist/images/loader-rings.4bcf82c529.svg' ); ?>"
				 alt="<?php echo esc_html__( 'Dashboard Loader', 'lisfinity-core' ) ?>"/>
			<p class="mt-20 text-lg text-grey-900"><?php _e( 'Preparing dashboard...', 'lisfinity-core' ); ?></p>
		</div>

	</div>
<?php endif; ?>
