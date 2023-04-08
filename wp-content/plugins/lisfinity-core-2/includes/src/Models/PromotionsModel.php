<?php
/**
 * Model for our custom WooCommerce product type with all
 * possible extensions and custom functionality.
 *
 * @author pebas
 * @package woocommerce-listing
 * @version 1.0.0
 */

namespace Lisfinity\Models;

use Lisfinity\Abstracts\Model as Model;

/**
 * Class PromotionsModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class PromotionsModel extends Model {

	protected $table = 'promotions';

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'package_id'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'order_id'      => [
				'type'  => 'bigint(20)',
				'value' => '0',
			],
			'wc_product_id' => [
				'type'  => 'bigint(20)',
				'value' => '0',
			],
			'user_id'       => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'value'         => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'count'         => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'position'      => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'type'          => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'status'        => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'activated_at'  => [
				'type'  => 'timestamp',
				'value' => 'NULL NULL',
			],
			'expires_at'    => [
				'type'  => 'timestamp NULL',
				'value' => 'NULL NULL',
			],
		];

		return $this->fields;
	}

	/**
	 * Add product id for the given promotion to WooCommerce order so
	 * we can attach promotion to the desired custom product
	 * --------------------------------------------------------------
	 *
	 * @param $item
	 * @param $cart_item_key
	 * @param $values
	 * @param $order
	 */
	public function add_product_to_order( $item, $cart_item_key, $values, $order ) {
		if ( ! isset( $values['product'] ) || empty( $values['product'] ) ) {
			return;
		}

		// We're adding custom wc product to wc order as wc uses promotion product for the id.
		$item->update_meta_data( 'product', $values['product'] );

		// Add custom value to prevent product expiration extension if the purchase is not coming from ad submit form.
		if ( isset( $values['no-product-expiration'] ) ) {
			$item->update_meta_data( 'no-product-expiration', true );
		}

		if ( isset( $values['no-price'] ) ) {
			$item->update_meta_data( 'no-price', true );
		}

		// Add default product status if necessary.
		if ( isset( $values['status'] ) ) {
			$item->update_meta_data( 'status', $values['status'] );
		}

		// Add product duration so we can use it when the order is completed.
		if ( isset( $values['duration'] ) ) {
			$item->update_meta_data( 'duration', $values['duration'] );
		}

		if ( isset( $values['is_edit'] ) ) {
			$item->update_meta_data( 'is_edit', $values['is_edit'] ? 'yes' : 'no' );
		}
	}

	/**
	 * Buying promotions link to checkout.
	 * ----------------------------------
	 *
	 * @throws \Exception
	 */
	public function buy_promotion_checkout_prepare() {
		// todo used by ajax and should be removed
		// clear the cart before adding promotion in it.
		WC()->cart->empty_cart();

		$package_id    = $_REQUEST['id'] ? $_REQUEST['id'] : 0;
		$package_value = $_REQUEST['days'] ? $_REQUEST['days'] : 0;
		$product_id    = $_REQUEST['product'] ? $_REQUEST['product'] : 0;

		if ( ! empty( $_REQUEST['action'] ) && 'buy_promotion' == $_REQUEST['action'] && wp_verify_nonce( $_REQUEST['nonce'], 'buy_promotion' ) ) {
			WC()->cart->add_to_cart(
				$package_id,
				$package_value,
				'',
				'',
				[
					'package_id' => $package_id,
					'product'    => $product_id,
				]
			);
		}

		$result['permalink'] = get_permalink( wc_get_page_id( 'checkout' ) );
		wp_send_json( $result );
	}


	/**
	 * Filter given promotions by specific key and value
	 * -------------------------------------------------
	 *
	 * @param $promotions
	 * @param $filter_by
	 * @param $filter
	 *
	 * @return array
	 */
	public function filter_promotion( $promotions, $filter_by, $filter ) {
		$filtered = [];
		if ( ! empty( $promotions ) ) {
			foreach ( $promotions as $promotion ) {
				if ( $filter === $promotion->$filter_by ) {
					$filtered[] = $promotion;
				}
			}
		}

		return $filtered;
	}

	/**
	 * Get all promotions related to a given user
	 * ------------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_user_promotions( $user_id ) {
		$promotions = $this->where( 'user_id', $user_id )->get();

		return $promotions;
	}

	/**
	 * Get all promotions related to a provided product
	 * ------------------------------------------------
	 *
	 * @param $product_id
	 * @param array $promotions
	 * @param string $status
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_product_promotions( $product_id, $promotions = [], $status = 'active' ) {
		$filtered = [];

		// if promotions are not provided, query them from db.
		if ( empty( $promotions ) ) {
			$promotions = $this->where( [ [ 'product_id', $product_id ], [ 'status', $status ] ] )->get();

			if ( $promotions ) {
				foreach ( $promotions as $promotion ) {
					$filtered[ $promotion->wc_product_id ] = $promotion;
				}
			}

			return $filtered;
		}

		foreach ( $promotions as $promotion ) {
			if ( $promotion->product_id === $product_id && $promotion->status === $status ) {
				$filtered[] = $promotion;
			}
		}

		return $filtered;

	}

	/**
	 * Return the promotions for the given package
	 * -------------------------------------------
	 *
	 * @param array $promotions
	 * @param int $package_id
	 * @param array $type
	 *
	 * @return array
	 */
	public function filter_promotions_by_package( $promotions, $package_id, $type = [] ) {
		$filtered = [];

		$type = empty( $type ) ? [ 'addon', 'product', 'profile' ] : $type;
		if ( ! empty( $promotions ) ) {
			foreach ( $promotions as $promotion ) {
				if ( $promotion->package_id === $package_id && in_array( $promotion->type, $type ) ) {
					$promotion->title = $this->format_promotion_title( $promotion );
					$filtered[]       = $promotion;
				}
			}
		}

		return $filtered;
	}

	/**
	 * Format the title of the promotions.
	 * -----------------------------------
	 *
	 * @param $promotion
	 *
	 * @return mixed|string|void
	 */
	protected function format_promotion_title( $promotion ) {
		$title = $promotion->position;
		switch ( $promotion->position ) {
			case 'addon-image':
				$title = _n( 'Image', 'Images', $promotion->value, 'lisfinity-core' );
				break;
			case 'addon-video':
				$title = _n( 'Video', 'Videos', $promotion->value, 'lisfinity-core' );
				break;
			case 'addon-docs':
				$title = _n( 'Doc', 'Docs', $promotion->value, 'lisfinity-core' );
				break;
			case 'addon-qr':
				$title = __( 'QR Code', 'lisfinity-core' );
				break;
			case 'home-banner':
				$title = __( 'Home Banner', 'lisfinity-core' );
				break;
			case 'home-ads':
				$title = __( 'Home Ads', 'lisfinity-core' );
				break;
			case 'bump-pin':
				$title = __( 'Bump Pin', 'lisfinity-core' );
				break;
			case 'bump-color':
				$title = __( 'Bump Color', 'lisfinity-core' );
				break;
			case 'category-featured':
				$title = __( 'Category Featured', 'lisfinity-core' );
				break;
		}

		return $title;
	}

	/**
	 * Get all promotions that can be bought
	 * -------------------------------------
	 *
	 * @param $type
	 * @param $promotion_type
	 * @param $additional_args
	 *
	 * @return array
	 */
	public function get_promotion_products( $type, $promotion_type = 'product', $additional_args = '' ) {
		$types = str_contains( $type, ',' ) ? explode( ',', $type ) : $type;

		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'tax_query'           => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $types,
					'operator' => 'IN',
				],
			],
			'meta_key'            => '_promotion-type',
			'meta_value'          => $promotion_type,
			'no_found_rows'       => true,
			'cache_results'       => true,
		];

		if ( ! isset( $additional_args['posts_per_page'] ) ) {
			$args['posts_per_page'] = - 1;
		}

		$args = wp_parse_args( $additional_args, $args );

		$products = new \WP_Query( $args );

		return isset( $args['fields'] ) ? $products->posts : $this->prepare_promotion_products_meta( $products->posts );
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
	private function prepare_promotion_products_meta( $products ) {

		if ( empty( $products ) ) {
			return $products;
		}

		$prepared = [];
		foreach ( $products as $promotion ) {
			// global meta.
			$product                   = wc_get_product( $promotion->ID );
			$promotion->type           = get_post_meta( $promotion->ID, '_promotion-type', true );
			$promotion->duration       = get_post_meta( $promotion->ID, '_promotion-duration-profile', true );
			$promotion->price          = $product->get_price() * lisfinity_get_chosen_currency_rate();
			$promotion->price_html     = lisfinity_get_price_html( $promotion->price );
			$promotion->{'sale-price'} = $product->get_sale_price();
			if ( $promotion->type === 'product' ) {
				$promotion->{'product-type'}   = get_post_meta( $promotion->ID, '_promotion-product-type', true );
				$promotion->duration           = get_post_meta( $promotion->ID, '_promotion-duration', true );
				$promotion->duration_step      = get_post_meta( $promotion->ID, '_promotion-duration-step', true );
				$promotion->duration_min_value = get_post_meta( $promotion->ID, '_promotion-duration-min-value', true );
				$promotion->duration_max_value = get_post_meta( $promotion->ID, '_promotion-duration-max-value', true );
			}
			if ( $promotion->type === 'premium_profile' ) {
				$promotion->cost_type = carbon_get_post_meta( $promotion->ID, 'promotion-cost-type' );
			}
			$promotion->thumbnail = false;
			if ( has_post_thumbnail( $promotion->ID ) ) {
				$thumbnail['id']      = get_post_thumbnail_id( $promotion->ID );
				$thumbnail['url']     = wp_get_attachment_image_url( $thumbnail['id'], 'full' );
				$thumbnail['caption'] = get_the_post_thumbnail_caption( $thumbnail['id'] );
				$thumbnail['meta']    = wp_get_attachment_metadata( $thumbnail['id'] );
				$thumbnail['thumb']   = wp_get_attachment_metadata( $thumbnail['id'] );
				$promotion->thumbnail = $thumbnail;
			}
			$promotion->currency            = get_woocommerce_currency_symbol();
			$promotion->decimals            = wc_get_price_decimals();
			$promotion->decimals_separator  = wc_get_price_decimal_separator();
			$promotion->thousands_separator = wc_get_price_thousand_separator();
			$promotion->price_format        = get_woocommerce_price_format();

			$prepared[] = $promotion;
		}

		return $prepared;
	}

	/**
	 * Get the correct wc_product from the specified promotion position
	 * ----------------------------------------------------------------
	 *
	 * @param $position
	 *
	 * @return array
	 */
	public function get_promotion_product( $position ) {
		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'tax_query'           => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => 'promotion',
					'operator' => 'IN',
				],
			],
			'meta_key'            => '_promotion-product-type',
			'meta_value'          => $position,
			'no_found_rows'       => true,
			'cache_results'       => true,
		];

		$product = new \WP_Query( $args );

		return $product->posts;
	}

	public function get_premium_profile_promotion_product() {
		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'meta_key'            => '_promotion-type',
			'meta_value'          => 'premium_profile',
			'no_found_rows'       => true,
			'cache_results'       => true,
			'fields'              => 'ids',
		];

		$product = new \WP_Query( $args );

		return $product->posts;
	}

	/**
	 * Get all ads by the given position
	 * ---------------------------------
	 *
	 * @param $position
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_ads_with_promotion( $position ) {
		$products = $this->where( [
			[ 'position', $position ],
			[ 'status', 'active' ],
			[ 'expires_at', '>=', 'UNIX_TIMESTAMP()' ]
		] )->get( '', 'ORDER BY created_at DESC', 'created_at, product_id' );

		return $products;
	}

}
