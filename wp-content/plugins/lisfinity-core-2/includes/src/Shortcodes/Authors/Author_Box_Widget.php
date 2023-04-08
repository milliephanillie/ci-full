<?php


namespace Lisfinity\Shortcodes\Authors;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Models\Users\ProfilesModel;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;

class Author_Box_Widget extends Shortcode {
	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'author-box';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Lisfinity Author Box', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-th-large';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity-authors-page' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		// Category feeds.
		$this->start_controls_section(
			'authors_box_content',
			[
				'label' => __( 'Display Boxes', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->display_element( 'display_promoted_authors', esc_html__( 'Display promoted authors', 'lisfinity-core' ), '' );
		$this->display_element( 'display_all_authors', esc_html__( 'Display all authors', 'lisfinity-core' ), '' );
		$this->display_element( 'display_handpicked_authors', esc_html__( 'Display handpicked authors', 'lisfinity-core' ), '' );
		$repeater       = new Repeater();
		$profiles_model = new ProfilesModel();
		$repeater->add_control(
			'profile_id',
			[
				'label'       => __( 'Profiles', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'options'     => lisfinity_format_post_select( [
					'post_type' => $profiles_model::$post_type_name,
				] ),
				'description' => __( 'Manually choose the profile that you wish to display.', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'profiles_handpicked',
			[
				'label'         => __( 'Handpick Posts', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Manually choose the profiles that you wish to display.', 'lisfinity-core' ),
				'title_field'   => __( 'Profile: {{{ profile_id }}}', 'lisfinity-core' ),
				'separator'     => 'before',
				'condition'     => [
					'display_handpicked_authors' => 'yes'
				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'author_box_wrapper_style',
			[
				'label' => __( 'Wrapper Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->wrapper_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'author_box_image_style',
			[
				'label' => __( 'Image Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->image_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'author_box_title_style',
			[
				'label' => __( 'Title Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->title_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'author_box_info_style',
			[
				'label' => __( 'Listing Info Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'author_box_info_tabs'
		);
		$this->start_controls_tab(
			'author_box_info_ratings',
			[
				'label' => __( 'Ratings', 'lisfinity-core' ),
			]
		);

		$this->author_box_info_ratings_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'author_box_info_location',
			[
				'label' => __( 'Location', 'lisfinity-core' ),
			]
		);

		$this->author_box_info_location_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function wrapper_style() {
		$this->set_background_color( 'author_box_wrapper_bg_color', 'rgba(255, 255, 255, 1)', esc_html__( 'Background Color', 'lisfinity-core' ), '.vendor' );
		$this->set_padding( 'author_box_wrapper_padding', '.vendor', '20', '15', '20', '20', 'false' );
		$this->set_margin( 'author_box_wrapper_margin', '.vendor', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'author_box_wrapper_border_radius', '3', '3', '3', '3', 'px', '.vendor' );

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Box_Shadow::get_type(),
			[
				'name'           => 'author_box_wrapper_box_shadow',
				'selector'       => '{{WRAPPER}} .vendor',
				'fields_options' =>
					[
						'box_shadow_type' =>
							[
								'default' => 'yes'
							],
						'box_shadow'      => [
							'default' =>
								[
									"horizontal" => 0,
									'vertical'   => 3,
									'blur'       => 8,
									'spread'     => 0,
									'color'      => 'rgba(239, 239, 239, 1)',
								]
						]
					]
			]
		);

		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'     => 'author_box_wrapper_border',
				'selector' => '{{WRAPPER}} .vendor',
			]
		);
	}

	public function image_style() {

		$this->set_width( 'author_box_image_width', '.vendor .profile--thumbnail', '90', 'px' );
		$this->set_height( 'author_box_image_height', '.vendor .profile--thumbnail', '84', 'px' );
		$this->set_background_color( 'author_box_image_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.vendor .profile--thumbnail' );
		$this->set_padding( 'author_box_image_padding', '.vendor .profile--thumbnail', '10', '10', '10', '10', true );
		$this->set_margin( 'author_box_image_margin', '.vendor .profile--thumbnail', '0', '20', '0', '0', true );
		$this->set_border_radius( 'author_box_image_border_radius', '20', '20', '20', '20', 'px', '.vendor .profile--thumbnail' );
		$this->add_group_control(
			Group_Control_Single_Product_Gallery_Border::get_type(),
			[
				'name'           => 'author_box_image_border',
				'selector'       => '{{WRAPPER}} .vendor .profile--thumbnail',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '6',
							'right'    => '6',
							'bottom'   => '6',
							'left'     => '6',
							'isLinked' => false,
						],
					],
					'color'  => [
						'default' => '#f6f6f6',
					],
				],
			]
		);
	}

	public function title_style() {
		$this->set_elements_alignment( 'author_box_title_alignment', 'left', '.vendor--content h6', false );
		$this->start_controls_tabs(
			'author_box_title_tabs',
			[
				'label' => __( 'Tabs', 'lisfinity-core' ),
			]
		);
		$this->start_controls_tab(
			'author_box_title_default_tab',
			[
				'label' => __( 'Default', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'author_box_title_typography',
				'selector'       => '{{WRAPPER}} .vendor--content h6 a',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'author_box_title_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.vendor--content h6 a' );
		$this->set_padding( 'author_box_title_padding', '.vendor--content h6 a', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'author_box_title_margin', '.vendor--content h6 a', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'author_box_title_border_radius', '0', '0', '0', '0', 'px', '.vendor--content h6 a' );

		$this->end_controls_tab();
		$this->start_controls_tab(
			'author_box_title_hover_tab',
			[
				'label' => __( 'On Hover', 'lisfinity-core' ),
			]
		);
		$this->add_group_control(
			Group_Control_Single_Product_Id_Typography::get_type(),
			[
				'name'           => 'author_box_title_hover_typography',
				'selector'       => '{{WRAPPER}} .vendor--content h6 a:hover',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'color'       => [
						'default' => 'rgba(0, 0, 0, 1)'
					],
					'font_size'   => [
						'default' =>
							[ 'size' => '16' ]
					],
					'font_weight' => [
						'default' => 400
					],
				],
			]
		);
		$this->set_background_color( 'author_box_title_hover_bg_color', 'transparent', esc_html__( 'Background Color', 'lisfinity-core' ), '.vendor--content h6:hover' );
		$this->set_padding( 'author_box_title_hover_padding', '.vendor--content h6:hover', '0', '0', '0', '0', 'false' );
		$this->set_margin( 'author_box_title_hover_margin', '.vendor--content h6:hover', '0', '0', '0', '0', 'false' );
		$this->set_border_radius( 'author_box_title_hover_border_radius', '0', '0', '0', '0', 'px', '.vendor--content h6:hover' );

		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	public function author_box_info_ratings_style() {
		$this->display_element( 'display_info_mark', 'Display Mark' );

		$this->add_control(
			'author_box_info_ratings_icon',
			[
				'label'     => __( 'Home Icon', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_icon_ratings',
			[
				'label'   => __( 'Different Home Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'icon_ratings',
			[
				'label'       => __( 'Home Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom home icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_icon_ratings' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_ratings',
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
					'{{WRAPPER}} .fill-product-star-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fill-product-star-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'icon_color_ratings',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ef4e4e',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .fill-product-star-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .fill-product-star-icon i'   => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);


		$this->set_background_color( 'author_box_info_ratings_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.lisfinity-product--info .bg-yellow-300' );

		$this->set_border_radius( 'author_box_info_ratings_border_radius', '50', '50', '50', '50', '%', '.lisfinity-product--info .bg-yellow-300' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'author_box_info_ratings_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.mr-22 .ml-6.text-sm ',
			]
		);
	}

	public function author_box_info_location_style() {
		$this->display_element( 'display_info_location', 'Display Location' );

		$this->add_control(
			'author_box_info_locations_icon',
			[
				'label'     => __( 'Home Icon', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'use_custom_icon_location',
			[
				'label'   => __( 'Different Home Icon?', 'lisfinity-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$this->add_control(
			'icon_location',
			[
				'label'       => __( 'Home Icon', 'lisfinity-core' ),
				'type'        => Controls_Manager::ICONS,
				'description' => __( 'Choose the custom home icon', 'lisfinity-core' ),
				'condition'   => [
					'use_custom_icon_location' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_locations',
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
					'{{WRAPPER}} .fill-product-place-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fill-product-place-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'icon_color_locations',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#05606e',
				'description' => __( 'Choose the icon color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .fill-product-place-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .fill-product-place-icon'   => 'color: {{VALUE}};',
				],
				'separator'   => 'after',
			]
		);

		$this->set_background_color( 'author_box_info_location_bg_color', 'rgba(193, 254, 246, 1)', 'Background color', '.lisfinity-product--info .bg-cyan-300' );

		$this->set_border_radius( 'author_box_info_location_border_radius', '50', '50', '50', '50', '%', '.lisfinity-product--info .bg-cyan-300' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'author_box_info_location_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info .ml-6.text-sm ',
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

		include lisfinity_get_template_part( 'author-box', 'shortcodes/authors', $args );
	}

}
