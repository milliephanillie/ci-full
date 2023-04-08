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

class Navigation_Login_Widget extends Widget_Base {

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
		return 'navigation-login';
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
		return __( 'Lisfinity -> Login Icon', 'lisfinity-core' );
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
		return 'fa fa-sign-in';
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
			'login_icon_settings',
			[
				'label' => __( 'Settings', 'lisfinity-core' ),
			]
		);

		$this->login_icon_settings();

		$this->end_controls_section();

		$this->start_controls_section(
			'login_icon_style',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_login' => ''
				]
			]
		);
		$this->start_controls_tabs(
			'login_icon_tabs',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'login_icon_default_tab',
			[
				'label'     => __( 'Default', 'lisfinity-core' ),
				'condition' => [
					'place_icon_login' => ''
				]
			]
		);


		$this->login_icon_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'login_icon_on_hover_tab',
			[
				'label'     => __( 'On hover', 'lisfinity-core' ),
				'condition' => [
					'place_icon_login' => ''
				]
			]
		);

		$this->login_icon_hover();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'login_icon_upload_style',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_login' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'login_icon_upload_tabs',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'login_icon_font_awesome_tab',
			[
				'label'     => __( 'Uploaded Icon', 'lisfinity-core' ),
				'condition' => [
					'place_icon_login' => 'yes'
				]
			]
		);


		$this->login_icon_font_awesome_style();
		$this->login_icon_font_awesome_hover();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'login_icon_svg_tab',
			[
				'label'     => __( 'Uploaded SVG', 'lisfinity-core' ),
				'condition' => [
					'place_icon_login' => 'yes'
				]
			]
		);

		$this->login_icon_svg_style();
		$this->login_icon_svg_hover();

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/*
	 * Login Icon Settings
	 * -------------------------
	 */

	public function login_icon_settings() {

		$link = get_permalink( lisfinity_get_page_id( 'page-login' ) );

		$this->add_control(
			'link_default_login',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://test', 'elementor' ),
				'default'     => [
					'url' => $link,
				],
			]
		);


		$this->add_control(
			'place_icon_login',
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
			'selected_icon_login',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_login' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_align_login',
			[
				'label'     => __( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
				'condition' => [
					'selected_icon_login[value]!' => '',
					'place_icon_login'            => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_indent_login',
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
					'place_icon_login' => 'yes'
				]
			]
		);
	}

	public function login_icon_style() {


		$this->icon_width( '.login--icon', 'login_icon_width', '16' );

		$this->icon_height( '.login--icon', 'login_icon_height', '16' );


		$this->add_control(
			'login_icon_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .login--icon' => 'fill: {{VALUE}}!important;',
				]
			]
		);


		$this->set_heading_section( 'login_icon_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_login_icon_position' );

		$this->set_element_position( 'login_icon_position_x', '0', 'login_icon_position_y', '0', '.login--icon' );

		$this->set_heading_section( 'login_icon_bg_heading', esc_html__( 'Background', 'lisfinity-core' ), 'login_icon_bg_hr' );

		$this->add_control(
			'login_icon_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .login--icon' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'login_icon_border_radius',
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
					'{{WRAPPER}} .login--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

	}

	public function login_icon_hover() {

		$this->icon_width( '.login--icon:hover', 'login_icon_hover_width', '16' );

		$this->icon_height( '.login--icon:hover', 'login_icon_hover_height', '16' );

		$this->add_control(
			'login_icon_hover_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .login--icon:hover' => 'fill: {{VALUE}}!important;',
				]
			]
		);
		$this->set_heading_section( 'login_icon_hover_bg_heading', esc_html__( 'Background On hover', 'lisfinity-core' ), 'login_icon_hover_bg_hr' );

		$this->add_control(
			'login_icon_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .login--icon:hover' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'login_icon_hover_border_radius',
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
					'{{WRAPPER}} .login--icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
	}

	public function login_icon_svg_style() {


		$this->icon_width( '.login--icon', 'login_icon_svg_width', '16' );

		$this->icon_height( '.login--icon', 'login_icon_svg_height', '16' );

		$this->set_heading_section( 'login_icon_svg_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_login_icon_svg_position' );

		$this->set_element_position( 'login_icon_svg_position_x', '0', 'login_icon_svg_position_y', '0', '.login--icon' );

	}

	public function login_icon_svg_hover() {

		$this->set_heading_section( 'hover_heading_svg_login', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'hover_hr_svg_login' );

		$this->icon_width( '.login_icon:hover', 'login_icon_svg_hover_width', '16' );

		$this->icon_height( '.login_icon:hover', 'login_icon_svg_hover_height', '16' );

	}

	public function login_icon_font_awesome_style() {

		$this->set_text_color( 'login_icon_font_awesome_color', 'rgba(25, 148, 115, 1)', '.login--icon' );

		$this->set_font_size( 'login_icon_font_awesome_width', '16', '.login--icon' );

		$this->set_heading_section( 'login_icon_font_awesome_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_login_icon_font_awesome_position' );

		$this->set_element_position( 'login_icon_font_awesome_position_x', '0', 'login_icon_font_awesome_position_y', '0', '.login--icon' );

	}

	public function login_icon_font_awesome_hover() {

		$this->set_heading_section( 'hover_heading_font_awesome_login', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'hover_hr_font_awesome_login' );

		$this->set_text_color( 'login_icon_font_awesome_color_hover', 'rgba(25, 148, 115, 1)', '.login--icon:hover' );

		$this->set_font_size( 'login_icon_font_awesome_width_hover', '16', '.login--icon:hover' );

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
					"{{WRAPPER}} {$icon_class}" => 'fill: {{VALUE}};',
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
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}!important; position: relative;',
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
		include lisfinity_get_template_part( 'navigation-login', 'shortcodes/navigation-menu', $args );
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
	protected function _content_template() {
		return '';
	}

}
