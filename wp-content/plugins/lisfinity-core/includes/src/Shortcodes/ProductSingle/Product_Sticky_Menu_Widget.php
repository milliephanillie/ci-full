<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Price_Countdown_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sticky_Menu_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sticky_Menu_Typography;

class Product_Sticky_Menu_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-sticky-menu';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Sticky Menu', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-tape';
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
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->set_background_color( 'sticky_menu_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.product--menu-sticky .menu-container .menu-sticky--wrapper' );
		$this->set_padding( 'sticky_menu_wrapper_padding', '.product--menu-sticky .menu-container .menu-sticky--wrapper', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'sticky_menu_wrapper_margin', '.product--menu-sticky .menu-container .menu-sticky--wrapper', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'sticky_menu_wrapper_border_radius', '3', '3', '3', '3', 'px', '.product--menu-sticky .menu-container .menu-sticky--wrapper, {{WRAPPER}} .product--menu-sticky .menu-container' );

		$this->add_group_control(
			Group_Control_Single_Product_Sticky_Menu_Box_Shadow::get_type(),
			[
				'name'     => 'sticky_menu_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .product--menu-sticky .menu-container',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'sticky_menu_wrapper_border',
				'selector' => '{{WRAPPER}} .product--menu-sticky .menu-container .menu-sticky--wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'elements_position',
			[
				'label' => __( 'Position', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->set_heading_section( 'logo_position_heading', esc_html__( 'Logo Position', 'lisfinity-core' ), 'logo_position_hr' );
		$this->set_element_position( 'logo_position_x', '0', 'logo_position_y', '0', '.menu-container .title-container .product--figure' );

		$this->set_heading_section( 'title_position_heading', esc_html__( 'Title Position', 'lisfinity-core' ), 'title_position_hr' );
		$this->set_element_position( 'title_position_x', '0', 'title_position_y', '0', '.menu-container .title-container .product--title' );

		$this->set_heading_section( 'price_position_heading', esc_html__( 'Price Position', 'lisfinity-core' ), 'price_position_hr' );
		$this->set_element_position( 'price_position_x', '0', 'price_position_y', '0', '.menu-container .product--price' );

		$this->end_controls_section();

		$this->start_controls_section(
			'sticky_menu_logo', [
				'label' => __( 'Logo', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);
		$this->logo_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'sticky_menu_title', [
				'label' => __( 'Title', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);
		$this->title_style();
		$this->end_controls_section();
		$this->start_controls_section(
			'auction_price_sticky',
			[
				'label' => __( 'Auction Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'auction_price_tabs_sticky',
			[
				'label' => __( 'auction price', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'auction_price_tab_sticky',
			[
				'label' => __( 'Auction Price Options', 'lisfinity-core' ),
			]
		);
		$this->products_price_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'countdown_tab_sticky',
			[
				'label' => __( 'Countdown Options', 'lisfinity-core' ),
			]
		);

		$this->products_countdown_style();

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'fixed_style',
			[
				'label' => __( 'Fixed Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_fixed_price_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'negotiable_style',
			[
				'label' => __( 'Negotiable Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_negotiable_price_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'on_call_style',
			[
				'label' => __( 'On Call Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_on_call_price_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'free_style',
			[
				'label' => __( 'Free Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_free_price_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'on_sale_style',
			[
				'label' => __( 'On Sale Price Type', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_on_sale_price_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'place_bid_button_style',
			[
				'label' => __( 'Place Bid Section', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_place_bid_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'buy_now_button_style',
			[
				'label' => __( 'Buy Now Section', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->products_buy_now_style();
		$this->end_controls_section();

	}

	public function logo_style() {
		$this->set_border_radius( 'logo_border_radius', '3', '3', '3', '3', 'px', '.menu-container .title-container .product--figure, {{WRAPPER}} .menu-container .title-container .product--figure img' );
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_logo_box_shadow',
				'selector' => '{{WRAPPER}} .menu-container .title-container .product--figure img',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_logo_box_shadow',
				'selector' => '{{WRAPPER}} .menu-container .title-container .product--figure img',
			]
		);
		$this->set_width( 'logo_width', '.menu-container .title-container .product--figure', '75', 'px' );
		$this->set_height( 'logo_height', '.menu-container .title-container .product--figure', '56', 'px' );


	}

	public function title_style() {

		$this->add_group_control(
			Group_Control_Single_Product_Sticky_Menu_Typography::get_type(),
			[
				'name'     => 'single_product_title_typography',
				'selector' => '{{WRAPPER}} .product--menu-sticky .product--title',
			]
		);
		$this->set_background_color( 'title_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.product--menu-sticky .product--title' );

		$this->set_border_radius( 'title_border_radius', '0', '0', '0', '0', 'px', '.product--menu-sticky .product--title' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_title_box_shadow',
				'selector' => '{{WRAPPER}} .product--menu-sticky .product--title',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_title_box_shadow',
				'selector' => '{{WRAPPER}} .product--menu-sticky .product--title',
			]
		);

		$this->set_padding( 'title_padding', '.product--menu-sticky .product--title', '0', '0', '0', '20', false );

		$this->set_margin( 'title_margin', '.product--menu-sticky .product--title', '0', '0', '0', '0', false );

	}

	public function products_price_style() {

		$this->display_element( 'display_price', esc_html__( 'Display Listing Price', 'lisfinity-core' ) );

		$this->set_background_color( 'color', 'rgba(38,38,38, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.lisfinity-product--meta__price', false );

		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'single_product_id_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--meta__price ',
			]
		);

		$this->add_control(
			'place_icon_price',
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
			'icon_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_price' => 'yes',
				]
			]
		);

		$this->add_control(
			'products_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'products_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(149, 149, 149, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'products_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function products_countdown_style() {
		$this->display_element( 'display_countdown', 'Display Countdown' );

		$this->set_background_color( 'color_countdown', 'rgba(38,38,38, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.countdown', false );

		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_countdown_typography',
				'selector' => '{{WRAPPER}} .countdown ',
			]
		);

		$this->add_control(
			'place_icon_countdown_price',
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
			'icon_price_countdown',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_countdown_price' => 'yes',
				]
			]
		);

		$this->add_control(
			'products_icon_countdown_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-countdown' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'products_icon_countdown_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(149, 149, 149, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-countdown' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'products_icon_countdown_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon-countdown' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function products_place_bid_style() {

		$this->start_controls_tabs(
			'place_bid_tabs',
			[
				'label' => __( 'Place Bid', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'place_bid_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'text_place_bid',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Place Bid', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Typography::get_type(),
			[
				'name'     => 'single_product_button_place_bid_typography',
				'selector' => '{{WRAPPER}} button.bg-green-700',
			]
		);

		$this->set_background_color( 'place_bid_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Color', 'lisfinity-core' ), 'button.bg-green-700', false );

		$this->set_background_color( 'place_bid_bg_color', 'rgba(39, 171, 131, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), 'button.bg-green-700' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_button_place_bid_border',
				'selector' => '{{WRAPPER}} button.bg-green-700',
			]
		);

		$this->set_border_radius( 'place_bid_border_radius', '3', '3', '3', '3', 'px', 'button.bg-green-700' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_button_place_bid_box_shadow',
				'selector' => '{{WRAPPER}} button.bg-green-700',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'place_bid_hover_tab',
			[
				'label' => __( 'On Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Typography::get_type(),
			[
				'name'     => 'single_product_button_place_bid_hover_typography',
				'selector' => '{{WRAPPER}} button.bg-green-700:hover',
			]
		);

		$this->set_background_color( 'place_bid_hover_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Color', 'lisfinity-core' ), 'button.bg-green-700:hover', false );

		$this->set_background_color( 'place_bid_hover_bg_color', 'rgba(39, 171, 131, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), 'button.bg-green-700:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_button_place_bid_hover_border',
				'selector' => '{{WRAPPER}} button.bg-green-700:hover',
			]
		);

		$this->set_border_radius( 'place_bid_hover_border_radius', '3', '3', '3', '3', 'px', 'button.bg-green-700:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_button_place_bid_hover_box_shadow',
				'selector' => '{{WRAPPER}} button.bg-green-700:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	public function products_buy_now_style() {

		$this->set_heading_section( 'buy_now_price_heading', esc_html__( 'Price', 'lisfinity-core' ), 'buy_now_price_hr' );

		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_by_now_price_typography',
				'selector' => '{{WRAPPER}} .send-message .woocommerce-Price-amount.amount',
			]
		);

		$this->set_background_color( 'buy_now_price_color', 'rgba(207, 17, 36, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.send-message .woocommerce-Price-amount.amount', false );

		$this->set_background_color( 'buy_now_price_bg_color', 'rgba(255, 244, 244, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.send-message .buy-now-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_buy_now_price_border',
				'selector' => '{{WRAPPER}} .send-message .buy-now-price',
			]
		);

		$this->set_border_radius( 'buy_now_price_border_radius', '3', '3', '3', '3', 'px', '.send-message .buy-now-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_buy_now_price_box_shadow',
				'selector' => '{{WRAPPER}} .send-message .buy-now-price',
			]
		);

		$this->start_controls_tabs(
			'buy_now_tabs',
			[
				'label' => __( 'Buy Now', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'buy_now_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'buy_now_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Buy Now', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Typography::get_type(),
			[
				'name'     => 'single_product_button_buy_now_typography',
				'selector' => '{{WRAPPER}} button.bg-red-500',
			]
		);

		$this->set_background_color( 'buy_now_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Color', 'lisfinity-core' ), 'button.bg-red-500', false );

		$this->set_background_color( 'buy_now_bg_color', 'rgba(248, 106, 106, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), 'button.bg-red-500' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_button_buy_now_border',
				'selector' => '{{WRAPPER}} button.bg-red-500',
			]
		);

		$this->set_border_radius( 'buy_now_border_radius', '3', '3', '3', '3', 'px', 'button.bg-red-500' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_button_buy_now_box_shadow',
				'selector' => '{{WRAPPER}} button.bg-red-500',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'buy_now_hover_tab',
			[
				'label' => __( 'On Hover', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'buy_now_text_hover',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Buy Now', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Typography::get_type(),
			[
				'name'     => 'single_product_button_buy_now_hover_typography',
				'selector' => '{{WRAPPER}} button.bg-red-500:hover',
			]
		);
		$this->set_background_color( 'buy_now_color_hover', 'rgba(255, 255, 255, 1)', esc_html__( 'Color', 'lisfinity-core' ), 'button.bg-red-500:hover', false );

		$this->set_background_color( 'buy_now_bg_color_hover', 'rgba(248, 106, 106, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), 'button.bg-red-500:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_button_buy_now_border_hover',
				'selector' => '{{WRAPPER}} button.bg-red-500:hover',
			]
		);

		$this->set_border_radius( 'buy_now_border_radius_hover', '3', '3', '3', '3', 'px', 'button.bg-red-500:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_button_buy_now_box_shadow_hover',
				'selector' => '{{WRAPPER}} button.bg-red-500:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	public function products_fixed_price_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_fixed_price_typography',
				'selector' => '{{WRAPPER}} .product-info-fixed-price .woocommerce-Price-amount.amount',
			]
		);

		$this->set_background_color( 'fixed_price_color', 'rgba(207, 17, 36, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.product-info-fixed-price .woocommerce-Price-amount.amount', false );

		$this->set_background_color( 'fixed_price_bg_color', 'rgba(255, 244, 244, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.product-info-fixed-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_fixed_price_border',
				'selector' => '{{WRAPPER}} .product-info-fixed-price',
			]
		);

		$this->set_border_radius( 'fixed_price_border_radius', '3', '3', '3', '3', 'px', '.product-info-fixed-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_fixed_price_box_shadow',
				'selector' => '{{WRAPPER}} .product-info-fixed-price',
			]
		);
	}

	public function products_negotiable_price_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_negotiable_price_typography',
				'selector' => '{{WRAPPER}} .product-info-negotiable-price .woocommerce-Price-amount.amount',
			]
		);
		$this->set_background_color( 'negotiable_price_color', 'rgba(207, 17, 36, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.product-info-negotiable-price .woocommerce-Price-amount.amount', false );

		$this->set_background_color( 'negotiable_price_bg_color', 'rgba(255, 244, 244, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.product-info-negotiable-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_negotiable_price_border',
				'selector' => '{{WRAPPER}} .product-info-negotiable-price',
			]
		);

		$this->set_border_radius( 'negotiable_price_border_radius', '3', '3', '3', '3', 'px', '.product-info-negotiable-price' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_negotiable_price_box_shadow',
				'selector' => '{{WRAPPER}} .product-info-negotiable-price',
			]
		);

		$this->start_controls_tabs(
			'negotiable_label_tabs',
			[
				'label' => __( 'Negotiable Label', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'negotiable_label_tab',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_negotiable_price_label_typography',
				'selector' => '{{WRAPPER}} .negotiable--info .product--price',
			]
		);

		$this->set_background_color( 'negotiable_price_label_color', 'rgba(38, 38, 38, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.negotiable--info .product--price', false );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'negotiable_label_icon_tab',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'place_icon_negotiable_label_icon_price',
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
			'icon_price_negotiable_label_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_negotiable_label_icon_price' => 'yes',
				]
			]
		);

		$this->add_control(
			'products_icon_negotiable_label_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-negotiable' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'products_icon_negotiable_label_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(149, 149, 149, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-negotiable' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'products_icon_negotiable_label_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon-negotiable' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function products_on_call_price_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_on_call_price_typography',
				'selector' => '{{WRAPPER}} .price-on-call .product--price',
			]
		);
		$this->set_background_color( 'on_call_price_color', 'rgba(9, 103, 210, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.price-on-call .product--price', false );

		$this->set_heading_section( 'on_call_icon_heading', esc_html__( 'Icon', 'lisfinity-core' ), 'on_call_icon_hr' );

		$this->add_control(
			'place_price_on_call_icon_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',

			]
		);

		$this->add_control(
			'icon_price_on_call_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_price_on_call_icon_price' => 'yes',
				]
			]
		);

		$this->add_control(
			'products_price_on_call_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-on-call' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'products_price_on_call_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(9, 103, 210, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-on-call' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'products_price_on_call_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon-on-call' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function products_free_price_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_free_price_typography',
				'selector' => '{{WRAPPER}} .free-price',
			]
		);

		$this->set_background_color( 'free_price_color', 'rgba(39, 171, 131, 1 )', esc_html__( 'Color', 'lisfinity-core' ), '.free-price', false );

		$this->set_heading_section( 'free_icon_heading', esc_html__( 'Icon', 'lisfinity-core' ), 'free_icon_hr' );

		$this->add_control(
			'place_price_free_icon_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',

			]
		);

		$this->add_control(
			'icon_price_free_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_price_free_icon_price' => 'yes',
				]
			]
		);

		$this->add_control(
			'products_price_free_icon_size',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
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
					'size' => 14,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-free' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'products_price_free_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(39, 171, 131, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .products-icon-free' => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'products_price_free_icon_position',
			[
				'label'     => __( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-icon-free' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function products_on_sale_price_style() {

		$this->set_background_color( 'on_sale_price_bg_color', 'rgba(255, 244, 244, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.on-sale-container' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_on_sale_price_border',
				'selector' => '{{WRAPPER}} .on-sale-container',
			]
		);

		$this->set_border_radius( 'on_sale_price_border_radius', '3', '3', '3', '3', 'px', '.on-sale-container' );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_on_sale_price_box_shadow',
				'selector' => '{{WRAPPER}} .on-sale-container',
			]
		);

		$this->start_controls_tabs(
			'on_sale_tabs',
			[
				'label' => __( 'On sale', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'on_sale_default_tab',
			[
				'label' => __( 'Regular Price', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_on_sale_price_regular_typography',
				'selector' => '{{WRAPPER}} .on-sale-container del .woocommerce-Price-amount.amount',
			]
		);

		$this->set_background_color( 'on_sale_price_regular_color', 'rgba(207,17,36, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.on-sale-container del .woocommerce-Price-amount.amount', false );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'on_sale_price_tab',
			[
				'label' => __( 'Sale Price', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Price_Countdown_Typography::get_type(),
			[
				'name'     => 'single_product_on_sale_price_typography',
				'selector' => '{{WRAPPER}} .on-sale-container ins .woocommerce-Price-amount.amount',
			]
		);
		$this->set_background_color( 'on_sale_price_color', 'rgba(207,17,36, 1)', esc_html__( 'Color', 'lisfinity-core' ), '.on-sale-container ins .woocommerce-Price-amount.amount', false );

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
			'settings' => $settings
		];

		include lisfinity_get_template_part( 'product-sticky-menu', 'shortcodes/product-single', $args );
	}

}
