<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\Elements\ElementsGlobalModel;

class Global_Elements_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'global-elements';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Global Elements', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-cogs';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'general', 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Partners section.
		$this->start_controls_section(
			'elements_content',
			[
				'label' => __( 'Global Widget', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'global_elements_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'The elements have first to be created from the %s section of wp-admin area.', 'lisfinity-core' ), '<strong>Lisfinity Elements -> Global Elements</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'widget',
			[
				'label'       => __( 'Choose Global Element', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => lisfinity_format_post_select( [
					'post_type' => ElementsGlobalModel::$type,
				] ),
				'default'     => '',
				'description' => __( 'Choose from the list of the global elements', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'settings' => $settings,
		];
		include lisfinity_get_template_part( 'global-elements', 'shortcodes/elements', $args );
	}

}
