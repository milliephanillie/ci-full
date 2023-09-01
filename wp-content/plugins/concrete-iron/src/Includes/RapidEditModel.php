<?php

namespace ConcreteIron\Includes;


class RapidEditModel
{
    private $redirect = false;

    protected $data = [];

    protected $is_edit = false;

    protected $packages_enabled = false;

    protected $has_promotions = false;

    protected $has_commission = false;

    protected $is_business = false;

    protected $additional_payment = false;

    protected $submission_commission = false;

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
//        add_filter('lisfinity__submit_form_fields', [$this, 'set_edit_fields']);
//        add_filter('lisfinity__product_fields', [$this, 'edit_product_fields'], 10, 2);
        add_filter('lisfinity__submit_form_fields', [$this, 'remove_costs'], 10, 2);
    }

    public function remove_costs($fields) {
        if (isset($fields['media'])) {
            if (array_key_exists('media_calculation', $fields['media'])) {
                unset($fields['media']['media_calculation']);
            }
            if (array_key_exists('total_calculation', $fields['media'])) {
                unset($fields['media']['total_calculation']);
            }
        }


        error_log("here are the fields");
        error_log(print_r($fields, true));

        return $fields;
    }

    public function edit_product_fields($titles, bool $is_edit = false) {
        error_log(print_r("what is the edit?", true));
        error_log(print_r($is_edit, true));
        error_log(print_r($titles, true));
        if (array_key_exists('packages', $titles) && ! empty($is_edit) && $is_edit === true ) {
            //unset($titles['packages']);
        }

        return $titles;
    }

    public function set_edit_fields($fields)
    {
        // if the route is an edit
        if (!array_key_exists('package', $fields)) {
            return $fields;
        }

        return $fields;
    }
}
