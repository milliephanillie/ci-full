<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\PackageModel;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Button_Border;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Button_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Content_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Footnote_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Recommended_Button_Border;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Title_Typography;





class Subscriptions_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'lisfinity-subscriptions';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Subscription Packages', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-money';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'packages_feed',
			[
				'label' => __( 'Packages Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | number of products.
		$this->add_control(
			'number',
			[
				'label'       => __( 'Number of Packages to Show', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => 4,
				'description' => __( 'Choose the number of packages that will be displayed.', 'lisfinity-core' ),
			]
		);

		// control | order of the products.
		$this->add_control(
			'order',
			[
				'label'       => __( 'Products Order', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'asc'  => __( 'Ascending', 'lisfinity-core' ),
					'desc' => __( 'Descending', 'lisfinity-core' ),
				],
				'default'     => 'asc',
				'description' => __( 'Choose order of the products', 'lisfinity-core' ),
			]
		);

		// control | order of the products.
		$this->add_control(
			'orderby',
			[
				'label'       => __( 'Products Sorting', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'meta_value_num' => __( 'Price', 'lisfinity-core' ),
					'date'           => __( 'Date', 'lisfinity-core' ),
					'title'          => __( 'Name', 'lisfinity-core' ),
				],
				'default'     => 'date',
				'description' => __( 'Choose sorting of the products', 'lisfinity-core' ),
			]
		);

		// control | handpick.
		$package_model = new PackageModel();
		$repeater      = new Repeater();
		$repeater->add_control(
			'package_id',
			[
				'label'       => __( 'Price Package', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => false,
				'options'     => $package_model->format_packages_select(),
				'description' => __( 'Manually choose price package that you wish to display.', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'packages_handpicked',
			[
				'label'         => __( 'Handpick Price Packages', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Manually choose price packages that you wish display.', 'lisfinity-core' ),
				'title_field'   => __( 'Price Package: {{{ package_id }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->end_controls_section();

		// Style |  Packages.

		$this->start_controls_section(
			'ordinary_package_style',
			[
				'label' => __( 'Packages Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->package_box_style();

		$this->start_controls_tabs(
			'ordinary_packages_tabs'
		);
		$this->start_controls_tab(
			'ordinary_package_title_tab',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);

		$this->ordinary_packages_title_style();
		$this->end_controls_tab();
		$this->start_controls_tab(
			'ordinary_package_price_tab',
			[
				'label' => __( 'Price', 'lisfinity-core' ),
			]
		);

		$this->ordinary_packages_price_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'ordinary_package_content_tab',
			[
				'label' => __( 'Content', 'lisfinity-core' ),
			]
		);

		$this->ordinary_packages_content_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'ordinary_package_button_tab',
			[
				'label' => __( 'Button', 'lisfinity-core' ),
			]
		);

		$this->ordinary_packages_button_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		// Style |  Recommended Package.

		$this->start_controls_section(
			'recommended_package_style',
			[
				'label' => __( 'Recommended Packages Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->recommended_package_box_style();

		$this->start_controls_tabs(
			'recommended_packages_tabs'
		);
		$this->start_controls_tab(
			'recommended_package_title_tab',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
			]
		);

		$this->recommended_packages_title_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'recommended_package_price_tab',
			[
				'label' => __( 'Price', 'lisfinity-core' ),
			]
		);

		$this->recommended_packages_price_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'recommended_package_content_tab',
			[
				'label' => __( 'Content', 'lisfinity-core' ),
			]
		);

		$this->recommended_packages_content_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'recommended_package_button_tab',
			[
				'label' => __( 'Button', 'lisfinity-core' ),
			]
		);

		$this->recommended_packages_button_style();
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

		// Style |  Packages.

		$this->start_controls_section(
			'package_footnote_style',
			[
				'label' => __( 'Footnotes Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->package_footnote_style();

		$this->end_controls_section();

	}

	/*
	 * Packages box styles.
	 * -------------------------
	 */

	public function package_box_style() {

		$this->set_heading_section( 'package_box_style_heading', esc_html__('Box Style', 'lisfinity-core'), 'package_box_style_hr' );
		$this->set_background_color( 'package_box_bg_color', 'rgba(246, 246, 246, 1)', esc_html__('Background Color','lisfinity-core'), '.package.bg-grey-100' );
		$this->set_border_radius( 'package_box_bg_color_border_radius', '3', '3','3','3','px', esc_html__('Border Radius','lisfinity-core'), '.package.bg-grey-100' );

		$this->add_group_control(
			Group_Control_Packages_Box_Shadow::get_type(),
			[
				'name'     => 'packages_box_shadow_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100',
			]
		);


		$this->add_control(
			'package_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

	}

	/*
	 * Packages box styles.
	 * -------------------------
	 */


	public function package_footnote_style() {

		$this->add_group_control(
			Group_Control_Packages_Footnote_Typography::get_type(),
			[
				'name'     => 'packages_footnote_typography',
				'selector' => '{{WRAPPER}} .package--footnote .footnote',
			]
		);

		$this->set_text_color( 'packages_footnote_color', esc_html__('Text Color','lisfinity-core'), 'rgba(96, 96, 96, 1)', '.package--footnote .footnote' );

		$this->set_heading_section( 'icon_footnote_id', esc_html__('Icon Style','lisfinity-core'), 'icon_footnote_hr' );



		$this->add_control(
			'icon_footnote_size_id',
			[
				'label'       => __( 'Text Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .package--footnotes__star' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'icon_footnote_translate_Y_id',
			[
				'label'       => __( 'Icon Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em' ],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .package--footnotes-star-wrapper' => 'transform: translate(-{{SIZE}}{{UNIT}}, -{{SIZE}}{{UNIT}});',
				]
			]
		);


		$this->add_control(
			'icon_footnote_color_id',
			[
				'label'     => __( 'Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(239, 78, 78, 1)',
				'selectors' => [
					'{{WRAPPER}} .package .text-red-600'    => 'color:{{VALUE}};',
					'{{WRAPPER}} .package--footnotes__star' => 'color:{{VALUE}};',
				]
			]
		);
	}


	/*
	 * Packages title styles.
	 * -------------------------
	 */

	public function ordinary_packages_title_style() {
		$this->add_group_control(
			Group_Control_Packages_Title_Typography::get_type(),
			[
				'name'     => 'packages_title_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .text-lg',
			]
		);

		$this->set_text_color( 'packages_title_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-grey-100 .text-lg' );
	}

	/*
	 * Packages price styles.
	 * -------------------------
	 */

	public function ordinary_packages_price_style() {
		$this->set_font_size( 'package_price_size_id', '28', '.package.relative.bg-grey-100 .package--price' );
		$this->set_text_color( 'package_price_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(38, 38, 38, 1)', '.package.relative.bg-grey-100 .package--price' );

		$this->set_padding( 'package_price_padding_id', '.package.relative.bg-grey-100 .package--price', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'package_price_margin_id', '.package.relative.bg-grey-100 .package--price', '30', '0', '0', '0', 'true' );

		$this->set_heading_section( 'ordinary_price_before_price', esc_html__('Original Price','lisfinity-core'), 'ordinary_price_before_price_hr' );

		$this->set_font_size( 'package_original_price_size_id', '18', '.package.relative.bg-grey-100 del .woocommerce-Price-amount.amount' );

		$this->set_text_color( 'package_original_price_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-grey-100 del' );
	}

	/*
	 * Packages content styles.
	 * -------------------------
	 */

	public function ordinary_packages_content_style() {

		$this->add_group_control(
			Group_Control_Packages_Content_Typography::get_type(),
			[
				'name'     => 'packages_content_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .package--features .relative.mb-8',
			]
		);

		$this->set_text_color( 'package_content_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-grey-100 .package--features .relative.mb-8' );

		$this->set_heading_section( 'package_content_number_id', esc_html__('Number of Items Style','lisfinity-core'), 'package_content_number_id_hr' );

		$this->set_text_color( 'package_content_number_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-grey-100 .package--features .relative.mb-8 strong' );

		$this->set_font_size( 'package_content_number_size_id', '14', '.package.relative.bg-grey-100 .package--features .relative.mb-8 strong' );

		$this->add_control(
			'content_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_padding( 'package_content_padding_id', '.package.relative.bg-grey-100 .package--features', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'package_content_margin_id', '.package.relative.bg-grey-100 .package--features', '30', '0', '0', '0', 'true' );

	}

	/*
	 * Packages button styles.
	 * -------------------------
	 */

	public function ordinary_packages_button_style() {

		$this->add_group_control(
			Group_Control_Packages_Button_Typography::get_type(),
			[
				'name'     => 'packages_button_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .package--buy',
			]
		);
		$this->set_text_color( 'package_button_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-grey-100 .package--buy' );
		$this->add_group_control(
			Group_Control_Packages_Button_Border::get_type(),
			[
				'name'     => 'packages_button_border',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .package--buy',
			]
		);
		$this->set_border_radius( 'package_button_border_radius_id', '3', '3','3','3','px', 'Border Radius', '.package.relative.bg-grey-100 .package--buy' );
		$this->set_background_color( 'package_button_bg_color_id', 'transparent', esc_html__('Background Color','lisfinity-core'), '.package.relative.bg-grey-100 .package--buy' );

		$this->set_heading_section( 'package_button_on_hover_id', esc_html__('On hover','lisfinity-core'), 'package_button_on_hover_hr_id' );

		$this->add_group_control(
			Group_Control_Packages_Button_Typography::get_type(),
			[
				'name'     => 'packages_button_on_hover_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .package--buy:hover',
			]
		);
		$this->set_text_color( 'package_button_color_id_on_hover', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.package.relative.bg-grey-100 .package--buy:hover' );
		$this->add_group_control(
			Group_Control_Packages_Button_Border::get_type(),
			[
				'name'     => 'packages_button_border_on_hover',
				'selector' => '{{WRAPPER}} .package.relative.bg-grey-100 .package--buy:hover',
			]
		);
		$this->set_border_radius( 'package_button_border_radius_id_on_hover', '3', '3', '3', '3', 'px', 'Border Radius', '.package.relative.bg-grey-100 .package--buy:hover' );
		$this->set_background_color( 'package_button_bg_color_id_on_hover', 'rgba(149, 149, 149, 1)', esc_html__('Background Color','lisfinity-core'), '.package.relative.bg-grey-100 .package--buy:hover' );

		$this->set_heading_section( 'package_button_heading_padding_id', esc_html__('Padding and Margin','lisfinity-core'), 'package_button_padding_hr_id' );

		$this->set_padding( 'package_button_padding_id', '.package.relative.bg-grey-100 .package--buy', '8', '0', '8', '0', 'true' );
		$this->set_margin( 'package_button_margin_id', '.package.relative.bg-grey-100 .package--buy', '30', '0', '20', '0', 'true' );

	}

	/*
	 * Recommended Package title styles.
	 * -------------------------
	 */


	public function recommended_package_box_style() {

		$this->set_heading_section( 'recommended_package_box_style_heading', esc_html__('Box Style','lisfinity-core'), 'recommended_package_box_style_hr' );
		$this->set_background_color( 'recommended_package_box_bg_color', 'rgba(255, 255, 255, 1)', esc_html__('Background Color','lisfinity-core'), '.package.bg-white' );
		$this->set_border_radius( 'recommended_package_box_bg_color_border_radius', '3', '3', '3', '3', 'px', esc_html__('Border Radius','lisfinity-core'), '.package.bg-white' );

		$this->add_group_control(
			Group_Control_Packages_Box_Shadow::get_type(),
			[
				'name'     => 'recommended_packages_box_shadow_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-white',
			]
		);


		$this->add_control(
			'recommended_package_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

	}

	/*
	 * Recommended Package title styles.
	 * -------------------------
	 */

	public function recommended_packages_title_style() {
		$this->add_group_control(
			Group_Control_Packages_Title_Typography::get_type(),
			[
				'name'     => 'package_recommended_title_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-white .text-lg',
			]
		);
		$this->set_text_color( 'package_recommended_title_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-white .text-lg' );
	}


	/*
	 * Recommended Package price styles.
	 * -------------------------
	 */

	public function recommended_packages_price_style() {

		$this->set_font_size( 'package_recommended_price_size_id', '28', '.package.relative.bg-white .package--price' );

		$this->set_text_color( 'package_recommended_price_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(38, 38, 38, 1)', '.package.relative.bg-white .package--price' );

		$this->set_padding( 'recommended_package_price_padding_id', '.package.relative.bg-white .package--price', '0', '0', '0', '0', 'true' );

		$this->set_margin( 'recommended_package_price_margin_id', '.package.relative.bg-white .package--price', '30', '0', '0', '0', 'true' );


		$this->set_heading_section( 'recommended_package_price_box', esc_html__('Price Box','lisfinity-core'), 'recommended_package_price_box_hr' );

		$this->set_background_color( 'recommended_package_price_box_bg_color', 'rgba(230, 246, 255, 1)', esc_html__('Background Color','lisfinity-core'), '.package--price .package--recommended' );

		$this->add_control(
			'recommended_package_price_box_width',
			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'size' => 100,
					'unit' => '%'
				],
				'selectors'   => [
					'{{WRAPPER}} .package--price .package--recommended' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'recommended_package_price_box_height',
			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'size' => 60,
					'unit' => 'px',
				],
				'selectors'   => [
					'{{WRAPPER}} .package--price .package--recommended' => 'height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_border_radius( 'recommended_package_price_box_border_radius', '3', '3', '3', '3', 'px', esc_html__('Border Radius','lisfinity-core'), '.package--price .package--recommended' );
	}


	/*
	 * Recommended Package content styles.
	 * -------------------------
	 */

	public function recommended_packages_content_style() {

		$this->add_group_control(
			Group_Control_Packages_Content_Typography::get_type(),
			[
				'name'     => 'recommended_packages_content_typography',
				'selector' => '{{WRAPPER}} .package.bg-white .package--features .relative.mb-8',
			]
		);

		$this->set_text_color( 'package_recommended_content_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.bg-white .package--features .relative.mb-8' );

		$this->set_heading_section( 'recommended_package_content_number_id', esc_html__('Number of Items Style','lisfinity-core'), 'package_content_number_id_hr_2' );

		$this->set_text_color( 'recommended_package_content_number_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(76, 76, 76, 1)', '.package.relative.bg-white .package--features .relative.mb-8 strong' );

		$this->set_font_size( 'recommended_package_content_number_size_id', '14', '.package.relative.bg-white .package--features .relative.mb-8 strong' );

		$this->add_control(
			'recommended_content_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_padding( 'recommended_package_content_padding_id_2', '.package.relative.bg-white .package--features', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'recommended_package_content_margin_id_2', '.package.relative.bg-white .package--features', '30', '0', '0', '0', 'true' );

	}

	/*
	 * Recommended Package button styles.
	 * -------------------------
	 */

	public function recommended_packages_button_style() {

		$this->add_group_control(
			Group_Control_Packages_Button_Typography::get_type(),
			[
				'name'     => 'recommended_packages_button_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-white .package--buy',
			]
		);

		$this->set_text_color( 'package_recommended_button_color_id', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.package.relative.bg-white .package--buy' );

		$this->add_group_control(
			Group_Control_Packages_Recommended_Button_Border::get_type(),
			[
				'name'     => 'recommended_packages_button_border',
				'selector' => '{{WRAPPER}} .package.relative.bg-white .package--buy',
			]
		);
		$this->set_border_radius( 'recommended_package_button_border_radius_id', '3', '3', '3', '3', 'px', 'Border Radius', '.package.relative.bg-white .package--buy' );
		$this->set_background_color( 'recommended_package_button_bg_color_id', 'rgba(33, 134, 235, 1)', esc_html__('Background Color','lisfinity-core'), '.package.relative.bg-white .package--buy' );

		$this->set_heading_section( 'recommended_package_button_on_hover_id', esc_html__('On hover','lisfinity-core'), 'recommended_package_button_on_hover_hr_id' );

		$this->add_group_control(
			Group_Control_Packages_Button_Typography::get_type(),
			[
				'name'     => 'recommended_packages_button_on_hover_typography',
				'selector' => '{{WRAPPER}} .package.relative.bg-white .package--buy:hover',
			]
		);
		$this->set_text_color( 'recommended_package_button_color_id_on_hover', esc_html__( 'Set Color', 'lisfinity-core' ), 'rgba(255, 255, 255, 1)', '.package.relative.bg-white .package--buy:hover' );
		$this->add_group_control(
			Group_Control_Packages_Recommended_Button_Border::get_type(),
			[
				'name'     => 'recommended_packages_button_border_on_hover',
				'selector' => '{{WRAPPER}} .package.relative.bg-white .package--buy:hover',
			]
		);
		$this->set_border_radius( 'recommended_package_button_border_radius_id_on_hover', '3', '3', '3', '3', 'px', 'Border Radius', '.package.relative.bg-white .package--buy:hover' );
		$this->set_background_color( 'recommended_package_button_bg_color_id_on_hover', 'rgba(3, 68, 158, 1)', esc_html__('Background Color','lisfinity-core'), '.package.relative.bg-white .package--buy:hover' );

		$this->set_heading_section( 'recommended_package_button_heading_padding_id', esc_html__('Padding and Margin','lisfinity-core'), 'recommended_package_button_padding_hr_id' );

		$this->set_padding( 'recommended_package_content_padding_id', '.package.relative.bg-white .package--features', '0', '0', '0', '0', 'true' );
		$this->set_margin( 'recommended_package_content_margin_id', '.package.relative.bg-white .package--features', '30', '0', '0', '0', 'true' );

	}

	/*
	 * Functions.
	 * -------------------------
	 */

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

	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_unit, $message, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'unit'     => $default_unit
				],
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	public function set_text_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => esc_html( $message ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [ "{{WRAPPER}} $selector" => 'color:{{VALUE}};' ],
			]
		);
	}

	public function set_font_size( $id, $default, $selector ) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Text Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_elements_alignment( $id, $label, $default, $selector ) {
		$this->add_control(
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
	 * Get the arguments for the products wp query
	 * -------------------------------------------
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	protected function get_packages_args( $settings ) {
		$type = \WC_Product_Payment_Subscription::$type;
		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number'],
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $type,
					'operator' => 'IN',
				],
			],
		];

		// add order to query args.
		if ( ! empty( $settings['order'] ) ) {
			$args['order'] = $settings['order'];
		}

		// add sorting to query args.
		if ( ! empty( $settings['orderby'] ) ) {
			if ( 'meta_value_num' === $settings['orderby'] ) {
				$args['orderby']  = $settings['orderby'];
				$args['meta_key'] = '_price';
			} else {
				$args['orderby'] = $settings['orderby'];
			}
		}

		// add handpicked products to query args.
		if ( ! empty ( $settings['packages_handpicked'] ) ) {
			$handpicked = [];
			foreach ( $settings['packages_handpicked'] as $package_handpicked ) {
				if ( ! empty( $package_handpicked['package_id'] ) ) {
					$handpicked[] = $package_handpicked['package_id'];
				}
			}
			if ( ! empty( $handpicked ) ) {
				$args['post__in'] = $handpicked;
			}
		}

		return $args;
	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args     = $this->get_packages_args( $settings );

		$packages = new \WP_Query( $args );

		$args = [
			'settings' => $settings,
			'packages' => $packages,
		];
		include lisfinity_get_template_part( 'subscriptions', 'shortcodes', $args );
	}

}
