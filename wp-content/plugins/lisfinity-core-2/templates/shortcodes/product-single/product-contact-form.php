<?php
/**
 * Template Name: Shortcodes | Product Contact Form
 * Description: The file that is being used to display product contact form shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php if ( ! empty( $args['settings']['selected_icon_button'] ) ) {
	$icon = $args['settings']['selected_icon_button']['value'];
} ?>
<?php $id = lisfinity_get_product_id(); ?>
<div class="product-contact-form">

	<button type="button"
			id="open-contact-modal"
			class="relative flex-center btn btn--transparent-red font-normal px-24 whitespace-no-wrap w-full btn--contact-modal"
	>
		<?php if ( ! empty( $icon ) ) : ?>
			<?php if ( is_array( $icon ) ) : ?>
				<img class="btn--contact-modal-icon"
					 src="<?php echo esc_url( $icon['url'] ); ?>"
					 alt="<?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>">
			<?php else : ?>
				<i class="<?php echo esc_html__( $icon, 'lisfinity-core' ) ?> btn--contact-modal-icon h-18 w-18"
				   aria-hidden="true"></i>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $args['settings']['text'] ) : ?>
			<?php echo esc_html( $args['settings']['text'] ) ?>
		<?php else : ?>
			<?php esc_html_e( 'Send a Message', 'lisfinity-core' ); ?>
		<?php endif; ?>
	</button>

	<!-- Modal -->
	<div
		id="contact-modal"
		class="modal--wrapper modal--admin fixed top-0 left-0 py-30 flex justify-center w-full h-full overflow-y-auto hidden"
	>
		<div
			id="contact-modal-inner"
			class="modal my-auto relative z-2 whitespace-normal">

			<div class="modal--inner bg-white rounded shadow-xl overflow-hidden">

				<div class="modal--header flex justify-between items-center p-20 bg-grey-100">

					<h5
						class="modal--title font-bold text-lg w-full"><?php esc_html_e( 'Send a Message', 'lisfinity-core' ); ?></h5>

					<div class="modal--header__right flex items-center">
						<button type="button" id="close-contact-modal"
								class="flex items-center ml-20 text-sm text-grey-700">
							<?php esc_html_e( 'Esc', 'lisfinity-core' ); ?>
							<svg class="ml-8 w-16 h-16 fill-field-icon pointer-events-none" version="1.1"
								 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
								 y="0px"
								 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
							<path d="M53.9,50L96.9,6.9c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L50,46.1L6.9,3.1C5.9,2,4.1,2,3.1,3.1C2,4.1,2,5.9,3.1,6.9
								L46.1,50L3.1,93.1c-1.1,1.1-1.1,2.8,0,3.9c0.5,0.5,1.2,0.8,1.9,0.8s1.4-0.3,1.9-0.8L50,53.9l43.1,43.1c0.5,0.5,1.2,0.8,1.9,0.8
								s1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L53.9,50z"/>
						</svg>
						</button>
					</div>

				</div>

				<div id="form-listing-modal-inner" class="py-30 px-40">

					<div class="mb-20">
						<?php if ( $args['settings']['content_text'] ) : ?>
							<?php echo esc_html( $args['settings']['content_text'] ) ?>
						<?php else : ?>
							<?php esc_html_e( 'Here you can send a message to the owner of the ad. Please try to be as explanatory as possible so that they can provide you with more precise answers.', 'lisfinity-core' ); ?>
						<?php endif; ?>
					</div>

					<form id="form-listing-single-contact" method="post">

						<!-- Input | Name -->
						<div class="field flex flex-col mb-20">
							<div class="field--top flex justify-between">
								<label for="name"
									   class="field--label mb-4 text-sm text-grey-500"><?php esc_html_e( 'Your Name', 'lisfinity-core' ); ?></label>
							</div>

							<div
								class="field--wrapper relative flex items-center h-44 p-14 bg-grey-100 border border-grey-300 rounded">
								<input type="text" id="name" name="name" class="w-full bg-transparent"
									   placeholder="<?php esc_attr_e( 'Enter your name', 'lisfinity-core' ); ?>"
									   autoComplete="off"/>
							</div>
						</div>

						<!-- Input | Email -->
						<div class="field flex flex-col mb-20">
							<div class="field--top flex justify-between">
								<label for="email"
									   class="field--label mb-4 text-sm text-grey-500"><?php esc_html_e( 'Your Email', 'lisfinity-core' ); ?></label>
							</div>

							<div
								class="field--wrapper relative flex items-center h-44 p-14 bg-grey-100 border border-grey-300 rounded">
								<input type="email" id="email" name="email" class="w-full bg-transparent"
									   placeholder="<?php esc_attr_e( 'Enter your email', 'lisfinity-core' ); ?>"
									   autoComplete="off"/>
							</div>
						</div>

						<!-- Input | Message -->
						<div class="field flex flex-col mb-20">
							<div class="field--top flex justify-between">
								<label for="message"
									   class="field--label mb-4 text-sm text-grey-500"><?php esc_html_e( 'Your Message', 'lisfinity-core' ); ?></label>
							</div>

							<div
								class="field--wrapper relative flex items-center bg-grey-100 border border-grey-300 rounded">
								<textarea id="message" name="message" class="w-full bg-transparent p-14"
										  style="min-height: 160px;"
										  autoComplete="off"></textarea>
							</div>
						</div>

						<div id="contact-form-error" class="text-red-700 hidden"></div>

						<div class="flex">
							<button type="submit"
									class="relative save-button flex justify-center items-center ml-auto py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
							>
								<?php if ( $args['settings']['save_button_text'] ) : ?>
									<?php echo esc_attr( $args['settings']['save_button_text'] ); ?>
								<?php else: ?>
									<?php esc_html_e( 'Send', 'lisfinity-core' ); ?>
								<?php endif; ?>
							</button>
							<input type="hidden" name="product_id" value="<?php esc_attr_e( get_the_ID() ); ?>">
						</div>

					</form>

				</div>

			</div>

		</div>
		<!-- .Modal -->
	</div>

</div>
