<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sidebar_Menu_Typography;

class Product_Mobile_Menu_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-mobile-menu';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Mobile Menu', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-bars';
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
		// Menu Wrapper.
		$this->start_controls_section(
			'scroll_menu_wrapper',
			[
				'label' => __( 'Menu Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->menu_wrapper();
		$this->end_controls_section();

		// Menu Items.
		$this->start_controls_section(
			'scroll_menu_items',
			[
				'label' => __( 'Menu Items', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->menu_items();
		$this->end_controls_section();

		// Scroll Button.
		$this->start_controls_section(
			'scroll_menu_button',
			[
				'label' => __( 'Scroll Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->scroll_button();
		$this->end_controls_section();


	}

	public function menu_wrapper() {
		$this->set_background_color( 'scroll_menu_wrapper_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.wrapper-menu-mobile' );
		$this->set_padding( 'scroll_menu_wrapper_padding', '.wrapper-menu-mobile', '16', '20', '16', '20', 'false' );
		$this->set_margin( 'scroll_menu_wrapper_margin', '.wrapper-menu-mobile', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'scroll_menu_wrapper_border_radius', '3', '3', '3', '3', 'px', '.wrapper-menu-mobile' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_menu_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .wrapper-menu-mobile',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'scroll_menu_wrapper_border',
				'selector' => '{{WRAPPER}} .wrapper-menu-mobile',
			]
		);
	}

	public function menu_items() {
		$this->start_controls_tabs(
			'scroll_mobile_menu_items_tabs',
			[
				'label' => __( 'Content Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'scroll_mobile_menu_item_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'scroll_mobile_menu_items_typography',
				'selector' => '{{WRAPPER}} .menu-mobile-link',
			]
		);
		$this->set_background_color( 'scroll_mobile_menu_item_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.menu-mobile-link' );
		$this->set_padding( 'scroll_mobile_menu_item_padding', '.menu-mobile-link', '4', '10', '4', '10', 'false' );
		$this->set_margin( 'scroll_mobile_menu_item_margin', '.menu-mobile-link', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'scroll_mobile_menu_item_border_radius', '3', '3', '3', '3', 'px', '.menu-mobile-link' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_mobile_menu_item_box_shadow',
				'selector' => '{{WRAPPER}} .menu-mobile-link',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'scroll_mobile_menu_item_border',
				'selector' => '{{WRAPPER}} .menu-mobile-link',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'scroll_mobile_menu_item_hover_tab',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'scroll_mobile_menu_items_typography_hover',
				'selector' => '{{WRAPPER}} a.menu-mobile-link:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
					'text_decoration' => [
						'default' => 'none'
					]
				],
			]
		);
		$this->set_background_color( 'scroll_mobile_menu_item_bg_color_hover', 'rgba(255, 243, 196, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.menu-mobile-link:hover' );
		$this->set_padding( 'scroll_mobile_menu_item_padding_hover', '.menu-mobile-link:hover', '4', '10', '4', '10', 'false' );
		$this->set_margin( 'scroll_mobile_menu_item_margin_hover', '.menu-mobile-link:hover', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'scroll_mobile_menu_item_border_radius_hover', '3', '3', '3', '3', 'px', '.menu-mobile-link:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_mobile_menu_item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .menu-mobile-link:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'scroll_mobile_menu_item_border_hover',
				'selector' => '{{WRAPPER}} .menu-mobile-link:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	public function scroll_button() {
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
					'size' => 24,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .scroll-button-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .scroll-button-icon' => 'fill: {{VALUE}};color: {{VALUE}};',
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
					'size' => '10'
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
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

		include lisfinity_get_template_part( 'product-mobile-menu', 'shortcodes/product-single', $args );
	}

}
