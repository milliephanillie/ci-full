<?php


namespace Lisfinity\Shortcodes\Authors;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Models\Users\ProfilesModel;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Author_Search_Widget extends Shortcode {
	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'author-search';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Lisfinity Author Search', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-search';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-authors-page' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'letters_style',
			[
				'label' => __( 'Letters', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->letters_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'input_style',
			[
				'label' => __( 'Input Field', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->input_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'select_style',
			[
				'label' => __( 'Select Field', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->select_style();
		$this->end_controls_section();


	}

	public function letters_style() {
		$this->start_controls_tabs(
			'letters_tabs'
		);
		$this->start_controls_tab(
			'letters_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'letters_typography',
				'selector'       => '{{WRAPPER}} .letters-button',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'letters_default_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.letters-button' );
		$this->set_padding( 'letters_default_padding', '.letters-button', '0', '10', '0', '10', 'false' );
		$this->set_margin( 'letters_default_margin', '.letters-button', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'letters_default_border_radius', '3', '3', '3', '3', 'px', '.letters-button' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'letters_hover_tab',
			[
				'label' => __( 'On hover', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'letters_hover_typography',
				'selector'       => '{{WRAPPER}} .letters-button:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'letters_hover_default_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.letters-button:hover' );
		$this->set_padding( 'letters_hover_default_padding', '.letters-button:hover', '0', '10', '0', '10', 'false' );
		$this->set_margin( 'letters_hover_default_margin', '.letters-button:hover', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'letters_hover_default_border_radius', '3', '3', '3', '3', 'px', '.letters-button:hover' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'letters_active_tab',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'letters_active_typography',
				'selector'       => '{{WRAPPER}} .letters-button.bg-blue-200',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'letters_active_default_bg_color', '#e6f6ff', esc_html__( 'Background Color', 'lisfinity-core' ), '.letters-button.bg-blue-200' );
		$this->set_padding( 'letters_active_default_padding', '.letters-button.bg-blue-200', '0', '10', '0', '10', 'false' );
		$this->set_margin( 'letters_active_default_margin', '.letters-button.bg-blue-200', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'letters_active_default_border_radius', '3', '3', '3', '3', 'px', '.letters-button.bg-blue-200' );

		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function input_style() {
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'input_field_typography',
				'selector'       => '{{WRAPPER}} .products--find input',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'input_field_default_bg_color', '#f6f6f6', esc_html__( 'Background Color', 'lisfinity-core' ), '.products--find' );
		$this->set_padding( 'input_field_default_padding', '.products--find', '20', '20', '20', '20', 'false' );
		$this->set_margin( 'input_field_default_margin', '.products--find', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'input_field_default_border_radius', '3', '3', '3', '3', 'px', '.products--find' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'input_field_border',
				'selector'       => '{{WRAPPER}} .products--find',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => false,
						],
					],
					'color'  => [
						'default' => '#d7d7d7',
					],
				],
			]
		);
	}

	public function select_style() {
		$this->set_background_color( 'select_author_search_bg_color', '#fffff', 'Background Color', '.search--action div[class*=css-0]' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "select_author_search_border",
				'selector'       => "{{WRAPPER}} .search--action div[class*=css-0]",
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0'
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

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "search_search--action_select",
				'selector'       => "{{WRAPPER}} .search--action div[class*=css-0]",
				'separator'      => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(51, 51, 51, 1)'
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

		// dropdown typography.
		$this->add_control(
			'select_author_search_dropdown',
			[
				'label'     => __( 'Dropdown Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'select_author_search_dropdown_bg_color', '#ffffff', 'Background Color', '.search--action div[class*=-menu], {{WRAPPER}} .search--action div[class*=-fk865s]', true );

		$this->set_background_color( 'select_author_search_dropdown_bg_color_hover', '#f6f6f6', 'Background Color on Hover', '.search--action .css-dpec0i-option', true );

		$this->set_background_color( 'select_author_search_dropdown_bg_color_active', '#f6f6f6', 'Active Background Color', '.search--action .css-xo7z33-option', true );
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "select_author_search_dropdown",
				'selector'       => "{{WRAPPER}} .search--action div[class*=-option]",
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
						'default' => 400
					],
				],
			]
		);

		$this->start_controls_tabs(
			'label_tabs'
		);
		$this->start_controls_tab(
			'label_tab',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "select_author_label",
				'selector'       => "{{WRAPPER}} .search--action label",
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
				],
			]
		);
		$this->set_padding( 'select_author_label_padding', '.search--action label', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'select_author_label_margin', '.search--action label', '0', '4', '0', '0', 'false' );
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_tab',
			[
				'label' => __( 'Icon', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'use_custom_icon',
			[
				'label'   => __( 'Different Home Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Home Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom home icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
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
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .select-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .select-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#959595',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .select-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .select-icon i'   => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
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

		include lisfinity_get_template_part( 'author-search', 'shortcodes/authors', $args );
	}

}
