<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Link_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Title_Typography;

class Product_Safety_Tips_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-safety-tips';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Safety Tips', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fab fa-gratipay';
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
			'safety_tips_wrapper',
			[
				'label' => __( 'Safety Tips Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->wrapper_style();

		$this->end_controls_section();
		$this->start_controls_section(
			'safety_tips_icon',
			[
				'label' => __( 'Safety Tips Icon', 'lisfinity-core' ),
			]
		);
		$this->icon_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'safety_tips_title',
			[
				'label' => __( 'Safety Tips Content', 'lisfinity-core' ),
			]
		);
		$this->content_wrapper();
		$this->start_controls_tabs(
			'content_tabs',
			[
				'label' => __( 'Tabs Content', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"title_tab",
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);
		$this->title_style();

		$this->end_controls_tab();
		$this->start_controls_tab(
			"text_tab",
			[
				'label' => __( 'Text', 'lisfinity-core' ),
			]
		);
		$this->text_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			"link_tab",
			[
				'label' => __( 'Link', 'lisfinity-core' ),
			]
		);
		$this->link_style();

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function wrapper_style() {
		$this->set_background_color( 'safety_tips_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.profile--tips' );
		$this->set_padding( 'safety_tips_wrapper_padding', '.profile--tips', '30', '20', '30', '20', 'false' );
		$this->set_margin( 'safety_tips_wrapper_margin', '.profile--tips', '30', '0', '0', '0', 'false' );
		$this->set_border_radius( 'safety_tips_wrapper_border_radius', '3', '3', '3', '3', 'px', '.profile--tips' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'safety_tips_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .profile--tips',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'safety_tips_wrapper_border',
				'selector' => '{{WRAPPER}} .profile--tips',
			]
		);
	}

	public function icon_style() {
		$this->start_controls_tabs(
			'icon_tabs',
			[
				'label' => __( 'Tabs Icon', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"wrapper_icon_tab",
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'icon_bg_color',
			[
				'label'     => __( 'Icon Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(230, 246, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .tips--icon-wrapper' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->set_width( 'icon_bg_width', '.tips--icon-wrapper', '70', 'px' );

		$this->set_height( 'icon_bg_height', '.tips--icon-wrapper', '70', 'px' );
		$this->set_padding( 'icon_wrapper_wrapper_padding', '.tips--icon-wrapper', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'icon_wrapper_wrapper_margin', '.tips--icon-wrapper', '0', '20', '0', '0', 'false' );
		$this->set_border_radius( 'icon_border_radius', '50', '50', '50', '50', '%', '.tips--icon-wrapper' );
		$this->end_controls_tab();

		$this->start_controls_tab(
			"icon_tab",
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'place_icon',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(124, 196, 250, 1)',
				'selectors' => [
					'{{WRAPPER}} .safety-tips-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => '36',
				],
				'selectors'   => [
					'{{WRAPPER}} .safety-tips-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function content_wrapper() {
		$this->set_elements_alignment( 'safety_tips_wrapper_content_alignment', 'left', '.tips--content', false );
		$this->set_background_color( 'safety_tips_content_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfnity-core' ), '.tips--content' );
		$this->set_padding( 'safety_tips_wrapper_content_padding', '.tips--content', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'safety_tips_wrapper_content_margin', '.tips--content', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'safety_tips_wrapper_content_border_radius', '0', '0', '0', '0', 'px', '.tips--content' );

	}

	public function title_style() {
		$this->add_control(
			'title_text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Safety Tips', 'lisfinity-core' ),
				'placeholder' => __( 'Click here', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'     => 'single_product_safety_tips_title_label_typography',
				'selector' => '{{WRAPPER}} .safety-tips-title',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 18 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);
	}

	public function text_style() {
		$this->add_control(
			'text_text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Buy and sell safely on Lisfinity!', 'lisfinity-core' ),
				'placeholder' => __( 'Click here', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_safety_tips_text_label_typography',
				'selector' => '{{WRAPPER}} #safety-tips-text',
			]
		);
	}

	public function link_style() {
		$this->add_control(
			'link_text',
			[
				'label'       => __( 'Link Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Read our Safety Tips', 'lisfinity-core' ),
				'placeholder' => __( 'Click here', 'lisfinity-core' ),
			]
		);

		$this->display_element( 'different_link', esc_html__( 'Use Different Link', 'lisfinity-core' ) );

		$this->add_control(
			'link_url',
			[
				'label'       => __( 'Link Url', 'lisfinity-core' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
				'placeholder' => __( 'https://your-link.com', 'lisfinity-core' ),
				'condition'   => [
					'different_link' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Link_Typography::get_type(),
			[
				'name'     => 'single_product_safety_tips_link_label_typography',
				'selector' => '{{WRAPPER}} .safety-tips-link',
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

		include lisfinity_get_template_part( 'product-safety-tips', 'shortcodes/product-single', $args );
	}

}
