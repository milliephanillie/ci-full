<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Title_Typography;

class Product_Financing_Calculator_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-financing-calculator';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Financing Calculator', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-calculator';
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
			'financing_calculator_wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->wrapper_style();

		$this->end_controls_section();

		// Title.
		$this->start_controls_section(
			'financing_calculator_title',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->title_style();
		$this->end_controls_section();

		// Title.
		$this->start_controls_section(
			'financing_calculator_label',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->label_style();
		$this->end_controls_section();

		// Input Fields.
		$this->start_controls_section(
			'financing_calculator_input_fields',
			[
				'label' => __( 'Input Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->input_fields_style();
		$this->end_controls_section();

		// Select Fields.
		$this->start_controls_section(
			'financing_calculator_select_fields',
			[
				'label' => __( 'Select Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->select_fields_style();
		$this->end_controls_section();

		// Button.
		$this->start_controls_section(
			'financing_calculator_button',
			[
				'label' => __( 'Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->button_style();
		$this->end_controls_section();

		// Calculator Result.
		$this->start_controls_section(
			'financing_calculator_result',
			[
				'label' => __( 'Calculator Result', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->calculator_result_style();
		$this->end_controls_section();
	}

	public function wrapper_style() {
		$this->set_background_color( 'calculator_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.profile--calculator' );
		$this->set_padding( 'calculator_wrapper_padding', '.profile--calculator', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'calculator_wrapper_margin', '.profile--calculator', '30', '0', '0', '0', 'false' );
		$this->set_border_radius( 'calculator_wrapper_border_radius', '3', '3', '3', '3', 'px', '.profile--calculator' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'calculator_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .profile--calculator',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'calculator_wrapper_border',
				'selector' => '{{WRAPPER}} .profile--calculator',
			]
		);
	}

	public function title_style() {
		$this->display_element( 'title_display', esc_html__( 'Display Title', 'lisfinity-core' ) );
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_title_typography',
				'selector'       => '{{WRAPPER}} #calculator-title',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '18' ]
					],
					'font_weight' => [
						'default' => '700'
					],
				],
			]
		);
	}

	public function label_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_label_typography',
				'selector'       => '{{WRAPPER}} .calculator label',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(76, 76, 76, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_padding( 'label_padding', '.calculator label', '0', '0', '0', '0', true );
		$this->set_margin( 'label_margin', '.calculator label', '2', '0', '0', '0', false );
	}

	public function input_fields_style() {
		$this->set_background_color( 'fields_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--fields' );
		$this->set_padding( 'fields_padding', '.calculator--fields', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'fields_margin', '.calculator--fields', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'fields_border_radius', '3', '3', '3', '3', 'px', '.calculator--fields' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'fields_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--fields',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'fields_border',
				'selector'       => '{{WRAPPER}} .calculator--fields',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(239, 239, 239, 1)' ]

				]
			]
		);
	}

	public function select_fields_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_fields_select_render_typography',
				'selector'       => '{{WRAPPER}} .calculator--select .css-dvua67-singleValue',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(38, 38, 38, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '400'
					],
				],
			]
		);
		$this->set_background_color( 'select_fields_default_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--select' );
		$this->set_padding( 'select_fields_default_padding', '.calculator--select', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'select_fields_default_margin', '.calculator--select', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'select_fields_default_border_radius', '3', '3', '3', '3', 'px', '.calculator--select, {{WRAPPER}} .css-bg1rzq-control' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'select_fields_default_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--select',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'select_fields_default_border',
				'selector'       => '{{WRAPPER}} .calculator--select',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(239, 239, 239, 1)' ]

				]
			]
		);
		$this->start_controls_tabs(
			'fields_tabs',
			[
				'label' => __( 'Tabs Content', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"select_items_default_tab",
			[
				'label' => __( 'Select Item Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_fields_select_items_typography',
				'selector'       => '{{WRAPPER}} .calculator--select .css-fk865s-option',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(38, 38, 38, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '400'
					],
				],
			]
		);
		$this->set_background_color( 'select_fields_item_default_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--select .css-fk865s-option' );
		$this->set_padding( 'select_fields_item_default_padding', '.calculator--select .css-fk865s-option', '8', '12', '8', '12', 'false' );
		$this->set_margin( 'select_fields_item_default_margin', '.calculator--select .css-fk865s-option', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'select_fields_item_default_border_radius', '3', '3', '3', '3', 'px', '.calculator--select .css-fk865s-option' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'select_fields_item_default_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--select .css-fk865s-option',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'select_fields_item_default_border',
				'selector'       => '{{WRAPPER}} .calculator--select .css-fk865s-option',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0'
						]
					],
					'color'  => [ 'default' => 'rgba(239, 239, 239, 1)' ]

				]
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			"select_tab_hover",
			[
				'label' => __( 'Select Item On Hover', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_fields_select_items_hover_typography',
				'selector'       => '{{WRAPPER}} .calculator--select .css-dpec0i-option',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(38, 38, 38, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '400'
					],
				],
			]
		);
		$this->set_background_color( 'select_fields_item_hover_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--select .css-dpec0i-option ' );
		$this->set_padding( 'select_fields_item_hover_padding', '.calculator--select .css-dpec0i-option', '8', '12', '8', '12', 'false' );
		$this->set_margin( 'select_fields_item_hover_margin', '.calculator--select .css-dpec0i-option', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'select_fields_item_hover_border_radius', '3', '3', '3', '3', 'px', '.calculator--select .css-dpec0i-option' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'select_fields_item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--select .css-dpec0i-option',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'select_fields_item_hover_border',
				'selector'       => '{{WRAPPER}} .calculator--select .css-dpec0i-option',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(239, 239, 239, 1)' ]

				]
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			"select_tab_active",
			[
				'label' => __( 'Select Item Active', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_fields_select_items_active_typography',
				'selector'       => '{{WRAPPER}} .calculator--select .css-xo7z33-option',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(38, 38, 38, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '400'
					],
				],
			]
		);
		$this->set_background_color( 'select_fields_item_active_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--select .css-xo7z33-option' );
		$this->set_padding( 'select_fields_item_active_padding', '.calculator--select .css-xo7z33-option', '8', '12', '8', '12', 'false' );
		$this->set_margin( 'select_fields_item_active_margin', '.calculator--select .css-xo7z33-option', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'select_fields_item_active_border_radius', '3', '3', '3', '3', 'px', '.calculator--select .css-xo7z33-option' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'select_fields_item_active_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--select .css-xo7z33-option',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'select_fields_item_active_border',
				'selector'       => '{{WRAPPER}} .calculator--select .css-xo7z33-option',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(239, 239, 239, 1)' ]

				]
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function button_style() {
		$this->display_element( 'button_text_display', esc_html__( 'Hide Button Text', 'lisfinity-core' ) );
		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Calculate', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_control(
			'place_icon',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_url',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon' => 'yes',
				]
			]
		);

		$this->display_element( 'button_icon_display', esc_html__( 'Hide Button Icon', 'lisfinity-core' ) );

		$this->start_controls_tabs(
			'button_tabs',
			[
				'label' => __( 'Tabs Content', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"button_default_tab",
			[
				'label' => __( 'Button Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_button_default_typography',
				'selector'       => '{{WRAPPER}} .calculator--button',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => '700'
					],
				],
			]
		);
		$this->add_control(
			'button_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 16,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .calculator-button-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .calculator-button-icon' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => '6'
				],
				'selectors' => [
					'{{WRAPPER}} .calculator-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_background_color( 'button_default_bg_color', 'rgba(9, 103, 210, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--button' );
		$this->set_padding( 'button_default_padding', '.calculator--button', '10', '20', '10', '20', 'false' );
		$this->set_margin( 'button_default_margin', '.calculator--button', '10', '0', '0', '0', 'false' );
		$this->set_border_radius( 'button_default_border_radius', '3', '3', '3', '3', 'px', '.calculator--button' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'button_default_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--button',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'button_default_border',
				'selector'       => '{{WRAPPER}} .calculator--button',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(9, 103, 210, 1)' ]

				]
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			"button_tab_hover",
			[
				'label' => __( 'Button On Hover', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_button_hover_typography',
				'selector'       => '{{WRAPPER}} .calculator--button:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => '700'
					],
				],
			]
		);
		$this->add_control(
			'button_hover_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 16,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .calculator--button:hover .calculator-button-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_hover_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .calculator--button:hover .calculator-button-icon' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'button_hover_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => '6'
				],
				'selectors' => [
					'{{WRAPPER}} .calculator--button:hover .calculator-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_background_color( 'button_hover_bg_color', 'rgba(3, 68, 158, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--button:hover ' );
		$this->set_padding( 'button_hover_padding', '.calculator--button:hover', '10', '20', '10', '20', 'false' );
		$this->set_margin( 'button_hover_margin', '.calculator--button:hover', '10', '0', '0', '0', 'false' );
		$this->set_border_radius( 'button_hover_border_radius', '3', '3', '3', '3', 'px', '.calculator--button:hover' );
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--button:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'button_hover_border',
				'selector'       => '{{WRAPPER}} .calculator--button:hover',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(3, 68, 158, 1)' ]

				]
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function calculator_result_style() {
		$this->start_controls_tabs(
			'result_tabs',
			[
				'label' => __( 'Tabs Content', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"result_label_tab",
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_result_label_typography',
				'selector'       => '{{WRAPPER}} .calculator--result .calculator-result-label',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '700'
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"result_price_tab",
			[
				'label' => __( 'Price', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'           => 'single_product_calculator_result_price_typography',
				'selector'       => '{{WRAPPER}} .calculator--result .calculator--result-price',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '400'
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->set_elements_alignment( 'calculator_result_alignment', 'left', '.calculator--result .calculator--result-price', false );
		$this->set_background_color( 'calculator_result_bg_color', 'rgba(230, 246, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.calculator--result' );
		$this->set_padding( 'calculator_result_padding', '.calculator--result', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'calculator_result_margin', '.calculator--result', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'calculator_result_border_radius', '3', '3', '3', '3', 'px', '.calculator--result' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'calculator_result_box_shadow',
				'selector' => '{{WRAPPER}} .calculator--result',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'calculator_result_border',
				'selector' => '{{WRAPPER}} .calculator--result',
			]
		);
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

		include lisfinity_get_template_part( 'product-financing-calculator', 'shortcodes/product-single', $args );
	}

}
