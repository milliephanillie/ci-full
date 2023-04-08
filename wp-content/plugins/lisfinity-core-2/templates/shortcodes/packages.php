<?php
/**
 * Template Name: Shortcodes | Pricing Packages
 * Description: The file that is being used to display products and various payment packages
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
$packages = $args['packages'];
?>
<?php if ( $packages->have_posts() ) : ?>
	<div class="container">
		<div class="row">
			<div class="flex justify-center w-full mx-auto px-col sm:px-0">
				<?php $packages_count = $packages->post_count; ?>
				<!-- Price Packages -->
				<div
					class="packages relative flex flex-wrap mx-auto w-full rounded xl:shadow-theme <?php echo $packages_count === 2 ? esc_attr( 'xl:w-3/4' ) : ( $packages_count === 1 ? esc_attr( 'xl:w-1/3' ) : '' ); ?>">
					<?php while ( $packages->have_posts() ) : ?>
						<?php $width = $packages_count === 1 ? 'xl:w-full' : ( $packages_count > 1 ? "xl:w-1/{$packages_count}" : 'xl:w-1/3' ); ?>
						<?php $packages->the_post(); ?>
						<?php $product_id = get_the_ID(); ?>
						<?php $wc_product = wc_get_product( $product_id ); ?>
						<?php $style = carbon_get_post_meta( $product_id, 'package-style' ); ?>
						<?php $package_footnotes = []; ?>
						<?php $discounts_enabled = carbon_get_post_meta( $product_id, 'package-discounts-enable' ); ?>
						<?php $discounts = carbon_get_post_meta( $product_id, 'package-discounts' ); ?>
						<!-- Price Package -->
						<div
							class="package relative flex flex-col mt-24 py-30 px-48 rounded text-grey-900 <?php echo esc_attr( $width ); ?> w-full sm:w-full xl:mt-0 <?php echo '2' == $style ? esc_attr( 'mt-24 mb-0 md:-mt-24 md:-mb-48 xl:-mt-40 xl:-my-40 bg-white shadow-theme z-10' ) : esc_attr( 'bg-grey-100 shadow-theme xl:shadow-none' ); ?>">
							<!-- Price Package | Title -->
							<h6 class="text-lg font-semibold text-grey-900"><?php the_title(); ?></h6>
							<?php $sale_price =  ! empty( $wc_product->get_sale_price() ); ?>
							<?php $price = $wc_product->get_price() * lisfinity_get_chosen_currency_rate(); ?>

							<?php
							if ( $sale_price ) {
								$price = wc_format_sale_price( wc_get_price_to_display( $wc_product, array( 'price' => $wc_product->get_regular_price() * lisfinity_get_chosen_currency_rate() ) ), wc_get_price_to_display( $wc_product ) * lisfinity_get_chosen_currency_rate() ) . $wc_product->get_price_suffix();
							}
							?>
							<!-- Price Package | Price -->
							<div
								class="package--price relative flex mt-30 font-bold text-5xl text-grey-1100 whitespace-nowrap">
								<?php if ( '2' == $style ) : ?>
									<span
										data-discounts="<?php echo esc_attr( json_encode( $discounts ) ); ?>"
										data-input="<?php echo esc_attr( "qty-$product_id" ); ?>"
										data-price="<?php echo esc_attr( $wc_product->get_price() ); ?>"
										class="package--recommended absolute w-full h-60 bg-blue-200 rounded-r"></span>
								<?php endif; ?>
								<span
									data-discounts="<?php echo esc_attr( json_encode( $discounts ) ); ?>"
									data-input="<?php echo esc_attr( "qty-$product_id" ); ?>"
									data-price="<?php echo esc_attr( $wc_product->get_price() ); ?>"
									class="relative z-10 <?php echo ! empty( $sale_price ) ? esc_attr( 'on-sale' ) : ''; ?>"><?php echo ! empty( $sale_price ) ? $price : lisfinity_get_price_html( $price ); ?></span>
							</div>
							<?php if ( $discounts_enabled && ! empty( $discounts ) ) : ?>
								<div id="<?php echo esc_attr( "discount--qty-$product_id" ); ?>"
									 class="hidden text-sm text-red-600 mt-20"><?php _e( 'You get a discount of: ' ) ?><span
										class="discount"><?php echo esc_html( '0' ); ?></span></div>
								<!-- Price Package | Qty -->
								<div class="flex items-center mt-20 -mb-10">
									<label for="<?php echo esc_attr( "qty-$product_id" ); ?>"
										   class="font-light text-sm text-grey-900"><?php esc_html_e( 'Listings number', 'lisfinity-core' ); ?></label>
									<div class="mx-8 p-4 bg-white border border-grey-300 rounded" style="width: 54px;">
										<?php if ( 'select' === carbon_get_post_meta( $product_id, 'package-discounts-type' ) ) : ?>
											<select id="<?php echo esc_attr( "qty-$product_id" ); ?>">
												<?php foreach ( $discounts as $discount ) : ?>
													<option
														value="<?php echo esc_attr( $discount['duration'] ); ?>"><?php echo esc_html( $discount['duration'] ); ?></option>
												<?php endforeach; ?>
											</select>
										<?php else: ?>
											<input id="<?php echo esc_attr( "qty-$product_id" ); ?>" type="number"
												   min="1" class="w-full bg-transparent" value="1">
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php $features = carbon_get_post_meta( $product_id, 'package-features' );?>
							<?php if ( ! empty( $features ) ) : ?>
								<!-- Price Package | Features -->
								<ul class="package--features flex flex-col mt-30">
									<?php foreach ( $features as $feature ) : ?>
										<li class="relative mb-8">
											<?php echo __( lisfinity_convert_to_option( $feature['package-feature'], $product_id ), 'lisfinity-core' ); ?>
											<?php if ( $feature['package-footnote'] ) : ?>
												<?php $package_footnotes[] = $feature['package-footnote']; ?>
												<?php foreach ( $package_footnotes as $footnote ) : ?>
													<span
														class="package--footnote -mr-4 leading-none align-top text-red-600"><?php echo esc_html( '*' ); ?></span>
												<?php endforeach; ?>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php $btn_class = '2' != $style ? 'border-grey-500 rounded hover:bg-grey-500 hover:shadow-md hover:text-white' : 'bg-blue-600 border-blue-600 text-white hover:bg-blue-800 hover:border-blue-800 hover:shadow-theme'; ?>
							<?php $different_text = carbon_get_post_meta( get_the_ID(), 'package-different-buy-button' ); ?>
							<?php $button_text = carbon_get_post_meta( get_the_ID(), 'package-button-text' ); ?>
							<?php if ( is_user_logged_in() ) : ?>
								<button type="button"
										data-id="<?php echo esc_attr( get_the_ID() ); ?>"
										class="package--buy action--buy-package flex-center mt-30 mb-20 py-8 h-46 border rounded font-semibold <?php echo esc_attr( $btn_class ); ?>"><?php echo $different_text && ! empty( $button_text ) ? esc_html( $button_text ) : esc_html__( 'Buy Package', 'lisfinity-core' ); ?></button>
							<?php else: ?>
								<?php $login_page_id = lisfinity_get_page_id( 'page-login' ); ?>
								<a href="<?php echo esc_url( get_permalink( $login_page_id ) ); ?>"
								   class="package--buy action--buy-package flex-center mt-30 mb-20 py-8 h-46 border rounded font-semibold <?php echo esc_attr( $btn_class ); ?>"><?php echo $different_text && ! empty( $button_text ) ? esc_html( $button_text ) : esc_html__( 'Buy Package', 'lisfinity-core' ); ?></a>
							<?php endif; ?>
							<?php if ( ! empty( $package_footnotes ) ) : ?>
								<div class="package--footnotes absolute">
									<?php $footnotes_count = 1; ?>
									<?php foreach ( $package_footnotes as $package_footnote ) : ?>
										<div class="package--footnote relative">
											<div class="package--footnotes-star-wrapper absolute flex items-center">
												<?php for ( $i = 1; $i <= $footnotes_count; $i += 1 ) : ?>
													<span
														class="package--footnotes__star align-top text-red-600"><?php echo esc_html( '*' ); ?></span>
												<?php endfor; ?>
											</div>
											<span
												class="footnote text-sm text-grey-800"><?php echo esc_html( $package_footnote ); ?></span>
										</div>
										<?php $footnotes_count += 1; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
