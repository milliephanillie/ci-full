<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

class HowItWorks_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'how_it_works';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s How it Works', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-cogs';
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
		// Partners section.
		$this->start_controls_section(
			'partners_settings',
			[
				'label' => __( 'Partners Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'items_style',
			[
				'label'       => __( 'Display Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'cascade' => __( 'Cascade', 'lisfinity-core' ),
					'grid'    => __( 'Grid', 'lisfinity-core' ),
				],
				'default'     => 'cascade',
				'description' => __( 'Choose the style type of the items', 'lisfinity-core' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'gap',
			[
				'label'       => __( 'Items Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'   => [
					'{{WRAPPER}} .hiw--row'     => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hiw--wrapper' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// control | handpick.
		$repeater = new Repeater();
		$repeater->add_control(
			'background_color',
			[
				'label'       => __( 'Item Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#ffffff',
				'description' => __( 'Choose the background color of the item box', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'image',
			[
				'label'       => __( 'Image', 'lisfinity-core' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Upload the image for the item', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Register FREE', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the item', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'title_color',
			[
				'label'       => __( 'Title Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#2d2d2d',
				'description' => __( 'Choose the color of the title', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'description',
			[
				'label'       => __( 'Description', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Register your account with us completely FREE', 'lisfinity-core' ),
				'description' => __( 'Enter the description of the item', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'description_color',
			[
				'label'       => __( 'Description Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#686868',
				'description' => __( 'Choose the color of the description', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'count',
			[
				'label'        => __( 'Display Items Count', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Display', 'lisfinity-core' ),
					'no'  => __( 'Hide', 'lisfinity-core' ),
				],
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'Choose if you wish to display items count in the box', 'lisfinity-core' ),
				'separator'    => 'before',
			]
		);
		$repeater->add_control(
			'count_color',
			[
				'label'       => __( 'Count Number Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#efefef',
				'description' => __( 'Choose the color of the description', 'lisfinity-core' ),
				'condition'   => [
					'count' => 'yes',
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label'         => __( 'How it Works', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'description'   => __( 'Set up how it works items that you wish to display', 'lisfinity-core' ),
				'title_field'   => sprintf( __( 'Item: %s', 'lisfinity-core' ), '{{{ title }}}'),
				'separator'     => 'before',
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
		include lisfinity_get_template_part( 'how-it-works', 'shortcodes/how-it-works', $args );
	}

}
