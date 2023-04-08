<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Search_Map extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'search-map';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Search Map', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-map-marked-alt';
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
		$this->map_default();
		$this->map_skin();
	}

	public function map_default() {
		$this->start_controls_section(
			'map_styles',
			[
				'label' => __( 'Map Styles', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			]
		);

		$this->add_group_control(
			Group_Control_Search_Page_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .page-search-map',
			]
		);

		$this->add_group_control(
			Group_Control_Banner_Form_Wrapper_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_shadow',
				'selector' => '{{WRAPPER}} .page-search-map',
			]
		);

		$this->add_control(
			'marker_cluster_styling',
			[
				'label'     => __( 'Cluster Styles', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cluster_bg_color',
			[
				'label'       => __( 'Marker Cluster Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#0967d2',
				'description' => __( 'Choose the default marker cluster background color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .leaflet-marker-icon .cluster' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cluster_color',
			[
				'label'       => __( 'Marker Cluster Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the default marker cluster color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .leaflet-marker-icon .cluster .cluster-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'marker_styling',
			[
				'label' => __( 'Marker Styles', 'lisfinity-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label'       => __( 'Marker Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#0967d2',
				'description' => __( 'Choose the default marker color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .leaflet-marker-icon svg.fill-blue-700' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'marker_color_promoted',
			[
				'label'       => __( 'Promoted Marker Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#e12d39',
				'description' => __( 'Choose the promoted marker color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .leaflet-marker-icon svg.fill-red-700' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function map_skin() {
		$this->start_controls_section(
			'map_skin',
			[
				'label' => __( 'Map Skin', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_style_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'Global map skins can be adjusted from %s', 'lisfinity-core' ), '<strong>Lisfinity Options -> Location & Map Setup</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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

		include lisfinity_get_template_part( 'search-map', 'shortcodes/search-page', $args );
	}

}
