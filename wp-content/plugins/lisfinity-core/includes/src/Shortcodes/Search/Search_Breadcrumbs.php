<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Search_Breadcrumbs extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-breadcrumbs';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Breadcrumbs', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-home';
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
		$this->breadcrumb_structure();
		$this->breadcrumb_details();
		$this->breadcrumb_page();
	}

	public function breadcrumb_structure() {
		$this->start_controls_section(
			'search_breacrumbs_structure',
			[
				'label' => __( 'Bredcrumb Structure', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// icon switcher.
		$this->add_control(
			'breadcrumb_structure',
			[
				'label'     => __( 'Structure', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->set_margin( 'wrapper_margin', '.page-search-breadcrumbs', 0, 0, 0, 0, true );

		$this->set_padding( 'wrapper_padding', '.page-search-breadcrumbs', 0, 0, 0, 0, true );

		$this->set_background_color( 'wrapper_bg_color', 'transparent', 'Background Color', '.page-search-breadcrumbs' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .page-search-breadcrumbs',
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .page-search-breadcrumbs',
			]
		);

		$this->end_controls_section();
	}

	public function breadcrumb_details() {
		$this->start_controls_section(
			'search_breacrumbs',
			[
				'label' => __( 'Bredcrumb Details', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// icon switcher.
		$this->add_control(
			'breadcrumb_home_icon',
			[
				'label'     => __( 'Home Icon', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_icon',
			[
				'label'   => __( 'Different Home Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'home_icon',
			[
				'label'       => __( 'Home Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom home icon', 'lisfinity-core' ),
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
					'{{WRAPPER}} .breadcrumb__home svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .breadcrumb__home i'   => 'font-size: {{SIZE}}{{UNIT}};',
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
				'default'     => '#ef4e4e',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .breadcrumb__home svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .breadcrumb__home i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// typography.
		$this->add_control(
			'breadcrumb_typography',
			[
				'label'     => __( 'Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->breadcrumbs_detailed_tabs();

		$this->end_controls_section();
	}

	public function breadcrumbs_detailed_tabs() {
		$this->start_controls_tabs( 'button_active_tabs' );

		// normal values;
		$this->start_controls_tab( 'breadcrumb_default',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_detail',
				'selector'       => '{{WRAPPER}} .breadcrumb__home a',
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
						'default' => '#4c4c4c',
					],
					'font_weight' => [
						'default' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		// normal values;
		$this->start_controls_tab( 'breadcrumb_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_detail_hover',
				'selector'       => '{{WRAPPER}} .breadcrumb__home a:hover',
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
						'default' => '#4c4c4c',
					],
					'font_weight' => [
						'default' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		// normal values;
		$this->start_controls_tab( 'breadcrumb_current',
			[
				'label' => __( 'Current', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_detail_current',
				'selector'       => '{{WRAPPER}} .breadcrumb__home span',
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
						'default' => '#4c4c4c',
					],
					'font_weight' => [
						'default' => '700',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function breadcrumb_page() {
		$this->start_controls_section(
			'search_page_structure',
			[
				'label' => __( 'Breadcrumb Pages', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'page_typography',
			[
				'label'     => __( 'Pages Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_pages',
				'selector'       => '{{WRAPPER}} .page--information',
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
						'default' => '#686868',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
			]
		);
		$this->add_control(
			'page_typography_current_heading',
			[
				'label'     => __( 'Current Page Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'pages_typography_current',
				'selector'       => '{{WRAPPER}} .page--information span',
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
						'default' => '#262626',
					],
					'font_weight' => [
						'default' => '600',
					],
				],
			]
		);

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

		include lisfinity_get_template_part( 'search-breadcrumbs', 'shortcodes/search-page', $args );
	}

}
