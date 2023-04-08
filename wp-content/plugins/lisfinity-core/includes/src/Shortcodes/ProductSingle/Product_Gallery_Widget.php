<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Current_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Current_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;

class Product_Gallery_Widget extends Shortcode {


	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-gallery';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Gallery', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-images';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-single-product' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {

		// Category feeds.
		$this->start_controls_section(
			'gallery_large_image',
			[
				'label' => __( 'Large Image', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->large_image_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'gallery_thumbnails',
			[
				'label' => __( 'Thumbnails', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->thumbnails_style();

		$this->end_controls_section();
		$this->start_controls_section(
			'gallery_thumbnails_box',
			[
				'label' => __( 'Thumbnails Box', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->thumbnails_box_style();

		$this->end_controls_section();

	}


	public function large_image_style() {
		$this->set_height('large_image_height', '.slick-slide, {{WRAPPER}} .slick-current figure img', '522', 'px');

		$this->add_control(
			'large_image_overlay',
			[
				'label'     => __( 'Set Overlay', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .elementor-product-gallery figure.photo-zoom::after' => 'background-color:{{VALUE}};
																									content: "";
																									position: absolute;
																									display: block;
																									z-index: 10;
																									  top: 0;
																									  left: 0;
																									  height: 100%;
																									  width: 100%;'
				],
			]
		);

		$this->set_border_radius( 'large_image_border_radius', '0', '0', '0', '0', 'px', '.slick-current figure.photo-zoom::after, {{WRAPPER}} .slick-current, {{WRAPPER}} .slick-current figure.photo-zoom img' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'large_image_box_shadow',
				'selector' => '{{WRAPPER}} .slick-current figure.photo-zoom::after',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'large_image_border',
				'selector' => '{{WRAPPER}} .slick-current figure.photo-zoom img',
			]
		);

		$this->set_padding( 'large_image_padding', '.elementor-product-gallery .photo-zoom', '0', '0', '0', '0', 'true' );

		$this->set_margin( 'large_image_margin', '.elementor-product-gallery .photo-zoom', '0', '0', '0', '0', 'true' );


	}

	public function thumbnails_style() {

		$this->start_controls_tabs(
			'thumbnails_tabs',
			[
				'label' => __( 'Thumbnails', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'thumbnails_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'thumbnails_size',
			[
				'label'       => __( 'Size', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '75',
				],
				'selectors'   => [
					'{{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--figure' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_border_radius( 'thumbnails_border_radius', '4', '4', '4', '4', 'px', '.elementor-product-gallery .slider--thumbnails .slide--figure img, {{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--figure' );

		$this->set_padding( 'thumbnails_padding', '.elementor-product-gallery .slider--thumbnails .slide--figure', '0', '0', '0', '0', 'true' );

		$this->set_margin( 'thumbnails_margin', '.elementor-product-gallery .slider--thumbnails .slide--figure', '0', '3', '0', '3', 'true' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'thumbnails_box_shadow',
				'selector' => '{{WRAPPER}} .slider--thumbnails .slide--default',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'thumbnails_border',
				'selector' => '{{WRAPPER}} .slider--thumbnails .slide--default',
			]
		);

		$this->set_elements_alignment( 'thumbnails_alignment', 'flex-start', '.slider--thumbnails' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnails_current_tab',
			[
				'label' => __( 'Current', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'thumbnails__current_size',
			[
				'label'       => __( 'Size', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '75',
				],
				'selectors'   => [
					'{{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--current-figure' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->set_border_radius( 'thumbnails_border_radius_current', '4', '4', '4', '4', 'px', '.elementor-product-gallery .slider--thumbnails .slide--current-figure .slide--current, {{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--current-figure img' );
		$this->set_border_radius( 'thumbnails_border_radius_current_hover', '4', '4', '4', '4', 'px', '.elementor-product-gallery .slider--thumbnails .slide--current-figure:hover .slide--current, {{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--current-figure:hover img', 'Border Radius on hover' );


		$this->add_responsive_control(
			'thumbnails_margin_current',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--current-figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				'default'     => [
					'top'      => (string) 0,
					'right'    => (string) 3,
					'bottom'   => (string) 0,
					'left'     => (string) 3,
					'isLinked' => false,
				]
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Current_Box_Shadow::get_type(),
			[
				'name'           => 'box_shadow_current',
				'selector'       => '{{WRAPPER}} .slide--current-figure .slide--current',
				'fields_options' => [
					'box_shadow_current_single-product-gallery-current-box-shadow' => [ 'default' => 'yes' ],
					'box_shadow'                    => [
						'default' => [
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 0,
							'spread'     => 4,
							'color'      => 'rgba(9, 103, 210, 1)',
						]
					],
					'box_shadow_position' => [
						'default' => 'inset'
					]
				],
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Current_Border::get_type(),
			[
				'name'           => 'thumbnails_border_current',
				'selector'       => '{{WRAPPER}} .elementor-product-gallery .slider--thumbnails .slide--current',
				'fields_options' => [
					'thumbnails_border_current' => [ 'default' => 'yes' ],
					'border'                    => [ 'default' => 'solid' ],
					'width'                     => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0'
					],
					'color' => [
						'default' => 'transparent'
					]
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnails_hover_tab',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'thumbnails_size_hover',
			[
				'label'       => __( 'Size', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '75',
				],
				'selectors'   => [
					'{{WRAPPER}} .elementor-product-gallery .slider--thumbnails figure:hover' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_border_radius( 'thumbnails_border_radius_hover', '4', '4', '4', '4', 'px', '.elementor-product-gallery .slider--thumbnails figure:hover img, {{WRAPPER}} .elementor-product-gallery .slider--thumbnails figure:hover' );


		$this->set_padding( 'thumbnails_padding_hover', '.elementor-product-gallery .slider--thumbnails figure:hover', '0', '0', '0', '0', 'true' );

		$this->set_margin( 'thumbnails_margin_hover', '.elementor-product-gallery .slider--thumbnails figure:hover', '0', '3', '0', '3', 'true' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'thumbnails_box_shadow_hover',
				'selector' => '{{WRAPPER}} .slider--thumbnails .slide--default:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'thumbnails_border_hover',
				'selector' => '{{WRAPPER}} .slider--thumbnails .slide--default:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


	}

	public function thumbnails_box_style() {

		$this->set_background_color( 'thumbnails_box_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.elementor-product-gallery .slider--thumbnails' );

		$this->set_border_radius( 'thumbnails_box_border_radius', '0', '0', '0', '0', 'px', '.elementor-product-gallery .slider--thumbnails' );
		$this->set_padding( 'thumbnails_box_padding', '.elementor-product-gallery .slider--thumbnails', '0', '0', '0', '0', 'true' );

		$this->set_margin( 'thumbnails_box_margin', '.elementor-product-gallery .slider--thumbnails', '10', '-3', '0', '-3', 'true' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'thumbnails_box_box_shadow',
				'selector' => '{{WRAPPER}} .slider--thumbnails',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'thumbnails_box_border',
				'selector' => '{{WRAPPER}} .slider--thumbnails',
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

		include lisfinity_get_template_part( 'product-gallery', 'shortcodes/product-single', $args );
	}

}
