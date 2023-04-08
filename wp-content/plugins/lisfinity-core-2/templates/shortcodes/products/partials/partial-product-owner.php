<?php
/**
 * Template Name: Shortcodes | Product Partials | Product Owner
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Product | Owner -->
<?php $author = get_post_field( 'post_author', get_the_ID() ); ?>

<?php $profile_id = lisfinity_get_premium_profile_id( $author ); ?>
<?php $location_show = 'always';

if ( lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ) {
	$location_show = lisfinity_get_option( 'membership-address' );
}
?>
<?php $is_logged_in = is_user_logged_in(); ?>
<?php
$owner        = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$account_type = carbon_get_user_meta( $owner, 'account-type' );
?>

<!-- Product | Owner Information -->
<div class="lisfinity-product--info-wrapper flex items-center mr-2">

	<?php $location_format = lisfinity_get_option( 'format-location' ); ?>
	<?php
	$location           = carbon_get_post_meta( $profile_id, 'profile-location' );
	$location_formatted = 'business' === $account_type ? lisfinity_format_location( $profile_id, 'full' === $location_format, false, explode( ',', $location['address'] ) ) : carbon_get_post_meta( $product_id, 'product-location' )['address'];
	$location_formatted = lisfinity_format_location( $profile_id, 'full' === $location_format, false, explode( ',', $location['address'] ) );
	?>
	<?php if ( ( 'custom' !== $args['settings']['style'] && 'no' !== carbon_get_theme_option( 'business-reviews-enable' ) && 'business' === $account_type ) || ( 'custom' === $args['settings']['style'] ) && 'yes' === $args['settings']['hide_show_product_info_mark'] && 'business' === $account_type ) : ?>
		<div
			class="lisfinity-product--info flex-center <?php echo ! empty( $location_format ) ? esc_attr( 'mr-10' ) : ''; ?>">
			<?php if ( ( 'custom' !== $args['settings']['style'] ) || ( 'custom' === $args['settings']['style'] ) && isset( $args['settings']['hide_show_product_info_mark_icon'] ) && 'yes' === $args['settings']['hide_show_product_info_mark_icon'] ) : ?>
				<span class="flex-center w-32 h-32 rounded-full bg-yellow-300">
                                            <?php $icon_args = [
												'width'  => 'w-14',
												'height' => 'h-14',
												'fill'   => 'fill-product-star-icon',
											]; ?>
											<?php include lisfinity_get_template_part( 'star', 'partials/icons', $icon_args ); ?>
                                        </span>
			<?php endif; ?>
			<span
				class="ml-6 text-sm text-grey-600"><?php echo esc_html( number_format_i18n( lisfinity_calculate_business_rating( $profile_id ), 1 ) ); ?></span>
		</div>
	<?php endif; ?>

	<?php if (  $location_show === 'always' || ( $is_logged_in && $location_show === 'logged_in' ) ): ?>

		<?php if ( ( 'custom' !== $args['settings']['style'] ) || ( 'custom' === $args['settings']['style'] ) && 'yes' === $args['settings']['hide_show_product_info_place'] ) : ?>
			<div class="lisfinity-product--info flex-center">
				<?php $location = carbon_get_post_meta( $product_id, 'product-location' ); ?>
				<?php if ( ( 'custom' !== $args['settings']['style'] ) || ( 'custom' === $args['settings']['style'] ) && isset( $args['settings']['hide_show_product_info_place_icon'] ) && 'yes' === $args['settings']['hide_show_product_info_place_icon'] ) : ?>
					<span class="flex-center w-32 h-32 min-w-32 rounded-full bg-cyan-300">
                                            <?php $icon_args = [
												'width'  => 'w-14',
												'height' => 'h-14',
												'fill'   => 'fill-product-place-icon',
											]; ?>
											<?php include lisfinity_get_template_part( 'map-marker', 'partials/icons', $icon_args ); ?>
                                        </span>
				<?php endif; ?>
				<span
					class="ml-6 text-sm text-grey-600">

			<?php if (! empty( lisfinity_format_location( $product_id, 'full' === $location_format ) ) ) : ?>
				<?php echo $args['settings']['location_type'] === 'listing_location' ? esc_html( lisfinity_format_location( $product_id, 'full' === $location_format ) ) : $location_formatted; ?>
			<?php else : ?>
				<?php echo $args['settings']['location_type'] === 'listing_location' ? esc_html( carbon_get_post_meta( $product_id, 'product-location' )['address'] ) : $location_formatted; ?>
			<?php endif; ?>
			</span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

</div>

<!-- Product | Owner Image -->
<?php if ( has_post_thumbnail( $profile_id ) && 'business' === $account_type ) : ?>
	<?php if ( ( 'custom' !== $args['settings']['style'] ) || ( 'custom' === $args['settings']['style'] ) && 'yes' === $args['settings']['hide_show_product_owner_logo'] ) : ?>

		<div class="lisfinity-product--author flex-center">
			<?php $profile_image = get_the_post_thumbnail_url( $profile_id, 'premium-profile-image' ); ?>
			<?php $business_permalink = get_permalink( $profile_id ); ?>
			<?php if ( 'yes' === lisfinity_get_option( 'product-box-logo-clickable' ) ) : ?>
				<a href="<?php echo esc_url( $business_permalink ); ?>">
					<img src="<?php echo esc_url( $profile_image ); ?>"
						 alt="<?php esc_attr_e( 'Premium Profile Image', 'lisfinity-core' ); ?>"
						 class="h-32">
				</a>
			<?php else: ?>
				<img src="<?php echo esc_url( $profile_image ); ?>"
					 alt="<?php esc_attr_e( 'Premium Profile Image', 'lisfinity-core' ); ?>"
					 class="h-32">
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
