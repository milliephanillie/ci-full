<?php
/**
 * Template part for displaying posts suggestions
 *
 * @package WordPress
 * @subpackage Lisfinity
 * @since 1.0.0
 */

?>
<!-- Post | Suggestions -->
<div class="post-single--prev-next flex-wrap">
	<?php $prev_post = get_previous_post(); ?>
	<?php $next_post = get_next_post(); ?>
	<div class="wrapper flex flex-wrap">

		<?php if ( ! empty( $prev_post ) ) : ?>
			<div class="px-col w-full sm:w-1/2">
				<div class="suggestion rounded overflow-hidden">
					<a href="<?php the_permalink( $prev_post->ID ); ?>"
					   class="flex items-center mb-16">
                                                    <span class="mr-8 icon">
                                                        <svg version="1.1" height="24px" width="24px" id="Layer_1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 100 100"
                                                             style="enable-background:new 0 0 100 100;"
                                                             xml:space="preserve">
                                                            <path d="M95.8,47.4H12.9l9.9-9.9c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L2.4,50.1l1.9,1.9c0,0,0,0,0,0l14.6,14.6
                                                                c0.5,0.5,1.2,0.8,1.9,0.8s1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9l-9.9-9.9h82.9c1.5,0,2.8-1.2,2.8-2.8
                                                                C98.5,48.6,97.3,47.4,95.8,47.4z"/>
                                                            </svg>
                                                    </span>
						<span
							class="font-semibold"><?php esc_html_e( 'Prev', 'lisfinity' ); ?></span>
					</a>
					<?php if ( lisfinity_is_core_active() ) : ?>
						<?php $thumbnail = get_the_post_thumbnail_url( $prev_post->ID ); ?>
						<?php if ( ! empty( $thumbnail ) ) : ?>
							<figure>
								<img src="<?php echo esc_url( $thumbnail ) ?>"
								     alt="<?php the_title(); ?>">
							</figure>
						<?php endif; ?>
						<div class="suggestion--content p-20 bg-grey-100 rounded">
							<h2>
								<?php $prev_title = get_the_title( $prev_post->ID ); ?>
								<a href="<?php the_permalink( $prev_post->ID ); ?>"><?php echo ! empty( $prev_title ) ? wp_kses_post( $prev_title ) : the_date(); ?></a>
							</h2>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php else: ?>
			<div class="px-col w-full sm:w-1/2 opacity-75">
				<div class="suggestion rounded overflow-hidden">
					<div class="flex items-center mb-16 opacity-75">
												<span class="mr-8 icon">
													<svg version="1.1" height="24px" width="24px" id="Layer_1"
													     xmlns="http://www.w3.org/2000/svg"
													     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
													     viewBox="0 0 100 100"
													     style="enable-background:new 0 0 100 100;"
													     xml:space="preserve">
														<path d="M95.8,47.4H12.9l9.9-9.9c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L2.4,50.1l1.9,1.9c0,0,0,0,0,0l14.6,14.6
															c0.5,0.5,1.2,0.8,1.9,0.8s1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9l-9.9-9.9h82.9c1.5,0,2.8-1.2,2.8-2.8
															C98.5,48.6,97.3,47.4,95.8,47.4z"/>
														</svg>
													</span>
						<span
							class="font-semibold"><?php esc_html_e( 'Prev', 'lisfinity' ); ?></span>
					</div>
					<?php if ( lisfinity_is_core_active() ) : ?>
						<figure class="w-full h-186 bg-grey-200">
						</figure>
						<div class="suggestion--content p-20 bg-grey-100 rounded opacity-50">
							<h2><?php esc_html_e( 'No previous posts!', 'lisfinity' ); ?></h2>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $next_post ) ) : ?>
			<div class="px-col w-full sm:w-1/2 sm:ml-auto">
				<div
					class="suggestion suggestion__next rounded overflow-hidden mt-20 lg:mt-0">
					<a href="<?php the_permalink( $next_post->ID ); ?>"
					   class="flex items-center justify-end mb-16">
                                                    <span
	                                                    class="font-semibold"><?php esc_html_e( 'Next', 'lisfinity' ); ?></span>
						<span class="ml-8 icon">
                                                        <svg version="1.1" height="24px" width="24px" id="Layer_1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 100 100"
                                                             style="enable-background:new 0 0 100 100;"
                                                             xml:space="preserve">
                                                        <path d="M5.1,52.9H88L78,62.8c-1.1,1.1-1.1,2.8,0,3.9c1.1,1.1,2.8,1.1,3.9,0l16.6-16.6l-1.9-1.9c0,0,0,0,0,0L81.9,33.6
                                                            c-0.5-0.5-1.2-0.8-1.9-0.8s-1.4,0.3-1.9,0.8c-1.1,1.1-1.1,2.8,0,3.9l9.9,9.9H5.1c-1.5,0-2.7,1.2-2.7,2.8C2.4,51.7,3.6,52.9,5.1,52.9
                                                            z"/>
                                                        </svg>
                                                    </span>
					</a>
					<?php if ( lisfinity_is_core_active() ) : ?>
						<?php $thumbnail = get_the_post_thumbnail_url( $next_post->ID ); ?>
						<?php if ( ! empty( $thumbnail ) ) : ?>
							<figure class="rounded overflow-hidden">
								<img src="<?php echo esc_url( $thumbnail ) ?>"
								     alt="<?php the_title(); ?>">
							</figure>
						<?php endif; ?>
						<div class="suggestion--content p-20 bg-grey-100 rounded">
							<h2>
								<?php $next_title = get_the_title( $next_post->ID ); ?>
								<a href="<?php the_permalink( $next_post->ID ); ?>"><?php echo ! empty( $next_title ) ? wp_kses_post( $next_title ) : the_date(); ?></a>
							</h2>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php else: ?>
			<div class="px-col w-full sm:w-1/2 sm:ml-auto opacity-75">
				<div
					class="suggestion suggestion__next rounded overflow-hidden mt-20 lg:mt-0">
					<div class="flex items-center justify-end mb-16 opacity-75">
													<span
														class="font-semibold"><?php esc_html_e( 'Next', 'lisfinity' ); ?></span>
						<span class="ml-8 icon">
														<svg version="1.1" height="24px" width="24px" id="Layer_1"
														     xmlns="http://www.w3.org/2000/svg"
														     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
														     viewBox="0 0 100 100"
														     style="enable-background:new 0 0 100 100;"
														     xml:space="preserve">
															<path d="M5.1,52.9H88L78,62.8c-1.1,1.1-1.1,2.8,0,3.9c1.1,1.1,2.8,1.1,3.9,0l16.6-16.6l-1.9-1.9c0,0,0,0,0,0L81.9,33.6
																c-0.5-0.5-1.2-0.8-1.9-0.8s-1.4,0.3-1.9,0.8c-1.1,1.1-1.1,2.8,0,3.9l9.9,9.9H5.1c-1.5,0-2.7,1.2-2.7,2.8C2.4,51.7,3.6,52.9,5.1,52.9
																z"/>
														</svg>
													</span>
					</div>
					<?php if ( lisfinity_is_core_active() ) : ?>
						<figure class="w-full h-186 bg-grey-200">
						</figure>
						<div class="suggestion--content p-20 bg-grey-100 opacity-50">
							<h2><?php esc_html_e( 'No next posts!', 'lisfinity' ); ?></h2>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
