<?php
/**
 * Template Name: Shortcodes | Homepage hero section search form
 * Description: The file that is being used to display homepage hero section search form fields
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 * @author pebas
 */
?>
<?php
$args['settings']['to_custom_category'] = lisfinity_is_enabled( lisfinity_get_option( 'to-custom-category' ) );
?>
<div class="home-search"
	 data-id="home-search"
	 data-settings="<?php echo esc_attr( json_encode( $args['settings'] ) ); ?>">
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

