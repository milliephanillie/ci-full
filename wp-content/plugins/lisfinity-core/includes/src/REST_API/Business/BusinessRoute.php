<?php


namespace Lisfinity\REST_API\Business;

use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Models\Users\ProfilesModel;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;

class BusinessRoute extends Route
{

	private $business_id;

	private $letter;

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'business_type' => [
			'path' => '/business/type/(?P<business>\d+)',
			'rest_path' => '/business/type',
			'callback' => 'get_business_type',
			'permission_callback' => 'allow_access',
			'methods' => 'GET',
		],
		'business' => [
			'path' => '/business/(?P<business>\d+)',
			'rest_path' => '/business',
			'callback' => 'get_business',
			'permission_callback' => 'allow_access',
			'methods' => 'GET',
		],
		'business_archive' => [
			'path' => '/business/archive',
			'callback' => 'get_businesses',
			'permission_callback' => 'allow_access',
			'methods' => 'GET',
		],
	];

	public function get_businesses(WP_REST_Request $request_data)
	{
		$data = $request_data->get_params();

		$vendors_model = new ProfilesModel();
		$vendors = new \stdClass();

		$this->letter = $data['letter'] ?? '';

		// vendors query.
		$offset = (absint($data['page']) - 1) * 18;
		$promoted = lisfinity_get_promoted_businesses();
		$query_args = ['offset' => $offset, 'fields' => 'ids'];
		if (!empty($data['keyword'])) {
			$query_args['s'] = $data['keyword'];
		}
		if (!empty($data['order']) && 'all' !== $data['order']) {
			$query_args['meta_query'][] = [
				'key' => '_business_average_rating',
				'value' => [floatval($data['order']) - 0.5, floatval($data['order']) + 0.5,],
				'compare' => 'BETWEEN',
				'type' => 'meta_value_num',
			];
		}
		if (!empty($promoted)) {
			$query_args['post__not_in'] = $promoted;
		}
		if (!empty($data['letter'])) {
			add_filter('posts_where', [$this, 'business_where_letter'], 10, 2);
		}
		$vendors_query = $vendors_model->get_vendors_query($query_args);
		if (!empty($data['letter'])) {
			remove_filter('posts_where', [$this, 'business_where_letter']);
		}

		$vendors_posts = [
			'all' => $vendors_query,
			'promoted' => $this->format_vendors_meta($promoted),
			'query' => $this->format_vendors_meta($vendors_query->posts),
			'count' => $vendors_query->post_count,
			'max_num_pages' => $vendors_query->max_num_pages,
			'found_posts' => $vendors_query->found_posts,
			'page' => absint($data['page']) ?? 1,
			'offset' => $offset,
		];
		$vendors->vendors = $vendors_posts;

		$response = rest_ensure_response($vendors);
		$response->header('X-WP-Total', (int)$vendors->vendors['found_posts']);
		$response->header('X-WP-TotalPages', (int)$vendors->vendors['max_num_pages']);
		$response->header('X-WP-Page', (int)$vendors->vendors['page']);

		if (isset($data['vendorsOnly'])) {
			return $vendors;
		}

		// page options.
		$bg_image_id = carbon_get_post_meta($data['page_id'], 'page-header-image');
		$bg_position = carbon_get_post_meta($data['page_id'], 'page-header-image-position');
		$overlay_color = lisfinity_hex_to_rgba(carbon_get_post_meta($data['page_id'], 'page-header-overlay'));
		$overlay_opacity = carbon_get_post_meta($data['page_id'], 'page-overlay-opacity');
		$options = [
			'title' => get_the_title($data['page_id']),
			'background-image' => $bg_image_id ? wp_get_attachment_image_url($bg_image_id, 'full') : false,
			'background-position' => $bg_position,
			'background-overlay' => "{$overlay_color['red']}, {$overlay_color['green']}, {$overlay_color['blue']}, {$overlay_opacity}",
		];
		$vendors->options = $options;
		$vendors->letters = lisfinity_get_businesses_letters();

		return $vendors;
	}

	public function business_where_letter($sql)
	{
		global $wpdb;
		$sql .= $wpdb->prepare(" AND LOWER( post_title ) LIKE %s ", mb_strtolower($this->letter) . '%');

		return $sql;
	}

	public function format_vendors_meta($vendors)
	{
		$vendor = [];
		if (!empty($vendors)) {
			$location_format = lisfinity_get_option('format-location');
			foreach ($vendors as $vendor_id) {
				$vendor[$vendor_id]['title'] = get_post_field('post_title', $vendor_id);
				$vendor[$vendor_id]['rating'] = number_format_i18n(lisfinity_calculate_business_rating($vendor_id), 1) ?? number_format_i18n(carbon_get_theme_option('business-reviews-default-rating'));
				$vendor[$vendor_id]['thumbnail'] = has_post_thumbnail($vendor_id) ? get_the_post_thumbnail_url($vendor_id, 'premium-profile-image') : false;
				$vendor[$vendor_id]['location'] = carbon_get_post_meta($vendor_id, 'profile-location');
				$vendor[$vendor_id]['location_formatted'] = lisfinity_format_location($vendor_id, 'full' === $location_format, false, explode(',', $vendor[$vendor_id]['location']['address']));
				$vendor[$vendor_id]['url'] = get_permalink($vendor_id);
			}
		}

		return $vendor;
	}

	public function get_business_type(WP_REST_Request $request_data)
	{
		$data = $request_data->get_params();

		if (empty($data['business'])) {
			return false;
		}

		if (lisfinity_is_enabled(lisfinity_get_option('all-businesses-premium'))) {
			return 'business';
		}

		$model = new PromotionsModel();

		$premium = $model->where([
			['product_id', $data['business']],
			['status', 'active'],
			['expires_at', '>', 'NOW()'],
		])->get('1', '', 'id, user_id, product_id, wc_product_id, status, expires_at, created_at');

		return !empty($premium) && 'business' === carbon_get_user_meta($premium[0]->user_id, 'account-type');
	}

	public function get_business(WP_REST_Request $request_data)
	{
		$data = $request_data->get_params();

		if (empty($data['business'])) {
			return false;
		}

		$this->business_id = $data['business'];
		$location_format = lisfinity_get_option('format-location');
		$business = new \stdClass();
		$premium_profile = new \stdClass();

		$business->ID = $this->business_id;
		$premium_profile->title = get_post_field('post_title', $business->ID);
		$premium_profile->slug = sanitize_title($premium_profile->title);
		$premium_profile->rating = number_format_i18n(lisfinity_calculate_business_rating($business->ID), 1);


		$business->title = get_post_field('post_title', $business->ID);

		$business->thumbnail = has_post_thumbnail($business->ID) ? get_the_post_thumbnail_url($business->ID, 'full') : false;
		$business->about_us = get_post_field('post_content', $business->ID);
		$business->type = get_post_meta($business->ID, '_profile-type', true);
		$business->expiration = get_post_meta($business->ID, '_profile-expiration', true);

		$business->phones = carbon_get_post_meta($business->ID, 'profile-phones');

		$business->telegram = carbon_get_post_meta($business->ID, 'profile-telegram');

		$business->location = carbon_get_post_meta($business->ID, 'profile-location');
		$business->location_formatted = lisfinity_format_location($business->ID, 'full' === $location_format, false, explode(',', $business->location['address']));
		if (lisfinity_is_enabled(lisfinity_get_option('members_listings_details'))) {
			$business->location_map_show = lisfinity_get_option('membership-address');
		} else {
			$business->location_map_show = true;
		}

		$business->url = get_permalink($business->ID);
		$banner = get_post_meta($business->ID, '_profile-banner', true);

		if (!empty($banner)) {
			$business->banner = wp_get_attachment_image_url($banner, 'full');
		} else if ('personal' === carbon_get_user_meta($business->ID, 'account-type') && lisfinity_is_enabled(lisfinity_get_option('all-businesses-premium'))) {
			$business->banner = lisfinity_get_option('premium-page-banner-bg')['url'];
		} else {
			$business->banner = false;
		}


		$social = new \stdClass();
		$social->facebook = get_post_meta($business->ID, '_profile-social-facebook', true);
		$social->twitter = get_post_meta($business->ID, '_profile-social-twitter', true);
		$social->instagram = get_post_meta($business->ID, '_profile-social-instagram', true);
		$social->vk = get_post_meta($business->ID, '_profile-social-vk', true);
		$business->social = $social;

		$testimonial = new \stdClass();
		$review_options = carbon_get_theme_option('business-reviews-options');
		$review_enabled = get_option('_business-reviews-enable');
		if ($review_enabled) {
			$testimonial->limit = get_option('_business-reviews-characters-limit');
			$testimonial->default = get_option('_business-reviews-default-rating');
			$options = array_column($review_options, 'review-option');
			$options_array = [];
			if (!empty($options)) {
				foreach ($options as $option) {
					$options_array_element =
						[
							'label' => $option,
							'slug' => sanitize_title($option),

						];
					$options_array[] = $options_array_element;
				}
			}
			$testimonial->options = $options_array;
			$business->testimonial = $testimonial;
		} else {
			$business->testimonial = false;
		}

		// premium profile working times
		$hours_enabled = carbon_get_post_meta( $business->ID, 'profile-hours-enable' );
		$weekdays      = lisfinity_days_of_the_week( true, true );
		$hours         = [];
		if ( 'yes' === $hours_enabled ) {
			$day_count = 1;
			foreach ( $weekdays as $slug => $label ) {
				$hours_type                  = carbon_get_post_meta( $business->ID, "profile-hours-monday-type" );
				$hours[ $day_count ]['day']  = $weekdays[ $slug ];
				$hours[ $day_count ]['type'] = $hours_type;
				if ( 'working' === $hours_type ) {
					$options = carbon_get_post_meta( $business->ID, "profile-hours-{$slug}-hours" );
					if ( ! empty( $options ) ) {
						$option_count = 0;
						foreach ($options as $option) {
							if (!empty($option['open'])) {
								$hours[$day_count]['hours'][$option_count]['open'] = date(lisfinity_get_formatted_time(false, ':', false), strtotime($option['open']));
							} else {
								$hours[$day_count]['hours'][$option_count]['open'] = __('Not set', 'lisfinity-core');
							}
							if (!empty($option['close'])) {
								$hours[$day_count]['hours'][$option_count]['close'] = date(lisfinity_get_formatted_time(false, ':', false), strtotime($option['close']));
							} else {
								$hours[$day_count]['hours'][$option_count]['close'] = __('Not set', 'lisfinity-core');
							}
							$option_count += 1;
						}
					}
				}
				$day_count += 1;
			}
		}
		$business->hours_enabled = 'yes' === $hours_enabled;
		$business->hours = $hours;
		$business->current_day = lisfinity_get_current_weekday();
		$business->website = carbon_get_post_meta($business->ID, 'profile-website');
		$business->email = carbon_get_post_meta($business->ID, 'profile-email');
		$premium_profile->premium_profile = $business;

		$premium_profile->author = get_post_field('post_author', $business->ID);
		$premium_profile->products = $this->get_products($data, $business->ID);

		return $premium_profile;
	}

	protected function get_products($data, $author)
	{
		$products_per_page = lisfinity_get_option('search-products-per-page');
		$args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			//'posts_per_page' => - 1,
			'posts_per_page' => $products_per_page,
			'tax_query' => [
				[
					'taxonomy' => 'product_type',
					'field' => 'name',
					'terms' => 'listing',
					'operator' => 'IN',
				],
			],
			'meta_key' => '_product-business',
			'meta_value' => $author,
		];

		// offset pages if needed to recreate pagination.
		$result['page'] = !empty($data['offset']) ? $data['offset'] : 1;

		if (!empty($data['offset'])) {
			$offset = (absint($result['page']) - 1) * $args['posts_per_page'];
			$args['offset'] = $offset;
		}

		// query products.
		$products = new \WP_Query($args);

		$result['products'] = $this->prepare_products_for_display($products->posts, isset($data['fromPage']));

		$result['found_posts'] = $products->found_posts;
		$result['max_num_pages'] = $products->max_num_pages;
		$result['count'] = $products->count;

		$response = rest_ensure_response($result);
		$response->header('X-WP-Total', (int)$result['found_posts']);
		$response->header('X-WP-TotalPages', (int)$result['max_num_pages']);
		$response->header('X-WP-Page', (int)$result['page']);

		return $result;

	}

	/**
	 * Prepare the products data to be used in a query
	 * -----------------------------------------------
	 *
	 * @param array $products
	 * @param bool $is_detailed
	 *
	 * @return mixed
	 */
	public function prepare_products_for_display($products, $is_detailed)
	{
		if (empty($products)) {
			return $products;
		}

		foreach ($products as $product) {
			$owner = carbon_get_post_meta($product->ID, 'product-owner');
			$account_type = carbon_get_user_meta($owner, 'account-type');
			$data['author_id'] = get_post_meta($product->ID, '_product-owner', true);
			$wc_product = wc_get_product($product->ID);
			$location_format = lisfinity_get_option('format-location');
			$product_meta['price'] = (int)get_post_meta($product->ID, '_price', true) * lisfinity_get_chosen_currency_rate();
			$product->meta = [];
			$product->meta['location'] = carbon_get_post_meta($product->ID, 'product-location');
			$product->meta['product_type'] = carbon_get_post_meta($product->ID, 'product-type');
			$product->meta['price_type'] = !empty($wc_product->get_sale_price()) ? 'on-sale' : carbon_get_post_meta($product->ID, 'product-price-type');
			$product->type = carbon_get_post_meta($product->ID, 'product-category');
			$product->thumbnail = has_post_thumbnail($product->ID) ? get_the_post_thumbnail_url($product->ID, 'full') : false;
			$product->permalink = get_permalink($product->ID);
			$product->premium_profile = lisfinity_get_premium_profile($product->post_author);
			$user_avatar = carbon_get_user_meta($owner, 'avatar');
			$product->user_avatar = wp_get_attachment_image_url($user_avatar, 'big');
			$product->user_verified = carbon_get_user_meta($data['author_id'], 'verified');
			$product->profile_image = has_post_thumbnail($product->premium_profile) ? get_the_post_thumbnail_url($product->premium_profile, 'premium-profile-image') : false;
			$product->on_sale = !empty($wc_product->get_sale_price());
			$product->price_html = lisfinity_get_price_html($product_meta['price']);
			$product->location_formatted = lisfinity_format_location($product->ID, 'full' === $location_format);
			$product->rating = number_format_i18n(lisfinity_calculate_business_rating($product->premium_profile->ID), 1);
			$product->promoted_color = lisfinity_is_promoted_product($product->ID, 'bump-color');
			$product->account_type = $account_type;
			$product->description = $product->post_content;

			// taxonomies setup
			$product->taxonomies = $this->format_product_taxonomies($product->ID, $product->type);

			if ('auction' === $product->meta['price_type']) {
				$product->meta['auction_status'] = carbon_get_post_meta($product->ID, 'product-auction-status');
				$product->meta['auction_ends'] = carbon_get_post_meta($product->ID, 'product-auction-ends');
				$product_meta['sell_on_site'] = carbon_get_post_meta($product->ID, 'product-price-sell-on-site');
				$start_price = carbon_get_post_meta($product->ID, 'product-auction-start-price');
				$start_price = !empty($start_price) ? $start_price * lisfinity_get_chosen_currency_rate() : 1 * lisfinity_get_chosen_currency_rate();
				$product->meta['start_price'] = lisfinity_get_price_html($start_price, $wc_product);
			}
		}

		return $products;
	}

	/**
	 * Format product taxonomies so we can use them in a product box
	 * -------------------------------------------------------------
	 *
	 * @param int $product_id
	 * @param string $type
	 *
	 * @return array
	 */
	protected function format_product_taxonomies($product_id, $type)
	{
		$search_page = lisfinity_get_page_id('page-search');
		$taxonomies = lisfinity_get_option("taxonomy--{$type}");
		$tax = [];

		if (!empty($taxonomies)) {
			$taxonomy_model = new TaxonomiesAdminModel();
			foreach ($taxonomies as $taxonomy) {
				$options = $taxonomy_model->get_taxonomy_options($taxonomy);
				$terms = get_the_terms($product_id, $taxonomy);
				$has_icon = !empty($options['icon']);
				$icon_size_default = lisfinity_get_option('ad-taxonomy-icon-size');

				if (!is_wp_error($terms)) {
					if (!empty($terms[0])) {
						$tax[$taxonomy]['name'] = lisfinity_convert_slug_to_name($taxonomy);
						$tax[$taxonomy]['icon'] = $has_icon ? wp_get_attachment_image_url($options['icon'], 'full') : false;
						$tax[$taxonomy]['icon-size'] = $options['icon-size'] ?? $icon_size_default;
						$tax[$taxonomy]['term'] = $terms[0]->name;
						$link = add_query_arg("tax[{$taxonomy}]", $terms[0]->slug, get_permalink($search_page));
						$tax[$taxonomy]['link'] = $link;
					}
				}

			}
		}

		return $tax;
	}

}
