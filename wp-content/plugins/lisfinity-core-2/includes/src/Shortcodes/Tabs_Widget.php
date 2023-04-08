<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Models\Elements\ElementsGlobalModel;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Tabs_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;

class Tabs_Widget extends Shortcode {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'lisfinity-tabs';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Tabs', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-table';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'general', 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Partners section.
		$this->start_controls_section(
			'elements_content',
			[
				'label' => __( 'Global Elements Tabs', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'global_elements_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'The elements have first to be created from the %s section of wp-admin area.', 'lisfinity-core' ), '<strong>Lisfinity Elements -> Global Elements</strong>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Overall', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' ),
			]
		);

		$repeater->add_control(
			'widget',
			[
				'label'       => __( 'Choose Global Element', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => lisfinity_format_post_select( [
					'post_type' => ElementsGlobalModel::$type,
				] ),
				'default'     => '',
				'description' => __( 'Choose from the list of the global elements', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'         => __( 'Tabs', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'title_field'   => __( 'Tab: {{{ title }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_wrapper',
			[
				'label' => __( 'Tabs Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->tabs_wrapper_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_style',
			[
				'label' => __( 'Tabs Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->tabs_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_content_wrapper',
			[
				'label' => __( 'Tabs Content Wrapper', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->tabs_content_wrapper_style();
		$this->end_controls_section();
	}

	public function tabs_wrapper_style() {
		$this->set_background_color( 'tabs_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.ge-tabs--headers nav' );
		$this->set_border_radius( 'tabs_wrapper_border_radius', '3', '3', '3', '3', 'px', '.ge-tabs--headers nav' );
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'tabs_wrapper_border',
				'selector' => '{{WRAPPER}} .ge-tabs--headers nav',
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'           => 'tabs_wrapper_box_shadow',
				'selector'       => '{{WRAPPER}} .ge-tabs--headers nav',
				'fields_options' => [
					'box_shadow_current' => [ 'default' => 'yes' ],
					'box_shadow'         => [
						'default' => [
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 4,
							'spread'     => 1,
							'color'      => 'rgba(0, 0, 0, .5)',
						]
					]
				],
			]
		);
		$this->add_control(
			'tabs_wrapper_padding_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_padding( 'tabs_wrapper_padding', '.ge-tabs--headers nav', '15', '0', '15', '0', 'false' );
		$this->set_margin( 'tabs_wrapper_margin', '.ge-tabs--headers nav', '0', '0', '0', '0', 'false' );

		$this->add_control(
			'tabs_wrapper_alignment_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

	}

	public function tabs_content_wrapper_style() {
		$this->set_background_color( 'tabs_content_wrapper_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.ge-tabs--content' );
		$this->set_padding( 'tabs_content_wrapper_padding', '.ge-tabs--content', '40', '40', '40', '40', 'false' );
		$this->set_margin( 'tabs_content_wrapper_margin', '.ge-tabs--content', '0', '0', '20', '0', 'false' );
		$this->set_border_radius( 'tabs_content_wrapper_border_radius', '3', '3', '3', '3', 'px', '.ge-tabs--content' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'tabs_content_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .ge-tabs--content',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'tabs_content_wrapper_border',
				'selector' => '{{WRAPPER}} .ge-tabs--content',
			]
		);

//		$this->set_elements_alignment( 'tabs_position', 'flex-start', '.ge-tabs--headers nav', true );
	}

	/**
	 * Product tabs style
	 * ----------------------
	 */
	public function tabs_style() {
		$this->start_controls_tabs(
			'tabs_style_tabs'
		);

		// default tab.

		$this->start_controls_tab(
			'tab_style_default',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Tabs_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .product-tabs--header .product-tab',
			]
		);
		$this->add_control(
			'tabs_text_color',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_background_color( 'products_tab-inactive_color', 'rgba(0, 0, 0, 1)', 'Text Color', '.ge-tabs--action', false );

		$this->set_background_color( 'products_tab-inactive_color_hover', 'rgba(0, 0, 0, 1)', 'Text Color on Hover', '.ge-tabs--action:hover', false );

		$this->add_control(
			'tabs_bg_color',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_background_color( 'products_tabs_bg_color_inactive', 'transparent', 'Background color', '.ge-tabs--action' );

		$this->set_background_color( 'products_tabs_bg_color_inactive_hover', 'transparent', 'Background color on hover', '.ge-tabs--action:hover' );
		$this->add_control(
			'tabs_padding_color',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_padding( 'tab_default_padding', '.ge-tabs--action', '8', '20', '8', '20', 'false' );
		$this->set_margin( 'tab_default_margin', '.ge-tabs--action', '0', '0', '0', '0', 'false' );
		$this->add_control(
			'tabs_other_color',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_border_radius( 'tab_default_border_radius', '3', '3', '3', '3', 'px', '.ge-tabs--action' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'tab_default_box_shadow',
				'selector' => '{{WRAPPER}} .ge-tabs--action',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'tab_default_border',
				'selector' => '{{WRAPPER}} .ge-tabs--action',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_style_active',
			[
				'label' => __( 'Active', 'lisfinity-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Product_Tabs_Typography::get_type(),
			[
				'name'     => 'tab_typography_active',
				'selector' => '{{WRAPPER}} .ge-tabs--action active',
			]
		);
		$this->add_control(
			'tabs_text_color_active',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->set_background_color( 'products_tab_active_color', 'rgba(0, 0, 0, 1)', 'Text Color', '.ge-tabs--action.active', false );

		$this->set_background_color( 'text_color_hover_id', 'rgba(0, 0, 0, 1)', 'Text Color on Hover', '.ge-tabs--action.active:hover', false );

		$this->add_control(
			'tabs_bg_color_active',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_background_color( 'products_tabs_bg_color_active', '#e6f6ff', 'Background color', '.ge-tabs--action.active' );

		$this->set_background_color( 'products_tabs_bg_color_active_hover', '#e6f6ff', 'Background color on hover', '.ge-tabs--action.active:hover' );

		$this->add_control(
			'tabs_padding_active',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_padding( 'tab_active_padding', '.ge-tabs--action.active:hover', '8', '20', '8', '20', 'false' );
		$this->set_margin( 'tab_active_margin', '.ge-tabs--action.active:hover', '0', '0', '0', '0', 'false' );
		$this->add_control(
			'tabs_other_active',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->set_border_radius( 'tab_active_border_radius', '3', '3', '3', '3', 'px', '.ge-tabs--action.active:hover' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'     => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} .ge-tabs--action.active:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'tab_active_border',
				'selector' => '{{WRAPPER}} .ge-tabs--action.active:hover',
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
		include lisfinity_get_template_part( 'global-elements-tabs', 'shortcodes/elements', $args );
	}

}
