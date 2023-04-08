<?php

namespace Lisfinity\Abstracts;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

abstract class Shortcode extends Widget_Base {

	/**
	 * Create an option to set the margin
	 * ----------------------------------
	 *
	 * @param $id
	 * @param $selector
	 * @param $top
	 * @param $right
	 * @param $bottom
	 * @param $left
	 * @param $isLinked
	 */
	public function set_margin( $id, $selector, $top, $right, $bottom, $left, $isLinked ) {
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
					'top'      => (string) $top,
					'right'    => (string) $right,
					'bottom'   => (string) $bottom,
					'left'     => (string) $left,
					'isLinked' => $isLinked,
				]
			]
		);
	}

	/**
	 * Create an option to set the padding
	 * -----------------------------------
	 *
	 * @param $id
	 * @param $selector
	 * @param $top
	 * @param $right
	 * @param $bottom
	 * @param $left
	 * @param $isLinked
	 */
	public function set_padding( $id, $selector, $top, $right, $bottom, $left, $isLinked, $message = 'Padding' ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => (string) $top,
					'right'    => (string) $right,
					'bottom'   => (string) $bottom,
					'left'     => (string) $left,
					'isLinked' => $isLinked,
				]
			]
		);
	}

	/**
	 * Create an option to set the background color
	 * --------------------------------------------
	 *
	 * @param $id
	 * @param $default_color
	 * @param $message
	 * @param $selector
	 */
	public function set_background_color( $id, $default_color, $message = '', $selector, $is_background = true ) {
		$message = ! empty( $message ) ? __( $message, 'lisfinity-core' ) : __( 'Background Color', 'lisfinity-core' );
		$type    = $is_background ? 'background-' : '';
		$this->add_responsive_control(
			$id,
			[
				'label'       => $message,
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => "{$type}color:{{VALUE}};"
				],
			]
		);
	}

	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_responsive_control(
			$id,
			[
				'label'     => __( $heading, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			$hr_id,
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
	}

	public function break_into_columns( $id, $selector, $default, $additional_percentage = '' ) {
		$args = [
			'label'       => __( 'Break Into Columns', 'lisfinity-core' ),
			'label_block' => true,
			'type'        => Controls_Manager::NUMBER,
			'default'     => $default,
			'min'         => 1,
			'max'         => 9,
			'description' => __( 'Choose the number of columns you wish to break', 'lisfinity-core' ),
			'selectors'   => [
				"{{WRAPPER}} $selector" => 'width: calc((100% / {{VALUE}}));',
			],
		];
		if ( ! empty( $additional_percentage ) && 0 < $additional_percentage ) {
			$args['selectors'] = [ "{{WRAPPER}} $selector" => "width: calc((100% / {{VALUE}}) - $additional_percentage);" ];
		}
		$this->add_responsive_control(
			$id,
			$args
		);
	}

	public function display_element( $id, $message, $default = 'yes' ) {
		$this->add_responsive_control(
			$id,
			[
				'label'        => __( $message, 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => $default,
			]
		);
	}

	public function column_gap( $id, $selector, $selector2, $default ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( 'Ad Columns Gap', 'lisfinity-core' ),
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
					'size' => $default,
				],
				'description' => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector"  => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} $selector2" => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_width( $id, $selector, $default, $default_unit ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => $default_unit,
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector, $condition = '', $horizontal_position = 'left' ) {
		$fieldsArray = [
			'label_block' => true,
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min' => - 999,
					'max' => 999,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => $default_x,
			],
			'description' => __( 'Horizontal', 'lisfinity-core' ),
			'selectors'   => [
				"{{WRAPPER}} $selector" => "$horizontal_position: {{SIZE}}{{UNIT}};",
			]
		];
		if ( $condition !== '' ) {
			$fieldsArray['condition'] = [
				$condition => 'yes'
			];
		}
		$this->add_responsive_control(
			$id_x,
			$fieldsArray

		);

		$this->add_responsive_control(
			$id_y,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 9999,
						'max' => 9999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}};',
				]
			]
		);
	}


	public function set_height( $id, $selector, $default, $default_unit ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%', '' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => $default_unit,
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function row_gap() {
		$this->add_responsive_control(
			'products-columns-gap-y',
			[
				'label'       => __( 'Ad Columns Gap Vertical', 'lisfinity-core' ),
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
					'size' => 32,
				],
				'description' => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}


	public function set_border_radius( $id, $top, $right, $bottom, $left, $default_unit, $selector, $label = 'Border Radius' ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( $label, 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'unit'   => $default_unit,
					'top'    => $top,
					'right'  => $right,
					'bottom' => $bottom,
					'left'   => $left
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	public function set_elements_alignment( $id, $default, $selector, $is_flex = true, $title = 'Alignment' ) {
		$args = [
			'label'       => __( $title, 'lisfinity-core' ),
			'label_block' => true,
			'type'        => \Elementor\Controls_Manager::CHOOSE,
			'options'     => [
				'flex-start' => [
					'title' => __( 'Left', 'lisfinity-core' ),
					'icon'  => 'fa fa-align-left',
				],
				'center'     => [
					'title' => __( 'Center', 'lisfinity-core' ),
					'icon'  => 'fa fa-align-center',
				],
				'flex-end'   => [
					'title' => __( 'Right', 'lisfinity-core' ),
					'icon'  => 'fa fa-align-right',
				],
			],
			'default'     => $default,
			'toggle'      => true,
			'description' => __( 'Set alignment', 'lisfinity-core' ),
			'selectors'   => [
				"{{WRAPPER}} $selector" => 'justify-content: {{VALUE}};',
			],
		];

		if ( ! $is_flex ) {
			$args['options']   = [
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
			];
			$args['selectors'] = [ "{{WRAPPER}} $selector" => 'text-align: {{VALUE}};' ];
		}
		$this->add_responsive_control(
			$id,
			$args
		);
	}

}
