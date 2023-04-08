<?php

namespace Lisfinity\Abstracts;

use Lisfinity\Models\ProductImporter\RapidAddon;


abstract class Importer {

	/**
	 * @var string
	 */
	protected static $name;
	/**
	 * @var string
	 */
	protected static $slug;
	/**
	 * @var RapidAddon
	 */
	public $addon;

	protected $product_type = 'listing';

	protected $fields = [];

	public function init() {
		$this::$name = $this->set_name();
		$this::$slug = sanitize_title( $this::$name );

		if ( class_exists( 'Lisfinity\Models\ProductImporter\RapidAddon' ) && ! class_exists( '\RappidAddon' ) ) {
			$this->addon = new RapidAddon( $this::$name, $this::$slug );
		} else {
			$this->addon = new \RapidAddon( $this::$name, $this::$slug );
		}

		$this->addon->disable_default_images();

		$this->addon->run( [
			'themes'     => [
				'Lisfinity',
			],
			'plugins'    => [ 'lisfinity-core/lisfinity-core.php' ],
			'post_types' => [ 'product' ],
		] );

		$this->set_fields();
		$this->addon->set_import_function( [ $this, 'import_fields' ] );

		// hooks.
		add_action( 'pmxi_saved_post', [ $this, 'after_post_is_stored' ], 10, 1 );
	}

	protected function set_name() {
		return __( 'Default Settings', 'lisfinity-core' );
	}

	protected function set_fields() {
		return [];
	}

	public function import_fields( $post_id, $data, $import_options, $article ) {
		global $wpdb;
		foreach ( $this->fields as $key => $type ) {
			if ( empty( $article['ID'] ) || $this->addon->can_update_meta( $key, $import_options ) ) {
				if ( $type === 'category' && ! empty( $data[ $key ] ) ) { // update ad category.
					$categories = get_option( 'lisfinity_groups' );
					if ( ! $this->category_exists( $categories, sanitize_title( $data[ $key ] ) ) ) {
						$category['single_name'] = ucwords( $data[ $key ] );
						$category['plural_name'] = ucwords( $data[ $key ] );
						$category['slug']        = sanitize_title( $data[ $key ] );
						$categories[]            = $category;
						update_option( 'lisfinity_groups', $categories );
						$this->addon->log( "<strong>GROUP CATEGORY:</strong> {$category['slug']} created." );

						$data[ $key ] = $category['slug'];
					}
					update_post_meta( $post_id, $key, $data[ $key ] );
				}
				if ( $type === 'date' ) {// if it is a date.
					if ( ! is_numeric( $data[ $key ] ) ) {
						$date = strtotime( $data[ $key ] );
					} else {
						$date = $data[ $key ];
					}
					update_post_meta( $post_id, $key, $date );
				}
				if ( $type === 'price' ) {
					if ( $key === '_price' ) {
						update_post_meta( $post_id, '_regular_price', str_replace( [ '.', ',' ], [
							'',
							''
						], $data[ $key ] ) );
					}
					update_post_meta( $post_id, $key, str_replace( [ '.', ',' ], [ '', '' ], $data[ $key ] ) );
				}
				if ( $type === 'text' ) { // if it is a default text.
					if ( $key === '_product-owner' ) {
						$business_id = lisfinity_get_premium_profile_id( $data[ $key ] );
						update_post_meta( $post_id, '_product-owner', $data[ $key ] );
						update_post_meta( $post_id, '_product-business', $business_id );
					} else {
						update_post_meta( $post_id, $key, $data[ $key ] );
					}
				}
				if ( $type === 'thumbnail' && ! empty( $data[ $key ] ) ) {
					$attachment_id = $data[ $key ]['attachment_id'];
					set_post_thumbnail( $post_id, $attachment_id );
					add_post_meta( $post_id, '_product_image_gallery', $attachment_id, true );
				}
				if ( $type === 'video' && ! empty( $data[ $key ] ) ) {
					$videos           = preg_split( '/\r\n|\r|\n/', $data[ $key ] );
					$videos_formatted = [];
					foreach ( $videos as $video ) {
						$videos_formatted[]['video'] = $video;
					}

					carbon_set_post_meta( $post_id, 'product-videos', $videos_formatted );
				}
			}
		}
	}

	public function category_exists( $categories, $key ) {
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $group ) {
				if ( sanitize_title( $group['single_name'] ) === $key || sanitize_title( $group['plural_name'] ) === $key ) {
					return true;
				}
			}
		}

		return false;
	}

	public function after_post_is_stored( $id ) {
		wp_set_object_terms( $id, $this->product_type, 'product_type' );

		// images.
		$images = get_post_meta( $id, '_product-images-tmp' );
		delete_post_meta( $id, '_product_image_gallery' );
		add_post_meta( $id, '_product_image_gallery', implode( ',', $images ) );

		// files.
		$files           = get_post_meta( $id, '_product-files-tmp' );
		$files_formatted = [];
		foreach ( $files as $file ) {
			$files_formatted[]['file'] = $file;
		}
		carbon_set_post_meta( $id, 'product-files', $files_formatted );
	}

}
