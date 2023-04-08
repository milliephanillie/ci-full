<?php
/**
 * Template Name: Shortcodes | Navigation Login
 * Description: The file that is being used to display user login icon
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */


?>
<?php

if ( $args['settings']['link_default_login'] ) {
	$link_manual = $args['settings']['link_default_login']['url'];
	$link        = '';
}
if ( $args['settings']['selected_icon_login'] ) {
	$icon = $args['settings']['selected_icon_login']['value'];
}
if ( ! is_user_logged_in() && get_permalink( lisfinity_get_page_id( 'page-login' ) ) !== $link_manual ) {
	$link = $link_manual;

} elseif ( ! is_user_logged_in() && get_permalink( lisfinity_get_page_id( 'page-login' ) ) === $link_manual ) {
	$link = get_permalink( lisfinity_get_page_id( 'page-login' ) );
}

?>
<?php $elementor = \Elementor\Plugin::instance(); ?>
<?php if ( ! is_user_logged_in() || $elementor->editor->is_edit_mode() ) : ?>
	<a class="flex-center py-12 rounded font-semibold text-white" href="<?php echo esc_url( $link ); ?>"
	   title="<?php echo esc_html__( 'Sign In', 'lisfinity-core' ); ?>">
		<?php if ( empty( $args['settings']['place_icon_login'] ) || ( ! empty( $args['settings']['place_icon_login'] && empty( $icon ) ) ) ) : ?>
			<svg version="1.1" width="16px" height="16px" xmlns="http://www.w3.org/2000/svg"
				 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 class="login--icon"
				 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100; fill:#65d6ad;" xml:space="preserve">
								<path class="st0" d="M8.5,87.7"/>
				<g>
					<path d="M50,57c13.2,0,24-10.8,24-24S63.2,9,50,9C36.8,9,26,19.7,26,33S36.8,57,50,57z M50,14.5c10.2,0,18.5,8.3,18.5,18.5
										S60.2,51.5,50,51.5S31.5,43.2,31.5,33S39.8,14.5,50,14.5z"/>
					<path d="M97.9,86.2C84.7,74.5,67.7,68,50,68S15.3,74.5,2.1,86.2c-1.1,1-1.2,2.7-0.2,3.9c1,1.1,2.7,1.2,3.9,0.2
										C17.9,79.5,33.7,73.5,50,73.5s32.1,6,44.3,16.8c0.5,0.5,1.2,0.7,1.8,0.7c0.8,0,1.5-0.3,2.1-0.9C99.2,89,99.1,87.2,97.9,86.2z"/>
				</g>
							</svg>
		<?php elseif ( is_array( $icon ) ) : ?>
			<img src=" <?php echo( $icon['url'] ); ?>" alt=" <?php echo esc_html__( 'icon', 'lisfinity-core' ); ?>"
				 class="login--icon w-20 h-20 fill-icon-reset pointer-events-none"/>
		<?php else : ?>
			<i class="<?php echo esc_html__( $icon, 'lisfinity-core' ) ?> login--icon" aria-hidden="true"></i>
		<?php endif; ?>
	</a>
<?php endif; ?>
