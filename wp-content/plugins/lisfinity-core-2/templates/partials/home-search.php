<?php
/**
 * Template Name: Partial | HomePage Search
 * Description: Partials that is loading search form for the homepage
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */
?>
<?php $options = [
	'columns'            => (int) lisfinity_get_option( 'home-fields-columns' ),
	'padding'            => (int) lisfinity_get_option( 'home-fields-padding' ),
	'width'              => lisfinity_get_option( 'home-fields-wrapper-width' ),
	'fields_style'       => lisfinity_get_option( 'home-fields-style' ),
	'to_custom_category' => lisfinity_is_enabled( lisfinity_get_option( 'to-custom-category' ) ),
]; ?>
<?php $width = "w-full lg:w-{$options['width']}%"; ?>
<?php $container_class = '2' === $options['fields_style'] ? 'flex items-center pt-10 px-20 pb-20 rounded hero-form-style-2' : 'flex-center'; ?>
<div id="home-search" class="mx-auto <?php echo esc_attr( $width . ' ' . $container_class ); ?>"
	 data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
	<div class="flex-center flex-col">
		<svg width="45" height="45" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#4c4c4c">
			<g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="1">
				<circle cx="22" cy="22" r="6" stroke-opacity="0">
					<animate attributeName="r"
							 begin="1.5s" dur="3s"
							 values="6;22"
							 calcMode="linear"
							 repeatCount="indefinite"/>
					<animate attributeName="stroke-opacity"
							 begin="1.5s" dur="3s"
							 values="1;0" calcMode="linear"
							 repeatCount="indefinite"/>
					<animate attributeName="stroke-width"
							 begin="1.5s" dur="3s"
							 values="2;0" calcMode="linear"
							 repeatCount="indefinite"/>
				</circle>
				<circle cx="22" cy="22" r="6" stroke-opacity="0">
					<animate attributeName="r"
							 begin="3s" dur="3s"
							 values="6;22"
							 calcMode="linear"
							 repeatCount="indefinite"/>
					<animate attributeName="stroke-opacity"
							 begin="3s" dur="3s"
							 values="1;0" calcMode="linear"
							 repeatCount="indefinite"/>
					<animate attributeName="stroke-width"
							 begin="3s" dur="3s"
							 values="2;0" calcMode="linear"
							 repeatCount="indefinite"/>
				</circle>
				<circle cx="22" cy="22" r="8">
					<animate attributeName="r"
							 begin="0s" dur="1.5s"
							 values="6;1;2;3;4;5;6"
							 calcMode="linear"
							 repeatCount="indefinite"/>
				</circle>
			</g>
		</svg>
	</div>
</div>
