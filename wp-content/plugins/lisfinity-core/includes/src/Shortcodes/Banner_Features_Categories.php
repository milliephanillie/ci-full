<?php


namespace Lisfinity\Shortcodes;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Features_Categories_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Border;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;

class Banner_Features_Categories extends Widget_Base {
	public $banner_taxonomies = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );


	}

	public function get_name() {
		return 'lisfinity-hero-categories';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */

	public function get_title() {
		return sprintf( __( '%s Banner Featured Categories', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-th-large';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity', 'lisfinity-banner' ];
	}


	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'lisfinity_hero_categories_field',
			[
				'label' => __( 'Categories Content', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->fields();

		$this->end_controls_section();

		$this->start_controls_section(
			'lisfinity_hero_categories',
			[
				'label' => __( 'Categories', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'carousel',
			[
				'label'        => __( 'Display as Carousel on Mobiles', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'lisfinity-core' ),
				'label_off'    => __( 'No', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'description'  => __( 'Display categories as a carousel on smaller screens', 'lisfinity-core' ),
			]
		);

		$this->features_categories_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'lisfinity_hero_categories_icon',
			[
				'label' => __( 'Categories Icons', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->features_categories_icons_style();

		$this->end_controls_section();


	}

	public function fields() {
		$model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();

		$banner_taxonomies = lisfinity_get_option( 'home-banner-taxonomies' );

		$tab_repeater = new Repeater();

		$groups = $model->get_groups_with_taxonomies();

		$tab_repeater->add_control(
			'title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Latest Listings', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' ),
			]
		);

		if ( ! empty( $groups ) ) {
			$tab_repeater->add_control(
				'taxonomy',
				[
					'label'   => __( 'Label', 'elementor-pro' ),
					'type'    => Controls_Manager::SELECT2,
					'options' => $model->format_options_for_select( true ),
				]
			);
		} else {
			$tab_repeater->add_control(
				'taxonomy',
				[
					'label'   => __( 'Label', 'elementor-pro' ),
					'type'    => Controls_Manager::SELECT2,
					'options' => lisfinity_get_terms_by_taxonomy_select(),
				]
			);
		}

		$tab_repeater->add_control(
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

		$tab_repeater->add_control(
			'selected_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);


		$this->add_control(
			'taxonomies_tabs',
			[
				'label'         => __( 'Listing Tabs', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $tab_repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'title_field'   => __( 'Tab: {{{ title }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

	}


	public function features_categories_style() {

		$this->add_group_control(
			Group_Control_Banner_Features_Categories_Typography::get_type(),
			[
				'name'     => 'banner_features_categories_typography',
				'selector' => '{{WRAPPER}} .banner--taxonomies h5'
				,
			]
		);

		$this->set_text_color( 'features_categories_text_color', 'rgba(188, 188, 188, 1)', '.banner--taxonomies h5' );

		$this->set_heading_section( 'features_categories_heading_position', esc_html__( 'Set text position', 'lisfinity-core' ), 'features_categories_position_hr' );

		$this->set_element_position( 'text_position_x', '0', 'text_position_y', '0', '.banner--taxonomies h5' );

		$this->set_heading_section( 'features_categories_background_heading', esc_html__( 'Category Box', 'lisfinity-core' ), 'features_categories_background_hr' );

		$this->set_width( 'categories_width', '86', 'px', '.banner--taxonomy__bg' );

		$this->set_height( 'categories_height', '86', 'px', '.banner--taxonomy__bg' );

		$this->set_background_color( 'features_categories_bg_color', 'rgba(149, 149, 149, 0.15)', '.banner--taxonomy__bg' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => 'banner_features_categories_box_shadow',
				'selector' => '{{WRAPPER}} .banner--taxonomy__bg'
				,
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Border::get_type(),
			[
				'name'     => 'banner_features_categories_border',
				'selector' => '{{WRAPPER}} .banner--taxonomy__bg'
				,
			]
		);

		$this->set_border_radius( 'features_categories_border_radius', '3', '3', '3', '3', 'px', '.banner--taxonomy__bg' );

		$this->set_padding( 'features_categories_padding', '.banner--taxonomy__container', '0', '2', '0', '2', 'false' );

		$this->set_margin( 'features_categories_margin', '.banner--taxonomy__container', '10', '0', '0', '0', 'false' );

		$this->set_heading_section( 'features_categories_heading_hover', esc_html__( 'On Hover', 'lisfinity-core' ), 'features_categories_heading_hr' );

		$this->set_text_color( 'features_categories_text_color_hover', 'rgba(188, 188, 188, 1)', '.banner--taxonomy__container:hover h5' );

		$this->set_background_color( 'features_categories_bg_color_hover', 'rgba(255, 255, 255, 0.2)', '.banner--taxonomy__bg:hover' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => 'banner_features_categories_box_shadow_hover',
				'selector' => '{{WRAPPER}} .banner--taxonomy__bg:hover'
				,
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Border::get_type(),
			[
				'name'     => 'banner_features_categories_border_hover',
				'selector' => '{{WRAPPER}} .banner--taxonomy__bg:hover'
				,
			]
		);
		$this->set_width( 'categories_width_hover', '86', 'px', '.banner--taxonomy__bg:hover' );

		$this->set_height( 'categories_height_hover', '86', 'px', '.banner--taxonomy__bg:hover' );


	}

	public function features_categories_icons_style() {

		$this->add_control(
			'categories_icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .banner--taxonomy__bg .hero-category-icon" => 'color:{{VALUE}}; fill:{{VALUE}};'
				]
			]
		);


		$this->add_control(
			'categories_icon_width',
			[
				'label'      => __( 'Icon Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 24,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .banner--taxonomy__bg .hero-category-icon' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};font-size:{{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->set_heading_section( 'features_categories_icon_heading_hover', esc_html__( 'On Hover', 'lisfinity-core' ), 'features_categories_icon_heading_hr' );

		$this->add_control(
			'categories_icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .banner--taxonomy__bg:hover .hero-category-icon" => 'color:{{VALUE}}; fill:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'categories_icon_width_hover',
			[
				'label'      => __( 'Icon Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 24,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .banner--taxonomy__bg:hover .hero-category-icon' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};font-size:{{SIZE}}{{UNIT}};',
				]
			]
		);


	}


	/**
	 * Functions
	 * ------------------------------
	 */

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


	public function set_text_color( $id, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'color:{{VALUE}};'

				]
			]
		);
	}

	public function set_width( $id, $default_size, $default_unit, $selector ) {
		$this->add_control(
			$id,
			[
				'label'      => __( 'Width', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => $default_size,
					'unit' => $default_unit
				],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'width:{{SIZE}}{{UNIT}};',
				]
			]
		);
	}

	public function set_height( $id, $default_size, $default_unit, $selector ) {
		$this->add_control(
			$id,
			[
				'label'      => __( 'Height', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => $default_size,
					'unit' => $default_unit
				],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'height:{{SIZE}}{{UNIT}};',
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
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}; position: relative;',
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
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
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

	public function set_background_color( $id, $default_color, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default_color,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};'
				],
			]
		);
	}

	public function set_opacity( $id, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( 'Opacity', 'lisfinity-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => $default,
				'range'     => [
					'min' => 0,
					'max' => 1,
				],
				'selectors' => [
					"{{WRAPPER}} $selector" => 'opacity:{{SIZE}};'
				],
			]
		);
	}


	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_unit, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'unit'   => $default_unit,
					'top'    => $default_top,
					'right'  => $default_right,
					'bottom' => $default_bottom,
					'left'   => $default_left,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

//		$settings['order'] = array_column( $settings['lisfinity_hero_form_fields'], 'custom_id' );

		$args = [
			'settings' => $settings,
		];


		include lisfinity_get_template_part( 'hero-categories', 'shortcodes/banner', $args );
	}

}
