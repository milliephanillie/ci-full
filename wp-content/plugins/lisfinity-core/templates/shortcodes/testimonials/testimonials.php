<?php
/**
 * Template Name: Shortcodes | Testimonials
 * Description: The file that is being used to display site testimonials section
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */

use Lisfinity\Models\Testimonials\TestimonialModel;

?>
<?php
$settings = $args['settings'];
$model    = new TestimonialModel();
?>
<?php for ( $i = 1; $i <= $settings['rows']; $i += 1 ) : ?>
	<?php
	$args = [
		'number' => 5,
		'offset' => ( absint( $i ) - 1 ) * 5,
	]
	?>
	<?php $testimonials = $model->get_all_business_reviews( $args ); ?>
	<?php $testimonials = array_merge( $testimonials, $testimonials ); ?>
	<?php $testimonials = array_merge( $testimonials, $testimonials ); ?>



	<?php if ( ! empty( $testimonials ) ) : ?>
		<div class="testimonials--wrapper relative py-10 overflow-hidden">
			<div class="testimonials flex flex-nowrap">
				<?php foreach ( $testimonials as $testimonial ) : ?>
					<div class="testimonial--column px-col">
						<div class="testimonial p-30 bg-white rounded shadow-theme">
							<div class="testimonial--content">
								<svg version="1.1" class="w-24 h-24 fill-grey-500" xmlns="http://www.w3.org/2000/svg"
								     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								     viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;"
								     xml:space="preserve">
								<g>
									<path d="M91.2,25.3c-2.8-4.5-7.1-7.5-12.3-8.4c-5.4-1-11.1,0.4-15.5,3.8c-4.4,3.3-7.2,8.3-7.7,13.7c-0.5,5.4,1.1,10.6,4.6,14.7
										c2.9,3.4,6.7,5.7,10.9,6.6l-1.6,25.2l0.2,0.5c0.4,1.1,1.4,1.8,2.5,1.9c0.1,0,0.1,0,0.2,0c1.1,0,2.1-0.6,2.5-1.5
										c0.9-1.8,2-3.8,3.2-6.1c5.4-10.1,12.7-24,15.1-31.8C95.2,37.5,94.5,30.6,91.2,25.3z M88.1,42.2c-1.9,6.2-7.3,16.9-12.1,26L77,50.8
										l-2.7-0.2c-3.8-0.3-7.3-2.1-9.8-5c-2.5-2.9-3.7-6.7-3.3-10.6c0.4-3.9,2.3-7.4,5.5-9.8c2.6-2,5.7-3,8.8-3c0.8,0,1.6,0.1,2.4,0.2
										c4.6,0.8,7.2,3.6,8.6,5.8C89,32.1,89.5,37.4,88.1,42.2z"/>
									<path d="M28.9,17c-5.4-1-11.1,0.4-15.5,3.8C9,24.2,6.2,29.1,5.7,34.5c-0.5,5.4,1.1,10.6,4.6,14.7c2.9,3.4,6.7,5.7,10.9,6.6
										l-1.6,25.2l0.2,0.5c0.4,1.1,1.4,1.8,2.5,1.9c0.1,0,0.1,0,0.2,0c1.1,0,2-0.6,2.5-1.5c0.9-1.8,2-3.8,3.2-6.1
										c5.4-10.1,12.7-24,15.1-31.8c1.9-6.3,1.1-13.2-2.1-18.5C38.5,20.8,34.1,17.9,28.9,17z M38.1,42.2c-1.9,6.2-7.3,16.9-12.1,26
										L27,50.8l-2.7-0.2c-3.8-0.3-7.3-2.1-9.8-5c-2.5-2.9-3.7-6.7-3.3-10.6c0.4-3.9,2.3-7.4,5.5-9.8c2.6-2,5.7-3,8.8-3
										c0.8,0,1.6,0.1,2.4,0.2c4.6,0.8,7.2,3.6,8.6,5.8C39,32.1,39.5,37.4,38.1,42.2z"/>
								</g>
						</svg>
								<p class="testimonial--text italic text-grey-700 leading-relaxed"><?php echo esc_html( wp_trim_words( $testimonial->comment_content, $settings['chars_limit'], '...' ) ); ?></p>
							</div>

							<div class="testimonial--meta flex justify-between">
								<a href="<?php echo esc_url( get_the_permalink( $testimonial->comment_post_ID ) ); ?>"
								   class="testimonial--author flex align-center">
									<figure
										class="relative top-2 flex-center w-40 h-40 border-grey-300 overflow-hidden">
										<img
											src="<?php echo esc_url( get_the_post_thumbnail_url( $testimonial->comment_post_ID ) ); ?>"
											alt="<?php echo esc_attr( get_the_title( $testimonial->comment_post_ID ) ); ?>">
									</figure>
									<div class="ml-10">
									<span
										class="text-sm text-grey-500"><?php echo esc_html( date( 'Y', strtotime( $testimonial->comment_date ) ) ); ?></span>
										<div><?php echo get_the_title( $testimonial->comment_post_ID ); ?></div>
									</div>
								</a>
								<div
									class="flex-center testimonial--ratings <?php echo ! empty( $location_format ) ? esc_attr( 'mr-10' ) : ''; ?>">
                                        <span class="flex-center w-32 h-32 rounded-full bg-yellow-300">
                                            <?php $icon_args = [
	                                            'width'  => 'w-14',
	                                            'height' => 'h-14',
	                                            'fill'   => 'fill-product-star-icon',
                                            ]; ?>
                                            <?php include lisfinity_get_template_part( 'star', 'partials/icons', $icon_args ); ?>
                                        </span>
									<span
										class="ml-6 text-sm text-grey-600"><?php echo esc_html( number_format_i18n( $model->calculate_single_review_average( $testimonial->comment_ID ), 1 ) ); ?></span>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endfor;
