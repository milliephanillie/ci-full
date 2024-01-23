<?php
namespace ConcreteIron\Includes;

use Lisfinity\Models\ProductModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\REST_API\Search\SearchRoute;

class RapidAds {
    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_filter('concreteiron__ads', [$this, 'filter_ads_by_category'], 10, 2);
    }

    public function filter_ads_by_category($advertisments, $product_id) {
        $term_slugs = $this->get_term_slugs($product_id, 'concrete-equipment-subcategory');
        if (empty($term_slugs)) {
            $term_slugs = $this->get_term_slugs($product_id, 'concrete-equipment-type');
        }

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => absint( lisfinity_get_option( 'ad-similar-number' ) ) + 1,
            'post__not_in'   => [ $product_id ],
            'tax_query'      => [
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'name',
                    'terms'    => 'listing',
                    'operator' => 'IN',
                ],
            ],
            'orderby'        => 'rand',
        ];

        if ('promoted' === lisfinity_get_option('display-sidebar-promotion')) {
            $promoted_products = lisfinity_get_promoted_products('single-ad');

            error_log(print_r("promoted products", true));
            error_log(print_r($promoted_products, true));

            $matching_promoted_ids = [];

            $promoted_product_terms_slugs = [];

            foreach ($promoted_products as $promoted_product) {
                $promoted_terms_slugs = $this->get_term_slugs($promoted_product->product_id, 'concrete-equipment-subcategory');
                if (empty($promoted_terms_slugs)) {
                    $promoted_terms_slug = $this->get_term_slugs($promoted_product->product_id, 'concrete-equipment-type');
                }

                array_push($promoted_product_terms_slugs, $promoted_terms_slugs);
            }

            $flattened_promoted_product_terms_slugs = array_reduce($promoted_product_terms_slugs, function ($carry, $item) {
                return array_merge($carry, $item);
            }, []);

            if (!empty(array_intersect($term_slugs, $flattened_promoted_product_terms_slugs))) {
                $matching_promoted_ids[] = $promoted_product->product_id;
            }

            if ( ! empty( $matching_promoted_ids ) ) {
                $matching_promoted_ids = array_unique($matching_promoted_ids);
                $args['post__in'] = $matching_promoted_ids;
            } else {
                return [];
            }
        }

        $products = (new ProductModel())->get_products_query($args);

        error_log(print_r($products, true));
        error_log(print_r("matching_promoted_ids", true));
        error_log(print_r($matching_promoted_ids, true));

        $search_route = new SearchRoute();

        $product_category = carbon_get_post_meta($product_id, 'product-category');

        return $search_route->prepare_products_for_display( $products->posts, false, $product_category );
    }

    /**
     * Helper method to get term slugs for a given product ID and taxonomy.
     *
     * @param int $product_id The product ID.
     * @param string $taxonomy The taxonomy name.
     * @return array An array of term slugs.
     */
    private function get_term_slugs($product_id, $taxonomy) {
        $terms = get_the_terms($product_id, $taxonomy);
        $term_slugs = array();

        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $term_slugs[] = $term->slug;
            }
        }

        return $term_slugs;
    }
}