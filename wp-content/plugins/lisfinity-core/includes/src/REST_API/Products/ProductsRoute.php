<?php


namespace Lisfinity\REST_API\Products;

use Lisfinity\Models\Compare\CompareModel;
use Lisfinity\Models\Forms\FormSubmitModel;
use Lisfinity\Models\Notifications\NotificationModel;
use Lisfinity\Models\PackageModel;
use Lisfinity\Models\ProductModel;
use Lisfinity\Models\PromotionsModel;
use Lisfinity\Models\SearchBuilder\SearchBuilderModel;
use Lisfinity\Models\Stats\StatModel;
use Lisfinity\Models\SubscriptionModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\REST_API\Search\SearchRoute;
use WP_REST_Request;
use Lisfinity\Abstracts\Route as Route;
use WC_Product_Payment_Package as Payment_Package;

class ProductsRoute extends Route {

	public $premium_only = false;

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'product'                => [
			'path'                => '/product/(?P<product>\d+)',
			'rest_path'           => '/product',
			'callback'            => 'get_product',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'get_product_method'     => [
			'path'                => '/product/method',
			'callback'            => 'get_product_method',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'product_action'         => [
			'path'                => '/product-action/(?P<action>\S+)',
			'rest_path'           => '/product-action',
			'callback'            => 'product_action',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'packages'               => [
			'path'                => '/packages/(?P<type>\S+)',
			'rest_path'           => '/packages',
			'callback'            => 'get_packages',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
		'package_and_promotions' => [
			'path'                => '/package/get-package',
			'callback'            => 'get_package_and_promotions',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'product_data'           => [
			'path'                => '/product/get-data',
			'callback'            => 'get_product_data',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'check_package'          => [
			'path'                => '/package/check',
			'callback'            => 'check_package',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
		'compare_products'       => [
			'path'                => '/compare',
			'callback'            => 'get_compares',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'compare_remove'         => [
			'path'                => '/compare-remove',
			'callback'            => 'compare_remove_product',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'request_call'           => [
			'path'                => '/request-call',
			'callback'            => 'request_call',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'send_message'           => [
			'path'                => '/send-message',
			'callback'            => 'send_message',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		],
		'query_attachments'      => [
			'path'                => '/query-attachments',
			'callback'            => 'query_attachments',
			'permission_callback' => 'is_user_logged_in',
			'methods'             => 'POST',
		],
	];

	public function get_product_method( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		if ( 'permalink' === $data['method'] ) {
			$result['success']   = true;
			$result['permalink'] = get_the_permalink( $data['id'] );
		}

		return $result;
	}

	public function compare_remove_product( WP_REST_Request $request ) {
		$data = $request->get_params();

		$model = new CompareModel();
		$query = $model->where( [
			[ 'product_id', $data['product'] ],
			[ 'user_id', str_replace( '.', '', $data['user_id'] ) ]
		], false )->destroy();

		return $query;
	}

	public function get_compares( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		$model = new CompareModel();
		if ( false === $data['type'] ) {
			$type         = $model->get_last_compare_product( $data['id'] );
			$data['type'] = ! empty( $type ) ? $type->product_type : '';
		}
		$products = $model->get_compare_products( str_replace( '.', '', $data['id'] ), $data['type'] );

		$result = new \stdClass();

		$taxonomy_slugs       = $this->get_compare_taxonomies( $data['type'] );
		$result->taxonomies   = $this->get_compare_taxonomies( $data['type'], false );
		$result->taxonomies[] = __( 'Price', 'lisfinity-core' );

		if ( ! empty( $products ) ) {
			$products_model   = new ProductModel();
			$query            = $products_model->get_products_query( [ 'post__in' => $products ] );
			$result->products = $query->posts;
			foreach ( $result->products as $product ) {
				$wc_product = wc_get_product( $product->ID );

				$product->taxonomies                  = $this->format_product_taxonomies( $product->ID, $data['type'], false, $taxonomy_slugs, true );
				$product->taxonomies['price']['term'] = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();

				$product->thumbnail           = has_post_thumbnail( $product->ID ) ? get_the_post_thumbnail_url( $product->ID, 'big' ) : false;
				$product->currency            = get_woocommerce_currency_symbol();
				$product->decimals_separator  = wc_get_price_decimal_separator();
				$product->thousands_separator = wc_get_price_thousand_separator();
			}
		}

		return $result;
	}

	public function get_compare_taxonomies( $type, $slugs_only = true ) {
		$taxonomies = lisfinity_get_option( "compare-taxonomy--{$type}" );
		$taxes      = [];

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( $slugs_only ) {
					$taxes[] = $taxonomy;
				} else {
					$taxes[] = str_replace( '-', ' ', ucwords( $taxonomy ) );
				}
			}
		}

		return $taxes;

	}

	public function product_action( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		if ( empty( $data['action'] ) ) {
			return false;
		}

		if ( $data['action'] === 'like' ) {
			$result = $this->manage_likes( $data['product_id'] );
		}

		if ( $data['action'] === 'compare' ) {
			$result = $this->manage_compare( $data );
		}

		if ( $data['action'] === 'delete' ) {
			$result = $this->delete_product( $data );
		}

		if ( $data['action'] === 'mark-sold' ) {
			$result = $this->mark_product_as_sold( $data );
		}

		if ( $data['action'] === 'block-profile' ) {
			$result = $this->mark_product_as_sold( $data );
		}

		return $result;
	}

	protected function mark_product_as_sold( $data ) {
		$result = [];

		wp_update_post( [
			'ID'          => $data['product_id'],
			'post_status' => $data['status'],
		] );

		$result['success'] = true;
		$result['status']  = $data['status'];
		if ( 'sold' === $data['status'] ) {
			$result['message'] = sprintf( __( 'The ad has been successfully marked as sold', 'lisfinity-core' ), $data['status'] );
		} else {
			$result['message'] = sprintf( __( 'The ad has been successfully unmarked as sold', 'lisfinity-core' ), $data['status'] );
		}

		return $result;
	}

	protected function delete_product( $data ) {
		$result = [];

		$product = wp_delete_post( $data['product_id'], false );

		$result['success'] = true;
		$result['product'] = $product;
		if ( ! empty( $data['redirect'] ) ) {
			$result['redirect'] = true;
		}
		$result['message'] = __( 'The ad has been successfully deleted', 'lisfinity-core' );

		return $result;
	}

	public function manage_compare( $data ) {
		$products_limit = 3;
		$user           = str_replace( '.', '', $data['user_id'] );
		$product_id     = $data['product_id'];

		if ( empty( $product_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The product id has not been set.', 'lisfinity-core' );
		}

		$model    = new CompareModel();
		$type     = carbon_get_post_meta( $product_id, 'product-category' );
		$products = $model->get_compare_products( $user, $type );
		$model->where( [ [ 'user_id', $user ], [ 'product_type', '<>', "'{$type}'" ] ], '', false )->destroy();

		if ( in_array( $product_id, $products ) ) {
			$result['error']    = true;
			$result['message']  = __( 'The product is already in a compare list.', 'lisfinity-core' );
			$result['products'] = $products;

			return $result;
		}

		if ( is_array( $products['products'] ) && count( $products['products'] ) >= $products_limit ) {
			$first_product = $model->get_first_compare_product( $user, $type );
			$model->where( 'product_id', $first_product, false )->destroy();
		}

		if ( empty( $result['error'] ) ) {
			$data = [
				'user_id'      => $user,
				'product_id'   => $product_id,
				'product_type' => $type,
			];
			$model->store_compare( $data );
			$result['success'] = true;
			$result['message'] = __( 'The product has been added to compare.', 'lisfinity-core' );
		}

		return $model->get_compare_products( $user, $type );

	}

	protected function manage_likes( $product_id ) {
		$result = [];
		if ( empty( $product_id ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The product id has not been set.', 'lisfinity-core' );
		}

		$ip = lisfinity_get_ip_address();

		$likes_ips = get_post_meta( $product_id, 'product-likes' );
		if ( ! is_array( $likes_ips ) ) {
			$likes_ips = [];
		}
		if ( ! in_array( $ip, $likes_ips ) ) {
			add_post_meta( $product_id, 'product-likes', $ip );
			$result['liked'] = true;
			$result['class'] = 'fill-red-600';

			$notification_model = new NotificationModel();
			$business           = carbon_get_post_meta( $product_id, 'product-business' );
			// todo integrate emails and notifications here!.
			$notification_data = [
				'user_id'     => 0,
				'type'        => 1,
				'product_id'  => $product_id,
				'business_id' => $business,
				'parent_id'   => $product_id,
				'parent_type' => 5,
				'status'      => 0,
			];

			$notification_model->store_notification( $notification_data );

		} else {
			delete_post_meta( $product_id, 'product-likes', $ip );
			$result['liked'] = false;
			$result['class'] = 'fill-white';
		}

		$result['likes']    = count( $likes_ips );
		$result['like_ips'] = get_post_meta( $product_id, 'product-likes' );

		return $result;
	}

	protected function get_products_count( $product_id ) {
		$model                      = new ProductModel();
		$products                   = $model->get_products_query( [ 'fields' => 'ids' ] );
		$result                     = [];
		$result['products_count']   = $products->found_posts;
		$result['product_position'] = array_search( $product_id, $products->posts ) + 1;

		return $result;
	}

	/**
	 * Prepare product meta for displaying on the frontend
	 * ---------------------------------------------------
	 *
	 * @param $product
	 *
	 * @return mixed
	 */
	protected function prepare_product_meta( $product ) {
		global $wp_locale;
		$wc_product = wc_get_product( $product->ID );

		$advertisements          = $this->get_product_advertisements( $product->ID );
		$product->advertisements = $advertisements;
		$product->guid           = get_permalink( $product->ID );
		$expires                 = carbon_get_post_meta( $product->ID, 'product-expiration' );
		$is_expired              = $expires < current_time( 'timestamp' );
		$product->is_expired     = $is_expired;
		$premium_taxonomies      = get_option( 'lisfinity__premium_taxonomies' );

		if ( lisfinity_is_enabled( lisfinity_get_option( 'enable-qr-promotion' ) ) ) {
			$product->qr = get_post_meta( $product->ID, '_product-qr', true );
			// check if the qr code has been paid for.
			if ( ! get_post_meta( $product->ID, 'qr-paid' ) && lisfinity_get_qr_promotion() ) {
				$product->qr = false;
			}
		}

		// get owner information
		$location_format        = lisfinity_get_option( 'format-location' );
		$premium_profile        = new \stdClass();
		$premium_profile->owner = get_post_meta( $product->ID, '_product-owner', true );
		$premium_profile->ID    = lisfinity_get_premium_profile_id( $premium_profile->owner );

		$premium_profile->title = get_post_field( 'post_title', $premium_profile->ID );

		$owner                        = carbon_get_post_meta( $product->ID, 'product-owner' );
		$account_type                 = lisfinity_get_account_type( $owner );
		$user_data                    = get_userdata( $owner );
		$user_first_name              = $user_data->first_name;
		$user_last_name               = $user_data->last_name;
		$user_avatar                  = carbon_get_user_meta( $owner, 'avatar' );
		$premium_profile->user_avatar = wp_get_attachment_image_url( $user_avatar, 'big' );

		$premium_profile->title = 'business' == $account_type && get_post_field( 'post_title', $premium_profile->ID ) ? get_post_field( 'post_title', $premium_profile->ID ) : $user_first_name . ' ' . $user_last_name;
		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'use-product-logo' ) ) ) {
			$premium_profile->thumbnail = has_post_thumbnail( $premium_profile->ID ) && 'business' == $account_type ? get_the_post_thumbnail_url( $premium_profile->ID, 'big' ) : false;
		} else {
			$logo = get_post_meta( $product->ID, 'business-logo', true );
			if ( ! empty( $logo ) ) {
				$premium_profile->thumbnail = wp_get_attachment_image_url( $logo, 'big' );
			}
		}
		$premium_profile->type       = carbon_get_post_meta( $premium_profile->ID, 'profile-type' );
		$premium_profile->expiration = carbon_get_post_meta( $premium_profile->ID, 'profile-expiration' );
		if ( ! lisfinity_is_enabled( lisfinity_get_option( 'use-product-phones' ) ) && 'business' === $account_type ) {
			$premium_profile->phones = carbon_get_post_meta( $premium_profile->ID, 'profile-phones' );
		} else {
			$phone = carbon_get_post_meta( $product->ID, 'phone' );
		}
		$premium_profile->location_formatted = 'business' === $account_type ? lisfinity_format_location( $premium_profile->ID, 'full' === $location_format, false, explode( ',', $premium_profile->location['address'] ) ) : carbon_get_post_meta( $product->ID, 'product-location' )['address'];
		$premium_profile->store_referral     = lisfinity_is_enabled( lisfinity_get_option( 'enable-store-referral' ) ) ? get_post_meta( $product->ID, '_product-store-referral', true ) : false;
		$premium_profile->thumbnail          = has_post_thumbnail( $premium_profile->ID ) ? get_the_post_thumbnail_url( $premium_profile->ID, 'big' ) : false;
		$premium_profile->type               = carbon_get_post_meta( $premium_profile->ID, 'profile-type' );
		$premium_profile->expiration         = carbon_get_post_meta( $premium_profile->ID, 'profile-expiration' );
		$premium_profile->phones             = carbon_get_post_meta( $premium_profile->ID, 'profile-phones' );
		$premium_profile->website            = carbon_get_post_meta( $premium_profile->ID, 'profile-website' );
		$premium_profile->email              = carbon_get_post_meta( $premium_profile->ID, 'profile-email' );
		$premium_profile->telegram           = carbon_get_post_meta( $premium_profile->ID, 'profile-telegram' );
		$premium_profile->location           = carbon_get_post_meta( $premium_profile->ID, 'profile-location' );
		$premium_profile->location_formatted = lisfinity_format_location( $premium_profile->ID, 'full' === $location_format, false, explode( ',', $premium_profile->location['address'] ) );
		if ( lisfinity_is_enabled( lisfinity_get_option( 'members_listings_details' ) ) ) {
			$premium_profile->location_map_show = lisfinity_get_option( 'membership-address' );
		} else {
			$premium_profile->location_map_show = true;
		}
		if ( ! empty( $phone ) && $premium_profile->phones[0]['profile-phone'] !== $phone ) {
			$premium_profile->phones[] = [
				'_type'              => '_',
				'profile-phone'      => $phone,
				'profile-phone-apps' => lisfinity_get_option( 'business-phone-apps' ) ?? false,
			];
		}
		$website = carbon_get_post_meta( $product->ID, 'website' );
		if ( ! empty( $website ) && $premium_profile->website !== $website ) {
			$premium_profile->website = $website;
		}
		$email = get_post_meta( $product->ID, 'email', true );
		if ( ! empty( $email ) && $premium_profile->email !== $email ) {
			$premium_profile->email = $email;
		}
		$premium_profile->url    = get_permalink( $premium_profile->ID );
		$premium_profile->rating = number_format_i18n( lisfinity_calculate_business_rating( $premium_profile->ID ), 1 );

		// premium profile working times
		$hours_enabled = carbon_get_post_meta( $premium_profile->ID, 'profile-hours-enable' );
		$weekdays      = lisfinity_days_of_the_week( true, true );
		$hours         = [];
		if ( 'yes' === $hours_enabled ) {
			$day_count = 0;
			foreach ( $weekdays as $slug => $label ) {
				$hours_type                  = carbon_get_post_meta( $premium_profile->ID, "profile-hours-{$slug}-type" );
				$hours[ $day_count ]['day']  = $weekdays[ $slug ];
				$hours[ $day_count ]['type'] = $hours_type;
				if ( 'working' === $hours_type ) {
					$options = carbon_get_post_meta( $premium_profile->ID, "profile-hours-{$slug}-hours" );
					if ( ! empty( $options ) ) {
						$option_count = 0;
						foreach ( $options as $option ) {
							//$option = array_shift( $option );
							if ( ! empty( $option['open'] ) ) {
								$hours[ $day_count ]['hours'][ $option_count ]['open'] = date( lisfinity_get_formatted_time( false, ':', false ), strtotime( $option['open'] ) );
							} else {
								$hours[ $day_count ]['hours'][ $option_count ]['open'] = __( 'Not set', 'lisfinity-core' );
							}
							if ( ! empty( $option['close'] ) ) {
								$hours[ $day_count ]['hours'][ $option_count ]['close'] = date( lisfinity_get_formatted_time( false, ':', false ), strtotime( $option['close'] ) );
							} else {
								$hours[ $day_count ]['hours'][ $option_count ]['close'] = __( 'Not set', 'lisfinity-core' );
							}
							$option_count += 1;
						}
					}
				}
				$day_count += 1;
			}
		}
		$premium_profile->hours_enabled = 'yes' === $hours_enabled;
		$premium_profile->hours         = $hours;
		$premium_profile->current_day   = lisfinity_get_current_weekday() - 1;

		$product->premium_profile = $premium_profile;

		$product->all_products = $this->get_products_count( $product->ID );

		$stats_model    = new StatModel();
		$product->views = $stats_model->get_product_views( $product->ID );

		$likes                    = get_post_meta( $product->ID, 'product-likes' );
		$product->likes           = $likes;
		$product_meta['category'] = carbon_get_post_meta( $product->ID, 'product-category' );

		$product_meta['product_type']   = carbon_get_post_meta( $product->ID, 'product-type' );
		$product_meta['price']          = (int) get_post_meta( $product->ID, '_price', true ) * lisfinity_get_chosen_currency_rate();
		$product_meta['regular_price']  = (int) get_post_meta( $product->ID, '_regular_price', true ) * lisfinity_get_chosen_currency_rate();
		$product_meta['sale_price']     = (int) get_post_meta( $product->ID, '_sale_price', true ) * lisfinity_get_chosen_currency_rate();
		$product_meta['price_type']     = ! empty( $product_meta['sale_price'] ) ? 'on-sale' : carbon_get_post_meta( $product->ID, 'product-price-type' );
		$product_meta['promotion_type'] = get_post_meta( $product->ID, '_promotion-type', true );
		$product_meta['on_sale']        = ! empty( $product_meta['sale_price'] );
		$product_meta['duration']       = get_post_meta( $product->ID, '_promotion-duration-profile', true );
		$product_meta['price_html']     = lisfinity_get_price_html( $product_meta['price'] );
		$product_meta['likes']          = ! empty( $likes ) ? count( $likes ) : 0;

		$product->location = carbon_get_post_meta( $product->ID, 'product-location' );

		$product_meta['location'] = lisfinity_format_location( $product->ID, 'full' === $location_format, false, explode( ',', $product->location['address'] ) );

		if ( 'auction' === $product_meta['price_type'] ) {
			$reserve_price                     = get_post_meta( $product->ID, '_product-auction-reserve-price', true );
			$product_meta['auction_status']    = carbon_get_post_meta( $product->ID, 'product-auction-status' );
			$product_meta['auction_ends']      = carbon_get_post_meta( $product->ID, 'product-auction-ends' );
			$start_price                       = carbon_get_post_meta( $product->ID, 'product-auction-start-price' );
			$start_price                       = ! empty( $start_price ) ? $start_price : 1;
			$product_meta['start_price']       = $start_price;
			$product_meta['start_price_html']  = lisfinity_get_price_html( $start_price, $wc_product );
			$product_meta['reserve_price_met'] = false;
			if ( ! empty ( $reserve_price ) ) {
				$product_meta['reserve_price_met'] = 'no';
				$bids_model                        = new \Lisfinity\Models\Bids\BidModel();
				$highest_bid                       = $bids_model->where( 'product_id', 288 )->get( '', '', 'MAX(amount) as amount, bidder_id' );
				if ( ! empty( $highest_bid[0] ) && $highest_bid[0]->amount >= $reserve_price ) {
					$product_meta['reserve_price_met'] = 'yes';
				}
			}

			$price_option = lisfinity_get_option( 'product-start-price-default' );
			if ( 'last' === $price_option ) {
				$bids_model = new \Lisfinity\Models\Bids\BidModel();
				$last_bid   = $bids_model->where( 'product_id', $product->ID )->get( '1', 'ORDER BY id DESC', 'amount', 'col' );
				if ( ! empty( $last_bid[0] ) ) {
					$product_meta['start_price_html'] = lisfinity_get_price_html( $last_bid[0] * lisfinity_get_chosen_currency_rate(), $wc_product );
				}
			}
		}

		$purchasable_types = [ 'fixed', 'auction', 'on-sale', 'negotiable' ];
		if ( in_array( $product_meta['price_type'], $purchasable_types ) ) {
			$product_meta['sell_on_site'] = carbon_get_post_meta( $product->ID, 'product-price-sell-on-site' );
		}

		$product->product_meta = $product_meta;

		$thumbnail['id']      = get_post_thumbnail_id( $product->ID );
		$thumbnail['url']     = wp_get_attachment_image_url( $thumbnail['id'], 'full' );
		$thumbnail['caption'] = get_the_post_thumbnail_caption( $thumbnail['id'] );
		$product->thumbnail   = $thumbnail;

		// get product media.
		$gallery_images = $wc_product->get_gallery_image_ids();
		$gallery        = [];
		$gallery_ids    = [];
		if ( ! empty( $gallery_images ) ) {
			$gallery_count = 1;
			if ( wp_get_attachment_image_src( $thumbnail['id'], 'full' ) ) {
				$gallery[0]['original']  = wp_get_attachment_image_src( $thumbnail['id'], 'full' );
				$gallery[0]['big']       = wp_get_attachment_image_url( $thumbnail['id'], 'full' );
				$gallery[0]['thumbnail'] = wp_get_attachment_image_url( $thumbnail['id'], 'product-slider-thumb' );
				$gallery[0]['alt']       = get_post_meta( $thumbnail['id'], '_wp_attachment_image_alt', true );

				if ( empty( $gallery[0]['original'][1] ) ) {
					$gallery[0]['original'][1] = get_post_meta( $thumbnail['id'], 'img_width', true );
					$gallery[0]['original'][2] = get_post_meta( $thumbnail['id'], 'img_height', true );
				}

				$gallery_ids[] = $thumbnail['id'];
			}
			foreach ( $gallery_images as $image ) {
				if ( ! in_array( $image, $gallery_ids ) ) {
					$gallery[ $gallery_count ]['original']  = wp_get_attachment_image_src( $image, 'full' );
					$gallery[ $gallery_count ]['big']       = wp_get_attachment_image_url( $image, 'full' );
					$gallery[ $gallery_count ]['thumbnail'] = wp_get_attachment_image_url( $image, 'product-slider-thumb' );
					$gallery[ $gallery_count ]['alt']       = get_post_meta( $image, '_wp_attachment_image_alt', true );

					$gallery[ $gallery_count ]['original'][1] = get_post_meta( $image, 'img_width', true );
					$gallery[ $gallery_count ]['original'][2] = get_post_meta( $image, 'img_height', true );

					$gallery_count += 1;
					$gallery_ids[] = $image;
				}
			}
			$product->gallery = ! empty( $gallery ) ? $gallery : false;
		}
		$bg_image = lisfinity_get_option( 'listing-fallback-image' ) ?? '';
		if ( ! $product->gallery && ! empty( $bg_image ) ) {
			$gallery[]        = [
				'original'  => wp_get_attachment_image_src( $bg_image['id'], 'full' ),
				'big'       => wp_get_attachment_image_url( $bg_image['id'], 'full' ),
				'thumbnail' => wp_get_attachment_image_url( $bg_image['id'], 'product-slider-thumb' ),
				'alt'       => get_post_meta( $bg_image['id'], '_wp_attachment_image_alt', true ),
			];
			$product->gallery = $gallery;
		}

		$product->videos = carbon_get_post_meta( $product->ID, 'product-videos' );
		$product->files  = [];
		$files           = carbon_get_post_meta( $product->ID, 'product-files' );

		$product->fallback_image = lisfinity_get_option( 'listing-fallback-image' )['thumbnail'] ?? '';
		if ( ! empty( $files ) ) {
			$count = 0;
			foreach ( $files as $file ) {
				$product->files[ $count ]['url']   = wp_get_attachment_url( $file['file'] );
				$product->files[ $count ]['title'] = get_the_title( $file['file'] );
				$count                             += 1;
			}
		}

		// organize product taxonomies.
		$product->type = carbon_get_post_meta( $product->ID, 'product-category' );
		if ( empty( $product->type ) ) {
			$product->type = 'common';
		}
		$search_builder_model = new SearchBuilderModel();
		$groups               = get_option( 'lisfinity--single-fields' );
		$taxonomy_fields      = $search_builder_model->get_fields();

		if ( ! empty( $product->type ) ) {
			$product->groups     = $groups[ $product->type ] ?? $groups['common'];
			$product->taxonomies = $this->format_product_taxonomies( $product->ID, $product->type );

			$product->taxonomy_options = $taxonomy_fields['detailed'][ $product->type ]['options'] ?? [];

			// share options.
			$product->share = lisfinity_get_option( 'share-options' );

			// safety tips.
			$tips_permalink          = lisfinity_get_option( 'page-tips' );
			$product->tips_permalink = get_permalink( $tips_permalink );

			// financing calculator.
			$calculator['currency']            = get_woocommerce_currency_symbol();
			$calculator['decimals_separator']  = wc_get_price_decimal_separator();
			$calculator['thousands_separator'] = wc_get_price_thousand_separator();
			$calculator['display']             = false;
			$calculator_categories             = lisfinity_get_option( 'display-calculator' ) ?? [];
			if ( is_array( $calculator_categories ) && ( in_array( 'all', $calculator_categories ) || in_array( $product->type, $calculator_categories ) ) ) {
				$calculator['display'] = true;
			}
			$product->calculator = $calculator;
		}

		return apply_filters( 'lisfinity__product_single_prepared_meta', $product );
	}

	protected function get_product_advertisements( $product_id ) {
		// todo make sure that we're looking for promoted products and not just any.
		// todo make sure that they are either promoted or from the same category or both
		// todo also add check for expired products and promotions like in other queries.
		// todo use ProductsModel->get_products_query() for the actual query.
		$product_category = carbon_get_post_meta( $product_id, 'product-category' );
		$args             = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => absint( lisfinity_get_option( 'ad-similar-number' ) ) + 1,
			'post__not_in'   => [ $product_id ],
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'listing',
					'operator' => 'IN',
				],
			],
			'orderby'        => 'rand',
		];
		if ( ! empty( $product_category ) ) {
			$args['meta_key']   = '_product-category';
			$args['meta_value'] = $product_category;
		}

		// display only promoted listings.
		if ( 'promoted' === lisfinity_get_option( 'display-sidebar-promotion' ) ) {
			$promoted_products = lisfinity_get_promoted_products( 'single-ad' );
			$promoted_ids      = [];
			if ( ! empty( $promoted_products ) ) {
				foreach ( $promoted_products as $promoted_product ) {
					$category = carbon_get_post_meta( $promoted_product->product_id, 'product-category' );
					if ( $category === $product_category ) {
						$promoted_ids[] = $promoted_product->product_id;
					}
				}
			}
			if ( ! empty( $promoted_ids ) ) {
				$args['post__in'] = $promoted_ids;
			} else {
				return [];
			}
		}

		$products = (new ProductModel())->get_products_query($args);

		$search_route = new SearchRoute();

		return $search_route->prepare_products_for_display( $products->posts, false, $product_category );
	}

	protected function format_product_taxonomies( $product_id, $type, $list_all = true, $chosen_taxonomies = [], $register_empty = false ) {
		$taxonomy_model = new TaxonomiesAdminModel();
		$all_taxonomies = $taxonomy_model->get_options();
		if ( $list_all ) {
			$common     = $all_taxonomies['common'] ?? [];
			$by_type    = $all_taxonomies[ $type ] ?? [];
			$taxonomies = array_merge( $common, $by_type );
		} else {
			$taxonomies = $all_taxonomies[ $type ];
		}
		$tax = [];

		if ( ! empty( $taxonomies ) ) {

			if ( ! empty( $chosen_taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					if ( in_array( $taxonomy['slug'], $chosen_taxonomies ) ) {
						$options = $taxonomy_model->get_taxonomy_options( $taxonomy['slug'] );
						if ( ! $this->premium_only && ! empty( $options['premium'] ) && 'yes' === $options['premium'] ) {
							$this->premium_only = true;
						}

						$terms             = get_the_terms( $product_id, $taxonomy['slug'] );
						$has_icon          = ! empty( $options['icon'] );
						$icon_size_default = lisfinity_get_option( 'ad-taxonomy-icon-size' );

						if ( ! is_wp_error( $terms ) ) {
							if ( ! empty( $terms[0] ) ) {
								if ( ! $this->premium_only && 'yes' === $tax[ $taxonomy['slug'] ]['premium'] ) {
									$this->premium_only = true;
								}

								$tax[ $taxonomy['slug'] ]['slug']        = $taxonomy['slug'];
								$tax[ $taxonomy['slug'] ]['icon']        = $has_icon ? wp_get_attachment_image_url( $options['icon'], 'full' ) : false;
								$tax[ $taxonomy['slug'] ]['icon-size']   = $options['icon-size'] ?? $icon_size_default;
								$tax[ $taxonomy['slug'] ]['type']        = $options['type'];
								$tax[ $taxonomy['slug'] ]['single_name'] = $options['single_name'];
								$tax[ $taxonomy['slug'] ]['plural_name'] = $options['plural_name'];
								$tax[ $taxonomy['slug'] ]['group']       = $options['field_group'];
								if ( 'checkbox' === $options['type'] ) {
									foreach ( $terms as $term ) {
										$premium = get_term_meta( $term->term_id, 'premium', true );
										if ( ! $this->premium_only && 'yes' === $premium ) {
											$this->premium_only = true;
										}
										$tax[ $taxonomy['slug'] ]['term'][]      = $term->name;
										$tax[ $taxonomy['slug'] ]['term_slug'][] = $term->slug;
										$tax[ $taxonomy['slug'] ]['term_id'][]   = $term->term_id;
									}
								} else {
									$premium = get_term_meta( $terms[0]->term_id, 'premium', true );
									if ( ! $this->premium_only && 'yes' === $premium ) {
										$this->premium_only = true;
									}
									$tax[ $taxonomy['slug'] ]['term']      = $terms[0]->name;
									$tax[ $taxonomy['slug'] ]['term_slug'] = $terms[0]->slug;
									$tax[ $taxonomy['slug'] ]['term_id']   = $terms[0]->term_id;
								}
							} elseif ( $register_empty ) {
								$tax[ $taxonomy['slug'] ]['term'][] = __( 'Not specified', 'lisfinity-core' );
							}
						}
					}
				}

			} else {
				foreach ( $taxonomies as $taxonomy ) {
					$options = $taxonomy_model->get_taxonomy_options( $taxonomy['slug'] );
					if ( ! $this->premium_only && ! empty( $options['premium'] ) && 'yes' === $options['premium'] ) {
						$this->premium_only = true;
					}

					$terms             = get_the_terms( $product_id, $taxonomy['slug'] );
					$has_icon          = ! empty( $options['icon'] );
					$icon_size_default = lisfinity_get_option( 'ad-taxonomy-icon-size' );

					if ( ! is_wp_error( $terms ) ) {
						if ( ! empty( $terms[0] ) ) {
							$tax[ $taxonomy['slug'] ]['slug']        = $taxonomy['slug'];
							$tax[ $taxonomy['slug'] ]['icon']        = $has_icon ? wp_get_attachment_image_url( $options['icon'], 'full' ) : false;
							$tax[ $taxonomy['slug'] ]['icon-size']   = $options['icon-size'] ?? $icon_size_default;
							$tax[ $taxonomy['slug'] ]['type']        = $options['type'];
							$tax[ $taxonomy['slug'] ]['single_name'] = $options['single_name'];
							$tax[ $taxonomy['slug'] ]['plural_name'] = $options['plural_name'];
							$tax[ $taxonomy['slug'] ]['group']       = $options['field_group'];
							if ( 'checkbox' === $options['type'] ) {
								foreach ( $terms as $term ) {
									$premium = get_term_meta( $term->term_id, 'premium', true );
									if ( ! $this->premium_only && 'yes' === $premium ) {
										$this->premium_only = true;
									}
									$tax[ $taxonomy['slug'] ]['term'][]      = $term->name;
									$tax[ $taxonomy['slug'] ]['term_slug'][] = $term->slug;
									$tax[ $taxonomy['slug'] ]['term_id'][]   = $term->term_id;
								}
							} else {
								$premium = get_term_meta( $terms[0]->term_id, 'premium', true );
								if ( ! $this->premium_only && 'yes' === $premium ) {
									$this->premium_only = true;
								}
								$tax[ $taxonomy['slug'] ]['term']      = $terms[0]->name;
								$tax[ $taxonomy['slug'] ]['term_slug'] = $terms[0]->slug;
								$tax[ $taxonomy['slug'] ]['term_id']   = $terms[0]->term_id;
							}
						}
					}
				}

			}
		}

		return $tax;
	}

	/**
	 * Get product by the requested data
	 * ---------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|mixed|\WP_Post|null
	 */
	public function get_product( WP_REST_Request $request_data ) {
		$data    = $request_data->get_params();
		$product = get_post( $data['product'] );

		$product = $this->prepare_product_meta( $product );

		return $product;
	}

	/**
	 * Load and normalize necessary meta fields
	 * for the promotions custom post type.
	 * ----------------------------------------
	 *
	 * @param $products
	 *
	 * @return array
	 */
	private function prepare_packages_meta( $products ) {

		if ( empty( $products ) ) {
			return $products;
		}

		$prepared = [];
		foreach ( $products as $promotion ) {
			// global meta.
			$product = wc_get_product( $promotion->ID );
			$prefix  = '';
			if ( $product->is_type( \WC_Product_Payment_Subscription::$type ) ) {
				$prefix = 's-';
			}
			$meta['type']       = get_post_meta( $promotion->ID, '_promotion-type', true );
			$meta['duration']   = get_post_meta( $promotion->ID, '_promotion-duration-profile', true );
			$meta['price']      = $product->get_regular_price();
			$meta['sale-price'] = $product->get_sale_price();
			if ( $meta['type'] === 'product' ) {
				$meta['product-type'] = get_post_meta( $promotion->ID, '_promotion-product-type', true );
				$meta['duration']     = get_post_meta( $promotion->ID, '_promotion-duration', true );
			}
			$promotion->meta      = $meta;
			$thumbnail['id']      = get_post_thumbnail_id( $promotion->ID );
			$thumbnail['url']     = wp_get_attachment_image_url( $thumbnail['id'], 'medium' );
			$thumbnail['caption'] = get_the_post_thumbnail_caption( $thumbnail['id'] );
			$thumbnail['meta']    = wp_get_attachment_metadata( $thumbnail['id'] );
			$thumbnail['thumb']   = wp_get_attachment_metadata( $thumbnail['id'] );
			$promotion->thumbnail = $thumbnail;

			// payment_packages specific meta.
			if ( Payment_Package::$type === $product->get_type() ) {
				$package['style']      = carbon_get_post_meta( $promotion->ID, "{$prefix}package-style" );
				$package['features']   = carbon_get_post_meta( $promotion->ID, "{$prefix}package-features" );
				$package['promotions'] = carbon_get_post_meta( $promotion->ID, "{$prefix}package-promotions" );
				$package['meta']       = carbon_get_post_meta( $promotion->ID, 'package-sold-once' );
				$package['limit']      = carbon_get_post_meta( $promotion->ID, 'package-products-limit' );
				$package['duration']   = carbon_get_post_meta( $promotion->ID, 'package-products-duration' );
				$promotion->package    = $package;
			}

			$prepared[] = $promotion;
		}

		return $prepared;
	}

	/**
	 * Get all promotions except for images/files/videos
	 * -------------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array
	 */
	public function get_packages( WP_REST_Request $request_data ) {
		$data  = $request_data->get_params();
		$types = strpos( $data['type'], ',' ) !== false ? explode( ',', $data['type'] ) : $data['type'];

		$args     = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $types,
					'operator' => 'IN',
				],
			],
			'meta_query'     => [
				[
					'key'     => 'promotion-type',
					'value'   => 'product',
					'compare' => '=',
				],
			],
		];
		$products = get_posts( $args );

		return $this->prepare_packages_meta( $products );
	}

