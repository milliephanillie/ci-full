<?php


namespace Lisfinity\Shortcodes\BusinessProfileSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Ratings_Text_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;

class Business_Contact_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'business-contact';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Business Contact', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
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
			'business_map_wrapper',
			[
				'label' => __( 'Map style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->display_element('display_map', esc_html__('Display Map', 'lisfinty-core'));
		$this->add_group_control(
			Group_Control_Testimonials_Box_Shadow::get_type(),
			[
				'name'     => 'map_border_box',
				'selector' => '{{WRAPPER}} .business--map-wrapper',
			]
		);

		$this->set_background_color( 'box_background_color_map', 'rgba(255, 255, 255, 1)', 'Background Color', '.business--map' );


		$this->set_border_radius( 'box_border_radius_map', '3', '3', '3', '3', 'px', '.business--map, {{WRAPPER}} .business--map-wrapper' );
		$this->set_width( 'map_width', '.business--map', '100', '%' );
		$this->set_height( 'map_height', '.business--map', '380', 'px' );

		$this->end_controls_section();
		$this->start_controls_section(
			'business_location',
			[
				'label' => __( 'Address style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->display_element('display_address', esc_html__('Display Address', 'lisfinty-core'));
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'     => 'address_typography',
				'selector' => '{{WRAPPER}} .business--location',
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
						'default' => 400
					],
				],
			]
		);

		$this->set_padding('address_padding', '.business--location', '0', '0', '0', '0', false);
		$this->set_margin('address_margin', '.business--location', '0', '0', '0', '0', false);

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

		include lisfinity_get_template_part( 'business-contact', 'shortcodes/business-profile', $args );
	}

}
