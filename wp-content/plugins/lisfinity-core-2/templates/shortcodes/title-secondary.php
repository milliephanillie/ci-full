<?php
/**
 * Template Name: Shortcodes | Title Secondary
 * Description: The file that is being used to display title shortcode
 *
 * @author pebas
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @var $args
 */
?>
<!-- Section | Title Secondary -->
<div class="container">
    <div class="row">
        <div class="flex flex-col px-2 justify-between w-full items-start mx-auto xs:flex-row xs:items-end">
            <?php if ( ! empty( $args['heading'] ) || ! empty( $args['subheading'] ) ) : ?>
                <div class="title--heading relative">
                    <?php if ( ! empty( $args['subheading'] ) ) : ?>
                        <p><?php echo esc_html( $args['subheading'] ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $args['heading'] ) ) : ?>
                        <?php $shadow_text = ! empty( $args['shadow_different'] ) && 'yes' === $args['shadow_different'] ? $args['shadow_text'] : $args['heading']; ?>
                        <div class="title--shadow relative">
                            <span class="title--shadow__big font-bold text-xs-shadow whitespace-no-wrap sm:text-shadow"
                                  style="color: <?php echo $args['shadow_color'] ? esc_attr( $args['shadow_color'] ) : '#f6f6f6'; ?>"><?php echo esc_html( $shadow_text ); ?></span>
                            <h3 class="text-4xl font-bold text-grey-1000 sm:text-6xl"><?php echo esc_html( $args['heading'] ); ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ( ! empty( $args['linked'] ) ) : ?>
                <a href="<?php echo esc_url( $args['link_url']['url'] ); ?>"
                   class="flex justify-right items-center mt-6 xs:mt-0 r:alt"
                   data-animation="ripple"
                >
					<span class="mr-12 text-md">
						<?php echo esc_html( $args['link_text'] ); ?>
					</span>
                    <?php $icon_args = [
                        'width'  => 'w-24',
                        'height' => 'h-24',
                        'fill'   => 'fill-field-icon',
                    ]; ?>
                    <?php include lisfinity_get_template_part( 'arrow_right', 'partials/icons', $icon_args ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>