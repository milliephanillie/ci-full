<?php

namespace Lisfinity\Shortcodes\Controls\Navigation;

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
class Group_Control_Navigation_Button_Border extends Group_Control_Base {
	/**
	 * Fields.
	 *
	 * Holds all the border control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Border control fields.
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
	public static function get_type()
	{
		return 'navigation-button-border';
	}

		/**
		 * Init fields.
		 *
		 * Initialize border control fields.
		 *
		 * @return array Control fields.
		 * @since 1.2.2
		 * @access protected
		 *
		 */
		protected
		function init_fields()
		{
			$fields = [];

			$fields['border'] = [
				'label' => _x('Border Type', 'Border Control', 'lisfinity-core'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'lisfinity-core'),
					'solid' => _x('Solid', 'Border Control', 'lisfinity-core'),
					'double' => _x('Double', 'Border Control', 'lisfinity-core'),
					'dotted' => _x('Dotted', 'Border Control', 'lisfinity-core'),
					'dashed' => _x('Dashed', 'Border Control', 'lisfinity-core'),
				],
				'default' => 'solid',
				'selectors' => [
					'{{SELECTOR}}' => 'border-style: {{VALUE}};',
				],
			];

			$fields['width'] = [
				'label' => _x('Width', 'Border Control', 'lisfinity-core'),
				'type' => Controls_Manager::DIMENSIONS,

				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0'
				],
				'selectors' => [
					'{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'border!' => '',
				],
			];

			$fields['color'] = [
				'label' => _x('Color', 'Border Control', 'lisfinity-core'),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{SELECTOR}}' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border!' => '',
				],
			];

			return $fields;
		}

		/**
		 * Get default options.
		 *
		 * Retrieve the default options of the border control. Used to return the
		 * default options while initializing the border control.
		 *
		 * @return array Default border control options.
		 * @since 1.9.0
		 * @access protected
		 *
		 */
		protected
		function get_default_options()
		{
			return [
				'popover' => [
					'starter_title' => _x('Border', 'Border Control', 'lisfinity-core'),
					'starter_name' => $this::get_type(),
					'starter_value' => 'yes',
					'settings' => [
						'render_type' => 'ui',
					],
				],
			];
		}
}