	public function check_package( WP_REST_Request $request_data ) {
		$data    = $request_data->get_params();
		$model   = new PackageModel();
		$package = $model->where( [
			[ 'id', $data['id'] ],
			[ 'user_id', $data['user_id'] ],
			[ 'status', 'active' ],
			[ 'products_limit', '>', 'products_count' ],
		] )->get( '1', '', 'id', 'col' );

		$result['package'] = ! empty( $package ) ? array_shift( $package ) : false;

		return $result;
	}

	public function get_product_data( WP_REST_Request $request_data ) {
		$data   = $request_data->get_params();
		$result = [];

		$model                     = new FormSubmitModel();
		$result['payment_package'] = $this->get_package_and_promotions_data( $data, true );
		$result['product']         = $this->get_product_edit_data( $data, $model );

		return $result;
	}

	public function get_product_edit_data( $data, $model ) {
		if ( empty( $data ) ) {
			return false;
		}

		$product = [];

		$fields = $model->get_fields();
		if ( empty( $fields ) ) {
			return false;
		}

		$post = get_post( $data['id'] );

		if ( empty( $post ) ) {
			return false;
		}

		foreach ( $fields as $field_group => $field ) {
			foreach ( $field as $key => $values ) {
				if ( 'title' === $key ) {
					$product['title'] = $post->post_title;
				} else if ( 'description' === $key ) {
					$product['description'] = $post->post_content;
				} else if ( 'terms' === $key ) {
					$value           = get_post_meta( $post->ID, $key, true );
					$product[ $key ] = 1 == $value;
				} else if ( 'working_hours' === $values['type'] ) {
					$days                     = array_keys( lisfinity_days_of_the_week() );
					$new_key                  = $key;
					$formatted_days           = [];
					$formatted_days['enable'] = get_post_meta( $post->ID, "{$new_key}-enable", true );

					foreach ( $days as $day ) {
						$modify_key                      = ltrim( $new_key, '_' );
						$formatted_days[ $day ]['type']  = get_post_meta( $post->ID, "{$new_key}-{$day}-type", true );
						$hours                           = carbon_get_post_meta( $post->ID, "{$modify_key}-{$day}-hours" );
						$formatted_days[ $day ]['hours'] = ! empty( $hours ) ? $hours : [
							[
								'open'  => '08:00:00',
								'close' => '16:00:00',
							]
						];
					}

					$product[ $key ] = $formatted_days;
				} else if ( 'checkbox' === $values['type'] ) {
					$value           = get_post_meta( $post->ID, $key, true );
					$product[ $key ] = 1 == $value;
				} else if ( 'location' === $values['type'] ) {
					if ( 'address' !== $key ) {
						$new_key = lisfinity_replace_first_instance( $key, '_', '' );
						$address = carbon_get_post_meta( $post->ID, $new_key );
					} else {
						$address = carbon_get_post_meta( $post->ID, 'product-location' );
					}
					$product["location[$key]"]['address']       = $address['address'];
					$product["location[$key]"]['marker']['lat'] = $address['lat'];
					$product["location[$key]"]['marker']['lng'] = $address['lng'];
				} else if ( 'date' === $values['type'] ) {
					$new_key = lisfinity_replace_first_instance( $key, '_', '' );
					$date    = carbon_get_post_meta( $post->ID, $new_key );
					if ( ! empty( $date ) ) {
						$product[ $key ] = date( 'Y-m-d H:i', $date );
					}
				} else if ( in_array( $key, [
					'_price',
					'_regular_price',
					'_sale_price',
					'_price_buy_now',
					'_stock_custom'
				] ) ) {
					$product['_regular_price'] = get_post_meta( $post->ID, '_regular_price', true );
					$product['_sale_price']    = get_post_meta( $post->ID, '_sale_price', true );
					if ( ! empty( $product['_sale_price'] ) ) {
						$product['_price'] = get_post_meta( $post->ID, '_regular_price', true );
					} else {
						$product['_price'] = get_post_meta( $post->ID, '_price', true );
					}
					$product['_price_buy_now'] = get_post_meta( $post->ID, '_price_buy_now', true );
					$product['_stock_custom']  = get_post_meta( $post->ID, '_stock_custom', true );
				} else if ( 'taxonomies' === $values['type'] ) {
					$product['cf_category'] = carbon_get_post_meta( $post->ID, 'product-category' );
					$model                  = new \Lisfinity\Models\Taxonomies\TaxonomiesAdminModel();
					$common_taxonomies      = array_column( $model->get_taxonomies_options_by_group( 'common' ), 'slug' );
					if ( ! empty( $product['cf_category'] ) ) {
						$taxonomies = get_post_taxonomies( $post->ID );
						if ( ! empty( $taxonomies ) ) {
							foreach ( $taxonomies as $taxonomy ) {
								if ( ! in_array( $taxonomy, [
									'product_cat',
									'product_type',
									'product_tag',
									'product_visibility',
									'product_shipping_class'
								] ) ) {
									$terms = get_the_terms( $data['id'], $taxonomy );

									if ( ! empty( $terms ) ) {
										if ( count( $terms ) > 1 ) {
											foreach ( $terms as $term ) {
												$product[ $product['cf_category'] ][ $taxonomy ][] = $term->slug;
												if ( in_array( $taxonomy, $common_taxonomies ) ) {
													$product['common'][ $taxonomy ][] = $term->slug;
												}
											}
										} else {
											$product[ $product['cf_category'] ][ $taxonomy ] = $terms[0]->slug;
											if ( in_array( $taxonomy, $common_taxonomies ) ) {
												$product['common'][ $taxonomy ] = $terms[0]->slug;
											}
										}
									}
								}
							}
						}
					}
				} else if ( 'image' === $values['type'] ) {
					$gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
					//$product[ $key ] = ! empty( $gallery ) ? explode( ',', $gallery ) : [];
					if ( ! empty( $gallery ) ) {
						foreach ( explode( ',', $gallery ) as $index => $image ) {
							$image_url                             = wp_get_attachment_image_url( $image );
							$product[ $key ][ $index ]['data_url'] = $image_url;
						}
					}

				} else if ( 'media' === $values['type'] ) {
					if ( isset( $values['type_filter'] ) ) {
						if ( 'file' === $values['store_as'] ) {
							$product[ $key ] = carbon_get_post_meta( $post->ID, 'product-files' );
						} else {
							$gallery         = get_post_meta( $post->ID, '_product_image_gallery', true );
							$product[ $key ] = ! empty( $gallery ) ? explode( ',', $gallery ) : [];
						}
					} else {
						$videos = carbon_get_post_meta( $post->ID, 'product-videos' );
						if ( ! empty( $videos ) ) {
							$count = 0;
							foreach ( $videos as $video ) {
								$video_id = explode( "?v=", $video['video'] );
								if ( ! empty( $video_id[1] ) ) {
									$video_id                               = $video_id[1];
									$thumbnail                              = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
									$product[ $key ][ $count ]['url']       = $video['video'];
									$product[ $key ][ $count ]['thumbnail'] = $thumbnail;
									$count                                  += 1;
								}
							}
						}
					}
				} elseif ( 'qr' === $values['type'] ) {
					$product[ $key ] = get_post_meta( $post->ID, $key, true );
				} elseif ( isset( $values['settings']['basic'] ) ) {
					$value                 = get_post_meta( $post->ID, $key, ! isset( $values['settings']['complex'] ) );
					$product[ $key ]       = $value;
					$product["{$key}_url"] = wp_get_attachment_image_url( (int) $value, 'full' );
				} else {
					$new_key         = lisfinity_replace_first_instance( $key, '_', '' );
					$product[ $key ] = carbon_get_post_meta( $post->ID, $new_key );
					if ( empty( $product[ $key ] ) ) {
						$product[ $key ] = get_post_meta( $post->ID, $key, true );
					}
				}

				$images_to_pay = get_post_meta( $post->ID, '_needs-payment|image', true );
				if ( get_post_meta( $post->ID, '_needs-payment|image', true ) ) {
					$product['needs-payment']['image'] = $images_to_pay;
				}
				$docs_to_pay = get_post_meta( $post->ID, '_needs-payment|docs', true );
				if ( get_post_meta( $post->ID, '_needs-payment|docs', true ) ) {
					$product['needs-payment']['docs'] = $docs_to_pay;
				}
			}
		}


		return $product;
	}

