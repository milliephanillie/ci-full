<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Phone_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Phone_Typography;

class Product_Owner_Phone_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-owner-phone';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Owner Phone', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-phone';
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
			'owner_phone',
			[
				'label' => __( 'Phone Number Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'phone_tabs',
			[
				'label' => __( 'Tabs Default', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"revealed_phone_tab",
			[
				'label' => __( 'Revealed Number', 'lisfinity-core' ),
			]
		);

		$this->phone_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			"hidden_phone_tabs",
			[
				'label' => __( 'Hidden Numbers', 'lisfinity-core' ),
			]
		);

		$this->hidden_phone_style();

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'button_show',
			[
				'label' => __( 'Button Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->show_number_button();
		$this->end_controls_section();

		$this->start_controls_section(
			'icon_color',
			[
				'label' => __( 'Icon Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->icon_style();
		$this->end_controls_section();

	}

	public function phone_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Phone_Typography::get_type(),
			[
				'name'     => 'revealed_phone_typography',
				'selector' => '{{WRAPPER}} .revealed-phone',
			]
		);
		$this->set_background_color( 'revealed_phone_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.revealed-phone' );
		$this->set_padding( 'revealed_phone_wrapper_padding', '.revealed-phone', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'revealed_phone_wrapper_margin', '.revealed-phone', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'revealed_phone_wrapper_border_radius', '0', '0', '0', '0', 'px', '.revealed-phone' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'revealed_phone_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .revealed-phone',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'revealed_phone_wrapper_border',
				'selector' => '{{WRAPPER}} .revealed-phone',
			]
		);
	}

	public function hidden_phone_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Phone_Typography::get_type(),
			[
				'name'     => 'hidden_phone_typography',
				'selector' => '{{WRAPPER}} .hidden-phone',
			]
		);
		$this->set_background_color( 'hidden_phone_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.hidden-phone' );
		$this->set_padding( 'hidden_phone_wrapper_padding', '.hidden-phone', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'hidden_phone_wrapper_margin', '.hidden-phone', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'hidden_phone_wrapper_border_radius', '0', '0', '0', '0', 'px', '.hidden-phone' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'hidden_phone_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .hidden-phone',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'hidden_phone_wrapper_border',
				'selector' => '{{WRAPPER}} .hidden-phone',
			]
		);
	}

	public function show_number_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Phone_Button_Typography::get_type(),
			[
				'name'     => 'owner_phone_button_typography',
				'selector' => '{{WRAPPER}} .owner-phone-button',
			]
		);
		$this->set_background_color( 'owner_phone_button_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.owner-phone-button' );
		$this->set_padding( 'owner_phone_button_wrapper_padding', '.owner-phone-button', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'owner_phone_button_wrapper_margin', '.owner-phone-button', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'owner_phone_button_wrapper_border_radius', '0', '0', '0', '0', 'px', '.owner-phone-button' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'owner_phone_button_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .owner-phone-button',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'owner_phone_button_wrapper_border',
				'selector' => '{{WRAPPER}} .owner-phone-button',
			]
		);
	}

	public function icon_style() {
		$this->display_element('use_uniform_color', esc_html__('Use uniform color', 'lisfinity-core'), '');
		$this->add_control(
			'use_uniform_color_icon',
			[
				'label'       => __('Color', 'lisfinity-core'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'selectors'   => [
					"{{WRAPPER}} .fill-viber, {{WRAPPER}} .fill-whatsapp, {{WRAPPER}} .fill-skype " => "color:{{VALUE}}; fill:{{VALUE}};",
					"{{WRAPPER}} .fill-skype svg path:first-child" => "fill: black;"
				],
				'condition' => [
					'use_uniform_color' => 'yes'
				]
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

		include lisfinity_get_template_part( 'product-owner-phone', 'shortcodes/product-single', $args );
	}

}
