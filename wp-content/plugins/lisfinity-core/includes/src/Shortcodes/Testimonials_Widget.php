<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Ratings_Text_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;

class Testimonials_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'testimonials';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Testimonials', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-comments';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Partners section.
		$this->start_controls_section(
			'hiw_settings',
			[
				'label' => __( 'How it Works Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'rows',
			[
				'label'       => __( 'Testimonials Rows', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					1 => __( '1 row (5 sliding testimonials)', 'lisfinity-core' ),
					2 => __( '2 rows (10 sliding testimonials)', 'lisfinity-core' ),
					3 => __( '3 rows (15 sliding testimonials)', 'lisfinity-core' ),
					4 => __( '4 rows (20 sliding testimonials)', 'lisfinity-core' ),
				],
				'default'     => 2,
				'description' => __( 'Choose the number of rows where testimonials will be displayed.', 'lisfinity-core' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'chars_limit',
			[
				'label'       => __( 'Testimonial Characters Limit', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 18,
				'description' => __( 'Choose testimonials characters limit.', 'lisfinity-core' ),
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		// Testimonials styles.

		// Style | Box styles
		$this->start_controls_section(
			'testimonials_box_style',
			[
				'label' => __( 'Template Box Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->box_style_settings();

		$this->end_controls_section();


		// Style | Content styles
		$this->start_controls_section(
			'testimonials_content_style',
			[
				'label'       => __( 'Content Style', 'lisfinity-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			]
		);

		$this->content_style_settings();

		$this->end_controls_section();

		// Style | Footer styles
		$this->start_controls_section(
			'testimonials_footer_style',
			[
				'label'       => __( 'Footer Style', 'lisfinity-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Footer tabs
		$this->start_controls_tabs(
			'testimonials_footer_tabs'
		);

		// Style | Footer logo tab.

		$this->start_controls_tab(
			'footer_author_tab',
			[
				'label' => __( 'Author', 'lisfinity-core' ),
			]
		);

		$this->footer_author_style_settings();

		$this->end_controls_tab();


		// Style | Footer icon tab.

		$this->start_controls_tab(
			'footer_icon_tab',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->footer_icon_style_settings();

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

		// product sorting content elements settings.
		$this->start_controls_section(
			'sorting_testimonials_elements',
			[
				'label' =>__('Sorting Elements', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this-> testimonials_elements_sorting();

		$this->end_controls_section();

	}



	/**
	 * * Box Style
	 * -------------------------
	 */


	public function box_style_settings() {
		$this->add_group_control(
			Group_Control_Testimonials_Box_Shadow::get_type(),
			[
				'name'     => 'testimonials_border_box',
				'selector' => '{{WRAPPER}} .testimonial',
			]
		);

		$this->set_background_color('box_background_color_testimonials', 'rgba(255, 255, 255, 1)', esc_html__('Background Color', 'lisfinity-core'), '.testimonial');

		$this->add_responsive_control(
			'box_background_width_testimonials',
			[
				'label' => __('Width', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::SLIDER,
				'default'     => [
					'unit' => 'px',
					'size' => 357,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 999
					]
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'box_background_height_testimonials',
			[
				'label' => __('Height', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::SLIDER,
				'default'     => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 999
					],
					'%' => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->set_columns_gap('box_background_gap_testimonials', '16', '.testimonial--column');

		$this->set_border_radius('box_border_radius_testimonials', '3', '3',  '3', '3','px', esc_html__('Border radius','lisfinity-core'), '.testimonial');

		$this->set_padding('box-padding-testimonials', '.testimonial', '30', '30', '30', '30', 'true');


	}

	/**
	 * *Content Style
	 * -------------------------
	 */

	public function content_style_settings() {
		$this->add_group_control(
			Group_Control_Testimonials_Content_Typography::get_type(),
			[
				'name'     => 'testimonials_content_typography',
				'selector' => '{{WRAPPER}} .testimonial--content .testimonial--text',
			]
		);

		$this->set_text_color('testimonials_content_color_id', esc_html__('Text Color','lisfinity-core'), 'rgba(104, 104, 104, 1)', '.testimonial--content .testimonial--text');

		$this->set_padding('testimonials_content_padding', '.testimonial--content', '0', '0', '0', '0', 'true');

		$this->set_margin('testimonials_content_margin', '.testimonial--content', '0', '0', '0', '0', 'true');


	}

	/**
	 * *Footer Logo Style
	 * -------------------------
	 */


	public function footer_author_style_settings() {

		$this->set_heading_section('id_icon_logo_testimonials', esc_html__('Set logo','lisfinity-core'), 'id_logo_hr_testimonials');

		$this->add_control(
			'testimonials_logo_size_id',

			[
				'label'       => __( 'Logo Size', 'lisfinity-core' ),
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
					'size' => '40',
				],
				'selectors'   => [
					'{{WRAPPER}} .testimonial--author .relative' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'testimonials_logo_height_id',

			[
				'label'       => __( 'Logo Size', 'lisfinity-core' ),
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
					'size' => '40',
				],
				'selectors'   => [
					'{{WRAPPER}} .testimonial--author .relative' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_heading_section('id_author_positioning_testimonials', esc_html__('Set logo position','lisfinity-core'), 'id_author_positioning_hr-testimonials');

		$this->add_control(
			'profiles_logo_alignment_id_testimonials',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->set_element_position('testimonials_logo_alignment_x', '30', 'testimonials_logo_alignment_y', '120', '.testimonial--author .relative', 'profiles_logo_alignment_id_testimonials');



		$this->set_heading_section('id_text_year_testimonials', esc_html__('Set year style','lisfinity-core'), 'id_text_year_hr_testimonials');


		$this->set_text_color('testimonials_author_year_color', esc_html__('Set the color of the year','lisfinity-core'), 'rgba(149, 149, 149, 1)', '.testimonial--author .ml-10 span');

		$this->add_group_control(
			Group_Control_Testimonials_Year_Typography::get_type(),
			[
				'name'     => 'testimonials_year_typography',
				'selector' => '{{WRAPPER}} .testimonial--author .ml-10 span',
			]
		);

		$this->set_heading_section('id_text_testimonials', esc_html__('Set author name style','lisfinity-core'), 'id_text_hr_testimonials');

		$this->add_group_control(
			Group_Control_Testimonials_Author_Typography::get_type(),
			[
				'name'     => 'testimonials_author_text_typography',
				'selector' => '{{WRAPPER}} .testimonial--author .ml-10 div',
			]
		);

		$this->set_text_color('testimonials_author_text_color', esc_html__('Set the color of the author name','lisfinity-core'), 'rgba(0, 0, 0, 1)', '.testimonial--author .ml-10 div');

		$this->set_text_alignment('text_alignment_id', esc_html__('Text Alignment','lisfinity-core'), 'left', '.testimonial--author .ml-10');

		$this->set_heading_section('id_author_text_positioning_testimonials', esc_html__('Set text position','lisfinity-core'), 'id_author_text_positioning_hr-testimonials');

		$this->add_control(
			'profiles_text_alignment_id_testimonials',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .testimonial--author .ml-10' => 'position: relative;',
				]
			]
		);

		$this->set_element_position('testimonials_text_alignment_x', '70', 'testimonials_text_alignment_y', '120', '.testimonial--author .ml-10', 'profiles_text_alignment_id_testimonials');



	}


	/**
	 * *Footer Logo Style
	 * -------------------------
	 */


	public function footer_icon_style_settings() {


		$this->set_heading_section('id_icon_style_heading_testimonials', esc_html__('Set style','lisfinity-core'), 'id_icon_style_hr');

		$this->set_background_color('testimonials_rating_bg_color', 'rgba(255, 243, 196, 1)', esc_html__('Background color','lisfinity-core'), '.testimonial--meta .flex-center .flex-center');

		$this->set_border_radius('testimonials_rating_border_radius', '50', '50', '50', '50','%', esc_html__('Border radius','lisfinity-core'), '.testimonial--meta .flex-center .flex-center');

		$this->set_icon_color('testimonials_rating_icon_color', esc_html__('Icon Color','lisfinity-core'), 'rgba(203, 110, 23, 1)', '.testimonial--meta .flex-center .flex-center svg');

		$this->set_icon_size('testimonials_rating_icon_size', '14', '.testimonial--meta .flex-center .flex-center svg');

		$this->set_text_color('testimonials_rating_text_color', esc_html__('Set the color of the text','lisfinity-core'), 'rgba(127, 127, 127, 1)', '.testimonial--meta .flex-center span.ml-6.text-sm.text-grey-600');


		$this->add_group_control(
			Group_Control_Testimonials_Ratings_Text_Typography::get_type(),
			[
				'name'     => 'testimonials_info_location_typography',
				'selector' => '{{WRAPPER}} .testimonial--meta .flex-center span.ml-6.text-sm.text-grey-600',
			]
		);

		$this->set_heading_section('id_icon_positioning_heading_testimonials', esc_html__('Set position','lisfinity-core'), 'id_icon_positioning_hr-testimonials');

		$this->add_control(
			'profiles_icon_alignment_id_testimonials',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .testimonial--meta .testimonial--ratings' => 'position: relative;',
				]
			]
		);

		$this->set_element_position('testimonials_icon_alignment_x', '275', 'testimonials_icon_alignment_y', '120', '.testimonial--meta .testimonial--ratings', 'profiles_icon_alignment_id_testimonials');


	}

	/**
	 * *Testimonials elements sorting
	 * -------------------------
	 */

	public function testimonials_elements_sorting() {

		$this->add_control(
			'sort_testimonials_content',
			[
				'label' => __( 'Content Order', 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => 1,
				'selectors'   => [
					'{{WRAPPER}} .testimonial--content' => 'order:{{VALUE}};',
					'{{WRAPPER}} .testimonial' => 'display: flex; flex-wrap: wrap;',
					'{{WRAPPER}} .testimonial--meta' => 'width: -webkit-fill-available;'
				],
			]
		);

		$this->sort_elements('sort_testimonials_footer', esc_html__('Footer Order','lisfinity-core'),  2, '.testimonial--meta');
}


	/**
	 * * functions
	 * -------------------------
	 */



	public function set_text_color($id, $message, $default, $selector) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
				]
			]
		);
	}


	public function set_heading_section($id, $heading, $hr_id) {
		$this->add_control(
			$id,
			[
				'label' => __( $heading, 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
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

	public function set_background_color($id, $default_color, $message, $selector) {
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

	public function sort_elements($id, $description, $order_number, $selector) {
		$this->add_control(
			$id,
			[
				'label' => __( $description, 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => $order_number,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
			]
		);
	}

	public function set_columns_gap($columns_gap_id, $default_gap, $selector) {
		$this->add_responsive_control(
			$columns_gap_id,
			[
				'label'       => __( 'Profiles Columns Gap', 'lisfinity-core' ),
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
					'size' => $default_gap,
				],
				'description' => __( 'Choose the number of columns gap', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				]
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
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'unit'     => $default_unit
				],
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	public function set_icon_color($id, $message, $default, $selector)  {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'fill:{{VALUE}};',
				]
			]
		);
	}

	public function set_icon_size($id, $default, $selector) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_element_position($id_x, $default_x, $id_y, $default_y, $selector, $condition) {
		$this->add_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => -350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'left: {{SIZE}}{{UNIT}}; position: absolute;',
					'{{WRAPPER}} .testimonial' => 'position: relative;'
				],
				'condition'   => [
					$condition => 'yes',
				],
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
						'min' => -350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector"=> 'top: {{SIZE}}{{UNIT}}; position: absolute;',
					'{{WRAPPER}} .testimonial' => 'position: relative;'
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);


	}

	public function set_padding($id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean) {

		$this->add_control(
			$id,
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_margin($id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean) {

		$this->add_control(
			$id,
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_text_alignment($id, $message, $default, $selector) {
		$this->add_control(
			$id,
			[
				'label' => __($message, 'lisfinity-core'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => $default,
				'toggle' => true,
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

		$args = [
			'settings' => $settings,
		];
		include lisfinity_get_template_part( 'testimonials', 'shortcodes/testimonials', $args );
	}

}
