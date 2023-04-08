<?php

namespace Lisfinity\Shortcodes;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Items_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Navigation_Widget extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'navigation-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Theme Navigation Menu', 'lisfinity-core' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'fa fa-bars';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'lisfinity', 'lisfinity-navigation' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_parent_menu',
			[
				'label' => __( 'Parent Menu', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'nav_menu',
			[
				'label'    => __( 'Navigation Menu', 'lisfinity-core' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options'  => $this->_get_menus(),
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_position',
			[
				'label' => __( 'Navigation position', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->navigation_position();
		$this->end_controls_section();

		$this->start_controls_section(
			'_header_taxonomies_style',
			[
				'label' => __( 'Menu Item style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->header_menu_items_style();
		$this->end_controls_section();

//		$this->start_controls_section(
//			'_header_button_submit',
//			[
//				'label' => __( 'Button', 'lisfinity-core' ),
//				'tab'   => Controls_Manager::TAB_STYLE,
//			]
//		);
//		$this->header_button_submit();
//		$this->end_controls_section();
//
//		// todo - should be added through react in order to disable it.
//		/*		$this->start_controls_section(
//					'_header_avatar',
//					[
//						'label' => __( 'Avatar', 'lisfinity-core' ),
//						'tab'   => Controls_Manager::TAB_STYLE,
//					]
//				);
//				$this->header_avatar();
//				$this->end_controls_section(); */
//
//		$this->start_controls_section(
//			'_header_icons',
//			[
//				'label' => __( 'Icons', 'lisfinity-core' ),
//				'tab'   => Controls_Manager::TAB_STYLE,
//			]
//		);
//		$this->start_controls_tabs(
//			'_header_icons_tabs'
//		);
//
//		// todo - should be added through react.
//		/*$this->start_controls_tab(
//			'_header_icons_cart',
//			[
//				'label' => __( 'Cart', 'lisfinity-core' )
//			]
//		);
//
//		$this->header_icons_cart();
//		$this->end_controls_tab();*/
//
//		$this->start_controls_tab(
//			'_header_icons_notification',
//			[
//				'label' => __( 'Notification', 'lisfinity-core' )
//			]
//		);
//
//		$this->header_icons_notifications();
//		$this->end_controls_tab();
//		$this->end_controls_tabs();
//		$this->end_controls_section();
	}


	/*
	 * Navigation position
	 * -------------------------
	 */

	public function navigation_position() {
		$this->set_elements_alignment( 'navigation_positioning', 'flex-end', '.menu--lisfinity' );
	}

	/*
	 * Menu Items Style
	 * -------------------------
	 */

	public function header_menu_items_style() {

		$this->add_group_control(
			Group_Control_Navigation_Items_Typography::get_type(),
			[
				'name'     => 'menu_item_typography',
				'selector' => '{{WRAPPER}} .menu-item.menu-item-type-post_type.menu-item-object-page a',
			]
		);

		$this->set_text_color( 'menu_item_text_color', 'rgba(255, 255, 255, 1)', '.menu-item.menu-item-type-post_type.menu-item-object-page a' );

		$this->set_padding( 'menu_item_padding', '.menu-item.menu-item-type-post_type.menu-item-object-page', '0', '16', '0', '16', 'true' );

		$this->set_margin( 'menu_item_margin', '.menu-item.menu-item-type-post_type.menu-item-object-page', '0', '0', '0', '0', 'true' );

		$this->set_heading_section( 'header_submenu_heading_id', esc_html__( 'Submenu', 'lisfinity-core' ), 'header_submenu_hr_id' );

		$this->add_group_control(
			Group_Control_Navigation_Submenu_Border::get_type(),
			[
				'name'     => 'submenu_border',
				'selector' => '{{WRAPPER}} .sub-menu-wrapper',
			]
		);

		$this->set_background_color( 'header_submenu_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.sub-menu-wrapper' );

		$this->set_border_radius( 'header_submenu_border_radius', '3', '3', '3', '3', 'true', 'px', '.sub-menu-wrapper' );

		$this->set_padding( 'header_submenu_padding', '.sub-menu-wrapper', '12', '0', '12', '0', 'true' );

		$this->set_heading_section( 'header_submenu_item_heading_id', esc_html__( 'Submenu Item', 'lisfinity-core' ), 'header_submenu_item_hr_id' );

		$this->add_group_control(
			Group_Control_Navigation_Submenu_Item_Typography::get_type(),
			[
				'name'     => 'submenu_item_typography',
				'selector' => '{{WRAPPER}} .sub-menu-wrapper .menu-item a',
			]
		);


		$this->add_control(
			'header_submenu_item_text_color',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(76, 76, 76, 1)',
				'selectors' => [
					'{{WRAPPER}} .sub-menu-wrapper .menu-item a' => 'color:{{VALUE}}!important;'
				],
			]
		);

		$this->set_background_color( 'header_submenu_item_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.sub-menu-wrapper .menu-item' );

		$this->add_group_control(
			Group_Control_Navigation_Submenu_Item_Border::get_type(),
			[
				'name'     => 'submenu_item_border',
				'selector' => '{{WRAPPER}} .sub-menu-wrapper .menu-item',
			]
		);

		$this->set_border_radius( 'header_submenu_item_border_radius', '3', '3', '3', '3', 'true', 'px', '.sub-menu-wrapper .menu-item' );

		$this->set_padding( 'header_submenu_padding_value', '.sub-menu-wrapper .menu-item a', '16', '6', '16', '6', 'true' );

		$this->set_heading_section( 'header_submenu_item_heading_id_hover', esc_html__( 'Submenu Item on Hover', 'lisfinity-core' ), 'header_submenu_item_hr_id_hover' );

		$this->add_control(
			'header_submenu_item_text_color_hover',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(76, 76, 76, 1)',
				'selectors' => [
					'{{WRAPPER}} .sub-menu-wrapper .menu-item a:hover' => 'color:{{VALUE}}!important;'
				],
			]
		);

		$this->set_background_color( 'header_submenu_item_bg_color_hover', 'rgba(239, 239, 239, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.sub-menu-wrapper .menu-item:hover' );

		$this->add_group_control(
			Group_Control_Navigation_Submenu_Item_Border::get_type(),
			[
				'name'     => 'submenu_item_border_hover',
				'selector' => '{{WRAPPER}} .sub-menu-wrapper .menu-item:hover',
			]
		);

		$this->set_border_radius( 'header_submenu_border_radius_hover', '3', '3', '3', '3', 'true', 'px', '.sub-menu-wrapper .menu-item:hover' );


	}


	/*
	 * Button Style
	 * -------------------------
	 */

	public function header_button_submit() {

		$this->add_group_control(
			Group_Control_Navigation_Button_Typography::get_type(),
			[
				'name'     => 'header_button_typography',
				'selector' => '{{WRAPPER}} .menu-item.menu-item-submit .btn__load',
			]
		);
		$this->set_text_color( 'header_button_text_color', 'rgba(255, 255, 255, 1)', '.menu-item.menu-item-submit .btn__load' );

		$this->add_group_control(
			Group_Control_Navigation_Button_Border::get_type(),
			[
				'name'     => 'header_button_border',
				'selector' => '{{WRAPPER}} .menu-item.menu-item-submit .btn__load',
			]
		);

		$this->set_border_radius( 'header_button_border_radius_id', '3', '3', '3', '3', 'true', 'px', '.menu-item.menu-item-submit .btn__load' );

		$this->set_background_color( 'header_button_bg_color_id', 'rgba(68,68,68,.7)', 'Background Color', '.menu-item.menu-item-submit .btn__load' );

		$this->set_heading_section( 'header_button_icon_id', 'Button Icon', 'header_button_icon_hr_id' );

		$this->custom_icon( 'header_button_display_icon', 'header_button_icon_url' );

		$this->add_control(
			'header_button_icon_width_id',

			[
				'label'       => __( 'Icon Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 15,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .btn-submit-icon'                       => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .menu-item.menu-item-submit .btn__load' => 'padding: 0;'
				],
			]
		);

		$this->add_control(
			'header_button_icon_height_id',

			[
				'label'       => __( 'Icon Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 15,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .btn-submit-icon'                       => 'height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .menu-item.menu-item-submit .btn__load' => 'padding: 1px 1px 1px 1px;!important'
				],
			]
		);

		$this->add_control(
			'header_button_icon_color_id',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .btn-submit-icon' => 'fill: {{VALUE}}!important;',
				],
				'condition'   => [
					'header_button_display_icon' => '',

				]
			]
		);
		$this->set_heading_section( 'header_button_on_hover_id', 'On hover', 'header_button_on_hover_hr_id' );

		$this->add_group_control(
			Group_Control_Navigation_Button_Typography::get_type(),
			[
				'name'     => 'header_button_typography_hover',
				'selector' => '{{WRAPPER}} .menu-item.menu-item-submit .btn__load:hover',
			]
		);

		$this->set_text_color( 'header_button_color_id_on_hover', 'rgba(255, 255, 255, 1)', '.menu-item.menu-item-submit .btn__load:hover' );

		$this->add_group_control(
			Group_Control_Navigation_Button_Border::get_type(),
			[
				'name'     => 'header_button_border_on_hover',
				'selector' => '{{WRAPPER}} .menu-item.menu-item-submit .btn__load:hover',
			]
		);

		$this->set_border_radius( 'header_button_border_radius_id_on_hover', '3', '3', '3', '3', '3', 'px', '.menu-item.menu-item-submit .btn__load:hover' );
		$this->set_background_color( 'header_button_bg_color_id_on_hover', 'rgba(9, 103, 210, 1)', 'Background Color', '.menu-item.menu-item-submit .btn__load:hover' );

		$this->set_heading_section( 'header_button_heading_padding_id', 'Padding & Margin', 'header_button_padding_hr_id' );

		$this->set_padding( 'header_button_padding_id', '.menu-item.menu-item-submit .btn__load', '12', '30', '12', '30', 'true' );
		$this->set_margin( 'header_button_margin_id', '.menu-item.menu-item-submit .btn__load', '0', '0', '0', '0', 'true' );


	}





	/*
	 * Functions.
	 * -------------------------
	 */

	public function display_element( $id ) {
		$this->add_control(
			$id,
			[
				'label'        => __( 'Display', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
	}

	public function custom_icon( $id_place_icon, $id_icon_url ) {

		$this->add_control(
			$id_place_icon,
			[
				'label'        => __( 'Use Custom Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => true,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_responsive_control(
			$id_icon_url,
			[
				'label'       => __( 'Place icon', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
				'condition'   => [
					$id_place_icon => 'yes',
				],
			]
		);
	}

	public function icon_color( $icon_class, $id_icon_color, $default_color ) {

		$this->add_control(
			$id_icon_color,
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'fill: {{VALUE}}!important;',
				]
			]
		);
	}

	public function icon_width( $icon_class, $id_icon_size, $default_size ) {

		$this->add_control(
			$id_icon_size,

			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_size,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function icon_height( $icon_class, $id_icon_size, $default_size ) {

		$this->add_control(
			$id_icon_size,

			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_size,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_background_color( $id, $default_color, $message, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};'
				],
			]
		);
	}

	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			$hr_id,
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
	}

	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_boolean, $unit, $selector ) {
		$this->add_responsive_control(
			$id,
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
					'unit'     => $unit
				],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector ) {
		$this->add_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}!important;',
				]
			]
		);

		$this->add_control(
			$id_y,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}}!important;',
				]
			]
		);
	}


	public function set_text_color( $id, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [ "{{WRAPPER}} $selector" => 'color:{{VALUE}};' ],
			]
		);
	}

	public function set_font_size( $id, $default, $selector ) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Text Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_padding( $id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean ) {

		$this->add_control(
			$id,
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_margin( $id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean ) {

		$this->add_control(
			$id,
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_elements_alignment( $id, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( 'Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => $default,
				'toggle'      => true,
				'description' => __( 'Set alignment', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'justify-content: {{VALUE}};',
				],
			]
		);
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args     = [
			'settings' => $settings,
		];
		include lisfinity_get_template_part( 'navigation-menu', 'shortcodes/navigation-menu', $args );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		return '';
	}

	protected function _get_menus() {
		/*
			Get all menus available
		*/
		$menus        = get_terms( 'nav_menu' );
		$menus_select = [
			'' => 'Default Menu'
		];
		foreach ( $menus as $each_menu ) {
			$menus_select[ $each_menu->slug ] = $each_menu->name;
		}

		return $menus_select;
	}
}
