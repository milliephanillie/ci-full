<?php

class RapidLocateTemplate {
    public function __construct() {
        add_filter( 'woocommerce_locate_template', 'concrete_iron_plugin_locate_woo_templates', 10, 3 );
    }

    public function concrete_iron_plugin_locate_woo_templates( $template, $template_name, $template_path ) {
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = CONCRETEIRON . 'templates/woocommerce/';

		// Look within passed path within the theme - this is priority.
		$template = locate_template(

			[
				$template_path . $template_name,
				$template_name,
			]
		);

		// Modification: Get the template from this plugin, if it exists.
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template.
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found.
		return $template;
    }
}