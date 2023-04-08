<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Actions_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Name_Typography;

class Product_Actions_Widget extends Shortcode {

	public $actions = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->actions = [
			'bookmark'   => esc_html__( 'Bookmark', 'lisfinity-core' ),
			'print'      => esc_html__( 'Print', 'lisfinity-core' ),
			'visits'     => esc_html__( 'Visits', 'lisfinity-core' ),
			'likes'      => esc_html__( 'Likes', 'lisfinity-core' ),
			'report'     => esc_html__( 'Report', 'lisfinity-core' ),
			'share'      => esc_html__( 'Share', 'lisfinity-core' ),
			'calculator' => esc_html__( 'Calculator', 'lisfinity-core' ),
			'compare'    => esc_html__( 'Compare', 'lisfinity-core' )
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-actions';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Actions', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-heart';
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
			'actions',
			[
				'label' => __( 'Actions', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->actions_content();
		$this->end_controls_section();

	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */

	public function actions_content() {
		$tab_repeater = new Repeater();

		$tab_repeater->add_control(
			'title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Action Title', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' )
			]
		);

		$tab_repeater->add_control(
			'actions',
			[
				'label'   => __( 'Action', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->actions,
			]
		);

		$tab_repeater->add_control(
			'display_action_text',
			[
				'label'        => __( 'Display Action Text', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],
				'condition'    => [
					'actions' => 'visits'
				]

			]
		);


		$tab_repeater->add_control(
			'display_modal',
			[
				'label'        => __( 'Display in modal', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'actions' => 'share'
				]
			]
		);

		$tab_repeater->add_control(
			'action_text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Action Text', 'lisfinity-core' ),
				'description' => __( 'Enter the text you wish to display', 'lisfinity-core' ),
				'condition'   => [
					'display_action_text' => 'yes'
				]
			]
		);

		$tab_repeater->add_control(
			'remove_icon_action',
			[
				'label'        => __( 'Remove icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'actions',
							'operator' => '===',
							'value'    => 'visits',
						],
						[
							'name'     => 'display_modal',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$tab_repeater->add_control(
			'place_icon_action',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'remove_icon_action',
							'operator' => '!==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_modal',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$tab_repeater->add_control(
			'selected_icon_action',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_action' => 'yes'
				]
			]
		);
		$tab_repeater->add_group_control(
			Group_Control_Single_Product_Owner_Name_Typography::get_type(),
			[
				'name'     => 'share_label_typography',
				'selector' => '{{WRAPPER}} .shares--label',
				'label' => 'Label Typography',
				'fields_options' => [
					'color' => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font-size' => [
						'default' => '18'
					],
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);

		$tab_repeater->add_responsive_control(
			'share_label_margin',
			[
				'label'       => __( 'Label Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .shares--label" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => (string) 0,
					'right'    => (string) 0,
					'bottom'   => (string) 10,
					'left'     => (string) 0,
					'isLinked' => false,
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);
		$tab_repeater->add_responsive_control(
			'share_label_padding',
			[
				'label'       => __( 'Label Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .shares--label" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => (string) 0,
					'right'    => (string) 30,
					'bottom'   => (string) 0,
					'left'     => (string) 30,
					'isLinked' => false,
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);

		$tab_repeater->add_responsive_control(
			'share_label_position_vertical',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 999,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'label' => __( 'Vertical Label Position', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .shares--label" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);
		$tab_repeater->add_responsive_control(
			'share_label_position_horizontal',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 999,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'label' => __( 'Horizontal Label Position', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .shares--label" => 'left: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition' => [
					'display_modal' => ''
				],
				'separator' => 'after'
			]
		);

		$tab_repeater->add_responsive_control(
			'share_icons_margin',
			[
				'label'       => __( 'Icons Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .shares" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => (string) 0,
					'right'    => (string) -4,
					'bottom'   => (string) -6,
					'left'     => (string) -4,
					'isLinked' => false,
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);
		$tab_repeater->add_responsive_control(
			'share_icons_padding',
			[
				'label'       => __( 'Icons Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .share" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => (string) 0,
					'right'    => (string) 30,
					'bottom'   => (string) 40,
					'left'     => (string) 30,
					'isLinked' => false,
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);


		$tab_repeater->add_responsive_control(
			'share_icons_position_vertical',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 999,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'label' => __( 'Vertical Icons Position', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .shares" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition' => [
					'display_modal' => ''
				]
			]
		);
		$tab_repeater->add_responsive_control(
			'share_icons_position_horizontal',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 999,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'label' => __( 'Horizontal Icons Position', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .shares" => 'left: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition' => [
					'display_modal' => ''
				],
				'separator' => 'after'
			]
		);



		$tab_repeater->start_controls_tabs(
			'tabs',
			[
				'label' => __( "Tabs Default", 'lisfinity-core' ),
				'condition'   => [
					'display_modal!' => ''
				],
			]
		);
		$tab_repeater->start_controls_tab(
			"tab_icon_default",
			[
				'label' => __( 'Icon Default', 'lisfinity-core' ),
			]
		);
		$tab_repeater->add_control(
			'action_icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions!' => [ 'calculator', 'compare' ]
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_calculator',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(240, 180, 41, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'calculator'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_compare',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9, 103, 210, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'compare'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_active',
			[
				'label'     => __( 'Icon Color Active', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(203, 110, 23, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .bookmarked-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'bookmark'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_active_2',
			[
				'label'     => __( 'Icon Color Active', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'likes'
				]
			]
		);

		$tab_repeater->add_control(
			'action_icon_width',
			[
				'label'      => __( 'Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 16,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->add_control(
			'action_icon_position',
			[
				'label'      => __( 'Icon Spacing', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 5,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->end_controls_tab();
		$tab_repeater->start_controls_tab(
			"tab_icon_hover",
			[
				'label' => __( 'Icon On Hover', 'lisfinity-core' ),
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions!' => [ 'calculator', 'compare' ]
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_calculator_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(240, 180, 41, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'calculator'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_compare_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9, 103, 210, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'compare'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_active_hover',
			[
				'label'     => __( 'Icon Color Active', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(203, 110, 23, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'bookmark'
				]
			]
		);
		$tab_repeater->add_control(
			'action_icon_color_active_hover_2',
			[
				'label'     => __( 'Icon Color Active', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'condition' => [
					'actions' => 'likes'
				]
			]
		);

		$tab_repeater->add_control(
			'action_icon_width_hover',
			[
				'label'      => __( 'Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 16,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);
		$tab_repeater->add_control(
			'action_icon_position_hover',
			[
				'label'      => __( 'Icon Spacing', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 5,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .product-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->end_controls_tab();
		$tab_repeater->end_controls_tabs();

		$tab_repeater->start_controls_tabs(
			'tabs_container',
			[
				'label' => __( "Tabs Default", 'lisfinity-core' ),
				'condition'   => [
					'display_modal!' => ''
				],
			]
		);
		$tab_repeater->start_controls_tab(
			"tab_container_default",
			[
				'label' => __( 'Box Default', 'lisfinity-core' ),
			]
		);

		$tab_repeater->add_control(
			'action_box_color',
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color:{{VALUE}};'
				],
			]
		);

		$tab_repeater->add_group_control(
			Group_Control_Single_Product_Actions_Typography::get_type(),
			[
				'name'     => 'typography_hover',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			]
		);

		$tab_repeater->add_control(
			'action_box_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(246, 246, 246, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color:{{VALUE}};'
				],
			]
		);

		$tab_repeater->add_control(
			'action_box_padding',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '4',
					'right'  => '10',
					'bottom' => '4',
					'left'   => '10',
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$tab_repeater->add_control(
			'action_box_margin',
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '10',
					'right'  => '4',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->add_control(
			'action_box_border',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '3',
					'right'  => '3',
					'bottom' => '3',
					'left'   => '3',
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->end_controls_tab();
		$tab_repeater->start_controls_tab(
			"tab_box_hover",
			[
				'label' => __( 'Box On Hover', 'lisfinity-core' ),
			]
		);
		$tab_repeater->add_control(
			'action_box_color_hover',
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'color:{{VALUE}}'
				],
			]
		);
		$tab_repeater->add_group_control(
			Group_Control_Single_Product_Actions_Typography::get_type(),
			[
				'name'     => 'typography_hover_2',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}:hover',
			]
		);

		$tab_repeater->add_control(
			'action_box_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 239, 239, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color:{{VALUE}}'
				],
			]
		);

		$tab_repeater->add_control(
			'action_box_padding_hover',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '4',
					'right'  => '10',
					'bottom' => '4',
					'left'   => '10',
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$tab_repeater->add_control(
			'action_box_margin_hover',
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '10',
					'right'  => '4',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$tab_repeater->add_control(
			'action_box_border_hover',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'top'    => '3',
					'right'  => '3',
					'bottom' => '3',
					'left'   => '3',
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);


		$tab_repeater->end_controls_tab();
		$tab_repeater->end_controls_tabs();


		$this->add_control(
			'actions_tabs',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $tab_repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'title_field'   => sprintf( __( 'Tab: %s', 'lisfinity-core' ), '{{{ actions }}}' ),
				'separator'     => 'before',
			]
		);

	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'settings' => $settings,
		];

		include lisfinity_get_template_part( 'product-actions', 'shortcodes/product-single', $args );
	}

}
