<?php


namespace Lisfinity\Shortcodes\Auth;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Breadcrumbs_Active_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Password_Reset_Form_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'password-reset-form';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Password Reset Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-unlock-alt';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-auth' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'input_style',
			[
				'label' => __( 'Input', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->input_fields_style();

		$this->end_controls_section();
		$this->start_controls_section(
			'label_style',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->labels();

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			[
				'label' => __( 'Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->button_submit();

		$this->end_controls_section();

		$this->start_controls_section(
			'back_to_login',
			[
				'label' => __( 'Back to Login Style', 'lisfinty-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);
		$this->back_to_login();
		$this->end_controls_section();

		$this->start_controls_section(
			'success_message',
			[
				'label' => __( 'Success Message Style', 'lisfinty-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);
		$this->success_message_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'error_message',
			[
				'label' => __( 'Error Message Style', 'lisfinty-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);
		$this->error_message_style();
		$this->end_controls_section();


	}

	public function input_fields_style() {

		$this->add_control(
			'reset_password_use_custom_input_icon',
			[
				'label'   => __( 'Place Different Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'reset_password_input_submit_icon',
			[
				'label'       => __( 'Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'reset_password_use_custom_input_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'reset_password_input_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .field-icon, {{WRAPPER}} .field-icon svg' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'reset_password_input_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(104, 104, 104, 1)',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .field-icon, {{WRAPPER}} .field-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'separator'   => 'after',
			]
		);

		$this->add_control(
			'reset_password_input_icon_position',
			[
				'label'      => __( 'Icon Spacing', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 8,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} .field-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};'
				],
			] );


		$this->set_background_color( 'password_reset_input_bg_color', '#f6f6f6', 'Background Color', '.form--auth .field--wrapper' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "password_reset_input_border",
				'selector'       => "{{WRAPPER}} .form--auth .field--wrapper",
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

		$this->set_padding( 'password_reset_input_padding', '.form--auth .field--wrapper', '14', '14', '14', '14', true );

		$this->set_margin( 'password_reset_input_margin', '.form--auth .field--wrapper', '0', '0', '0', '0', true );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "password_reset_input",
				'selector'       => "{{WRAPPER}} .form--auth .field--wrapper",
				'separator'      => 'before',
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

	public function labels() {
		$this->set_margin( 'password_reset_label_margin', '.form--auth .field--label', 0, 0, 4, 0, false );

		$this->set_padding( 'password_reset_label_padding', '.form--auth .field--label', 0, 0, 0, 0, false );

		$this->add_responsive_control(
			'password_reset_label_position',
			[
				'label'       => __( 'Label Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => true,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'flex-start',
				'selectors'   => [
					'{{WRAPPER}} .form--auth .field--top' => 'justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "password_reset_labels",
				'selector'       => "{{WRAPPER}} .form--auth .field--label",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ]
					],
					'font_weight' => [
						'default' => 600
					],
				],
			]
		);

	}

	public function button_submit() {

		$this->set_width( 'button_width', '.success-message', '100', '%' );

		$this->add_responsive_control(
			'button_position',
			[
				'label'       => __( 'Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => true,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .success-message-wrapper' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->set_padding( 'button_padding', '.password-reset-submit-btn', '0', '30', '0', '30', false );

		$this->set_margin( 'button_margin', '.password-reset-submit-btn', '0', '0', '0', '0', false );

		$this->add_control(
			'reset_password_button_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_password_use_custom_button_icon',
			[
				'label'   => __( 'Place Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'reset_password_button_submit_icon',
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'reset_password_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'reset_password_button_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .password-reset-submit-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .password-reset-submit-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'reset_password_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'reset_password_button_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .password-reset-submit-btn svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .password-reset-submit-btn i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'reset_password_use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			'reset_password_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_password_custom_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'reset_password_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'reset_password_custom_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'reset_password_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'reset_password_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'reset_password_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'reset_password_button_bg_color', '#0967d2', 'Background Color', '.password-reset-submit-btn' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "reset_password_button_border",
				'selector'       => "{{WRAPPER}} .password-reset-submit-btn",
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
					'color'  => [ 'default' => 'rgba(9, 103, 210, 1)' ],
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

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "reset_password_button_shadow",
				'selector' => "{{WRAPPER}} .password-reset-submit-btn",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "reset_password_button_typography",
				'selector'       => "{{WRAPPER}} .password-reset-submit-btn",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
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

		$this->end_controls_tab();

		// hover button values.
		$this->start_controls_tab( 'reset_password_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'reset_password_button_bg_color_hover', '#03449e', 'Background Color', '.password-reset-submit-btn:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "reset_password_button_border_hover",
				'selector'       => "{{WRAPPER}} .password-reset-submit-btn:hover",
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
					'color'  => [ 'default' => 'rgba(3, 68, 158, 1)' ],
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

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "reset_password_button_shadow_hover",
				'selector' => "{{WRAPPER}} .password-reset-submit-btn:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "reset_password_button_typography_hover",
				'selector'       => "{{WRAPPER}} .password-reset-submit-btn:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
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

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function back_to_login() {
		$this->add_control(
			'back_to_login_different_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'back_to_login_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'back_to_login_different_text' => 'yes',
				],
			]
		);
		$this->start_controls_tabs( 'back_to_login_tabs' );

		// normal button values;
		$this->start_controls_tab( 'back_to_login_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "back_to_login_typography_typography",
				'selector' => "{{WRAPPER}} .back-to-login",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#e12d39'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 500
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'back_to_login_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "back_to_login_typography_typography_hover",
				'selector' => "{{WRAPPER}} .back-to-login:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#e12d39'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight' => [
						'default' => 500
					],
					'text_decoration' => [
						'default' => 'underline'
					]
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function success_message_style() {
		$this->add_control(
			'important_note',
			[
				'raw' => __( 'This field will be visible after successfully submitted form.', 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
			]
		);
		$this->set_width( 'success_message_width', '.success-message', '100', '%' );

		$this->add_responsive_control(
			'success_message_position',
			[
				'label'       => __( 'Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => true,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .success-message-wrapper' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->set_background_color('success_message_bg_color', '#3ebd93', esc_html__('Background Color', 'lisfinty-core'), '.success-message');

		$this->set_padding( 'success_message_padding', '.success-message', '20', '20', '20', '20', false );

		$this->set_margin( 'success_message_margin', '.success-message', '20', '0', '0', '0', false );

		$this->add_control(
			'success_message_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'success_message_different_icon',
			[
				'label'   => __( 'Place Different Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'success_message_icon',
			[
				'label'       => __( 'Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'success_message_different_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'success_message_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .success-message svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .success-message i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'success_message_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, .25)',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .success-message svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .success-message i'   => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);
		$this->add_control(
			'success_message_icon_position_horizontal',
			[
				'label'       => __( 'Position Horizontal', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 9999,
						'max' => 9999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '-20',
				],
				'selectors'   => [
					"{{WRAPPER}} .success-message-icon" => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);


		$this->add_control(
			'success_message_icon_position_vertical',
			[
				'label'       => __( 'Position Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 9999,
						'max' => 9999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					"{{WRAPPER}} .success-message-icon" => 'top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// button text.
		$this->add_control(
			'success_message_text_heading',
			[
				'label'     => __( 'Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'success_message_different_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'success_message_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'success_message_different_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "success_message_typography",
				'selector'       => "{{WRAPPER}} .success-message",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
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


	}

	public function error_message_style() {
		$this->add_control(
			'important_note_error',
			[
				'raw' => __( 'This field will be visible after unsuccessfully submitted form.', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->set_width( 'error_message_width', '.error-message', '100', '%' );

		$this->add_responsive_control(
			'error_message_position',
			[
				'label'       => __( 'Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => true,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .error-message-wrapper' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->set_background_color('error_message_bg_color', '#ef4e4e', esc_html__('Background Color', 'lisfinty-core'), '.error-message');

		$this->set_padding( 'error_message_padding', '.error-message', '20', '20', '20', '20', false );

		$this->set_margin( 'error_message_margin', '.error-message', '20', '0', '0', '0', false );

		$this->add_control(
			'error_message_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'error_message_different_icon',
			[
				'label'   => __( 'Place Different Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'error_message_icon',
			[
				'label'       => __( 'Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'error_message_different_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'error_message_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size', 'lisfinity-core' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .error-message svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .error-message i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'error_message_icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, .25)',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .error-message svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .error-message i'   => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);
		$this->add_control(
			'error_message_icon_position_horizontal',
			[
				'label'       => __( 'Position Horizontal', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 9999,
						'max' => 9999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '-20',
				],
				'selectors'   => [
					"{{WRAPPER}} .error-message-icon" => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);


		$this->add_control(
			'error_message_icon_position_vertical',
			[
				'label'       => __( 'Position Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 9999,
						'max' => 9999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '-6',
				],
				'selectors'   => [
					"{{WRAPPER}} .error-message-icon" => 'top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// button text.
		$this->add_control(
			'error_message_text_heading',
			[
				'label'     => __( 'Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'error_message_different_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'error_message_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'error_message_different_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "error_message_typography",
				'selector'       => "{{WRAPPER}} .error-message",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)'
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

		include lisfinity_get_template_part( 'password-reset-form', 'shortcodes/auth', $args );
	}

}
