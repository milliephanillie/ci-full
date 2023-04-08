<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Title_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'title';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Title', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-font';
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
		// Category feeds.
		$this->start_controls_section(
			'title',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// control | heading.
		$this->add_control(
			'heading',
			[
				'label'       => __( 'Heading', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lisfinity Title', 'lisfinity-core' ),
				'description' => __( 'Enter the heading for the title section.', 'lisfinity-core' ),
			]
		);

		// control | shadow different.
		$this->add_control(
			'shadow_different',
			[
				'label'        => __( 'Different Shadow Text?', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'Same as the Title', 'lisfinity-core' ),
				],
				'label_on'     => __( 'yes', 'lisfinity-core' ),
				'label_off'    => __( 'no', 'lisfinity-core' ),
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Toggle this option if the shadowed text should be different than the header.', 'lisfinity-core' ),
			]
		);

		// control | shadow.
		$this->add_control(
			'shadow_text',
			[
				'label'       => __( 'Heading Shadow Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lisfinity Title', 'lisfinity-core' ),
				'description' => __( 'Enter the shadow text for the title section.', 'lisfinity-core' ),
				'condition'   => [ 'shadow_different' => 'yes' ],
			]
		);

		// control | heading.
		$this->add_control(
			'shadow_color',
			[
				'label'       => __( 'Shadowed Text Color Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f6f6f6',
				'description' => __( 'Choose shadowed text style', 'lisfinity-core' ),
			]
		);

		// control | subheading.
		$this->add_control(
			'subheading',
			[
				'label'       => __( 'Subheading', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lisfinity Subtitle', 'lisfinity-core' ),
				'description' => __( 'Enter the subheading for the title section.', 'lisfinity-core' ),
			]
		);

		// control | link.
		$this->add_control(
			'linked',
			[
				'label'        => __( 'Enable Link', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'lisfinity-core' ),
				'label_off'    => __( 'No', 'lisfinity-core' ),
				'return_value' => 'yes',
				'description'  => __( 'Would you like to enable link at the title.', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'link_text',
			[
				'label'       => __( 'Link Text', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'All Links', 'lisfinity-core' ),
				'description' => __( 'Enter text for the link.', 'lisfinity-core' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'linked',
							'operator' => 'in',
							'value'    => [ 'yes' ],
						]
					]
				]
			]
		);

		$this->add_control(
			'link_url',
			[
				'label'       => __( 'Link URL', 'lisfinity-core' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Enter the url for the link.', 'lisfinity-core' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'linked',
							'operator' => 'in',
							'value'    => [ 'yes' ],
						]
					]
				]
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
			'settings'         => $settings,
			'heading'          => $settings['heading'],
			'shadow_different' => $settings['shadow_different'],
			'shadow_text'      => $settings['shadow_text'],
			'shadow_color'     => $settings['shadow_color'],
			'subheading'       => $settings['subheading'],
			'linked'           => $settings['linked'],
		];
		if ( ! empty( $args['linked'] ) ) {
			$args['link_text'] = $settings['link_text'];
			$args['link_url']  = $settings['link_url'];
		}
		include lisfinity_get_template_part( 'title', 'shortcodes', $args );
	}

}
