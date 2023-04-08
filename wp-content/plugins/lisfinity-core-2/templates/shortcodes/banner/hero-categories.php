<?php
/**
 * Template Name: Page | Shortcodes | Homepage hero section Hero Categories
 * Description: The file that is being used to display homepage hero section taxonomies fields
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */

$categories_array = $args['settings']['taxonomies_tabs'];
$settings = [];
$settings['carousel'] = 'yes' === $args['settings']['carousel'] ?? false;
?>

<div class="banner--taxonomies flex flex-wrap justify-center items-center mt-30 -mb-10 -mx-2" data-options="<?php echo esc_attr( json_encode( $settings ) ); ?>">
	<?php foreach ( $categories_array as $category ) { ?>
		<a href="<?php echo esc_url( $category['link']['url'] ); ?>"
		   class="banner--taxonomy__container flex-center flex-col mt-10 px-2">
			<div class="banner--taxonomy__bg flex-center h-64 w-86 sm:h-86 sm:w-86 rounded mx-auto">
				<?php if ( is_array( $category['selected_icon']['value'] ) ) : ?>
					<img src="<?php echo( $category['selected_icon']['value']['url'] ); ?>"
						 class="hero-category-icon"/>
				<?php else: ?>
					<i class="<?php echo esc_html__( $category['selected_icon']['value'], 'lisfinity-core' ) ?> hero-category-icon"
					   aria-hidden="true"></i>
				<?php endif; ?>
			</div>
			<h5 class="mt-6 text-sm text-grey-400 text-center">
				<?php echo esc_html( $category['title'] ); ?></h5>
		</a>
		<?php
	} ?>
</div>


