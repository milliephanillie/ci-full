<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Number_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Text_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Typography;

class CategoriesCarousel_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'categories-carousel';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Category Carousel', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-angle-double-right';
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
				'label' => __( 'Category Types Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | template.
		/*$this->add_control(
			'template',
			[
				'label'       => __( 'Template', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'options'     => [
					'grid'     => __( 'Grid', 'lisfinity-core' ),
					'carousel' => __( 'Carousel', 'lisfinity-core' ),
				],
				'default'     => 'grid',
				'description' => __( 'Choose categories template that you wish to use between grid or carousel.', 'lisfinity-core' ),
			]
		);*/

		// control | types.
		$groups_model = new GroupsAdminModel();
		$this->add_control(
			'types',
			[
				'label'       => __( 'Category Types', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $groups_model->format_options_for_select( true ),
				'default'     => '',
				'description' => __( 'Choose category types that you wish to display. Leave empty to display them all.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-types' => 'justify-content: center;',
				]
			]
		);

		$taxonomy_model = new TaxonomiesAdminModel();
		//$groups         = $groups_model->get_groups_slugs();
		// disabled for the first theme version until we come up with more designs.
		$groups = [];
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$this->add_control(
					"taxonomy[{$group}]",
					[
						'label'       => sprintf( __( 'Choose %s Taxonomy to Display', 'lisfinity-core' ), '<strong>' . lisfinity_convert_slug_to_name( $group ) . '</strong>' ),
						'label_block' => true,
						'type'        => Controls_Manager::SELECT2,
						'multiple'    => false,
						'options'     => $taxonomy_model->get_taxonomies_by_group( $group, true, __( 'None', 'lisfinity-core' ) ),
						'default'     => '',
						'description' => __( 'Choose taxonomy from which you wish to display terms. Leave empty to disable it.', 'lisfinity-core' ),
					]
				);
			}
		}

		$this->add_control(
			'taxonomy-permalink',
			[
				'label'       => __( 'Category Permalink', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default' => __( 'Category Page', 'lisfinity-core' ),
					'search'  => __( 'Search Page', 'lisfinity-core' ),
				],
				'default'     => 'default',
				'description' => __( 'Choose what page will open when a user click on the category created in the fields builder.', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'categories_styles',
			[
				'label' => __( 'Category Types Display', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Style | Products Count
		$this->add_control(
			'products_count',
			[
				'label'        => __( 'Display Products Count?', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'lisfinity-core' ),
				'label_off'    => __( 'No', 'lisfinity-core' ),
				'return_value' => 'yes',
				'description'  => __( 'Choose if you wish to display products count in a box.', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		// Style | Box styles
		$this->start_controls_section(
			'categories_carousel_box_style',
			[
				'label' => __( 'Category Box Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->box_style_categories_carousel_settings();

		$this->end_controls_section();

		$this->start_controls_section(
			'categories_carousel_layout',
			[
				'label' => __( 'Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->category_carousel_layout();

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'categories_carousel_styles',
			[
				'label' => __( 'Category Text Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Products Count
		$this->category_carousel_text_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'categories_carousel_products_count_style',
			[
				'label' => __( 'Products Count Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_count_style();

		$this->end_controls_section();
	}

	/*
	 * Products layout settings.
	 * -------------------------
	 */
	public function category_carousel_layout() {
		$this->add_control(
			'category_items_number_desktop',
			[
				'label'       => __( 'How many categories to show on desktop', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'default'     => 7,
				'min'         => 1,
				'max'         => 20,
				'description' => __( 'Choose how many categories you wish to display on desktop. Changes won\'t be visible in Elementor.', 'lisfinity-core' ),
			]
		);
		$this->add_responsive_control(
			'category_columns',
			[
				'label'       => __( 'Break Categories Into Columns', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => 1,
				'max'         => 6,
				'description' => __( 'Choose the number of columns you wish to break categories', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-group' => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'category_columns_gap',
			[
				'label'       => __( 'Category Columns Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 16,
				],
				'description' => __( 'Choose the size of the gap.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-group' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap-y',
			[
				'label'       => __( 'Category Columns Gap Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 32,
				],
				'description' => __( 'Choose the size of the vertically gap.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-group' => 'margin-top:{{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * * Box Style
	 * -------------------------
	 */


	public function box_style_categories_carousel_settings() {
		$this->add_group_control(
			Group_Control_Category_Carousel_Box_Shadow::get_type(),
			[
				'name'     => 'category_carousel_border_box',
				'selector' => '{{WRAPPER}} .category-group--inner',
			]
		);

		// Style | Image overlay
		$this->add_control(
			'overlay',
			[
				'label'       => __( 'Images Overlay', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Set the overlay for the category types background images.', 'lisfinity-core' ),
			]
		);


		$this->set_border_radius( 'box_border_radius', '3', '3', '3', '3', 'px', 'Border radius', '.category-group--inner' );
	}


	/**
	 * * text style
	 * -------------------------
	 */


	public function category_carousel_text_style() {
		$this->add_group_control(
			Group_Control_Category_Carousel_Typography::get_type(),
			[
				'name'     => 'category_carousel_typography',
				'selector' => '{{WRAPPER}} .category-group--content .font-semibold',
			]
		);

		$this->set_text_color( 'category_carousel_text_color_id', 'Text Color', 'rgba(255, 255, 255, 1)', '.category-group--content .font-semibold' );

		$this->set_text_alignment( 'category_carousel_text_alignment_id', 'Text Alignment', 'left', '.category-group--content .font-semibold' );

		$this->set_heading_section( 'category_carousel_text_bg_heading', 'Text Background Style', 'category_carousel_text_bg_hr' );

		$this->set_background_color( 'category_carousel_text_bg_color_id', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20' );

		$this->set_border_radius( 'category_carousel_text_border_radius_id', '0', '0', '0', '0', 'px', esc_html__( 'Border Radius', 'lisfinity-core' ), '.category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20' );

		$this->add_group_control(
			Group_Control_Category_Carousel_Text_Box_Shadow::get_type(),
			[
				'name'     => 'category_carousel_text_box_shadow',
				'selector' => '{{WRAPPER}} .category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20',
			]
		);

		$this->add_control(
			'category_carousel_text_bg_width_id',

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
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'   => [
					'{{WRAPPER}} .category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'category_carousel_text_bg_padding_id',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 20,
					'right'    => 30,
					'bottom'   => 20,
					'left'     => 30,
					'isLinked' => true,
				]
			]
		);

		$this->set_heading_section( 'category_carousel_text_heading_position', 'Text Position', 'category_carousel_text_hr_position' );

		$this->add_control(
			'category_carousel_text_position_x',

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
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20' => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'category_carousel_text_position_y',

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
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .category-group--content.absolute.bottom-0.left-0.py-20.px-30.w-full.z-20' => 'bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);


	}


	/**
	 * * Product Count Style
	 * -------------------------
	 */

	public function products_count_style() {

		$this->set_width( 'products_count_width', '30', 'span.absolute.w-30.h-30.bg-grey-1000.opacity-50', '.absolute.top-20.right-20.flex-center.w-30.h-30.rounded-full.overflow-hidden.z-20' );

		$this->set_height( 'products_count_height', '30', 'span.absolute.w-30.h-30.bg-grey-1000.opacity-50', '.absolute.top-20.right-20.flex-center.w-30.h-30.rounded-full.overflow-hidden.z-20' );

		$this->set_background_color( 'products_count_bg_color', 'rgba(45, 45, 45, 0.5)', 'Background color', '.category-group--inner .absolute.rounded-full' );

		$this->set_border_radius( 'product_counts_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.category-group--inner .absolute.rounded-full' );

		$this->add_group_control(
			Group_Control_Category_Carousel_Number_Box_Shadow::get_type(),
			[
				'name'     => 'category_carousel_number_box_shadow',
				'selector' => '{{WRAPPER}} .category-group--inner .absolute.rounded-full',
			]
		);

		$this->set_font_size( 'products_count_text_size', '12', '.category-group--inner .absolute .relative.text-sm' );

		$this->set_text_color( 'products_text_color_id', 'Text Color', 'rgba(255, 255, 255, 1)', '.category-group--inner .absolute .relative.text-sm' );

		$this->set_heading_section( 'products_count_position_heading', 'Products Count Position', 'products_count_positioning_hr' );

		$this->set_element_position( 'products_count_position_x', '20', 'products_count_position_y', '20', '.category-group--inner .absolute.rounded-full' );
	}


	/**
	 * * functions
	 * -------------------------
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

	public function set_text_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $message, 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
				]
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

	public function set_width( $id, $default, $selector, $selector2 ) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector"  => 'width: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} $selector2" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_height( $id, $default, $selector, $selector2 ) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector"  => 'height: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} $selector2" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_unit, $message, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'top'    => $default_top,
					'right'  => $default_right,
					'bottom' => $default_bottom,
					'left'   => $default_left,
					'unit'   => $default_unit
				],
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};',
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
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}};',
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
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}};',
				]
			]
		);
	}

	public function set_text_alignment( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => $default,
				'toggle'      => true,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'text-align: {{VALUE}};',
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

		$groups_model = new GroupsAdminModel();
		$items        = $groups_model->get_groups_with_taxonomies();
		if ( ! empty( $items ) ) {
			if ( ! empty( $settings['types'] ) ) {
				$filtered = [];
				foreach ( $items as $group => $taxonomies ) {
					if ( in_array( $group, $settings['types'] ) ) {
						$filtered[ $group ] = $taxonomies;
					}
				}
			} else {
				$filtered = $items;
			}

			$settings['template'] = 'carousel';
			$args                 = [
				'settings'   => $settings,
				'categories' => $filtered,
			];
			$use_carousel         = 'grid' !== $settings['template'] && count( $filtered ) > 6;

			if ( $use_carousel ) {
				include lisfinity_get_template_part( 'category-types-carousel', 'shortcodes/category-types', $args );
			} else {
				include lisfinity_get_template_part( 'category-types-grid', 'shortcodes/category-types', $args );
			}
		}
	}

}
