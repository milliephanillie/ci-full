<?php

namespace Lisfinity\Models\ProductImporter;

use Lisfinity\Abstracts\Importer;

class Media extends Importer {

	protected $fields = [
		'post_thumbnail'  => 'thumbnail',
		'_product-videos' => 'video',
	];

	protected function set_name() {
		return __( 'Ad Featured Image and Video Settings', 'lisfinity-core' );
	}

	protected function set_fields() {
		$this->addon->add_field( 'post_thumbnail', __( 'Featured Image', 'lisfinity-core' ), 'image', null, __( 'The main image of the ad.', 'lisfinity-core' ) );
		$this->addon->import_images( '_product_gallery', __( 'Ad Gallery', 'lisfinity-core' ) );
		$this->addon->import_files( '_product_files', __( 'Ad Additional Files', 'lisfinity-core' ) );
		$this->addon->add_field( '_product-videos', __( 'Ad Videos', 'lisfinity-core' ), 'textarea', null, __( 'Add a links to YouTube videos separated with a new line', 'lisfinity-core' ), false, null );
		$this->addon->add_text( __( 'Separate each video link with a new line.', 'lisfinity-core' ) );
	}
}
