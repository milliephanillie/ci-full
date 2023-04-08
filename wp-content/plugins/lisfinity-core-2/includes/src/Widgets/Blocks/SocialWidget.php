<?php


namespace Lisfinity\Widgets\Blocks;

use Carbon_Fields\Widget;
use Carbon_Fields\Field;

class SocialWidget extends Widget {

	function __construct() {
		$this->setup( 'social_widget', __( 'Lisfinity > Social', 'lisfinity-core' ), __( 'Used to display social networks of choice', 'lisfinity-core' ), [
			Field::make( 'text', 'title', __( 'Title', 'lisfinity-core' ) ),
			Field::make( 'text', 'facebook', __( 'Facebook', 'lisfinity-core' ) ),
			Field::make( 'text', 'twitter', __( 'Twitter', 'lisfinity-core' ) ),
			Field::make( 'text', 'instagram', __( 'Instagram', 'lisfinity-core' ) ),
			Field::make( 'text', 'dribbble', __( 'Dribbble', 'lisfinity-core' ) ),
			Field::make( 'text', 'linkedin', __( 'Linkedin', 'lisfinity-core' ) ),
			Field::make( 'text', 'youtube', __( 'YouTube', 'lisfinity-core' ) ),
			Field::make( 'text', 'reddit', __( 'Reddit', 'lisfinity-core' ) ),
			Field::make( 'text', 'pinterest', __( 'Pinterest', 'lisfinity-core' ) ),
			Field::make( 'text', 'medium', __( 'Medium', 'lisfinity-core' ) ),
			Field::make( 'text', 'vk', __( 'VKontakte', 'lisfinity-core' ) ),
		] );
	}

	function front_end( $args, $instance ) {
		include lisfinity_get_template_part( 'title', 'widgets/partials', $args );
		include lisfinity_get_template_part( 'social', 'widgets/social', $args );
	}

}

