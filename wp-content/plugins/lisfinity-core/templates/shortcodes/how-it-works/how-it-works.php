<?php
/**
 * Template Name: Shortcodes | How it Works
 * Description: The file that is being used to display site how it works section
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
$items    = $settings['items'];
?>

<?php if ( ! empty( $items ) ) : ?>
	<div class="how-it-works container px-0">
		<div class="hiw--row flex flex-wrap">
			<?php $count = 1; ?>
			<?php foreach ( $items as $item ) : ?>
				<div
					class="hiw--wrapper relative w-full mb-20 sm:w-1/2 lg:w-1/4 <?php echo 'cascade' === $settings['items_style'] ? esc_attr( 'how-it-works__cascade' ) : ''; ?>">
					<?php if ( 'yes' === $item['count'] ) : ?>
						<div class="hiw-item--count top-20 left-30 font-semibold absolute"
							 style="color: <?php echo esc_attr( $item['count_color'] ); ?>; font-size: 44px;"
						><?php echo esc_html( $count ) ?></div>
					<?php endif; ?>
					<div class="hiw-item p-20 pt-40 rounded shadow-theme"
					         style="background-color: <?php echo esc_attr( $item['background_color'] ); ?>">

						<?php if ( ! empty( $item['image'] ) ) : ?>
							<figure class="flex-center">
								<img src="<?php echo esc_url( $item['image']['url'] ); ?>"
								     alt="<?php echo esc_attr( $item['title'] ); ?>">
							</figure>
						<?php endif; ?>

						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h6
								class="mt-20 font-semibold text-2xl"
								style="color: <?php echo esc_attr( $item['title_color'] ); ?>"
							><?php echo esc_html( $item['title'] ); ?></h6>
						<?php endif; ?>

						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p
								class="hiw--text mt-10 mb-20 leading-relaxed"
								style="color: <?php echo esc_attr( $item['description_color'] ); ?>"
							><?php echo esc_html( $item['description'] ); ?></p>
						<?php endif; ?>

						<?php $count += 1; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif;
