<?php

if ( ! function_exists( '_product_gallery' ) ) {
	function _product_gallery( $post_id, $id, $url ) {
		delete_post_meta( $post_id, '_product-images-tmp', $id );
		add_post_meta( $post_id, '_product-images-tmp', $id, false );
	}
}

if ( ! function_exists( '_product_files' ) ) {
	function _product_files( $post_id, $id, $url ) {
		delete_post_meta( $post_id, '_product-files-tmp', $id );
		add_post_meta( $post_id, '_product-files-tmp', $id, false);
	}
}
