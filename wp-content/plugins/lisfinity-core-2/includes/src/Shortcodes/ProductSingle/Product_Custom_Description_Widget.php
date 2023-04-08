<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Custom_Fields_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Name_Typography;

class Product_Custom_Description_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-custom-description';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Custom Description', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-signature';
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
			'wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->wrapper_style();

		$this->end_controls_section();
		$this->start_controls_section(
			'product_custom_fields',
			[
				'label' => __( 'Field Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'tabs',
			[
				'label' => __( "Tabs Default", 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"tab_title_default",
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'field_typography_title',
				'selector' => '{{WRAPPER}} .custom-description-title',
			]
		);
		$this->set_text_color( 'title_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.custom-description-title' );
		$this->set_elements_alignment('content_fields_title_alignment', 'center', '.custom-description-title', false);
		$this->set_padding('content_fields_title_padding', '.custom-description-title', '0', '0', '0', '0', true);
		$this->set_margin('content_fields_title_margin', '.custom-description-title', '0', '0', '0', '0', true);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"tab_content_default",
			[
				'label' => __( 'Description', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'field_typography_description',
				'selector' => '{{WRAPPER}} .custom-description-content',
			]
		);
		$this->set_text_color( 'description_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.custom-description-content' );
		$this->set_elements_alignment('content_fields_text_alignment', 'center', '.custom-description-content', false);
		$this->set_padding('content_fields_content_padding', '.custom-description-content', '0', '0', '0', '0', true);
		$this->set_margin('content_fields_content_margin', '.custom-description-content', '0', '0', '0', '0', true);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"tab_from_who_default",
			[
				'label' => __( 'From Who', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'field_typography_from_who',
				'selector' => '{{WRAPPER}} .custom-description-from-who',
			]
		);
		$this->set_text_color( 'description_color_from_who', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.custom-description-from-who' );
		$this->set_elements_alignment('content_fields_from_who_alignment', 'center', '.custom-description-from-who', false);
		$this->set_padding('content_fields_from_who_padding', '.custom-description-from-who', '0', '0', '0', '0', true);
		$this->set_margin('from-who_fields_from_who_margin', '.custom-description-content', '0', '0', '0', '0', true);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function wrapper_style() {
		$this->set_width('wrapper_width', '.product-custom-description', '100', '%');
		$this->set_height('wrapper_height', '.product-custom-description', '100', '%');
		$this->set_background_color( 'wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.product-custom-description' );
		$this->set_padding( 'wrapper_padding', '.product-custom-description', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'wrapper_margin', '.product-custom-description', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'wrapper_border_radius', '3', '3', '3', '3', 'px', '.product-custom-description' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .product-custom-description',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .product-custom-description',
			]
		);

		$this->set_elements_alignment('content_alignment', 'center', '.elementor-product-description-field');
	}

	public function set_text_color( $id, $message, $default, $selector, $default_args = [] ) {
		$args = [
			'label'     => __( $message, 'lisfinity-core' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => $default,
			'selectors' => [
				"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
			],
		];
		if ( ! empty( $default_args ) ) {
			$args[] = $default_args;
		}
		$this->add_control( $id, $args );
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

		include lisfinity_get_template_part( 'product-custom-description', 'shortcodes/product-single', $args );
	}

}
