<?php declare( strict_types=1 );

namespace App\Carbon\Datasore;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Datastore\Post_Meta_Datastore;
use Carbon_Fields\Field\Field;
use Carbon_Fields\Toolset\Key_Toolset;

final class KeyValuePair {
	public $key;
	public $value;

	public function __construct( string $key, string $value ) {
		$this->key   = $key;
		$this->value = $value;
	}
}

trait EagerLoadingMetaDatastore {
	protected function get_storage_array( Field $field, $storage_key_patterns ) {
		$storage = [];
		$meta    = get_metadata( $this->get_meta_type(), $this->get_object_id() );
		if ( ! $meta ) {
			return $storage; // new object
		}
		foreach ( $storage_key_patterns as $storage_key => $type ) {
			switch ( $type ) {
				case Key_Toolset::PATTERN_COMPARISON_EQUAL:
					if ( isset( $meta[ $storage_key ] ) ) {
						$storage[] = new KeyValuePair( $storage_key, $meta[ $storage_key ][0] );
					}
					break;
				case Key_Toolset::PATTERN_COMPARISON_STARTS_WITH:
					foreach ( $meta as $key => $value ) {
						if ( is_string( $key ) && strpos( $key, $storage_key ) === 0 ) {
							$storage[] = new KeyValuePair( $key, $meta[ $key ][0] );
						}
					}
					break;
				default:
					throw new \LogicException( "Unknown storage key pattern type: {$type}" );
					break;
			}
		}

		$storage = apply_filters( 'carbon_fields_datastore_storage_array', $storage, $this, $storage_key_patterns );

		return $storage;
	}
}

final class EagerLoadingPostMetaDatastore extends Post_Meta_Datastore {
	use EagerLoadingMetaDatastore;
}
