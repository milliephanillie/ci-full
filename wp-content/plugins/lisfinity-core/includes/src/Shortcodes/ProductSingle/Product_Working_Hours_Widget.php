<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;

class Product_Working_Hours_Widget extends Shortcode
{

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name()
	{
		return 'product-working-hours';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title()
	{
		return sprintf(__('%s Product Working Hours', 'lisfinity-core'), '<strong>Lisfinity > </strong>');
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon()
	{
		return 'fas fa-sort-numeric-up';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories()
	{
		return ['lisfinity-single-product'];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls()
	{
		// Category feeds.
		$this->start_controls_section(
			'text_label',
			[
				'label' => __('Title', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->start_controls_tabs(
			'label_tabs',
			[
				'label' => __('Tabs Label', 'lisfinity-core'),
			]
		);
		$this->start_controls_tab(
			"icon_tab",
			[
				'label' => __('Icon', 'lisfinity-core'),
			]
		);
		$this->add_control(
			'remove_icon_action',
			[
				'label' => __('Remove icon', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'lisfinity-core'),
				'label_off' => __('Hide', 'lisfinity-core'),
				'return_value' => 'yes',
				'default' => '',

			]
		);

		$this->add_control(
			'place_icon_action',
			[
				'label' => __('Use different icon', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'lisfinity-core'),
				'label_off' => __('No', 'lisfinity-core'),
				'return_value' => 'yes',
				'default' => '',

			]
		);

		$this->add_control(
			'selected_icon_action',
			[
				'label' => __('Icon', 'elementor'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition' => [
					'place_icon_action' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'lisfinity-core'),
				'type' => Controls_Manager::COLOR,
				'default' => '#0967d2',
				'selectors' => [
					"{{WRAPPER}} .working-hour-icon, {{WRAPPER}} .working-hour-icon svg" => "fill: {{VALUE}}; color: {{VALUE}};"
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __('Icon Size', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '16',
				],
				'selectors' => [
					"{{WRAPPER}} .working-hour-icon, {{WRAPPER}} .working-hour-icon svg" => 'width: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			"label_tab",
			[
				'label' => __('Label', 'lisfinity-core'),
			]
		);

		$this->add_control(
			'label_text',
			[
				'label' => __('Text', 'elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __('Work time:', 'elementor'),
				'placeholder' => __('Click here', 'elementor'),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name' => 'single_product_work_time_label_typography',
				'selector' => '{{WRAPPER}} .work-time-label',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'color' => [
						'default' => 'rgba(76, 76, 76, 1)'
					],
					'font_size' => [
						'default' =>
							['size' => 14]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"background_tab",
			[
				'label' => __('Background', 'lisfinity-core'),
			]
		);
		$this->set_background_color('background_color', '#f6f6f6', esc_html__('Background Color', 'lisfinity-core'), '.profile--widget-title');
		$this->set_border_radius('background_radius', '3', '3', '3', '3', 'px', '.profile--widget-title');
		$this->set_padding('background_padding', '.profile--widget-title', '14', '20', '14', '20', false);
		$this->set_margin('background_margin', '.profile--widget-title', '0', '0', '20', '-20', false);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'hours',
			[
				'label' => __('Work Hours', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->start_controls_tabs(
			'hours_tabs',
			[
				'label' => __('Tabs Hours', 'lisfinity-core'),
			]
		);
		$this->start_controls_tab(
			"open_label_tab",
			[
				'label' => __('Open Label', 'lisfinity-core'),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name' => 'open_label_typography',
				'selector' => '{{WRAPPER}} .open-label',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'color' => [
						'default' => 'rgba(25, 148, 115, 1)'
					],
					'font_size' => [
						'default' =>
							['size' => 14]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"closed_label_tab",
			[
				'label' => __('Closed Label', 'lisfinity-core'),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name' => 'closed_label_typography',
				'selector' => '{{WRAPPER}} .closed-label',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'color' => [
						'default' => 'rgba( 225, 45, 57, 1)'
					],
					'font_size' => [
						'default' =>
							['size' => 14]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"days_label_tab",
			[
				'label' => __('Days Label', 'lisfinity-core'),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name' => 'days_label_typography',
				'selector' => '{{WRAPPER}} .days-label',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'color' => [
						'default' => 'rgba( 45, 45, 45, 1)'
					],
					'font_size' => [
						'default' =>
							['size' => 14]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"time_label_tab",
			[
				'label' => __('Time', 'lisfinity-core'),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name' => 'time_typography',
				'selector' => '{{WRAPPER}} .time',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'color' => [
						'default' => 'rgba( 9, 103, 210, 1)'
					],
					'font_size' => [
						'default' =>
							['size' => 14]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$args = [
			'settings' => $settings,
		];

		include lisfinity_get_template_part('product-working-hours', 'shortcodes/product-single', $args);
	}

}
