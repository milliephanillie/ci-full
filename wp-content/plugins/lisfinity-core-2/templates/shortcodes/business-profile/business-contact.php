<?php
/**
 * Template Name: Shortcodes | Login Form
 * Description: The file that is being used to display login form shortcode
 *
 * @author pebas
 * @package templates/shortcodes/single
 * @version 1.0.0
 *
 * @var $args
 */

?>
<?php
$settings = [
	'display_address' => $args['settings']['display_address'],
	'display_map' => $args['settings']['display_map'],
	'membership_address' => 'always',
	'login_url' => get_permalink( lisfinity_get_page_id( 'page-login' ) )
];

if(lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
	$settings['membership_address'] = lisfinity_get_option('membership-address');
}
?>

<div class="elementor-business-contact" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"></div>
