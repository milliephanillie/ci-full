<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Multiple_Value_Subtitle_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Multiple_Value_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Single_Value_Label_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Single_Value_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Typography;

class Product_Specification_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-specification';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Specification', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-info';
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
		// Wrapper.
		$this->start_controls_section(
			'specification_wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->wrapper_style();
		$this->end_controls_section();
		// Group Wrapper.
		$this->start_controls_section(
			'specification_group_wrapper',
			[
				'label' => __( 'Group Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->group_wrapper_style();
		$this->end_controls_section();

		// Single Value.
		$this->start_controls_section(
			'specification_single_value',
			[
				'label' => __( 'Single Value Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->single_value_style();
		$this->end_controls_section();

		// Multiple Value.
		$this->start_controls_section(
			'specification_multiple_value',
			[
				'label' => __( 'Multiple Value Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->multiple_value_style();
		$this->end_controls_section();

	}

	public function wrapper_style() {
		$this->set_background_color('wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__('Background Color', 'lisfinity-core'), '.specification--wrapper');
		$this->set_padding('wrapper_padding', '.specification--wrapper', '0', '30', '0', '0', 'false');
		$this->set_margin('wrapper_margin', '.specification--wrapper', '-10', '0', '0', '0', 'false');
		$this->set_border_radius('wrapper_border_radius', '0', '0', '0', '0', 'px', '.specification--wrapper');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .specification--wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .specification--wrapper',
			]
		);
	}

	public function group_wrapper_style() {
		$this->start_controls_tabs(
			'group_wrapper_specification_tabs',
			[
				'label' => __( 'Single Value Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'group_wrapper_title_specification_tab',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Specification_Typography::get_type(),
			[
				'name'     => 'group_wrapper_title_specification_typography',
				'selector' => '{{WRAPPER}} .group--wrapper h5',
			]
		);

		$this->set_background_color('group_wrapper_title_specification_bg_color', 'transparent', esc_html__('Background Color', 'lisfinity-core'), '.group--wrapper h5');
		$this->set_padding('group_wrapper_title_specification_padding', '.group--wrapper h5', '0', '0', '0', '0', 'false');
		$this->set_margin('group_wrapper_title_specification_margin', '.group--wrapper h5', '0', '0', '20', '0', 'false');
		$this->set_border_radius('group_wrapper_title_specification_border_radius', '0', '0', '0', '0', 'px', '.group--wrapper h5');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'group_wrapper_title_specification_box_shadow',
				'selector' => '{{WRAPPER}} .group--wrapper h5',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'group_wrapper_title_specification_border',
				'selector' => '{{WRAPPER}} .group--wrapper h5',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'group_wrapper_container_specification_tab',
			[
				'label' => __( 'Container', 'lisfinity-core' ),
			]
		);
		$this->set_background_color('group_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__('Background Color', 'lisfinity-core'), '.group--wrapper');
		$this->set_padding('group_wrapper_padding', '.group--wrapper', '0', '0', '0', '0', 'false');
		$this->set_margin('group_wrapper_margin', '.group--wrapper', '48', '0', '0', '0', 'false');
		$this->set_border_radius('group_wrapper_border_radius', '0', '0', '0', '0', 'px', '.group--wrapper');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'group_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .group--wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'group_wrapper_border',
				'selector' => '{{WRAPPER}} .group--wrapper',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	public function single_value_style() {

		$this->add_group_control(
			Group_Control_Single_Product_Specification_Single_Value_Label_Typography::get_type(),
			[
				'name'     => 'single_value_label_specification_typography',
				'selector' => '{{WRAPPER}} .single-value span.font-light.text-grey-700, {{WRAPPER}} .single-value-full span.font-light.text-grey-700',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Specification_Single_Value_Typography::get_type(),
			[
				'name'     => 'single_value_specification_typography',
				'selector' => '{{WRAPPER}} .single-value span.font-semibold, {{WRAPPER}} .single-value-full span.font-semibold',
			]
		);

		$this->set_background_color('single_value_specification_bg_color', 'transparent', esc_html__('Background Color Of the Odd fields', 'lisfinity-core'), '.single-value');
		$this->set_background_color('single_value_specification_bg_color_full', 'rgba(246, 246, 246, 1)', esc_html__('Background Color Of the Even Fields', 'lisfinity-core'), '.single-value-full');
		$this->set_padding('single_value_specification_padding', '.single-value, {{WRAPPER}} .single-value-full', '10', '10', '10', '10', 'false');
		$this->set_margin('single_value_specification_margin', '.single-value, {{WRAPPER}} .single-value-full', '0', '0', '0', '0', 'false');
		$this->set_border_radius('single_value_specification_border_radius', '0', '0', '0', '0', 'px', '.single-value, {{WRAPPER}} .single-value-full');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'single_value_specification_box_shadow',
				'selector' => '{{WRAPPER}} .single-value, {{WRAPPER}} .single-value-full',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'single_value_specification_border',
				'selector' => '{{WRAPPER}} .single-value, {{WRAPPER}} .single-value-full',
			]
		);

		$this->break_into_columns('single_value_specification_columns', '.single-value, {{WRAPPER}} .single-value-full', '2', '2%');


	}

	public function multiple_value_style() {

		$this->add_group_control(
			Group_Control_Single_Product_Specification_Multiple_Value_Subtitle_Typography::get_type(),
			[
				'name'     => 'multiple_value_subtitle_specification_typography',
				'selector' => '{{WRAPPER}} .multiple-value h6',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Specification_Multiple_Value_Typography::get_type(),
			[
				'name'     => 'multiple_value_specification_typography',
				'selector' => '{{WRAPPER}} .multiple-value .mb-8.w-full ',
			]
		);

		$this->set_background_color('multiple_value_specification_bg_color', 'transparent', esc_html__('Background Color', 'lisfinity-core'), '.multiple-value');
		$this->set_padding('multiple_value_specification_padding', '.multiple-value', '10', '10', '10', '10', 'false');
		$this->set_margin('multiple_value_specification_margin', '.multiple-value', '0', '0', '0', '0', 'false');
		$this->set_border_radius('multiple_value_specification_border_radius', '0', '0', '0', '0', 'px', '.multiple-value');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'multiple_value_specification_box_shadow',
				'selector' => '{{WRAPPER}} .multiple-value',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'multiple_value_specification_border',
				'selector' => '{{WRAPPER}} .multiple-value',
			]
		);

		$this->break_into_columns('multiple_value_specification_columns', '.multiple-value .flex.flex-wrap span', '3', '2%');

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

		include lisfinity_get_template_part( 'product-specification', 'shortcodes/product-single', $args );
	}

}
