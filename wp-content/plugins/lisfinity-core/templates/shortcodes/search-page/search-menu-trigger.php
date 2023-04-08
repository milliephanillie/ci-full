<?php
/**
 * Template Name: Shortcodes | Search Page Filter Menu Trigger
 * Description: The file that is being used to display search page filter menu trigger icon
 *
 * @author pebas
 * @package templates/shortcodes/search-page
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Search | Sidebar Filter -->
<?php
$options = [
	'custom_icon' => false,
];
?>

<div class="mobile-menu--search relative" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
</div>
