<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;

class Product_Id_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-id';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Id', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
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
		return [ 'lisfinity-single-product' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'id_label',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'id_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'ID:', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_id_label_typography',
				'selector' => '{{WRAPPER}} .id-text',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 13 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'id',
			[
				'label' => __( 'Id', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | heading.
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_id_typography',
				'selector' => '{{WRAPPER}} .product--id',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 13 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);


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

		include lisfinity_get_template_part( 'product-id', 'shortcodes/product-single', $args );
	}

}
