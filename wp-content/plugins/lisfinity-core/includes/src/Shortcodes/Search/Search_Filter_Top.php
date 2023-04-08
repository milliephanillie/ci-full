<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;

class Search_Filter_Top extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-filter-top';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Filter Actions', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-sort-amount-up-alt';
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
		$this->structure();
		$this->sort_by();
		$this->map_settings();
	}

	public function structure() {
		$this->start_controls_section(
			'elements_structure',
			[
				'label' => __( 'Sort By Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'elements_position',
			[
				'label'       => __( 'Elements Position', 'lisfinity-core' ),
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
					'{{WRAPPER}} .search--action--right' => 'display: flex; justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function sort_by() {
		$this->start_controls_section(
			'sort_by',
			[
				'label' => __( 'Sort By Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'display_sortby',
			[
				'label'   => __( 'Display Sort By', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->set_margin( 'sortby_margin', '.search--action__sortby', 0, 40, 0, 0, false );

		$this->set_padding( 'sortby_padding', '.search--action__sortby', 0, 0, 0, 0, true );

		$this->set_background_color( 'sortby_bg_color', 'transparent', 'Background Color', '.search--action__sortby' );

		// icon switcher.
		$this->add_control(
			'sort_icon_heading',
			[
				'label' => __( 'Sort By Icon', 'lisfinity-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'use_custom_sort_icon',
			[
				'label'   => __( 'Different Sort By Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'sort_icon',
			[
				'label'       => __( 'Sort By Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom sort by icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_sort_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sort_icon_size',
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
					'{{WRAPPER}} .search--action__sortby svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .search--action__sortby i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_sort_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'sort_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#4c4c4c',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--action__sortby svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .search--action__sortby i'   => 'color: {{VALUE}};',
				],
			]
		);

		// sortby field label.
		$this->add_control(
			'sort_by_label',
			[
				'label'     => __( 'Sort By Label', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'sort_by_label_typography',
				'selector'       => '{{WRAPPER}} label[for="sortby"]',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 12,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#959595',
					],
					'font_weight' => [
						'default' => 'default',
					],
				],
			]
		);

		// sortby field.
		$this->add_control(
			'sort_by_value',
			[
				'label'     => __( 'Sort By Value', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'sort_by_value_typography',
				'selector'       => '{{WRAPPER}} .search--action__sortby div[class*="-singleValue"]',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 14,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#333333',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
			]
		);

		// sortby field.
		$this->add_control(
			'sort_by_dropdown',
			[
				'label'     => __( 'Sort By Dropdown', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sort_by_dropdown_background',
			[
				'label'       => __( 'Dropdown Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the background color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--action__sortby div[class*="-menu"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sort_by_dropdown_background_hover',
			[
				'label'       => __( 'Dropdown Background Color on Hover', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f6f6f6',
				'description' => __( 'Choose the background color for the hover element', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--action__sortby .css-xo7z33-option' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sort_by_dropdown_color_hover',
			[
				'label'       => __( 'Dropdown Item Color on Hover', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#333333',
				'description' => __( 'Choose the item color for the hover element', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--action__sortby .css-xo7z33-option' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'sort_by_dropdown_typography',
				'selector'       => '{{WRAPPER}} .search--action__sortby .css-fk865s-option',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 14,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#333333',
					],
					'font_weight' => [
						'default' => 'default',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function map_settings() {
		$this->start_controls_section(
			'map',
			[
				'label' => __( 'Show Map Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_warning',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'Default state of the show map field is controlled from the %s.', 'lisfinity-core' ), '<strong>Lisfinity Options -> Listings Setup</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'display_map',
			[
				'label'   => __( 'Display Map Option', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->set_margin( 'map_margin', '.search--action__map', 0, 0, 0, 0, false );

		$this->set_padding( 'map_padding', '.search--action__map', 0, 0, 0, 0, true );

		$this->set_background_color( 'map_bg_color', 'transparent', 'Background Color', '.search--action__map' );

		// icon switcher.
		$this->add_control(
			'map_icon_heading',
			[
				'label'     => __( 'Map Icon', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_map_icon',
			[
				'label'   => __( 'Different Map Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'map_icon',
			[
				'label'       => __( 'Map Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom map icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_map_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'map_icon_size',
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
					'{{WRAPPER}} .search--action__map svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .search--action__map i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_map_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'map_icon_color',
			[
				'label'       => __( 'Map Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#4c4c4c',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--action__map svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .search--action__map i'   => 'color: {{VALUE}};',
				],
			]
		);

		// map field label.
		$this->add_control(
			'map_label',
			[
				'label'     => __( 'Map Label', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'map_label_typography',
				'selector'       => '{{WRAPPER}} .search--action__map .toggle--label__label',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 12,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#959595',
					],
					'font_weight' => [
						'default' => 'default',
					],
				],
			]
		);

		// map field label.
		$this->add_control(
			'map_value_heading',
			[
				'label'     => __( 'Map Value', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'map_value_typography',
				'selector'       => '{{WRAPPER}} .search--action__map .toggle--label__value',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 12,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#333333',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
			]
		);

		$this->map_slider();

		$this->end_controls_section();
	}

	public function map_slider() {
		$this->add_control(
			'map_slider',
			[
				'label'     => __( 'Map Switcher', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'slider_tabs' );

		$this->start_controls_tab( 'slider_default',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'slider_background',
			[
				'label'       => __( 'Slider Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#efefef',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle .slider' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_border_color',
			[
				'label'       => __( 'Slider Border Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#d7d7d7',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle .slider' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'slider_button_color',
			[
				'label'       => __( 'Slider Button Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#7f7f7f',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle .slider::before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'slider_default_active',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'slider_background_active',
			[
				'label'       => __( 'Slider Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#e6f6ff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle input:checked + .slider' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_border_color_active',
			[
				'label'       => __( 'Slider Border Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#d7d7d7',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle input:checked + .slider' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'slider_button_color_active',
			[
				'label'       => __( 'Slider Button Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#0967d2',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .toggle input:checked + .slider::before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
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

		include lisfinity_get_template_part( 'search-filter-top', 'shortcodes/search-page', $args );
	}

}
