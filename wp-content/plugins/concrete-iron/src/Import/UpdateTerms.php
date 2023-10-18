<?php
namespace ConcreteIron\Import;

class UpdateTerms {
    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = 'update/terms';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_terms'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    public function update_terms(\WP_REST_Request $request) {
        $params = $request->get_params();
        //$post_id, $category, $subcategory_lvl_1, $subcategory_lvl_2, $subcategory_lvl_3 = null;
        $category = $params['category'];
        $subcategory_lvl_1 = $params['subcategory_lvl_1'] ?? null;
        $subcategory_lvl_2 = $params['subcategory_lvl_2'] ?? null;
        $subcategory_lvl_3 = $params['subcategory_lvl_3'] ?? null;


        $category_update = update_post_meta($params['post_id'], '_product-category', 'concrete-equipment');
        $subcategory_lvl_3_update = $this->set_subcategory_lvl_3($params['post_id'], $subcategory_lvl_3);

        if(!$subcategory_lvl_3_update) {
            return new \WP_Error(
                'update_terms',
                'category 3 error'
            );
        }

        $subcategory_lvl_2_update = $this->set_subcategory_lvl_2($params['post_id'], $subcategory_lvl_3_update[0]);

        if(!$subcategory_lvl_3_update) {
            return new \WP_Error(
                'update_terms',
                'category 2 error'
            );
        }

        return rest_ensure_response(new \WP_REST_Response(
            [
                'category' => $category_update,
                'subcategory_lvl_3_update' => [
                    'subcategory_lvl_3_update' => $subcategory_lvl_3_update,
                    'name' => $this->get_term_name_by_id($subcategory_lvl_3_update[0], 'concrete-equipment-subcategory')
                ],
                'subcategory_lvl_2_update' => [
                    'subcategory_lvl_2_update' => $subcategory_lvl_2_update,
                    'name' => $this->get_term_name_by_id($subcategory_lvl_2_update[0], 'concrete-equipment-type')
                ],
            ]
        ));
    }

    public function set_subcategory_lvl_3($post_id, $subcategory_lvl_3) {
        $slug = $this->slugify($subcategory_lvl_3);
        $subcategory_lvl_3_update = wp_set_object_terms($post_id, $slug, 'concrete-equipment-subcategory', false);

        return $subcategory_lvl_3_update;
    }

    public function set_subcategory_lvl_2($post_id, $subcategory_lvl_3_slug) {
        $term = get_term_by('term_taxonomy_id', $subcategory_lvl_3_slug, 'concrete-equipment-subcategory');
        $parent = get_term_by('term_id', $term->parent, 'concrete-equipment-type');

        $subcategory_lvl_2_update = null;
        if( $parent ) {
            $subcategory_lvl_2_update = wp_set_object_terms($post_id, $parent->slug, 'concrete-equipment-type');
        }

        return $subcategory_lvl_2_update;
    }

    public function slugify($string) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public function get_term_name_by_id($term_id, $taxonomy = '') {
        $term = get_term($term_id, $taxonomy);

        if (is_wp_error($term) || empty($term)) {
            return false;
        }

        return $term->name;
    }
}