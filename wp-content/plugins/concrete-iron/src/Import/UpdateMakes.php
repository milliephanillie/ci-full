<?php

namespace ConcreteIron\Import;

class UpdateMakes
{
    public function __construct()
    {
        $this->boot();
    }

    public function boot()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'update/makes';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_makes'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);

        $route = 'update/category-makes';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_category_makes'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Update the makes
     *
     * @param \WP_REST_Request $request
     * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function update_makes(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['term_name']) {
            return new \WP_Error(
                'update_make',
                'missing post_id or term_name'
            );
        }

        $term_exists = $this->term_exists($params['term_name'], 'concrete-equipment-makes');

        $term = null;
        $make_update = null;

        if($term_exists) {
            $term = get_term_by('name', $params['term_name'], 'concrete-equipment-makes');

            if($term) {
                $make_update = wp_set_object_terms($params['post_id'], $term->slug, 'concrete-equipment-makes', false);
            }
        }

        return rest_ensure_response(new \WP_REST_Response(
            [
                'term_name' => $params['term_name'],
                'term_exists' => $term_exists,
                'term_slug' => ($term and property_exists($term, 'slug')) ? $term->slug : null,
                'make_update' => $make_update,
            ]
        ));
    }

    /**
     * Update the category make (or third level subcategory)
     *
     * @param \WP_REST_Request $request
     * @return void|\WP_Error
     */
    public function update_category_makes(\WP_REST_Request $request) {
        $params = $request->get_params();

        if(!$params['post_id'] || !$params['term_name'] || !$params['parent_name']) {
            return new \WP_Error(
                'update_category_makes',
                'missing post_id, or term_name, or parent_name'
            );
        }

        $term_exists_with_specific_parent = $this->term_exists_with_specific_parent('concrete-equipment-cat-makes', $params['term_name'], $params['parent_name'], 'concrete-equipment-subcategory');

        $update = null;
        if($term_exists_with_specific_parent) {
            $update = $this->set_term($params['post_id'], $params['term_name'], 'concrete-equipment-cat-makes');
        }

        return rest_ensure_response(new \WP_REST_Response([
            'update' => $update,
            'term' => get_term((int) $update, 'concrete-equipment-cat-makes'),
            'terms' => get_term_by('id', $update, 'concrete-equipment-cat-makes')
        ]));
    }

    /**
     * Set the category makes
     *
     * @param $post_id
     * @param $cat_make
     * @return array|bool|int|int[]|string|string[]|\WP_Error|null
     */
    public function set_term($post_id, $term_name, $taxonomy) {
        $slug = $this->slugify($term_name);

        $update = wp_set_object_terms($post_id, $slug, $taxonomy, false);

        return $update;
    }

    /**
     * Check if the term exists
     *
     * @param $term_name
     * @param $taxonomy_slug
     * @return bool
     */
    public function term_exists($term_name, $taxonomy_slug) {
        $the_terms = get_terms(
            [
                'taxonomy' => $taxonomy_slug,
                'hide_empty' => false
            ]
        );

        $termExists = false;

        foreach($the_terms as $term) {
            if (strpos($term->name, $term_name) !== false) {
                $termExists = true;
                break;
            }
        }

        return $termExists;
    }

    public function term_exists_with_specific_parent($taxonomy, $term_name, $parent_name, $parent_taxonomy){
        $parent_term = get_term_by('name', $parent_name, $parent_taxonomy);

        if ($parent_term && !is_wp_error($parent_term)) {
            $parent_term_id = $parent_term->term_id;
        } else {
            return false;
        }

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'name' => $term_name,
            'hide_empty' => false,
        ]);



        $term_exists_with_specific_parent = false;

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if ($term->parent == $parent_term_id) {
                    $term_exists_with_specific_parent = true;
                    break;
                }
            }
        }

        return $term_exists_with_specific_parent;
    }

    /**
     * Turn string into slug
     *
     * @param $string
     * @return string
     */
    public function slugify($string) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
