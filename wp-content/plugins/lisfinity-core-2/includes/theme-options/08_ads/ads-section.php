<?php
global $opt_name;
Redux::set_section( $opt_name, [
		'title'  => __( 'Listings Setup', 'lisfinity-core' ),
		'id'     => 'ads-settings',
		'desc'   => __( 'Setting to adjust various options used for the listings.', 'lisfinity-core' ),
		'icon'   => 'fa fa-bolt',
		'fields' => [
			[
				'id'      => '_product-search-map',
				'type'    => 'select',
				'title'   => __( 'Listings Map on Search Page', 'lisfinity-core' ),
				'desc'    => __( 'Choose the way map will be displayed on the search page.', 'lisfinity-core' ),
				'options' => [
					'on'        => __( 'Always On (not possible to turn off)', 'lisfinity-core' ),
					'off'       => __( 'Always Off (not possible to turn on)', 'lisfinity-core' ),
					'maybe_on'  => __( 'On (possible to turn off)', 'lisfinity-core' ),
					'maybe_off' => __( 'Off (possible to turn on)', 'lisfinity-core' ),
				],
				'default' => 'maybe_off',
				'select2' => [
					'allowClear' => false,
				]
			],
			[
				'id'      => '_product-search-map-location',
				'type'    => 'select',
				'title'   => __( 'Choose which location you want to display', 'lisfinity-core' ),
				'desc'    => __( 'Choose which location you want to display on the Single Listing Page and Search page Products.', 'lisfinity-core' ),
				'options' => [
					'owner_location'   => __( 'Owner Location', 'lisfinity-core' ),
					'listing_location' => __( 'Listing Location', 'lisfinity-core' ),
				],
				'default' => 'owner_location',
				'select2' => [
					'allowClear' => false,
				]
			],
			[
				'id'    => '_listing-fallback-image',
				'type'  => 'media',
				'title' => __( 'Set the fallback image', 'lisfinity-core' ),
				'desc'  => __( 'Set the fallback image for the listings without images.', 'lisfinity-core' )
			],
			[
				'id'      => '_product-mark-as-sold',
				'type'    => 'switch',
				'title'   => __( 'Show the Mark as Sold option', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether to show the Mark as Sold option in the User Dashboard -> Listings', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_product-owner-verified',
				'type'    => 'switch',
				'title'   => __( 'Show the badge if the owner is verified', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether to show the badge if the listings owner is verified user', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-box-logo-clickable',
				'type'    => 'switch',
				'title'   => __( 'Is Listing Box Logo Clickable?', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether the advertiser logo on the listing box is clickable', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_share-options',
				'type'     => 'select',
				'title'    => __( 'Listing Share Options', 'lisfinity-core' ),
				'desc'     => __( 'Choose listing share options from the list.', 'lisfinity-core' ),
				'options'  => lisfinity_share_options(),
				'default'  => [ 'facebook', 'twitter', 'whatsapp' ],
				'multi'    => true,
				'sortable' => true,
			],
			[
				'id'      => '_display-calculator',
				'type'    => 'select',
				'title'   => __( 'Display Finance Calculator', 'lisfinity-core' ),
				'desc'    => __( 'Display finance calculator on the chosen listing categories or leave empty to disable it completely.', 'lisfinity-core' ),
				'options' => lisfinity_format_product_categories_select(),
				'default' => [],
				'multi'   => true,
			],
			[
				'id'      => '_calculator-position',
				'type'    => 'select',
				'title'   => __( 'Display Finance Calculator', 'lisfinity-core' ),
				'desc'    => __( 'Display finance calculator on the chosen listing categories or leave empty to disable it completely.', 'lisfinity-core' ),
				'options' => [
					'both'    => __( 'In Content and Widget', 'lisfinity-core' ),
					'content' => __( 'In Content', 'lisfinity-core' ),
					'widget'  => __( 'In Widget', 'lisfinity-core' ),
				],
				'default' => 'both',
				'select2' => [
					'allowClear' => true,
				]
			],
			[
				'id'      => '_display-safety-tips',
				'type'    => 'switch',
				'title'   => __( 'Display Safety Tips', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display safety tips widget on listing single page.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'        => '_near-expiration-days',
				'type'      => 'text',
				'title'     => __( 'Near Expiration Days', 'lisfinity-core' ),
				'desc'      => __( 'Choose the number of days a listing has to have remaining in order to be displayed in the Expiring Listings widget in the user dashboard', 'lisfinity-core' ),
				'default'   => '4',
				'attribute' => [
					'type' => 'number',
					'min'  => '1',
					'max'  => '100',
				],
			],
			[
				'id'        => '_recent-listings',
				'type'      => 'text',
				'title'     => __( 'Number of Recent Listings', 'lisfinity-core' ),
				'desc'      => __( 'Choose the number of recent listings per user that should be stored in the database', 'lisfinity-core' ),
				'default'   => '6',
				'attribute' => [
					'type' => 'number',
					'min'  => '1',
					'max'  => '100',
				],
			],
			[
				'id'      => '_listings-delete-expired-listings',
				'type'    => 'switch',
				'title'   => __( 'Delete Expired Listings', 'lisfinity-core' ),
				'desc'    => __( 'Choose if expired listings be automatically removed from the site', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'       => '_delete-attached-media',
				'type'     => 'switch',
				'title'    => esc_html__( 'Delete Media Attached to a Listing', 'lisfinity-core' ),
				'subtitle' => esc_html__( 'Choose if you wish to delete all media attached to a listing when it is being removed from the site', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_listings-delete-expired-listings', '!=', '0' ],
			],
			[
				'id'         => '_listings-expiration-days-removal',
				'type'       => 'text',
				'title'      => __( 'Time to Delete Expired Listings', 'lisfinity-core' ),
				'desc'       => __( 'How many days should pass for the system to delete expired listing from database?', 'lisfinity-core' ),
				'attributes' => [
					'type' => 'number',
					'min'  => 1,
					'max'  => 365,
					'step' => 1,
				],
				'default'    => 30,
				'required'   => [ '_listings-delete-expired-listings', '!=', '0' ],
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Listing Submission', 'lisfinity-core' ),
		'id'         => 'ads-submission-settings',
		'desc'       => __( 'Setting to adjust various options used for the listing submission process.', 'lisfinity-core' ),
		'icon'       => 'fa fa-bullhorn',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_product-status',
				'type'    => 'select',
				'title'   => __( 'Submit Listing Default Status', 'lisfinity-core' ),
				'desc'    => __( 'Choose the default status of an listing after it has been submitted by a member.', 'lisfinity-core' ),
				'options' => [
					'live'         => __( 'Live', 'lisfinity-core' ),
					'pending'      => __( 'Pending Admin Review', 'lisfinity-core' ),
					'live_premium' => __( 'Live (Premium Profiles Only)', 'lisfinity-core' ),
				],
				'default' => 'live',
				'select2' => [
					'allowClear' => false,
				]
			],
			[
				'id'      => '_product-status-edit',
				'type'    => 'select',
				'title'   => __( 'Edit Listing Default Status', 'lisfinity-core' ),
				'desc'    => __( 'Choose the default status of an ad after it has been edited by a member.', 'lisfinity-core' ),
				'options' => [
					'live'         => __( 'Live', 'lisfinity-core' ),
					'pending'      => __( 'Pending Admin Review', 'lisfinity-core' ),
					'live_premium' => __( 'Live (Premium Profiles Only)', 'lisfinity-core' ),
				],
				'default' => 'live',
				'select2' => [
					'allowClear' => false,
				]
			],
			[
				'id'          => '_product-bad-words',
				'type'        => 'textarea',
				'title'       => __( 'Bad Words', 'lisfinity-core' ),
				'desc'        => __( 'Type the words that are forbidden in the listings description. Please, make sure that you separate each word with comma.', 'lisfinity-core' ),
				'default'     => '',
				'placeholder' => __( 'Bad, Word, is, this' )
			],
			[
				'id'      => '_collapsable-multiple-choice-lists',
				'type'    => 'switch',
				'title'   => __( 'Enable collapsing Multiple Choice Lists in the Details step', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you want to enable collapsing Multiple Choice Lists in the Details step.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-stock-manage',
				'type'    => 'switch',
				'title'   => __( 'Enable Stock Managing', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you with to allow listing owners to set a number of available items for sale.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_product-price-types',
				'type'     => 'select',
				'title'    => __( 'Available Pricing Options', 'lisfinity-core' ),
				'desc'     => __( 'Choose pricing types that will be allowed for product submission. If empty all pricing types will be used.', 'lisfinity-core' ),
				'options'  => lisfinity_available_price_types(),
				'default'  => lisfinity_get_default_price_types(),
				'multi'    => true,
				'sortable' => true,
			],
			[
				'id'      => '_product-discounts-deactivated',
				'type'    => 'switch',
				'title'   => __( 'Deactivate Listings Discounts', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you want to deactivate listings discounts.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-duration',
				'type'    => 'text',
				'title'   => __( 'Listing Duration', 'lisfinity-core' ),
				'desc'    => __( 'Number of days a listing will be active and displayed on the site. This option can be be overridden by a pricing package.', 'lisfinity-core' ),
				'default' => '30',
			],
			[
				'id'      => '_enable-listing-images',
				'type'    => 'switch',
				'title'   => __( 'Enable Listing Images', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to allow uploading of the listing images.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_enable-wp-uploader',
				'type'     => 'switch',
				'title'    => __( 'Use WP Media Uploader', 'lisfinity-core' ),
				'desc'     => __( 'Choose if you wish to user WordPress media uploader instead of the system one.', 'lisfinity-core' ),
				'default'  => false,
				'required' => [ '_enable-listing-images', '!=', '0' ],
			],
			[
				'id'       => '_product-images-limit',
				'type'     => 'text',
				'title'    => __( 'Listing Images Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the maximum amount of images that can be uploaded to a single listing during submission.', 'lisfinity-core' ),
				'default'  => '16',
				'required' => [ '_enable-listing-images', '!=', '0' ],
			],
			[
				'id'       => '_product-images-free-limit',
				'type'     => 'text',
				'title'    => __( 'Free Listing Images Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the amount of free images that can be uploaded. Promotion product "addon-image" has to be created in order to monetize on non-free images otherwise all images will be free up to the maximum value set in option "Listing Images Limit".', 'lisfinity-core' ),
				'default'  => '4',
				'required' => [ '_enable-listing-images', '!=', '0' ],
			],
			[
				'id'      => '_enable-listing-docs',
				'type'    => 'switch',
				'title'   => __( 'Enable Listing Documents', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to allow uploading of the listing documents.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_product-documents-limit',
				'type'     => 'text',
				'title'    => __( 'Listing Documents Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the maximum amount of documents that can be uploaded to a single listing during submission.', 'lisfinity-core' ),
				'default'  => '2',
				'required' => [ '_enable-listing-docs', '!=', '0' ],
			],
			[
				'id'       => '_product-documents-free-limit',
				'type'     => 'text',
				'title'    => __( 'Free Listing Documents Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the amount of free documents that can be uploaded. Promotion product "addon-docs" has to be created in order to monetize on non-free images otherwise all images will be free up to the maximum value set in option "Listing Documents Limit".', 'lisfinity-core' ),
				'default'  => '1',
				'required' => [ '_enable-listing-docs', '!=', '0' ],
			],
			[
				'id'      => '_enable-listing-videos',
				'type'    => 'switch',
				'title'   => __( 'Enable Listing Videos', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to allow uploading of the listing videos.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_product-videos-limit',
				'type'     => 'text',
				'title'    => __( 'Listing Videos Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the maximum amount of videos that can be uploaded to a single listing during submission.', 'lisfinity-core' ),
				'default'  => '4',
				'required' => [ '_enable-listing-videos', '!=', '0' ],
			],
			[
				'id'       => '_product-videos-free-limit',
				'type'     => 'text',
				'title'    => __( 'Free Listing Videos Limit', 'lisfinity-core' ),
				'desc'     => __( 'Set the amount of free videos that can be uploaded. Promotion product "addon-videos" has to be created in order to monetize on non-free images otherwise all images will be free up to the maximum value set in option "Listing Images Limit".', 'lisfinity-core' ),
				'default'  => '1',
				'required' => [ '_enable-listing-videos', '!=', '0' ],
			],
			[
				'id'         => '_product-title-limit',
				'type'       => 'text',
				'title'      => __( 'Title Characters Limit', 'lisfinity-core' ),
				'desc'       => __( 'Limit the amount of characters that can be written in the listing title.', 'lisfinity-core' ),
				'attributes' => [
					'max'  => 1000,
					'type' => 'number',
				],
				'default'    => 50,
			],
			[
				'id'         => '_product-description-limit',
				'type'       => 'text',
				'title'      => __( 'Description Characters Limit', 'lisfinity-core' ),
				'desc'       => __( 'Limit the amount of characters that can be written in the listing description.', 'lisfinity-core' ),
				'attributes' => [
					'min'  => 40,
					'max'  => 100000,
					'type' => 'number',
				],
				'default'    => 300,
			],
			[
				'id'      => '_enable-qr-promotion',
				'type'    => 'switch',
				'title'   => __( 'Enable QR code promotion', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable qr code that can be scanned as additional promotion type', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_enable-qr-promotion-download',
				'type'    => 'switch',
				'title'   => __( 'Enable QR code download', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to enable qr code to be downloaded by the users', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_enable-store-referral',
				'type'    => 'switch',
				'title'   => __( 'Enable Store Referral', 'lisfinity-core' ),
				'desc'    => __( 'Enable referral link to the store that is accessible from the product listing page.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-common-first',
				'type'    => 'switch',
				'title'   => __( 'Display Common Fields First?', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display the common fields first on the 2nd step (Details) of the listing submission form', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Listings Feed', 'lisfinity-core' ),
		'id'         => 'ads-feed-settings',
		'desc'       => __( 'Setting to adjust various options used for the listings displaying on the site.', 'lisfinity-core' ),
		'icon'       => 'fa fa-tv',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_search-product-style',
				'type'    => 'image_select',
				'title'   => __( 'Listing Box Style (Search Page)', 'lisfinity-core' ),
				'desc'    => __( 'Choose how the listing boxes will be displayed on the search page and on similar products section on the listing single page.', 'lisfinity-core' ),
				'options' => [
					'1' => [
						'alt' => __( 'Style 1', 'lisfinity-core' ),
						'img' => LISFINITY_CORE_URL . 'dist/statics/options/product_box_1.jpg',
					],
					'2' => [
						'alt' => __( 'Style 2', 'lisfinity-core' ),
						'img' => LISFINITY_CORE_URL . 'dist/statics/options/product_box_2.jpg',
					],
					'3' => [
						'alt' => __( 'Style 3', 'lisfinity-core' ),
						'img' => LISFINITY_CORE_URL . 'dist/statics/options/product_box_3.jpg',
					],
					'4' => [
						'alt' => __( 'Style 4', 'lisfinity-core' ),
						'img' => LISFINITY_CORE_URL . 'dist/statics/options/product_box_4.jpg',
					],
				],
				'default' => '3',
			],
			[
				'id'      => '_search-products-display-sold',
				'type'    => 'switch',
				'title'   => __( 'Display Sold Out Listings', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display sold out listings', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_search-products-per-page',
				'type'    => 'text',
				'title'   => __( 'Listing Number', 'lisfinity-core' ),
				'desc'    => __( 'How many listing per page will be displayed on the search page.', 'lisfinity-core' ),
				'default' => '12',
			],
			[
				'id'      => '_dashboard-products-per-page',
				'type'    => 'text',
				'title'   => __( 'Listing Number in Dashboard', 'lisfinity-core' ),
				'desc'    => __( 'How many listing per page will be displayed in the dashboard.', 'lisfinity-core' ),
				'default' => '12',
			],
			[
				'id'      => '_bump-up-position',
				'type'    => 'text',
				'title'   => __( 'Bump up Listing Position Availability', 'lisfinity-core' ),
				'desc'    => __( 'Set the minimum position of an listing in a category in order for bump up promotion to become available. Product promotion of the type "bump-up" has to be created first.', 'lisfinity-core' ),
				'default' => '4',
			],
			/*[
				'id'      => '_listing-search-cache',
				'type'    => 'switch',
				'title'   => __( 'Cache Search Results', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish the system to store search results in the cache file in order to speed up the site.', 'lisfinity-core' ),
				'default' => false,
			],*/
			[
				'id'      => '_listing-search-custom-scroll',
				'type'    => 'switch',
				'title'   => __( 'Different Content & Page Scroll', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to set the search page to use different scrollbars for the page and the content.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_search-products-sort',
				'type'     => 'select',
				'title'    => __( 'Sort By Options on Search Page', 'lisfinity-core' ),
				'desc'     => __( 'Choose how visitors can sort the listings. Leave empty to disable the filter completely.', 'lisfinity-core' ),
				'options'  => [
					'newest'      => __( 'Newest', 'lisfinity-core' ),
					'price_asc'   => __( 'Price Asc', 'lisfinity-core' ),
					'price_desc'  => __( 'Price Desc', 'lisfinity-core' ),
					'nearby'      => __( 'Nearby', 'lisfinity-core' ),
					'recommended' => __( 'Recommended', 'lisfinity-core' ),
				],
				'multi'    => true,
				'sortable' => true,
				'default'  => [ '' ],
			],
			[
				'id'      => '_search-nearby-format',
				'type'    => 'select',
				'title'   => __( 'Nearby Radius Unit', 'lisfinity-core' ),
				'desc'    => __( 'Should nearby radius be calculated in kilometers or miles.', 'lisfinity-core' ),
				'options' => [
					'km' => __( 'Kilometers', 'lisfinity-core' ),
					'mi' => __( 'Miles', 'lisfinity-core' ),
				],
				'default' => 'km',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_search-nearby-radius',
				'type'    => 'text',
				'title'   => __( 'Nearby Radius Distance', 'lisfinity-core' ),
				'desc'    => __( 'Set the default search radius distance.', 'lisfinity-core' ),
				'default' => '100',
			],
			[
				'id'      => '_search-chosen-labels',
				'type'    => 'switch',
				'title'   => __( 'Search Chosen Values Labels', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display labels for teh chosen search query values or not.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_search-display-count',
				'type'    => 'switch',
				'title'   => __( 'Display Searched Listings Count', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display searched listings count on the search button. Works great with the fast servers.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_search-display-category-select',
				'type'    => 'switch',
				'title'   => __( 'Display Category Select on Category Search', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display category select field on the search by category pages', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Listing Taxonomies', 'lisfinity-core' ),
		'id'         => 'ad-box-taxonomies-settings',
		'desc'       => __( 'Setting to adjust various options used for the listing box taxonomies display.', 'lisfinity-core' ),
		'icon'       => 'fa fa-folder',
		'subsection' => true,
		'fields'     => [
			[
				'id'    => 'ad-box-taxonomies-info',
				'type'  => 'info',
				'title' => esc_html__( 'Listing Box Taxonomies', 'lisfinity-core' ),
				'desc'  => sprintf( '<div style="margin-top: -10px;"><p>%1s</p></div>',
					__( 'Taxonomies that will be displayed on the listing box on a search page. On custom pages you can set taxonomies using Elementor Page builder and Lisfinity Listing Box shortcode.', 'lisfinity-core' )
				),
				'style' => 'info',
			],
			[
				'id'      => '_ad-taxonomy-icon-size',
				'type'    => 'select',
				'title'   => __( 'Default Taxonomy Icon Size', 'lisfinity-core' ),
				'desc'    => __( 'Set the default size of the taxonomy icons in the listings box.', 'lisfinity-core' ),
				'options' => [
					'12' => __( '12px', 'lisfinity-core' ),
					'13' => __( '13px', 'lisfinity-core' ),
					'14' => __( '14px', 'lisfinity-core' ),
					'15' => __( '15px', 'lisfinity-core' ),
					'16' => __( '16px', 'lisfinity-core' ),
					'17' => __( '17px', 'lisfinity-core' ),
					'18' => __( '18px', 'lisfinity-core' ),
					'19' => __( '19px', 'lisfinity-core' ),
					'20' => __( '20px', 'lisfinity-core' ),
					'21' => __( '21px', 'lisfinity-core' ),
					'22' => __( '22px', 'lisfinity-core' ),
					'23' => __( '23px', 'lisfinity-core' ),
					'24' => __( '24px', 'lisfinity-core' ),
				],
				'default' => '12',
			],
			[
				'id'      => '_ad-taxonomy-labels',
				'type'    => 'switch',
				'title'   => __( 'Display Taxonomy Labels', 'lisfinity-core' ),
				'desc'    => __( 'Enable to display the taxonomy labels.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_ad-taxonomy-labels-select',
				'type'     => 'select',
				'title'    => __( 'Choose an option', 'lisfinity-core' ),
				'options'  => [
					'display_icon'   => __( 'Show Icon', 'lisfinity-core' ),
					'icon_and_label' => __( 'Show both Icon and Label', 'lisfinity-core' ),
					'display_label'  => __( 'Show Label', 'lisfinity-core' ),
					'hide_both'      => __( 'Hide both Icon and Label', 'lisfinity-core' ),
				],
				'required' => [ '_ad-taxonomy-labels', '=', true ],
				'desc'     => __( 'Choose which option you wish to display as taxonomy labels in the listing box. You can use prefix field in the Search Builder to override this setting.', 'lisfinity-core' ),
				'default'  => 'display_icon',
			],
			[
				'id'      => '_ad-term-labels-select',
				'type'    => 'select',
				'title'   => __( 'Display Term Labels', 'lisfinity-core' ),
				'options' => [
					'display_icon'  => __( 'Icon', 'lisfinity-core' ),
					'icon_and_term' => __( 'Icon and Term', 'lisfinity-core' ),
					'display_term'  => __( 'term', 'lisfinity-core' ),
				],
				'desc'    => __( 'Choose whether you wish to display term labels in the listing box. You can use prefix field in the Search Builder to override this setting.', 'lisfinity-core' ),
				'default' => 'display_term',
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Listings Compare', 'lisfinity-core' ),
		'id'         => 'ad-compare-settings',
		'desc'       => __( 'Setting to adjust various options used for the listings comparison', 'lisfinity-core' ),
		'icon'       => 'fa fa-th-list',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_ads-compare',
				'type'    => 'switch',
				'title'   => __( 'Listing Compare', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to enable listings comparing across the site.', 'lisfinity-core' ),
				'default' => true,
			],
		],
	]
);

Redux::set_section( $opt_name, [
		'title'      => __( 'Listings Title', 'lisfinity-core' ),
		'id'         => 'ad-title-settings',
		'desc'       => __( 'Setting to adjust various options used for the listings title', 'lisfinity-core' ),
		'icon'       => 'fa fa-th-list',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_ad-title-hide',
				'type'    => 'switch',
				'title'   => __( 'Remove Default Title Field', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to remove the title field from the listing submission in case you wish that the listing title is automatically generated. For the best results make sure to create custom titles in the sections below.', 'lisfinity-core' ),
				'default' => false,
			]
		]
	]
);


Redux::set_section( $opt_name, [
		'title'      => __( 'Listing Page', 'lisfinity-core' ),
		'id'         => 'ad-single-settings',
		'desc'       => __( 'Setting to adjust various options used for the listing single page', 'lisfinity-core' ),
		'icon'       => 'fa fa-newspaper-o',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_ad-likes',
				'type'    => 'switch',
				'title'   => __( 'Listing Likes', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to display how many likes an listing has.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_ad-visits',
				'type'    => 'switch',
				'title'   => __( 'Listing Visits', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to display how many times an listing has been visited.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_ad-published-date',
				'type'    => 'switch',
				'title'   => __( 'Listing Published Date', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to display listing published date.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_ad-similar',
				'type'    => 'switch',
				'title'   => __( 'Listing Similar', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you wish to display similar listings on the listing single page.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'       => '_ad-similar-number',
				'type'     => 'text',
				'title'    => __( 'Similar Listings Number', 'lisfinity-core' ),
				'desc'     => __( 'How many similar listings to be displayed on the listing single page.', 'lisfinity-core' ),
				'default'  => '3',
				'required' => [ '_ad-similar', '=', '1' ],
			],
			[
				'id'      => '_use-product-phones',
				'type'    => 'switch',
				'title'   => __( 'Use Product Phones', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you want to display product phones instead of business owner phones.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_use-product-logo',
				'type'    => 'switch',
				'title'   => __( 'Use Product Logo', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you want to display product logo instead of business owner logo.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_display-product-map',
				'type'    => 'switch',
				'title'   => __( 'Display Product Map', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you want to display product map.', 'lisfinity-core' ),
				'default' => true,
			],
			[
				'id'      => '_display-product-website',
				'type'    => 'switch',
				'title'   => __( 'Display Website Link', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you want to display a website link.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_display-product-email',
				'type'    => 'switch',
				'title'   => __( 'Display Email', 'lisfinity-core' ),
				'desc'    => __( 'Choose whether you want to display an email.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_display-sidebar-promotion',
				'type'    => 'select',
				'title'   => __( 'Display Sidebar Promotion', 'lisfinity-core' ),
				'options' => [
					'always'   => __( 'Always', 'lisfinity-core' ),
					'promoted' => __( 'Promoted Only', 'lisfinity-core' ),
					'never'    => __( 'Never', 'lisfinity-core' ),
				],
				'desc'    => __( 'Choose when you wish to display listings in the sidebar widget.', 'lisfinity-core' ),
				'default' => 'always',
				'select2' => [
					'allowClear' => false,
				],
			],
		],
	]
);

$taxonomies_model = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
$groups_model     = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
$options          = $groups_model->get_options();
$groups           = $groups_model->get_groups_slugs();
if ( ! is_array( $groups ) || ! is_array( $options ) ) {
	return [];
}
if ( empty( $options ) ) {
	$groups[]  = 'common';
	$options[] = [
		'single_name' => 'Common',
		'plural_name' => 'Commons',
		'slug'        => 'common',
	];
}
$slugs = array_column( $options, 'slug' );

foreach ( $groups as $group ) {
	$group     = urldecode( $group );
	$group_key = ! empty( $groups_model->get_options() ) ? array_search( $group, $slugs ) : 0;
	if ( ! empty( $options[ $group_key ] ) ) {
		Redux::set_field( $opt_name, 'ad-box-taxonomies-settings', [
			'id'       => "_-taxonomy--{$group}",
			'type'     => 'select',
			'title'    => sprintf( __( 'Choose %s Taxonomy to display', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ),
			'desc'     => sprintf( __( 'Choose listing info which will be displayed on the listing box per category: <strong>%s</strong>.', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ),
			'options'  => $taxonomies_model->get_taxonomies_by_group( $group, false ),
			'multi'    => true,
			'sortable' => true,
			'default'  => [],
		] );
		Redux::set_field( $opt_name, 'ad-compare-settings', [
			'id'       => "_compare-taxonomy--{$group}",
			'type'     => 'select',
			'title'    => sprintf( __( 'Choose %s Taxonomy to display', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ),
			'desc'     => sprintf( __( 'Choose listing info which will be compared per category: <strong>%s</strong>.', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ),
			'options'  => $taxonomies_model->get_taxonomies_by_group( $group, false ),
			'multi'    => true,
			'sortable' => true,
			'default'  => [],
			'required' => [ '_ads-compare', '=', '1' ],
		] );
	}
}

foreach ( $options as $option ) {
	$variable = urldecode( $option['slug'] );
	Redux::set_field( $opt_name, 'ad-title-settings', [
			'id'      => "_enable-custom-listing-titles-$variable",
			'type'    => 'switch',
			'title'   => __( "Enable Custom Listing {$option['plural_name']}", 'lisfinity-core' ),
			'desc'    => __( 'Enable this option if you wish to custom create listing titles upon submission.', 'lisfinity-core' ),
			'default' => false,
		]
	);
	Redux::set_field( $opt_name, 'ad-title-settings',
		[
			'id'          => "_custom-listing-$variable-title",
			'type'        => 'text',
			'title'       => __( "Listing {$option['plural_name']} Title Format", 'lisfinity-core' ),
			'subtitle'    => __( 'Format listing titles', 'lisfinity-core' ),
			'desc'        => __( 'Format title like this: %%title%% - %%taxonomy_1%% - %%taxonomy_2%%. %%title%% = Listing title | %%taxonomy%% = Any taxonomy slug from the fields builder', 'lisfinity-core' ),
			'placeholder' => esc_html( '%%title%% - (%%make%%)(%%model%%)(%%year%%)' ),
			'default'     => esc_html( '%%title%%' ),
			'required'    => [ "_enable-custom-listing-titles-$variable", '=', '1' ],
		]
	);

}


Redux::set_section( $opt_name, [
		'title'      => __( 'Listing Auction', 'lisfinity-core' ),
		'id'         => 'listing-auction-settings',
		'desc'       => __( 'Setting to adjust various options used for the listing auctions', 'lisfinity-core' ),
		'icon'       => 'fa fa-gavel',
		'subsection' => true,
		'fields'     => [
			[
				'id'      => '_product-enable-random-bidding',
				'type'    => 'switch',
				'title'   => __( 'Enable Random Bidding', 'lisfinity-core' ),
				'desc'    => __( 'Choose if the buyers can bid a random price for the listing or the last bid is the minimum allowed amount', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-enable-reserve-price',
				'type'    => 'switch',
				'title'   => __( 'Enable Reserve Price', 'lisfinity-core' ),
				'desc'    => __( 'The auction will be successful at the end only if this price has been reached.', 'lisfinity-core' ),
				'default' => false,
			],
			[
				'id'      => '_product-start-price-default',
				'type'    => 'select',
				'title'   => __( 'Display Starting Price as Default', 'lisfinity-core' ),
				'desc'    => __( 'Choose if you wish to display the starting price or last bid price as default instead of Buy Now price.', 'lisfinity-core' ),
				'options' => [
					'buy'   => esc_html__( 'Buy Now', 'lisfinity-core' ),
					'start' => esc_html__( 'Starting Price', 'lisfinity-core' ),
					'last'  => esc_html__( 'Last Bid', 'lisfinity-core' ),
				],
				'default' => 'buy',
				'select2' => [
					'allowClear' => false,
				],
			],
			[
				'id'      => '_pay-for-details',
				'type'    => 'switch',
				'title'   => __( 'Enable Commission For Receiving Buyer Details', 'lisfinity-core' ),
				'desc'    => __( 'If enabled auction poster would have to pay the commission to the site in order to receive the details of the winner. In order for the vendor to successfully pay the commission a WooCommerce product of type commission would have to be created.', 'lisfinity-core' ),
				'default' => false,
			],
		],
	]
);
