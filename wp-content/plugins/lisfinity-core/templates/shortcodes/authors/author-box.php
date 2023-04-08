<?php
/**
 * Template Name: Shortcodes | Author Box
 * Description: The file that is being used to display author box
 *
 * @author pebas
 * @package templates/shortcodes/authors
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$option_ratings = [];
if ( 'yes' === $args['settings']['use_custom_icon_ratings'] && ! empty( $args['settings']['icon_ratings'] ) ) {
	$option_ratings['icon_ratings'] = $args['settings']['icon_ratings'];
}

$option_location = [];
if ( 'yes' === $args['settings']['use_custom_icon_location'] && ! empty( $args['settings']['icon_location'] ) ) {
	$option_location['icon_location'] = $args['settings']['icon_location'];
}
$settings = [
	'profiles_handpicked'        => $args['settings']['profiles_handpicked'],
	'display_promoted_authors'   => $args['settings']['display_promoted_authors'],
	'display_all_authors'        => $args['settings']['display_all_authors'],
	'display_handpicked_authors' => $args['settings']['display_handpicked_authors'],
	'display_info_mark'          => $args['settings']['display_info_mark'],
	'display_info_location'      => $args['settings']['display_info_location'],
	'option_ratings' => $option_ratings,
	'option_location' => $option_location
];

?>

<div class="elementor-author-box" data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>">
</div>
