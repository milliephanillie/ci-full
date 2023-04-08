<?php
/**
 * Template Name: Shortcodes | Partners
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
$widths   = [ 100, 120, 160 ];
?>

<div class="flex flex-wrap -mb-4 -mx-10">
	<?php if ( ! empty( $settings ) && ! empty( $settings['promo_image'] ) ) : ?>
		<?php $promo_height = ! empty( $settings['promo_image_height']) ? $settings['promo_image_height'] . 'px' : '100%'; ?>
		<div class="relative pr-60 w-full sm:w-1/2 overflow-hidden">
		<?php if ( ! empty( $settings['promo_link'] ) ) : ?>
				<a href="<?php echo esc_url( $settings['promo_link']['url'] ); ?>"
				   class="absolute top-0 left-0 w-full h-full z-1"
					<?php echo 'on' === $settings['promo_link']['is_external'] ? esc_attr( 'target="_blank"' ) : ''; ?>
					<?php echo 'on' === $settings['promo_link']['nofollow'] ? esc_attr( 'rel="nofollow"' ) : ''; ?>
				></a>
			<?php endif; ?>
			<div class="relative flex-center <?php echo ! empty( $promo_height ) ? 'items-center' : ''; ?>"
				 style="height: <?php echo esc_attr( "{$promo_height}" ); ?>">
				<img src="<?php echo esc_url( $settings['promo_image']['url'] ) ?>"
					 alt="<?php echo esc_attr__( 'Promo Image', 'lisfinity-core' ); ?>"
					 class="absolute top-0 left-0 w-full h-full object-cover">
			</div>
		</div>
	<?php endif; ?>
	<div
		class="mt-20 sm:mt-0 w-full sm:w-1/2">
		<?php if ( 'yes' === $settings['title_enable']) : ?>
                <div class="title--heading relative flex flex-col mb-40 px-60">
					<?php if ( ! empty( $settings['subheading'] ) ) : ?>
                        <p class="mb-6 z-2"><?php echo esc_html( $settings['subheading'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $settings['heading'] ) ) : ?>
						<?php $shadow_text = ! empty( $settings['shadow_different'] ) && 'yes' === $settings['shadow_different'] ? $settings['shadow_text'] : $settings['heading']; ?>
                        <div class="title--shadow relative">
                            <span class="title--shadow__big font-bold text-xs-shadow whitespace-no-wrap sm:text-shadow"
                                  style="color: <?php echo $settings['shadow_color'] ? esc_attr( $settings['shadow_color'] ) : '#f6f6f6'; ?>"><?php echo esc_html( $shadow_text ); ?></span>
                            <h3 class="text-4xl font-bold text-grey-1000 sm:text-6xl"><?php echo esc_html( $settings['heading'] ); ?></h3>
                        </div>
					<?php endif; ?>
                </div>
			<?php endif; ?>
	<div
		class="partners partners__masonry <?php echo is_admin() ? 'partners__is-admin' : ''; ?>">
		<?php if ( ! empty( $partners ) ) : ?>
		<?php $partners_count = count( $partners ); ?>
		<?php foreach ( $partners as $partner ) : ?>
			<?php $width = 'yes' === $settings['partners_masonry'] ? $widths[ array_rand( $widths ) ] : ($settings['partners_width'] ?? 130); ?>
			<?php $padding = 60 === $width ? 'p-10' : 'p-20'; ?>
			<div
				class="partner partners-item relative flex-center mb-4 mx-2 bg-white rounded shadow-md overflow-hidden <?php echo esc_attr( $padding ); ?>"
				style='width: <?php echo esc_attr( "{$width}px;" ); ?> height: <?php echo esc_attr( "{$width}px;" ); ?>'>
				<a href="<?php echo esc_url( $partner['partner_url']['url'] ); ?>"
				   class="absolute top-0 left-0 w-full h-full z-1"
					<?php echo 'on' === $partner['partner_url']['is_external'] ? esc_attr( 'target="_blank"' ) : ''; ?>
					<?php echo 'on' === $partner['partner_url']['nofollow'] ? esc_attr( 'rel="nofollow"' ) : ''; ?>
				></a>
				<img src="<?php echo esc_url( $partner['partner_image']['url'] ); ?>"
				     alt="<?php echo esc_attr( $partner['partner_name'] ); ?>"
				>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>
