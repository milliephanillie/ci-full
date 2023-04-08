<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Description_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Typography;

class Product_Description_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-description';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Description', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-file-alt';
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
			'description_wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->set_background_color( 'description_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '#productDescription' );
		$this->set_padding( 'description_wrapper_padding', '#productDescription', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'description_wrapper_margin', '#productDescription', '40', '0', '60', '0', 'false' );
		$this->set_border_radius( 'description_wrapper_border_radius', '0', '0', '0', '0', 'px', '#productDescription' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'description_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} #productDescription',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'description_wrapper_border',
				'selector' => '{{WRAPPER}} #productDescription',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'description_label',
			[
				'label' => __( 'Content', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'content_tabs',
			[
				'label' => __( 'Content Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'content_title_tab',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);


		$this->add_group_control(
			Group_Control_Single_Product_Specification_Typography::get_type(),
			[
				'name'     => 'description_title_typography',
				'selector' => '{{WRAPPER}} #productDescription h5',
			]
		);
		$this->set_background_color( 'description_title_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '#productDescription h5' );
		$this->set_padding( 'description_title_padding', '#productDescription h5', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'description_title_margin', '#productDescription h5', '0', '0', '0', '0', 'true' );
		$this->end_controls_tab();
		$this->start_controls_tab(
			'content_text_specification_tab',
			[
				'label' => __( 'Text', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Description_Typography::get_type(),
			[
				'name'     => 'description_text_typography',
				'selector' => '{{WRAPPER}} #productDescription div',
			]
		);
		$this->set_background_color( 'description_text_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '#productDescription div' );
		$this->set_padding( 'description_text_padding', '#productDescription div', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'description_text_margin', '#productDescription div', '20', '0', '0', '0', 'true' );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

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

		include lisfinity_get_template_part( 'product-description', 'shortcodes/product-single', $args );
	}

}
