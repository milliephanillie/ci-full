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

class Navigation_Cart_Widget extends Widget_Base {

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
		return 'navigation-cart';
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
		return __( 'Lisfinity -> Cart Icon', 'lisfinity-core' );
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
		return 'fa fa-shopping-cart';
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
		return [ 'lisfinity-navigation' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize
	 * the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_parent_menu',
			[
				'label' => __( 'Settings', 'lisfinity-core' ),
			]
		);

		$this->cart_settings();

		$this->end_controls_section();
		$this->start_controls_section(
			'_navigation_cart',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_cart' => ''
				]
			]
		);
		$this->start_controls_tabs(
			'cart_icon_tabs',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'cart_icon_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
				'condition' => [
					'place_icon_cart' => ''
				]
			]
		);


		$this->cart_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_icon_active_tab',
			[
				'label'     => __( 'Active', 'lisfinity-core' ),
				'condition' => [
					'place_icon_cart' => ''
				]
			]
		);

		$this->navigation_cart_active();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_icon_on_hover_tab',
			[
				'label'     => __( 'On hover', 'lisfinity-core' ),
				'condition' => [
					'place_icon_cart' => ''
				]
			]
		);

		$this->navigation_cart_hover();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'cart_icon_upload_style',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_cart' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'cart_icon_upload_tabs',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'cart_icon_font_awesome_tab',
			[
				'label' => __( 'Uploaded Icon', 'lisfinity-core' ),
				'condition' => [
					'place_icon_cart' => 'yes'
				]
			]
		);


		$this->cart_icon_font_awesome_style();
		$this->cart_icon_font_awesome_hover();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_icon_svg_tab',
			[
				'label'     => __( 'Uploaded SVG', 'lisfinity-core' ),
				'condition' => [
					'place_icon_cart' => 'yes'
				]
			]
		);

		$this->cart_icon_svg_style();
		$this->cart_icon_svg_hover();

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'cart_badge_notification',
			[
				'label' => __( 'Badge Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->badge_notifications();

		$this->cart_icon_badge_notification_hover();


		$this->end_controls_section();
	}

	/*
	 * Header Icons Cart
	 * -------------------------
	 */

	public function cart_settings() {

		$this->add_control(
			'place_icon_cart',
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
			'selected_icon_cart',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_cart' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_align_cart',
			[
				'label'     => __( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
				'condition' => [
					'selected_icon_cart[value]!' => '',
					'place_icon_cart'            => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_indent_cart',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .login--icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'place_icon_cart' => 'yes'
				]
			]
		);
	}

	public function cart_style() {
		$this->icon_width( '#cart--wrapper svg', 'header_icons_cart_width', '24' );

		$this->icon_height( '#cart--wrapper svg', 'header_icons_cart_height', '24' );


		$this->add_control(
			'header_icons_cart_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .cart--icon' => 'fill: {{VALUE}}!important;',
				],
			]
		);


		$this->set_heading_section('heading_cart_position', esc_html__('Position', 'lisfinity-core'), 'hr_cart_position');

		$this->set_relative_element_position('cart_position_x', '0', 'cart_position_y', '0', '#cart--wrapper');

		$this->set_heading_section( 'cart_icon_bg_heading', esc_html__( 'Background', 'lisfinity-core' ), 'cart_icon_bg_hr' );

		$this->add_control(
			'cart_icon_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .cart--icon' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cart_icon_bg_size',

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
					'size' => 20,
				],
				'selectors'   => [
					'{{WRAPPER}} .cart--icon' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'cart_icon_bg_height',

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
					'size' => 24,
				],
				'selectors'   => [
					'{{WRAPPER}} .cart--icon' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'cart_icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 50,
					'right'    => 50,
					'bottom'   => 50,
					'left'     => 50,
					'isLinked' => true,
					'unit'     => '%'
				],
				'selectors'  => [
					'{{WRAPPER}} .cart--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

	}

	public function navigation_cart_hover() {

		$this->set_heading_section('notification_hover_heading_cart', esc_html__('Icon On hover', 'lisfinity-core'), 'notification_cart_hover_hr_cart' );

		$this->icon_width( '.cart--icon:hover', 'header_icons_cart_notification_hover_width', '24' );

		$this->icon_height( '.cart--icon:hover', 'header_icons_cart_notification_hover_height', '24' );

		$this->add_control(
			'header_icons_cart_notification_hover_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .cart--icon:hover' => 'fill: {{VALUE}}!important;',
				],
			]
		);
		$this->set_heading_section('notification_hover_bg_heading_cart', esc_html__('Background On hover', 'lisfinity-core'), 'notification_cart_hover_bg_hr' );

		$this->add_control(
			'notification_hover_bg_color_cart',
			[
				'label'       => __( 'Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'transparent',
				'selectors'   => [
					'{{WRAPPER}} .cart--icon:hover' => 'background-color:{{VALUE}};'
				],
			]
		);

		$this->add_control(
			'notification_cart_hover_bg_size',

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
					'size' => 24,
				],
				'selectors'   => [
					'{{WRAPPER}} .cart--icon:hover' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'notification_cart_hover_bg_height',

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
					'size' => 24,
				],
				'selectors'   => [
					'{{WRAPPER}} .cart--icon:hover' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'notification_cart_hover_border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 50,
					'right'    => 50,
					'bottom'   => 50,
					'left'     => 50,
					'isLinked' => true,
					'unit'     => '%'
				],
				'selectors'  => [
					'{{WRAPPER}} .cart--icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
	}

	public function navigation_cart_active() {
		$this->set_heading_section('notification_cart_active_heading', esc_html__('Icon Active', 'lisfinity-core'), 'notification_cart_active_hr' );

		$this->icon_width( '#cart--wrapper.btn-active .w-20.h-20.fill-icon-reset.pointer-events-none', 'header_cart_icons_notification_active_width', '24' );

		$this->icon_height( '#cart--wrapper.btn-active .w-24.h-20.fill-icon-reset.pointer-events-none', 'header_cart_icons_notification_active_height', '24' );

		$this->add_control(
			'header_cart_icons_notification_active_color',
			[
				'label'       => __( 'Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'selectors'   => [
					'{{WRAPPER}} #cart--wrapper.btn-active .w-20.h-20.fill-icon-reset.pointer-events-none' => 'color:{{VALUE}};'
				]
			]
		);

		$this->set_heading_section('notification_cart_active_bg_heading', esc_html__('Background Active', 'lisfinity-core'), 'notification_cart_active_bg_hr' );

		$this->add_control(
			'notification_cart_active_bg_color',
			[
				'label'       => __( 'Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'transparent',
				'selectors'   => [
					'{{WRAPPER}} #cart--wrapper.btn-active' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'notification_cart_active_bg_size',

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
					'size' => 20,
				],
				'selectors'   => [
					'{{WRAPPER}} #cart--wrapper.btn-active' => 'width: {{SIZE}}{{UNIT}}!important; ',
				],
			]
		);

		$this->add_control(
			'notification_cart_active_bg_height',

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
					'size' => 20,
				],
				'selectors'   => [
					'{{WRAPPER}} #cart--wrapper.btn-active' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'notification_cart_active_border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 50,
					'right'    => 50,
					'bottom'   => 50,
					'left'     => 50,
					'isLinked' => true,
					'unit'     => '%'
				],
				'selectors'  => [
					'{{WRAPPER}} #cart--wrapper.btn-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
	}

	public function cart_icon_svg_style() {


		$this->icon_width( '.cart--icon', 'cart_icon_svg_width', '24' );

		$this->icon_height( '.cart--icon', 'cart_icon_svg_height', '24' );

		$this->set_heading_section( 'cart_icon_svg_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_cart_icon_svg_position' );

		$this->set_relative_element_position( 'cart_icon_svg_position_x', '0', 'cart_icon_svg_position_y', '0', '.cart--icon' );

	}

	public function cart_icon_svg_hover() {

		$this->set_heading_section( 'hover_heading_svg_cart', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'hover_hr_svg_cart' );

		$this->icon_width( '.cart_icon:hover', 'cart_icon_svg_hover_width', '24' );

		$this->icon_height( '.cart_icon:hover', 'cart_icon_svg_hover_height', '24' );

	}

	public function cart_icon_font_awesome_style() {

		$this->set_text_color( 'cart_icon_font_awesome_color', 'rgba(25, 148, 115, 1)', '.cart--icon' );

		$this->set_font_size( 'cart_icon_font_awesome_width', '24', '.cart--icon' );

		$this->set_heading_section( 'cart_icon_font_awesome_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_cart_icon_font_awesome_position' );

		$this->set_relative_element_position( 'cart_icon_font_awesome_position_x', '0', 'cart_icon_font_awesome_position_y', '0', '.cart--icon' );

	}

	public function cart_icon_font_awesome_hover() {

		$this->set_heading_section( 'hover_heading_font_awesome_cart', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'hover_hr_font_awesome_cart' );

		$this->set_text_color( 'cart_icon_font_awesome_color_hover', 'rgba(25, 148, 115, 1)', '.cart--icon:hover' );

		$this->set_font_size( 'cart_icon_font_awesome_width_hover', '24', '.cart--icon:hover' );

	}

	/*
	 * Header Icon Cart Badge
	 * -------------------------
	 */

	public function badge_notifications() {

		$this->set_text_color( 'cart_badge_text_color', 'rgba(38, 38, 38, 1)', '#cart--wrapper .cart-count' );

		$this->set_font_size( 'cart_badge_text_size', '10', '#cart--wrapper .cart-count' );

		$this->set_background_color( 'cart_badge_bg_color', 'rgba(239, 239, 239, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '#cart--wrapper .cart-count' );

		$this->icon_width( '#cart--wrapper .cart-count', 'cart_badge_bg_width', '16' );

		$this->icon_height( '#cart--wrapper .cart-count', 'cart_badge_bg_height', '16' );

		$this->set_border_radius( 'cart_badge_border_radius', '50', '50', '50', '50', 'true', '%', '#cart--wrapper .cart-count' );

		$this->set_heading_section('cart_badge_position_heading', esc_html__('Position', 'lisfinity-core'), 'cart_badge_position_hr' );

		$this->set_element_position( 'cart_badge_position_x', '-8', 'cart_badge_position_y', '3', '#cart--wrapper .cart-count' );

	}

	public function cart_icon_badge_notification_hover() {

		$this->set_heading_section('cart_hover_heading', esc_html__('On hover', 'lisfinity-core'), 'cart_hover_hr');

		$this->set_text_color( 'cart_badge_hover_text_color', 'rgba(38, 38, 38, 1)', '#cart--wrapper .cart-count:hover' );

		$this->set_font_size( 'cart_badge_hover_text_size', '10', '#cart--wrapper .cart-count:hover' );

		$this->set_background_color( 'cart_badge_hover_bg_color', 'rgba(239, 239, 239, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '#cart--wrapper .cart-count:hover' );

		$this->icon_width( '#cart--wrapper .cart-count:hover', 'cart_badge_hover_bg_width', '16' );

		$this->icon_height( '#cart--wrapper .cart-count:hover', 'cart_badge_hover_bg_height', '16' );

		$this->set_border_radius( 'badge_hover_border_radius', '50', '50', '50', '50', 'true', '%', '#cart--wrapper .cart-count:hover' );

		$this->set_heading_section('cart_badge_hover_position_heading', esc_html__('Position', 'lisfinity-core'), 'cart_badge_hover_position_hr' );

		$this->set_element_position( 'cart_badge_hover_position_x', '-8', 'cart_badge_active_position_y', '3', '#cart--wrapper .cart-count:hover' );


	}


	/*
	 * Functions.
	 * -------------------------
	 */

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
						'max' => 900,
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
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'width: {{SIZE}}{{UNIT}}!important;',
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
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'height: {{SIZE}}{{UNIT}}!important;',
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

	public function set_relative_element_position( $id_x, $default_x, $id_y, $default_y, $selector ) {
		$this->add_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 990,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}!important;position: relative;',
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
						'min' => - 990,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}}!important; position: relative;',
				]
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
						'min' => - 990,
						'max' => 990,
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
						'min' => - 990,
						'max' => 990,
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
		include lisfinity_get_template_part( 'navigation-cart', 'shortcodes/navigation-menu', $args );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live
	 * preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		return '';
	}

}
