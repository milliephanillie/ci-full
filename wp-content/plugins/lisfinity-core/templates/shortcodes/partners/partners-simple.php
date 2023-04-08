<?php
/**
 * Template Name: Shortcodes | Partners Simple
 * Description: The file that is being used to display site partners
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$settings = $args['settings'];
$partners = $settings['partners'];
if ( $settings['partners_number'] < count( $settings['partners'] ) ) {
	$partners = array_slice( $settings['partners'], 0, $settings['partners_number'] );
}
if ( 'yes' === $settings['partners_randomize'] ) {
	shuffle( $partners );
}
?>

<?php if ( ! empty( $partners ) ) : ?>
	<div class="flex">
		<div class="partners <?php echo is_admin() ? 'partners__is-admin' : ''; ?> flex flex-wrap -mb-8 -mx-8">
			<?php $partners_count = count( $partners ); ?>
			<?php foreach ( $partners as $partner ) : ?>
				<div class="my-8 px-8 w-full sm:w-1/2 md:w-1/3 bg:w-1/4">
					<div
						class="partner partners-item relative flex-center p-40 bg-white rounded shadow-partners overflow-hidden hover:shadow-none"
						style="height: 150px"
					>
						<a href="<?php echo esc_url( $partner['partner_url']['url'] ); ?>"
						   class="absolute top-0 left-0 w-full h-full z-1"
							<?php echo 'on' === $partner['partner_url']['is_external'] ? esc_attr( 'target="_blank"' ) : ''; ?>
							<?php echo 'on' === $partner['partner_url']['nofollow'] ? esc_attr( 'rel="nofollow"' ) : ''; ?>
						></a>
						<img src="<?php echo esc_url( $partner['partner_image']['url'] ); ?>"
							 alt="<?php echo esc_attr( $partner['partner_name'] ); ?>"
							 class="max-h-full"
						>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
