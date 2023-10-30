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

        // Check if the notification name matches
        if ( $notification['name'] == 'Contact Seller' ) {
            foreach ($form['fields'] as $field) {
                if (isset($field['inputName']) && $field['inputName'] == 'ci_post_id') {
                    $field_id = (string)$field['id'];
                    if (isset($entry[$field_id])) {
                        $post_id = $entry[$field_id];
                        break;
                    }
                }
            }

            if (!$post_id) {
                return $notification; // Return if post_id is not found
            }

            $seller_id = get_post_meta( $post_id, '_product-agent', true );

            $business_owner = get_post_meta($post_id, '_business-owner', true);
            $profile_email = get_post_meta($business_owner, '_profile-email', true);

            $seller_info = get_userdata($seller_id);
            $seller_email = $seller_info->user_email;

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
        }
        
        return $notification;
    }
}