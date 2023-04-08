<?php


namespace Lisfinity\Widgets\Blocks;

use Carbon_Fields\Widget;
use Carbon_Fields\Field;

class PagesComplexWidget extends Widget {

	function __construct() {
		$this->setup( 'pages_complex_widget', __( 'Lisfinity > Pages Advanced', 'lisfinity-core' ), __( 'Used to display pages as a list.', 'lisfinity-core' ), [
			Field::make( 'text', 'pc_title', __( 'Title', 'lisfinity-core' ) ),
			Field::make( 'complex', 'pc_pages', __( 'Choose Pages', 'lisfinity-core' ) )->set_help_text( __( 'Choose the pages you wish to display.', 'lisfinity-core' ) )
			     ->add_fields(
				     [
					     Field::make( 'select', 'page', __( 'Package Feature', 'lisfinity-core' ) )
					          ->set_options( 'lisfinity_get_all_pages' )
					          ->set_help_text( __( 'Choose Page', 'lisfinity-core' ) ),
				     ]
			     )
		] );
	}

	function front_end( $args, $instance ) {
		include lisfinity_get_template_part( 'title', 'widgets/partials', $args );
		include lisfinity_get_template_part( 'pages-advanced', 'widgets/pages', $args );
	}

}

