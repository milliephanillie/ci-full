<?php
namespace ConcreteIron\Includes;

class RapidRewrites {
    public function _construct() {
        $this->boot();
    }

    public function boot() {
        add_action('generate_rewrite_rules', [$this, 'custom_rewrite_rules']);
    }

    public function custom_rewrite_rules($wp_rewrite) {
        $new_rules = array(
            'ad-category/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?tax_query=' . http_build_query([
                    [
                        'taxonomy' => 'concrete-equipment-subcategory',
                        'terms' => $wp_rewrite->preg_index(3),
                        'field' => 'slug',
                    ],
                    [
                        'taxonomy' => 'concrete-equipment-type',
                        'terms' => $wp_rewrite->preg_index(2),
                        'field' => 'slug',
                    ],
                ]),
        );

        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}