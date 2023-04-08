<?php


namespace Lisfinity\Shortcodes\Auth;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Register_Form_Widget extends Shortcode {
	public $fields = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->fields = [
			'username' => esc_html__( 'Username', 'lisfinity-core' ),
			'first_name' => esc_html__( 'First Name', 'lisfinity-core' ),
			'last_name' => esc_html__( 'Last Name', 'lisfinity-core' ),
			'email'    => esc_html__( 'Email', 'lisfinity-core' ),
			'password' => esc_html__( 'Password', 'lisfinity-core' ),
			'phone'    => esc_html__( 'Phone', 'lisfinity-core' ),
			'terms'    => esc_html__( 'Terms & Conditions', 'lisfinity-core' ),
			'captcha' => esc_html__( 'reCAPTCHA', 'lisfinity-core' ),
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'register-form';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Register Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-user-plus';
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
		// Login form.

		$this->start_controls_section(
			'register_form_content',
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

		$this->button_submit();

		$this->create_account();

	}

	public function form_fields() {
		$tab_repeater = new Repeater();

		$tab_repeater->add_control(
			'important_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'To enable reCaptcha, you need to fill the fields in the WP dashboard/Lisfinity options/register setup/reCAPTCHA.', 'lisfinity_core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'fields' => 'captcha'
				]
			]
		);


