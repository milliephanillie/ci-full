<?php
/**
 * Form submit fields model
 *
 * @author pebas
 * @package forms/submit
 * @version 1.0.0
 */

namespace Lisfinity\Models\Forms;

/**
 * Class FormSubmitModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class FormSubmitModel
{

    protected $fields;

    private $reserved_field_names = [
        'title',
        'description',
        '_price',
        '_sale_price',
        '_stock_custom',
    ];

    public function __construct()
    {
        $this->set_fields();
    }


    /**
     * Get taxonomy form fields
     * ------------------------
     *
     * @return mixed
     */
    public function get_fields()
    {
        return $this->fields;
    }

    public function get_reserved_field_names()
    {
        $this->reserved_field_names;
    }

    public function get_field_ids($type = '', $specific_type = '')
    {
        $ids = [];

        if ( ! empty($this->fields)) {
            foreach ($this->fields as $groups) {
                if ( ! empty($groups)) {
                    foreach ($groups as $id => $field) {
                        if ( ! empty($type) && ! empty($specific_type)) {
                            if ($field['type'] === $type && $field['store_as'] === $specific_type) {
                                $ids[] = $id;
                            }
                        } elseif ( ! empty($specific_type)) {
                            if ( ! empty($field['store_as']) && $field['store_as'] === $specific_type) {
                                $ids[] = $id;
                            }
                        } elseif ( ! empty($type)) {
                            if ($field['type'] === $type) {
                                $ids[] = $id;
                            }
                        } else {
                            $ids[] = $id;
                        }
                    }
                }
            }
        }

        return $ids;
    }

    /**
     * Set terms form fields
     * --------------------------
     *
     * @return array
     */

    protected function set_fields()
    {

        $latitude     = lisfinity_get_option('map-default-latitude');
        $longitude    = lisfinity_get_option('map-default-longitude');
        $zoom         = lisfinity_get_option('map-default-zoom');
        $wp_uploader  = lisfinity_is_enabled(lisfinity_get_option('enable-wp-uploader')) ? 'media' : 'image';
        $sell_on_site = lisfinity_is_enabled(lisfinity_get_option('vendors-enabled', '1'));
        $count        = 0;

        $fields = [
            // 1. fields | general.
            'general'  => [
                'title'       => [
                    'key'         => $count++,
                    'label'       => __('Ad Title', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'attributes'  => [
                        'placeholder' => __('Type title...', 'lisfinity-core'),
                        'max_chars'   => lisfinity_get_option('product-title-limit') ?? 50,
                    ],
                    'required'    => true,
                ],
                '_phone'      => [
                    'key'         => $count++,
                    'label'       => __('Phone', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'attributes'  => [
                        'placeholder' => __('Type phone...', 'lisfinity-core')
                    ],
                ],
                '_website'    => [
                    'key'         => $count++,
                    'label'       => __('Website', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'attributes'  => [
                        'placeholder' => __('Type website...', 'lisfinity-core')
                    ],
                ],
                '_email'      => [
                    'key'         => $count++,
                    'label'       => __('Email', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'attributes'  => [
                        'placeholder' => __('Type email...', 'lisfinity-core')
                    ],
                ],
                'description' => [
                    'key'         => $count++,
                    'label'       => __('Description', 'lisfinity-core'),
                    'value'       => '',
                    'description' => sprintf(__('Max %s characters.',
                        'lisfinity-core'), lisfinity_get_option('product-description-limit') ?? 300),
                    'type'        => 'rich-text',
                    'options'     => [
                        'rich_editing' => true,
                        'hide_buttons' => true,
                        'max_chars'    => lisfinity_get_option('product-description-limit') ?? 300,
                        'bad_words'    => ! empty(lisfinity_get_option('product-bad-words')) ? lisfinity_get_option('product-bad-words') : false,
                    ],
                    'attributes'  => [
                        'placeholder' => __('Type the description of your ad...', 'lisfinity-core'),
                    ],
                    'required'    => true,
                ],
            ],
            // 2. fields | details
            'package'  => [
                'packages' => [
                    'key'         => $count++,
                    'label'       => __('Packages', 'lisfinity-core'),
                    'value'       => 'concrete-equipment',
                    'description' => __('Select a package for your listing.', 'lisfinity-core'),
                    'type'        => 'packages',
                    'product' => 'payment_package',
                    'required'    => true,
                ],
//                'total_calculation' => [
//                    'key'         => $count++,
//                    'label'       => __('Total Costs', 'lisfinity-core'),
//                    'type'        => 'costs',
//                    'field_type'  => 'promo',
//                    'calculation' => 'full',
//                ],
            ],
            // 2. fields | details
            'details'  => [
                'taxonomies' => [
                    'key'         => $count++,
                    'label'       => __('Specifics', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('Select the details for the listings based on the category', 'lisfinity-core'),
                    'type'        => 'taxonomies',
                ],
            ],
            // 3. fields | price.
            'price'    => [
                '_product-price-type'            => [
                    'key'         => $count++,
                    'label'       => __('Select Price Types', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'select',
                    'options'     => lisfinity_get_chosen_price_types(),
                    'additional'  => [
                        'class' => 'w-full bg:w-96%',
                    ],
                ],
                '_product-store-referral'        => [
                    'key'         => $count++,
                    'label'       => __('Store Referral', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('The link that users will be redirected to when trying to buy the listing.', 'lisfinity-core'),
                    'type'        => 'text',
                    'attributes'  => [
                        'type' => 'url',
                    ],
                    'additional'  => [
                        'class' => '-mt-20 mb-20 w-full',
                    ],
                ],
                '_price'                         => [
                    'key'         => $count++,
                    'label'       => __('Price', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'conditional' => [
                        '_product-price-type',
                        ['fixed', 'negotiable', 'per_week', 'per_month', 'per_day', 'per_hour']
                    ],
                    'attributes'  => [
                        'field_type' => 'price',
                    ],
                    'required'    => true,
                    'additional'  => [
                        'class' => ! lisfinity_is_enabled(lisfinity_get_option('product-discounts-deactivated')) ? 'mb-20 bg:mb-0 w-full bg:w-47%' : 'mb-20 bg:mb-0 w-full bg:w-48% mr-20',
                    ],
                ],
                '_sale_price'                    => [
                    'key'         => $count++,
                    'label'       => __('Sale Price', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'conditional' => [
                        '_product-price-type',
                        ['fixed', 'negotiable', 'per_week', 'per_month', 'per_day', 'per_hour']
                    ],
                    'attributes'  => [
                        'field_type' => 'price',
                    ],
                    'required'    => true,
                    'additional'  => [
                        'class' => 'mb-20 bg:ml-20 bg:w-47% w-full',
                    ],
                ],
                '_price_buy_now'                 => [
                    'key'         => $count++,
                    'label'       => __('Buy now price', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('The price to sell the product immediately', 'lisfinity-core'),
                    'type'        => 'text',
                    'conditional' => ['_product-price-type', 'auction'],
                    'attributes'  => [
                        'type' => 'number',
                        'min'  => 1,
                    ],
                    'required'    => true,
                    'additional'  => [
                        'class' => 'mb-20 bg:mb-0 bg:w-47% w-full',
                    ],
                ],
                '_stock_custom'                  => [
                    'key'         => $count++,
                    'label'       => __('Stock Quantity', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'conditional' => ['_product-price-type', ['fixed', 'negotiable']],
                    'attributes'  => [
                        'type' => 'number',
                        'min'  => 1,
                    ],
                    'additional'  => [
                        'class' => ! lisfinity_is_enabled(lisfinity_get_option('product-discounts-deactivated')) ? 'mb-20 bg:mb-0 w-full bg:w-48%' : 'mb-20 bg:mb-0 w-full bg:w-48% ml-3',
                    ],
                ],
                '_product-auction-start-price'   => [
                    'key'         => $count++,
                    'label'       => __('Start Price', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'text',
                    'conditional' => ['_product-price-type', 'auction'],
                    'attributes'  => [
                        'type' => 'number',
                        'min'  => 1,
                    ],
                    'additional'  => [
                        'class' => 'mb-20 bg:ml-20 bg:w-47% w-full',
                    ],
                ],
                '_product-auction-reserve-price' => [
                    'key'         => $count++,
                    'label'       => __('Reserve Price', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('Enter reserve price for the auction. The auction will not be successful unless the specified price has been reached.',
                        'lisfinity-core'),
                    'type'        => 'text',
                    'conditional' => ['_product-price-type', 'auction'],
                    'attributes'  => [
                        'type' => 'number',
                        'min'  => 1,
                    ],
                    'additional'  => [
                        'class' => 'mb-20 bg:w-47% w-full',
                    ],
                ],
                // auction.
                '_product-auction-starts'        => [
                    'key'         => $count++,
                    'type'        => 'date',
                    'label'       => __('Auction Start Time', 'lisfinity-core'),
                    'value'       => '',
                    'conditional' => ['_product-price-type', 'auction'],
                    'placeholder' => __('Pick the date', 'lisfinity-core'),
                    'options'     => [
                        'enableTime'  => true,
                        'minDate'     => 'today',
                        'time_24hr'   => true,
                        'dateFormat'  => 'Y-m-d H:i:S',
                        'altInput'    => true,
                        'altFormat'   => 'M d, Y H:i',
                        'defaultDate' => current_time('mysql'),
                    ],
                    'additional'  => [
                        'class' => ! $sell_on_site ? 'mb-20 bg:mb-0 bg:w-47% w-full bg:ml-20' : 'mb-20 bg:mb-0 bg:w-47% w-full',
                    ],
                ],
                '_product-auction-ends'          => [
                    'key'         => $count++,
                    'label'       => __('Auction End Time', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'date',
                    'conditional' => ['_product-price-type', 'auction'],
                    'options'     => [
                        'enableTime'  => true,
                        'minDate'     => 'today',
                        'time_24hr'   => true,
                        'dateFormat'  => 'Y-m-d H:i:S',
                        'altInput'    => true,
                        'altFormat'   => 'M d, Y H:i',
                        'defaultDate' => gmdate('Y-m-d H:i:s',
                            (time() + (get_option('gmt_offset') * HOUR_IN_SECONDS) + (10 * DAY_IN_SECONDS))),
                    ],
                    'additional'  => [
                        'class' => $sell_on_site ? 'bg:w-47% w-full bg:ml-20' : 'bg:w-47% w-full',
                    ],
                ],
                '_product-price-sell-on-site'    => [
                    'key'         => $count++,
                    'type'        => 'checkbox',
                    'label'       => __('Sell on site?', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('The product will be sold through this platform and vendors share released on the specified date.',
                        'lisfinity-core'),
                    'conditional' => [
                        '_product-price-type',
                        ['fixed', 'auction', 'negotiable', 'per_week', 'per_month', 'per_day', 'per_hour']
                    ],
                    'additional'  => [
                        'class' => 'bg:mt-20',
                    ],
                ],
            ],
            // 4. fields | address.
            'address'  => [
                '_product-location' => [
                    'key'         => $count++,
                    'label'       => __('Location', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'location',
                    'taxonomy'    => true,
                    'props'       => [
                        'google'    => '1' === lisfinity_get_option('location-autogenerate'),
                        'api'       => lisfinity_get_option('map-api'),
                        'latitude'  => ! empty($latitude) ? $latitude : 40.712776,
                        'longitude' => ! empty($longitude) ? $longitude : 74.005974,
                        'zoom'      => ! empty ($zoom) ? $zoom : 14,
                        'coords'    => true,
                    ],
                    'required'    => true,
                ],
            ],
            // 5. fields | media
            'media'    => [
                '_product_image_gallery' => [
                    'key'             => $count++,
                    'label'           => __('Images', 'lisfinity-core'),
                    'value'           => '',
                    'description'     => __('The first image will be used as a cover. You can click and drag to sort images.', 'lisfinity-core'),
                    'type'            => $wp_uploader,
                    'store_as'        => 'image',
                    'type_filter'     => 'image',
                    'product_gallery' => true,
                    'labels'          => [__('image', 'lisfinity-core'), __('images', 'lisfinity-core')],
                    'limit'           => lisfinity_get_option('product-images-limit'),
                    'multiple'        => true,
                    'size_limit'      => lisfinity_get_maximum_upload_size_setting()['output'],
                    'props'           => [
                        'type'               => __('Images', 'lisfinity-core'),
                        'button_label'       => __('Upload Images', 'lisfinity-core'),
                        'media_button_label' => __('Choose Images', 'lisfinity-core'),
                        'media_title'        => __('Product Images', 'lisfinity-core'),
                    ],
                    //'required'    => true,
                ],
                '_product-files'         => [
                    'key'         => $count++,
                    'label'       => __('Additional Documents', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'media',
                    'store_as'    => 'file',
                    'limit'       => lisfinity_get_option('product-documents-limit'),
                    'labels'      => [__('document', 'lisfinity-core'), __('documents', 'lisfinity-core')],
                    'type_filter' => 'application',
                    'multiple'    => true,
                    'props'       => [
                        'type'               => __('Documents', 'lisfinity-core'),
                        'button_label'       => __('Upload Documents', 'lisfinity-core'),
                        'media_button_label' => __('Choose Documents', 'lisfinity-core'),
                        'media_title'        => __('Product Documents', 'lisfinity-core'),
                    ],
                ],
                '_product-videos'        => [
                    'key'         => $count++,
                    'label'       => __('Promo Videos', 'lisfinity-core'),
                    'value'       => '',
                    'description' => __('Enter link to YouTube or Vimeo videos', 'lisfinity-core'),
                    'type'        => 'media',
                    'limit'       => lisfinity_get_option('product-videos-limit'),
                    'labels'      => [__('video', 'lisfinity-core'), __('videos', 'lisfinity-core')],
                ],
                '_product-qr'            => [
                    'key'         => $count++,
                    'label'       => __('QR Code', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'qr',
                    'limit'       => lisfinity_get_option('product-videos-limit'),
                    'labels'      => [
                        __('Add QR code image', 'lisfinity-core'),
                        __('Ad QR code link', 'lisfinity-core')
                    ],
                    'img_data'    => [
                        'type'        => 'media',
                        'store_as'    => 'image',
                        'type_filter' => 'image',
                        'labels'      => [__('image', 'lisfinity-core'), __('images', 'lisfinity-core')],
                        'limit'       => lisfinity_get_option('product-images-limit'),
                        'multiple'    => false,
                        'no_preview'  => true,
                    ],
                ],
                'media_calculation'      => [
                    'key'         => $count++,
                    'label'       => __('Total media costs', 'lisfinity-core'),
                    'type'        => 'costs',
                    'field_type'  => 'media',
                    'calculation' => 'simple',
                ],
//                'total_calculation'      => [
//                    'key'        => $count++,
//                    'label'      => __('Total Costs', 'lisfinity-core'),
//                    'type'       => 'costs_additional',
//                    'field_type' => 'promo',
//                ],
            ],
//            // 6. fields | promotion.
//            'promotions' => [
//                'promotions'        => [ // has to be called promotions
//                    'key'         => $count++,
//                    'label'       => __('Promotions', 'lisfinity-core'),
//                    'value'       => '',
//                    'type'        => 'promotions',
//                    'product'     => 'promotion'
//                ],
//                'total_calculation' => [
//                    'key'         => $count++,
//                    'label'       => __('Total Costs', 'lisfinity-core'),
//                    'type'        => 'costs',
//                    'field_type'  => 'promo',
//                    'calculation' => 'full',
//                ],
//            ],
        ];

        if ( ! $sell_on_site) {
            unset($fields['price']['_product-price-sell-on-site']);
        }

        $promotions_enabled = lisfinity_is_enabled(lisfinity_get_option('site-promotions'));

        if ( ! $promotions_enabled) {
            unset($fields['promotions']);

            $fields['media'] = [
                '_product_image_gallery' => [
                    'key'         => $count++,
                    'label'       => __('Images', 'lisfinity-core'),
                    'value'       => '',
                    'description'     => __('The first image will be used as a cover. You can click and drag to sort images.', 'lisfinity-core'),
                    'type'        => $wp_uploader,
                    'store_as'    => 'image',
                    // todo make this optional and selectable from the theme options.
                    'type_filter' => 'image',
                    'labels'      => [__('image', 'lisfinity-core'), __('images', 'lisfinity-core')],
                    'limit'       => lisfinity_get_option('product-images-limit'),
                    'multiple'    => true,
                    'props'       => [
                        'type'               => __('Images', 'lisfinity-core'),
                        'button_label'       => __('Upload Images', 'lisfinity-core'),
                        'media_button_label' => __('Choose Images', 'lisfinity-core'),
                        'media_title'        => __('Product Images', 'lisfinity-core'),
                    ],
                    //'required'    => true,
                ],
                '_product-files'         => [
                    'key'         => $count++,
                    'label'       => __('Additional Documents', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'media',
                    'store_as'    => 'file',
                    'limit'       => lisfinity_get_option('product-documents-limit'),
                    'labels'      => [__('document', 'lisfinity-core'), __('documents', 'lisfinity-core')],
                    // todo make this optional and selectable from the theme options.
                    'type_filter' => ['application'],
                    'size_limit'  => lisfinity_get_maximum_upload_size_setting()['output'],
                    'props'       => [
                        'type'               => __('Documents', 'lisfinity-core'),
                        'button_label'       => __('Upload Documents', 'lisfinity-core'),
                        'media_button_label' => __('Choose Documents', 'lisfinity-core'),
                        'media_title'        => __('Product Documents', 'lisfinity-core'),
                    ],
                ],
                '_product-videos'        => [
                    'key'         => $count++,
                    'label'       => __('Promo Videos', 'lisfinity-core'),
                    'value'       => '',
                    'type'        => 'media',
                    'limit'       => lisfinity_get_option('product-videos-limit'),
                    'labels'      => [__('video', 'lisfinity-core'), __('videos', 'lisfinity-core')],
                ],
                'media_calculation'      => [
                    'key'         => $count++,
                    'label'       => __('Total media costs', 'lisfinity-core'),
                    'type'        => 'costs',
                    'field_type'  => 'media',
                    'calculation' => 'simple',
                ],
                'total_calculation'      => [
                    'key'         => $count++,
                    'label'       => __('Total Costs', 'lisfinity-core'),
                    'type'        => 'costs',
                    'field_type'  => 'promo',
                    'calculation' => 'full',
                ],
            ];
        }

        if (lisfinity_is_enabled(lisfinity_get_option('ad-title-hide'))) {
            unset($fields['general']['title']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('product-stock-manage'))) {
            unset($fields['price']['_stock_custom']);
        }
        if ( ! lisfinity_get_option('phone-number-listing-submission-form')) {
            unset($fields['general']['_phone']);
        }
        if ( ! lisfinity_get_option('website-listing-submission-form')) {
            unset($fields['general']['_website']);
        }
        if ( ! lisfinity_get_option('email-listing-submission-form')) {
            unset($fields['general']['_email']);
        }
        if (lisfinity_is_enabled(lisfinity_get_option('vendors-only'))) {
            unset($fields['price']['_product-price-sell-on-site']);
        }
        if (lisfinity_is_enabled(lisfinity_get_option('product-discounts-deactivated'))) {
            unset($fields['price']['_sale_price']);
        }
        $media_disabled = [];
        if ( ! lisfinity_is_enabled(lisfinity_get_option('enable-listing-images'))) {
            $media_disabled[] = 'image';
            unset($fields['media']['_product_image_gallery']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('enable-listing-docs'))) {
            $media_disabled[] = 'doc';
            unset($fields['media']['_product-files']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('enable-listing-videos'))) {
            $media_disabled[] = 'video';
            unset($fields['media']['_product-videos']);
        }
        if ($media_disabled === ['image', 'doc', 'video']) {
            unset($fields['media']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('enable-qr-promotion'))) {
            unset($fields['media']['_product-qr']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('enable-store-referral'))) {
            unset($fields['price']['_product-store-referral']);
        }
        if ( ! lisfinity_is_enabled(lisfinity_get_option('product-enable-reserve-price'))) {
            unset($fields['price']['_product-auction-reserve-price']);
        }

        $this->fields = apply_filters('lisfinity__submit_form_fields', $fields);

        return $this->fields;
    }

}
