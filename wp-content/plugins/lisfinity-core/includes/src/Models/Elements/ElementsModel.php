<?php
/**
 * Model for our custom Elementor Elements functionality
 *
 * @author pebas
 * @package lisfinity-elements
 * @version 1.0.0
 */

namespace Lisfinity\Models\Elements;


class ElementsModel {

	public function admin_menu() {
		$this->create_menu_page();
	}

	public function create_menu_page() {
		if ( \LisfinityBase::GetRegisterInfo() ) {
			add_menu_page(
				__( 'Lisfinity Elements', 'lisfinity-core' ),
				__( 'Lisfinity Elements', 'lisfinity-core' ),
				'manage_options',
				'lisfinity-elements',
				[ $this, 'elements_settings' ],
				'dashicons-schedule',
				27
			);
		}
	}

	public function elements_settings() {
		$args = [];
		include lisfinity_get_template_part( 'elements', 'elements', $args );
	}
}
