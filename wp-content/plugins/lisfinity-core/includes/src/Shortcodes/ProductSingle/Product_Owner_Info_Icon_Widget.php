<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Name_Typography;

class Product_Owner_Info_Icon_Widget extends Shortcode {

	public $icons = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->icons = [
			'rating'   => esc_html__( 'Rating', 'lisfinity-core' ),
			'location'      => esc_html__( 'Location', 'lisfinity-core' ),
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-owner-info-icon';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Owner Info Icon', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-info-circle';
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
			'owner_info_icon_wrapper',
			[
				'label' => __( 'Wrapper Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label'   => __( 'Select Icon', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->icons,
			]
		);

		$this->add_control(
			'icon_bg_color_rating',
			[
				'label'       => __('Icon Background Color', 'lisfinity-core'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 243, 196, 1)',
				'selectors'   => [
					'{{WRAPPER}} .owner-icon-wrapper' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'select_icon' => 'rating'
				]
			]
		);

		$this->add_control(
			'icon_bg_color_location',
			[
				'label'       => __('Icon Background Color', 'lisfinity-core'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(193, 254, 246, 1)',
				'selectors'   => [
					'{{WRAPPER}} .owner-icon-wrapper' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'select_icon' => 'location'
				]
			]
		);

		$this->set_width('icon_bg_width', '.owner-icon-wrapper', '32', 'px');

		$this->set_height('icon_bg_height', '.owner-icon-wrapper', '32', 'px');

		$this->set_border_radius( 'icon_border_radius', '50', '50', '50', '50', '%', '.owner-icon-wrapper' );

		$this->end_controls_section();

		$this->start_controls_section(
			'owner_info_icon',
			[
				'label' => __( 'Icon Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'place_icon',
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
			'selected_icon',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color_rating',
			[
				'label'       => __('Icon Color', 'lisfinity-core'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(203, 110, 23, 1)',
				'selectors'   => [
					'{{WRAPPER}} .owner-icon-wrapper .fill-product-star-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition' => [
					'select_icon' => 'rating'
				]
			]
		);

		$this->add_control(
			'icon_color_location',
			[
				'label'       => __('Icon Color', 'lisfinity-core'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(5, 96, 110, 1)',
				'selectors'   => [
					'{{WRAPPER}} .owner-icon-wrapper .fill-product-place-icon' => 'fill:{{VALUE}}; color: {{VALUE}};'
				],
				'condition' => [
					'select_icon' => 'location'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
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
					'size' => '14',
				],
				'selectors'   => [
					'{{WRAPPER}} .owner-icon-wrapper .owner-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'owner_info_text',
			[
				'label' => __( 'Text Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'owner_icon_typography',
				'selector' => '{{WRAPPER}} .owner-icon-text ',
			]
		);


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

		include lisfinity_get_template_part( 'product-owner-info-icon', 'shortcodes/product-single', $args );
	}

}
