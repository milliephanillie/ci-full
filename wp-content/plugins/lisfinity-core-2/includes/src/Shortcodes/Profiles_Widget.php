<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\PostModel;
use Lisfinity\Models\Users\ProfilesModel;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Link_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profiles_Text_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profiles_Title_Typography;

class Profiles_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'profiles';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Profiles', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-opencart';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'basic', 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'profiles_feed',
			[
				'label' => __( 'Profiles Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | number of profiles.
		$this->add_control(
			'number',
			[
				'label'       => __( 'Number of Profiles to Show', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 3,
				'default'     => 3,
				'description' => __( 'Choose the number of profiles that will be displayed.', 'lisfinity-core' ),
			]
		);

		// control | handpick.
		$repeater       = new Repeater();
		$profiles_model = new ProfilesModel();
		$repeater->add_control(
			'profile_id',
			[
				'label'       => __( 'Profiles', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
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
			]
		);

		// control | order of the profiles.
		$this->add_control(
			'order',
			[
				'label'       => __( 'Posts Order', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'asc'  => __( 'Ascending', 'lisfinity-core' ),
					'desc' => __( 'Descending', 'lisfinity-core' ),
				],
				'default'     => 'asc',
				'description' => __( 'Choose order of the profiles', 'lisfinity-core' ),
			]
		);

		// control | order of the profiles.
		$this->add_control(
			'orderby',
			[
				'label'       => __( 'Posts Sorting', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'date'  => __( 'Date', 'lisfinity-core' ),
					'title' => __( 'Name', 'lisfinity-core' ),
				],
				'default'     => 'date',
				'description' => __( 'Choose sorting of the profiles', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'categories_styles',
			[
				'label' => __( 'Profile Types Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);



		// Style | Image overlay
		$this->add_control(
			'style',
			[
				'label'       => __( 'Template Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'1' => __( 'Style 1', 'lisfinity-core' ),
					'2' => __( 'Style 2', 'lisfinity-core' ),
				],
				'default'     => '1',
				'description' => __( 'Choose taxonomies display style.', 'lisfinity-core' ),
			]
		);

		$this->break_profiles_into_columns();

		$this->end_controls_section();


		// profiles box settings.
		$this->start_controls_section(
			'box_settings_profile',
			[
				'label' =>__('Box Settings', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this-> box_styling();

		$this->end_controls_section();

		// Style |  Profile Header.

		$this->start_controls_section(
			'profile_header_style',
			[
				'label' =>__('Profile Header Style', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style' => '1',
				],
			]
		);
		$this->start_controls_tabs(
			'profile_header_tabs'
		);
		$this->start_controls_tab(
			'profiles_rating',
			[
				'label' => __( 'Rating Icon', 'lisfinity-core' ),
			]
		);


		$this->profiles_rating_icon_style();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'profiles_logo',
			[
				'label' => __( 'Logo', 'lisfinity-core' ),
			]
		);

		$this->profiles_logo_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Style |  Logo.

		$this->start_controls_section(
			'profiles_logo_2',
			[
				'label' => __( 'Logo', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style' => '2',
				],
			]
		);


		$this->profiles_logo_2_style();
		$this->end_controls_section();

		// Style |  Title.

		$this->start_controls_section(
			'profiles_title',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->profiles_title_style();
		$this->end_controls_section();

		// Style |  Text.

		$this->start_controls_section(
			'profiles_text',
			[
				'label' => __( 'Text', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->profiles_text_style();
		$this->end_controls_section();


		// Style |  Link.

		$this->start_controls_section(
			'profiles_link',
			[
				'label' => __( 'Link', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '1'
				]
			]
		);


		$this->profiles_link_style();
		$this->end_controls_section();

		// Style |  Profile Header.

		$this->start_controls_section(
			'profile_footer_style',
			[
				'label' =>__('Profile Footer Style', 'lisfinity-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style' => '2',
				],
			]
		);

		$this->set_padding('id_footer_padding', '.profile--footer', '0', '0', '0', '0', 'true');

		$this->set_margin('id_footer_margin', '.profile--footer', '24', '0', '0', '0', 'true');

		$this->set_heading_section('id_footer_heading', 'Set footer elements', 'id_footer_hr');

		$this->start_controls_tabs(
			'profile_footer_tabs'
		);
		$this->start_controls_tab(
			'profiles_footer_rating',
			[
				'label' => __( 'Rating Icon', 'lisfinity-core' ),
			]
		);


		$this->profiles_rating_icon_style_two();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'profiles_footer_link',
			[
				'label' => __( 'Link', 'lisfinity-core' ),
			]
		);

		$this->profiles_link_style_two_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();


	}


	/*
	 * Profiles title settings.
	 * -------------------------
	 */

	public function profiles_title_style() {

		$this->add_group_control(
			Group_Control_Profiles_Title_Typography::get_type(),
			[
				'name'     => 'profiles_title_typography',
				'selector' => '{{WRAPPER}} .profile--content .text-lg',
			]
		);

		$this->set_heading_section('id_title_color_heading', 'Title color', 'id_title_color_hr');

		$this->set_text_color('profiles_title_text_color_id', 'Text color', 'rgba(0, 0, 0, 1)', '.profile--content .text-lg');

		$this->set_text_color('profiles_title_text_color_on_hover_id', 'Text color on the hover', 'rgba(0, 0, 0, 1)', '.profile--content .text-lg:hover');

		$this->set_text_alignment('profiles_title_alignment_id', 'Set alignment of the title', 'left', '.profile--content .text-lg');

		$this->set_padding('id_title_padding', '.profile--content .text-lg', '0', '0', '0', '0', 'true');

		$this->set_margin('id_title_margin', '.profile--content .text-lg', '0', '0', '0', '0', 'true');



	}


	public function break_profiles_into_columns() {

		$this->set_columns_breakage('profiles_columns_id', '3', '.profiles .px-col.mt-86', '1');

		$this->set_columns_gap('profiles_columns_gap', '16', '.profiles .px-col.mt-86', '1');

		$this->set_columns_breakage('profiles_columns_id_two', '5', '.profiles .px-col.mt-24', '2');

		$this->set_columns_gap('profiles_columns_gap_two', '16', '.profiles .px-col.mt-24', '2');

	}

	/*
	 * Profiles text settings.
	 * -------------------------
	 */

	public function profiles_text_style() {

		$this->add_group_control(
			Group_Control_Profiles_Text_Typography::get_type(),
			[
				'name'     => 'profiles_text_typography',
				'selector' => '{{WRAPPER}} .profile--content .mt-6.text-grey-700',
			]
		);

		$this->set_text_color('profiles_text_color_id', 'Text color', 'rgba(104, 104, 104, 1)', '.profile--content .mt-6.text-grey-700');

		$this->set_padding('id_text_padding', '.profile--content .mt-6.text-grey-700', '0', '0', '0', '0', 'true');

		$this->set_margin('id_text_margin', '.profile--content .mt-6.text-grey-700', '6', '0', '0', '0', 'true');

		$this->set_text_alignment('profiles_text_alignment_id', 'Set alignment of the text', 'left', '.profile--content .mt-6.text-grey-700');


	}


	/**
	 * Profiles rating icon settings.
	 * ----------------------
	 */
	public function profiles_rating_icon_style() {

		$this->set_heading_section('id_icon_style_heading', 'Set style', 'id_icon_style_hr');

		$this->set_background_color('profiles_rating_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.profile--rating .lisfinity-product--info .flex-center');

		$this->set_border_radius('profiles_rating_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.profile--rating .lisfinity-product--info .flex-center');

		$this->set_icon_color('profiles_rating_icon_color', 'Icon Color', 'rgba(203, 110, 23, 1)', '.profile--rating .lisfinity-product--info .flex-center svg');

		$this->set_icon_size('profiles_rating_icon_size', '14', '.profile--rating .lisfinity-product--info .flex-center svg');

		$this->set_text_color('profiles_rating_text_color', 'Set the color of the text', 'rgba(127, 127, 127, 1)', '.profile--rating .lisfinity-product--info.flex-center .ml-6.text-sm.text-grey-600');


		$this->add_group_control(
			Group_Control_Profile_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'profile_info_location_typography',
				'selector' => '{{WRAPPER}} .profile--rating .lisfinity-product--info.flex-center .ml-6.text-sm.text-grey-600',
			]
		);

		$this->set_heading_section('id_icon_positioning_heading', 'Set position', 'id_icon_positioning_hr');

		$this->add_responsive_control(
			'profiles_icon_alignment_id',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .profile--rating' => 'position: relative;',
				]
			]
		);


		$this->set_element_position('profiles_icon_alignment_x', '0', '', 'profiles_icon_alignment_y', '0', '.profile--rating', 'profiles_icon_alignment_id');


	}

	/**
	 * Profiles rating icon settings.
	 * ----------------------
	 */
	public function profiles_rating_icon_style_two() {

		$this->set_heading_section('id_icon_style_two_heading', 'Set style', 'id_icon_style_two_hr');

		$this->set_background_color('profiles_rating_two_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.profile--rating .lisfinity-product--info .flex-center');

		$this->set_border_radius('profiles_rating_two_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.profile--rating .lisfinity-product--info .flex-center');

		$this->set_icon_color('profiles_rating_two_icon_color', 'Icon Color', 'rgba(203, 110, 23, 1)', '.profile--rating .lisfinity-product--info .flex-center svg');

		$this->set_icon_size('profiles_rating_two_icon_size', '14', '.profile--rating .lisfinity-product--info .flex-center svg');

		$this->set_text_color('profiles_rating_two_text_color', 'Set the color of the text', 'rgba(127, 127, 127, 1)', '.profile--rating .lisfinity-product--info.flex-center .ml-6.text-sm.text-grey-600');


		$this->add_group_control(
			Group_Control_Profile_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'profile_info_location_two_typography',
				'selector' => '{{WRAPPER}} .profile--rating .lisfinity-product--info.flex-center .ml-6.text-sm.text-grey-600',
			]
		);

		$this->set_heading_section('id_icon_positioning_two_heading', 'Set position', 'id_icon_two_positioning_hr');

		$this->add_control(
			'profiles_icon_two_alignment_id',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .profile--rating' => 'position: relative;',
				]
			]
		);


		$this->set_element_position('profiles_icon_alignament_x_two', '0', '', 'profiles_icon_alignament_y_two', '0', '.profile--rating', 'profiles_icon_two_alignment_id');


	}

	/**
	 * * Profiles link settings.
	 * -------------------------
	 */

	public function profiles_link_style() {

		$this->add_group_control(
			Group_Control_Profile_Link_Typography::get_type(),
			[
				'name'     => 'profile_link_typography',
				'selector' => '{{WRAPPER}} .profile--link .flex',
			]
		);

		$this->set_text_color('profiles_link_text_color_id', 'Text color', 'rgba(188, 188, 188, 1)', '.profile--link .flex');

		$this->set_text_color('profiles_link_text_color_on_hover_id', 'Text color on hover', 'rgba(188, 188, 188, 1)', '.profile--link .flex:hover');

		$this->set_icon_color('profiles_link_icon_color_id', 'Icon color', 'rgba(188, 188, 188, 1)', '.profile--link .flex svg');
		$this->set_icon_color('profiles_link_icon_color_on_hover_id', 'Icon color on hover', 'rgba(188, 188, 188, 1)', '.profile--link .flex svg:hover');

		$this->set_icon_size('profiles_link_icon_size_id', '24', '.profile--link .flex svg');

		$this->set_flex_alignment('profiles_link_alignment_id', 'Align Link', 'flex-end', '.profile--link .flex', '1');

	}

	/**
	 * * Profiles link 2 settings.
	 * -------------------------
	 */

	public function profiles_link_style_two_style() {

		$this->set_heading_section('id_link_style_two_heading', 'Set style', 'id_link_style_two_hr');

		$this->add_group_control(
			Group_Control_Profile_Link_Typography::get_type(),
			[
				'name'     => 'profile_link_style_two_typography',
				'selector' => '{{WRAPPER}} .profile--link .flex',
			]
		);

		$this->set_text_color('profiles_link_style_two_text_color_id', 'Text color', 'rgba(188, 188, 188, 1)', '.profile--link .flex');

		$this->set_text_color('profiles_link_style_two_text_color_on_hover_id', 'Text color on hover', 'rgba(188, 188, 188, 1)', '.profile--link .flex:hover');

		$this->set_icon_color('profiles_link_style_two_icon_color_id', 'Icon color', 'rgba(188, 188, 188, 1)', '.profile--link .flex svg');
		$this->set_icon_color('profiles_link_style_two_icon_color_on_hover_id', 'Icon color on hover', 'rgba(188, 188, 188, 1)', '.profile--link .flex svg:hover');

		$this->set_icon_size('profiles_link_style_two_icon_size_id', '24', '.profile--link .flex svg');

		$this->set_heading_section('id_link_positioning_two_heading', 'Set position', 'id_link_two_positioning_hr');

		$this->add_control(
			'profiles_link_two_alignment_id',
			[
				'label' => __( 'Set Position of the element', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .profile--link' => 'position: relative;',
				]
			]
		);


		$this->set_element_position('profiles_link_alignament_x_two', '0', '', 'profiles_link_alignament_y_two', '0', '.profile--link', 'profiles_link_two_alignment_id');


	}

	/**
	 * * Profiles logo settings.
	 * -------------------------
	 */

	public function profiles_logo_style() {

		$this->set_heading_section('id_logo_positioning_heading', 'Set position', 'id_logo_positioning_hr');

		$this->add_control(
			'profiles_logo_alignment_id',
			[
				'label' => __( 'Set Position of the logo', 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .profile--header' => 'position: relative;',

				]
			]
		);


		$this->set_element_position('profiles_logo_alignment_x', '20', "'{{WRAPPER}} .profile--logo' => 'position: absolute; height: 86px;'", 'profiles_logo_alignment_y', '20', '.profile--logo', 'profiles_logo_alignment_id');

		$this->add_control(
			'logo_size_id',

			[
				'label'       => __( 'Logo Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'size' => '100',
					'unit' => '%',
				],
				'selectors'   => [
					'{{WRAPPER}} .profile--logo .rounded-full' => 'width: {{SIZE}}{{UNIT}}; height: 100%!important;',
				],
			]
		);
	}


	/**
	 * * Profiles logo settings.
	 * -------------------------
	 */

	public function profiles_logo_2_style() {


		$this->add_control(
			'logo_2_size_id',

			[
				'label'       => __( 'Logo Size', 'lisfinity-core' ),
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
					'size' => '192',
					'unit' => 'px',
				],
				'selectors'   => [
					'{{WRAPPER}} .profile--logo' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_flex_alignment('profiles_logo_alignment_two_id', 'Align Logo', 'center', '.profile--header' , '2');
	}


	/**
	 * *Profiles box styling
	 * -------------------------
	 */


	public function box_styling() {
		$this->add_group_control(
			Group_Control_Profile_Box_Shadow::get_type(),
			[
				'name'     => 'profiles_border_box',
				'selector' => '{{WRAPPER}} .profile',
			]
		);

		$this->set_background_color('box_background_color', 'rgba(255, 255, 255, 1)', 'Background Color', '.profile');


		$this->set_border_radius('box_border_radius', '3', '3', '3', '3', 'px', 'Border radius', '.profile');

	}



	/**
	 * * functions
	 * -------------------------
	 */

	public function display_element($id, $message) {
		$this->add_control(
			$id,
			[
				'label' => __( $message, 'lisfinity-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lisfinity-core' ),
				'label_off' => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
	}

	public function set_text_color($id, $message, $default, $selector) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
				]
			]
		);
	}

	public function set_text_size($id, $default, $selector) {
		$this->add_control(
			$id,

			[
				'label'       => __( 'Text Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
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

	public function set_heading_section($id, $heading, $hr_id) {
		$this->add_control(
			$id,
			[
				'label' => __( $heading, 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
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

	public function set_background_color($id, $default_color, $message, $selector) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};',
				],
			]
		);
	}

	public function sort_elements($id, $description, $order_number, $selector) {
		$this->add_control(
			$id,
			[
				'label' => __( $description, 'lisfinity-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => $order_number,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
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

	public function set_icon_color($id, $message, $default, $selector)  {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'fill:{{VALUE}};',
				]
			]
		);
	}

	public function set_icon_size($id, $default, $selector) {
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
						'max' => 30,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_element_position($id_x, $default_x, $selectorWrapper, $id_y, $default_y, $selector, $condition) {
		$this->add_responsive_control(
			$id_x,

			[
				'label'       => __( 'Horizontal', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => -350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector"=> 'right: {{SIZE}}{{UNIT}};',
					$selectorWrapper
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			$id_y,

			[
				'label' => __( 'Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => -350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector"=> 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);
	}

	public function set_padding($id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean) {

		$this->add_control(
			$id,
			[
				'label'      => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_margin($id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean) {

		$this->add_control(
			$id,
			[
				'label'      => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					"{{WRAPPER}} $selector" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				]
			]
		);
	}

	public function set_text_alignment($id, $message, $default, $selector) {
		$this->add_control(
			$id,
			[
				'label' => __($message, 'lisfinity-core'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => $default,
				'toggle' => true,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'text-align: {{VALUE}};',
				],
			]
		);
	}

	public function set_flex_alignment($id, $message, $default, $selector, $condition) {
		$this->add_control(
			$id,
			[
				'label' => __($message, 'lisfinity-core'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => $default,
				'toggle' => true,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'justify-content: {{VALUE}};',
				],
				'condition'   => [
					'style' => $condition,
				],
			]
		);
	}

	public function set_columns_breakage($columns_id, $default_number_of_column, $selector, $condition)
	{

		$this->add_responsive_control(
			$columns_id,
			[
				'label' => __('Break Profiles Into Columns', 'lisfinity-core'),
				'label_block' => true,
				'type' => Controls_Manager::NUMBER,
				'default' => $default_number_of_column,
				'min' => 1,
				'max' => 9,
				'description' => __('Choose the number of columns you wish to break profiles', 'lisfinity-core'),
				'selectors' => [
					"{{WRAPPER}} $selector" => 'width: calc(100% / {{VALUE}});',
				],
				'condition' => [
					'style' => $condition
				]
			]
		);
	}
	public function set_columns_gap($columns_gap_id, $default_gap, $selector, $condition) {
		$this->add_responsive_control(
			$columns_gap_id,
			[
				'label'       => __( 'Profiles Columns Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_gap,
				],
				'description' => __( 'Choose the number of columns gap', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'style' => $condition
				]
			]
		);
	}



	/**
	 * Get the arguments for the profiles wp query
	 * -------------------------------------------
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	protected function get_post_args( $settings ) {
		$profiles_model = new ProfilesModel();
		$args           = [
			'post_status'    => 'publish',
			'post_type'      => $profiles_model::$post_type_name,
			'posts_per_page' => $settings['number'],
		];

		// add handpicked profiles to query args.
		if ( ! empty ( $settings['profiles_handpicked'] ) ) {
			$handpicked = [];
			foreach ( $settings['profiles_handpicked'] as $post_handpicked ) {
				if ( ! empty( $post_handpicked['profile_id'] ) ) {
					$handpicked[] = $post_handpicked['profile_id'];
				}
			}
			if ( ! empty( $handpicked ) ) {
				$args['post__in'] = $handpicked;
			}
		}

		// add order to query args.
		if ( ! empty( $settings['order'] ) ) {
			$args['order'] = $settings['order'];
		}

		// add sorting to query args.
		if ( ! empty( $settings['orderby'] ) ) {
			$args['orderby'] = $settings['orderby'];
		}

		return $args;
	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args     = $this->get_post_args( $settings );

		$profiles = new \WP_Query( $args );

		$args = [
			'settings' => $settings,
			'profiles' => $profiles,
		];
		include lisfinity_get_template_part( "profiles-style-{$args['settings']['style']}", 'shortcodes/profiles', $args );
	}

}
