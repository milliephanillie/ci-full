<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Actions_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Title_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sidebar_Menu_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Product_Contact_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-contact-form';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Contact Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-envelope-o';
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
			'button',
			[
				'label' => __( 'Button styles', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->button_styles();

		$this->end_controls_section();

		$this->start_controls_section(
			'modal',
			[
				'label' => __( 'Modal styles', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->modal_styles();

		$this->end_controls_section();

	}

	public function button_styles() {
		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Send a Message', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);
		$this->set_elements_alignment('text_alignment', 'center', '.btn--contact-modal');
		$this->add_control(
			'place_icon_button',
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
			'selected_icon_button',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'button_tabs',
			[
				'label' => __( 'Tabs Visit Store', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"button_default_tab",
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->default_button();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"button_hover_tab",
			[
				'label' => __( 'hover', 'lisfinity-core' ),
			]
		);
		$this->hover_button();
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function default_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'default_button_typography',
				'selector' => '{{WRAPPER}} .btn--contact-modal',
			]
		);
		$this->add_control(
			'icon_color_button',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .btn--contact-modal-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_button',
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
					'{{WRAPPER}} .btn--contact-modal-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);
		$this->add_control(
			'icon_indent_button',
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
					'{{WRAPPER}} .btn--contact-modal-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);

		$this->set_background_color( 'default_button_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.btn--contact-modal' );
		$this->set_padding( 'default_button_padding', '.btn--contact-modal', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'default_button_margin', '.btn--contact-modal', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'default_button_border_radius', '3', '3', '3', '3', 'px', '.btn--contact-modal' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'default_button_box_shadow',
				'selector' => '{{WRAPPER}} .btn--contact-modal',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'default_button_border',
				'selector' => '{{WRAPPER}} .btn--contact-modal',
			]
		);
	}

	public function hover_button() {
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(),
			[
				'name'     => 'hover_button_typography',
				'selector' => '{{WRAPPER}} .btn--contact-modal:hover span',
			]
		);
		$this->add_control(
			'icon_color_button_hover',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .btn--contact-modal:hover .btn--contact-modal-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size_button_hover',
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
					'{{WRAPPER}} .btn--contact-modal:hover .btn--contact-modal-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_indent_button_hover',
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
					'{{WRAPPER}} .btn--contact-modal:hover .btn--contact-modal-icon' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition'        => [
					'place_icon_button' => 'yes'
				]
			]
		);
		$this->set_background_color( 'hover_button_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.btn--contact-modal:hover' );
		$this->set_padding( 'hover_button_padding', '.btn--contact-modal:hover', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'hover_button_margin', '.btn--contact-modal:hover', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'hover_button_border_radius', '3', '3', '3', '3', 'px', '.btn--contact-modal:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'hover_button_box_shadow',
				'selector' => '{{WRAPPER}} .btn--contact-modal:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'hover_button_border',
				'selector' => '{{WRAPPER}} .btn--contact-modal:hover',
			]
		);
	}

	public function modal_styles() {
		$this->start_controls_tabs(
			'modal_tabs',
			[
				'label' => __( 'Tabs Modal', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"header_tab",
			[
				'label' => __( 'Header', 'lisfinity-core' ),
			]
		);
		$this->header_style();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"content_tab",
			[
				'label' => __( 'Content', 'lisfinity-core' ),
			]
		);
		$this->content_styles();
		$this->end_controls_tab();
		$this->start_controls_tab(
			"save_button_tab",
			[
				'label' => __( 'Button', 'lisfinity-core' ),
			]
		);
		$this->save_button();
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function header_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(),
			[
				'name'     => 'modal_header_label_typography',
				'selector' => '{{WRAPPER}} .modal--title',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 16 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);

		$this->set_elements_alignment('title_alignment', 'left', '.modal--title', false);
		$this->set_background_color( 'modal_header_bg_color', 'rgba(246, 246, 246, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.modal--header' );
		$this->set_padding( 'modal_header_padding', '.modal--header', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'modal_header_margin', '.modal--header', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'modal_header_border_radius', '0', '0', '0', '0', 'px', '.modal--header' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'modal_header_box_shadow',
				'selector' => '{{WRAPPER}} .modal--header',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'modal_header_border',
				'selector' => '{{WRAPPER}} .modal--header',
			]
		);
	}

	public function content_styles() {

		$this->add_control(
			'content_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Click here', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'content_text_typography',
				'selector' => '{{WRAPPER}} #form-listing-modal-inner .mb-20',
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
		$this->set_elements_alignment('content_text_alignment', 'left', '#form-listing-modal-inner .mb-20', false);

		$this->set_heading_section('label_heading', esc_html__('Label', 'lisfinity-core'), 'label_hr');
		$this->add_group_control(
			Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .field--label',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#959595'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ]
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
		$this->set_elements_alignment('label_alignment', 'flex-start', '.field--top', true);
		$this->set_heading_section('fields_heading', esc_html__('Fields', 'lisfinity-core'), 'fields_hr');

		$this->set_background_color( 'fields_bg_color', '#f6f6f6', 'Background Color', '.field--wrapper' );
		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "fields_border",
				'selector' => "{{WRAPPER}} .field--wrapper",
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
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3'
						]
					],
				]
			]
		);
		$this->add_control(
			'fields_placeholder',
			[
				'label'     => __( 'Value Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "fields_placeholder",
				'selector' => "{{WRAPPER}} .field--wrapper input",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(149, 149, 149, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
	}

	public function save_button() {
		$this->add_control(
			'save_button_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Send', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Hover_Button_Typography::get_type(),
			[
				'name'     => 'save_button_typography',
				'selector' => '{{WRAPPER}} .save-button',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Owner_Hover_Button_Typography::get_type(),
			[
				'name'     => 'save_button_typography_hover',
				'selector' => '{{WRAPPER}} .save-button:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 700
					],
				],
				'label' => 'Typography on hover'
			]
		);
		$this->set_background_color( 'save_button_bg_color', '#0967d2', esc_html__( 'Background Color', 'lisfinity-core' ), '.save-button' );
		$this->set_background_color( 'save_button_bg_color_hover', '#03449E', esc_html__( 'Background Color on hover', 'lisfinity-core' ), '.save-button:hover' );
		$this->set_padding( 'save_button_padding', '.save-button', '12', '24', '12', '24', 'false' );
		$this->set_margin( 'save_button_margin', '.save-button', '0', '4', '0', '0', 'false' );
		$this->set_border_radius( 'save_button_border_radius', '3', '3', '3', '3', 'px', '.save-button' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'save_button_box_shadow',
				'selector' => '{{WRAPPER}} .save-button',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(),
			[
				'name'     => 'save_button_border',
				'selector' => '{{WRAPPER}} .save-button',
			]
		);
	}
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'settings' => $settings,
		];

		include lisfinity_get_template_part( 'product-contact-form', 'shortcodes/product-single', $args );
	}

}
