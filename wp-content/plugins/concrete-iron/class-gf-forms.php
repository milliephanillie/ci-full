<?php

class Headless_GravityForms
{
    public $rest_base = 'gf/forms';

    public function __construct($namespace)
    {
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

    /**
     * Retreive a single form and all fields and options (exluding notifications)
     * @param WP_REST_Request $request
     * @return WP_Error|WP_REST_Response
     */
    public function get_form(WP_REST_Request $request)
    {
        $form_id = $request['form_id'];
        $post_id = $request['postID'];
        $post_permalink = $request['postPermalink'];

        $title = get_the_title($post_id);
        $form = GFAPI::get_form($form_id);

        if ($form) {
            // Loop through each field in the form to find the hidden field with ID 5
            foreach ($form['fields'] as &$field) {
                if ($field['defaultValue'] == 'ci_post_id' && $field['type'] == 'hidden') {
                    $field['defaultValue'] = $post_id; // Set the defaultValue to postID
                }

                if ($field['defaultValue'] == 'listing_url' && $field['type'] == 'hidden') {
                    $link = get_permalink($post_id);
                    $field['defaultValue'] = $link; // Set the defaultValue to postID
                }
            }

            unset($field);  // Unset the reference to prevent unexpected behavior

            // Strip data we do not want to share
            unset($form['notifications']);

            return new WP_REST_Response($form, 200);
        } else {
            return new WP_Error('not_found', 'Form not found', ['status' => 404]);
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
