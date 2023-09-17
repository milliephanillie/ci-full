<?php

class ProductImages
{
    const ACF_PREFIX = 'mp_';

    /**
     * CustomImport constructor
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Boot the actions/filters/functions
     */
    public function boot()
    {
        add_filter('https_ssl_verify', '__return_false');
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register our WP REST API endpoints
     */
    public function register_routes()
    {
        $namespace = 'ci/v1';
        $route = 'product_images';

        register_rest_route($namespace, $route, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'product_images'],
            'permission_callback' => '__return_true',
        ));
    }

    public function product_images(\WP_REST_Request $request)
    {
        $data = $request->get_params();

        $post_id = $data['post_id'];
        $images = $data['images'];
        $errors['errors'] = [];
        $attach_ids['attach_ids'] = [];
        $files['files'] = [];

        foreach ($images as $key => $image_url) {
            $image_data = file_get_contents($image_url);

            if (!$image_data) {
                array_push($errors['errors'], [$key => $image_url]);
                continue;
            }

            $filename = basename($image_url);

            $upload_dir = wp_upload_dir(); // Set upload folder

            if (wp_mkdir_p($upload_dir['path'])) {
                $filepath = $upload_dir['path'];
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $filepath = $upload_dir['basedir'];
                $file = $upload_dir['basedir'] . '/' . $filename;
            }

            if (!$file) {
                continue;
            }


            $bytes = file_put_contents($file, $image_data, FILE_APPEND | LOCK_EX);

            $wp_filetype = wp_check_filetype($filename, null);

            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            // Create the attachment
            $attach_id = wp_insert_attachment($attachment, $file);

            if (is_wp_error($attach_id) || 0 === $attach_id) {
                array_push($errors['errors'], ['attach_id_missing' => $file]);
                continue;
            }


//			// Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');
//			// Define attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
//			// Assign metadata to attachment
            wp_update_attachment_metadata($attach_id, $attach_data);
        }

        return rest_ensure_response(new WP_REST_Response(
            [
                "attachments" => [
                    "post_id" => $post_id,
                    $attach_ids,
                    $files,
                    $errors,
                ]
            ]
        ));
    }
}

$i = new ProductImages();

