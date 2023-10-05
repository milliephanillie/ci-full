<?php

class Headless_GravityForms
{
    public $rest_base = 'gf/forms';

    public function __construct($namespace)

    {

        // This can be outside the class or in the same class
        add_filter('custom_gf_field_modify', function($field) {
            if ($field->type == 'text') {
                $icon = "";  // Default icon

                switch ($field->label) {
                    case 'Name':
                        $icon = '<i class="fas fa-user"></i>';
                        break;
                    case 'Phone':
                        $icon = '<i class="fas fa-phone"></i>';
                        break;
                    case 'Email':
                        $icon = '<i class="fas fa-envelope"></i>';
                        break;
                }

                // Modify the field object to contain the desired output
                // For this example, I'm just adding a new property for the icon
                $field->icon = $icon;
            }
            return $field;
        });

        /**
         * @api {get} /ci/v1/gf/forms/1
         * @apiName GetForm
         * @apiGroup GravityForms
         * @apiDescription Retreive a single form
         * @apiParam {Number} form_id ID of the form
         *
         * @apiSuccess {Object[]} GF_Form Object (excluding notifications)
         */
        register_rest_route($namespace, $this->rest_base . '/(?P<form_id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_form'],
                'args' => [
                    'context' => [
                        'default' => 'view',
                    ],
                ],
                'permission_callback' => '__return_true',
            ],
        ]);
    }

    public function get_form(\WP_REST_Request $request)
    {
        $form_id = $request['form_id'];
        $post_id = $request['postID'];

        $form = \GFAPI::get_form($form_id);

        if ($form) {
            foreach ($form['fields'] as &$field) {
                if ($field['id'] == 5 && $field['type'] == 'hidden') {
                    $field->defaultValue = $post_id;
                }

                // Apply the custom filter directly within the method
                $field = apply_filters('custom_gf_field_modify', $field);
            }

            // Cleanup: Remove the reference to the field object to avoid unexpected behavior
            unset($field);

            // Strip data we do not want to share
            unset($form['notifications']);

            return new \WP_REST_Response($form, 200);
        } else {
            return new \WP_Error('not_found', 'Form not found', ['status' => 404]);
        }
    }


}

/**
 * Register custom API routes
 */
add_action('rest_api_init', function () {
    $api_namespace = 'ci/v1';
    new Headless_GravityForms($api_namespace);
});
