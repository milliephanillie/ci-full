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
        add_filter( 'gform_notification', [$this, 'add_to_email'], 10, 3 );
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
        // Check if the notification name matches
        if ( $notification['name'] == 'Contact Seller' ) {
            $post_id = $entry[5];

            $seller_id = get_post_meta( $post_id, '_product-agent', true );

            // Get the email of the seller/user
            $seller_info = get_userdata($seller_id);
            $seller_email = $seller_info->user_email;

            // Append the seller's email to the existing "to" email addresses
            if ($seller_email) { // Check that email exists
                if (!empty($notification['to'])) {
                    $notification['to'] .= ', ';
                }
                $notification['to'] .= $seller_email;
            }
        }

        return $notification;
    }
}