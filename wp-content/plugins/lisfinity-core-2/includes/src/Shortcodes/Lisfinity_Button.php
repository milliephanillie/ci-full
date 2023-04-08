<?php

namespace Lisfinity\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

/**
 * Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Lisfinity_Button extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'lisfinity-button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Lisfinity -> Button', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'fa fa-toggle-off';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 2.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'lisfinity' ];
	}

	/**
	 * Get button sizes.
	 *
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @return array An array containing button sizes.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elementor' ),
			'sm' => __( 'Small', 'elementor' ),
			'md' => __( 'Medium', 'elementor' ),
			'lg' => __( 'Large', 'elementor' ),
			'xl' => __( 'Extra Large', 'elementor' ),
		];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'elementor' ),
			]
		);

		$this->start_controls_tabs(
			'section_button_tabs'
		);
		$this->start_controls_tab(
			'section_button_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);


		$this->default_button();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'section_button_is_logged_in_tab',
			[
				'label' => __( 'Logged In', 'lisfinity-core' ),
			]
		);

		$this->is_logged_in();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'lisfinity_button_width',
			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'   => [
					"{{WRAPPER}} .elementor-button" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs(
			'section_button_style_tabs'
		);
		$this->start_controls_tab(
			'section_button_default_style_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->default_button_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'section_button_is_logged_in_style_tab',
			[
				'label' => __( 'Logged In', 'lisfinity-core' ),
			]
		);

		$this->is_logged_in_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'lisfinity_button_icon',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'selected_icon[value]!' => '',
				]
			]
		);

		$this->icon_style();
		$this->end_controls_section();

	}

	public function default_button() {
		$this->add_control(
			'button_type',
			[
				'label'        => __( 'Type', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					''        => __( 'Default', 'elementor' ),
					'info'    => __( 'Info', 'elementor' ),
					'success' => __( 'Success', 'elementor' ),
					'warning' => __( 'Warning', 'elementor' ),
					'danger'  => __( 'Danger', 'elementor' ),
				],
				'prefix_class' => 'elementor-button-',
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Click here', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default'     => [
					'url' => '',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => __( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
			]
		);

		$this->add_control(
			'size',
			[
				'label'          => __( 'Size', 'elementor' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'sm',
				'options'        => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'     => __( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_indent',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Custom Position', 'elementor' ),
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
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .elementor-button i" => 'position: relative; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'elementor' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label'       => __( 'Button ID', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor' ),
				'separator'   => 'before',

			]
		);
	}

	public function is_logged_in() {
		$this->add_control(
			'display_link_is_user_logged_in',
			[
				'label'        => __( 'Use different link if the user is logged in', 'lisfinity-core' ),
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
			'link_is_user_logged_in',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default'     => [
					'url' => '',
				],
				'condition'   => [
					'display_link_is_user_logged_in' => 'yes'
				],
				'description' => __( 'Link if the user is logged in', 'lisfinity-core' )
			]
		);

		$this->add_control(
			'selected_icon_is_user_logged_in',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_is_logged_in',
				'condition'        => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_align_is_user_logged_in',
			[
				'label'     => __( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
				'condition' => [
					'selected_icon_is_user_logged_in[value]!' => '',
					'display_link_is_user_logged_in'          => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_indent_is_user_logged_in',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);


	}

	public function default_button_style() {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);


		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => 'rgba(76, 76, 76, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => 12,
					'right'  => 30,
					'bottom' => 12,
					'left'   => 30,
					'unit'   => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->set_heading_section( 'button_position_heading', esc_html__( 'Position', 'lisfinity-core' ), 'button_position_hr' );

		$this->add_responsive_control(
			'button_position_x',

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
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button' => 'right: {{SIZE}}{{UNIT}}!important; position: relative;',

				]
			]
		);

		$this->add_responsive_control(
			'button_position_y',

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
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button' => 'top: {{SIZE}}{{UNIT}}!important; position: relative;',
				]
			]
		);

		$this->add_control(
			'default_button_hover_heading',
			[
				'label'     => __( 'Hover', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'default_button_hover_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography-over',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-button:hover',
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9, 103, 210, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);


	}

	public function is_logged_in_style() {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'typography-is_logged_in',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .elementor-button',
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'text_shadow_is_logged_in',
				'selector'  => '{{WRAPPER}} .elementor-button',
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);


		$this->add_control(
			'button_text_color_is_logged_in',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'background_color_is_logged_in',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => 'rgba(76, 76, 76, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);
		$this->set_heading_section( 'button_logged_in_position_heading', esc_html__( 'Position', 'lisfinity-core' ), 'button_logged_in_position_hr' );
		$this->add_responsive_control(
			'button_position_x_is_logged',

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
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button' => 'right: {{SIZE}}{{UNIT}}!important; position: relative;',

				],
				'condition'   => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'button_position_y_is_logged',

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
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button' => 'top: {{SIZE}}{{UNIT}}!important; position: relative;',
				],
				'condition'   => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);


		$this->add_control(
			'default_button_hover_heading_is_logged_in',
			[
				'label'     => __( 'Hover', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'default_button_hover_hr_is_logged_in',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'hover_color_is_logged_in',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'button_background_hover_color_is_logged_in',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9, 103, 210, 1)',
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'display_link_is_user_logged_in' => 'yes'
				],
			]
		);

		$this->add_control(
			'button_hover_border_color_is_logged_in',
			[
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!'                 => '',
					'display_link_is_user_logged_in' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

	}

	public function icon_style() {
		$this->add_responsive_control(
			'icon_horizontal_position',

			[
				'label'     => esc_html( 'Horizontal Position' ),
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
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon svg, {{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon i" => 'position: relative; left: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'icon_vertical_position',

			[
				'label'     => esc_html( 'Vertical Position' ),
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
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon svg, {{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon i" => 'position: relative; top: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_control(
			'icon_style_color',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon i"   => 'color:{{VALUE}};',
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon svg" => 'fill:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_style_size',

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
					'size' => 14,
				],
				'selectors'   => [
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon i"   => 'font-size:{{SIZE}}{{UNIT}};',
					"{{WRAPPER}} .elementor-button-content-wrapper .elementor-button-icon svg" => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_heading_section( 'icon_style_hover_heading', esc_html__( 'On hover', 'lisfinity-core' ), 'icon_style_hover_hr' );
		$this->add_control(
			'icon_style_color_hover',
			[
				'label'     => esc_html( 'Color' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .elementor-button-content-wrapper:hover .elementor-button-icon i"   => 'color:{{VALUE}};',
					"{{WRAPPER}} .elementor-button-content-wrapper:hover .elementor-button-icon svg" => 'fill:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_style_size_hover',

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
					'size' => 14,
				],
				'selectors'   => [
					"{{WRAPPER}} .elementor-button-content-wrapper:hover .elementor-button-icon i"   => 'font-size:{{SIZE}}{{UNIT}};',
					"{{WRAPPER}} .elementor-button-content-wrapper:hover .elementor-button-icon svg" => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
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

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			if ( is_user_logged_in() && 'yes' === $settings['display_link_is_user_logged_in'] ) {
				$this->add_link_attributes( 'button', $settings['link_is_user_logged_in'] );
			} else {
				$this->add_link_attributes( 'button', $settings['link'] );
			}
			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		}

		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		?>
		<?php if ( empty( $settings['button_display'] ) || ( 'logged' === $settings['button_display'] && is_user_logged_in() ) || ( 'not_logged' === $settings['button_display'] && ! is_user_logged_in() ) ) : ?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<?php $this->render_text(); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$migrated_is_logged_in = isset( $settings['__fa4_migrated']['selected_icon_is_user_logged_in'] );
		$is_new_is_logged_in   = empty( $settings['icon_is_logged_in'] ) && Icons_Manager::is_migration_allowed();


		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align'      => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['icon_align'],
				],
			],
			'text'            => [
				'class' => 'elementor-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>

	<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
		<?php if ( 'yes' === $settings['display_link_is_user_logged_in'] && is_user_logged_in() && ! empty( $settings['selected_icon_is_user_logged_in']['value'] ) ) : ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
		<?php Icons_Manager::render_icon( $settings['selected_icon_is_user_logged_in'], [ 'aria-hidden' => 'true' ] ) ?>
	<?php elseif ( 'yes' === $settings['display_link_is_user_logged_in'] && is_user_logged_in() && empty( $settings['selected_icon_is_user_logged_in']['value'] ) ) : ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ) ?>
			</span>
	<?php elseif ( 'yes' === $settings['display_link_is_user_logged_in'] && ! is_user_logged_in() ) : ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ) ?>
			</span>
	<?php else: ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ) ?>
			</span>
	<?php endif; ?>
		<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
		</span>
		<?php
	}

	public function on_import( $element ) {
		return [
			Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' ),
			Icons_Manager::on_import_migration( $element, 'icon_is_logged_in', 'selected_icon_is_user_logged_in' ),
		];
	}
}
