<?php
/**
 * Template Name: Shortcodes | Product Partials | Product Timeline
 * Description: The file that is being used to display products and various product types
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$id     = get_the_ID();
$listed = carbon_get_post_meta( $id, 'product-listed' );
// use default post submission date if the post meta list date is not available.
$submitted       = ! empty( $listed ) ? $listed : strtotime( get_the_date( '', $id ) );
$expires         = carbon_get_post_meta( $id, 'product-expiration' );
$duration        = $expires - $submitted;
$remaining       = $expires - current_time( 'timestamp' );
$submitted_human = human_time_diff( $submitted, current_time( 'timestamp' ) );
$expires_human   = human_time_diff( $expires, current_time( 'timestamp' ) );
$expires_date    = date( 'M d', $expires );
$percentage      = $duration !== 0 ? floor( 100 - ( $remaining * 100 ) / $duration ) : 0;
$days_remaining  = round( $remaining / DAY_IN_SECONDS );
?>
<div class="product--timeline mt-10 order-5">
	<div class="flex flex-wrap mt-10 text-sm text-grey-500 whitespace-nowrap">
		<?php esc_html_e( 'Submitted', 'lisfinity-core' ); ?>
		<span
			class="ml-3 font-semibold text-grey-1100">
	<?php printf( esc_html__( '%s ago', 'lisfinity-core' ), $submitted_human ); ?>
	</span>
		<span class="mx-3"><?php echo esc_html( '-' ); ?></span>
		<?php if ( $remaining > 0 ) : ?>
			<Fragment>
				<?php esc_html_e( 'Expires in', 'lisfinity-core' ); ?>
				<span class="ml-3 font-semibold text-grey-1100"><?php echo esc_html( $expires_human ); ?></span>
			</Fragment>
		<?php else: ?>
			<Fragment>
				<?php esc_html_e( 'Expired on', 'lisfinity-core' ); ?>
				<span class="ml-3 font-semibold text-grey-1100"><?php echo esc_html( $expires_date ); ?></span>
			</Fragment>
		<?php endif; ?>
	</div>

	<div class="timeline relative mt-8 w-full h-3 bg-grey-100 rounded overflow-hidden">
		<?php $timeline_class = 'bg-green-500';
		if ( $percentage >= 40 && $percentage < 80 ) {
			$timeline_class = 'bg-yellow-500';
		}
		if ( $percentage >= 80 ) {
			$timeline_class = 'bg-red-500';
		}
		?>
		<div
			class="timeline--line absolute top-0 left-0 h-3 <?php echo esc_attr( $timeline_class ); ?>"
			style="width: <?php echo esc_attr( $percentage ); ?>%"
		></div>
	</div>
</div>
