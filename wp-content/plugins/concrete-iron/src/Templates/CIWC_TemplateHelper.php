<?php
namespace ConcreteIron\Templates;

class CIWC_TemplateHelper {
    public static function locate_woo_templates_in_plugin($template, $template_name, $template_path) {
		global $woocommerce;

		$original_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = CONCRETEIRON . 'templates/woocommerce/';

		$template = locate_template(

			[
				$template_path . $template_name,
				$template_name,
			]
		);

		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		if ( ! $template ) {
			$template = $original_template;
		}

		return $template;
    }
}