<?php


namespace Lisfinity\Shortcodes\BusinessProfileSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Ratings_Text_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;

class Business_Reviews_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'business-reviews';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Business Reviews', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-sort-numeric-up';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-business-profile' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'business_reviews_wrapper',
			[
				'label' => __( 'Wrapper style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->box_style_settings();

		$this->end_controls_section();

		$this->start_controls_section(
			'business_reviews_content',
			[
				'label' => __( 'Content style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->content_style_settings();

		$this->end_controls_section();

		// Style | Footer styles
		$this->start_controls_section(
			'business_reviews_footer_style',
			[
				'label'       => __( 'Footer Style', 'lisfinity-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_padding('business_reviews_footer_padding', '.testimonial--author-wrapper', '0', '0', '0', '0', true);
		$this->set_margin('business_reviews_footer_margin', '.testimonial--author-wrapper', '20', '0', '0', '0', true);


		// Style | Footer tabs
		$this->start_controls_tabs(
			'business_reviews_footer_tabs'
		);

		// Style | Footer logo tab.

		$this->start_controls_tab(
			'business_reviews_footer_author_tab',
			[
				'label' => __( 'Author', 'lisfinity-core' ),
			]
		);

		$this->footer_author_style_settings();

		$this->end_controls_tab();


		// Style | Footer icon tab.

		$this->start_controls_tab(
			'business_reviews_footer_icon_tab',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->footer_icon_style_settings();

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'business_reviews_sorting',
			[
				'label' => __( 'Sort the elements', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->testimonials_elements_sorting();

		$this->end_controls_section();


	}

	public function box_style_settings() {

		$this->set_background_color( 'box_background_color_testimonials', 'rgba(255, 255, 255, 1)', 'Background Color', '.testimonial' );
		$this->add_group_control(
			Group_Control_Testimonials_Box_Shadow::get_type(),
			[
				'name'     => 'testimonials_border_box',
				'selector' => '{{WRAPPER}} .testimonial',
			]
		);
		$this->add_control(
			'testimonials_wrapper_border_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_border_radius( 'box_border_radius_testimonials', '3', '3', '3', '3', 'px', '.testimonial' );
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'box_border_testimonials',
				'selector' => '{{WRAPPER}} .testimonial',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width' => ['default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '0',
						'left' => '0'
					]],
					'color' => ['default' => 'rgba(239, 239, 239, 1)']

				]
			]
		);

		$this->add_control(
			'testimonials_wrapper_padding_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_padding('box_padding_testimonials', '.testimonial', '30', '30', '30', '30', true);
		$this->set_margin('box_margin_testimonials', '.testimonial', '0', '0', '0', '0', true);
	}

	public function content_style_settings() {
		$this->add_group_control(
			Group_Control_Testimonials_Content_Typography::get_type(),
			[
				'name'     => 'testimonials_content_typography',
				'selector' => '{{WRAPPER}} .testimonial--content .testimonial--text',
			]
		);

		$this->set_background_color('testimonials_content_color_id', 'rgba(104, 104, 104, 1)', esc_html__('Text Color', 'lisfinity-core'), '.testimonial--content .testimonial--text', false);

		$this->add_control(
			'testimonials_content_padding_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_padding('testimonials_content_padding', '.testimonial--content', '0', '0', '0', '0', 'true');

		$this->set_margin('testimonials_content_margin', '.testimonial--content', '0', '0', '0', '0', 'true');

		$this->add_control(
			'place_icon',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => TRUE,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'testimonials_content_icon_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#959595',
				'selectors' => [
					'{{WRAPPER}} .testimonial-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label'      => __( 'Size', 'lisfinity-core' ),
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
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-icon' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'testimonials_content_position_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'      => __( 'Icon Spacing', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};',
				],
			]
		);


	}

	public function footer_author_style_settings() {

		$this->set_heading_section('business_reviews_icon_logo_testimonials', esc_html__('Set logo', 'lisfinity-core'), 'id_logo_hr_testimonials');

		$this->set_width('business_reviews_logo_width_id', '.testimonial--author-img', '40', 'px');
		$this->set_height('business_reviews_logo_height_id', '.testimonial--author-img', '40', 'px');

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'business_reviews_logo_border',
				'selector' => '{{WRAPPER}} .testimonial--author-img',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width' => ['default' => [
						'top' => '2',
						'right' => '2',
						'bottom' => '2',
						'left' => '2'
					]],
					'color' => ['default' => 'rgba(239, 239, 239, 1)']

				]
			]
		);

		$this->set_padding('business_reviews_logo_padding', '.testimonial--author-img', '0', '0', '0', '0', true);
		$this->set_margin('business_reviews_logo_margin', 'figure.testimonial--author-img', '0', '10', '0', '0', true);
		$this->set_heading_section('business_reviews_author_positioning_testimonials', esc_html__('Set logo position', 'lisfinity-core'), 'id_author_positioning_hr-testimonials');

		$this->add_control(
			'business_reviews_logo_alignment_id_testimonials',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->set_element_position('business_reviews_logo_alignment_x', '0', 'business_reviews_logo_alignment_y', '0', '.testimonial--author-img');

		$this->set_heading_section('business_reviews_text_year_testimonials', 'Set year style', 'business_reviews_text_year_hr_testimonials');


		$this->set_background_color('business_reviews_author_year_color',  'rgba(149, 149, 149, 1)', esc_html__('Set the color of the year', 'lisfinity-core'), '.testimonial--author-year', false);

		$this->add_group_control(
			Group_Control_Testimonials_Year_Typography::get_type(),
			[
				'name'     => 'business_reviews_year_typography',
				'selector' => '{{WRAPPER}} .testimonial--author-year',
			]
		);

		$this->set_elements_alignment('business_reviews_year_alignment_id', 'left', '.testimonial--author-year', false);

		$this->set_heading_section('business_reviews__text_testimonials', 'Set author name style', 'id_text_hr_testimonials');

		$this->add_group_control(
			Group_Control_Testimonials_Author_Typography::get_type(),
			[
				'name'     => 'business_reviews_author_text_typography',
				'selector' => '{{WRAPPER}} .testimonial--author-name',
			]
		);

		$this->set_background_color('business_reviews_author_text_color', 'rgba(0, 0, 0, 1)',esc_html__('Set the color of the author name', 'lisfinity-core'),  '.testimonial--author-name', false);

		$this->set_heading_section('business_reviews_author_text_positioning_testimonials', esc_html__('Set text position', 'lisfinity-core'),'id_author_text_positioning_hr-testimonials');

		$this->set_element_position('testimonials_text_alignment_x', '0', 'testimonials_text_alignment_y', '0', '.testimonial--author-content');



	}

	public function footer_icon_style_settings() {

		$this->set_heading_section('business_reviews_icon_style_heading', 'Set style', 'id_icon_style_hr');

		$this->set_background_color('business_reviews_rating_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.lisfinity-product--info .bg-yellow-300');

		$this->set_border_radius('business_reviews_rating_border_radius', '50', '50',  '50','50', '%', '.lisfinity-product--info .bg-yellow-300');

		$this->set_width('business_reviews_rating_width', '.lisfinity-product--info .bg-yellow-300', '32', 'px');

		$this->set_height('business_reviews_rating_height', '.lisfinity-product--info .bg-yellow-300', '32', 'px');

		$this->add_control(
			'place_icon_footer',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => TRUE,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'selected_icon_footer',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_footer' => 'yes',
				],
			]
		);
		$this->add_control(
			'testimonials_icon_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'icon_color_footer',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(203, 110, 23, 1)',
				'selectors' => [
					'{{WRAPPER}} .testimonial-icon-footer' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_width_footer',
			[
				'label'      => __( 'Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => '14',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-icon-footer' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'testimonials_text_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_background_color('business_reviews_rating_text_color', 'rgba(127, 127, 127, 1)',esc_html__('Set the color of the text', 'lisfinity-core'), '.lisfinity-product--info-text', false);

		$this->add_group_control(
			Group_Control_Testimonials_Ratings_Text_Typography::get_type(),
			[
				'name'     => 'business_reviews_info_location_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info-text',
			]
		);

		$this->set_heading_section('business_reviews__icon_positioning_heading', 'Set position', 'id_icon_positioning_hr-testimonials');

		$this->set_element_position('business_reviews_icon_alignment_x', '0', 'business_reviews_icon_alignment_y', '0', '.lisfinity-product--info');

	}

	public function testimonials_elements_sorting() {

		$this->add_control(
			'sort_business_reviews_content',
			[
				'label' => __( 'Content Order', 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => 1,
				'selectors'   => [
					'{{WRAPPER}} .testimonial--content' => 'order:{{VALUE}};',
				],
			]
		);

			$this->add_control(
				'sort_business_reviews_footer',
				[
					'label' => __( 'Footer Order', 'lisfinity-core' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 4,
					'step' => 1,
					'default' => 2,
					'selectors'   => [
						"{{WRAPPER}} .testimonial--author-wrapper" => 'order:{{VALUE}};',
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

		include lisfinity_get_template_part( 'business-reviews', 'shortcodes/business-profile', $args );
	}

}
