<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sidebar_Menu_Typography;

class Product_Sidebar_Menu_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-sidebar-menu';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Sidebar Menu', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-list-ul';
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
		// Wrapper
		$this->start_controls_section(
			'sidebar_menu_wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->sidebar_wrapper();
		$this->end_controls_section();

		//Items
		$this->start_controls_section(
			'sidebar_menu_item',
			[
				'label' => __( 'Sidebar Menu Items', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->sidebar_menu_items();
		$this->end_controls_section();

	}

	public function sidebar_wrapper() {
		$this->set_background_color( 'sidebar_menu_wrapper_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.product--menu__aside_elementor div' );
		$this->set_padding( 'sidebar_menu_wrapper_padding', '.product--menu__aside_elementor div', '16', '14', '16', '14', 'false' );
		$this->set_margin( 'sidebar_menu_wrapper_margin', '.product--menu__aside_elementor div', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'sidebar_menu_wrapper_border_radius', '3', '3', '3', '3', 'px', '.product--menu__aside_elementor div' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'sidebar_menu_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .product--menu__aside_elementor div',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'sidebar_menu_wrapper_border',
				'selector' => '{{WRAPPER}} .product--menu__aside_elementor div',
			]
		);
	}

	public function sidebar_menu_items() {

		$this->start_controls_tabs(
			'sidebar_menu_items_tabs',
			[
				'label' => __( 'Content Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'sidebar_menu_item_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'sidebar_menu_items_typography',
				'selector' => '{{WRAPPER}} .sidebar-menu-link',
			]
		);
		$this->set_background_color( 'sidebar_menu_item_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.sidebar-menu-link' );
		$this->set_padding( 'sidebar_menu_item_padding', '.sidebar-menu-link', '4', '10', '4', '10', 'false' );
		$this->set_margin( 'sidebar_menu_item_margin', '.sidebar-menu-link', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'sidebar_menu_item_border_radius', '3', '3', '3', '3', 'px', '.sidebar-menu-link' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'sidebar_menu_item_box_shadow',
				'selector' => '{{WRAPPER}} .sidebar-menu-link',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'sidebar_menu_item_border',
				'selector' => '{{WRAPPER}} .sidebar-menu-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sidebar_menu_item_active_tab',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'sidebar_menu_items_typography_active',
				'selector' => '{{WRAPPER}} a.sidebar-menu-link-active',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
					'text_decoration' => [
						'default' => 'none'
					]
				],
			]
		);
		$this->set_background_color( 'sidebar_menu_item_bg_color_active', 'rgba(255, 243, 196, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.sidebar-menu-link-active' );
		$this->set_padding( 'sidebar_menu_item_padding_active', '.sidebar-menu-link-active', '4', '10', '4', '10', 'false' );
		$this->set_margin( 'sidebar_menu_item_margin_active', '.sidebar-menu-link-active', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'sidebar_menu_item_border_radius_active', '3', '3', '3', '3', 'px', '.sidebar-menu-link-active' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'sidebar_menu_item_box_shadow_active',
				'selector' => '{{WRAPPER}} .sidebar-menu-link-active',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'sidebar_menu_item_border_active',
				'selector' => '{{WRAPPER}} .sidebar-menu-link-active',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'sidebar_menu_item_hover_tab',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'sidebar_menu_items_typography_hover',
				'selector' => '{{WRAPPER}} a.sidebar-menu-link:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
					'text_decoration' => [
						'default' => 'none'
					]
				],
			]
		);
		$this->set_background_color( 'sidebar_menu_item_bg_color_hover', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.sidebar-menu-link:hover' );
		$this->set_padding( 'sidebar_menu_item_padding_hover', '.sidebar-menu-link:hover', '4', '10', '4', '10', 'false' );
		$this->set_margin( 'sidebar_menu_item_margin_hover', '.sidebar-menu-link:hover', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'sidebar_menu_item_border_radius_hover', '3', '3', '3', '3', 'px', '.sidebar-menu-link:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'sidebar_menu_item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .sidebar-menu-link:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'sidebar_menu_item_border_hover',
				'selector' => '{{WRAPPER}} .sidebar-menu-link:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		include lisfinity_get_template_part( 'product-sidebar-menu', 'shortcodes/product-single', $args );
	}

}
