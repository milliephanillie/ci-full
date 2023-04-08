<?php


namespace Lisfinity\Shortcodes\Auth;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Login_Form_Widget extends Shortcode {

	public $fields = [];

	public function __construct( $data = [], $args = NULL ) {
		parent::__construct( $data, $args );
		$this->fields = [
			'username' => esc_html__( 'Username', 'lisfinity-core' ),
			'password' => esc_html__( 'Password', 'lisfinity-core' ),
			'rememberme' => esc_html__( 'Remember Me', 'lisfinity-core' ),
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'login-form';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Login Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-image';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-login-page' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Login form.

		$this->start_controls_section(
			'login_form_content',
			[
				'label' => __( 'Form Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->form_fields();
		$this->end_controls_section();
		$this->form_wrapper();

		$this->labels();

		$this->input_fields_style();

		$this->checkbox();

		$this->forgot_password();

		$this->button_submit();

		$this->create_account();

	}

	public function form_fields() {
		$tab_repeater = new Repeater();

		$tab_repeater->add_control(
			'title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Action Title', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' ),
			]
		);

		$tab_repeater->add_control(
			'fields',
			[
				'label'   => __( 'Field', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->fields,
			]
		);
		$tab_repeater->add_control(
			'place_icon_field',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => TRUE,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$tab_repeater->add_control(
			'selected_icon_field',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_field' => 'yes',
				],
			]
		);

		$tab_repeater->add_control(
			'field_icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$tab_repeater->add_control(
			'field_icon_width',
			[
				'label'      => __( 'Size', 'lisfinity-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$tab_repeater->add_control(
			'field_icon_position',
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
					'size' => 5,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .product-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'fields_tabs',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $tab_repeater->get_controls(),
				'prevent_empty' => FALSE,
				'description'   => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'title_field'   => sprintf( __( 'Tab: %s', 'lisfinity-core' ), '{{{ fields }}}' ),
				'separator'     => 'before',
			]
		);

	}

	public function form_wrapper() {
		$this->start_controls_section(
			'login_form',
			[
				'label' => __( 'Form Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->set_background_color( 'login_form_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.form--auth' );
		$this->set_padding( 'login_form_wrapper_padding', '.form--auth', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'login_form_wrapper_margin', '.form--auth', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'login_form_wrapper_border_radius', '3', '3', '3', '3', 'px', '.form--auth' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'login_form_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .form--auth',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'login_form_wrapper_border',
				'selector' => '{{WRAPPER}} .form--auth',
			]
		);

		$this->end_controls_section();
	}

	public function labels() {
		$this->start_controls_section(
			'login_form_login_form_labels',
			[
				'label' => __( 'Form Fields Labels', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_margin( 'login_form_label_margin', '.form--auth .field--label', 0, 0, 4, 0, FALSE );

		$this->set_padding( 'login_form_label_padding', '.form--auth .field--label', 0, 0, 0, 0, FALSE );

		$this->add_responsive_control(
			'login_form_label_position',
			[
				'label'       => __( 'Label Position', 'lisfinity-core' ),
				'label_block' => TRUE,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => TRUE,
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
				'name'           => "login_form_labels",
				'selector'       => "{{WRAPPER}} .form--auth .field--label",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ],
					],
					'font_weight' => [
						'default' => 600,
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function input_fields_style() {

		$this->start_controls_section(
			'login_form_input_fields',
			[
				'label' => __( 'Filters Select Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_background_color( 'login_form_input_bg_color', '#f6f6f6', 'Background Color', '.form--auth .field--wrapper' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "login_form_input_border",
				'selector'       => "{{WRAPPER}} .form--auth .field--wrapper",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1',
						],
					],
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3',
						],
					],
				],
			]
		);

		$this->set_padding( 'login_form_input_padding', '.form--auth .field--wrapper', '14', '14', '14', '14', TRUE );

		$this->set_margin( 'login_form_input_margin', '.form--auth .field--wrapper', '0', '0', '0', '0', TRUE );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "login_form_input",
				'selector'       => "{{WRAPPER}} .form--auth .field--wrapper",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(149, 149, 149, 1)',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 400,
					],
				],
			]
		);

		$this->end_controls_section();

	}

	public function checkbox() {
		$this->start_controls_section(
			'login_form_checkbox_fields',
			[
				'label' => __( 'Filters Checkbox Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'login_form_checkbox_structure',
			[
				'label'     => __( 'Checkbox Structure Options', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'login_form_checkbox_columns',
			[
				'label'       => __( 'No. of Columns', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'description' => __( 'Choose number of columns', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .field--checkbox.field--checkbox__auth' => 'width: calc(100% / {{VALUE}});',
				],
			]
		);

		$this->add_control(
			'login_form_checkbox_styles',
			[
				'label'     => __( 'Checkbox Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->set_padding( 'login_form_checkbox_label_padding', '.field--checkbox.field--checkbox__auth label', 0, 0, 0, 12, FALSE, esc_html__( 'Label Padding', 'lisfinity-core' ) );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "login_form_checkbox_typography",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth label",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
			]
		);

		$this->add_control(
			'login_form_checkbox_bg_styles',
			[
				'label'     => __( 'Checkbox Background Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'login_form_checkbox_bg_color', '#f6f6f6', 'Background Color', '.field--checkbox.field--checkbox__auth input' );
		$this->set_background_color( 'login_form_checkbox_bg_color_active', '#2186eb', 'Background Color', '.field--checkbox.field--checkbox__auth input::after' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "login_form_checkbox_shadow",
				'selector' => "{{WRAPPER}} .field--checkbox.field--checkbox__auth input",
			]
		);

		$this->add_control(
			'login_form_checkbox_border_styles',
			[
				'label'     => __( 'Checkbox Border Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "login_form_checkbox_border",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth input",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1',
						],
					],
					'color'  => [ 'default' => 'rgba(215, 215, 215, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3',
						],
					],
				],
			]
		);


		$this->add_responsive_control(
			'login_form_checkbox_border_radius_active',
			[
				'label' => __( 'Border Radius Active Element' ),
				'type'  => Controls_Manager::DIMENSIONS,

				'default'   => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .field--checkbox.field--checkbox__auth input::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function forgot_password() {
		$this->start_controls_section(
			'forgot_password',
			[
				'label' => __( 'Forgot Password', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'custom_forgot_password_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => FALSE,
			]
		);

		$this->add_control(
			'forgot_password_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_forgot_password_text' => 'yes',
				],
			]
		);
		$this->start_controls_tabs( 'forgot_password_tabs' );

		// normal button values;
		$this->start_controls_tab( 'forgot_password_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "forgot_password_typography_typography",
				'selector'       => "{{WRAPPER}} .forgot-password",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#e12d39',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forgot_password_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "forgot_password_typography_typography_hover",
				'selector'       => "{{WRAPPER}} .forgot-password:hover",
				'fields_options' => [
					'typography'      => [ 'default' => 'yes' ],
					'color'           => [
						'default' => '#e12d39',
					],
					'font_size'       => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight'     => [
						'default' => 500,
					],
					'text_decoration' => [
						'default' => 'underline',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function button_submit() {
		$this->start_controls_section(
			'login_form_submit_button',
			[
				'label' => __( 'Submit Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_width( 'login_form_submit_button_width', '.login-submit-btn', '100', '%' );

		$this->set_padding( 'login_form_submit_button_padding', '.login-submit-btn', '0', '30', '0', '30', FALSE );

		$this->set_margin( 'login_form_submit_button_margin', '.login-submit-btn', '0', '0', '0', '0', FALSE );

		$this->add_control(
			'login_form_button_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'login_form_use_custom_button_icon',
			[
				'label'   => __( 'Place Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => FALSE,
			]
		);

		$this->add_control(
			'login_form_button_submit_icon',
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'login_form_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'login_form_button_icon_size',
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
					'{{WRAPPER}} .login-submit-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .login-submit-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'login_form_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'login_form_button_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .login-submit-btn svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .login-submit-btn i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'login_form_use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			'login_form_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'login_form_custom_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => FALSE,
			]
		);

		$this->add_control(
			'login_form_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'login_form_custom_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'login_form_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'login_form_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'login_form_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'login_form_button_bg_color', '#0967d2', 'Background Color', '.login-submit-btn' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "login_form_button_border",
				'selector'       => "{{WRAPPER}} .login-submit-btn",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1',
						],
					],
					'color'  => [ 'default' => 'rgba(9, 103, 210, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "login_form_button_shadow",
				'selector' => "{{WRAPPER}} .login-submit-btn",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "login_form_button_typography",
				'selector'       => "{{WRAPPER}} .login-submit-btn",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 16 ],
					],
					'font_weight' => [
						'default' => 700,
					],
				],
			]
		);

		$this->end_controls_tab();

		// hover button values.
		$this->start_controls_tab( 'login_form_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'login_form_button_bg_color_hover', '#03449e', 'Background Color', '.login-submit-btn:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "login_form_button_border_hover",
				'selector'       => "{{WRAPPER}} .login-submit-btn:hover",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1',
						],
					],
					'color'  => [ 'default' => 'rgba(3, 68, 158, 1)' ],
					'radius' => [
						'default' => [
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "login_form_button_shadow_hover",
				'selector' => "{{WRAPPER}} .login-submit-btn:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "login_form_button_typography_hover",
				'selector'       => "{{WRAPPER}} .login-submit-btn:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(255, 255, 255, 1)',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 16 ],
					],
					'font_weight' => [
						'default' => 700,
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	public function create_account() {
		$this->start_controls_section(
			'login_form_create_account',
			[
				'label' => __( 'No account?', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'login_form_create_account_tabs' );

		// normal button values;
		$this->start_controls_tab( 'login_form_create_account_text_tab',
			[
				'label' => __( 'Text', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'login_form_create_account_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => FALSE,
			]
		);

		$this->add_control(
			'create_account_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'login_form_create_account_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_text_typography",
				'selector'       => "{{WRAPPER}} .noaccount-text",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#262626',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'login_form_create_account_link',
			[
				'label' => __( 'Link', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'login_form_create_account_link_text',
			[
				'label'   => __( 'Different Link Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => FALSE,
			]
		);

		$this->add_control(
			'create_account_link_text',
			[
				'label'       => __( 'Different Link Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'login_form_create_account_link_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_link_text_typography",
				'selector'       => "{{WRAPPER}} .noaccount-link",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#0967D2',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_link_text_typography_hover",
				'selector'       => "{{WRAPPER}} .noaccount-link:hover",
				'separator'      => 'before',
				'fields_options' => [
					'typography'      => [ 'default' => 'yes' ],
					'color'           => [
						'default' => '#0967D2',
					],
					'font_size'       => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight'     => [
						'default' => 500,
					],
					'text-decoration' => [
						'default' => 'underline',
					],
				],
				'label'          => 'Typography On Hover',
			]
		);

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

		include lisfinity_get_template_part( 'login-form', 'shortcodes/auth', $args );
	}

}
