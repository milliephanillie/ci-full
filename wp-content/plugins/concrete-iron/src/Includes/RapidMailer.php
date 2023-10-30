<?php

namespace ConcreteIron\Includes;

class RapidMailer {
    /**
     * RapidMailer constructor
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
        add_filter( 'gform_notification', [$this, 'add_to_email'], 15, 3 );
    }

    /**
     * Add the seller to the notification if the email comes from the Contact Seller form
     *
     * @param $notification
     * @param $form
     * @param $entry
     * @return mixed
     */
    public function add_to_email( $notification, $form, $entry ) {
        error_log(print_r('An email has been sent. The notification is: ', true));
        error_log(print_r($notification['name'], true));

        $data = [
            'full_name' => '',
            'email_address' => '',
            'phone' => '',
            'message' => '',
            'listing_url' => '',
            'name' => ''
        ];

        // Check if the notification name matches
        if ( $notification['name'] == 'Contact Seller' ) {
            $template_path = plugin_dir_path( CONCRETEIRON ) . 'templates/lead.php';

            foreach ($form['fields'] as $field) {
                $field_id = (string)$field['id'];
                if (isset($field['inputName']) && $field['inputName'] == 'ci_post_id') {
                    if (isset($entry[$field_id])) {
                        $post_id = $entry[$field_id];
                    }
                }

                if (isset($field['inputName']) && $field['inputName'] == 'full_name') {
                    if (isset($entry[$field_id])) {
                        $data['full_name'] = $entry[$field_id];
                    }
                }

                if (isset($field['inputName']) && $field['inputName'] == 'email_address') {
                    if (isset($entry[$field_id])) {
                        $data['email_address'] = $entry[$field_id];
                    }
                }

                if (isset($field['inputName']) && $field['inputName'] == 'phone') {
                    if (isset($entry[$field_id])) {
                        $data['phone'] = $entry[$field_id];
                    }
                }

                if (isset($field['inputName']) && $field['inputName'] == 'message') {
                    if (isset($entry[$field_id])) {
                        $data['message'] = $entry[$field_id];
                    }
                }

                if (isset($field['inputName']) && $field['inputName'] == 'listing_url') {
                    if (isset($entry[$field_id])) {
                        $data['listing_url'] = $entry[$field_id];
                    }
                }
            }

            if (!$post_id) {
                return $notification; // Return if post_id is not found
            }

            $seller_id      = get_post_meta( $post_id, '_product-agent', true );
            $seller_info    = get_userdata($seller_id);
            $seller_email   = $seller_info->user_email;

            $business_owner = get_post_meta($post_id, '_product-business', true);
            error_log(print_r('_product-business', true));
            error_log(print_r($business_owner, true));
            $business  = get_post($business_owner);
            $business_author = get_userdata($business->post_author);
            if($business_author) {
                $data['name'] = $business_author->user_login;
            }
            $profile_email  = get_post_meta($business_owner, '_profile-email', true);

            error_log(print_r('The seller email is: ', true));
            error_log(print_r($seller_email, true));

            if ($seller_email && strpos($notification['to'], $seller_email) === false) {
                if (!empty($notification['to'])) {
                    $notification['to'] .= ', ';
                }
                $notification['to'] .= $seller_email;
            }

            if ($profile_email && strpos($notification['to'], $profile_email) === false) {
                if (!empty($notification['to'])) {
                    $notification['to'] .= ', ';
                }
                $notification['to'] .= $profile_email;
            }



            $content = $this->get_template_content($template_path, $data);
            error_log(print_r("the content of the template", true));
            error_log(print_r($content, true));
            error_log(print_r("the data", true));
            error_log(print_r($data, true));
            if($content) {
                $notification['message'] = $content;
            }
        }

        return $notification;
    }

    function get_template_content($template_path, $vars = array()) {
        if(file_exists($template_path)) {
            // Extract the variables to a local namespace
            extract($vars);

            // Start output buffering
            ob_start();

            // Include the template file
            include $template_path;

            // End buffering and return its contents
            return ob_get_clean();
        }
        return false;
    }
}