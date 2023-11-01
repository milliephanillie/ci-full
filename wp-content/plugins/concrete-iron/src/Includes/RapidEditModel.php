<?php
namespace ConcreteIron\Includes;


class RapidEditModel
{
    /**
     * RapidEditModel constructor
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
        add_filter('lisfinity__submit_form_fields', [$this, 'set_fields_for_edit_route'], 10, 2);
        add_filter('lisfinity__submit_form_fields', [$this, 'remove_costs'], 10, 2);
    }

    /**
     * Unset the package field if it's an edit and not expired.
     *
     * @param $fields
     * @return mixed
     */
    public function set_fields_for_edit_route($fields) {
        if(strpos($_SERVER['HTTP_REFERER'], 'my-account/edit') !== false) {
            $post_id = intval(basename($_SERVER['HTTP_REFERER']));

            if(0 !== $post_id) {
                $listing_status = get_post_meta($post_id, '_product-status', true);

                if('expired' !== $listing_status) {
                    if(isset($fields['package'])) {
                       unset($fields['package']);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Remove the costs and calculations from the media field
     *
     * @param $fields
     * @return mixed
     */
    public function remove_costs($fields) {
        if (isset($fields['media'])) {
            if (array_key_exists('media_calculation', $fields['media'])) {
                unset($fields['media']['media_calculation']);
            }
            if (array_key_exists('total_calculation', $fields['media'])) {
                unset($fields['media']['total_calculation']);
            }
        }

        return $fields;
    }
}