		$tab_repeater->add_control(
			'title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tab Title', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' )
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
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],
				'conditions'    => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'fields',
							'operator' => '!==',
							'value' => 'terms',
						],
						[
							'name' => 'fields',
							'operator' => '!==', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'captcha',
						],
					],
				]
			]
		);

		$tab_repeater->add_control(
			'selected_icon_field',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_field' => 'yes'
				]
			]
		);

		$tab_repeater->add_control(
			'field_icon_color',
			[
				'label'     => __( 'Icon Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .field-icon, {{WRAPPER}} {{CURRENT_ITEM}} .field-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};'
				],
				'conditions'    => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'fields',
							'operator' => '!==',
							'value' => 'terms',
						],
						[
							'name' => 'fields',
							'operator' => '!==', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'captcha',
						],
					],
				]
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
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .field-icon, {{WRAPPER}} {{CURRENT_ITEM}} .field-icon svg' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'
				],
				'conditions'    => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'fields',
							'operator' => '!==',
							'value' => 'terms',
						],
						[
							'name' => 'fields',
							'operator' => '!==', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'captcha',
						],
					],
				]
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
					'size' => 8,
					'unit' => 'px'
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .field-icon' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};'
				],
				'conditions'    => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'fields',
							'operator' => '!==',
							'value' => 'terms',
						],
						[
							'name' => 'fields',
							'operator' => '!==', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'captcha',
						],
					],
				]
			]
		);

		$this->add_control(
			'fields_tabs',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $tab_repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'title_field'   => sprintf( __( 'Tab: %s', 'lisfinity-core' ), '{{{ fields }}}' ),
				'separator'     => 'before',
			]
		);

	}

	public function form_wrapper() {
		$this->start_controls_section(
			'register_form',
			[
				'label' => __( 'Form Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->set_background_color( 'register_form_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.form--auth' );
		$this->set_padding( 'register_form_wrapper_padding', '.form--auth', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'register_form_wrapper_margin', '.form--auth', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'register_form_wrapper_border_radius', '3', '3', '3', '3', 'px', '.form--auth' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'register_form_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .form--auth',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'register_form_wrapper_border',
				'selector' => '{{WRAPPER}} .form--auth',
			]
		);

		$this->end_controls_section();
	}

	public function labels() {
		$this->start_controls_section(
			'register_form_register_form_labels',
			[
				'label' => __( 'Form Fields Labels', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_margin( 'register_form_label_margin', '.form--auth .field--label', 0, 0, 4, 0, false );

		$this->set_padding( 'register_form_label_padding', '.form--auth .field--label', 0, 0, 0, 0, false );

		$this->add_responsive_control(
			'register_form_label_position',
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
				'name'           => "register_form_labels",
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

		$this->end_controls_section();
	}

	public function input_fields_style() {

		$this->start_controls_section(
			'register_form_input_fields',
			[
				'label' => __( 'Input Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_background_color( 'register_form_input_bg_color', '#f6f6f6', 'Background Color', '.form--auth .field--wrapper' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "register_form_input_border",
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

		$this->set_padding( 'register_form_input_padding', '.form--auth .field--wrapper', '14', '14', '14', '14', true );

		$this->set_margin( 'register_form_input_margin', '.form--auth .field--wrapper', '0', '0', '0', '0', true );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_input",
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

		$this->end_controls_section();

	}

	public function checkbox() {
		$this->start_controls_section(
			'register_form_checkbox_fields',
			[
				'label' => __( 'Checkbox Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'register_form_checkbox_structure',
			[
				'label'     => __( 'Checkbox Structure Options', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'register_form_checkbox_columns',
			[
				'label'       => __( 'No. of Columns', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'description' => __( 'Choose number of columns', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .field--checkbox.field--checkbox__auth' => 'width: calc(100% / {{VALUE}});',
				]
			]
		);

		$this->add_control(
			'register_form_checkbox_styles',
			[
				'label'     => __( 'Checkbox Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->set_padding( 'register_form_checkbox_label_padding', '.field--checkbox.field--checkbox__auth label', 0, 0, 0, 12, false, esc_html__( 'Label Padding', 'lisfinity-core' ) );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_checkbox_typography",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth label",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(45, 45, 45, 1)'
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
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_checkbox_typography_link",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth label a",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#0552b5'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '600'
					],
				],
				'label'          => 'Link Typography'
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_checkbox_typography_link_hover",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth label a:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#0552b5'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '14' ]
					],
					'font_weight' => [
						'default' => '600'
					],
					'text_decoration' => [
						'default' => 'underline'
					],
				],
				'label'          => 'Link Typography on Hover'
			]
		);

		$this->add_control(
			'register_form_checkbox_bg_styles',
			[
				'label'     => __( 'Checkbox Background Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'register_form_checkbox_bg_color', '#f6f6f6', 'Background Color', '.field--checkbox.field--checkbox__auth input' );
		$this->set_background_color( 'register_form_checkbox_bg_color_active', '#2186eb', 'Background Color', '.field--checkbox.field--checkbox__auth input::after' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "register_form_checkbox_shadow",
				'selector' => "{{WRAPPER}} .field--checkbox.field--checkbox__auth input",
			]
		);

		$this->add_control(
			'register_form_checkbox_border_styles',
			[
				'label'     => __( 'Checkbox Border Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "register_form_checkbox_border",
				'selector'       => "{{WRAPPER}} .field--checkbox.field--checkbox__auth input",
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


		$this->add_responsive_control(
			'register_form_checkbox_border_radius_active',
			[
				'label' => __( 'Border Radius Active Element' ),
				'type'  => Controls_Manager::DIMENSIONS,

				'default'   => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0'
				],
				'selectors' => [
					'{{WRAPPER}} .field--checkbox.field--checkbox__auth input::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function button_submit() {
		$this->start_controls_section(
			'register_form_submit_button',
			[
				'label' => __( 'Submit Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->set_width( 'register_form_submit_button_width', '.register-btn', '100', '%' );

		$this->add_responsive_control(
			'register_button_position',
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
					'{{WRAPPER}} .register-button-wrapper' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->set_padding( 'register_form_submit_button_padding', '.register-btn', '0', '30', '0', '30', false );

		$this->set_margin( 'register_form_submit_button_margin', '.register-btn', '0', '0', '0', '0', false );

		$this->add_control(
			'register_form_button_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'register_form_use_custom_button_icon',
			[
				'label'   => __( 'Place Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'register_form_button_submit_icon',
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'register_form_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'register_form_button_icon_size',
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
					'{{WRAPPER}} .register-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .register-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'register_form_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'register_form_button_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .register-btn svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .register-btn i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'register_form_use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			'register_form_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'register_form_custom_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'register_form_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'register_form_custom_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'register_form_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'register_form_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'register_form_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'register_form_button_bg_color', '#27ab83', 'Background Color', '.register-btn' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "register_form_button_border",
				'selector'       => "{{WRAPPER}} .register-btn",
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
					'color'  => [ 'default' => '#27ab83' ],
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
				'name'     => "register_form_button_shadow",
				'selector' => "{{WRAPPER}} .register-btn",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_button_typography",
				'selector'       => "{{WRAPPER}} .register-btn",
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
		$this->start_controls_tab( 'register_form_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'register_form_button_bg_color_hover', '#147d64', 'Background Color', '.register-btn:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "register_form_button_border_hover",
				'selector'       => "{{WRAPPER}} .register-btn:hover",
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
					'color'  => [ 'default' => '#147d64' ],
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
				'name'     => "register_form_button_shadow_hover",
				'selector' => "{{WRAPPER}} .register-btn:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "register_form_button_typography_hover",
				'selector'       => "{{WRAPPER}} .register-btn:hover",
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
		$this->end_controls_section();
	}

	public function create_account() {
		$this->start_controls_section(
			'register_form_create_account',
			[
				'label' => __( 'Have account?', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->set_background_color( 'have_account_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.have-account-wrapper' );
		$this->add_responsive_control(
			'have_account_width',
			[
				'label'       => __( 'Width', 'lisfinity-core' ),
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
					'unit' => '%',
					'size' => '100',
				],
				'selectors'   => [
					'{{WRAPPER}} .have-account-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->set_padding( 'have_account_padding', '.have-account-wrapper', '0', '0', '0', '0', true );
		$this->set_margin( 'have_account_margin', '.have-account-wrapper', '20', '0', '0', '0', false );

		$this->add_responsive_control(
			'have_account_position',
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
					'{{WRAPPER}} .have-account-container' => 'display: flex; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);


		$this->start_controls_tabs( 'register_form_create_account_tabs' );

		// normal button values;
		$this->start_controls_tab( 'register_form_create_account_text_tab',
			[
				'label' => __( 'Text', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'register_form_create_account_text',
			[
				'label'   => __( 'Different Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'have_account_text',
			[
				'label'       => __( 'Different Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'register_form_create_account_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_text_typography",
				'selector'       => "{{WRAPPER}} .have-account-text",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#262626'
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

		$this->start_controls_tab( 'register_form_create_account_link',
			[
				'label' => __( 'Link', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'register_form_create_account_link_text',
			[
				'label'   => __( 'Different Link Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'have_account_link_text',
			[
				'label'       => __( 'Different Link Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'register_form_create_account_link_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_link_text_typography",
				'selector'       => "{{WRAPPER}} .have-account-link",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#0967D2'
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

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "create_account_link_text_typography_hover",
				'selector'       => "{{WRAPPER}} .have-account-link:hover",
				'separator'      => 'before',
				'fields_options' => [
					'typography'      => [ 'default' => 'yes' ],
					'color'           => [
						'default' => '#0967D2'
					],
					'font_size'       => [
						'default' =>
							[ 'size' => 14 ]
					],
					'font_weight'     => [
						'default' => 500
					],
					'text-decoration' => [
						'default' => 'underline'
					]
				],
				'label'          => 'Typography On Hover'
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

		include lisfinity_get_template_part( 'register-form', 'shortcodes/auth', $args );
	}

}
