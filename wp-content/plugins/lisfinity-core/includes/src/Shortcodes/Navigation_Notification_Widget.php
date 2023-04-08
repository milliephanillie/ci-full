<?php

namespace Lisfinity\Shortcodes;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Items_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Footer_Text_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Text_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Title_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Navigation_Notification_Widget extends Widget_Base {

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
		return 'navigation-notification';
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
		return __( 'Lisfinity -> Navigation Notifications', 'lisfinity-core' );
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
		return 'fa fa-bell';
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
				'label' => __( 'Icon Settings', 'lisfinity-core' ),
			]
		);

		$this->notification_settings();
		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_notification',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_notification' => ''
				]
			]
		);
		$this->start_controls_tabs(
			'notification_icon_tabs',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->start_controls_tab(
			'notification_icon_default_tab',
			[
				'label'     => __( 'Default', 'lisfinity-core' ),
				'condition' => [
					'place_icon_notification' => ''
				]
			]
		);


		$this->notification_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'notification_icon_active_tab',
			[
				'label'     => __( 'Active', 'lisfinity-core' ),
				'condition' => [
					'place_icon_notification' => ''
				]
			]
		);

		$this->navigation_notification_active();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'notification_icon_on_hover_tab',
			[
				'label'     => __( 'On hover', 'lisfinity-core' ),
				'condition' => [
					'place_icon_notification' => ''
				]
			]
		);

		$this->navigation_notification_hover();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'notification_icon_upload_style',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'place_icon_notification' => 'yes'
				]
			]
		);
		$this->notification_icon_svg_style();
		$this->notification_icon_svg_hover();

		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_badge_notification',
			[
				'label' => __( 'Badge Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->badge_notifications();


		$this->start_controls_tabs(
			'_navigation_icons_notification_badge_tabs'
		);


		$this->start_controls_tab(
			'_navigation_icons_notification_badge_hover_tab',
			[
				'label' => __( 'On Hover', 'lisfinity-core' )
			]
		);

		$this->navigation_icons_badge_notification_hover();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_navigation_icons_badge_notification_active_tab',
			[
				'label' => __( 'Active', 'lisfinity-core' )
			]
		);

		$this->navigation_icons_badge_notification_active();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_message_notification',
			[
				'label' => __( 'Notification Message Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->message_style();

		$this->image_style();

		$this->set_heading_section( 'navigation_notification_message_footer_heading', esc_html__( 'Footer', 'lisfinity-core' ), 'navigation_notification_message_footer_hr' );

		$this->start_controls_tabs(
			'_navigation_message_footer_tabs'
		);


		$this->start_controls_tab(
			'navigation_message_footer_icon',
			[
				'label' => __( 'Time', 'lisfinity-core' )
			]
		);
		$this->set_heading_section( 'navigation_notification_message_footer_icon_heading', esc_html__( 'Icon', 'lisfinity-core' ), 'navigation_notification_message_footer_icon_hr' );

		$this->navigation_message_footer_icon_style();

		$this->set_heading_section( 'navigation_notification_message_footer_text_heading', esc_html__( 'Text', 'lisfinity-core' ), 'navigation_notification_message_footer_text_hr' );

		$this->navigation_message_footer_text_style();

		$this->set_heading_section( 'navigation_notification_message_footer_sorting_icon_text_heading', esc_html__( 'Sort elements', 'lisfinity-core' ), 'navigation_notification_message_footer_sorting_icon_text_hr' );

		$this->navigation_message_footer_sort_element();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_message_footer_link',
			[
				'label' => __( 'Link', 'lisfinity-core' )
			]
		);

		$this->navigation_message_footer_link();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}


	/*
	 * Navigation Notifications
	 * -------------------------
	 */

	public function notification_settings() {

		$this->add_control(
			'text',
			[
				'label'       => __( 'Add Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( '', 'elementor' ),
				'placeholder' => __( 'Some text', 'elementor' ),
			]
		);

		$this->add_control(
			'place_icon_notification',
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
			'selected_icon_notification',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_notification' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_align_notification',
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
					'place_icon_notification'     => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_indent_notification',
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
					'place_icon_notification' => 'yes'
				]
			]
		);
	}

	public function notification_style() {

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text',
				'selector' => '{{WRAPPER}} .notification-text',
				'label' => 'Text Typography'
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color', 'rgba(25, 148, 115, 1)', '.notification-text');

		$this->icon_width( '#notification--wrapper svg', 'header_icons_notification_width', '20' );

		$this->icon_height( '#notification--wrapper svg', 'header_icons_notification_height', '20' );


		$this->add_control(
			'header_icons_notification_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #notification--icon' => 'fill: {{VALUE}}!important;',
				],
			]
		);

		$this->set_heading_section( 'notification_icon_text_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_notification_icon_text_position' );

		$this->set_element_position_relative( 'notification_icon_svg_position_x_text', '0', 'notification_icon_svg_position_y_text', '0', '.notification-text' );

		$this->set_heading_section( 'heading_notification_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_notification_position' );

		$this->set_element_position_relative( 'notification_position_x', '0', 'notification_position_y', '0', '#notification--icon' );

		$this->set_heading_section( 'notification_icon_bg_heading', esc_html__( 'Background', 'lisfinity-core' ), 'notification_icon_bg_hr' );

		$this->add_control(
			'notification_icon_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} #notification--icon' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'notification_icon_bg_size',

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
					'{{WRAPPER}} #notification--icon' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'notification_icon_bg_height',

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
					'{{WRAPPER}} #notification--icon' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'notification_icon_border_radius',
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
					'{{WRAPPER}} #notification--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

	}

	public function navigation_notification_hover() {
		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text_hover',
				'selector' => '{{WRAPPER}} .user--notifications:hover .notification-text',
				'label' => 'Text Typography on Hover',
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color_hover', 'rgba(25, 148, 115, 1)', '.user--notifications:hover .notification-text');

		$this->icon_width( '#notification--icon:hover', 'header_icons_notification_notification_hover_width', '20' );

		$this->icon_height( '#notification--icon:hover', 'header_icons_notification_notification_hover_height', '20' );

		$this->add_control(
			'header_icons_notification_notification_hover_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(25, 148, 115, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #notification--icon:hover' => 'fill: {{VALUE}}!important;',
				],
			]
		);
		$this->set_heading_section( 'notification_hover_bg_heading_notification', esc_html__( 'Background On hover', 'lisfinity-core' ), 'notification_notification_hover_bg_hr' );

		$this->add_control(
			'notification_hover_bg_color_notification',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} #notification--icon:hover' => 'background-color:{{VALUE}};'
				],
			]
		);

		$this->add_control(
			'notification_notification_hover_bg_size',

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
					'{{WRAPPER}} #notification--icon:hover' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'notification_notification_hover_bg_height',

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
					'{{WRAPPER}} #notification--icon:hover' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'notification_notification_hover_border_radius',
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
					'{{WRAPPER}} #notification--icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
	}

	public function navigation_notification_active() {
		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text_active',
				'selector' => '{{WRAPPER}} .btn-active .notification-text',
				'label' => 'Text Typography on Hover',
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color_active', 'rgba(25, 148, 115, 1)', '.btn-active .notification-text');
		$this->icon_width( '#notification--wrapper.btn-active .w-20.h-20.fill-icon-reset.pointer-events-none', 'header_notification_icons_notification_active_width', '20' );

		$this->icon_height( '#notification--wrapper.btn-active .w-20.h-20.fill-icon-reset.pointer-events-none', 'header_notification_icons_notification_active_height', '20' );

		$this->add_control(
			'header_notification_icons_notification_active_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(25, 148, 115, 1)',
				'selectors' => [
					'{{WRAPPER}} #notification--wrapper.btn-active .w-20.h-20.fill-icon-reset.pointer-events-none' => 'color:{{VALUE}};'
				]
			]
		);

		$this->set_heading_section( 'notification_notification_active_bg_heading', esc_html__( 'Background Active', 'lisfinity-core' ), 'notification_notification_active_bg_hr' );

		$this->add_control(
			'notification_notification_active_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} #notification--wrapper.btn-active' => 'background-color:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'notification_notification_active_bg_size',

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
					'{{WRAPPER}} #notification--wrapper.btn-active' => 'width: {{SIZE}}{{UNIT}}!important; ',
				],
			]
		);

		$this->add_control(
			'notification_notification_active_bg_height',

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
					'{{WRAPPER}} #notification--wrapper.btn-active' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'notification_notification_active_border_radius',
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
					'{{WRAPPER}} #notification--wrapper.btn-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
	}

	public function notification_icon_svg_style() {
		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text_svg',
				'selector' => '{{WRAPPER}} .user--notifications .notification-text',
				'label' => 'Text Typography',
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color_svg', 'rgba(25, 148, 115, 1)', '.user--notifications .notification-text');
		$this->add_control(
			'notification_icon_svg_width',

			[
				'label'       => __( 'Size', 'lisfinity-core' ),
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
					'{{WRAPPER}} #notification--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #notification--icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'notification_icon_font_awesome_color',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(25, 148, 115, 1)',
				'selectors' => [
					'{{WRAPPER}} #notification--icon' => 'color:{{VALUE}}; fill:{{VALUE}};',
					'{{WRAPPER}} #notification--icon svg' => 'fill:{{VALUE}};'
				],
			]
		);
		$this->set_heading_section( 'notification_icon_svg_text_position', esc_html__( 'Text Position', 'lisfinity-core' ), 'hr_notification_icon_svg_text_position' );

		$this->set_element_position_relative( 'notification_icon_svg_position_text_x', '0', 'notification_icon_svg_position_text_y', '0', '.notification-text' );

		$this->set_heading_section( 'notification_icon_svg_position', esc_html__( 'Position', 'lisfinity-core' ), 'hr_notification_icon_svg_position' );

		$this->set_element_position_relative( 'notification_icon_svg_position_x', '0', 'notification_icon_svg_position_y', '0', '#notification--icon' );

	}

	public function notification_icon_svg_hover() {

		$this->set_heading_section( 'hover_heading_svg_notification', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'hover_hr_svg_notification' );

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text_hover_svg',
				'selector' => '{{WRAPPER}} .user--notifications:hover .notification-text',
				'label' => 'Text Typography on Hover',
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color_hover_svg', 'rgba(25, 148, 115, 1)', '.user--notifications:hover .notification-text');

		$this->add_control(
			'notification_icon_svg_width_hover',

			[
				'label'       => __( 'Size', 'lisfinity-core' ),
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
					'{{WRAPPER}} #notification--icon:hover i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #notification--icon:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'notification_icon_font_awesome_color_hover',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(25, 148, 115, 1)',
				'selectors' => [
					'{{WRAPPER}} #notification--icon:hover' => 'color:{{VALUE}};',
					'{{WRAPPER}} #notification--icon:hover svg' => 'fill:{{VALUE}};'
				],
			]
		);

		$this->set_heading_section( 'active_heading_svg_notification', esc_html__( 'Icon Active', 'lisfinity-core' ), 'active_hr_svg_notification' );

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_icon_text_active_svg',
				'selector' => '{{WRAPPER}} .btn-active .notification-text',
				'label' => 'Text Typography on Hover',
			]
		);

		$this->set_text_color('navigation_notification_icon_text_color_active_svg', 'rgba(25, 148, 115, 1)', '.btn-active .notification-text');

		$this->add_control(
			'notification_icon_svg_width_active',

			[
				'label'       => __( 'Size', 'lisfinity-core' ),
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
					'{{WRAPPER}} .btn-active #notification--icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .btn-active #notification--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'notification_icon_font_awesome_color_active',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(25, 148, 115, 1)',
				'selectors' => [
					'{{WRAPPER}} .btn-active #notification--icon svg' => 'fill:{{VALUE}};',
					'{{WRAPPER}} .btn-active #notification--icon' => 'color:{{VALUE}}; fill:{{VALUE}};'
				],
			]
		);

	}

	/*
	 * Navigation Badge Notifications
	 * -------------------------
	 */

	public function badge_notifications() {

		$this->set_text_color( 'badge_text_color', 'rgba(255, 255, 255, 1)', '#notifications--wrapper .user--notifications span.absolute.flex-center' );

		$this->set_font_size( 'badge_text_size', '10', '#notifications--wrapper .user--notifications span.absolute.flex-center' );

		$this->set_background_color( 'badge_bg_color', 'rgba(248, 106, 106, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '#notifications--wrapper .user--notifications span.absolute.flex-center' );

		$this->icon_width( '#notifications--wrapper .user--notifications span.absolute.flex-center', 'badge_bg_width', '16' );

		$this->icon_height( '#notifications--wrapper .user--notifications span.absolute.flex-center', 'badge_bg_height', '16' );

		$this->set_border_radius( 'badge_border_radius', '50', '50', '50', '50', 'true', '%', '#notifications--wrapper .user--notifications span.absolute.flex-center' );

		$this->set_heading_section( 'notification_badge_position_heading', esc_html__( 'Position', 'lisfinity-core' ), 'notification_badge_position_hr' );

		$this->set_element_position( 'badge_position_x', '-10', 'badge_position_y', '-12', '#notifications--wrapper .user--notifications span.absolute.flex-center' );

	}

	public function navigation_icons_badge_notification_hover() {

		$this->set_text_color( 'badge_hover_text_color', 'rgba(255, 255, 255, 1)', '#notifications--wrapper .user--notifications:hover span.absolute.flex-center' );

		$this->set_font_size( 'badge_hover_text_size', '10', '#notifications--wrapper .user--notifications:hover span.absolute.flex-center' );

		$this->set_background_color( 'badge_hover_bg_color', 'rgba(248, 106, 106, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '#notifications--wrapper .user--notifications:hover span.absolute.flex-center' );

		$this->icon_width( '#notifications--wrapper .user--notifications:hover span.absolute.flex-center', 'badge_hover_bg_width', '16' );

		$this->icon_height( '#notifications--wrapper .user--notifications:hover span.absolute.flex-center', 'badge_hover_bg_height', '16' );

		$this->set_border_radius( 'badge_hover_border_radius', '50', '50', '50', '50', 'true', '%', '#notifications--wrapper .user--notifications:hover span.absolute.flex-center' );

		$this->set_heading_section( 'notification_badge_hover_position_heading', esc_html__( 'Position', 'lisfinity-core' ), 'notification_badge_hover_position_hr' );

		$this->set_element_position( 'badge_hover_position_x', '-10', 'badge_hover_position_y', '-12', '#notifications--wrapper .user--notifications:hover span.absolute.flex-center' );


	}

	public function navigation_icons_badge_notification_active() {

		$this->set_text_color( 'badge_active_text_color', 'rgba(255, 255, 255, 1)', '#notifications--wrapper .user--notifications.btn-active span.absolute.flex-center' );

		$this->set_font_size( 'badge_active_text_size', '10', '#notifications--wrapper .user--notifications.btn-active span.absolute.flex-center' );

		$this->set_background_color( 'badge_active_bg_color', 'rgba(248, 106, 106, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '#notifications--wrapper .user--notifications:active span.absolute.flex-center' );

		$this->icon_width( '#notifications--wrapper .user--notifications.btn-active span.absolute.flex-center', 'badge_active_bg_width', '16' );

		$this->icon_height( '#notifications--wrapper .user--notifications.btn-active span.absolute.flex-center', 'badge_active_bg_height', '16' );

		$this->set_border_radius( 'badge_active_border_radius', '50', '50', '50', '50', 'true', '%', '#notifications--wrapper .user--notifications:active span.absolute.flex-center' );

		$this->set_heading_section( 'notification_badge_active_position_heading', esc_html__( 'Position', 'lisfinity-core' ), 'notification_badge_active_position_hr' );

		$this->set_element_position( 'badge_active_position_x', '-10', 'badge_active_position_y', '-12', '#notifications--wrapper .user--notifications.btn-active span.absolute.flex-center' );

	}

	/*
	 * Navigation Message Notifications
	 * -------------------------
	 */

	public function message_style() {

		$this->set_background_color( 'navigation_notification_message_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background color', 'lisfinity-core' ), '.header-notification li.bg-grey-100' );

		$this->set_background_color( 'navigation_notification_message_bg_color_hover', '#fff4f4', esc_html__( 'Background color on hover', 'lisfinity-core' ), '.header-notification li.bg-red-100' );

		$this->set_padding( 'navigation_notification_message_padding', '.header-notification--main', '0', '5', '0', '10', 'true' );

		$this->set_heading_section( 'navigation_notification_message_heading', esc_html__( 'Title', 'lisfinity-core' ), 'navigation_notification_message_hr' );

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Title_Typography::get_type(),
			[
				'name'     => 'navigation_notification_message_title',
				'selector' => '{{WRAPPER}} .header-notification--main h6',
			]
		);

		$this->set_text_color( 'navigation_notification_message_title_color', 'rgba(79, 79, 79, 1)', '.header-notification--main h6' );

		$this->set_heading_section( 'notification_message_title_position_heading', esc_html__( 'Title Position', 'lisfinity-classic' ), 'notification_message_title_position_hr' );

		$this->set_element_position_relative( 'notification_title_position_x', '0', 'notification_title_position_y', '0', '.header-notification--main h6' );

		$this->set_heading_section( 'navigation_notification_message_text_heading', esc_html__( 'Content', 'lisfinity-core' ), 'navigation_notification_message_text_hr' );

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_message_text',
				'selector' => '{{WRAPPER}} .header-notification--main .header-notification--content',
			]
		);

		$this->set_text_color( 'navigation_notification_message_text_color', 'rgba(79, 79, 79, 1)', '.header-notification--main .header-notification--content' );

	}

	public function image_style() {

		$this->set_heading_section( 'notification_message_image_position_heading', esc_html__( 'Image Position', 'lisfinity-classic' ), 'notification_message_image_position_hr' );

		$this->set_element_position( 'notification_image_position_x', '0', 'notification_image_position_y', '0', '.header-notification figure' );
	}

	public function navigation_message_footer_icon_style() {
		$this->icon_color( '.header-notification--meta .flex .fill-grey-400 div svg', 'navigation_notification_message_footer_icon_color', 'rgba(1178, 178, 178, 1)' );

		$this->icon_width( '.header-notification--meta .flex .fill-grey-400 div svg', 'navigation_notification_message_footer_icon_width', '16' );

		$this->icon_height( '.header-notification--meta .flex .fill-grey-400 div svg', 'navigation_notification_message_footer_icon_height', '16' );


	}

	public function navigation_message_footer_text_style() {

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Footer_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_message_footer_time_text',
				'selector' => '{{WRAPPER}} .header-notification--meta .flex .time.font-semibold',
			]
		);

		$this->set_text_color( 'navigation_notification_message_footer_time_color', 'rgba(1178, 178, 178, 1)', '.header-notification--meta .flex .time.font-semibold' );


	}

	public function navigation_message_footer_sort_element() {

		$this->sort_elements( 'navigation_message_footer_sort_icon', esc_html__( 'Sort Icon', 'lisfinity-core' ), '1', '.header-notification--meta .flex .fill-grey-400' );

		$this->sort_elements( 'navigation_message_footer_sort_time', esc_html__( 'Sort Text', 'lisfinity-core' ), '2', '.header-notification--meta .flex .time.font-semibold' );

	}

	public function navigation_message_footer_link() {

		$this->add_group_control(
			Group_Control_Navigation_Notification_Message_Footer_Text_Typography::get_type(),
			[
				'name'     => 'navigation_notification_message_footer_link_text',
				'selector' => '{{WRAPPER}} .header-notification--meta a',
			]
		);

		$this->set_text_color( 'navigation_message_footer_link_text_color', 'rgba(9, 103, 210, 1)', '.header-notification--meta a' );

		$this->set_heading_section( 'navigation_message_footer_link_icon_heading', esc_html__( 'Icon', 'lisfinity-core' ), 'navigation_message_footer_link_icon_hr' );

		$this->icon_color( '.header-notification--meta a .relative div svg', 'navigation_message_footer_link_icon_color', 'rgba(9, 103, 210, 1)' );

		$this->icon_width( '.header-notification--meta a .relative', 'navigation_message_footer_link_icon_width', '14' );

		$this->icon_height( '.header-notification--meta a .relative', 'navigation_message_footer_link_icon_height', '14' );

	}


	/*
	 * Functions.
	 * -------------------------
	 */

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

	public function sort_elements( $id, $description, $order_number, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $description, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'default'   => $order_number,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
			]
		);
	}

	public function display_element( $id ) {
		$this->add_control(
			$id,
			[
				'label'                            => __( 'Display', 'lisfinity-core' ),
				'type'                             => Controls_Manager::SWITCHER,
				'label_on'                         => __( 'Show', 'lisfinity-core' ),
				'label_off'                        => __( 'Hide', 'lisfinity-core' ),
				'return_value'                     => 'yes',
				'default'                          => 'yes',
				'{{WRAPPER}} .notification--hover' => 'background-color: transparent;'
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
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'width: {{SIZE}}{{UNIT}}!important;',
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

	public function set_element_position_relative( $id_x, $default_x, $id_y, $default_y, $selector ) {
		$this->add_responsive_control(
			$id_x,

			[
				'label_block' => true,
				'label'     => esc_html( 'Horizontal' ),
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
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}; position:relative;',
				]
			]
		);

		$this->add_responsive_control(
			$id_y,

			[
				'label_block' => true,
				'label'     => esc_html( 'Vertical' ),
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
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}};position:relative;',
				]
			]
		);
	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector ) {
		$this->add_responsive_control(
			$id_x,

			[
				'label_block' => true,
				'label'     => esc_html( 'Horizontal' ),
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
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}!important;',
				]
			]
		);

		$this->add_responsive_control(
			$id_y,

			[
				'label_block' => true,
				'label'     => esc_html( 'Vertical' ),
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
		include lisfinity_get_template_part( 'navigation-notifications', 'shortcodes/navigation-menu', $args );
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
