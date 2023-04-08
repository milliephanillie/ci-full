<?php


namespace Lisfinity\Shortcodes\BusinessProfileSingle;


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Custom_Fields_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Label_On_Sale_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Promoted_Icon_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Title_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Ratings_Text_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;

class Business_Store_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'business-store';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Business Store', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
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
		return [ 'lisfinity-business-profile' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'business_breadcrumb',
			[
				'label' => __( 'Breadcrumbs style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'breadcrumbs_tabs' );

		// normal values;
		$this->start_controls_tab( 'breadcrumbs_wrapper_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);

		$this->breadcrumbs_style();
		$this->end_controls_tab();
		// normal values;
		$this->start_controls_tab( 'breadcrumbs_filter_tab',
			[
				'label' => __( 'Filter Dropdown', 'lisfinity-core' ),
			]
		);
		$this->breadcrumbs_filter();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'business_sidebar',
			[
				'label' => __( 'Sidebar style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->sidebar_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'business_button',
			[
				'label' => __( 'Button style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->button_search();
		$this->end_controls_section();
		$this->button_detailed();

		$this->start_controls_section(
			'business_chosen_filters',
			[
				'label' => __( 'Chosen Filters', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->chosen_filters();

		$this->end_controls_section();

		$this->listing_style();

		$this->listings_layout();

		// product box settings.
		$this->start_controls_section(
			'box_settings',
			[
				'label'     => __( 'Box Settings', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->box_styling();

		$this->end_controls_section();

		$this->start_controls_section(
			'products_style_image',
			[
				'label'     => __( 'Image Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->product_image_settings();

		$this->end_controls_section();

		$this->start_controls_section(
			'verified',
			[
				'label'     => __( 'Verified Author Badge', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->verified_author();
		$this->end_controls_section();

		$this->start_controls_section(
			'products_style_bookmarks',
			[
				'label'     => __( 'Bookmarks Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_action_bookmark_style();
		$this->end_controls_section();
		// product promoted icon settings.

		$this->start_controls_section(
			'promoted_icon_style',
			[
				'label'     => __( 'Promoted Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_promoted_icon_style();

		$this->end_controls_section();

		// product price settings.

		$this->start_controls_section(
			'product_price',
			[
				'label'     => __( 'Listing Price', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);
		$this->start_controls_tabs(
			'product_price_tabs'
		);

		// product price default tab.

		$this->start_controls_tab(
			'product_price_default',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);


		$this->products_fixed_price_style();
		$this->end_controls_tab();


		// product price auction tab.

		$this->start_controls_tab(
			'product_price_auction',
			[
				'label' => __( 'Auction', 'lisfinity-core' ),
			]
		);

		$this->products_auction_price_style();
		$this->end_controls_tab();

		// product price price on call tab.

		$this->start_controls_tab(
			'product_price_price_on_call',
			[
				'label' => __( 'On Call', 'lisfinity-core' ),
			]
		);

		$this->products_price_on_call_style();
		$this->end_controls_tab();

		// product price free tab.

		$this->start_controls_tab(
			'product_price_free',
			[
				'label' => __( 'Free', 'lisfinity-core' ),
			]
		);

		$this->products_price_free_style();
		$this->end_controls_tab();

		// product price free tab.

		$this->start_controls_tab(
			'product_price_on_sale',
			[
				'label' => __( 'On Sale', 'lisfinity-core' ),
			]
		);

		$this->products_price_on_sale_style();

		$this->products_price_label_on_sale_style();

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->end_controls_section();

		// product title settings.

		$this->start_controls_section(
			'products_title_style',
			[
				'label'     => __( 'Listing Title Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_title_style();

		$this->end_controls_section();

		// product info settings.

		$this->start_controls_section(
			'product_info_style',
			[
				'label'     => __( 'Listing Info Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);
		$this->start_controls_tabs(
			'product_info_tabs'
		);
		$this->start_controls_tab(
			'product_info_ratings',
			[
				'label'     => __( 'Ratings', 'lisfinity-core' ),
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_info_ratings_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'product_info_location',
			[
				'label'     => __( 'Location', 'lisfinity-core' ),
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_info_location_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// product logo settings.

		$this->start_controls_section(
			'products_logo',
			[
				'label'     => __( 'Listing Logo', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_logo_style();

		$this->end_controls_section();

		// product box settings.
		$this->start_controls_section(
			'taxonomies',
			[
				'label' => __( 'Taxonomies', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'taxonomies_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'Taxonomies that will be displayed in the box can be set from %s', 'lisfinity-core' ), '<strong>Lisfinity Options -> Listings Setup -> Listing Taxonomies</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);


		$this->products_custom_fields_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'business_pagination',
			[
				'label' => __( 'Pagination', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->business_pagination();

		$this->end_controls_section();
	}

	public function verified_author() {

		$this->set_background_color( 'products_verified_author_bg_color', 'rgba(101, 214, 173, 1)', 'Background color', '.author-verified-wrapper' );

		$this->add_responsive_control(
			'author_badge_wrapper_size',

			[
				'label'       => __( 'Wrapper Width', 'lisfinity-core' ),
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
					'size' => 32,
				],
				'selectors'   => [
					"{{WRAPPER}} .author-verified-container" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_badge_wrapper_height',

			[
				'label'       => __( 'Wrapper Height', 'lisfinity-core' ),
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
					'size' => 32,
				],
				'selectors'   => [
					"{{WRAPPER}} .author-verified-container, {{WRAPPER}} .author-verified-wrapper" => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->set_border_radius( 'products_verified_author_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.author-verified-wrapper' );

		$this->add_control(
			'place_icon_author_badge',
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
			'icon_author_badge',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_author_badge' => 'yes',
				]
			]
		);
		$this->set_icon_color( 'products_verified_author_icon_color', 'Icon Color', 'rgba(255, 255, 255, 1)', 'i.author-verified-icon, {{WRAPPER}} .author-verified-icon svg' );

		$this->set_icon_size( 'products_verified_author_icon_size', '14', 'i.author-verified-icon, {{WRAPPER}} .author-verified-icon svg' );

		$this->set_heading_section( 'products_author_badge_position_heading', 'Verified Badge Position', 'products_author_badge_positioning_hr' );

		$this->add_responsive_control(
			'products_author_badge_position_x',

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
					'size' => 20,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .author-verified-container' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'products_author_badge_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 20,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .author-verified-container' => 'top: {{SIZE}}{{UNIT}};',
				]
			]
		);

	}

	/**
	 * Breadcrumbs
	 * ------------------------------
	 */
	public function breadcrumbs_style() {
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => 'breadcrumb_detail',
				'selector'       => '{{WRAPPER}} .search--breadcrumb .text-grey-1100, {{WRAPPER}} .search--breadcrumb .text-grey-700',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[
								'size' => 14,
								'unit' => 'px',
							],
					],
					'font_color'  => [
						'default' => '#262626',
					],
					'font_weight' => [
						'default' => '400',
					],
				]
			]
		);

		$this->set_margin( 'business_store_wrapper_margin', '.search--breadcrumb', '0', '0', '16', '0', true );

		$this->set_padding( 'business_store_wrapper_padding', '.search--breadcrumb', '4', '20', '4', '20', true );

		$this->set_background_color( 'business_store_wrapper_bg_color', 'rgba(255, 255, 255, 1)', 'Background Color', '.search--breadcrumb' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => 'business_store_wrapper_border',
				'selector' => '{{WRAPPER}} .search--breadcrumb',
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'           => 'business_store_wrapper_box_shadow',
				'selector'       => '{{WRAPPER}} .search--breadcrumb',
				'fields_options' => [
					'box_shadow_current' => [ 'default' => 'yes' ],
					'box_shadow'         => [
						'default' => [
							'horizontal' => 0,
							'vertical'   => 3,
							'blur'       => 8,
							'spread'     => 0,
							'color'      => 'rgba(239, 239, 239, 1)',
						]
					]
				],
			]
		);
	}

	public function breadcrumbs_filter() {

		// icon switcher.
		$this->add_control(
			'breadcrumb_home_icon',
			[
				'label'     => __( 'Icon', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_icon',
			[
				'label'   => __( 'Different Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'filter_icon',
			[
				'label'       => __( 'Filter Icon', 'lisfinity-core' ),
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
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .filter-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .filter-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#959595',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .filter-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .filter-icon'     => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);
		$this->add_control(
			'filter_breadcrumb_dropdown_typography_search_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "filter_breadcrumb_select_dropdown",
				'selector'       => "{{WRAPPER}} .search--breadcrumb .css-dvua67-singleValue",
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
				'label' => 'Selected Item typography'
			]
		);
		$this->add_control(
			'filter_breadcrumb_dropdown_label_typography_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "filter_breadcrumb_select_dropdown_label_details",
				'selector'       => "{{WRAPPER}} .search--breadcrumb label",
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
				'label'          => 'Label Typography'
			]
		);
		$this->add_control(
			'filter_breadcrumb_dropdown_bg_color_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_background_color( 'filter_breadcrumb_select_dropdown_bg_color', '#ffffff', 'Background Color', '.search--breadcrumb div[class*=-menu], {{WRAPPER}} .search--breadcrumb div[class*=-fk865s]', true );

		$this->set_background_color( 'filter_breadcrumb_select_dropdown_bg_color_hover', '#f6f6f6', 'Background Color on Hover', '.search--breadcrumb .css-dpec0i-option', true );

		$this->set_background_color( 'filter_breadcrumb_select_dropdown_bg_color_active', '#f6f6f6', 'Active Background Color', '.search--breadcrumb .css-xo7z33-option', true );

		$this->add_control(
			'filter_breadcrumb_dropdown_typography_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "filter_breadcrumb_select_dropdown_details",
				'selector'       => "{{WRAPPER}} .search--breadcrumb div[class*=-option], {{WRAPPER}} .search--breadcrumb div[class*=-menu]",
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

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "filter_breadcrumb_select_dropdown_details_hover",
				'selector'       => "{{WRAPPER}} .search--breadcrumb .css-dpec0i-option",
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
				'label'          => 'Typography On Hover',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "filter_breadcrumb_select_dropdown_details_active",
				'selector'       => "{{WRAPPER}} .search--breadcrumb .css-xo7z33-option",
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
				'label'          => 'Typography of Active Item'
			]
		);
	}

	/**
	 * Sidebar
	 * ------------------------------
	 */

	public function sidebar_style() {
		$this->start_controls_tabs( 'sidebar_tabs' );

		// normal values;
		$this->start_controls_tab( 'sidebar_wrapper_tab',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
			]
		);
		$this->set_margin( 'business_sidebar_wrapper_margin', '.filters', 0, 0, 0, 0, true );

		$this->set_padding( 'business_sidebar_wrapper_padding', '.filters', '20', '16', '30', '16', false );

		$this->set_background_color( 'business_sidebar_wrapper_bg_color', 'rgba(255, 255, 255, 1)', 'Background Color', '.filters' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "business_sidebar_wrapper_shadow",
				'selector' => '{{WRAPPER}} .filters',
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "business_sidebar_wrapper_border",
				'selector' => '{{WRAPPER}} .filters',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'sidebar_title_tab',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);
		$this->set_margin( 'title_margin', '.filters--header', 0, 0, 0, 0, true );

		$this->set_padding( 'title_padding', '.filters--header', 0, 0, 0, 0, false );


		$this->add_control(
			'custom_filter_text',
			[
				'label'   => __( 'Custom Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'filter_text',
			[
				'label'       => __( 'Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own filter title text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_filter_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "search_filters_title",
				'selector' => "{{WRAPPER}} .filters--title .flex-center",
			]
		);
		$this->end_controls_tab();
		//reset
		$this->start_controls_tab( 'sidebar_reset_button_tab',
			[
				'label' => __( 'Reset', 'lisfinity-core' ),
			]
		);
		$this->set_padding( 'reset_padding', '.action--reset', '0', '0', '0', '0', false );

		$this->add_control(
			'reset_bg',
			[
				'label'       => __( 'Reset Background Color (Disabled)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Choose the background color when the button is disabled', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'reset_bg_hover',
			[
				'label'       => __( 'Reset Background Color (Disabled, On Hover)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Choose the background color when the button is disabled and on hover', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset__disabled:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "reset_border",
				'selector' => "{{WRAPPER}} .action--reset",
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "reset_shadow",
				'selector' => "{{WRAPPER}} .action--reset",
			]
		);

		// reset icon.
		$this->add_control(
			'use_custom_reset_icon',
			[
				'label'   => __( 'Different Reset Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'reset_icon',
			[
				'label'       => __( 'Reset Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom reset icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'reset_icon_size',
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
					'{{WRAPPER}} .action--reset svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .action--reset i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'reset_icon_active',
			[
				'label'       => __( 'Reset Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#199473',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .action--reset svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .action--reset i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'use_custom_reset_icon' => 'yes',
				],
			]
		);

		// Reset Text.
		$this->add_control(
			'heading_reset_text_style',
			[
				'label'     => __( 'Reset Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_text',
			[
				'label'       => __( 'Different Reset Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own reset text or leave empty to use default value', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'reset_text_color_active',
			[
				'label'       => __( 'Reset Text Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#199473',
				'description' => __( 'Choose the text color when the reset button is active', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .reset-text' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		//label
		$this->start_controls_tab( 'sidebar_label_tab',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);

		$this->set_margin( 'business_store_label_margin', '.filters--label', 0, 0, 4, 0, false );

		$this->set_padding( 'business_store_label_padding', '.filters--label', 0, 0, 0, 0, false );

		$this->add_responsive_control(
			'business_store_label_position',
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
					'{{WRAPPER}} .filters--label' => 'display: flex; justify-content: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "business_store_filters_labels",
				'selector'       => "{{WRAPPER}} .filters--label",
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
		$this->end_controls_tab();
		//select
		$this->start_controls_tab( 'sidebar_select_tab',
			[
				'label' => __( 'Select', 'lisfinity-core' ),
			]
		);
		$this->select();
		$this->end_controls_tab();
		//checkbox
		$this->start_controls_tab( 'sidebar_checkbox_tab',
			[
				'label' => __( 'Checkbox', 'lisfinity-core' ),
			]
		);
		$this->checkbox();
		$this->end_controls_tab();

		//checkbox
		$this->start_controls_tab( 'sidebar_range_tab',
			[
				'label' => __( 'Range', 'lisfinity-core' ),
			]
		);
		$this->range();
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function select() {

		$this->set_background_color( 'sidebar_select_bg_color', '#f6f6f6', 'Background Color', '.filters div[class*=css-0]' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "sidebar_select_border",
				'selector'       => "{{WRAPPER}} .filters div[class*=css-0]",
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

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "sidebar_search_filters_select",
				'selector'       => "{{WRAPPER}} .filters div[class*=css-0]",
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

		// dropdown typography.
		$this->add_control(
			'sidebar_select_dropdown',
			[
				'label'     => __( 'Dropdown Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'sidebar_select_dropdown_bg_color', '#ffffff', 'Background Color', '.filters div[class*=-menu], {{WRAPPER}} .filters div[class*=-fk865s]', true );

		$this->set_background_color( 'sidebar_select_dropdown_bg_color_hover', '#f6f6f6', 'Background Color on Hover', '.filters .css-dpec0i-option', true );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "sidebar_search_filters_select_dropdown",
				'selector'       => "{{WRAPPER}} .filters div[class*=-option]",
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

	}

	public function range() {

		$this->add_control(
			'sidebar_range_field',
			[
				'label'     => __( 'Range Fields Style', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'sidebar_price_bg_color', '#f6f6f6', 'Background Color', '.filters .field--with-icon' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "sidebar_price_border",
				'selector'       => "{{WRAPPER}} .filters .field--with-icon",
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
			'sidebar_range_label',
			[
				'label'     => __( 'Label Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "sidebar_price_filters_select",
				'selector'       => "{{WRAPPER}} .filters .field--with-icon__label",
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
						'default' => 600
					],
				],
			]
		);

		$this->add_control(
			'sidebar_range_placeholder',
			[
				'label'     => __( 'Value Typography', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "price_filters_select_placeholder",
				'selector'       => "{{WRAPPER}} .filters input",
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

	public function checkbox() {

		$this->add_control(
			'sidebar_checkbox_structure',
			[
				'label'     => __( 'Checkbox Structure Options', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sidebar_checkbox_columns',
			[
				'label'       => __( 'No. of Columns', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 2,
				'description' => __( 'Choose number of columns', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox' => 'width: calc(100% / {{VALUE}});',
				]
			]
		);

		$this->add_control(
			'sidebar_checkbox_styles',
			[
				'label'     => __( 'Checkbox Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->set_padding( 'sidebar_checkbox_label_padding', '.search-taxonomy.checkbox .field--checkbox label', 0, 0, 0, 12, false );

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "sidebar_checkbox_typography",
				'selector'       => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox",
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
						'default' => 600
					],
				],
			]
		);

		$this->add_control(
			'sidebar_checkbox_bg_styles',
			[
				'label'     => __( 'Checkbox Background Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->set_background_color( 'sidebar_checkbox_bg_color', '#f6f6f6', 'Background Color', '.search-taxonomy.checkbox .field--checkbox input' );
		$this->set_background_color( 'sidebar_checkbox_bg_color_active', '#2186eb', 'Active Background Color', '.search-taxonomy.checkbox .field--checkbox input::after' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "sidebar_checkbox_shadow",
				'selector' => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input",
			]
		);

		$this->add_control(
			'sidebar_checkbox_border_styles',
			[
				'label'     => __( 'Checkbox Border Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "sidebar_checkbox_border",
				'selector'       => "{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input",
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
			'sidebar_checkbox_border_radius_active',
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
					'{{WRAPPER}} .search-taxonomy.checkbox .field--checkbox input::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Button Search
	 * ------------------------------
	 */

	public function button_search() {

		$this->add_control(
			'business_button_icon_heading',
			[
				'label'     => __( 'Icon Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'business_use_custom_button_icon',
			[
				'label'   => __( 'Different Button Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'business_button_submit_icon',
			[
				'label'       => __( 'Button Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom button icon', 'lisfinity-core' ),
				'condition'   => [
					'business_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'business_button_icon_size',
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
					'{{WRAPPER}} .btn--search svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .btn--search i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'business_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'business_button_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .btn--search svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .btn--search i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'business_use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		// button text.
		$this->add_control(
			'business_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'business_custom_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'business_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'business_custom_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'business_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'business_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'business_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'business_button_bg_color', '#0967d2', 'Background Color', '.btn--search' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "business_button_border",
				'selector'       => "{{WRAPPER}} .btn--search",
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
				'name'     => "business_button_shadow",
				'selector' => "{{WRAPPER}} .btn--search",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "business_button_typography",
				'selector'       => "{{WRAPPER}} .btn--search",
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
		$this->start_controls_tab( 'business_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'business_button_bg_color_hover', '#03449e', 'Background Color', '.btn--search:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => "business_button_border_hover",
				'selector'       => "{{WRAPPER}} .btn--search:hover",
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
				'name'     => "business_button_shadow_hover",
				'selector' => "{{WRAPPER}} .btn--search:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'           => "business_button_typography_hover",
				'selector'       => "{{WRAPPER}} .btn--search:hover",
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

	/**
	 * Button Detailed Search
	 * ------------------------------
	 */

	public function button_detailed() {
		$this->start_controls_section(
			'detailed_button',
			[
				'label' => __( 'Detailed Button', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// button text.
		$this->add_control(
			'd_button_text_heading',
			[
				'label'     => __( 'Button Text', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_d_button_text',
			[
				'label'   => __( 'Different Button Text?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'd_button_text',
			[
				'label'       => __( 'Different Submit Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Type your own submit text or leave empty to use default value', 'lisfinity-core' ),
				'condition'   => [
					'custom_d_button_text' => 'yes',
				],
			]
		);

		// tabs.
		$this->add_control(
			'd_button_styles',
			[
				'label'     => __( 'Button Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'd_button_active_tabs' );

		// normal button values;
		$this->start_controls_tab( 'd_button_normal',
			[
				'label' => __( 'Normal', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'd_button_bg_color', '#e6f6ff', 'Background Color', '.btn--light' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "d_button_border",
				'selector' => "{{WRAPPER}} .btn--light",
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
					'color'  => [ 'default' => 'rgba(186, 227, 255, 1)' ],
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
				'name'     => "d_button_shadow",
				'selector' => "{{WRAPPER}} .btn--light",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "d_button_typography",
				'selector' => "{{WRAPPER}} .btn--light",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(33, 134, 235, 1)'
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

		$this->end_controls_tab();

		// hover button values.
		$this->start_controls_tab( 'd_button_hover',
			[
				'label' => __( 'Hover', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'd_button_bg_color_hover', '#e6f6ff', 'Background Color', '.btn--light:hover' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "d_button_border_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
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
					'color'  => [ 'default' => 'rgba(186, 227, 255, 1)' ],
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
				'name'     => "d_button_shadow_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
			]
		);

		$this->add_group_control(
			Group_Control_Filters_Typography::get_type(),
			[
				'name'     => "d_button_typography_hover",
				'selector' => "{{WRAPPER}} .btn--light:hover",
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(33, 134, 235, 1)'
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

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Chosen Filters
	 * ------------------------------
	 */

	public function chosen_filters() {
		$this->start_controls_tabs( 'chosen_filters_tabs' );

		// wrapper;
		$this->start_controls_tab( 'chosen_filters_wrapper',
			[
				'label' => __( 'Wrapper', 'lisfinity-core' ),
			]
		);
		$this->chosen_filters_wrapper();
		$this->end_controls_tab();
		//label
		$this->start_controls_tab( 'chosen_filters_label',
			[
				'label' => __( 'Label', 'lisfinity-core' ),
			]
		);
		$this->chosen_filters_label();
		$this->end_controls_tab();
		//items
		$this->start_controls_tab( 'chosen_filters_items',
			[
				'label' => __( 'Items', 'lisfinity-core' ),
			]
		);
		$this->chosen_filters_items();
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function chosen_filters_wrapper() {
		$this->set_margin( 'business_chosen_filters_wrapper_margin', '.filters--chosen', '0', '0', '20', '0', false );

		$this->set_padding( 'business_chosen_filters_wrapper_padding', '.filters--chosen', '0', '0', '0', '0', true );

		$this->set_background_color( 'business_chosen_filters_wrapper_bg_color', 'transparent', 'Background Color', '.filters--chosen' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => "business_chosen_filters_wrapper_shadow",
				'selector' => '{{WRAPPER}} .filters--chosen',
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "business_chosen_filters_wrapper_border",
				'selector' => '{{WRAPPER}} .filters--chosen',
			]
		);
	}

	public function chosen_filters_label() {
		$this->set_margin( 'business_chosen_filters_label_margin', '.filters--chosen .label', '8', '4', '0', '0', false );

		$this->set_padding( 'business_chosen_filters_label_padding', '.filters--chosen .label', '0', '0', '0', '0', true );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_chosen_filters_label_typography',
				'selector'       => '{{WRAPPER}} .filters--chosen .label',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#959595',
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
	}

	public function chosen_filters_items() {

		$this->add_control(
			'business_use_custom_items_icon',
			[
				'label'   => __( 'Different Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'business_items_submit_icon',
			[
				'label'       => __( 'Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom icon', 'lisfinity-core' ),
				'condition'   => [
					'business_use_custom_items_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'business_items_icon_size',
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .chosen-item svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .chosen-item i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'business_use_custom_button_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'business_items_icon_color',
			[
				'label'       => __( 'Title Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#00000',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .chosen-item svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .chosen-item i'   => 'color: {{VALUE}};',
				],
				'condition'   => [
					'business_use_custom_button_icon' => 'yes',
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_chosen_filters_items_typography',
				'selector'       => '{{WRAPPER}} .chosen-item .inline-block',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#00000',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_chosen_filters_items_typography_hover',
				'selector'       => '{{WRAPPER}} .chosen-item .inline-block:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#00000',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ],
					],
					'font_weight' => [
						'default' => 500,
					],
				],
				'label'          => 'Typography on Hover'
			]
		);
		$this->set_margin( 'business_chosen_filters_items_margin', '.chosen-item', '8', '0', '0', '8', false );

		$this->set_padding( 'business_chosen_filters_items_padding', '.chosen-item', '4', '12', '4', '12', true );

		$this->set_background_color( 'business_chosen_filters_items_bg_color', 'rgba(255, 255, 255, 1)', 'Background Color', '.chosen-item' );
		$this->set_background_color( 'business_chosen_filters_items_bg_color_hover', 'rgba(255, 255, 255, 1)', 'Background Color on Hover', '.chosen-item:hover' );

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'           => "business_chosen_filters_items_shadow",
				'selector'       => '{{WRAPPER}} .chosen-item',
				'fields_options' => [
					'box_shadow_current' => [ 'default' => 'yes' ],
					'box_shadow'         => [
						'default' => [
							'horizontal' => 0,
							'vertical'   => 1,
							'blur'       => 3,
							'spread'     => 0,
							'color'      => 'rgba(239, 239, 239, .06)',
						]
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => "business_chosen_filters_items_border",
				'selector' => '{{WRAPPER}} .chosen-item',
			]
		);
	}


	/**
	 * Listings Style
	 * ------------------------------
	 */
	public function listing_style() {
		$this->start_controls_section(
			'listings_style',
			[
				'label' => __( 'Listings Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// control | template.
		$this->add_control(
			'style',
			[
				'label'       => __( 'Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'options'     => [
					'1'      => __( 'Style 1', 'lisfinity-core' ),
					'2'      => __( 'Style 2', 'lisfinity-core' ),
					'3'      => __( 'Style 3', 'lisfinity-core' ),
					'4'      => __( 'Style 4', 'lisfinity-core' ),
					'custom' => __( 'Custom Style', 'lisfinity-core' ),
				],
				'default'     => '1',
				'description' => __( 'Choose the style of the product box template.', 'lisfinity-core' ),
			]
		);


		$this->end_controls_section();
	}

	public function listings_layout() {
		// product image settings.
		$this->start_controls_section(
			'listings_style_layout',
			[
				'label' => __( 'Listings Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_layout();

		$this->end_controls_section();
	}


	public function products_layout() {

		$this->add_responsive_control(
			'products-columns',
			[
				'label'           => __( 'Break Listings Into Columns', 'lisfinity-core' ),
				'label_block'     => true,
				'type'            => Controls_Manager::NUMBER,
				'desktop_default' => 3,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'min'             => 1,
				'max'             => 6,
				'description'     => __( "Choose the number of columns you wish to break listing's boxes", 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap',
			[
				'label'           => __( 'Listings Columns Gap', 'lisfinity-core' ),
				'label_block'     => true,
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => [ 'px' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'desktop_default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'tablet_default'  => [
					'unit' => 'px',
					'size' => 16,
				],
				'mobile_default'  => [
					'unit' => 'px',
					'size' => 16,
				],
				'description'     => __( 'Add columns gap between listings (horizontal).', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap-y',
			[
				'label'           => __( 'Ad Columns Gap Vertical', 'lisfinity-core' ),
				'label_block'     => true,
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => [ 'px' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'desktop_default' => [
					'unit' => 'px',
					'size' => 32,
				],
				'tablet_default'  => [
					'unit' => 'px',
					'size' => 32,
				],
				'mobile_default'  => [
					'unit' => 'px',
					'size' => 32,
				],
				'description'     => __( 'Add columns gap between listings (vertical).', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}

	public function box_styling() {
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'products_border_box',
				'selector' => '{{WRAPPER}} .lisfinity-product',
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label'     => __( 'Background color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .product-col .lisfinity-product'          => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .product-col .lisfinity-product--content' => 'background-color:transparent;',
				],
			]
		);

		$this->set_border_radius( 'box_border_radius', '3', '3', '3', '3', 'px', '.lisfinity-product' );

	}

	public function product_image_settings() {
		$this->add_responsive_control(
			'background-image-position',
			[
				'label'       => __( 'Ad Image Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'options'     => [
					'column'         => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row'            => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column-reverse' => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'row-reverse'    => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'column',
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product' => 'display: flex; flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'background-image-position-centered',
			[
				'label'       => __( 'Ad Image Position Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
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
				'toggle'      => false,
				'default'     => 'center',
				'condition'   => [
					'_image_position' => 'absolute',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product' => 'align-items: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image-fit',
			[
				'label'       => __( 'Image Fit Mode', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'    => __( 'Default', 'lisfinity-core' ),
					'contain'    => __( 'Contain', 'lisfinity-core' ),
					'cover'      => __( 'Cover', 'lisfinity-core' ),
					'fill'       => __( 'Fill', 'lisfinity-core' ),
					'scale-down' => __( 'Scale Down', 'lisfinity-core' ),
				],
				'default'     => 'cover',
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main img' => 'object-fit: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image-height',
			[
				'label'       => __( 'Custom Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 284,
				],
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'_image_width',
			[
				'label'                => __( 'Width', 'lisfinity-core' ),
				'label_block'          => true,
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'inherit',
				'options'              => [
					''        => __( 'Custom', 'lisfinity-core' ),
					'inherit' => __( 'Full Width', 'lisfinity-core' ) . ' (100%)',
					'auto'    => __( 'Inline', 'lisfinity-core' ) . ' (auto)',
				],
				'selectors_dictionary' => [
					'inherit' => '100%',
				],
				'prefix_class'         => 'elementor-widget%s__width-',
				'selectors'            => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'width: {{VALUE}}; max-width: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'_image_custom_width',
			[
				'label'       => __( 'Custom Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'   => [
					'_image_width' => '',
				],
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'condition' => [
							'_image_width_tablet' => [ 'initial' ],
						],
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'condition' => [
							'_image_width_mobile' => [ 'initial' ],
						],
					],
				],
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'_image_position',
			[
				'label'              => __( 'Position', 'lisfinity-core' ),
				'label_block'        => true,
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''         => __( 'Default', 'lisfinity-core' ),
					'absolute' => __( 'Absolute', 'lisfinity-core' ),
				],
				'prefix_class'       => 'category-',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'position: {{VALUE}}',
				],
			]
		);

		$start = is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );
		$end   = ! is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );

		$this->add_control(
			'_image_offset_orientation_h',
			[
				'label'       => __( 'Horizontal Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					],
					'end'   => [
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					],
				],
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 0.1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'       => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->add_control(
			'_image_offset_orientation_v',
			[
				'label'       => __( 'Vertical Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'end'   => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->add_responsive_control(
			'image-margin',
			[
				'label'       => __( 'Image Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);

		$this->add_responsive_control(
			'image-border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
	}

	public function products_action_bookmark_style() {

		$this->display_element( 'hide_show_action_bookmark', esc_html__( 'Display Action Bookmark', 'lisfinity-core' ) );

		$this->add_control(
			'place_icon_bookmark',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'icon_bookmark',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_bookmark' => 'yes',
				],
			]
		);

		$this->set_icon_size( 'action_bookmark_icon_size', '18', '.bookmark-icon' );

		$this->set_heading_section( 'action_bookmark_icon_heading', 'Icon Color', 'action_bookmark_icon_color_hr' );

		$this->set_icon_color( 'id_bookmarked', 'Color of the active icon', '#ef4e4e', '.bookmark-icon.h-18.w-18.fill-red-600' );

		$this->set_icon_color( 'id_not_bookmarked', 'Color of the inactive icon', 'rgba(255, 255, 255, 1)', '.bookmark-icon.h-18.w-18.fill-white' );

		$this->set_heading_section( 'action_bookmark_position_heading', 'Icon Position', 'action_bookmark_position_hr' );

		$this->set_element_position( 'id_bookmark_position_x', '24', 'id_bookmark_position_y', '30', '.action--like', 'hide_show_action_bookmark', 'right' );
	}

	/**
	 * Product fixed price style settings
	 * ----------------------
	 */
	public function products_fixed_price_style() {

		$this->set_heading_section( 'price_default_heading', esc_html__('Price Options', 'lisfinity-core'), 'price_default_hr' );

		$this->set_background_color( 'display_default_price_color',  'rgba(76, 76, 76, 1)','Price Color',  '.price-default', false );

		$this->add_control(
			'display_default_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .price-default' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_default_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'icon_default_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_default_price' => 'yes',
				],
			]
		);

		$this->set_icon_size('products_default_icon_size', '14', '.default-products-icon');

		$this->set_icon_color('products_default_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(149, 149, 149, 1)', '.default-products-icon');

		$this->display_element( 'display_label_default', 'Display Label' );

	}


	/**
	 * Product price on sale style settings
	 * ----------------------
	 */
	public function products_price_on_call_style() {

		$this->set_heading_section( 'price_on_call_heading', esc_html__('Price Options', 'lisfinity-core'), 'price_on_call_hr' );

		$this->set_background_color( 'display_on_call_price_color', 'rgba(33, 134, 235, 1)','Price Color',  '.lisfinity-product--meta__price.text-blue-600', false );

		$this->add_control(
			'display_on_call_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-blue-600' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_on_call_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'icon_on_call_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_on_call_price' => 'yes',
				],
			]
		);

		$this->set_icon_size('products_price_on_call_icon_size', '14', '.fill-icon-call');

		$this->set_icon_color('products_price_on_call_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(33, 134, 235,1)', '.fill-icon-call');

	}

	/**
	 * Product auction price style settings
	 * ----------------------
	 */
	public function products_auction_price_style() {

		$this->set_heading_section( 'price_auction_heading', esc_html__('Price Options', 'lisfinity-core'), 'price_auction_hr' );

		$this->add_control(
			'place_icon_auction_price',
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
			'icon_auction_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_auction_price' => 'yes',
				],
			]
		);

		$this->set_icon_size('products_auction_icon_size', '14', '.fill-field-icon');

		$this->set_icon_color('products_auction_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(149, 149, 149,1)', '.fill-field-icon');

		$this->display_element( 'display_product_countdown', 'Display Countdown' );

	}

	/**
	 * Product price on sale style settings
	 * ----------------------
	 */

	public function products_price_on_sale_style() {

		$this->set_heading_section( 'price_on_sale_heading', esc_html__('Price Options', 'lisfinity-core'), 'price_hr' );

		$this->set_background_color( 'display_on_sale_price_color', 'rgba(97, 2, 21, 1)','Price Color',  '.lisfinity-product--meta__price.text-red-1100', false );

		$this->add_control(
			'display_sale_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-red-1100' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_on_sale_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'icon_on_sale_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_on_sale_price' => 'yes',
				],
			]
		);

		$this->set_icon_size('products_on_sale_icon_size', '14', '.fill-icon-sale');

		$this->set_icon_color('products_on_sale_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(97, 2, 21,,1)', '.fill-icon-sale');

	}

	public function products_price_label_on_sale_style() {

		$this->add_control(
			'hr_1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->display_element( 'display_label_on_sale', esc_html__('Display Listing Label', 'lisfinity-core') );


		$this->add_control(
			'hr_2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'display_label_options_on_sale',
			[
				'label'        => __( 'Display Label Additional Options', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => [
					"{{WRAPPER}} .lisfinity-product" => 'overflow: initial;',
				],
				'condition'    => [
					'display_label_on_sale' => 'yes',
				],
			]
		);


		$this->add_control(
			'label_options_icon_heading',
			[
				'label'     => __( 'Label Icon Options', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_icon_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_icon',
			[
				'label'        => __( 'Use Custom Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);
		$this->add_control(
			'label_icon_url',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'label_icon'                    => 'yes',
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_sale_icon_size_id',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
					'em' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '14',
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon svg, {{WRAPPER}} .label--sale .lisfinity-product--meta__icon i'                       => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);
		$this->add_control(
			'label_sale_icon_color_id',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon svg, {{WRAPPER}} .label--sale .lisfinity-product--meta__icon i' => 'fill: {{VALUE}}; color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'label_options_other_heading',
			[
				'label'     => __( 'Other Label Options', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_other_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Product_Label_On_Sale_Typography::get_type(),
			[
				'name'      => 'products_label_on_sale_typography',
				'selector'  => '{{WRAPPER}} .label--sale .lisfinity-product--meta__icon',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_text_color_id',
			[
				'label'       => __( 'Text Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the text.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon' => 'color:{{VALUE}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_bg_color_id',
			[
				'label'       => __( 'Label Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(239, 78, 78, 1)',
				'description' => __( 'Label Background Color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon' => 'background-color:{{VALUE}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);
		$this->add_control(
			'label_on_sale_border_radius_id',
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ 'px', 'em', '%' ],
				'range'       => [
					'%'  => [
						'min' => 0,
						'max' => 50,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'     => [
					'unit'   => 'px',
					'top'    => '5',
					'right'  => '5',
					'bottom' => '5',
					'left'   => '5'
				],
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon' => 'border-radius:{{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_padding_id',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 6,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_options_positioning_heading',
			[
				'label'     => __( 'Label Position', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_position_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->set_element_position( 'label_positioning_x', '-25', 'label_positioning_y', '-4', '.lisfinity-product .label--sale', 'display_label_options_on_sale', 'right' );


	}

	/**
	 * Product price style settings
	 * ----------------------
	 */
	public function products_price_free_style() {

		$this->set_heading_section( 'price_free_heading', esc_html__('Price Options', 'lisfinity-core'), 'price_free_hr' );

		$this->set_background_color( 'display_free_price_color', 'rgba(20, 125, 100, 1)', 'Price Color', '.lisfinity-product--meta__price.text-green-900', false );

		$this->add_control(
			'display_free_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-green-900' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_free_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],

			]
		);

		$this->add_control(
			'icon_free_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_free_price' => 'yes',
				],
			]
		);

		$this->set_icon_size('products_free_icon_size', '14', '.fill-icon-gift');

		$this->set_icon_color('products_free_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(20, 125, 100,1)', '.fill-icon-gift');

	}


	/**
	 * Product promoted icon style settings
	 * ----------------------
	 */
	public function products_promoted_icon_style() {
		$this->add_group_control(
			Group_Control_Product_Promoted_Icon_Typography::get_type(),
			[
				'name'     => 'products_promoted_icon_cap_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--title .label--promoted',
			]
		);

		$this->set_background_color( 'promoted_icon_color', 'rgba(247, 201, 72, 1)', esc_html__( 'Color', 'lisfinity-core' ),  '.lisfinity-product--title .label--promoted', false );

	}

	/**
	 * Product title style settings
	 * ----------------------
	 */
	public function products_title_style() {
		$this->display_element( 'hide_show_product_title', esc_html__( 'Display Product Title', 'lisfinity-core' ) );

		$this->add_group_control(
			Group_Control_Product_Title_Typography::get_type(),
			[
				'name'     => 'product_title_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--title .product--title ',
			]
		);

		$this->set_background_color( 'products_title_color', 'rgba(76, 76, 76, 1)', 'Set the color of the text', '.lisfinity-product--title .product--title', false );


		$this->add_control(
			'align_title',
			[
				'label'       => __( 'Set alignment of the title', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => 'left',
				'toggle'      => true,
				'description' => __( 'Set alignment of the title', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--title .product--title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->set_padding( 'id_title_padding', '.lisfinity-product--title .product--title', '0', '0', '0', '0', 'true' );

	}

	/**
	 * Product custom fields style settings
	 * ----------------------
	 */
	public function products_custom_fields_style() {

		$this->set_background_color( 'products_custom_fields_bg_color', 'rgba(246, 246, 246, 1)', esc_html__('Background color', 'lisfinity-core'), '.lisfinity-product--cf' );

		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'product_custom_fields_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--cf',
			]
		);

		$this->set_background_color( 'products_custom_fields_color', 'rgba(94, 94, 94, 1)', esc_html__('Set the color of the text', 'lisfinity-core'), '.lisfinity-product--cf', false );


		$this->set_icon_size( 'products_custom_fields_icon_size', '12', '.lisfinity-product--cf .fill-taxonomy-icon' );


		$this->set_icon_color( 'products_custom_fields_icon_color', 'Icon Color', 'rgba(94, 94, 94, 1)', '.lisfinity-product--cf .fill-taxonomy-icon path' );


		$this->set_elements_alignment( 'align_custom_fields',  'flex-start', '.lisfinity-products--cf-wrapper' );


	}

	/**
	 * Product info ratings style settings
	 * ----------------------
	 */
	public function products_info_ratings_style() {

		$this->set_background_color( 'products_info_ratings_bg_color', 'rgba(255, 243, 196, 1)', esc_html__('Background color','lisfinity-core'), '.lisfinity-product--info.lisfinity__rating .bg-yellow-300' );

		$this->set_border_radius( 'products_info_ratings_border_radius', '50', '50', '50', '50', '%', '.lisfinity-product--info.lisfinity__rating .bg-yellow-300' );

		$this->set_icon_color( 'products_info_ratings_icon_color', 'Icon Color', 'rgba(203, 110, 23, 1)', '.lisfinity-product--info.lisfinity__rating .fill-product-star-icon' );

		$this->set_icon_size( 'products_info_ratings_icon_size', '14', '.lisfinity-product--info.lisfinity__rating .fill-product-star-icon' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'product_info_ratings_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.flex-center .ml-6.text-sm ',
			]
		);


	}

	/**
	 * Product info location style settings
	 * ----------------------
	 */
	public function products_info_location_style() {

		$this->set_background_color( 'products_info_location_bg_color', 'rgba(193, 254, 246, 1)', esc_html__('Background color','lisfinity-core'), '.lisfinity-product--info.lisfinity__location .bg-cyan-300' );

		$this->set_border_radius( 'products_info_location_border_radius', '50', '50', '50', '50', '%',  '.lisfinity-product--info.lisfinity__location .bg-cyan-300' );

		$this->set_icon_color( 'products_info_location_icon_color', 'Icon Color', 'rgba(5, 96, 110, 1)', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->set_icon_size( 'products_info_location_icon_size', '14', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'product_info_location_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.flex-center .ml-6.text-sm',
			]
		);

	}

	/**
	 * Product logo style settings
	 * ----------------------
	 */
	public function products_logo_style() {

		$this->display_element( 'display_product_owner_logo', 'Display Product Owner Logo' );

	}

	/**
	 * Pagination
	 * ------------------------------
	 */

	public function business_pagination() {
		$this->start_controls_tabs( 'business_pagination_tabs' );

		// default values;
		$this->start_controls_tab( 'business_pagination_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_default_page_typography',
				'selector'       => '{{WRAPPER}} .default-page',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#4c4c4',
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
		$this->set_background_color( 'business_default_link_pagination', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.default-page' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => 'business_default_page_border',
				'selector'       => '{{WRAPPER}} .default-page',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
						],
					],
					'color'  => [ 'default' => 'transparent' ],
					'radius' => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => 'business_default_page_box_shadow',
				'selector' => '{{WRAPPER}} .default-page',
			]
		);

		$this->add_control(
			'business_default_page_padding',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .default-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => '0',
				],
			]
		);

		$this->add_control(
			'business_default_page_margin',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .default-page' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => '0',
				],
			]
		);


		$this->end_controls_tab();

		// active values;
		$this->start_controls_tab( 'business_pagination_active_tab',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_active_page_typography',
				'selector'       => '{{WRAPPER}} .active-page',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#4c4c4',
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
		$this->set_background_color( 'business_default_link_pagination_active', '#fffff', esc_html__( 'Background Color', 'lisfinity-core' ), '.active-page' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => 'business_active_page_border',
				'selector'       => '{{WRAPPER}} .active-page',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
						],
					],
					'color'  => [ 'default' => 'transparent' ],
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
				'name'           => 'business_active_page_box_shadow',
				'selector'       => '{{WRAPPER}} .active-page',
				'fields_options' => [
					'default' => [
						"horizontal" => 0,
						'vertical'   => 3,
						'blur'       => 8,
						'spread'     => 0,
						'color'      => 'rgba(239 239 239, 1)',
					],
				],
			]
		);

		$this->add_control(
			'business_active_page_padding',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .active-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => '0',
				],
			]
		);

		$this->add_control(
			'business_active_page_margin',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .active-page' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => '0',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->set_heading_section( 'business_bottom_pagination_heading', esc_html__( 'Bottom Pagination', 'lisfinity-core' ), 'bottom_pagination_hr' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_default_link_typography',
				'selector'       => '{{WRAPPER}} .pagination--simple span',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#4c4c4',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 400,
					],
				],
				'label'          => 'Prev Page Typography'
			]
		);

		$this->add_responsive_control(
			'business_default_link_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size (Prev Page)', 'lisfinity-core' ),
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
					'{{WRAPPER}} .pagination--simple span svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'business_default_link_icon_color',
			[
				'label'       => __( 'Icon Color (Prev Page)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#4c4c4c',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .pagination--simple span svg' => 'fill: {{VALUE}};'
				],
				'separator'   => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'business_active_link_typography',
				'selector'       => '{{WRAPPER}} .pagination--simple button',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => '#4c4c4',
					],
					'font_size'   => [
						'default' =>
							[ 'size' => 14 ],
					],
					'font_weight' => [
						'default' => 400,
					],
				],
				'label'          => 'Next Page Typography'
			]
		);

		$this->add_responsive_control(
			'business_active_link_icon_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Size (Next Page)', 'lisfinity-core' ),
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
					'{{WRAPPER}} .pagination--simple button svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'business_active_link_icon_color',
			[
				'label'       => __( 'Icon Color (Next Page)', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#4c4c4c',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .pagination--simple button svg' => 'fill: {{VALUE}};'
				],
				'separator'   => 'after',
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
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector, {{WRAPPER}} $selector svg" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_icon_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $message, 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector, {{WRAPPER}} $selector svg" => 'fill:{{VALUE}}; color: {{VALUE}}',
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

		include lisfinity_get_template_part( 'business-store', 'shortcodes/business-profile', $args );
	}

}
