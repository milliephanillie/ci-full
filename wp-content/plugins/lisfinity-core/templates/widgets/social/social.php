<?php
/**
 * Template Name: Widget | Social
 * Description: The file that is being used to display social widget
 *
 * @author pebas
 * @package templates/widgets
 * @version 1.0.0
 *
 * @var $args
 * @var $instance
 */
?>
<?php $socials = [
	'facebook'  => $instance['facebook'],
	'twitter'   => $instance['twitter'],
	'instagram' => $instance['instagram'],
	'dribbble'  => $instance['dribbble'],
	'linkedin'  => $instance['linkedin'],
	'youtube'   => $instance['youtube'],
	'reddit'    => $instance['reddit'],
	'pinterest' => $instance['pinterest'],
	'medium'    => $instance['medium'],
	'vk'        => $instance['vk'],
]; ?>

<?php if ( ! empty( array_values( $socials ) ) ) : ?>
	<ul class="socials flex flex-wrap -mx-8 -mb-8">
		<?php foreach ( $socials as $label => $link ) : ?>
			<?php if ( ! empty( $link ) ) : ?>
				<li class="mt-8 px-4"><a href="<?php echo esc_url( $link ); ?>" rel="nofollow" target="_blank"
				                         title="<?php echo esc_attr( $label ); ?>"
				                         class="flex-center w-36 h-36 border rounded-full text-grey-700 hover:border-grey-400 hover:text-grey-400">
						<?php echo lisfinity_load_social_icon_svg( $label ); ?>
					</a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
