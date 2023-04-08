<?php


namespace Lisfinity\Widgets;

use Lisfinity\Widgets\Blocks\SocialWidget;
use Lisfinity\Widgets\Blocks\PagesWidget;
use Lisfinity\Widgets\Blocks\PagesComplexWidget;

class Widgets {

	public function init() {
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
	}

	public function register_widgets() {
		register_widget( SocialWidget::class );
		register_widget( PagesWidget::class );
		register_widget( PagesComplexWidget::class );
	}

}
