<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Messages_Text_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Typography;

class Product_Owner_Button_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-owner-button';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Owner Button', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-share-square';
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
			'visit_store_button',
			[
				'label' => __( 'Visit Store Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->display_element('display_visit_store_button', esc_html__('Display Button', 'lisfinity-core'));

		$this->add_control(
			'place_icon_visit_store_button',
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
			'selected_icon_visit_store_button',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_visit_store_button' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'visit_store_tabs',
			[
				'label' => __( 'Tabs Visit Store', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"visit_store_default_tab",
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->default_visit_store_button();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"visit_store_hover_tab",
			[
				'label' => __( 'hover', 'lisfinity-core' ),
			]
		);
		$this->hover_visit_store_button();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'messages_button_listing_owner',
			[
				'label' => __( 'Messages Button (Listing Owner)', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'map_warning',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'Appears when the user is the listing owner.', 'lisfinity-core' ), '<strong>Lisfinity Options -> Listings Setup</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->display_element('display_messages_button', esc_html__('Display Button', 'lisfinity-core'));

		$this->add_control(
			'place_icon_messages_button_listing_owner',
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
			'selected_icon_messages_button_listing_owner',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'messages_tabs_listing_owner',
			[
				'label' => __( 'Tabs Visit Store', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"messages_default_tab_listing_owner",
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->default_messages_button_listing_owner();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"messages_hover_tab_listing_owner",
			[
				'label' => __( 'hover', 'lisfinity-core' ),
			]
		);
		$this->hover_messages_button_listing_owner();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		$this->start_controls_section(
			'messages_button',
			[
				'label' => __( 'Messages Button (Not Listing Owner)', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'place_icon_messages_button',
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
			'selected_icon_messages_button',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_messages_button' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'messages_tabs',
			[
				'label' => __( 'Tabs Visit Store', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"messages_note_tab",
			[
				'label' => __( 'Note', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Button_Messages_Text_Typography::get_type(),
			[
				'name'     => 'messages_text_button_listing_owner_typography',
				'selector' => '{{WRAPPER}} .text-not-owner-button',
			]
		);
		$this->set_background_color( 'messages_text_button_listing_owner_button_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.text-not-owner-button' );
		$this->set_padding( 'messages_text_button_listing_owner_button_padding', '.text-not-owner-button', '0', '4', '0', '4', 'false' );
		$this->set_margin( 'messages_text_button_listing_owner_button_margin', '.text-not-owner-button', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'messages_text_button_listing_owner_button_border_radius', '3', '3', '3', '3', 'px', '.text-not-owner-button' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'messages_text_button_listing_owner_button_box_shadow',
				'selector' => '{{WRAPPER}} .text-not-owner-button',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			"messages_default_tab",
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->default_messages_button();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"messages_hover_tab",
			[
				'label' => __( 'hover', 'lisfinity-core' ),
			]
		);
		$this->hover_messages_button();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();



	}

	public function default_visit_store_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Button_Typography::get_type(),
			[
				'name'     => 'default_visit_store_button_typography',
				'selector' => '{{WRAPPER}} .profile--my-store span',
			]
		);
		$this->add_control(
			'icon_color_visit_store_button',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9, 103, 210, 1)',
				'selectors' => [
					'{{WRAPPER}} .profile--my-store .visit-store-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_visit_store_button',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .profile--my-store .visit-store-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_indent_visit_store_button',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .profile--my-store .visit-store-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->set_background_color( 'default_visit_store_button_bg_color', 'rgba(230, 246, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.profile--my-store' );
		$this->set_padding( 'default_visit_store_button_padding', '.profile--my-store', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'default_visit_store_button_margin', '.profile--my-store', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'default_visit_store_button_border_radius', '3', '3', '3', '3', 'px', '.profile--my-store' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'default_visit_store_button_box_shadow',
				'selector' => '{{WRAPPER}} .profile--my-store',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Button_Border::get_type(),
			[
				'name'     => 'default_visit_store_button_border',
				'selector' => '{{WRAPPER}} .profile--my-store',
			]
		);
	}

	public function hover_visit_store_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Hover_Button_Typography::get_type(),
			[
				'name'     => 'hover_visit_store_button_typography',
				'selector' => '{{WRAPPER}} .profile--my-store:hover span',
			]
		);
		$this->add_control(
			'icon_color_visit_store_button_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .profile--my-store:hover .visit-store-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_visit_store_button_hover',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .profile--my-store:hover .visit-store-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_indent_visit_store_button_hover',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .profile--my-store:hover .visit-store-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->set_background_color( 'hover_visit_store_button_bg_color', 'rgba(9, 103, 210, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.profile--my-store:hover' );
		$this->set_padding( 'hover_visit_store_button_padding', '.profile--my-store:hover', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'hover_visit_store_button_margin', '.profile--my-store:hover', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'hover_visit_store_button_border_radius', '3', '3', '3', '3', 'px', '.profile--my-store:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'hover_visit_store_button_box_shadow',
				'selector' => '{{WRAPPER}} .profile--my-store:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Hover_Button_Border::get_type(),
			[
				'name'     => 'hover_visit_store_button_border',
				'selector' => '{{WRAPPER}} .profile--my-store:hover',
			]
		);
	}

	public function default_messages_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'default_messages_button_typography',
				'selector' => '{{WRAPPER}} .messages-button',
			]
		);
		$this->add_control(
			'icon_color_messages_button',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .messages-button .messages-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_messages_button',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .messages-button .messages-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_indent_messages_button',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .messages-button .messages-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->set_background_color( 'default_messages_button_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.messages-button' );
		$this->set_padding( 'default_messages_button_padding', '.messages-button', '0', '24', '0', '24', 'false' );
		$this->set_margin( 'default_messages_button_margin', '.messages-button', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'default_messages_button_border_radius', '3', '3', '3', '3', 'px', '.messages-button' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'default_messages_button_box_shadow',
				'selector' => '{{WRAPPER}} .messages-button',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'default_messages_button_border',
				'selector' => '{{WRAPPER}} .messages-button',
			]
		);
	}

	public function hover_messages_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'hover_messages_button_typography',
				'selector' => '{{WRAPPER}} .messages-button:hover',
			]
		);
		$this->add_control(
			'icon_color_messages_button_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .messages-button:hover .messages-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_messages_button_hover',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .messages-button:hover .messages-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_indent_messages_button_hover',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .messages-button:hover .messages-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->set_background_color( 'hover_messages_button_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.messages-button:hover' );
		$this->set_padding( 'hover_messages_button_padding', '.messages-button:hover', '0', '24', '0', '24', 'false' );
		$this->set_margin( 'hover_messages_button_margin', '.messages-button:hover', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'hover_messages_button_border_radius', '3', '3', '3', '3', 'px', '.messages-button:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'hover_messages_button_box_shadow',
				'selector' => '{{WRAPPER}} .messages-button:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'hover_messages_button_border',
				'selector' => '{{WRAPPER}} .messages-button:hover',
			]
		);
	}

	public function default_messages_button_listing_owner() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'default_messages_button_listing_owner_typography',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner',
			]
		);
		$this->add_control(
			'icon_color_messages_button_listing_owner',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .messages-button-listing-owner .messages-icon-listing-owner' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_messages_button_listing_owner',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .messages-button-listing-owner .messages-icon-listing-owner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],

				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);
		$this->add_control(
			'icon_indent_messages_button_listing_owner',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .messages-button-listing-owner .messages-icon-listing-owner' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],

				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);

		$this->set_background_color( 'default_messages_button_listing_owner_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.messages-button-listing-owner' );
		$this->set_padding( 'default_messages_button_listing_owner_padding', '.messages-button-listing-owner', '0', '24', '0', '24', 'false' );
		$this->set_margin( 'default_messages_button_listing_owner_margin', '.messages-button-listing-owner', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'default_messages_button_listing_owner_border_radius', '3', '3', '3', '3', 'px', '.messages-button-listing-owner' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'default_messages_button_listing_owner_box_shadow',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'default_messages_button_listing_owner_border',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner',
			]
		);
	}

	public function hover_messages_button_listing_owner() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'hover_messages_button_listing_owner_typography',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner:hover',
			]
		);
		$this->add_control(
			'icon_color_messages_button_listing_owner_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .messages-button-listing-owner:hover .messages-icon-listing-owner' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_messages_button_listing_owner_hover',
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
					'size' => '18',
				],
				'selectors'   => [
					'{{WRAPPER}} .messages-button-listing-owner:hover .messages-icon-listing-owne' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_indent_messages_button_listing_owner_hover',
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .messages-button-listing-owner:hover .messages-icon-listing-owne' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'place_icon_messages_button_listing_owner' => 'yes'
				]
			]
		);
		$this->set_background_color( 'hover_messages_button_listing_owner_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.messages-button-listing-owner:hover' );
		$this->set_padding( 'hover_messages_button_listing_owner_padding', '.messages-button-listing-owner:hover', '0', '24', '0', '24', 'false' );
		$this->set_margin( 'hover_messages_button_listing_owner_margin', '.messages-button-listing-owner:hover', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'hover_messages_button_listing_owner_border_radius', '3', '3', '3', '3', 'px', '.messages-button-listing-owner:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'hover_messages_button_listing_owner_box_shadow',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'hover_messages_button_listing_owner_border',
				'selector' => '{{WRAPPER}} .messages-button-listing-owner:hover',
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

		include lisfinity_get_template_part( 'product-owner-button', 'shortcodes/product-single', $args );
	}

}
