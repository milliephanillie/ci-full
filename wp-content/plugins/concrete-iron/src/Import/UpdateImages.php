<?php
namespace ConcreteIron\Import;

class UpdateImages
{
    public $failed_uploads = [];

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
        $route = 'update/images';

        register_rest_route($namespace, $route, [
            'methods' => [\WP_REST_Server::CREATABLE],
            'callback' => [
                $this,
                'update_images'
            ],
            'args' => [],
            'permission_callback' => '__return_true',
        ]);
    }

    public function update_images(\WP_REST_Request $request) {
        $params = $request->get_params();
        $url_strings = $params['files'] ?? null;

        if(!$url_strings || !$params['post_id']) {
            return new \WP_Error(
                'update_images',
                'no files or no post id'
            );
        }

        if($params['old_domain'] && $params['new_domain'] && $params['files']) {
            $url_strings = $this->replace_domain($params['old_domain'], $params['new_domain'], $url_strings);
        }

        $data = $this->add_images_to_product_gallery($url_strings, $params['post_id'], null);

        return rest_ensure_response(new \WP_REST_Response(
            [
                $data
            ]
        ));
    }

    function replace_domain($old_domain, $new_domain, $url_string) {
        return str_replace($old_domain, $new_domain, $url_string);
    }

    /**
     * Download from url and upload to media, and add to product image gallery
     *
     * @param $csv_cell
     * @param $post_id
     * @return array|string[]
     */
    public function add_images_to_product_gallery($gallery_images, $post_id, $alt_text = null) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        // Convert gallery_images to array of URLs
        $urls = array_map('trim', str_getcsv($gallery_images));

        // Initialize an empty array to hold the new or existing attachment IDs
        $attachment_ids = [];

        // Check if the product already has a main image
        $has_main_image = get_post_meta($post_id, '_thumbnail_id', true);

        // Loop through each URL
        foreach ($urls as $url) {
            // Check if the image already exists
            $existing_id = $this->image_already_exists($url);

            if ($existing_id) {
                $attach_id = $existing_id;
            } else {
                // Download file to temp dir
                $response = wp_remote_get($url, ['timeout' => 300]);

                if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                    $this->failed_uploads[] = $url;
                    continue;
                }

                // Write to a temporary file
                $tmpfile = wp_tempnam($url);
                file_put_contents($tmpfile, wp_remote_retrieve_body($response));

                $file_array = array(
                    'name'     => basename($url),
                    'tmp_name' => $tmpfile,
                );

                // Insert downloaded file as an attachment
                $attach_id = media_handle_sideload($file_array, $post_id);

                // Check for handle sideload errors
                if (is_wp_error($attach_id)) {
                    $this->failed_uploads[] = $url;
                    continue;
                }
            }

            if($alt_text) {
                // Add alt text
                update_post_meta($attach_id, '_wp_attachment_image_alt', $alt_text);
            }

            // If there is no main product image, set the first successful upload as the main image
            if (!$has_main_image) {
                update_post_meta($post_id, '_thumbnail_id', $attach_id);
                $has_main_image = true;  // Prevent setting additional images as main
            }

            $attachment_ids[] = $attach_id;
        }

        // Update product gallery
        if (!empty($attachment_ids)) {
            $existing_gallery = get_post_meta($post_id, '_product_image_gallery', true);
            $merged_gallery = implode(',', array_merge(explode(',', $existing_gallery), $attachment_ids));
            update_post_meta($post_id, '_product_image_gallery', $merged_gallery);
        }

        // Return information about failed uploads
        if (!empty($this->failed_uploads)) {
            return ['status' => 'error', 'message' => 'Failed to upload some images', 'failed_uploads' => $this->failed_uploads];
        }

        return ['status' => 'success', 'message' => 'All images uploaded successfully', 'alt_text' => $alt_text, 'has_main_image' => $has_main_image];
    }

    /**
     * Check if the image already exists
     *
     * @param $url
     * @return false|string
     */
    public function image_already_exists($url) {
        global $wpdb;
        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1";
        $id = $wpdb->get_var($wpdb->prepare($query, $url));
        return ($id) ? $id : false;
    }
}