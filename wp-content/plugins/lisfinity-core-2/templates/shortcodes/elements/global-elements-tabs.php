<?php
/**
 * Template Name: Shortcodes | Global Elements Tabs
 * Description: The file that is being used to display site how it works section
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<?php
$settings = $args['settings'];
?>
<?php if ( ! empty( $settings['tabs'] ) ) : ?>
	<div class="ge-tabs">

		<div class="ge-tabs--headers">
			<nav>
				<?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
					<?php if ( ! empty( $tab['title'] ) ) : ?>
						<button type="button" class="ge-tabs--action <?php echo 0 === $index ? esc_attr( 'active' ) : ''; ?>" data-id="<?php echo esc_attr( $tab['widget'] ); ?>">
							<?php echo esc_html( $tab['title'] ); ?>
						</button>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
		</div>

		<div class="ge-tabs--content">
			<?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
				<?php if ( ! empty( $tab['widget'] ) ) : ?>
					<div class="ge-tab elementor-repeater-item-<?php echo esc_attr( $tab['_id'] ); ?> <?php echo esc_attr( "get-tab--{$tab['widget']}" ); ?> <?php echo 0 === $index ? esc_attr( 'active' ) : esc_attr( 'hidden' ); ?>" data-id="<?php echo esc_attr( $tab['widget'] ); ?>">
						<?php echo lisfinity_get_elementor_content( $tab['widget'] ); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

	</div>
<?php endif; ?>
