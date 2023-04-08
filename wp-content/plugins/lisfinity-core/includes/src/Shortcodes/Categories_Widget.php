<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Widget_Base;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Title_Typography;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;

class Categories_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'category-box';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Category Box', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-square-o';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity', 'lisfinity-category' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'categories_feed',
			[
				'label' => __( 'Choose Category', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | types.
		$groups_model = new GroupsAdminModel();
		$this->add_control(
			'category',
			[
				'label'       => __( 'Choose Category', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => false,
				'options'     => $groups_model->format_options_for_select( true, true ),
				'default'     => 'common',
				'placeholder' => __( 'All categories', 'lisfinity-core' ),
				'description' => __( 'Choose the category you wish to display. Leave empty to display all of them.', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		$taxonomy_model = new TaxonomiesAdminModel();
		$groups         = $groups_model->get_groups_slugs();
		$options        = $groups_model->get_options();
		$groups[]       = 'common';
		$options[]      = [
			'single_name' => 'Common',
			'plural_name' => 'Commons',
			'slug'        => 'common',
		];
		$slugs          = array_column( $options, 'slug' );
		foreach ( $groups as $group ) {
			$group_key = array_search( $group, $slugs );
			$this->start_controls_section(
				"taxonomies_feed-{$group}",
				[
					'label'     => sprintf( __( 'Choose %s Taxonomies', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ?? $group ),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => [
						'category' => $group,
					],
				]
			);
			$taxonomies = $taxonomy_model->get_taxonomies_by_group( $group, false );
			$this->add_control(
				"taxonomy[{$group}]",
				[
					'label'       => __( 'Choose taxonomies to display', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => $taxonomies,
					'description' => __( 'Choose taxonomy from which you wish to display terms.', 'lisfinity-core' ),
					'default'     => ! empty( $taxonomies ) ? [ array_keys( $taxonomies )[0] ] : [],
				]
			);
			$this->add_responsive_control(
				"taxonomy-columns[{$group}]",
				[
					'label'       => __( 'Break Taxonomies Into Columns', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::NUMBER,
					'default'     => 1,
					'min'         => 1,
					'max'         => 6,
					'description' => __( 'Choose the number of columns you wish to break taxonomies', 'lisfinity-core' ),
					'selectors'   => [
						'{{WRAPPER}} .category-box--terms li' => 'width: calc(100% / {{VALUE}});',
					],
				]
			);
			$this->add_responsive_control(
				"taxonomy-columns[{$group}]-gap",
				[
					'label'       => __( 'Taxonomies Columns Gap', 'lisfinity-core' ),
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
						'size' => 0,
					],
					'description' => __( 'Choose the number of columns you wish to break taxonomies', 'lisfinity-core' ),
					'selectors'   => [
						'{{WRAPPER}} .category-box--terms li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .category-box--terms ul' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				"taxonomy-hide-empty[{$group}]",
				[
					'label'       => __( 'Hide Empty?', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'yes' => __( 'Yes', 'lisfinity-core' ),
						'no'  => __( 'No', 'lisfinity-core' ),
					],
					'default'     => 'no',
					'description' => __( 'Choose if you wish to hide the empty terms.', 'lisfinity-core' ),
				]
			);
			$this->add_control(
				"taxonomy-limit[{$group}]",
				[
					'label'       => __( 'Taxonomies Limit', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::NUMBER,
					'placeholder' => 12,
					'default'     => 12,
					'description' => __( 'Set the maximum number of taxonomies that should be displayed.', 'lisfinity-core' ),
				]
			);
			$this->end_controls_section();
		}

		// title section.
		$this->title_section();


		// taxonomies style.
		$this->taxonomies_section();

		// box style.
		$this->box_style();

		// Category feeds.
		$this->start_controls_section(
			'show_more_button',
			[
				'label' => __( 'Show More Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->show_more_button();

		$this->end_controls_section();
	}

	protected function title_section() {
		$this->start_controls_section(
			'categories_title',
			[
				'label' => __( 'Box Title Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => __( 'Box Title', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Category', 'lisfinity-core' ),
				'separator'   => 'after',
			]
		);
		$this->add_control(
			'link',
			[
				'label'   => __( 'Link', 'lisfinity-core' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => __( 'HTML Tag', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h5',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'lisfinity-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .category-box--title' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title-margin',
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box--title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => '20',
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);
		$this->add_responsive_control(
			'title-padding',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box--title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);

		$this->set_heading_section( 'title_style_heading', 'Title Style', 'title_style_hr' );
		$this->add_control(
			'title-color',
			[
				'label'       => __( 'Text Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#323232',
				'description' => __( 'Set the overlay for the products background images.', 'lisfinity-core' ),
				'selectors'   => [ '{{WRAPPER}} .category-box--title .category-box--title__cst' => 'color: {{VALUE}}' ],
			]
		);

		$this->add_group_control(
			Group_Control_Category_Title_Typography::get_type(),
			[
				'name'     => 'category_title_cap_typography',
				'selector' => '{{WRAPPER}} .category-box--title .category-box--title__cst',
			]
		);

		$this->add_control(
			'title-background-color',
			[
				'label'       => __( 'Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'transparent',
				'description' => __( 'Set the background color of the title.', 'lisfinity-core' ),
				'selectors'   => [ '{{WRAPPER}} .category-box--title' => 'background-color: {{VALUE}}' ],
			]
		);

		$this->add_control(
			'custom_title_position',
			[
				'label'        => __( 'Use Custom Position', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'position_vertical_title',
			[
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
				'label' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-box--title' => "top: {{SIZE}}{{UNIT}}; position: absolute; z-index: 5;",
				],
				'condition' => [
					'custom_title_position' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'position_horizontal_title',
			[
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
				'label' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-box--title' => "left: {{SIZE}}{{UNIT}}; position: absolute; z-index: 5;",
				],
				'condition' => [
					'custom_title_position' => 'yes'
				]
			]
		);


		$this->set_heading_section( 'category_ads_count_heading', 'Category Ads Count Style', 'category_ads_count_hr' );

		$this->add_control(
			'title-ads-count',
			[
				'label'       => __( 'Display', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'yes' => esc_html__( 'Yes', 'lisfinity-core' ),
					'no'  => esc_html__( 'No', 'lisfinity-core' ),
				],
				'default'     => 'yes',
				'description' => __( 'Choose if you wish to display category ads count number', 'lisfinity-core' )
			]
		);
		$this->add_control(
			'title-ads-count-size',
			[
				'label'       => __( 'Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em', 'rem', 'vw' ],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
					'vw' => [
						'min'  => 0.1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [ '{{WRAPPER}} .category-box--title .category-box--title-ads-count' => 'font-size: {{SIZE}}{{UNIT}}' ],
				'condition'   => [
					'title-ads-count' => 'yes',
				],
			]
		);
		$typo_weight_options = [
			'' => __( 'Default', 'lisfinity-core' ),
		];

		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[ $weight ] = ucfirst( $weight );
		};

		$this->add_control(
			'title-ads-count-weight',
			[
				'label'       => __( 'Weight', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 700,
				'options'     => $typo_weight_options,
				'selectors'   => [ '{{WRAPPER}} .category-box--title .category-box--title-ads-count' => 'font-weight: {{VALUE}}' ],
				'condition'   => [
					'title-ads-count' => 'yes',
				],
			]
		);
		$this->add_control(
			'title-count-color',
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2186eb',
				'selectors' => [ '{{WRAPPER}} .category-box--title .category-box--title-ads-count' => 'color: {{VALUE}}' ],
				'condition' => [
					'title-ads-count' => 'yes',
				],
			]
		);

		$this->set_heading_section( 'category_ads_count_offset_heading', 'Category Ads Count Offset', 'category_ads_count_offset_hr' );

		$this->add_responsive_control(
			'title-offset-x',
			[
				'label'      => __( 'Horizontal', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => '-2',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .category-box--title .category-box--title-ads-count' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .category-box--title .category-box--title-ads-count'       => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'title-ads-count' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'title-offset-y',
			[
				'label'      => __( 'Vertical', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => '2',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body {{WRAPPER}} .category-box--title .category-box--title-ads-count' => 'top: {{SIZE}}{{UNIT}}:',
				],
				'condition'  => [
					'title-ads-count' => 'yes',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function taxonomies_section() {
		$this->start_controls_section(
			'categories_taxonomies_style',
			[
				'label' => __( 'Taxonomies Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'taxonomy-color',
			[
				'label'       => __( 'Text Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#4c4c4c',
				'description' => __( 'Set the overlay for the products background images.', 'lisfinity-core' ),
				'selectors'   => [ '{{WRAPPER}} .category-box--terms ul li a' => 'color: {{VALUE}}' ],
			]
		);
		$this->add_control(
			'taxonomy-color-hover',
			[
				'label'       => __( 'Text Color on Hover', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#2186eb',
				'description' => __( 'Set the overlay for the products background images.', 'lisfinity-core' ),
				'selectors'   => [ '{{WRAPPER}} .category-box--terms ul li a:hover' => 'color: {{VALUE}}' ],
			]
		);

		$this->add_group_control(
			Group_Control_Category_Typography::get_type(),
			[
				'name'     => 'category_taxonomies_typography',
				'selector' => '{{WRAPPER}} .category-box--terms ul li a',
				'exclude'  => [
				],
			]
		);

		$this->add_responsive_control(
			'taxonomy-margin',
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box--terms' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => '20',
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);
		$this->add_responsive_control(
			'taxonomy-padding',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box--terms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);


		$this->add_responsive_control(
			'taxonomies-width',
			[
				'label'       => __( 'Taxonomies Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ '%' ],
				'range'       => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'   => [
					'{{WRAPPER}} .category-box--terms' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'taxonomy-spacing',
			[
				'label'      => __( 'Terms Spacing', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors'  => [
					'{{WRAPPER}} .category-box--terms li' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-box--terms ul' => 'margin-top: -{{SIZE}}{{UNIT}}; margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'custom_taxonomies_position',
			[
				'label'        => __( 'Use Custom Position', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'position_vertical_taxonomies',
			[
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
					'size' => 20,
				],
				'label' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-box--terms' => "top: {{SIZE}}{{UNIT}}; position: absolute; z-index: 5;",
				],
				'condition' => [
					'custom_taxonomies_position' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'position_horizontal_taxonomies',
			[
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
					'size' => 20,
				],
				'label' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-box--terms' => "left: {{SIZE}}{{UNIT}}; position: absolute; z-index: 5;",
				],
				'condition' => [
					'custom_taxonomies_position' => 'yes'
				]
			]
		);

		$this->set_heading_section( 'terms_ads_count_style_heading', 'Terms Ads Count', 'terms_ads_count_style_hr' );

		// terms control.
		$this->add_control(
			'terms-count',
			[
				'label'       => __( 'Display', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'yes' => esc_html__( 'Yes', 'lisfinity-core' ),
					'no'  => esc_html__( 'No', 'lisfinity-core' ),
				],
				'default'     => 'yes',
			]
		);
		$this->add_control(
			'terms-count-size',
			[
				'label'       => __( 'Terms Ads Count Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em', 'rem', 'vw' ],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
					'vw' => [
						'min'  => 0.1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'   => [ '{{WRAPPER}} .category-box--terms .category-box--terms-count' => 'font-size: {{SIZE}}{{UNIT}}' ],
				'condition'   => [
					'terms-count' => 'yes',
				],
			]
		);
		$typo_weight_options = [
			'' => __( 'Default', 'lisfinity-core' ),
		];

		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[ $weight ] = ucfirst( $weight );
		};

		$this->add_control(
			'terms-count-weight',
			[
				'label'       => __( 'Weight', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 600,
				'options'     => $typo_weight_options,
				'selectors'   => [ '{{WRAPPER}} .category-box--terms .category-box--terms-count' => 'font-weight: {{VALUE}}' ],
				'condition'   => [
					'terms-count' => 'yes',
				],
			]
		);

		$this->add_control(
			'terms-count-color',
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c4c4c4',
				'selectors' => [ '{{WRAPPER}} .category-box--terms .category-box--terms-count' => 'color: {{VALUE}}' ],
				'condition' => [
					'terms-count' => 'yes',
				],
			]
		);

		$this->set_heading_section( 'terms_ads_count_position_heading', 'Terms Ads Count Offset', 'terms_ads_count_position_hr' );

		$this->add_responsive_control(
			'terms-offset-x',
			[
				'label'      => __( 'Horizontal', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => '-4',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .category-box--terms .category-box--terms-count' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .category-box--terms .category-box--terms-count'       => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'terms-count' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'terms-offset-y',
			[
				'label'      => __( 'Vertical', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => '1',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body {{WRAPPER}} .category-box--terms .category-box--terms-count' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'terms-count' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// background image.
		$this->category_image();
	}

	protected function box_style() {
		$this->start_controls_section(
			'category_box_style',
			[
				'label' => __( 'Box Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'box-background-color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-box' => 'background-color: {{VALUE}}',
				],
				'default'   => '#ffffff',
			]
		);

		$this->add_control(
			'custom_box_height',
			[
				'label'        => __( 'Use Custom Height', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		$this->add_responsive_control(
			'box_height',
			[
				'label'       => __( 'Height', 'lisfinity-core' ),
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
					'unit' => 'px',
					'size' => 245,
				],
				'selectors'   => [
					"{{WRAPPER}} .category-box" => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'custom_box_height' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'padding',
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => 30,
					'right'    => 30,
					'bottom'   => 30,
					'left'     => 30,
					'isLinked' => true,
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_border',
			[
				'label' => __( 'Box Border', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_border' );

		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .category-box',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Box Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'     => 'px',
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .category-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Category_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .category-box',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	protected function category_image() {
		$this->start_controls_section(
			'box_position',
			[
				'label' => __( 'Box Image', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'background-image',
			[
				'label'       => __( 'Display Background Image', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'yes',
				'options'     => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'No', 'lisfinity-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'background-image-url',
			[
				'label'       => __( 'Background Image', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'background-image-overlay',
			[
				'label'       => __( 'Display Image Overlay', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'yes',
				'options'     => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'No', 'lisfinity-core' ),
				],
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'background-image-overlay-color',
			[
				'label'     => __( 'Image Overlay Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .category-box--overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'background-image-overlay' => 'yes',
				],
			]
		);

		$this->set_heading_section( 'background_image_size_heading', 'Image Size', 'background_image_size_hr' );

		$this->add_responsive_control(
			'image-fit',
			[
				'label'       => __( 'Image Fit Mode', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'    => __( 'Default', 'lisfinity-core' ),
					'contain'    => __( 'Contain', 'lisfinity-core' ),
					'cover'      => __( 'Cover', 'lisfinity-core' ),
					'fill'       => __( 'Fill', 'lisfinity-core' ),
					'scale-down' => __( 'Scale Down', 'lisfinity-core' ),
				],
				'default'     => 'cover',
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg img' => 'object-fit: {{VALUE}}',
				],
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image-height',
			[
				'label'       => __( 'Custom Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 180,
				],
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}}; z-index: 2;',
				],
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'_image_width',
			[
				'label'                => __( 'Width', 'lisfinity-core' ),
				'label_block'          => true,
				'type'                 => Controls_Manager::SELECT,
				'default'              => '',
				'options'              => [
					''        => __( 'Default', 'lisfinity-core' ),
					'inherit' => __( 'Full Width', 'lisfinity-core' ) . ' (100%)',
					'auto'    => __( 'Inline', 'lisfinity-core' ) . ' (auto)',
					'initial' => __( 'Custom', 'lisfinity-core' ),
				],
				'selectors_dictionary' => [
					'inherit' => '100%',
				],
				'prefix_class'         => 'elementor-widget%s__width-',
				'selectors'            => [
					'{{WRAPPER}} .category-box--bg' => 'width: {{VALUE}}; max-width: {{VALUE}}',
				],
				'condition'            => [
					'background-image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'_image_custom_width',
			[
				'label'       => __( 'Custom Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'   => [
					'_image_width' => 'initial',
				],
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'condition' => [
							'_image_width_tablet' => [ 'initial' ],
						],
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'condition' => [
							'_image_width_mobile' => [ 'initial' ],
						],
					],
				],
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->set_heading_section( 'background_image_position_heading', 'Box Image Position', 'background_image_position_hr' );

		$this->add_responsive_control(
			'background-image-position',
			[
				'label'       => __( 'Box Image Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'options'     => [
					'top'    => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'     => 'top',
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);

		$this->add_control(
			'_image_position',
			[
				'label'              => __( 'Position', 'lisfinity-core' ),
				'label_block'        => true,
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''         => __( 'Default', 'lisfinity-core' ),
					'absolute' => __( 'Absolute', 'lisfinity-core' ),
					'fixed'    => __( 'Fixed', 'lisfinity-core' ),
				],
				'prefix_class'       => 'category-',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .category-box--bg' => 'position: {{VALUE}}',
				],
				'condition'          => [
					'background-image' => 'yes',
				],
			]
		);

		$start = is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );
		$end   = ! is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );

		$this->add_control(
			'_image_offset_orientation_h',
			[
				'label'       => __( 'Horizontal Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					],
					'end'   => [
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					],
				],
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .category-box--bg' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .category-box--bg'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 0.1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .category-box--bg' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .category-box--bg'       => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->add_control(
			'_image_offset_orientation_v',
			[
				'label'       => __( 'Vertical Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'end'   => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->set_heading_section( 'background_image_other_heading', 'Other Style', 'background_image_other_hr' );

		$this->add_responsive_control(
			'image-margin',
			[
				'label'       => __( 'Image Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .category-box--bg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 30,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'background-image' => 'yes'
				],
			]
		);
		$this->add_responsive_control(
			'image-padding',
			[
				'label'       => __( 'Image Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .category-box--image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'background-image' => 'yes'
				],
			]
		);

		$this->add_control(
			'image-background-color',
			[
				'label'       => __( 'Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'transparent',
				'description' => __( 'Set the background color of the image.', 'lisfinity-core' ),
				'selectors'   => [ '{{WRAPPER}} .category-box--bg' => 'background-color: {{VALUE}}' ],
			]
		);

		$this->add_responsive_control(
			'image-border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category-box--bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'background-image' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'category_image_border',
				'selector' => '{{WRAPPER}} .category-box--bg',
			]
		);
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'category_image_box_shadow',
				'selector' => '{{WRAPPER}} .category-box--bg',
			]
		);


		$this->end_controls_section();
	}

	protected function show_more_button() {
		$this->add_control(
			'display_show_more_button',
			[
				'label'        => __( 'Display Button', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'show_more_button_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Show More', 'elementor' ),
				'placeholder' => __( 'Show More', 'elementor' ),
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_more_button_url',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_more_button_color',
			[
				'label'     => __( 'Text Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4c4c4c',
				'selectors' => [ '{{WRAPPER}} .category--button' => 'color: {{VALUE}}' ],
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_control(
			'show_more_button_color_hover',
			[
				'label'     => __( 'Text Color on Hover', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2186eb',
				'selectors' => [ '{{WRAPPER}} .category--button:hover' => 'color: {{VALUE}}' ],
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Category_Typography::get_type(),
			[
				'name'      => 'show_more_button_typography',
				'selector'  => '{{WRAPPER}} .category--button',
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Category_Typography::get_type(),
			[
				'name'      => 'show_more_button_typography_hover',
				'selector'  => '{{WRAPPER}} .category--button:hover',
				'label'     => 'Typography on hover',
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'show_more_button_margin',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .category--button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'show_more_button_padding',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .category--button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_more_button_background_color',
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [ '{{WRAPPER}} .category--button' => 'background-color: {{VALUE}}' ],
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_control(
			'show_more_button_background_color_hover',
			[
				'label'     => __( 'Background Color on Hover', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [ '{{WRAPPER}} .category--button:hover' => 'background-color: {{VALUE}}' ],
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'show_more_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category--button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'      => 'show_more_button_border',
				'selector'  => '{{WRAPPER}} .category--button',
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'      => 'show_more_button_box_shadow',
				'selector'  => '{{WRAPPER}} .category--button',
				'condition' => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'button_horizontal_position',

			[
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
				'label'       => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category--button' => 'left: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'button_vertical_position',

			[
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
				'label'       => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category--button' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					'display_show_more_button' => 'yes'
				]
			]
		);
	}

	protected function render_title() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title'] ) ) {
			return '';
		}

		$this->add_render_attribute( 'title', 'class', 'category-box--title__cst' );

		$this->add_inline_editing_attributes( 'title' );

		$title = $settings['title'];

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

		return $title_html;
	}

	/**
	 * Heading function
	 * ------------------------------
	 */
	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'plugin-name' ),
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
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'settings'   => $settings,
			'category'   => $settings['category'],
			'taxonomies' => $settings["taxonomy[{$settings['category']}]"],
			'title'      => $this->render_title(),
		];

		include lisfinity_get_template_part( 'categories', 'shortcodes/categories', $args );
	}

}
