<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\REST_API\Taxonomies\TaxonomyRoute;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Number_Of_Terms_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Four;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Three;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Two;

class Taxonomies_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'taxonomies';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Taxonomies', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-opencart';
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
		$this->start_controls_section(
			'taxonomies_feed',
			[
				'label' => __( 'Taxonomies Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | types.
		$this->add_control(
			'taxonomy',
			[
				'label'       => __( 'Taxonomy', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => false,
				'options'     => $this->get_taxonomies_select(),
				'default'     => '',
				'description' => __( 'Choose category types from which you wish to display taxonomies. Leave empty to display them dynamically where possible.', 'lisfinity-core' ),
			]
		);

		// control | hide empty.
		$this->add_control(
			'hide_empty',
			[
				'label'       => __( 'Hide Empty?', 'lisfinity-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'options'     => [
					'no'  => __( 'Hide', 'lisfinity-core' ),
					'yes' => __( 'Show', 'lisfinity-core' ),
				],
				'default'     => 'no',
				'description' => __( 'Choose if the terms without any associated products should be visible.', 'lisfinity-core' ),
			]
		);

		// control | hide empty.
		$this->add_control(
			'number',
			[
				'label'       => __( 'Number of Terms', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 3,
				'default'     => 3,
				'description' => __( 'Choose the number of term you wish to display', 'lisfinity-core' ),
			]
		);

		// control | handpick terms.
		$taxonomies = get_object_taxonomies( 'product', '' );
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy => $taxonomy_object ) {
				$this->add_control(
					"handpicked[${taxonomy}]",
					[
						'label'       => __( 'Handpick Terms', 'lisfinity-core' ),
						'type'        => Controls_Manager::SELECT2,
						'multiple'    => true,
						'options'     => $this->get_terms_select( $taxonomy ),
						'default'     => '',
						'description' => __( 'Manually choose the terms you', 'lisfinity-core' ),
						'condition'   => [ 'taxonomy' => $taxonomy ],
					]
				);
			}
		}

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'taxonomies_styles',
			[
				'label' => __( 'Taxonomies Types Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Image overlay
		$this->add_control(
			'style',
			[
				'label'       => __( 'Template Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'1' => __( 'Style 1', 'lisfinity-core' ),
					'2' => __( 'Style 2', 'lisfinity-core' ),
					'3' => __( 'Style 3', 'lisfinity-core' ),
					'4' => __( 'Style 4', 'lisfinity-core' ),
				],
				'default'     => '1',
				'description' => __( 'Choose taxonomies display style.', 'lisfinity-core' ),
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

		// Style | Products Count Suffix
		$this->add_control(
			'suffix',
			[
				'label'       => __( 'Listings Count Suffix (Singular)', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'listing', 'lisfinity-core' ),
				'description' => __( 'Change products count suffix to something other than <strong>products</strong>.', 'lisfinity-core' ),
			]
		);

		// Style | Products Count Suffix
		$this->add_control(
			'suffix_plural',
			[
				'label'       => __( 'Listings Count Suffix (Plural)', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'listings', 'lisfinity-core' ),
				'description' => __( 'Change products count suffix to something other than <strong>products</strong>.', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		// Style | Taxonomies Layout .

		$this->start_controls_section(
			'taxonomy_layout',
			[
				'label' => __( 'Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_layout();

		$this->end_controls_section();

		// Style | Taxonomies box .

		$this->start_controls_section(
			'taxonomy_box',
			[
				'label' => __( 'Box Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->box_style_settings();

		$this->end_controls_section();

		// Style | Taxonomies box .

		$this->start_controls_section(
			'taxonomy_content_tab',
			[
				'label'     => __( 'Content Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '1',
				],
			]
		);
		$this->content_style_settings();

		$this->end_controls_section();

		// Style 2 | Taxonomies term  .

		$this->start_controls_section(
			'taxonomy_term_style_two_tab',
			[
				'label'     => __( 'Content Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '2',
				],
			]
		);
		$this->taxonomy_term_style_two_settings();

		$this->end_controls_section();

		// Style 2 | Taxonomies sorting term  .

		$this->start_controls_section(
			'taxonomy_content_sorting_two_tab',
			[
				'label'     => __( 'Content Positioning', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style'           => '2',
					'products_count!' => 'yes'
				],
			]
		);
		$this->taxonomy_content_sorting_two_settings();

		$this->end_controls_section();


		// Style 3 | Taxonomies term  .

		$this->start_controls_section(
			'taxonomy_term_style_three_tab',
			[
				'label'     => __( 'Content Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '3',
				],
			]
		);
		$this->taxonomy_term_style_three_settings();

		$this->end_controls_section();

		// Style 3 | Taxonomies term  .

		$this->start_controls_section(
			'taxonomy_content_sorting_three_tab',
			[
				'label'     => __( 'Content Positioning', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '3',
				],
			]
		);
		$this->taxonomy_content_sorting_three_settings();

		$this->end_controls_section();

		// Style 4 | Taxonomies term  .

		$this->start_controls_section(
			'taxonomy_term_style_four_tab',
			[
				'label'     => __( 'Content Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '4',
				],
			]
		);
		$this->taxonomy_term_style_four_settings();

		$this->end_controls_section();

		// Style 4 | Taxonomies term  .

		$this->start_controls_section(
			'taxonomy_content_sorting_four_tab',
			[
				'label'     => __( 'Content Positioning', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '4',
				],
			]
		);
		$this->taxonomy_content_sorting_four_settings();

		$this->end_controls_section();

	}


	/*
	 * Products layout settings.
	 * -------------------------
	 */
	public function products_layout() {

		$this->layout( 'taxonomies_columns_1', '.taxonomies .container .row .mt-16', '.taxonomies .container .row', 'taxonomies_columns_gap_1', '16', 'taxonomies_columns_gap_y_1', '32', '1', [
			'desktop_default' => 3,
			'tablet_default'  => 2,
			'mobile_default'  => 1,
		] );

		$this->layout( 'taxonomies_columns_2', '.taxonomies .container .row .px-8', '.taxonomies .container .row', 'taxonomies_columns_gap_2', '16', 'taxonomies_columns_gap_y_2', '32', '2', [
			'desktop_default' => 6,
			'tablet_default'  => 4,
			'mobile_default'  => 2,
		] );

		$this->layout( 'taxonomies_columns_3', '.taxonomies .taxonomy', '.taxonomies', 'taxonomies_columns_gap_3', '16', 'taxonomies_columns_gap_y_3', '32', '3', [
			'desktop_default' => 6,
			'tablet_default'  => 3,
			'mobile_default'  => 1,
		] );

		$this->layout( 'taxonomies_columns_4', '.category-types .category-group', '.category-types', 'taxonomies_columns_gap_4', '8', 'taxonomies_columns_gap_y_4', '32', '4', [
			'desktop_default' => 6,
			'tablet_default'  => 4,
			'mobile_default'  => 2,
		] );

	}


	/**
	 * * Box Style
	 * -------------------------
	 */


	public function box_style_settings() {
		$this->add_group_control(
			Group_Control_Taxonomies_Box_Shadow::get_type(),
			[
				'name'     => 'taxonomies_border_box',
				'selector' => '{{WRAPPER}} .taxonomy--term',
			]
		);

		$this->set_background_color( 'taxonomies_background_color_testimonials', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.taxonomy--term' );

		$this->add_responsive_control(
			'background_image_overlay_taxonomies',
			[
				'label'       => __( 'Display Image Overlay', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'yes',
				'options'     => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'No', 'lisfinity-core' ),
				]
			]
		);
		$this->add_responsive_control(
			'background_image_overlay_color_taxonomies',
			[
				'label'     => __( 'Image Overlay Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .taxonomies-box--overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'background_image_overlay_taxonomies' => 'yes',
				],
			]
		);

		$this->set_border_radius( 'taxonomies_border_radius_testimonials', '3', '3', '3', '3', 'px', esc_html__( 'Border radius', 'lisfinity-core' ), '.taxonomy--term', '1' );
		$this->set_border_radius( 'taxonomies_border_radius_testimonials_2', '3', '3', '3', '3', 'px', esc_html__( 'Border radius', 'lisfinity-core' ), '.taxonomy--term', '2' );
		$this->set_border_radius( 'taxonomies_border_radius_testimonials_3', '3', '3', '3', '3', 'px', esc_html__( 'Border radius', 'lisfinity-core' ), '.taxonomy--term', '3' );
		$this->set_border_radius( 'taxonomies_border_radius_testimonials_4', '3', '3', '3', '3', 'px', esc_html__( 'Border radius', 'lisfinity-core' ), '.category-types .category-group .category-group--inner', '4' );

	}


	/**
	 * * Content Style
	 * -------------------------
	 */


	public function content_style_settings() {

		$this->set_heading_section( 'taxonomies_heading', esc_html__( 'Taxonomy', 'lisfinity-core' ), 'hr_taxonomies' );

		$this->add_group_control(
			Group_Control_Taxonomies_Typography::get_type(),
			[
				'name'     => 'taxonomies_typography',
				'selector' => '{{WRAPPER}} .taxonomy--content .font-semibold',
			]
		);


		$this->set_text_color( 'taxonomies_text_color_id', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.taxonomy--content .font-semibold' );

		$this->set_heading_section( 'taxonomies_number_of_terms', esc_html__( 'Number of Terms', 'lisfinity-core' ), 'hr_number_of_terms' );

		$this->set_text_color( 'number_of_terms_color_id', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.taxonomy--content' );

		$this->set_font_size( 'number_of_terms_size_id', '14', '.taxonomy--content' );


		$this->set_heading_section( 'taxonomies_alignment_heading', esc_html__( 'Text Position', 'lisfinity-core' ), 'hr_alignment_taxonomies' );


		$this->add_control(
			'taxonomies_text_position_x_id',

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
					'{{WRAPPER}} .taxonomy--content'       => 'position: relative; right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-group--content' => 'position: relative; right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'taxonomies_text_position_y_id',

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
					'{{WRAPPER}} .taxonomy--content' => 'position: relative; top: {{SIZE}}{{UNIT}};',
				]
			]
		);


	}


	/**
	 * * Term Style 2
	 * -------------------------
	 */


	public function taxonomy_term_style_two_settings() {

		$this->set_heading_section( 'taxonomies_heading_two', esc_html__( 'Taxonomy', 'lisfinity-core' ), 'hr_taxonomies_two' );

		$this->add_group_control(
			Group_Control_Taxonomies_Typography_Style_Two::get_type(),
			[
				'name'     => 'taxonomies_typography_style_two',
				'selector' => '{{WRAPPER}} .taxonomy--term .mt-20',
			]
		);


		$this->set_text_color( 'taxonomies_text_color_id_style_two', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(0, 0, 0, 1)', '.taxonomy--term .mt-20' );

		$this->set_heading_section( 'taxonomies_number_of_terms_style_two', esc_html__( 'Number of Terms', 'lisfinity-core' ), 'hr_number_of_terms_style_two' );

		$this->set_text_color( 'number_of_terms_color_id_style_two', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(0, 0, 0, 1)', '.taxonomy--term' );

		$this->set_font_size( 'number_of_terms_size_id_style_two', '14', '.taxonomy--term' );


	}

	/**
	 * * Sorting Style 2
	 * -------------------------
	 */


	public function taxonomy_content_sorting_two_settings() {

		$this->sort_elements( 'sort_taxonomy_term_two', esc_html__( 'Taxonomy Term', 'lisfinity-core' ), '2', '2', '.taxonomy--term .mt-20' );

		$this->sort_elements( 'sort_taxonomy_image_two', esc_html__( 'Taxonomy Image', 'lisfinity-core' ), '1', '2', '.taxonomy--term .taxonomy--image' );

	}


	/**
	 * * Term Style 3
	 * -------------------------
	 */


	public function taxonomy_term_style_three_settings() {

		$this->set_heading_section( 'taxonomies_heading_three', esc_html__( 'Taxonomy', 'lisfinity-core' ), 'hr_taxonomies_three' );

		$this->add_group_control(
			Group_Control_Taxonomies_Typography_Style_Three::get_type(),
			[
				'name'     => 'taxonomies_typography_style_three',
				'selector' => '{{WRAPPER}} .taxonomy--term .px-24',
			]
		);


		$this->set_text_color( 'taxonomies_text_color_id_style_three', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(0, 0, 0, 1)', '.taxonomy--term .px-24' );

		$this->set_padding( 'id_taxonomies_three_padding', '.taxonomy--term .px-24', '0', '24', '0', '24', 'true' );

		$this->set_heading_section( 'taxonomies_number_of_terms_three', esc_html__( 'Number of Terms Text Style', 'lisfinity-core' ), 'hr_number_of_terms_three' );

		$this->set_text_color( 'number_of_terms_color_id_three', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.taxonomy--term .category-group--count' );

		$this->set_font_size( 'number_of_terms_size_id_three', '12', '.taxonomy--term .category-group--count' );

		$this->set_heading_section( 'taxonomies_number_of_terms_three_size', esc_html__( 'Number of Terms Box Style', 'lisfinity-core' ), 'hr_number_of_terms_three_size' );

		$this->add_control(
			'number_of_terms_width_id',
			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'default'     => [
					'unit' => 'px',
					'size' => 32
				],
				'selectors'   => [
					"{{WRAPPER}} .taxonomy--term .category-group--count" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_of_terms_height_id',
			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'default'     => [
					'unit' => 'px',
					'size' => 32
				],
				'selectors'   => [
					"{{WRAPPER}} .taxonomy--term .category-group--count" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_background_color( 'number_of_terms_id_three', 'rgba(76 76 76, 1)', 'Background Color', '.taxonomy--term .category-group--count' );

		$this->add_group_control(
			Group_Control_Taxonomies_Number_Of_Terms_Box_Shadow::get_type(),
			[
				'name'     => 'taxonomies_number_of_terms_box_shadow_style_three',
				'selector' => '{{WRAPPER}} .taxonomy--term .category-group--count',
			]
		);

		$this->set_border_radius( 'number_of_terms_border_radius_three', '50', '50', '50', '50', '%', 'Border Radius', '.taxonomy--term .category-group--count', '3' );


	}


	/**
	 * * Sorting Style 3
	 * -------------------------
	 */


	public function taxonomy_content_sorting_three_settings() {

		$this->display_element( 'sorting_elements_display_three', esc_html__( 'Sort elements', 'lisfinity-core' ) );

		$this->set_heading_section_condition( 'taxonomies_number_of_terms_three_position', esc_html__( 'Number of Terms', 'lisfinity-core' ), 'hr_number_of_terms_three_position', 'sorting_elements_display_three' );

		$this->set_element_position( 'count_x_id', '16', 'count_y_id', '16', '.taxonomy--term .category-group--count', 'sorting_elements_display_three' );

		$this->set_heading_section_condition( 'taxonomies_image_three_position', esc_html__( 'Taxonomy Image', 'lisfinity-core' ), 'hr_image_three_position', 'sorting_elements_display_three' );


		$this->set_element_position( 'taxonomy_image_x_id', '0', 'taxonomy_image_y_id', '0', '.taxonomy--image.relative', 'sorting_elements_display_three' );

		$this->set_heading_section_condition( 'taxonomy_term_three_position', esc_html__( 'Taxonomy Term', 'lisfinity-core' ), 'hr_term_three_position', 'sorting_elements_display_three' );

		$this->add_control(
			'taxonomies_term_x_id',

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
					'{{WRAPPER}} .taxonomy--term .px-24' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					'sorting_elements_display_three' => 'yes'
				]
			]
		);

		$this->add_control(
			'taxonomies_term_y_id',

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
					'{{WRAPPER}} .taxonomy--term .px-24' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					'sorting_elements_display_three' => 'yes'
				]
			]
		);


	}

	/**
	 * * Term Style 4
	 * -------------------------
	 */


	public function taxonomy_term_style_four_settings() {

		$this->set_heading_section( 'taxonomies_heading_four', esc_html__( 'Taxonomy', 'lisfinity-core' ), 'hr_taxonomies_four' );

		$this->add_group_control(
			Group_Control_Taxonomies_Typography_Style_Four::get_type(),
			[
				'name'     => 'taxonomies_typography_style_four',
				'selector' => '{{WRAPPER}} .category-group--content .font-semibold',
			]
		);


		$this->set_text_color( 'taxonomies_text_color_id_style_four', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.category-group--content .font-semibold' );

		$this->set_heading_section( 'taxonomies_number_of_terms_style_four', esc_html__( 'Number of Terms Text Style', 'lisfinity-core' ), 'hr_number_of_terms_style_four' );

		$this->set_text_color( 'number_of_terms_color_id_style_four', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.category-group--content' );

		$this->set_font_size( 'number_of_terms_size_id_style_four', '14', '.category-group--content' );


		$this->set_heading_section( 'taxonomies_number_of_terms_four', esc_html__( 'Number of Terms Box Style', 'lisfinity-core' ), 'hr_number_of_terms_four' );

		$this->set_text_color( 'number_of_terms_color_id_four', esc_html__( 'Text Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.category-group--inner .absolute.top-20 .relative' );

		$this->set_font_size( 'number_of_terms_size_id_four', '12', '.category-group--inner .absolute.top-20 .relative' );

		$this->set_background_color( 'number_of_terms_id_four', 'rgba(45, 45, 45, 0.5)', esc_html__( 'Background Color', 'lisfinity-core' ), '.category-group--inner .absolute.top-20 .absolute' );

		$this->set_border_radius( 'number_of_terms_border_radius_four', '50', '50', '50', '50', '%', esc_html__( 'Border Radius', 'lisfinity-core' ), '.category-group--inner .absolute.top-20 .absolute', '4' );

	}

	/**
	 * * Sorting Style 4
	 * -------------------------
	 */


	public function taxonomy_content_sorting_four_settings() {

		$this->display_element( 'sorting_elements_display_four', 'Sort elements' );

		$this->set_heading_section_condition( 'taxonomies_number_of_terms_four_position', esc_html__( 'Number of Terms', 'lisfinity-core' ), 'hr_number_of_terms_four_position', 'sorting_elements_display_four' );

		$this->set_element_position( 'taxonomies_number_of_terms_four_x_id', '20', 'taxonomies_number_of_terms_four_y_id', '20', '.category-group--inner.relative .absolute.top-20.right-20.flex-center', 'sorting_elements_display_four' );

		$this->set_heading_section_condition( 'taxonomies_term_four_position', esc_html__( 'Taxonomy Term', 'lisfinity-core' ), 'hr_term_four_position', 'sorting_elements_display_four' );

		$this->set_element_position( 'taxonomy_term_x_id', '0', 'taxonomy_term_y_id', '325', '.category-group--content', 'sorting_elements_display_four' );


	}


	/**
	 * * functions
	 * -------------------------
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

	public function layout( $id_columns, $selector1, $selector2, $id_columns_gap, $default_column_gap, $id_columns_gap_y, $default_column_gap_y, $condition, $default_columns = [ 'default' => '3' ] ) {

		$this->add_responsive_control(
			$id_columns,
			[
				'label'       => __( 'Break Taxonomies Into Columns', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				$default_columns,
				'min'         => 1,
				'description' => __( 'Choose the number of columns you wish to break taxonomy boxes', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector1" => 'width: calc(100% / {{VALUE}});',
				],
				'condition'   => [
					'style' => $condition
				]
			]
		);
		$this->add_responsive_control(
			$id_columns_gap,
			[
				'label'       => __( 'Taxonomy Columns Gap', 'lisfinity-core' ),
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
					'size' => $default_column_gap,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector1" => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} $selector2" => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => $condition
				]
			]
		);
		$this->add_responsive_control(
			$id_columns_gap_y,
			[
				'label'       => __( 'Taxonomy Columns Gap Vertical', 'lisfinity-core' ),
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
					'size' => $default_column_gap_y,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector1" => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'style' => $condition
				]
			]
		);
	}

	public function set_heading_section_condition( $id, $heading, $hr_id, $condition ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'plugin-name' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$condition => 'yes'
				]
			]
		);

		$this->add_control(
			$hr_id,
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					$condition => 'yes'
				]
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

	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_unit, $message, $selector, $condition ) {
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
				'condition'   => [
					'style' => $condition
				]
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

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector, $condition ) {
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
				],
				'condition'   => [
					$condition => 'yes'
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
				],
				'condition'   => [
					$condition => 'yes'
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

	public function sort_elements( $id, $description, $order_number, $max_number, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $description, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => $max_number,
				'step'      => 1,
				'default'   => $order_number,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
			]
		);
	}

	public function display_element( $id, $message ) {
		$this->add_control(
			$id,
			[
				'label'        => __( $message, 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
	}

	/**
	 * Format terms of the given taxonomy so that they can
	 * be used in a select field
	 * ---------------------------------------------------
	 *
	 * @param $taxonomy
	 *
	 * @return array
	 */
	public function get_terms_select( $taxonomy ) {
		$select = [];

		if ( isset( $taxonomy ) && ! empty( $taxonomy ) ) {
			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$select[ $term->term_id ] = $term->name;
				}
			}
		}

		return $select;
	}

	/**
	 * Format product post type taxonomies so that they
	 * can be used in a select field
	 * ------------------------------------------------
	 *
	 * @return array
	 */
	public function get_taxonomies_select() {
		$taxonomies = get_object_taxonomies( 'product', '' );

		$taxonomy_select = [];
		foreach ( $taxonomies as $taxonomy => $taxonomy_object ) {
			if ( ! in_array( $taxonomy, [
				'product_cat',
				'product_type',
				'product_tag',
				'product_visibility',
				'product_shipping_class'
			] ) ) {
				$taxonomy_select[ $taxonomy ] = $taxonomy_object->label;
			}
		}

		return $taxonomy_select;
	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// todo | we should invoke custom template here.
		$term_args = [];
		if ( ! empty( $settings['taxonomy'] ) ) {
			$term_args['taxonomy'] = $settings['taxonomy'];

			$term_args['hide_empty'] = false;
			if ( ! empty( $settings['hide_empty'] ) ) {
				$term_args['hide_empty'] = true;
			}

			if ( ! empty( $settings['number'] ) ) {
				$term_args['number'] = $settings['number'];
			}

			// add handpicked terms to query args.
			if ( ! empty( $settings["handpicked[{$settings['taxonomy']}]"] ) ) {
				$term_args['include'] = $settings["handpicked[{$settings['taxonomy']}]"];
			}
		}

		$request = lisfinity_get_taxonomy_and_term();
		if ( $request && ! lisfinity_get_slug( 'slug-category', 'ad-category' ) === $request[0] ) {
			$model       = new TaxonomiesAdminModel();
			$childs      = $model->get_taxonomies_by_parent( $request[0] );
			$parent_term = get_term_by( 'slug', $request[1], $request[0] );
			$args        = [
				'settings' => $settings,
				'terms'    => get_terms(
					[
						'taxonomy'   => $childs[0]['slug'],
						'hide_empty' => false,
						'parent'     => $parent_term->term_id,
					]
				),
				'taxonomy' => $childs[0]['slug'],
			];
		} else {
			$args = [
				'settings' => $settings,
				'terms'    => get_terms( $term_args ),
				'taxonomy' => $settings['taxonomy'],
			];
		}

		$use_carousel = is_array( $args['terms'] ) && count( $args['terms'] ) > 6;

		if ( $settings['style'] === '4' ) {
			if ( $use_carousel ) {
				include lisfinity_get_template_part( 'taxonomies-carousel', 'shortcodes/taxonomies', $args );
			} else {
				include lisfinity_get_template_part( 'taxonomies-grid', 'shortcodes/taxonomies', $args );
			}
		} else {
			include lisfinity_get_template_part( "taxonomies-style-{$settings['style']}", 'shortcodes/taxonomies', $args );
		}

	}

}
