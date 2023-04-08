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

class Business_Testimonial_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'business-testimonial';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Business Testimonial', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
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
			'button_style',
			[
				'label' => __( 'Button Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->display_element( 'display_text', esc_html__( 'Display Text', 'lisfinty-core' ) );
		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Leave a review', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'address_typography',
				'selector'       => '{{WRAPPER}} .testimonial-button',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#f0b429'
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


		$this->set_padding( 'button_padding', '.testimonial-button', '8', '30', '8', '30', false );
		$this->set_margin( 'button_margin', '.testimonial-button', '0', '0', '0', '0', false );

		$this->set_background_color( 'button_background_color', 'transparent', 'Background Color', '.testimonial-button' );

		$this->set_border_radius( 'box_border_radius', '3', '3', '3', '3', 'px', '.testimonial-button' );
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'button_border',
				'selector'       => '{{WRAPPER}} .testimonial-button',
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
					'color'  => [ 'default' => '#f0b429' ]
				],
			]
		);

		$this->set_heading_section('button_hover_heading', esc_html__('On Hover', 'lisfinity-core'), 'button_hover_hr');
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'address_typography_hover',
				'selector'       => '{{WRAPPER}} .testimonial-button:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#f0b429'
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
		$this->set_background_color( 'button_background_color_hover', 'transparent', 'Background Color', '.testimonial-button:hover' );

		$this->set_border_radius( 'box_border_radius_hover', '3', '3', '3', '3', 'px', '.testimonial-button:hover' );
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'button_border_hover',
				'selector'       => '{{WRAPPER}} .testimonial-button:hover',
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
					'color'  => [ 'default' => '#f0b429' ]
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'icon_style',
			[
				'label' => __( 'Icon Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->icon_style();

		$this->end_controls_section();


	}

	public function icon_style() {
		$this->display_element( 'display_icon', esc_html__( 'Hide Icon', 'lisfinty-core' ) );

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Different Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom home icon', 'lisfinity-core' ),
				'condition'   => [
					'display_icon' => '',
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
					'{{WRAPPER}} .testimonial-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.testimonial-icon'    => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_icon' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f0b429',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .testimonial-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} i.testimonial-icon'    => 'color: {{VALUE}};',
				],
				'condition'   => [
					'display_icon' => '',
				],
				'separator'   => 'after',
			]
		);

		$this->add_responsive_control(
			'icon_size_hover',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size on Hover', 'lisfinity-core' ),
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
					'{{WRAPPER}} .testimonial-button:hover .testimonial-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testimonial-button:hover i.testimonial-icon'    => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_icon' => '',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'       => __( 'Title Icon Color on Hover', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f0b429',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .testimonial-button:hover .testimonial-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .testimonial-button:hover i.testimonial-icon'    => 'color: {{VALUE}};',
				],
				'condition'   => [
					'display_icon' => '',
				],
				'separator'   => 'after',
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

		include lisfinity_get_template_part( 'business-testimonial', 'shortcodes/business-profile', $args );
	}

}
