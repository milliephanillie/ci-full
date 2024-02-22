<?php

namespace ConcreteIron\Includes;

class RapidCategoryContent {
    public function __construct() {
        $this->dir = plugin_dir_path(CONCRETEIRON) . trailingslashit('category-content');
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'ci/v1';
        $route = '/category-content';

        register_rest_route($namespace, $route, [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'get_category_content'],
            'permission_callback' => '__return_true', // Ensure this is secure for your use case
        ]);
    }

    public function get_category_content(\WP_REST_Request $request) {
        $params = $request->get_params();
        $data = [
            "params" => $params
        ];
        $template_path = '';

        $type = $data['concrete-equipment-type'] = $params['tax']['concrete-equipment-type'] ?? null;
        $subcategory = $data['concrete-equipment-subcategory'] = $params['tax']['concrete-equipment-subcategory'] ?? null;

        if ($type) {
            switch ($type) {
                case 'concrete-batching-equipment':
                    $template_path = 'concrete-batching-equipment/index.php';
                    break;
                case 'concrete-cutting-and-demolition-equipment':
                    $template_path = 'concrete-cutting-equipment/index.php';
                    break;
                case 'concrete-placing-and-finishing-equipment':
                    $template_path = 'concrete-placing-equipment/index.php';
                    break;
                case 'concrete-pumping-equipment':
                    $template_path = 'concrete-pumping-equipment/index.php';
                    break;
            }
        }

        if ($subcategory) {
            switch ($subcategory) {
                case 'volumetric-mixers':
                    $template_path = 'volumetric-mixer-for-sale/index.php';
                    break;
                case 'concrete-mixers':
                    $template_path = 'concrete-mixers-for-sale/index.php';
                    break;
            }
        }

        $content_file = $this->dir . $template_path;

        if (file_exists($content_file)) {
            $data['content'] = file_get_contents($content_file) ?? '';
        } else {
            $data['content'] = '';
        }

        return new \WP_REST_Response($data, 200);
    }
}