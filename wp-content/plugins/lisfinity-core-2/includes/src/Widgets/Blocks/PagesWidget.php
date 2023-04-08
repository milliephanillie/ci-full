<?php


namespace Lisfinity\Widgets\Blocks;

use Carbon_Fields\Widget;
use Carbon_Fields\Field;

class PagesWidget extends Widget {

	function __construct() {
		$this->setup( 'pages_widget', __( 'Lisfinity > Pages', 'lisfinity-core' ), __( 'Used to display pages as a list.', 'lisfinity-core' ), [
			Field::make( 'text', 'title', __( 'Title', 'lisfinity-core' ) ),
			Field::make( 'multiselect', 'pages', __( 'Title', 'lisfinity-core' ) )->set_help_text( __( 'Choose the pages you wish to display.', 'lisfinity-core' ) )
			     ->set_options( 'lisfinity_get_all_pages' ),
		] );
	}

	function front_end( $args, $instance ) {
		include lisfinity_get_template_part( 'title', 'widgets/partials', $args );
		include lisfinity_get_template_part( 'pages', 'widgets/pages', $args );
	}

}

