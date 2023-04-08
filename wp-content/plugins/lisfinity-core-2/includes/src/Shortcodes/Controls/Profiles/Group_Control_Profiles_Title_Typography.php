<?php

namespace Lisfinity\Shortcodes\Controls\Profiles;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Group_Control_Profiles_Title_Typography extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the typography control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Typography control fields.
	 */
	protected static $fields;

	/**
	 * Scheme fields keys.
	 *
	 * Holds all the typography control scheme fields keys.
	 * Default is an array containing `font_family` and `font_weight`.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Typography control scheme fields keys.
	 */
	private static $_scheme_fields_keys = [ 'font_family', 'font_weight' ];

	/**
	 * Get scheme fields keys.
	 *
	 * Retrieve all the available typography control scheme fields keys.
	 *
	 * @return array Scheme fields keys.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_scheme_fields_keys() {
		return self::$_scheme_fields_keys;
	}

	/**
	 * Get typography control type.
	 *
	 * Retrieve the control type, in this case `typography`.
	 *
	 * @return string Control type.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_type() {
		return 'profiles-title-typography';
	}

	public function init_fields() {
		$fields = [];

		$default_fonts = Plugin::$instance->kits_manager->get_active_kit_for_frontend()->get_settings( 'elementor_default_generic_fonts' );

		if ( $default_fonts ) {
			$default_fonts = ', ' . $default_fonts;
		}

		$fields['font_family'] = [
			'label'          => _x( 'Family', 'Typography Control', 'lisfinity-core' ),
			'type'           => Controls_Manager::FONT,
			'default'        => '',
			'selector_value' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
		];

		$fields['font_size'] = [
			'label'          => _x( 'Size', 'Typography Control', 'lisfinity-core' ),
			'type'           => Controls_Manager::SLIDER,
			'size_units'     => [ 'px', 'em', 'rem', 'vw' ],
			'range'          => [
				'px' => [
					'min' => 1,
					'max' => 200,
				],
				'vw' => [
					'min'  => 0.1,
					'max'  => 10,
					'step' => 0.1,
				],
			],
			'default'        => [
				'unit' => 'px',
				'size' => 16,
			],
			'responsive'     => true,
			'selector_value' => 'font-size: {{SIZE}}{{UNIT}}',
		];

		$typo_weight_options = [
			'' => __( 'Default', 'lisfinity-core' ),
		];

		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[ $weight ] = ucfirst( $weight );
		}

		$fields['font_weight'] = [
			'label'   => _x( 'Weight', 'Typography Control', 'lisfinity-core' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 500,
			'options' => $typo_weight_options,
		];

		$fields['text_transform'] = [
			'label'   => _x( 'Transform', 'Typography Control', 'lisfinity-core' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''           => __( 'Default', 'lisfinity-core' ),
				'uppercase'  => _x( 'Uppercase', 'Typography Control', 'lisfinity-core' ),
				'lowercase'  => _x( 'Lowercase', 'Typography Control', 'lisfinity-core' ),
				'capitalize' => _x( 'Capitalize', 'Typography Control', 'lisfinity-core' ),
				'none'       => _x( 'Normal', 'Typography Control', 'lisfinity-core' ),
			],
		];

		$fields['font_style'] = [
			'label'   => _x( 'Style', 'Typography Control', 'lisfinity-core' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => __( 'Default', 'lisfinity-core' ),
				'normal'  => _x( 'Normal', 'Typography Control', 'lisfinity-core' ),
				'italic'  => _x( 'Italic', 'Typography Control', 'lisfinity-core' ),
				'oblique' => _x( 'Oblique', 'Typography Control', 'lisfinity-core' ),
			],
		];

		$fields['text_decoration'] = [
			'label'   => _x( 'Decoration', 'Typography Control', 'lisfinity-core' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''             => __( 'Default', 'lisfinity-core' ),
				'underline'    => _x( 'Underline', 'Typography Control', 'lisfinity-core' ),
				'overline'     => _x( 'Overline', 'Typography Control', 'lisfinity-core' ),
				'line-through' => _x( 'Line Through', 'Typography Control', 'lisfinity-core' ),
				'none'         => _x( 'None', 'Typography Control', 'lisfinity-core' ),
			],
		];

		$fields['line_height'] = [
			'label'           => _x( 'Line-Height', 'Typography Control', 'lisfinity-core' ),
			'type'            => Controls_Manager::SLIDER,
			'desktop_default' => [
				'unit' => 'em',
				'size' => 1.5,
			],
			'tablet_default'  => [
				'unit' => 'em',
				'size' => 1.2,
			],
			'mobile_default'  => [
				'unit' => 'em',
				'size' => 1.2,
			],
			'range'           => [
				'px' => [
					'min' => 1,
				],
			],
			'responsive'      => true,
			'size_units'      => [ 'px', 'em' ],
			'selector_value'  => 'line-height: {{SIZE}}{{UNIT}}',
		];

		$fields['letter_spacing'] = [
			'label'          => _x( 'Letter Spacing', 'Typography Control', 'lisfinity-core' ),
			'type'           => Controls_Manager::SLIDER,
			'range'          => [
				'px' => [
					'min'  => - 5,
					'max'  => 10,
					'step' => 0.1,
				],
			],
			'responsive'     => true,
			'selector_value' => 'letter-spacing: {{SIZE}}{{UNIT}}',
		];

		return $fields;
	}

	public function new_fields( $fields = [] ) {
		return $fields;
	}

	/**
	 * Prepare fields.
	 *
	 * Process typography control fields before adding them to `add_control()`.
	 *
	 * @param array $fields Typography control fields.
	 *
	 * @return array Processed fields.
	 * @since 1.2.3
	 * @access protected
	 *
	 */
	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function ( &$field, $field_name ) {
			if ( in_array( $field_name, [ $this::get_type(), 'popover_toggle' ] ) ) {
				return;
			}

			$selector_value = ! empty( $field['selector_value'] ) ? $field['selector_value'] : str_replace( '_', '-', $field_name ) . ': {{VALUE}};';

			$field['selectors'] = [
				'{{SELECTOR}}' => $selector_value,
			];
		}
		);

		return parent::prepare_fields( $fields );
	}

	/**
	 * Add group arguments to field.
	 *
	 * Register field arguments to typography control.
	 *
	 * @param string $control_id Typography control id.
	 * @param array $field_args Typography control field arguments.
	 *
	 * @return array Field arguments.
	 * @since 1.2.2
	 * @access protected
	 *
	 */
	protected function add_group_args_to_field( $control_id, $field_args ) {
		$field_args = parent::add_group_args_to_field( $control_id, $field_args );

		$args = $this->get_args();

		if ( in_array( $control_id, self::get_scheme_fields_keys() ) && ! empty( $args['scheme'] ) ) {
			$field_args['scheme'] = [
				'type'  => self::get_type(),
				'value' => $args['scheme'],
				'key'   => $control_id,
			];
		}

		return $field_args;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the typography control. Used to return the
	 * default options while initializing the typography control.
	 *
	 * @return array Default typography control options.
	 * @since 1.9.0
	 * @access protected
	 *
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name'  => $this::get_type(),
				'starter_title' => _x( 'Typography', 'Typography Control', 'lisfinity-core' ),
				'settings'      => [
					'render_type' => 'ui',
				],
			],
		];
	}
}