	protected function get_package_and_promotions_data( $data, $exact_package = false, $post_type = false ) {
		$is_subscription = false;
		$agent           = lisfinity_get_agent( get_current_user_id() );
		if ( ! lisfinity_packages_enabled( $agent->owner_id ?? get_current_user_id() ) ) {
			return $this->get_package_and_promotions_without_packages();
		}
		$data['user_id'] = $agent->owner_id ?? get_current_user_id();
		$model           = new PackageModel();
		if ( $exact_package && ! $post_type) {
			$package_id = get_post_meta( $data['id'], '_payment-package', true );
			$package    = $model->where( [
				[ 'id', $package_id ],
			] )->get( '1', '', 'id, product_id, created_at, products_limit, products_count, products_duration', '' );
		} elseif ($exact_package && $post_type === 'package') {
            //TODO: this whole method is highly customized, check back for original method
            $package    = get_post($data['id']);
            return [
                $data['id'],
                $package,
                get_post_meta($data['id'])
            ];
        } else {
			$package = $model->where( [
				[ 'id', $data['id'] ],
				[ 'user_id', $data['user_id'] ],
				[ 'status', 'active' ],
				[ 'products_limit', '>', 'products_count' ],
			] )->get( '1', '', 'id, product_id, created_at, products_limit, products_count, products_duration', '' );
			if ( empty( $package ) ) {
				$subscription_model = new SubscriptionModel();
				$package            = $subscription_model->where( [
					[ 'id', $data['id'] ],
					[ 'user_id', $data['user_id'] ],
					[ 'status', 'active' ],
				] )->get( '1', '', 'id, product_id, created_at, products_limit, products_count, products_duration, promotions_limit, promotions_count', '' );
				$is_subscription    = true;
			}
		}

		// return false if the package is empty.
		if ( empty( $package ) ) {
            return [
                'yo 2',
                $data['id'],
                $package,
            ];
		}

		$package = array_shift( $package );

		$promotions = $this->get_promotions_for_package( $package->id );

		$free_promotions = carbon_get_post_meta( $package->product_id, 'package-free-promotions' );

		$promo_model                   = new PromotionsModel();
		$package->free_promotions      = $free_promotions ?? false;
		$package->is_subscription      = $is_subscription;
		$package->currency             = get_woocommerce_currency_symbol();
		$package->decimals             = wc_get_price_decimals();
		$package->decimal_separator    = wc_get_price_decimal_separator();
		$package->thousand_separator   = wc_get_price_thousand_separator();
		$wc_product                    = wc_get_product( $package->product_id );
		$package->title                = $wc_product->get_title();
		$package->price                = $wc_product->get_price() * lisfinity_get_chosen_currency_rate();
		$package->price_html           = lisfinity_get_price_html( $package->price );
		$package->price_format         = get_woocommerce_price_format();
		$package->remaining            = $package->products_limit - $package->products_count;
		$package->percentage           = floor( 100 - ( $package->remaining * 100 ) / $package->products_limit );
		$package->promotion['addon']   = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'addon' ] );
		$package->promotion['product'] = $promo_model->filter_promotions_by_package( $promotions, $package->id, [ 'product' ] );
		$package->promotion_qr         = $this->get_promotion_qr();
		$prefix                        = '';
		if ( $is_subscription ) {
			$prefix = 's-';
		}
		$package->is_exclusive = 'yes' === carbon_get_post_meta( $package->product_id, "{$prefix}package-categories-exclusive" );


		$categories = [];

		foreach ( carbon_get_post_meta( $wc_product->get_id(), "{$prefix}package-categories" ) as $category ) {
			$category['price']    = $category['price'] * lisfinity_get_chosen_currency_rate();
			$category['currency'] = lisfinity_get_chosen_currency();
			$category['currency'] = lisfinity_get_chosen_currency();
			$currency             = lisfinity_get_chosen_currency();
			$categories[]         = $category;
		}
		$package->categories        = $categories;
		$package->is_exclusive      = 'yes' === carbon_get_post_meta( $package->product_id, "{$prefix}package-categories-exclusive" );
		$package->categories        = carbon_get_post_meta( $wc_product->get_id(), "{$prefix}package-categories" );
		$submit_percentage          = carbon_get_post_meta( $wc_product->get_id(), "{$prefix}package-price-submit-percentage" );
		$package->submit_percentage = ! empty( $submit_percentage ) ? $submit_percentage : false;

		$package->limit_reached  = false;
		$package->pay_commission = false;
		if ( $package->products_limit <= $package->products_count ) {
			$package->pay_commission = true;
		}
		if ( $package->products_limit <= $package->products_count || ! empty( $package->categories || ! empty( $package->submit_percentage ) ) ) {
			if ( $package->products_limit <= $package->products_count ) {
				$package->limit_reached = true;
			}
			// get commission product.
			$args                = [
				'post_type' => 'product',
				'tax_query' => [
					[
						'taxonomy' => 'product_type',
						'field'    => 'name',
						'terms'    => \WC_Product_Commission::$type,
						'operator' => 'IN',
					],
				],
				'fields'    => 'ids',
			];
			$commission_product  = get_posts( $args );
			$package->commission = new \stdClass();
			if ( ! empty( $commission_product ) ) {
				$commission_product           = array_shift( $commission_product );
				$commission_product           = wc_get_product( $commission_product );
				$package->commission->product = $commission_product->get_id();
				$package->commission->price   = $commission_product->get_price();
				$commission                   = carbon_get_post_meta( $package->product_id, 'subscription-product-price' );
				if ( ! empty( $commission ) ) {
					$package->commission->price = (float) $commission;
				}
			}
		}

		$package->promotions = $promotions;

		$package->title        = get_the_title( $package->product_id );
		$package->created_date = date( 'm.d.Y', strtotime( $package->created_at ) );

		return $package;
	}

	public function get_promotion_qr() {
		return lisfinity_get_qr_promotion();
	}

	/**
	 * Get package and promotions connected to id
	 * ------------------------------------------
	 *
	 * @param WP_REST_Request $request_data
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_package_and_promotions( WP_REST_Request $request_data ) {
		$data = $request_data->get_params();
        //TODO: I added the exact package and post_type
        $exact_package = $data['exact_package'] ?? false;
        $post_type = $data['post_type'] ?? false;

		$packages_enabled = lisfinity_packages_enabled( get_current_user_id() );
		if ( ! $packages_enabled ) {
			return $this->get_package_and_promotions_without_packages();
		}

        //TODO: I added the exact package for second param and post_type for the third
		return $this->get_package_and_promotions_data( $data, $exact_package, $post_type);
	}

	protected function get_package_and_promotions_without_packages() {
		// if payment packages are disabled.
		$package          = new \stdClass();
		$promotions_model = new PromotionsModel();
		$all_promotions   = $promotions_model->get_promotion_products( 'promotion', 'addon' );

		$promotions = [];
		foreach ( $all_promotions as $promotion ) {
			$type             = carbon_get_post_meta( $promotion->ID, 'promotion-addon-type' );
			$promotion->addon = str_replace( 'addon-', '', $type );
			$regular_price    = get_post_meta( $promotion->ID, '_regular_price', true );
			$sale_price       = get_post_meta( $promotion->ID, '_sale_price', true );
			$promotion->price = $regular_price;
			if ( ! empty( $sale_price ) && 0 !== $sale_price ) {
				$package->price = $sale_price;
			}
			$promotions_enabled = '1' === lisfinity_get_option( 'site-promotions' );
			if ( ! isset( $all_promotions[ $promotion->addon ] ) ) {
				if ( 'image' === $promotion->addon ) {
					$promotion->value = 'no' !== $promotions_enabled ? lisfinity_get_option( 'product-images-free-limit' ) : lisfinity_get_option( 'product-images-limit' );
				} else if ( 'docs' === $promotion->addon ) {
					$promotion->value = 'no' !== $promotions_enabled ? lisfinity_get_option( 'product-documents-free-limit' ) : lisfinity_get_option( 'product-documents-limit' );
				} else if ( 'video' === $promotion->addon ) {
					$promotion->value = 'no' !== $promotions_enabled ? lisfinity_get_option( 'product-videos-free-limit' ) : lisfinity_get_option( 'product-videos-limit' );
				}
				$promotions[ $promotion->addon ] = $promotion;
			}
		}

		if ( ! in_array( 'image', array_keys( $promotions ) ) ) {
			$promotions['image']['value'] = lisfinity_get_option( 'product-images-limit' );
		}
		if ( ! in_array( 'docs', array_keys( $promotions ) ) ) {
			$promotions['docs']['value'] = lisfinity_get_option( 'product-docs-limit' );
		}
		if ( ! in_array( 'video', array_keys( $promotions ) ) ) {
			$promotions['video']['value'] = lisfinity_get_option( 'product-videos-limit' );
		}

		$package->currency           = get_woocommerce_currency_symbol();
		$package->decimals           = wc_get_price_decimals();
		$package->decimal_separator  = wc_get_price_decimal_separator();
		$package->thousand_separator = wc_get_price_thousand_separator();
		$package->price_format       = get_woocommerce_price_format();

		$package->promotions = $promotions;

		return $package;
	}

	/**
	 * Get all promotions that are connected to a package
	 * --------------------------------------------------
	 *
	 * @param $package_id
	 *
	 * @return array
	 */
	public function get_promotions_for_package( $package_id ) {
		$promotion  = new PromotionsModel();
		$packages   = $promotion->where( 'package_id', $package_id )->get();
		$promotions = [];

		if ( $packages ) {
			foreach ( $packages as $package ) {
				$package->duration = carbon_get_post_meta( $package->wc_product_id, 'promotion-duration' );
				$package->addon    = str_replace( 'addon-', '', $package->position );
				$regular_price     = get_post_meta( $package->wc_product_id, '_regular_price', true );
				$sale_price        = get_post_meta( $package->wc_product_id, '_sale_price', true );
				$package->price    = $regular_price;
				if ( ! empty( $sale_price ) && 0 !== $sale_price ) {
					$package->price = $sale_price;
				}
				$promotions[ $package->addon ] = $package;
			}
		}

		return $promotions;
	}

	public function request_call( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		if ( ! is_user_logged_in() ) {
			$result['error']   = true;
			$result['message'] = __( 'You have to be logged in to request a call', 'lisfinity-core' );

			return $result;
		}

		$user_data       = get_userdata( get_current_user_id() );
		$user_business   = lisfinity_get_premium_profile_id( $user_data->ID );
		$user_email      = carbon_get_post_meta( $user_business, 'profile-email' );
		$business_phones = carbon_get_post_meta( $user_business, 'profile-phones' );

		if ( empty( $user_email ) ) {
			$result['error']   = true;
			$result['message'] = __( 'You need to provide your business email in the dashboard before you can complete this action', 'lisfinity-core' );

			return $result;
		}

		$business_id = carbon_get_post_meta( $data['product_id'], 'product-business' );

		$to      = carbon_get_post_meta( $business_id, 'profile-email' );
		$subject = sprintf( esc_html__( 'New contact request | %s', 'lisfinity-core' ), get_option( 'blogname' ) );
		if ( empty( $business_phones ) ) {
			$body = sprintf( __( 'User %1$s has requested a contact. <br /> You can contact him on the details below: <br /><br /> Email: %2$s',
				'lisfinity-core' ), get_the_title( $user_business ), "<a href='mailto: {$user_email}'>$user_email</a>" );
		} else {
			$phones = array_column( $business_phones, 'profile-phone' );
			$string = '';
			$count  = 1;
			if ( ! empty( $user_email ) ) {
				$string .= sprintf( __( 'Email: %s <br />', 'lisfinity-core' ), "<a href='mailto: {$user_email}'>$user_email</a>" );
			}
			foreach ( $phones as $phone ) {
				$phone_formatted = str_replace( ' ', '', $phone );
				$string          .= sprintf( __( 'Phone %1$s: %2$s <br />', 'lisfinity-core' ), $count, "<a href='tel:{$phone_formatted}'>$phone</a>" );
				$count           += 1;
			}
			$permalink = get_permalink( $business_id );
			$title     = get_the_title( $business_id );
			$body      = sprintf( __( 'User %1$s has requested a contact. <br /> You can contact him on the details below: <br /><br /> %2$s',
				'lisfinity-core' ), "<a href='{$permalink}' target='_blank'>{$title}</a>", $string );
		}

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$mail = wp_mail( $to, $subject, $body, $headers );

		if ( ! $mail ) {
			$result['error']   = true;
			$result['message'] = __( 'The mail could not have been sent', 'lisfinity-core' );

			return $result;
		}

		$result['success'] = true;
		$result['message'] = __( 'The request has been successfully sent', 'lisfinity-core' );

		return $result;
	}

	public function send_message( WP_REST_Request $request ) {
		$data   = $request->get_params();
		$result = [];

		if ( empty( $data['name'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The name has not been provided', 'lisfinity-core' );

			return $result;
		}

		if ( empty( $data['email'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The email has not been provided', 'lisfinity-core' );

			return $result;
		}

		if ( empty( $data['message'] ) ) {
			$result['error']   = true;
			$result['message'] = __( 'The message has not been provided', 'lisfinity-core' );

			return $result;
		}

		$business_id = carbon_get_post_meta( $data['product_id'], 'product-business' );
		$owner_id    = carbon_get_post_meta( $data['product_id'], 'product-owner' );

		$to = carbon_get_post_meta( $business_id, 'profile-email' );
		if ( empty( $to ) ) {
			$owner_data = get_userdata( $owner_id );
			$to         = $owner_data->user_email;
		}
		$subject = sprintf( esc_html__( 'New Message | %s', 'lisfinity-core' ), get_option( 'blogname' ) );

		$body = sprintf( __( 'Hey look, %s sent you a message concerning your listing %s (ID: %s).', 'lisfinity-core' ), $data['name'], get_the_title( $data['product_id'] ), $data['product_id'] );
		$body .= sprintf( __( '<br /> <br /> Message: %s', 'lisfinity-core' ), $data['message'] );

		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];

		$mail = wp_mail( $to, $subject, $body, $headers );

		if ( ! $mail ) {
			$result['error']   = true;
			$result['message'] = __( 'The mail could not have been sent', 'lisfinity-core' );

			return $result;
		}

		$body = sprintf( __( 'Hey, your message concerning listing %s has been successfully submitted to %s.', 'lisfinity-core' ), get_the_title( $data['product_id'] ), $data['name'] );

		$mail = wp_mail( $data['email'], $subject, $body, $headers );

		if ( ! $mail ) {
			$result['error']   = true;
			$result['message'] = __( 'The mail could not have been sent', 'lisfinity-core' );

			return $result;
		}

		$result['success'] = true;
		$result['message'] = __( 'The request has been successfully sent', 'lisfinity-core' );

		return $result;
	}

	/**
	 * Query attachments from the media uploader
	 * -----------------------------------------
	 *
	 * @param WP_REST_Request $request
	 */
	public function query_attachments( WP_REST_Request $request ) {
		$query = $request->get_params();

		$keys = array(
			's',
			'order',
			'orderby',
			'posts_per_page',
			'paged',
			'post_mime_type',
			'post_parent',
			'author',
			'post__in',
			'post__not_in',
			'year',
			'monthnum',
		);

		foreach ( get_taxonomies_for_attachments( 'objects' ) as $t ) {
			if ( $t->query_var && isset( $query[ $t->query_var ] ) ) {
				$keys[] = $t->query_var;
			}
		}

		$query              = array_intersect_key( $query, array_flip( $keys ) );
		$query['post_type'] = 'attachment';

		if ( MEDIA_TRASH && ! empty( $query['post_status'] ) && 'trash' === $query['post_status'] ) {
			$query['post_status'] = 'trash';
		} else {
			$query['post_status'] = 'inherit';
		}

		if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) ) {
			$query['post_status'] .= ',private';
		}

		// Filter query clauses to include filenames.
		if ( isset( $query['s'] ) ) {
			add_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
		}

		$query = apply_filters( 'ajax_query_attachments_args', $query );
		$query = new \WP_Query( $query );

		$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
		$posts = array_filter( $posts );

		wp_send_json_success( $posts );
	}

}
