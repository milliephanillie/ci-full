<?php
/**
 * Template Name: Shortcodes | Profiles
 * Description: The file that is being used to display premium business profiles
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php $profiles = $args['profiles']; ?>
<?php if ( $profiles->have_posts() ) : ?>
	<!-- Section | Profiles -->
	<section class="profiles">
		<div class="container">
			<div class="row">

				<?php $count = 1; ?>
				<?php while ( $profiles->have_posts() ) : ?>
					<?php $profiles->the_post(); ?>
					<?php $profiles_id = get_the_ID(); ?>

					<?php $class = $count > 2 ? 'sm:mt-86' : 'sm:mt-86'; ?>
					<?php $class = $count > 3 ? 'xl:mt-86' : ''; ?>
					<!-- Profile -->
					<div class="px-col mt-86 w-full sm:w-1/2 xl:w-1/3 <?php echo esc_attr( $class ); ?>">
						<div class="profile p-24 bg-white rounded shadow-theme">

							<div class="profile--header flex justify-between">
								<?php $logo = get_the_post_thumbnail_url( $profiles_id, 'full' ); ?>
								<?php if ( ! empty( $logo ) ) : ?>
									<a href="<?php the_permalink(); ?>"
									   class="profile--logo -mt-60 relative flex-center w-86 h-86 bg-white rounded-full shadow-lg overflow-hidden">
										<img src="<?php echo esc_url( $logo ); ?>"
											 alt="<?php esc_attr_e( 'Logo', 'lisfinity-core' ); ?>"
											 class="absolute p-10 w-full h-full rounded-full object-contain">
									</a>
								<?php endif; ?>
								<div class="profile--rating">
									<div
										class="lisfinity-product--info flex-center <?php echo ! empty( $location_format ) ? esc_attr( 'mr-10' ) : ''; ?>">
                                        <span class="flex-center w-32 h-32 rounded-full bg-yellow-300">
                                            <?php $icon_args = [
												'width'  => 'w-14',
												'height' => 'h-14',
												'fill'   => 'fill-product-star-icon',
											]; ?>
											<?php include lisfinity_get_template_part( 'star', 'partials/icons', $icon_args ); ?>
                                        </span>
										<span
											class="ml-6 text-sm text-grey-600"><?php printf( esc_html( '%s of %s' ), number_format_i18n( lisfinity_calculate_business_rating( $profiles_id ), 1 ), lisfinity_count_business_ratings( $profiles_id ) ); ?></span>
									</div>
								</div>
							</div>

							<div class="profile--content mt-20">
								<h6 class="text-lg">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h6>
								<?php $content = get_the_content(); ?>
								<p class="mt-6 text-grey-700"><?php echo wp_trim_words( wp_strip_all_tags( $content ), 30 ); ?></p>
							</div>
							<div class="profile--link">
								<a href="<?php the_permalink(); ?>"
								   class="flex justify-end items-center mt-24 text-sm text-grey-400">
									<?php esc_html_e( 'See Author', 'lisfinity-core' ); ?>
									<?php $icon_args = [
										'width'  => 'w-24',
										'height' => 'h-24',
										'fill'   => 'fill-current',
										'margin' => 'ml-8',
									]; ?>
									<?php include lisfinity_get_template_part( 'arrow_right', 'partials/icons', $icon_args ); ?>
								</a>
							</div>
						</div>
					</div>

					<?php $count += 1; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>

			</div>
		</div>
	</section>
<?php endif; ?>
