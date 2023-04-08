<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Breadcrumbs_Active_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;

class Product_Breadcrumbs_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-breadcrumbs';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Breadcrumbs', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-home';
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
			'breadcrumbs',
			[
				'label' => __( 'Breadcrumbs Links', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'breadcrumbs_align',
			[
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'breadcrumbs' => __( 'Left', 'elementor' ),
						'icon'        => 'eicon-text-align-left',
					],
					'center'     => [
						'breadcrumbs' => __( 'Center', 'elementor' ),
						'icon'        => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'breadcrumbs' => __( 'Right', 'elementor' ),
						'icon'        => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-breadcrumbs ul.flex' => 'justify-content:{{VALUE}};'
				]
			]
		);
		$this->start_controls_tabs(
			'breadcrumbs_tabs',
			[
				'label' => __( 'breadcrumbs_links_tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'breadcrumbs_default_links_tab',
			[
				'label' => __( 'Default Links', 'lisfinity-core' ),
			]
		);

		// control | heading.
		$this->add_control(
			'breadcrumbs_color',
			[
				'label'     => __( 'Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(45, 45, 45, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li a' => 'color:{{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_breadcrumbs_typography',
				'selector' => '{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li a',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'breadcrumbs_active_links_tab',
			[
				'label' => __( 'Active Link', 'lisfinity-core' ),
			]
		);

		// control | heading.
		$this->add_control(
			'breadcrumbs_color_active',
			[
				'label'     => __( 'Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(45, 45, 45, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li .font-semibold.text-grey-1100' => 'color:{{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Breadcrumbs_Active_Typography::get_type(),
			[
				'name'     => 'single_product_breadcrumbs_typography_active',
				'selector' => '{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li .font-semibold.text-grey-1100',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'breadcrumbs_hover_links_tab',
			[
				'label' => __( 'Hover Link', 'lisfinity-core' ),
			]
		);

		// control | heading.
		$this->add_control(
			'breadcrumbs_color_hover',
			[
				'label'     => __( 'Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(45, 45, 45, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li a:hover' => 'color:{{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_breadcrumbs_typography_hover',
				'selector' => '{{WRAPPER}} .elementor-product-breadcrumbs .search--breadcrumb li a:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'breadcrumbs_container',
			[
				'label' => __( 'Breadcrumbs Container', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'breadcrumbs_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-breadcrumbs' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
					'unit'     => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-product-breadcrumbs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_breadcrumbs_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-product-breadcrumbs',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_breadcrumbs_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-product-breadcrumbs',
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_padding',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
					'unit'     => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-product-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_margin',
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => - 10,
					'left'     => 0,
					'isLinked' => true,
					'unit'     => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-product-breadcrumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'breadcrumbs_icon',
			[
				'label' => __( 'Breadcrumbs Icon', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'place_icon_breadcrumbs',
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
			'icon_breadcrumbs',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_breadcrumbs' => 'yes',
				]
			]
		);

		$this->add_control(
			'breadcrumb_icon_size',

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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-breadcrumbs' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'breadcrumb_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(239, 78, 78, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-breadcrumbs' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'breadcrumb_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon-breadcrumbs' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->breadcrumb_page();

	}

	public function breadcrumb_page() {
		$this->start_controls_section(
			'search_page_structure',
			[
				'label' => __( 'Breadcrumb Structure', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'page_typography',
			[
				'label'     => __( 'Pages Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_pages',
				'selector'       => '{{WRAPPER}} .information strong',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 14,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#686868',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
			]
		);
		$this->add_control(
			'page_typography_current_heading',
			[
				'label'     => __( 'Current Page Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'pages_typography_current',
				'selector'       => '{{WRAPPER}} .information strong.current--page',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 14,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#262626',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
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

		include lisfinity_get_template_part( 'product-breadcrumbs', 'shortcodes/product-single', $args );
	}

}
