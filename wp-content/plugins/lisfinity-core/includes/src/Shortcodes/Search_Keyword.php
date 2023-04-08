<?php

namespace Lisfinity\Shortcodes;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Items_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Typography;
use Lisfinity\Shortcodes\Controls\Search_Keyword\Group_Control_Search_Keyword_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Search_Keyword\Group_Control_Search_Keyword_Label_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Search_Keyword extends Shortcode {

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
		return 'search-keyword';
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
		return __( 'Lisfinity -> Search Keyword', 'lisfinity-core' );
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
		return 'fa fa-search';
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
		return [ 'lisfinity', 'lisfinity-navigation', 'lisfinity-search' ];
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
			'_navigation_search_keyword',
			[
				'label' => __( 'Search Bar Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->search_bar_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_search_button',
			[
				'label' => __( 'Search Button Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->search_button_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'_navigation_search_keyword_icon_style',
			[
				'label'     => __( 'Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);
		$this->search_keyword_style();

		$this->navigation_search_keyword_hover();

		$this->end_controls_section();

	}

	public function search_bar_style() {

		$this->add_group_control(
			Group_Control_Search_Keyword_Label_Typography::get_type(),
			[
				'name'     => 'search_keyword_label',
				'selector' => '{{WRAPPER}} .search-keyword div label',
			]
		);

		$this->set_width( 'search_keyword_width', '.search-keyword div.flex-center', '100', '%' );
		$this->set_height( 'search_keyword_height', '.search-keyword div.flex-center', '40', 'px' );

		$this->add_control(
			'search_keyword_bg_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .search-keyword div.flex-center' => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search-keyword div input'       => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .keyword-div form button'        => 'z-index: 11;'
				],
			]
		);
		$this->set_border_radius( 'search_keyword_border_radius', '3', '3', '3', '3', 'px', '.search-keyword div.flex-center' );
		$this->set_padding( 'search_keyword_padding', '.search-keyword div.flex-center', '10', '24', '10', '24', 'true' );
		$this->set_margin( 'search_keyword_margin', '.search-keyword div.flex-center', '0', '0', '0', '0', 'true' );

		$this->display_element( 'display_label', esc_html__( 'Display Input Label', 'lisfinity-core' ) );
		$this->set_background_color( 'search_keyword_label_color', 'rgba(0, 0, 0, 1)', esc_html__( 'Label Color', 'lisfinity-core' ), '.search-keyword div label', false );


		$this->add_group_control(
			Group_Control_Search_Keyword_Box_Shadow::get_type(),
			[
				'name'     => 'search_keyword_box_shadow',
				'selector' => '{{WRAPPER}} .search-keyword div.flex-center',
			]
		);


	}

	public function search_button_style() {
		$this->display_element('display_button_text', esc_html__('Add Text', 'lisfinity-core'), '');

		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( '', 'lisfinity-core' ),
				'placeholder' => __( 'Click here', 'lisfinity-core' ),
				'condition' => [
					'display_button_text' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Navigation_Button_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .keyword-div form button',
			]
		);
		$this->set_background_color( 'button_font_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Text Color', 'lisfinity-core' ), '.keyword-div form button', false );



		$this->add_control(
			'display_button_icon',
			[
				'label'        => __( 'Display Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => [ 'before' ]
			]
		);

		$this->add_control(
			'place_icon_search_keyword',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',

			]
		);

		$this->add_control(
			'selected_icon_search_keyword',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_search_keyword' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_align_search_keyword',
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
					'place_icon_search_keyword'   => 'yes'
				],
			]
		);

		$this->add_control(
			'icon_indent_search_keyword',
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
					'place_icon_search_keyword' => 'yes'
				]
			]
		);


		$this->set_border_radius( 'search_keyword_button_border_radius', '0', '3', '3', '0',  'px', '.keyword-div form button' );
		$this->set_padding( 'search_keyword_button_padding', '.keyword-div form button', '0', '10', '0', '10', 'true' );
		$this->set_margin( 'search_keyword_button_margin', '.keyword-div form button', '0', '0', '0', '0', 'true' );
		$this->set_background_color( 'search_keyword_button_bg_color', 'rgba(9, 103, 210, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.keyword-div form button' );
		$this->add_group_control(
			Group_Control_Search_Keyword_Box_Shadow::get_type(),
			[
				'name'     => 'search_keyword_button_box_shadow',
				'selector' => '{{WRAPPER}} .keyword-div form button',
			]
		);
	}

	public function search_keyword_style() {
		$this->add_control(
			'header_icons_search_width',

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
					'{{WRAPPER}} #search--icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #search--icon ' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'header_icons_search_color',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #search--icon' => 'fill: {{VALUE}}; color: {{VALUE}}',
					'{{WRAPPER}} #search--icon svg' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

	}

	public function navigation_search_keyword_hover() {

		$this->set_heading_section( 'notification_hover_heading_search', esc_html__( 'Icon On hover', 'lisfinity-core' ), 'notification_search_hover_hr_search' );

		$this->add_control(
			'header_icons_search_width_hover',

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
					'{{WRAPPER}} #search--icon:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #search--icon:hover ' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'header_icons_search_color_hover',
			[
				'label'       => __( 'Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #search--icon:hover' => 'fill: {{VALUE}}; color: {{VALUE}}',
					'{{WRAPPER}} #search--icon:hover svg' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

	}




	/*
	 * Functions.
	 * -------------------------
	 */

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
		include lisfinity_get_template_part( 'keyword', 'shortcodes/search-elements', $args );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the liveemeforest
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
