<?php
/**
 * Custom Terms Importer
 *
 * @author pebas
 * @package custom-fields/terms
 * @version 1.0.0
 */

namespace Lisfinity\Models\Taxonomies;

/**
 * Class TermsAdminModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class ImporterModel {

	// called in @hooks.php
	public function add_term_to_taxonomy_order( $term_id, $tt_id, $taxonomy ) {
		if ( in_array( $taxonomy, lisfinity_get_product_taxonomies() ) ) {
			$taxonomy_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
			$options        = $taxonomy_model->get_options();
			if ( ! empty( $options ) ) {
				foreach ( $options as $group => $fields ) {
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $index => $tax ) {
							if ( $tax['slug'] === $taxonomy ) {
								$key                                     = array_search( $taxonomy, array_column( $options[ $group ], 'slug' ) );
								$options[ $group ][ $key ]['term_ids'][] = "{$taxonomy}-{$term_id}";
								break;
							}
						}
					}
				}
				$taxonomy_model->set_options( $options );
			}
		}
	}

}
