<?php


namespace Lisfinity\Shortcodes\Search;


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
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Description_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Search_Listings extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-listings';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Listings', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-clipboard-list';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-search-page' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		$this->map_settings();

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

		// product image settings.
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

		// product action bookmark settings.

		// product box settings.
		$this->start_controls_section(
			'verified_user',
			[
				'label'     => __( 'Verified User', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->verified_author();

		$this->end_controls_section();

		$this->start_controls_section(
			'products_bookmark_style',
			[
				'label'     => __( 'Action Bookmark Style', 'lisfinity-core' ),
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

		// product price fixed tab.

		$this->start_controls_tab(
			'product_price_fixed',
			[
				'label' => __( 'Default prices', 'lisfinity-core' ),
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
				'raw'             => sprintf( __( 'Taxonomies that will be display in the box can be set from %s', 'lisfinity-core' ), '<strong>Lisfinity Options -> Listings Setup -> Listing Taxonomies</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);


		$this->products_custom_fields_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'product_description_displayed_constainer',
			[
				'label'     => __( 'Display custom description', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->product_description_displayed();

		$this->end_controls_section();

		// product pagination settings.

		$this->start_controls_section(
			'products_pagination',
			[
				'label'     => __( 'Pagination', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_pagination();

		$this->end_controls_section();
	}

	public function map_settings() {
		$this->start_controls_section(
			'map_settings',
			[
				'label' => __( 'Map Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'display_map',
			[
				'label'       => __( 'Display Map', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'on'  => __( 'On', 'lisfinity-core' ),
					'off' => __( 'Off', 'lisfinity-core' ),
				],
				'default'     => 'off',

			]
		);

		$this->add_responsive_control(
			'map-margin',
			[
				'label'       => __( 'Map Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .map--width' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'map_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Map Height', 'lisfinity-core' ),
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 280,
				],
				'selectors'  => [
					'{{WRAPPER}} .map .leaflet-container' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'display_map!' => [ 'off' ],
				],
			]
		);

		$this->end_controls_section();
	}

	public function product_description_displayed() {
		$this->display_element('product_description_displayed', 'Display Product Description', '');
		$this->add_group_control(
			Group_Control_Single_Product_Description_Typography::get_type(),
			[
				'name'     => 'product_description_text_typography',
				'selector' => '{{WRAPPER}} .search-listing-description',
			]
		);
		$this->set_elements_alignment('product_description_alignment', 'left', '.search-listing-description', false);
		$this->set_padding( 'product_description_padding', '.search-listing-description', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'product_description_margin', '.search-listing-description', '20', '0', '0', '0', 'true' );

	}

	public function listing_style() {
		$this->start_controls_section(
			'products_style',
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
			'products_style_layout',
			[
				'label' => __( 'Listings Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_layout();

		$this->end_controls_section();
	}

	public function products_layout() {
		$this->add_control(
			'full_content_height',
			[
				'label'        => __( 'Dynamic Content Height', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',

			]
		);

		$this->add_control(
			'product-layout-without-map-heading',
			[
				'label' => __( 'Layout Without Map', 'lisfinity-core' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

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
				'description'     => __( 'Choose the number of columns you wish to break ad boxes', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap',
			[
				'label'           => __( 'Ad Columns Gap', 'lisfinity-core' ),
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
				'description'     => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
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
				'description'     => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product-layout-with-map-heading',
			[
				'label'     => __( 'Layout With Map', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'products-columns-with-map',
			[
				'label'           => __( 'Break Listings Into Columns', 'lisfinity-core' ),
				'label_block'     => true,
				'type'            => Controls_Manager::NUMBER,
				'desktop_default' => 1,
				'tablet_default'  => 1,
				'mobile_default'  => 1,
				'min'             => 1,
				'max'             => 6,
				'description'     => __( 'Choose the number of columns you wish to break ad boxes', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom.map-active .product-col' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .lisfinity-products.map-active .product-col'         => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap-with-map',
			[
				'label'           => __( 'Ad Columns Gap', 'lisfinity-core' ),
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
				'description'     => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom.map-active .product-col' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products.map-active .product-col'         => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap-y-with-map',
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
				'description'     => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'       => [
					'{{WRAPPER}} .lisfinity-products--custom.map-active .product-col' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products.map-active .product-col'         => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}}',
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

	public function products_pagination() {
		$this->start_controls_tabs( 'pagination_tabs' );

		// default values;
		$this->start_controls_tab( 'pagination_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'default_page_typography',
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
		$this->set_background_color( 'default_link_pagination', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.default-page' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => 'default_page_border',
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
				'name'     => 'default_page_box_shadow',
				'selector' => '{{WRAPPER}} .default-page',
			]
		);

		$this->add_control(
			'default_page_padding',
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
			'default_page_margin',
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
		$this->start_controls_tab( 'pagination_active_tab',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'active_page_typography',
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
		$this->set_background_color( 'default_link_pagination_active', '#fffff', esc_html__( 'Background Color', 'lisfinity-core' ), '.active-page' );

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'           => 'active_page_border',
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
				'name'           => 'active_page_box_shadow',
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
			'active_page_padding',
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
			'active_page_margin',
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

		$this->set_heading_section( 'bottom_pagination_heading', esc_html__( 'Bottom Pagination', 'lisfinity-core' ), 'bottom_pagination_hr' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'           => 'default_link_typography',
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
				'label'          => 'Prev Page'
			]
		);

		$this->add_responsive_control(
			'default_link_icon_size',
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
			'default_link_icon_color',
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
				'name'           => 'active_link_typography',
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
				'label'          => 'Next Page'
			]
		);

		$this->add_responsive_control(
			'active_link_icon_size',
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
			'active_link_icon_color',
			[
				'label'       => __( 'Icon Color (Active Page)', 'lisfinity-core' ),
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

	public function products_action_bookmark_style() {

		$this->display_element( 'hide_show_action_bookmark', 'Display Action Bookmark' );

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

/**Product fixed price style settings
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

		$this->add_control(
			'products_default_icon_size',

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
				'selectors'   => [
					'{{WRAPPER}} .default-products-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.default-products-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

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
				'selectors'   => [
					'{{WRAPPER}} .fill-icon-call svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.fill-icon-call' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

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

		$this->add_control(
			'products_auction_icon_size',
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
				'selectors'   => [
					'{{WRAPPER}} .fill-field-icon.auction-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.fill-field-icon.auction-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_icon_color('products_auction_icon_color', esc_html__('Icon Color', 'lisfinity-core'), 'rgba(149, 149, 149,1)', '.auction-icon');

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

		$this->add_control(
			'products_on_sale_icon_size',

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
				'selectors'   => [
					'{{WRAPPER}} .fill-icon-sale svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.fill-icon-sale' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

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

		$this->set_background_color( 'display_free_price_color', 'rgba(20, 125, 100, 1)', esc_html__('Price Color', 'lisfinty-core'), '.lisfinity-product--meta__price.text-green-900', false );

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

		$this->add_control(
			'products_free_icon_size',

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
				'selectors'   => [
					'{{WRAPPER}} .fill-icon-gift svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} i.fill-icon-gift' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

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

		$this->set_text_color( 'promoted_icon_color', esc_html__( 'Color', 'lisfinity-core' ), 'rgba(247, 201, 72, 1)', '.lisfinity-product--title .label--promoted' );

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

		$this->set_text_color( 'products_title_color', 'Set the color of the text', 'rgba(76, 76, 76, 1)', '.lisfinity-product--title .product--title' );


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

		$this->set_padding( 'id_title_padding', '.lisfinity-product--title .product--title', '0', '0', '0', '0', 'true', 'hide_show_product_title' );

	}

	/**
	 * Product custom fields style settings
	 * ----------------------
	 */
	public function products_custom_fields_style() {

		$this->set_background_color( 'products_custom_fields_bg_color', 'rgba(246, 246, 246, 1)', esc_html__('Background color', 'lisfinity-core'),'.lisfinity-product--cf' );

		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'product_custom_fields_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--cf',
			]
		);

		$this->set_text_color( 'products_custom_fields_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.lisfinity-product--cf' );


		$this->set_icon_size( 'products_custom_fields_icon_size', '12', '.lisfinity-product--cf .fill-taxonomy-icon' );


		$this->set_icon_color( 'products_custom_fields_icon_color', 'Icon Color', 'rgba(94, 94, 94, 1)', '.lisfinity-product--cf .fill-taxonomy-icon path' );


		$this->set_elements_alignment( 'align_custom_fields', 'Set alignment of the element', 'flex-start', '.lisfinity-products--cf-wrapper' );


	}

	/**
	 * Product info ratings style settings
	 * ----------------------
	 */
	public function products_info_ratings_style() {
		$this->display_element( 'hide_show_product_info_mark', 'Display Mark' );


		$this->set_background_color( 'products_info_ratings_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.lisfinity-product--info.lisfinity__rating .bg-yellow-300' );

		$this->set_border_radius( 'products_info_ratings_border_radius', '50', '50','50','50','%', '.lisfinity-product--info.lisfinity__rating .bg-yellow-300' );

		$this->set_icon_color( 'products_info_ratings_icon_color', 'Icon Color', 'rgba(203, 110, 23, 1)', '.lisfinity-product--info.lisfinity__rating .fill-product-star-icon' );

		$this->set_icon_size( 'products_info_ratings_icon_size', '14', '.lisfinity-product--info.lisfinity__rating .fill-product-star-icon' );

		$this->set_text_color( 'products_info_ratings_text_color', 'Set the color of the text', 'rgba(127, 127, 127, 1)', '.lisfinity-product--info.lisfinity__rating .ml-6.text-sm' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'product_info_ratings_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.flex-center.mr-10 .ml-6.text-sm ',
			]
		);


	}

	/**
	 * Product info location style settings
	 * ----------------------
	 */
	public function products_info_location_style() {
		$this->display_element( 'hide_show_product_info_place', 'Display Place' );

		$this->set_background_color( 'products_info_location_bg_color', 'rgba(193, 254, 246, 1)', 'Background color', '.lisfinity-product--info.lisfinity__location .bg-cyan-300' );

		$this->set_border_radius( 'products_info_location_border_radius', '50', '50', '50', '50', '%', '.lisfinity-product--info.lisfinity__location .bg-cyan-300' );

		$this->set_icon_color( 'products_info_location_icon_color', 'Icon Color', 'rgba(5, 96, 110, 1)', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->set_icon_size( 'products_info_location_icon_size', '14', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->set_text_color( 'products_info_location_text_color', 'Set the color of the text', 'rgba(127, 127, 127, 1)', '.lisfinity-product--info.flex-center .ml-6.text-sm' );


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
	 * *Product box styling
	 * -------------------------
	 */
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

		$this->set_border_radius( 'box_border_radius', '3', '3',  '3','3','px', '.lisfinity-product' );

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
	 * * functions
	 * -------------------------
	 */


	public function custom_icon( $id_place_icon, $id_icon_url ) {
		$this->add_control(
			$id_place_icon,
			[
				'label'        => __( 'Use Custom Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => true,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			$id_icon_url,
			[
				'label'       => __( 'Place icon', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
				'condition'   => [
					$id_place_icon => 'yes',
				],
			]
		);
	}

	public function icon_style( $id_place_icon, $icon_class, $id_icon_size, $default_size, $id_icon_color, $default_color ) {


		$this->add_control(
			$id_icon_size,

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
					'size' => $default_size,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			$id_icon_color,
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'fill: {{VALUE}};color: {{VALUE}};',
				],
			]
		);

	}


	public function sort_elements( $id, $description, $order_number, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $description, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
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
					"{{WRAPPER}} $selector" => 'fill:{{VALUE}}; color: {{VALUE}}',
				],
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
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
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

		include lisfinity_get_template_part( 'search-listings', 'shortcodes/search-page', $args );
	}

}
