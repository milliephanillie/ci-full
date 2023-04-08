<?php
/**
 * Template Name: Page | Product Single Page Template Elementor
 * Description: Page template that is being used as the template for product single created with the Elementor
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
$owner = carbon_get_post_meta(get_the_ID(), 'product-owner');
$account_type = carbon_get_user_meta($owner, 'account-type');
$options = [
	'page_search'                => get_permalink( lisfinity_get_page_id( 'page-search' ) ),
	'messenger'                  => lisfinity_get_option( 'messenger' ),
	'messenger_limit'            => lisfinity_get_option( 'messenger-limit' ),
	'messenger_note'             => lisfinity_get_option( 'messenger-note' ),
	'messenger_note_translation' => lisfinity_get_option( 'messenger-note-translation' ),
	'report'                     => carbon_get_theme_option( 'report' ),
	'report_reasons'             => carbon_get_theme_option( 'report-reasons-enable' ),
	'ad_likes'                   => lisfinity_get_option( 'ad-likes' ),
	'ad_visits'                  => lisfinity_get_option( 'ad-visits' ),
	'ad_similar'                 => lisfinity_get_option( 'ad-similar' ),
	'ad_compare'                 => lisfinity_get_option( 'ads-compare' ),
	'safety_tips'                => lisfinity_get_option( 'display-safety-tips' ),
	'calculator_position'        => lisfinity_get_option( 'calculator-position' ),
	'hide_bidding'               => '1' === lisfinity_get_option( 'site-hide-bidding' ),
	'bidding_description'        => '1' === lisfinity_get_option( 'site-bidding-description' ),
	'random_bidding'             => '1' === lisfinity_get_option( 'product-enable-random-bidding' ),
	'account_type' => $account_type
]; ?>

<?php $elementor_page_id = lisfinity_get_option( 'page-single-listing' ); ?>
<?php if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	the_content();
} ?>
<?php
$owner        = carbon_get_post_meta( get_the_ID(), 'product-owner' );
$account_type = carbon_get_user_meta( $owner, 'account-type' );
$account =  get_post_meta( get_the_ID(), 'account-type', true );
?>
<main class="<?php echo esc_attr( $account ?? 'personal' ); ?>">
	<div id="loader"
		 class="fixed top-0 left-0 w-full h-full flex-center loader loader__auth flex flex-col w-full bg-white"
		 style="z-index: 99999;">

		<div class="flex-center flex-col">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto; zoom: .4;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
				<path class="ll" fill="none" stroke="#333" stroke-width="8" stroke-dasharray="42.76482137044271 42.76482137044271" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round"
					  style="transform:scale(0.8);transform-origin:50px 50px">
					<animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0;256.58892822265625"></animate>
				</path>
			</svg>
			<p class="text-lg text-grey-900"><?php esc_html_e( 'Loading Page...', 'lisfinity-core' ); ?></p>
		</div>
	</div>
	<div id="page-single-elementor" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	</div>
	<?php if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		echo lisfinity_get_elementor_content( $elementor_page_id );
	} ?>
</main>
