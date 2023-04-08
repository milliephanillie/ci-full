<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Partners_Widget extends Widget_Base {

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'partners';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Partners', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-users';
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
	 * !!! These are the old controls that we're keeping in case we'll
	 * need to use partners.php template again !!!
	 * ---------------------------------------------------------------
	 */
	protected function old_controls() {
		// image section.
		$this->start_controls_section(
			'promo_settings',
			[
				'label' => __( 'Promo Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'promo_image',
			[
				'label'         => __( 'Promo Background Image', 'lisfinity-core' ),
				'type'          => Controls_Manager::MEDIA,
				'prevent_empty' => true,
				'description'   => __( 'Choose background image for promo section', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->add_control(
			'promo_image_height',
			[
				'label'         => __( 'Promo Background Image Height', 'lisfinity-core' ),
				'type'          => Controls_Manager::NUMBER,
				'prevent_empty' => true,
				'description'   => __( 'Set the height for the background image or leave empty to match Partners wrapper height', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->add_control(
			'promo_link',
			[
				'label'         => __( 'Promo Link', 'lisfinity-core' ),
				'type'          => Controls_Manager::URL,
				'prevent_empty' => true,
				'description'   => __( 'Enter the url of the promo section.', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->end_controls_section();

		// Partners section.
		$this->start_controls_section(
			'partners_settings',
			[
				'label' => __( 'Partners Settings', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'partners_masonry',
			[
				'label'        => __( 'Use Masonry or Grid', 'lisfinity-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'yes' => __( 'Masonry', 'lisfinity-core' ),
					'no'  => __( 'Grid', 'lisfinity-core' ),
				],
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Choose if you wish to use Masonry or default Grid', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'partners_width',
			[
				'label'       => __( 'Partners Box Width', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Set default width of partners box', 'lisfinity-core' ),
				'default'     => 130,
				'condition'   => [
					'partners_masonry' => 'no',
				]
			]
		);

		// control | handpick.
		$repeater = new Repeater();
		$repeater->add_control(
			'partner_name',
			[
				'label'       => __( 'Partner Name', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter the name of the partner', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'partner_image',
			[
				'label'       => __( 'Partner Image', 'lisfinity-core' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Upload the image for the partner', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'partner_url',
			[
				'label'       => __( 'Partner Website', 'lisfinity-core' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Enter the link to the partner website', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'partners',
			[
				'label'         => __( 'Partners', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'description'   => __( 'Set up partners that you wish to display', 'lisfinity-core' ),
//				'title_field'   => __( 'Partner: {{{ partner_name }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'title',
			[
				'label' => __( 'Title', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_enable',
			[
				'label'        => __( 'Enable Title', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Enable', 'lisfinity-core' ),
					'no'  => __( 'Disable', 'lisfinity-core' ),
				],
				'label_on'     => __( 'yes', 'lisfinity-core' ),
				'label_off'    => __( 'no', 'lisfinity-core' ),
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Set to yes if you wish to enable title option.', 'lisfinity-core' ),
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

		$this->end_controls_section();
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
			'partners_number',
			[
				'label'       => __( 'Partners To Display', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Set the number of partners boxes you wish to be displayed. Set some huge amount if you wish to display them all.', 'lisfinity-core' ),
				'default'     => 999,
			]
		);

		$this->add_control(
			'partners_randomize',
			[
				'label'       => __( 'Randomize Parnters?', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Choose if you wish to randomize the displayed partners on each load', 'lisfinity-core' ),
				'options'     => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'No', 'lisfinity-core' ),
				],
				'default'     => 'no',
			]
		);

		// control | handpick.
		$repeater = new Repeater();
		$repeater->add_control(
			'partner_name',
			[
				'label'       => __( 'Partner Name', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter the name of the partner', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'partner_image',
			[
				'label'       => __( 'Partner Image', 'lisfinity-core' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Upload the image for the partner', 'lisfinity-core' ),
			]
		);
		$repeater->add_control(
			'partner_url',
			[
				'label'       => __( 'Partner Website', 'lisfinity-core' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Enter the link to the partner website', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'partners',
			[
				'label'         => __( 'Partners', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'description'   => __( 'Set up partners that you wish to display', 'lisfinity-core' ),
//				'title_field'   => __( 'Partner: {{{ partner_name }}}', 'lisfinity-core' ),
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
		if ( $settings['partners_number'] < count( $settings['partners'] ) ) {
			$settings['partners'] = array_slice( $settings['partners'], $settings['partners_number'] );
		}
		include lisfinity_get_template_part( 'partners-simple', 'shortcodes/partners', $args );
	}

}
