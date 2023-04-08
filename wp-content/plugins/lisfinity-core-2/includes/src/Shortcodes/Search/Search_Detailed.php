<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Search_Detailed extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-detailed';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Detailed', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-info-circle';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-search-page' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		$this->title();

		$this->button_search( 'header_button', '.header-search-button', '.header-search-button-icon', esc_html__( 'Header Search Button', 'lisfinity-core' ) );

		$this->sticky_header();

		$this->button_search( 'sticky_header_button', '.sticky-header-search-button', '.sticky-header-search-button-icon', esc_html__( 'Sticky Header Search Button', 'lisfinity-core' ) );

		$this->subtitle();

		$this->labels();

		$this->select();

		$this->range();

		$this->checkbox();

	}

	public function title() {
		$this->start_controls_section(
			'search_filters_header',
			[
				'label' => __( 'Filters Header', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_container_style',
			[
				'label' => __( 'Heading Style', 'lisfinity-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->set_margin( 'title_margin', '#page-search-detailed-elementor .filters--header-wrapper .filters--header', 0, 0, 0, 0, true );

		$this->set_padding( 'title_padding', '#page-search-detailed-elementor .filters--header-wrapper .filters--header', 0, 0, 0, 0, false );

		$this->add_control(
			'heading_title_style',
			[
				'label'     => __( 'Title Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_filter_title',
			[
				'label'   => __( 'Custom Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'filter_text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own filter title text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_filter_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'use_custom_icon',
			[
				'label'   => __( 'Different Title Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Title Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom title icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .search-detailed-title-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#bcbcbc',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search-detailed-title-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "search_filters_title_detailed",
				'selector' => "{{WRAPPER}} #page-search-detailed-elementor .filters--title span",
			]
		);

		$this->end_controls_section();
	}

	public function sticky_header() {
		$this->start_controls_section(
			'search_filters_sticky_header',
			[
				'label' => __( 'Filters Sticky Header', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_container_sticky_style',
			[
				'label' => __( 'Heading Style', 'lisfinity-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->set_margin( 'sticky_header_title_margin', '#page-search-detailed-elementor .filters--header-sticky .filters--header', 0, 0, 0, 0, true );

		$this->set_padding( 'sticky_header_title_padding', '#page-search-detailed-elementor .filters--header-sticky .filters--header', 24, 36, 24, 36, false );

		$this->set_background_color( 'sticky_header_title_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background color', 'lisfinity-core' ), '#page-search-detailed-elementor .filters--header-sticky .filters--header' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "sticky_header_border_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters--header-sticky .filters--header",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '1',
							'left'   => '0'
						]
					],
					'color'  => [ 'default' => 'rgba(246, 246, 246, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'           => "sticky_header_shadow_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters--header-sticky .filters--header",
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							"horizontal" => '0',
							'vertical'   => '0',
							'blur'       => '0',
							'spread'     => '20',
							'color'      => 'rgba(0, 0, 0, .09)',
						]
					]
				]
			]
		);


		$this->add_control(
			'heading_sticky_header_title_style',
			[
				'label'     => __( 'Title Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_filter_sticky_header_title',
			[
				'label'   => __( 'Custom Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'filter_text_sticky_header',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own filter title text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_filter_sticky_header_title' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "search_filters_title_detailed_2",
				'selector' => "{{WRAPPER}} #page-search-detailed-elementor .filters--header-sticky .filters--title span",
			]
		);

		$this->add_control(
			'use_custom_icon_sticky_header',
			[
				'label'   => __( 'Different Title Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'icon_sticky_header',
			[
				'label'       => __( 'Title Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom title icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_icon_sticky_header' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_sticky_header',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .sticky-header-title-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color_sticky_header',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#bcbcbc',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .sticky-header-title-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);


		$this->end_controls_section();
	}

	public function subtitle() {
		$this->start_controls_section(
			'search_filters_subtitle',
			[
				'label' => __( 'Filters Subtitle', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_container_style_subtitle',
			[
				'label' => __( 'Heading Style', 'lisfinity-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->set_margin( 'subtitle_margin', '#page-search-detailed-elementor .filters--group-title', 0, 0, 0, 0, true );

		$this->set_padding( 'subtitle_padding', '#page-search-detailed-elementor .filters--group-title', 0, 0, 0, 15, false );

		$this->add_control(
			'heading_subtitle_style',
			[
				'label'     => __( 'Title Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "search_filters_subtitle_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters--group-title",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 24 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function labels() {
		$this->start_controls_section(
			'search_filters_labels',
			[
				'label' => __( 'Filters Field Labels', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_margin( 'label_margin', '#page-search-detailed-elementor .filters--label', 0, 0, 4, 0, false );

		$this->set_padding( 'label_padding', '#page-search-detailed-elementor .filters--label', 0, 0, 0, 0, false );

		$this->add_responsive_control(
			'label_position',
			[
				'label'       => __( 'Label Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => true,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'flex-start',
				'selectors'   => [
					'{{WRAPPER}} #page-search-detailed-elementor .filters--label' => 'display: flex; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "search_filters_labels_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters--label",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function select() {
		$this->start_controls_section(
			'search_filters_select_fields',
			[
				'label' => __( 'Filters Select Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'select_structure',
			[
				'label'     => __( 'Select Fields Structure Options', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'select_columns',
			[
				'label'       => __( 'No. of Columns', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'description' => __( 'Choose number of columns', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy' => 'width: calc(100% / {{VALUE}});',
				]
			]
		);

		$this->set_background_color( 'select_bg_color', '#f6f6f6', 'Background Color', '.filters div[class*=css-0]' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "select_border_detailed",
				'selector'       => "{{WRAPPER}} .filters div[class*=css-0]",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "search_filters_select_detailed",
				'selector'       => "{{WRAPPER}} .filters div[class*=css-0], {{WRAPPER}} .filters div[class*=-placeholder]",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(149, 149, 149, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);

		// dropdown typography.
		$this->add_control(
			'select_dropdown',
			[
				'label'     => __( 'Dropdown Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',

			]
		);
		$this->set_background_color( 'select_dropdown_bg_color', '#ffffff', 'Background Color', '.filters div[class*=-menu], {{WRAPPER}} .filters div[class*=-fk865s]', true );

		$this->set_background_color( 'select_dropdown_bg_color_hover', '#f6f6f6', 'Background Color on Hover', '.filters .css-dpec0i-option', true );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "search_filters_select_dropdown_details",
				'selector'       => "{{WRAPPER}} .filters div[class*=-option], {{WRAPPER}} .filters div[class*=-menu]",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function range() {
		$this->start_controls_section(
			'search_filters_price_fields',
			[
				'label' => __( 'Filters Price Range Field', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'range_field',
			[
				'label'     => __( 'Range Fields Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'price_bg_color', '#f6f6f6', 'Background Color', '#page-search-detailed-elementor .filters .field--with-icon' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "price_border_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters .field--with-icon",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);

		$this->add_control(
			'range_label',
			[
				'label'     => __( 'Label Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "price_filters_select_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters .field--with-icon__label",
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
						'default' => 600
					],
				],
			]
		);

		$this->add_control(
			'range_placeholder',
			[
				'label'     => __( 'Value Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "price_filters_select_placeholder_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .filters input",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(149, 149, 149, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function checkbox() {
		$this->start_controls_section(
			'search_filters_checkbox_fields',
			[
				'label' => __( 'Filters Checkbox Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkbox_structure',
			[
				'label'     => __( 'Checkbox Structure Options', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'checkbox_columns',
			[
				'label'       => __( 'No. of Columns', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 2,
				'description' => __( 'Choose number of columns', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox' => 'width: calc(100% / {{VALUE}});',
				]
			]
		);

		$this->add_control(
			'checkbox_styles',
			[
				'label'     => __( 'Checkbox Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->set_padding( 'checkbox_label_padding', '#page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox label', 0, 0, 0, 12, false );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "checkbox_typography_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);

		$this->add_control(
			'checkbox_bg_styles',
			[
				'label'     => __( 'Checkbox Background Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'checkbox_bg_color', '#f6f6f6', 'Background Color', '#page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox input' );
		$this->set_background_color( 'checkbox_bg_color_active', '#2186eb', 'Background Color', '#page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox input::after' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "checkbox_shadow_detailed",
				'selector' => "{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox input",
			]
		);

		$this->add_control(
			'checkbox_border_styles',
			[
				'label'     => __( 'Checkbox Border Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "checkbox_border_detailed",
				'selector'       => "{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox input",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);


		$this->add_responsive_control(
			'checkbox_border_radius_active',
			[
				'label' => __( 'Border Radius Active Element' ),
				'type'  => Controls_Manager::DIMENSIONS,

				'default'   => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0'
				],
				'selectors' => [
					'{{WRAPPER}} #page-search-detailed-elementor .search-taxonomy.checkbox .field--checkbox input::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function button_search( $id, $class, $icon_class, $name ) {
		$this->start_controls_section(
			"submit_button_$id",
			[
				'label' => __( $name, 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			"button_icon_heading_$id",
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			"use_custom_button_icon_$id",
			[
				'label'   => __( 'Different Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			"button_submit_icon_$id",
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					"use_custom_button_icon_$id" => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			"button_icon_size_$id",
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					"{{WRAPPER}} $icon_class" => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					"use_custom_button_icon_$id" => 'yes',
				],
			]
		);

		$this->add_control(
			"button_icon_color_$id",
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $icon_class" => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'condition'   => [
					"use_custom_button_icon_$id" => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			"button_text_heading_$id",
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			"custom_button_text_$id",
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			"button_text_$id",
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					"custom_button_text_$id" => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			"button_styles_$id",
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( "button_active_tabs_$id" );

		// normal button values;
		$this->start_controls_tab( "button_normal_$id",
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( "button_bg_color_$id", '#0967d2', 'Background Color', $class );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "button_border_$id",
				'selector'       => "{{WRAPPER}} $class",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(9, 103, 210, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "button_shadow_$id",
				'selector' => "{{WRAPPER}} $class",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "button_typography_$id",
				'selector' => "{{WRAPPER}} $class",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 16 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);

		$this->end_controls_tab();

		// hover button values.
		$this->start_controls_tab( "button_hover_$id",
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( "button_bg_color_hover_$id", '#03449e', 'Background Color', "$class:hover" );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "button_border_hover_$id",
				'selector' => "{{WRAPPER}} $class:hover",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1'
						]
					],
					'color'  => [ 'default' => 'rgba(3, 68, 158, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "button_shadow_hover_$id",
				'selector' => "{{WRAPPER}} $class:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "button_typography_hover_$id",
				'selector' => "{{WRAPPER}} $class:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 16 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
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

		include lisfinity_get_template_part( 'search-detailed', 'shortcodes/search-page', $args );
	}

}
