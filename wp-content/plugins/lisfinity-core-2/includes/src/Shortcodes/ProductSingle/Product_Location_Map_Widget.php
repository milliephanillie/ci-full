<?php


namespace Lisfinity\Shortcodes\ProductSingle;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Location_Map_Address_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Location_Map_Expand_Map_Typography;

class Product_Location_Map_Widget extends Shortcode {
	public $addresses = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->addresses = [
			'owner_location'   => esc_html__( 'Owner Location', 'lisfinity-core' ),
			'listing_location'      => esc_html__( 'Listing Location', 'lisfinity-core' ),
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'product-location-map';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Product Location Map', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'far fa-map';
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
			'location_map',
			[
				'label' => __( 'Map Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'select_location',
			[
				'label'   => __( 'Select Location', 'lisfinity-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->addresses,
			]
		);
		$this->set_width('location_width', '.leaflet-container.leaflet-fade-anim', '100', '%');
		$this->set_height('location_height', '.leaflet-container.leaflet-fade-anim', '100', '%');
		$this->set_border_radius('location_border_radius', '3', '3', '3', '3', 'px', '.leaflet-container.leaflet-fade-anim');
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'location_box_shadow',
				'selector' => '{{WRAPPER}} .leaflet-container.leaflet-fade-anim',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'location_border',
				'selector' => '{{WRAPPER}} .leaflet-container.leaflet-fade-anim',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'location_map_address',
			[
				'label' => __( 'Address Information Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Location_Map_Address_Typography::get_type(),
			[
				'name'     => 'location_address_typography',
				'selector' => '{{WRAPPER}} .profile--address address',
			]
		);
		$this->set_elements_alignment('location_address_alignment', 'left', '.profile--address', false);
		$this->set_width('location_address_wrapper_width', '.profile--address', '100', '%');
		$this->set_height('location_address_wrapper_height', '.profile--address', '100', '%');
		$this->set_padding('location_address_wrapper_padding', '.profile--address', '0', '0','0', '0', false);
		$this->set_margin('location_address_wrapper_margin', '.profile--address', '10', '0','0', '0', false);
		$this->set_background_color('location_address_wrapper_bg_color', 'transparent', esc_html__('Background Color', 'lisfinity-core'), '.profile--address');
		$this->set_border_radius('location_address_wrapper_border_radius', '3', '3', '3', '3', 'px', '.profile--address');
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'location_address_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .profile--address',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'location_address_wrapper_border',
				'selector' => '{{WRAPPER}} .profile--address',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'location_map_expand_map',
			[
				'label' => __( 'Expand Map Button Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'location_map_expand_map_tabs',
			[
				'label' => __( 'Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			"location_map_expand_map_default_tab",
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Location_Map_Expand_Map_Typography::get_type(),
			[
				'name'     => 'location_expand_map_button_typography',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button',
			]
		);
		$this->set_padding('location_expand_map_button_padding', '.profile--address .profile--address-button', '0', '0','0', '0', false);
		$this->set_margin('location_expand_map_button_margin', '.profile--address .profile--address-button', '10', '0','0', '0', false);
		$this->set_background_color('location_expand_map_button_bg_color', 'transparent', esc_html__('Background Color', 'lisfinity-core'), '.profile--address .profile--address-button');
		$this->set_border_radius('location_expand_map_button_border_radius', '3', '3', '3', '3', 'px', '.profile--address .profile--address-button');
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'location_expand_map_button_box_shadow',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'location_expand_map_button_border',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			"location_map_expand_map_hover_tab",
			[
				'label' => __( 'On Hover', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Location_Map_Expand_Map_Typography::get_type(),
			[
				'name'     => 'location_expand_map_button_typography_hover',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button:hover',
			]
		);
		$this->set_padding('location_expand_map_button_hover_padding', '.profile--address .profile--address-button:hover', '0', '0','0', '0', false);
		$this->set_margin('location_expand_map_button_hover_margin', '.profile--address .profile--address-button:hover', '10', '0','0', '0', false);
		$this->set_background_color('location_expand_map_button_hover_bg_color', 'transparent', esc_html__('Background Color', 'lisfinity-core'), '.profile--address .profile--address-button:hover');
		$this->set_border_radius('location_expand_map_button_hover_border_radius', '3', '3', '3', '3', 'px', '.profile--address .profile--address-button:hover');
		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Box_Shadow::get_type(),
			[
				'name'     => 'location_expand_map_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Info_Button_Border::get_type(),
			[
				'name'     => 'location_expand_map_button_hover_border',
				'selector' => '{{WRAPPER}} .profile--address .profile--address-button:hover',
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

		include lisfinity_get_template_part( 'product-location-map', 'shortcodes/product-single', $args );
	}

}
