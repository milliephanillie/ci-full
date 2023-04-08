<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\PostModel;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Content_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Date_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Post_Category_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Title_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profiles_Title_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;

class Posts_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'lisfinity-posts';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Posts', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-address-card-o';
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
			'posts_feed',
			[
				'label' => __( 'Posts Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | template.
		$this->add_control(
			'template',
			[
				'label'       => __( 'Template', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'options'     => [
					'grid'     => __( 'Grid', 'lisfinity-core' ),
					'carousel' => __( 'Carousel', 'lisfinity-core' ),
				],
				'default'     => 'grid',
				'description' => __( 'Choose the post template that you wish to use between grid or carousel.', 'lisfinity-core' ),
			]
		);

		// control | number of posts.
		$this->add_control(
			'number',
			[
				'label'       => __( 'Number of Posts to Show', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 3,
				'default'     => 3,
				'description' => __( 'Choose the number of posts that will be displayed.', 'lisfinity-core' ),
			]
		);

		// control | handpick.
		$repeater = new Repeater();
		$repeater->add_control(
			'post_id',
			[
				'label'       => __( 'Posts', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => false,
				'options'     => lisfinity_format_post_select(),
				'description' => __( 'Manually choose the post that you wish to display.', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'posts_handpicked',
			[
				'label'         => __( 'Handpick Posts', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Manually choose the posts that you wish to display.', 'lisfinity-core' ),
				'title_field'   => __( 'Post: {{{ post_id }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		// control | order of the posts.
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
				'description' => __( 'Choose order of the posts', 'lisfinity-core' ),
			]
		);

		// control | order of the posts.
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
				'description' => __( 'Choose sorting of the posts', 'lisfinity-core' ),
			]
		);

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'posts_layout',
			[
				'label' => __( 'Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Layout
		$this->posts_layout();

		$this->end_controls_section();

		// Category styles.
		$this->start_controls_section(
			'categories_styles',
			[
				'label' => __( 'Image Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Image overlay
		$this->image_style();

		$this->end_controls_section();

		// Post Category styles.
		$this->start_controls_section(
			'post_categories_styles',
			[
				'label' => __( 'Post Category Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Post Category
		$this->post_category_style();

		$this->end_controls_section();

		// Post Title styles.
		$this->start_controls_section(
			'post_title_styles',
			[
				'label' => __( 'Post Title Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Style | Image overlay
		$this->post_title_style();

		$this->end_controls_section();

		// Post Content styles.
		$this->start_controls_section(
			'post_content_styles',
			[
				'label' => __( 'Post Content Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->post_content_style();

		$this->end_controls_section();

		// Post Author styles.
		$this->start_controls_section(
			'post_author_styles',
			[
				'label' => __( 'Post Author Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->post_author_style();

		$this->end_controls_section();
	}

	/*
	 * Posts layout settings.
	 * -------------------------
	 */
	public function posts_layout() {

		$this->add_group_control(
			Group_Control_Profile_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_posts',
				'selector' => '{{WRAPPER}} .post',
			]
		);

		$this->add_group_control(
			Group_Control_Profile_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_posts_hover',
				'selector' => '{{WRAPPER}} .post:hover',
				'label' => 'Box Shadow on Hover'
			]
		);

		$this->add_responsive_control(
			'posts_columns',
			[
				'label'       => __( 'Break Posts Into Columns', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 1,
				'max'         => 6,
				'description' => __( 'Choose the number of columns you wish to break posts boxes', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .posts .container .row .px-col' => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'posts-columns-gap',
			[
				'label'       => __( 'Post Columns Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'   => [
					'{{WRAPPER}} .posts .container .row .px-col' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .posts .container .row'         => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'posts-columns-gap-y',
			[
				'label'       => __( 'Post Columns Gap Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors'   => [
					'{{WRAPPER}} .posts .container .row .px-col .post' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	/*
	 * Posts image settings.
	 * -------------------------
	 */
	public function image_style() {

		$this->display_element('display_image', esc_html__('Display', 'lisfinity-core'));
		$this->image_overlay();

		$this->elements_width('image_width_id', '100', '%', '.post .post--image');

		$this->elements_height('image_height_id', '260', 'px', '.post .post--image');

		$this->set_heading_section('image_position_heading', esc_html__('Position', 'lisfinity-core'), 'image_position_hr');

		$this->add_control(
			'image_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post .post--image' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'image_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post .post--image' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_responsive_control(
			'image_position_post',
			[
				'label'       => __( 'Image Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'options'     => [
					'column'         => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row'            => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column-reverse' => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'row-reverse'    => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'column',
				'selectors'   => [
					'{{WRAPPER}} .post' => 'display: flex; flex-direction: {{VALUE}}',
				],
			]
		);

		$this->set_heading_section('image_other_heading', 'Other', 'image_other_hr');

		$this->set_border_radius('image_border_radius', '3', '3', '0', '0', 'px', esc_html__('Border Radius', 'lisfinity-core'), '.post .post--image img');

		$this->set_margin('image_margin', '.post .post--image', '0', '0', '0', '0');
	}

	/*
	 * Post Title settings.
	 * -------------------------
	 */
	public function post_title_style() {

		$this->display_element('display_post_title', esc_html__('Display', 'lisfinity-core'));

		$this->set_text_color('color_post_title', esc_html__('Color', 'lisfinity-core'), '#2d2d2d', '.post--content .font-semibold');

		$this->add_group_control(
			Group_Control_Profiles_Title_Typography::get_type(),
			[
				'name'     => 'post_title_typography',
				'selector' => '{{WRAPPER}} .post--content .font-semibold',
			]
		);

		$this->text_align('title_align_id', 'left', '.post--content .font-semibold');

		$this->set_heading_section('post_title_position_heading', esc_html__('Position', 'lisfinity-core'), 'post_title_position_hr');

		$this->add_control(
			'post_title_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--content .font-semibold' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'post_title_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--content .font-semibold' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

	}

	/*
	 * Post Category settings.
	 * -------------------------
	 */
	public function post_category_style() {

		$this->display_element('display_post_category', esc_html__('Display', 'lisfinity-core'));

		$this->set_text_color('color_post_category', esc_html__('Color', 'lisfinity-core'), '#7f7f7f', '.post--category .text-grey-600');

		$this->add_group_control(
			Group_Control_Testimonials_Content_Typography::get_type(),
			[
				'name'     => 'proct_category_typography',
				'selector' => '{{WRAPPER}} .post--category',
			]
		);

		$this->set_heading_section('post_category_position_heading', esc_html__('Position', 'lisfinity-core'), 'post_category_position_hr');

		$this->add_control(
			'post_category_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--category .text-grey-600' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'post_category_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--category .text-grey-600' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

	}

	/*
	 * Post Content settings.
	 * -------------------------
	 */
	public function post_content_style() {

		$this->display_element('display_post_content', esc_html__('Display', 'lisfinity-core'));

		$this->set_text_color('color_post_content', esc_html__('Color', 'lisfinity-core'), '#959595', '.post--content-excerpt');

		$this->add_group_control(
			Group_Control_Testimonials_Content_Typography::get_type(),
			[
				'name'     => 'post_content_typography',
				'selector' => '{{WRAPPER}} .post--content-excerpt',
			]
		);

		$this->text_align('content_align_id', 'left', '.post--content-excerpt');

		$this->set_padding('content_padding', '.post--content', '32', '30', '32', '30', false );

		$this->set_heading_section('post_content_position_heading', esc_html__('Position', 'lisfinity-core'), 'post_content_position_hr');

		$this->add_control(
			'post_content_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--content-excerpt' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'post_content_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post--content-excerpt' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

	}

	/*
	 * Post Author settings.
	 * -------------------------
	 */
	public function post_author_style() {

		$this->display_element('display_author', esc_html__('Display', 'lisfinity-core'));

		$this->set_heading_section('id_text_date_author_posts', esc_html__('Set date style','lisfinity-core'), 'id_text_date_hr_posts');


		$this->set_text_color('posts_author_date_color', esc_html__('Set the color of the date','lisfinity-core'), 'rgba(149, 149, 149, 1)', '.post--author .posted-on');

		$this->add_group_control(
			Group_Control_Testimonials_Year_Typography::get_type(),
			[
				'name'     => 'posts_date_typography',
				'selector' => '{{WRAPPER}} .post--author .posted-on',
			]
		);

		$this->set_heading_section('id_text_testimonials', esc_html__('Set author name style','lisfinity-core'), 'id_text_hr_testimonials');

		$this->add_group_control(
			Group_Control_Testimonials_Author_Typography::get_type(),
			[
				'name'     => 'posts_author_text_typography',
				'selector' => '{{WRAPPER}} .post--author .flex span:first-child',
			]
		);

		$this->set_text_color('posts_author_text_color', esc_html__('Set the color of the author name','lisfinity-core'), 'rgba(0, 0, 0, 1)', '.post--author .flex span:first-child');

		$this->display_element('display_author_image', esc_html__('Display image', 'lisfinity-core'));

		$this->add_control(
			'text_align_author',
			[
				'label'       => __( 'Set alignment of the content', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'flex-start'   => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'  => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => 'flex-start',
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .post--author' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->set_heading_section('id_author_positioning_posts', esc_html__('Set position','lisfinity-core'), 'id_author_positioning_hr_posts');

		$this->add_control(
			'post_author_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post .post--meta' => 'right: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'post_author_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .post .post--meta' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

	}

	/*
	 * Functions.
	 * -------------------------
	 */
	public function image_overlay(){
		$this->add_control(
			'overlay',
			[
				'label'       => __( 'Images Overlay', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Set the overlay for the category types background images.', 'lisfinity-core' ),
			]
		);
}

	public function text_align($id, $default, $selector) {
		$this->add_control(
			$id,
			[
				'label'       => __( 'Set alignment of the text', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => $default,
				'toggle'      => true,
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'text-align: {{VALUE}};',
				],
			]
		);
	}

	public function display_element( $id, $message ) {
		$this->add_control(
			$id,
			[
				'label'        => __( $message, 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
	}

	public function set_text_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $message, 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
				]
			]
		);
	}

	public function set_margin($id, $selector, $default_top, $default_right, $default_bottom, $default_left) {
		$this->add_responsive_control(
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
					'isLinked' => false,
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

	public function elements_width($id_element, $default_size, $default_unit, $selector) {
		$this->add_control(
			$id_element,

			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%'],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => $default_unit,
					'size' => $default_size,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'plugin-name' ),
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

	public function elements_height($id_element, $default_size, $default_unit, $selector) {
		$this->add_control(
			$id_element,

			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%'],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 990,
					],
				],
				'default'     => [
					'unit' => $default_unit,
					'size' => $default_size,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector ) {
		$this->add_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			$id_y,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}};',
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
				],
			]
		);
	}


	/**
	 * Get the arguments for the posts wp query
	 * -------------------------------------------
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	protected function get_post_args( $settings ) {
		$args = [
			'post_status'         => 'publish',
			'posts_per_page'      => $settings['number'],
			'ignore_sticky_posts' => 1,
		];

		// add handpicked posts to query args.
		if ( ! empty ( $settings['posts_handpicked'] ) ) {
			$handpicked = [];
			foreach ( $settings['posts_handpicked'] as $post_handpicked ) {
				if ( ! empty( $post_handpicked['post_id'] ) ) {
					$handpicked[] = $post_handpicked['post_id'];
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

		$posts = new \WP_Query( $args );

		$args = [
			'settings' => $settings,
			'posts'    => $posts,
		];
		include lisfinity_get_template_part( 'posts', 'shortcodes', $args );
	}

}
