<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Sidebar_Filter_Widget extends Shortcode {

	public $classes = [
		'container_class' => '.page-search-sidebar-filter',
		'wrapper_class'   => '.filters--wrapper',
	];

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-sidebar-filter';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Sidebar Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-align-justify';
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
		$this->form_wrapper();

		$this->title();

		$this->labels();

		$this->select();

		$this->range();

		$this->checkbox();

		$this->button_search();

		$this->button_detailed();
	}

	public function form_wrapper() {
		$this->start_controls_section(
			'search_filter_wrapper',
			[
				'label' => __( 'Filters Container', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_margin( 'wrapper_margin', $this->classes['container_class'], 0, 0, 0, 0, true );

		$this->set_padding( 'wrapper_padding', $this->classes['container_class'], 0, 14, 0, 14, false );

		$this->set_background_color( 'wrapper_bg_color', 'transparent', 'Background Color', $this->classes['container_class'] );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "wrapper_shadow",
				'selector' => "{{WRAPPER}} {$this->classes['container_class']}",
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "wrapper_border",
				'selector' => "{{WRAPPER}} {$this->classes['container_class']}",
			]
		);

		$this->end_controls_section();
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

		$this->set_margin( 'title_margin', '.filters--header', 0, 0, 0, 0, true );

		$this->set_padding( 'title_padding', '.filters--header', 0, 0, 0, 0, false );

		$this->add_control(
			'heading_title_style',
			[
				'label'     => __( 'Title Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_filter_text',
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
					'custom_filter_text' => 'yes',
				],
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
					'{{WRAPPER}} .filters--title svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .filters--title i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_icon' => 'yes',
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
					'{{WRAPPER}} .filters--title svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .filters--title i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "search_filters_title",
				'selector' => "{{WRAPPER}} .filters--title span",
			]
		);

		$this->add_control(
			'heading_reset_style',
			[
				'label'     => __( 'Reset Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// reset button.
		$this->set_padding( 'reset_padding', '.action--reset', 0, 0, 0, 0, false );
		$this->add_control(
			'reset_bg',
			[
				'label'       => __( 'Reset Background Color (Disabled)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Choose the background color when the button is disabled', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'reset_bg_hover',
			[
				'label'       => __( 'Reset Background Color (Disabled, On Hover)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Choose the background color when the button is disabled and on hover', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "reset_border",
				'selector' => "{{WRAPPER}} .action--reset",
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "reset_shadow",
				'selector' => "{{WRAPPER}} .action--reset",
			]
		);

		// reset icon.
		$this->add_control(
			'use_custom_reset_icon',
			[
				'label'   => __( 'Different Reset Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'reset_icon',
			[
				'label'       => __( 'Reset Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom reset icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'reset_icon_size',
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
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .action--reset svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .action--reset i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'reset_icon_color',
			[
				'label'       => __( 'Reset Icon Color (Disabled)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#d7d7d7',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .action--reset__disabled i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_reset_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		$this->add_control(
			'reset_icon_active',
			[
				'label'       => __( 'Reset Icon Color (Active)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#199473',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__active svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .action--reset__active i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		// Reset Text.
		$this->add_control(
			'heading_reset_text_style',
			[
				'label'     => __( 'Reset Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_text',
			[
				'label'       => __( 'Different Reset Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own reset text or leave empty to use default value', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'reset_text_color',
			[
				'label'       => __( 'Reset Text Color (Disabled)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#d7d7d7',
				'description' => __( 'Choose the text color when the reset button is disabled', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'reset_text_color_active',
			[
				'label'       => __( 'Reset Text Color (Active)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#199473',
				'description' => __( 'Choose the text color when the reset button is active', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__active span' => 'color: {{VALUE}};',
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

		$this->set_margin( 'label_margin', '.filters--label', 0, 0, 4, 0, false );

		$this->set_padding( 'label_padding', '.filters--label', 0, 0, 0, 0, false );

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
					'{{WRAPPER}} .filters--label' => 'display: flex; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'      => "search_filters_labels",
				'selector'  => "{{WRAPPER}} .filters--label",
				'separator' => 'before',
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

		$this->set_background_color( 'select_bg_color', '#f6f6f6', 'Background Color', '.filters div[class*=css-0]' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "select_border",
				'selector' => "{{WRAPPER}} .filters div[class*=css-0]",
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
				'name'      => "search_filters_select",
				'selector'  => "{{WRAPPER}} .filters div[class*=css-0]",
				'separator' => 'before',
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
				'name'     => "search_filters_select_dropdown",
				'selector' => "{{WRAPPER}} .filters div[class*=-option]",
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
		$this->set_background_color( 'price_bg_color', '#f6f6f6', 'Background Color', '.filters .field--with-icon' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "price_border",
				'selector' => "{{WRAPPER}} .filters .field--with-icon",
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
				'name'     => "price_filters_select",
				'selector' => "{{WRAPPER}} .filters .field--with-icon__label",
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
				'name'     => "price_filters_select_placeholder",
				'selector' => "{{WRAPPER}} .filters input",
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
					'{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox' => 'width: calc(100% / {{VALUE}});',
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

		$this->set_padding( 'checkbox_label_padding', '.search-taxonomy.checkbox .field--checkbox label', 0, 0, 0, 12, false );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "checkbox_typography",
				'selector' => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox",
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
		$this->set_background_color( 'checkbox_bg_color', '#f6f6f6', 'Background Color', '.search-taxonomy.checkbox .field--checkbox input' );
		$this->set_background_color( 'checkbox_bg_color_active', '#2186eb', 'Background Color', '.search-taxonomy.checkbox .field--checkbox input::after' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "checkbox_shadow",
				'selector' => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input",
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
				'name'     => "checkbox_border",
				'selector' => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input",
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
					'{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function button_search() {
		$this->start_controls_section(
			'submit_button',
			[
				'label' => __( 'Submit Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_button_icon',
			[
				'label'   => __( 'Different Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'button_submit_icon',
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
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
					'{{WRAPPER}} .btn--search svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .btn--search i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .btn--search svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .btn--search i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			'button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'button_bg_color', '#0967d2', 'Background Color', '.btn--search' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "button_border",
				'selector' => "{{WRAPPER}} .btn--search",
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
				'name'     => "button_shadow",
				'selector' => "{{WRAPPER}} .btn--search",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "button_typography",
				'selector' => "{{WRAPPER}} .btn--search",
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
		$this->start_controls_tab( 'button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'button_bg_color_hover', '#03449e', 'Background Color', '.btn--search:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "button_border_hover",
				'selector' => "{{WRAPPER}} .btn--search:hover",
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
				'name'     => "button_shadow_hover",
				'selector' => "{{WRAPPER}} .btn--search:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "button_typography_hover",
				'selector' => "{{WRAPPER}} .btn--search:hover",
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

	public function button_detailed() {
		$this->start_controls_section(
			'detailed_button',
			[
				'label' => __( 'Detailed Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// button text.
		$this->add_control(
			'd_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_d_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'd_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_d_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'd_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'd_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'd_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'd_button_bg_color', '#e6f6ff', 'Background Color', '.btn--light' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "d_button_border",
				'selector' => "{{WRAPPER}} .btn--light",
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
					'color'  => [ 'default' => 'rgba(186, 227, 255, 1)' ],
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
				'name'     => "d_button_shadow",
				'selector' => "{{WRAPPER}} .btn--light",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "d_button_typography",
				'selector' => "{{WRAPPER}} .btn--light",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(33, 134, 235, 1)'
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

		$this->end_controls_tab();

		// hover button values.
		$this->start_controls_tab( 'd_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'd_button_bg_color_hover', '#e6f6ff', 'Background Color', '.btn--light:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "d_button_border_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
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
					'color'  => [ 'default' => 'rgba(186, 227, 255, 1)' ],
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
				'name'     => "d_button_shadow_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "d_button_typography_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(33, 134, 235, 1)'
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

		include lisfinity_get_template_part( 'sidebar-filter', 'shortcodes/search-page', $args );
	}

}
