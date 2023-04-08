<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\SearchBuilder\SearchBuilderModel;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Button_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Border;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Label_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Search_Field_Border;

class Hero_Search_Field extends Widget_Base {

	public $fields = [];
	public $field_labels = [];
	public $taxonomies = [];

	public $field_types = [ 'sb-keyword', 'sb-taxonomy', 'sb-meta', 'type' ];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$search_builder     = new SearchBuilderModel();
		$taxonomy_admin     = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$this->fields       = [ 'keyword' ];
		$this->fields       = array_merge( $this->fields, $search_builder->get_fields()['home']['fields'] );
		$this->field_labels = $search_builder->get_fields()['home']['label'];
		$this->taxonomies   = $taxonomy_admin->get_options()['common'];

	}


	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'lisfinity-hero-search';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Banner Search Form', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-search-plus';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity', 'lisfinity-banner', 'lisfinity-search' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'lisfinity_hero',
			[
				'label' => __( 'Form Fields', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->form_fields();
		$this->end_controls_section();
		$this->start_controls_section(
			'lisfinity_hero_container_style',
			[
				'label' => __( 'Form Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->form_wrapper();
		$this->end_controls_section();
		$this->form_fields_style();
		$this->start_controls_section(
			'lisfinity_hero_button_style',
			[
				'label' => __( 'Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->form_button();
		$this->end_controls_section();

	}

	/**
	 * Form Fields
	 * ------------------------------
	 */

	public function form_fields() {

		$repeater = new Repeater();
		$repeater->add_control(
			'field_label',
			[
				'label'     => __( 'Label', 'elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'field_label_hide' => 'yes'
				]
			]
		);

		$repeater->add_responsive_control(
			'display_field',
			[
				'label'        => __( 'Display field', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'custom_id!' => 'hero_button'
				]
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label'      => __( 'Width', 'elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default'    => [
					'unit' => '%',
					'size' => '100',
				],


			]
		);

		$repeater->add_responsive_control(
			'gap',
			[
				'label'       => __( 'Fields Columns Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);


		$taxonomy_slugs = array_column( $this->taxonomies, 'slug' );

		$default_fields = [];


		if ( ! empty( $this->fields ) ) {
			foreach ( $this->fields as $field ) {
				if ( ! in_array( $field, $this->field_types ) ) {
					if ( ! in_array( $field, [ 'keyword', 'price', 'category-type' ] ) ) {
						$key              = array_search( $field, $taxonomy_slugs );
						$label            = $this->taxonomies[ $key ]['single_name'];
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => $label,
						];
					} else if ( 'price' === $field ) {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Price', 'lisfinity-core' ),
						];
					} else if ( 'category-type' === $field ) {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Category', 'lisfinity-core' ),
						];
					} else {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Keyword', 'lisfinity-core' ),
						];
					}
				}
			}
		}

		$default_fields[] = [
			'custom_id'   => 'hero_button',
			'field_label' => __( 'Button', 'lisfinity-core' ),
		];


		$this->add_control(
			'lisfinity_hero_form_fields',
			[
				'type'        => 'repeater',
				'fields'      => $repeater->get_controls(),
				'default'     => $default_fields,
				'title_field' => '{{{ field_label }}}',
			]
		);

	}

	/**
	 * Form Fields style
	 * ------------------------------
	 */

	public function form_fields_style() {
		$repeater = new Repeater();
		$repeater->add_control(
			'field_label',
			[
				'label'   => __( 'Label', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$taxonomy_slugs = array_column( $this->taxonomies, 'slug' );

		$default_fields = [];

		if ( ! empty( $this->fields ) ) {
			foreach ( $this->fields as $field ) {
				if ( ! in_array( $field, $this->field_types ) ) {
					if ( ! in_array( $field, [ 'keyword', 'price', 'category-type' ] ) ) {
						$key              = array_search( $field, $taxonomy_slugs );
						$label            = $this->taxonomies[ $key ]['single_name'];
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => $label,
						];
					} else if ( 'price' === $field ) {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Price', 'lisfinity-core' ),
						];
					} else if ( 'category-type' === $field ) {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Category', 'lisfinity-core' ),
						];
					} else {
						$default_fields[] = [
							'custom_id'   => $field,
							'field_label' => __( 'Keyword', 'lisfinity-core' ),
						];
					}
				}
			}
		}

		foreach ( $default_fields as $key => $field ) {
			$this->start_controls_section(
				"global_style_$key",
				[
					'label' => __( 'Global Style', 'lisfinity-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);
			$this->global_style( $field['custom_id'], $key );

			$this->end_controls_section();


			$this->start_controls_section(
				$field['field_label'],
				[
					'label' => __( $field['field_label'], 'lisfinity-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);
			$this->style( $field['custom_id'] );
			$this->end_controls_section();

		}
	}

	/**
	 * Form Wrapper Style
	 * ------------------------------
	 */

	public function form_wrapper() {
		$this->set_background_color( 'form_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.search--form' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "form_wrapper_box_shadow",
				'selector' => "{{WRAPPER}} .search--form",
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Border::get_type(),
			[
				'name'     => "form_wrapper_border",
				'selector' => "{{WRAPPER}} .search--form",
			]
		);
		$this->set_border_radius( 'form_wrapper_border_radius', '3', '3', '3', '3', 'px', '.search--form' );

		$this->set_padding( 'form_wrapper_padding', '.search--form', '10', '10', '10', '10', 'true' );

		$this->set_margin( 'form_wrapper_margin', '.search--form', '10', '10', '10', '10', 'true' );

	}

	/**
	 * Form Fields global style
	 * ------------------------------
	 */

	public function global_style( $id, $key ) {
		$this->add_control(
			"field_color_$key",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					'{{WRAPPER}} .search--form .w-full .relative div input'            => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .search--form .relative div input'                    => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .search--form .search-meta .w-full .field--with-icon' => 'color: {{VALUE}}!important;',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_search_field_border_$key",
				'selector' =>
					'{{WRAPPER}} .search--form .home-search-keyword .flex-center, {{WRAPPER}} .search--form .select--range, {{WRAPPER}} .search--form .category-type, {{WRAPPER}} .search--form .w-full .flex.px-24.bg-white, {{WRAPPER}} .search--form .w-full .select-banner, {{WRAPPER}} .search--form .search-meta .w-full .field--with-icon, {{WRAPPER}} .search--form .w-full .field--checkbox .filters--checkbox'

			]
		);


		$this->add_control(
			"background_color_$key",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .search--form .w-full .relative.select-banner.bg-white'             => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .w-full .flex.px-24.bg-white'                         => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .category-type'                                       => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .w-full.home-search-keyword div.flex-center.bg-white' => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .home-search-keyword .flex-center'                    => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .home-search-keyword div.flex-center.bg-white input'                => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .w-full .relative div input'                          => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .home-search-keyword div.flex-center.bg-white'                      => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .search-meta .w-full .field--with-icon'               => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .select--range'                                       => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .search--form .field--checkbox input'                               => 'background-color:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			"background_color_of_dependent_$key",
			[
				'label'     => __( 'Background color of the dependent fields', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(215, 215, 215, 1)',
				'selectors' => [
					"{{WRAPPER}} .search--form .w-full .flex.px-24.bg-grey-300" => 'background-color:{{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			"field_border_radius_$key",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'unit'   => 'px',
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3
				],
				'selectors'   => [
					"{{WRAPPER}} .search--form .home-search-keyword .flex-center"           => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .select--range"                              => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .category-type"                              => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .w-full .flex.px-24.bg-white"                => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .w-full .select-banner"                      => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .search-meta .w-full .field--with-icon"      => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .w-full .field--checkbox .filters--checkbox" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			"field_padding_$key",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'default'     => [
					'top'      => 10,
					'right'    => 24,
					'bottom'   => 10,
					'left'     => 24,
					'isLinked' => false,
				],
				'selectors'   => [
					'{{WRAPPER}} .search--form .w-full .relative .flex-center'         => 'padding: 0px;',
					'{{WRAPPER}} .search--form .home-search-keyword .bg-white'         => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .category-type'                         => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .w-full .relative.select-banner'        => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .w-full.hero-checkbox-container .flex'  => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .search-meta .w-full .field--with-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			"field_margin_$key",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .search--form .home-search-keyword.relative .flex-center' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .w-full .relative.select-banner'            => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .w-full.hero-checkbox-container .flex'      => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .search--form .search-meta .w-full .field--with-icon'     => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 10,
					'left'     => 0,
					'isLinked' => false,
				]
			]
		);

		$this->add_control(
			"hr_$key",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs(
			"tabs_$key",
			[
				'label' => __( "Tabs Default", 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"tab_dropdown_$key",
			[
				'label' => __( 'Dropdown', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			"hr_dropdown_$key",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->dropdown_style_global($key);

		$this->end_controls_tab();

		$this->start_controls_tab(
			"tab_label_$key",
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			"hr_dropdown_label_$key",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->label_style_global( $id, $key );

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}


	/**
	 * Form Fields individual style
	 * ------------------------------
	 */

	public function style( $id ) {

		$taxonomy_admin = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$options        = $taxonomy_admin->get_taxonomy_options( $id );

		$this->display_element( "display_style_$id", esc_html__( 'Display Additional Style', 'lisfinity-core' ) );


		if ( 'keyword' === $id || ( is_array( $options ) && 'checkbox' !== $options['type'] ) ) {
			$this->add_control(
				"field_color_$id",
				[
					'label'     => __( 'Text Color', 'elementor-pro' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(0, 0, 0, 1)',
					'selectors' => [
						"{{WRAPPER}} .search--form .$id .flex-center input"                    => 'color: {{VALUE}}!important;',
						"{{WRAPPER}} .search--form .$id div.css-1pcexqc-container input"       => 'color: {{VALUE}}!important;',
						"{{WRAPPER}} .search--form .$id.search-meta .w-full .field--with-icon" => 'color: {{VALUE}}!important;',
						"{{WRAPPER}} .search--form .$id .field--checkbox input"                => 'color:{{VALUE}};',
					],
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);
		}


		$this->add_control(
			"background_color_$id",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .search--form .$id.home-search-keyword div.flex-center.bg-white"           => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id.home-search-keyword div.flex-center.bg-white input"     => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id.home-search-keyword div.flex-center.bg-white .relative" => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id .flex-center input"                                     => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .w-full .relative.$id.bg-white"                              => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .search-meta.$id .field--with-icon"                          => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id .field--checkbox input"                                 => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id .select--range"                                         => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .search--form .$id .select--range .css-1pcexqc-container"                  => 'background-color:{{VALUE}};',

				],
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		if ( is_array( $options ) && 'checkbox' === $options['type'] ) {

			$this->set_background_color( "active_bg_color_checkbox_$id", 'rgba(33, 134, 235, 1)', esc_html__( 'Background color of Active Element' ), ".$id .field--checkbox input:checked::after" );

			$this->add_control(
				"border_color_$id",
				[
					'label'     => __( 'Border Color', 'lisfinity-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(215, 215, 215, 1)',
					'selectors' => [
						"{{WRAPPER}} .$id .field--checkbox input" => 'border-color:{{VALUE}};',

					],
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);

		}

		$this->set_border_radius( "field_border_radius_$id", '3', '3', '3', '3', 'px', ".search--form .$id.home-search-keyword div.flex-center.bg-white, {{WRAPPER}} .search--form .$id .field--checkbox input:checked::after, {{WRAPPER}} .search--form .$id .field--checkbox input, {{WRAPPER}} .search--form .search-meta.$id .field--with-icon, {{WRAPPER}} .search--form .w-full .relative.$id" );

		if ( 'keyword' === $id || ( is_array( $options ) && 'checkbox' !== $options['type'] ) ) {
			$this->add_control(
				"field_padding_$id",
				[
					'label'       => __( 'Padding', 'lisfinity-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'label_block' => true,
					'size_units'  => [ '%', 'px', 'em' ],
					'default'     => [
						'top'      => 10,
						'right'    => 24,
						'bottom'   => 10,
						'left'     => 24,
						'isLinked' => false,
					],
					'selectors'   => [
						"{{WRAPPER}} .search--form .$id.home-search-keyword div.flex-center.bg-white" => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						"{{WRAPPER}} .search--form .w-full .relative .flex-center"                    => 'padding: 0px;',
						"{{WRAPPER}} .search--form .w-full .relative.$id"                             => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						"{{WRAPPER}} .search--form .search-meta.$id .field--with-icon"                => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						"{{WRAPPER}} .search--form .$id .field--checkbox input"                       => 'padding: 8px;',
					],
					'condition'   => [
						"display_style_$id" => 'yes'
					]
				]
			);
		}


		$this->add_control(
			"field_margin_$id",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 10,
					'left'     => 0,
					'isLinked' => false,
				],
				'selectors'   => [
					"{{WRAPPER}} .search--form .$id.home-search-keyword div.flex-center.bg-white" => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .w-full .relative.$id"                             => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .search-meta.$id .field--with-icon"                => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .$id .field--checkbox input"                       => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [
					"display_style_$id" => 'yes'
				]
			]
		);

		if ( is_array( $options ) && 'checkbox' === $options['type'] ) {
			$this->set_heading_section( "heading_layout_$id", esc_html__( 'Set layout' ), "hr_layout_$id" );

			$this->add_responsive_control(
				"checkbox_in_column_$id",
				[
					'label'       => __( 'Break Checkboxes Into Columns', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::NUMBER,
					'default'     => 6,
					'min'         => 1,
					'max'         => 999,
					'description' => __( 'Choose the number of columns you wish to break checkboxes', 'lisfinity-core' ),
					'selectors'   => [
						"{{WRAPPER}} .$id .field--checkbox" => 'width: calc(100% / {{VALUE}});',
					]
				]
			);

			$this->add_responsive_control(
				"checkbox-columns-gap_$id",
				[
					'label'       => __( 'Checkboxes Columns Gap', 'lisfinity-core' ),
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
						'size' => 0,
					],
					'selectors'   => [
						"{{WRAPPER}} .$id .field--checkbox" => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->set_elements_alignment( 'checkbox_alignment', esc_html__( 'Alignment', 'lisfinity-core' ), 'flex-start', '.search--form .hero-checkbox-container' );

			$this->add_control(
				"heading_label_$id",
				[
					'label'     => __( 'Label', 'lisfinity-core' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);
			$this->add_control(
				"hr_label_$id",
				[
					'type'      => \Elementor\Controls_Manager::DIVIDER,
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Banner_Label_Typography::get_type(),
				[
					'name'      => "banner_label_$id",
					'selector'  => "{{WRAPPER}} .$id-label",
					'condition' => [
						"display_style_$id" => 'yes'
					],
				]
			);

			$this->add_control(
				"label_color_$id",
				[
					'label'     => __( 'Text Color', 'elementor-pro' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(0, 0, 0, 1)',
					'selectors' => [
						"{{WRAPPER}} .search--form label.$id-label" => 'color: {{VALUE}};',
					],
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_responsive_control(
				"label_padding_$id",
				[
					'label'       => __( 'Padding', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						"{{WRAPPER}} .search--form label.$id-label" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'     => [
						'top'      => 0,
						'right'    => 0,
						'bottom'   => 0,
						'left'     => 12,
						'isLinked' => false,
					],
					'condition'   => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_responsive_control(
				"label_margin_$id",
				[
					'label'       => __( 'Margin', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						"{{WRAPPER}} .search--form label.$id-label" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'     => [
						'top'      => 0,
						'right'    => 0,
						'bottom'   => 0,
						'left'     => 0,
						'isLinked' => false,
					],
					'condition'   => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_control(
				"position_label_heading_$id",
				[
					'label'     => __( 'Label Position', 'lisfinity-core' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_control(
				"position_label_hr_$id",
				[
					'type'      => \Elementor\Controls_Manager::DIVIDER,
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_control(
				"x_position_$id",

				[
					'label_block' => true,
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'min' => - 350,
							'max' => 350,
						],
					],
					'default'     => [
						'unit' => 'px',
						'size' => 0,
					],
					'description' => __( 'Horizontal', 'lisfinity-core' ),
					'selectors'   => [
						"{{WRAPPER}} .search--form .$id .$id-label" => 'right: {{SIZE}}{{UNIT}}; position: relative;',
					],
					'condition'   => [
						"display_style_$id" => 'yes'
					]
				]
			);

			$this->add_control(
				"y_position_$id",

				[
					'label_block' => true,
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'min' => - 350,
							'max' => 350,
						],
					],
					'default'     => [
						'unit' => 'px',
						'size' => 0,
					],
					'description' => __( 'Vertical', 'lisfinity-core' ),
					'selectors'   => [
						"{{WRAPPER}} .search--form .$id .$id-label" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
					],
					'condition'   => [
						"display_style_$id" => 'yes'
					]
				]
			);

		}


		if ( 'price' === $id ) {

			$this->add_control(
				'price_label_settings_heading',
				[
					'label'     => __( 'Label', 'lisfinity-core' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'display_style_price' => 'yes'
					]
				]
			);
			$this->add_control(
				'price_label_settings_hr',
				[
					'type'      => \Elementor\Controls_Manager::DIVIDER,
					'condition' => [
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				'remove_label_price',
				[
					'label'        => __( 'Remove label', 'lisfinity-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'lisfinity-core' ),
					'label_off'    => __( 'Hide', 'lisfinity-core' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => [
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"pull_label_price",
				[
					'label'        => __( 'Pull label out of the container', 'lisfinity-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'lisfinity-core' ),
					'label_off'    => __( 'Hide', 'lisfinity-core' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => [
						"remove_label_price"  => '',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"position_label_heading_price",
				[
					'label'     => __( 'Position', 'lisfinity-core' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						"pull_label_price"    => 'yes',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"position_label_hr_price",
				[
					'type'      => \Elementor\Controls_Manager::DIVIDER,
					'condition' => [
						"pull_label_price"    => 'yes',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				'x_position_price',

				[
					'label_block' => true,
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'min' => - 350,
							'max' => 350,
						],
					],
					'default'     => [
						'unit' => 'px',
						'size' => 40,
					],
					'description' => __( 'Horizontal', 'lisfinity-core' ),
					'selectors'   => [
						"{{WRAPPER}} .search--form .field--with-icon__label" => 'left: {{SIZE}}{{UNIT}}; position: relative;',
					],
					'condition'   => [
						'pull_label_price'    => 'yes',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				'y_position_price',

				[
					'label_block' => true,
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'min' => - 350,
							'max' => 350,
						],
					],
					'default'     => [
						'unit' => 'px',
						'size' => - 20,
					],
					'description' => __( 'Vertical', 'lisfinity-core' ),
					'selectors'   => [
						"{{WRAPPER}} .search--form .field--with-icon .field--with-icon__label"    => 'top: {{SIZE}}{{UNIT}}; position: relative;',
						"{{WRAPPER}} .search--form .field--input__range .field--with-icon__label" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
					],
					'condition'   => [
						'pull_label_price'    => 'yes',
						'display_style_price' => 'yes'
					]
				]
			);


			$this->add_group_control(
				Group_Control_Banner_Label_Typography::get_type(),
				[
					'name'      => "banner_label_price",
					'selector'  => "{{WRAPPER}} .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label, {{WRAPPER}} .search--form .field--with-icon__label",
					'condition' => [
						"remove_label_price"  => '',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"label_color_price_test",
				[
					'label'     => __( 'Text Color', 'elementor-pro' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(0, 0, 0, 1)',
					'selectors' => [
						'{{WRAPPER}} .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label' => 'color: {{VALUE}};',
						'{{WRAPPER}} .search--form .field--with-icon__label'                                                     => 'color: {{VALUE}};',
					],
					'condition' => [
						"remove_label_price"  => '',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"label_padding_price",
				[
					'label'       => __( 'Padding', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						'{{WRAPPER}} .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .search--form .search-meta .field--with-icon__label'                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .search--form .search-meta .field--input__range span.field--with-icon_label'                => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'     => [
						'top'      => 0,
						'right'    => 0,
						'bottom'   => 0,
						'left'     => 20,
						'isLinked' => true,
					],
					'condition'   => [
						"remove_label_price"  => '',
						'display_style_price' => 'yes'
					]
				]
			);

			$this->add_control(
				"label_margin_price",
				[
					'label'       => __( 'Margin', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						'{{WRAPPER}} .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .search--form .search-meta .field--with-icon__label'                                        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .search--form .search-meta .field--input__range span.field--with-icon_label'                => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'     => [
						'top'      => 0,
						'right'    => 10,
						'bottom'   => 0,
						'left'     => 0,
						'isLinked' => true,
					],
					'condition'   => [
						"remove_label_price"  => '',
						'display_style_price' => 'yes'
					]
				]
			);
		}


		if ( 'keyword' === $id || 'category-type' === $id || ( is_array( $options ) && 'checkbox' !== $options['type'] && 'price' !== $id ) ) {

			$this->add_control(
				"hr_$id",
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,
				]
			);

			$this->start_controls_tabs(
				"tabs_$id",
				[
					'label' => __( "$id", 'lisfinity-core' ),
				]
			);
			if ( $options && 'input' !== $options['type'] ) {
				$this->start_controls_tab(
					"tab_icon_$id",
					[
						'label' => __( 'Icon', 'lisfinity-core' ),
					]
				);

				$this->add_control(
					"hr_dropdown_icon_$id",
					[
						'type' => \Elementor\Controls_Manager::DIVIDER,
					]
				);

				$this->icon_style( $id );

				$this->end_controls_tab();
			}


			$this->start_controls_tab(
				"tab_dropdown_$id",
				[
					'label'     => __( 'Dropdown', 'lisfinity-core' ),
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);
			$this->add_control(
				"hr_dropdown_$id",
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,
				]
			);

			$this->dropdown_style( $id );

			$this->end_controls_tab();


			$this->start_controls_tab(
				"tab_label_$id",
				[
					'label'     => __( 'Label', 'lisfinity-core' ),
					'condition' => [
						"display_style_$id" => 'yes'
					]

				]
			);

			$this->add_control(
				"hr_dropdown_label_$id",
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,

				]
			);
			$this->label_style( $id );

			$this->end_controls_tab();

			$this->end_controls_tabs();
		}


	}

	/**
	 * Form Fields dropdown global style
	 * ------------------------------
	 */

	public function dropdown_style_global($key) {

		$this->add_control(
			"dropdown_bg_color_$key",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .results"         => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .css-kj6f9i-menu" => 'background-color:{{VALUE}}; z-index: 22;'
				],
			]
		);

		$this->add_control(
			"dropdown_border_radius_$key",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .results"         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				]
			]
		);

		$this->set_heading_section( "heading_dropdown_item_$key", esc_html__( 'Dropdown Item', 'lisfinity-core' ), "hr_dropdown_item_$key" );

		$this->add_control(
			"dropdown_color_$key",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					"{{WRAPPER}} .results .result"                                 => 'color: {{VALUE}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'color:{{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Label_Typography::get_type(),
			[
				'name'     => "banner_dropdown_result_font_size_$key",
				'selector' => '{{WRAPPER}} .results .result, {{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option'
				,
			]
		);

		$this->add_control(
			"dropdown_bg_color_item_$key",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .results .result .flex"                           => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .results .result .block"                          => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'background-color:{{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_dropdown_result_border_$key",
				'selector' => '{{WRAPPER}} .results .result .flex, {{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option'
				,
			]
		);

		$this->add_control(
			"dropdown_border_radius_item_$key",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .results .result .flex"                           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .results .result .block"                          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				]
			]
		);

		$this->add_control(
			"dropdown_result_padding_$key",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}}  .results .result .flex"                          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}}  .results .result .block"                         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 6,
					'right'    => 16,
					'bottom'   => 6,
					'left'     => 16,
					'isLinked' => false,
				]
			]
		);

		$this->add_control(
			"dropdown_result_margin_$key",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'options'     => [
					'top'      => 6,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'selectors'   => [
					"{{WRAPPER}}  .results .result .flex"                          => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}}  .results .result .block"                         => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->set_heading_section( "heading_dropdown_result_hover_$key", esc_html__( 'Dropdown Item on Hover', 'lisfinity-core' ), "hr_dropdown_result_hover_$key" );

		$this->add_control(
			"dropdown_color_hover_$key",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					"{{WRAPPER}} .results .result:hover"                           => 'color: {{VALUE}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-11unzgr .css-dpec0i-option" => 'color:{{VALUE}};'
				]
			]
		);

		$this->add_control(
			"dropdown_bg_color_item_hover_$key",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 239, 239, 1)',
				'selectors' => [
					"{{WRAPPER}} .results .result .flex:hover"        => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .results .result .block:hover"       => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-dpec0i-option" => 'background-color:{{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_dropdown_result_border_hover_$key",
				'selector' => '{{WRAPPER}} .results .result:hover, {{WRAPPER}} .css-kj6f9i-menu .css-dpec0i-option'
				,
			]
		);

		$this->add_control(
			"dropdown_result_border_radius_on_hover_$key",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}}  .results .result .flex:hover"       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}}  .results .result .block:hover"      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-dpec0i-option" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				]
			]
		);

		$this->add_control(
			"dropdown_result_padding_hover_$key",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}}  .results .result .flex:hover"       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}}  .results .result .block:hover"      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .css-kj6f9i-menu .css-dpec0i-option" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 6,
					'right'    => 16,
					'bottom'   => 6,
					'left'     => 16,
					'isLinked' => false,
				]
			]
		);
	}

	/**
	 * Form Fields dropdown individual style
	 * ------------------------------
	 */

	public function dropdown_style( $id ) {
		$taxonomy_admin = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$options        = $taxonomy_admin->get_taxonomy_options( $id );
		if ( $options && 'input' === $options['type'] ) {
			$this->add_control(
				"dropdown_bg_color_$id",
				[
					'label'     => __( 'Background Color', 'lisfinity-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(255, 255, 255, 1)',
					'selectors' => [
						"{{WRAPPER}} .$id .results"         => 'background-color:{{VALUE}};',
						"{{WRAPPER}} .$id .css-kj6f9i-menu" => 'background-color:{{VALUE}};',
					],
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);
		} else {
			$this->add_control(
				"dropdown_bg_color_$id",
				[
					'label'     => __( 'Background Color', 'lisfinity-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(255, 255, 255, 1)',
					'selectors' => [
						"{{WRAPPER}} .$id"                  => 'background-color:{{VALUE}};',
						"{{WRAPPER}} .$id .results"         => 'background-color:{{VALUE}};',
						"{{WRAPPER}} .$id .css-kj6f9i-menu" => 'background-color:{{VALUE}};',
					],
					'condition' => [
						"display_style_$id" => 'yes'
					]
				]
			);
		}

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_dropdown_border_$id",
				'selector' => "{{WRAPPER}} .$id .results, {{WRAPPER}} .$id .css-kj6f9i-menu"
				,
			]
		);

		$this->add_control(
			"dropdown_border_radius_$id",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .$id .results"         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				],
				'condition'   => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"heading_dropdown_item_$id",
			[
				'label'     => __( 'Dropdown Item', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);
		$this->add_control(
			"hr_dropdown_item_$id",
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"dropdown_color_$id",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id .results .result" => 'color: {{VALUE}};',
				],
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Label_Typography::get_type(),
			[
				'name'      => "banner_dropdown_result_font_size_$id",
				'selector'  => "{{WRAPPER}} .$id .results .result",
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"dropdown_bg_color_item_$id",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id .results .result .flex"                           => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .$id .results .result .block"                          => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'background-color:{{VALUE}};'
				],
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_dropdown_result_border_$id",
				'selector' => "{{WRAPPER}} .$id .results .result .block, {{WRAPPER}} .$id .results .result .flex, {{WRAPPER}} .$id .css-kj6f9i-menu .css-11unzgr .css-fk865s-option"
				,
			]
		);

		$this->add_control(
			"dropdown_border_radius_item_$id",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .$id .results .result .flex"                           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .$id .results .result .block"                          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				],
				'condition'   => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"dropdown_result_padding_$id",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .$id .results .result .flex"                           => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .$id .results .result .block"                          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu .css-11unzgr .css-fk865s-option" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 6,
					'right'    => 16,
					'bottom'   => 6,
					'left'     => 16,
					'isLinked' => false,
				],
				'condition'   => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"heading_dropdown_result_hover_$id",
			[
				'label'     => __( 'Dropdown Item on Hover', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);
		$this->add_control(
			"hr_dropdown_result_hover_$id",
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"dropdown_color_hover_$id",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id .results .result:hover"                    => 'color: {{VALUE}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu:hover .css-dpec0i-option" => 'color:{{VALUE}};'

				],
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"dropdown_bg_color_item_hover_$id",
			[
				'label'     => __( 'Background Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 239, 239, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id .results .result .flex:hover"              => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .$id .results .result .block:hover"             => 'background-color:{{VALUE}};',
					"{{WRAPPER}} .$id .css-kj6f9i-menu:hover .css-dpec0i-option" => 'background-color:{{VALUE}};'
				],
				'condition' => [
					"display_style_$id" => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Search_Field_Border::get_type(),
			[
				'name'     => "banner_dropdown_result_border_hover_$id",
				'selector' => "{{WRAPPER}} .$id .results .result .block:hover, {{WRAPPER}} .$id .results .result .flex:hover, {{WRAPPER}} .$id .css-kj6f9i-menu .css-dpec0i-option"
				,
			]
		);
	}

	/**
	 * Form Fields label individual style
	 * ------------------------------
	 */

	public function label_style( $id ) {
		$taxonomy_admin = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$options        = $taxonomy_admin->get_taxonomy_options( $id );

		$this->display_element( "remove_label_$id", esc_html__( 'Remove label', 'lisfinity-core' ) );


		$this->add_control(
			"pull_label_$id",
			[
				'label'        => __( 'Pull label out of the container', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					"remove_label_$id" => ''
				]
			]
		);


		$this->add_control(
			"position_label_heading_$id",
			[
				'label'     => __( 'Position', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					"pull_label_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"position_label_hr_$id",
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					"pull_label_$id" => 'yes'
				]
			]
		);

		$this->set_element_position( "x_position_$id", '0', "y_position_$id", '0', "label.$id-label.label", "pull_label_$id" );


		$this->add_group_control(
			Group_Control_Banner_Label_Typography::get_type(),
			[
				'name'      => "banner_label_$id",
				'selector'  => "{{WRAPPER}} .$id-label",
				'condition' => [
					"remove_label_$id" => ''
				]
			]
		);

		$this->add_control(
			"label_color_$id",
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id-label.label" => 'color: {{VALUE}}',
				],
				'condition' => [
					"remove_label_$id" => ''
				]
			]
		);

		$this->add_responsive_control(
			"label_padding_$id",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .$id-label.label" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					"remove_label_$id" => ''
				]
			]
		);

		$this->add_responsive_control(
			"label_margin_$id",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} label.$id-label" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 10,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					"remove_label_$id" => ''
				]
			]
		);
	}

	/**
	 * Form Fields label global style
	 * ------------------------------
	 */

	public function label_style_global( $id, $key ) {

		$taxonomy_admin = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
		$options        = $taxonomy_admin->get_taxonomy_options( $id );


		$this->add_group_control(
			Group_Control_Banner_Label_Typography::get_type(),
			[
				'name'     => "banner_label_$key",
				'selector' => "{{WRAPPER}} .label, {{WRAPPER}} .field--with-icon__label",
			]
		);

		if ( 'keyword' === $id || ( is_array( $options ) && 'checkbox' === $options['type'] ) ) {
			$this->add_control(
				"label_padding_checkbox_global_1_$key",
				[
					'label'       => __( 'Padding', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						"{{WRAPPER}}  .search--form .field--checkbox label.label" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'     => [
						'top'      => 0,
						'right'    => 0,
						'bottom'   => 0,
						'left'     => 12,
						'isLinked' => true,
					]
				]
			);
		}
		if ( 'keyword' === $id || ( is_array( $options ) && 'checkbox' !== $options['type'] ) ) {
			$this->add_control(
				"label_padding_checkbox_global_$key",
				[
					'label'       => __( 'Padding', 'lisfinity-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => [ 'px', 'em', '%' ],
					'selectors'   => [
						"{{WRAPPER}}  .search--form label.label"                                                                  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						"{{WRAPPER}}  .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'default'     => [
						'top'      => 0,
						'right'    => 0,
						'bottom'   => 0,
						'left'     => 0,
						'isLinked' => true,
					]
				]
			);
		}

		$this->add_control(
			"label_margin_$key",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} .search--form label.label"                                                                  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					"{{WRAPPER}} .search--form .search-meta .field--input__range .field--with-icon .field--with-icon__label" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 10,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				]
			]
		);
	}

	/**
	 * Form Fields icon style
	 * ------------------------------
	 */

	public function icon_style( $id ) {
		$this->add_control(
			"place_icon_$id",
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
			"selected_icon_$id",
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					"place_icon_$id" => 'yes'
				]
			]
		);

		$this->add_control(
			"selected_icon_color_$id",
			[
				'label'     => __( 'Icon Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(104, 104, 104, 1)',
				'selectors' => [
					"{{WRAPPER}} .$id #$id-icon svg" => 'fill: {{VALUE}};',
					"{{WRAPPER}} .$id #$id-icon"     => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			"selected_icon_size_$id",
			[
				'label'      => __( 'Icon Size', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'default'    => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors'  => [
					"{{WRAPPER}} .$id #$id-icon"     => 'font-size: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} .$id #$id-icon svg" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			"icon_indent_$id",
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
					],
				],
				'selectors' => [
					"{{WRAPPER}} .$id #$id-icon" => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			"remove_icon_$id",
			[
				'label'        => __( 'Remove icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',


			]
		);

	}

	/**
	 * Form Fields dropdown individual style
	 * ------------------------------
	 */

	public function form_button() {

		$this->start_controls_tabs(
			"button_tabs_global",
			[
				'label' => __( "Tabs", 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"tab_button_default",
			[
				'label' => __( 'General', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			"button_hr_standard",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Button Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Search', 'elementor' ),
				'placeholder' => __( 'Type..', 'elementor' ),
			]
		);
		$this->add_control(
			"remove_text_button",
			[
				'label'        => __( 'Remove Text', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',


			]
		);

		$this->button_style( '.search--form button', 'default' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			"tab_button_icon",
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			"button_icon_hr_",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			"place_icon_button",
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
			"selected_icon_button",
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					"place_icon_button" => 'yes'
				]
			]
		);

		$this->add_control(
			"selected_icon_color_button",
			[
				'label'     => __( 'Icon Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} #button-icon" => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			"selected_icon_size_button",
			[
				'label'      => __( 'Icon Size', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'default'    => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors'  => [
					"{{WRAPPER}} #button-icon" => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}!important;',
				],

			]
		);

		$this->add_responsive_control(
			"icon_indent_button",
			[
				'label'     => __( 'Horizontal position', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 999,
						'min' => - 999
					],
				],
				'default'   => [
					'size' => 0,
					'unit' => 'px'
				],
				'selectors' => [
					"{{WRAPPER}} #button-icon" => 'left: {{SIZE}}{{UNIT}};position: relative;',
				],
				'condition' => [
					"place_icon_button" => 'yes'
				]
			]
		);

		$this->add_control(
			"remove_icon_button",
			[
				'label'        => __( 'Remove icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',


			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			"tab_button_position",
			[
				'label' => __( 'Position', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'form_button_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 990,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--form button' => 'right: {{SIZE}}{{UNIT}}!important; position: relative;',

				]
			]
		);

		$this->add_control(
			'form_button_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 990,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search--form button' => 'top: {{SIZE}}{{UNIT}}!important; position: relative;',
				]
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			"tab_button_alignment",
			[
				'label' => __( 'Alignment', 'lisfinity-core' ),
			]
		);

		$this->set_elements_alignment( 'form_button_alignment', esc_html__( 'Align the button', 'lisfinity-core' ), 'center', '.search--form .hero-button-container' );

		$this->end_controls_tab();


		$this->end_controls_tabs();

		$this->add_control(
			"button_tabs_separator",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs(
			"button_tabs",
			[
				'label' => __( "Tabs", 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"tab_button_hover",
			[
				'label' => __( 'Button on Hover', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			"button_hr_hover",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->button_style( '.search--form button:hover', 'hover' );


		$this->end_controls_tab();

		$this->start_controls_tab(
			"tab_button_icon_hover",
			[
				'label' => __( 'Icon on Hover', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			"button_icon_hr_hover",
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			"selected_icon_color_button_hover",
			[
				'label'     => __( 'Icon Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					"{{WRAPPER}} .search--form button:hover #button-icon" => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			"selected_icon_size_button_hover",
			[
				'label'      => __( 'Icon Size', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'default'    => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors'  => [
					"{{WRAPPER}} .search--form button:hover #button-icon" => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}!important;',
				],

			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


	}

	public function button_style( $id, $style ) {
		$this->add_group_control(
			Group_Control_Banner_Button_Typography::get_type(),
			[
				'name'     => "banner_button_typography_$id",
				'selector' => "{{WRAPPER}} $id",
			]
		);

		$this->set_text_color( "form_button_color_$style", esc_html__( 'Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', $id );

		$this->set_background_color( "form_button_bg_color_$style", 'rgba(33, 134, 235, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), $id );

		$this->add_responsive_control(
			"form_button_border_radius_$style",
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'isLinked' => false,
				],
				'selectors'   => [
					"{{WRAPPER}} $id" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => "button_box_shadow_$style",
				'selector' => "{{WRAPPER}} $id",
			]
		);

		$this->add_responsive_control(
			"form_button_padding_$style",
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'top'      => 0,
					'right'    => 30,
					'bottom'   => 0,
					'left'     => 30,
					'isLinked' => false,
				],
				'selectors'   => [
					"{{WRAPPER}} $id" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			"form_button_margin_$style",
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'selectors'   => [
					"{{WRAPPER}} $id" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * * functions
	 * -------------------------
	 */
	public function display_element( $id, $message ) {
		$this->add_control(
			$id,
			[
				'label'        => __( $message, 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);
	}

	public function set_background_color( $id, $default_color, $message, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};'
				],
			]
		);
	}

	public function sort_elements( $id, $order_number, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( 'Sorting elements', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 50,
				'step'      => 1,
				'default'   => $order_number,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
			]
		);
	}

	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			$hr_id,
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
	}

	public function set_border_radius( $id, $top, $right, $bottom, $left, $default_unit, $selector, $label = 'Border Radius' ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( $label, 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'unit'   => $default_unit,
					'top'    => $top,
					'right'  => $right,
					'bottom' => $bottom,
					'left'   => $left
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
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

	public function set_icon_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $message, 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'fill:{{VALUE}};',
				]
			]
		);
	}

	public function set_icon_size( $id, $default, $selector ) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_elements_alignment( $id, $label, $default, $selector ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( $label, 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => $default,
				'toggle'      => true,
				'description' => __( 'Set alignment', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector, $condition ) {
		$this->add_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					$condition => 'yes'
				]
			]
		);

		$this->add_control(
			$id_y,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'condition'   => [
					$condition => 'yes'
				]
			]
		);
	}

	public function set_padding( $id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean ) {

		$this->add_control(
			$id,
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_margin( $id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean ) {

		$this->add_control(
			$id,
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}


	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['order'] = array_column( $settings['lisfinity_hero_form_fields'], 'custom_id' );

		$args = [
			'settings' => $settings,
		];


		include lisfinity_get_template_part( 'lisfinity-hero-search-form', 'shortcodes/banner', $args );
	}

}
