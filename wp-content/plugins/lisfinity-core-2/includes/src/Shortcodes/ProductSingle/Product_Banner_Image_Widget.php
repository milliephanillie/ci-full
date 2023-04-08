<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;

class Product_Banner_Image_Widget extends Shortcode {

	public $images = [];
	public $array = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->images['fallback-image'] = 'Fallback Image';
		$this->array = lisfinity_get_submission_fields_ids( '', 'image' );
		foreach ( $this->array as $key => $element ) {
			$this->images[ $element ] = $element;
		}
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-banner-image';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Banner Image', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
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
		return [ 'lisfinity-single-product' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'image_content',
			[
				'label' => __( 'Image', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_fields',
			[
				'label'   => __( 'Select Field', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->images,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'fallback_image_content',
			[
				'label' => __( 'Fallback Image', 'lisfinity-core' ),
				'description' => __('Choose the image in case that the banner image is not provided.', 'lisfinity-core'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'fallback_image',
			[
				'label'       => __( 'Fallback Image', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'image_wrapper',
			[
				'label' => __( 'Image Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->banner_image_wrapper_style();
		$this->end_controls_section();

		$this->banner_image();

	}

	public function banner_image_wrapper_style() {
		$this->set_background_color( 'banner_image_bg_color', 'transparent', esc_html__( 'Background color', 'lisfinity-core' ), '.elementor-product-banner-image' );

		$this->set_border_radius( 'banner_image_border_radius', '3', '3', '3', '3', 'px', '.elementor-product-banner-image, {{WRAPPER}} .product-banner--image img' );

		$this->set_padding( 'banner_image_padding', '.elementor-product-banner-image', '0', '0', '0', '0', false );

		$this->set_margin( 'banner_image_margin', '.elementor-product-banner-image', '0', '0', '20', '0', false );

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'single_product_banner_image_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-product-banner-image',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'single_product_banner_image_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-product-banner-image',
			]
		);
		$this->set_width( 'banner_image_wrapper_width', '.product-banner--image', '100', '%' );
		$this->set_height( 'banner_image_wrapper_height', '.product-banner--image', '350', 'px' );

	}

	public function banner_image() {
		$this->start_controls_section(
			'image',
			[
				'label' => __( 'Image', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'background-image-overlay',
			[
				'label'       => __( 'Display Image Overlay', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'yes',
				'options'     => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'No', 'lisfinity-core' ),
				],
				'condition'   => [
					'background-image' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'background-image-overlay-color',
			[
				'label'     => __( 'Image Overlay Color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .image-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'background-image-overlay' => 'yes',
				],
			]
		);

		$this->set_heading_section( 'background_image_size_heading', 'Image Size', 'background_image_size_hr' );

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
					'{{WRAPPER}} .product-banner--image img' => 'object-fit: {{VALUE}}',
				]
			]
		);

		$this->set_heading_section( 'banner_image_position_heading', esc_html__( 'Image Position', 'lisfinity-core' ), 'banner_image_position_hr' );
		$this->set_element_position( 'banner_image_position_x', '0', 'banner_image_position_y', '0', '.product-banner--image' );



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

		include lisfinity_get_template_part( 'product-banner-image', 'shortcodes/product-single', $args );
	}

}
