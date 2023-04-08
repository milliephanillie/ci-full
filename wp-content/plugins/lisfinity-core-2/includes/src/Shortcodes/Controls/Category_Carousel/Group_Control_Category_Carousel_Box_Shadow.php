<?php

namespace Lisfinity\Shortcodes\Controls\Category_Carousel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor box shadow control.
 *
 * A base control for creating box shadow control. Displays input fields to define
 * the box shadow including the horizontal shadow, vertical shadow, shadow blur,
 * shadow spread, shadow color and the position.
 *
 * @since 1.2.2
 */
class Group_Control_Category_Carousel_Box_Shadow extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the box shadow control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 * @static
	 *
	 * @var array Box shadow control fields.
	 */
	protected static $fields;

	/**
	 * Get box shadow control type.
	 *
	 * Retrieve the control type, in this case `box-shadow`.
	 *
	 * @return string Control type.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_type() {
		return 'category-carousel-box-shadow';
	}

	/**
	 * Init fields.
	 *
	 * Initialize box shadow control fields.
	 *
	 * @return array Control fields.
	 * @since 1.2.2
	 * @access protected
	 *
	 */
	protected function init_fields() {
		$controls = [];

		$controls['box_shadow'] = [
			'label'     => _x( 'Box Shadow', 'Box Shadow Control', 'lisfinity-core' ),
			'type'      => Controls_Manager::BOX_SHADOW,
			'default'   => [
				"horizontal" => 0,
				'vertical'   => 0,
				'blur'       => 0,
				'spread'     => 0,
				'color'      => 'rgba(239, 239, 239, 1)',
			],
			'selectors' => [
				'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
		];

		$controls['box_shadow_position'] = [
			'label'       => _x( 'Position', 'Box Shadow Control', 'lisfinity-core' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				' '     => _x( 'Outline', 'Box Shadow Control', 'lisfinity-core' ),
				'inset' => _x( 'Inset', 'Box Shadow Control', 'lisfinity-core' ),
			],
			'default'     => ' ',
			'render_type' => 'ui',
		];

		return $controls;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the box shadow control. Used to return the
	 * default options while initializing the box shadow control.
	 *
	 * @return array Default box shadow control options.
	 * @since 1.9.0
	 * @access protected
	 *
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Box Shadow', 'Box Shadow Control', 'lisfinity-core' ),
				'starter_name'  => $this::get_type(),
				'starter_value' => 'yes',
				'settings'      => [
					'render_type' => 'ui',
				],
			],
		];
	}
}
