<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;

class Product_Logo_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-logo';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Owner Logo', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-image';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-single-product' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'logo_wrapper',
			[
				'label' => __( 'Logo Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->logo_wrapper_style();

		$this->set_heading_section('logo_size_heading', esc_html__('Logo Size', 'lisfinity-core'), 'logo_size_hr');
		$this->set_width('logo_width', '.profile--thumbnail img', '100', '%');
		$this->set_heading_section('logo_position_heading', esc_html__('Logo Position', 'lisfinity-core'), 'logo_position_hr');
		$this->set_element_position('logo_position_x', '0', 'logo_position_y', '0', '#profile--thumbnail--elementor img');

		$this->end_controls_section();

	}

	public function logo_wrapper_style() {
		$this->set_background_color('logo_bg_color', 'rgba(246, 246, 246, 1)', esc_html__('Background color', 'lisfinity-core'), '#profile--thumbnail--elementor');

		$this->set_border_radius('logo_border_radius', '3', '3', '3', '3', 'px', '#profile--thumbnail--elementor');

		$this->set_padding('logo_padding', '.profile--thumbnail', '30', '30', '30', '30', false);

		$this->set_margin('logo_margin', '.profile--thumbnail', '0', '0', '20', '0', false);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_logo_box_shadow',
				'selector' => '{{WRAPPER}} #profile--thumbnail--elementor',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_logo_box_shadow',
				'selector' => '{{WRAPPER}} .profile--thumbnail',
			]
		);
		$this->set_width('logo_wrapper_width', '.profile--thumbnail', '100', '%');
		$this->set_height('logo_wrapper_height', '.profile--thumbnail', '150', 'px');

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

		include lisfinity_get_template_part( 'product-logo', 'shortcodes/product-single', $args );
	}

}
