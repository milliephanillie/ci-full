<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Custom_Fields_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Name_Typography;

class Product_Custom_Fields_Widget extends Shortcode {
	public $custom_fields = [];
	public $new_array = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->custom_fields = lisfinity_get_submission_fields_ids('text');
		foreach ($this->custom_fields as $key => $field){
			$this->new_array[$field] = $field;
		}
		$this->new_array['published-date'] = 'published-date';
	}
	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-custom-fields';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Custom Fields', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-signature';
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
			'product_custom_fields',
			[
				'label' => __( 'Field Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_control(
			'select_fields',
			[
				'label'   => __( 'Select Field', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->new_array,
			]
		);
		$this->add_control(
			'date_format',
			[
				'label'       => __( 'Date format', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Y-m-d', 'lisfinity-core' ),
				'placeholder' => __( 'Y-m-d', 'lisfinity-core' ),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'select_fields',
							'operator' => '===',
							'value' => 'published-date',
						],
						[
							'name' => 'select_fields',
							'operator' => '===', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'date-of-death',
						],
					],
				]
			]
		);
		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} .product-custom-fields',
			]
		);

		$this->set_text_color( 'due_date_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.product-custom-fields' );



		$this->end_controls_section();

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

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'settings' => $settings,
		];

		include lisfinity_get_template_part( 'product-custom-fields', 'shortcodes/product-single', $args );
	}

}
