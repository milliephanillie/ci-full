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
 * Class PackageModel
 * ------------------------------
 *
 * @package Lisfinity
 */
class SubscriptionModel extends Model {

	protected $table = 'subscriptions';

	public static $type = 'payment_subscription';

	public function __construct() {
		parent::__construct();
		$this->register_post_type();
	}

	/**
	 * Set the fields for the table
	 * ----------------------------
	 *
	 * @return array
	 */
	protected function set_table_fields() {
		$this->fields = [
			'user_id'           => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_id'        => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'order_id'          => [
				'type'  => 'bigint(20)',
				'value' => '0',
			],
			'products_limit'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'products_count'    => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'promotions_limit'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'promotions_count'  => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'products_duration' => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
			'product_price'     => [
				'type'  => 'varchar(100)',
				'value' => '0',
			],
			'transaction_fee'   => [
				'type'  => 'bigint(20)',
				'value' => '0',
			],
			'type'              => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'status'            => [
				'type'  => 'varchar(100)',
				'value' => 'NULL',
			],
			'starts_at'         => [
				'type'  => 'timestamp',
				'value' => "'0000-00-00 00:00:00'",
			],
			'expires_at'        => [
				'type'  => 'timestamp',
				'value' => "'0000-00-00 00:00:00'",
			],
			'post_id'           => [
				'type'  => 'bigint(20)',
				'value' => 'NULL',
			],
		];

		return $this->fields;
	}

	protected function register_post_type() {
		$post_type = self::$type;

		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Payment Subscription', 'lisfinity-core' );
		$plural   = __( 'Payment Subscriptions', 'lisfinity-core' );

		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => [ 'title' ],
			'menu_icon'          => 'dashicons-products',
			'menu_position'      => 29,
			'labels'             => [
				'name'               => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'lisfinity-core' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'lisfinity-core' ), $plural ),
				'add_new'            => __( 'Add New', 'lisfinity-core' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'lisfinity-core' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'lisfinity-core' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'lisfinity-core' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'lisfinity-core' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'lisfinity-core' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'lisfinity-core' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'lisfinity-core' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'lisfinity-core' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'lisfinity-core' ), $plural ),
			],
		);

		register_post_type( $post_type, $args );
	}

	/**
	 * Handler for storing custom values in database that is
	 * activated when WooCommerce order status has been changed.
	 * ---------------------------------------------------------
	 *
	 * @param integer $order_id - Inherited id of WooCommerce order
	 */
	public function order_paid( $order_id ) {
		$order = wc_get_order( $order_id );

		// return if promotion has already been processed.
		if ( get_post_meta( $order_id, 'subscription_processed', true ) ) {
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$product                  = wc_get_product( $item['product_id'] );
			$product_id               = $product->get_id();
			$customer_id              = $order->get_customer_id();
			$products_limit           = carbon_get_post_meta( $product_id, 'subscription-products-limit' );
			$products_duration        = carbon_get_post_meta( $product_id, 'subscription-products-duration' );
			$promotions_limit         = carbon_get_post_meta( $product_id, 'subscription-promotions-limit' );
			$free_trial_period        = carbon_get_post_meta( $product_id, 'subscription-free-trial-period' );
			$additional_product_price = carbon_get_post_meta( $product_id, 'subscription-product-price' );
			$transactions_fee         = carbon_get_post_meta( $product_id, 'subscription-transaction-fee' );

			// start and expiration dates.
			$start   = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
			$expires = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} months", current_time( 'timestamp' ) ) );

			if ( $product->is_type( \WC_Product_Payment_Subscription::$type ) && $customer_id ) {
				$values = [
					// id of the user that made order.
					$customer_id,
					// wc product id, id of this product if needed.
					$product_id,
					// wc order id.
					$order_id,
					// limit of the products.
					$products_limit ?? - 1,
					// spent products count.
					0,
					// promotions limit.
					$promotions_limit ?? - 1,
					// spent promotions count.
					0,
					// products duration.
					$products_duration ?? - 1,
					// additional product submission price.
					is_numeric( $additional_product_price ) ? $additional_product_price : 0,
					// transaction fee for the listings.
					$transactions_fee,
					// subscription.
					'subscription',
					// status of the promotion.
					'active',
					// starts.
					$start,
					// expires.
					$expires,
				];

				$existing_subscription = $this->where( [
					[ 'user_id', $customer_id ],
					[ 'product_id', $item['product_id'] ],
					[ 'status', 'active' ],
				] )->get( '', '', 'id, expires_at' );

				// if the subscription is already existing.
				if ( ! empty( $existing_subscription ) ) {
					$new_expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} months", strtotime( $existing_subscription[0]->expires_at ) ) );
					$this->set( [ [ 'expires_at', $new_expiration_date ], [ 'products_count', 0 ], [ 'promotions_count', 0 ] ] )->where( 'id', $existing_subscription[0]->id )->update();
				} else {
					$customer          = get_userdata( $customer_id );
					$post_id           = wp_insert_post( [
						'post_status' => 'publish',
						'post_type'   => self::$type,
						'post_title'  => sprintf( __( 'Subscription: %s', 'lisfinity-core' ), $customer->display_name ),
					] );
					$values['post_id'] = $post_id;
					// save promotion data in the database.
					$this->store( $values );
				}
				delete_user_meta( $customer_id, 'subscription_expired_mail' );

				// check if the premium profile has been included.
				$include_premium_profile = carbon_get_post_meta( $product_id, 'subscription-include-premium-profile' );
				$business_id = lisfinity_get_premium_profile_id( $customer_id );
				$model                   = new PromotionsModel();
				if ( $include_premium_profile ) {
					$promotion_product_id = $model->get_premium_profile_promotion_product();
					carbon_set_user_meta( $customer_id, 'account-type', 'business' );
					$promotions_values = [
						// payment package id.
						0,
						// wc order id.
						$order_id,
						// wc product id, id of this product if needed.
						$promotion_product_id ?? $product_id,
						// id of the user that made order.
						$customer_id,
						// id of the product that this promotion has been activated for.
						$business_id ?? $item['product'],
						// limit or duration number depending on the type of the promotion.
						$item['quantity'],
						// number of products that used addon promotions type, this shouldn't be higher than value.
						1,
						// position of promotion on the site.
						'profile-premium',
						// type of the promotion.
						'premium_profile',
						// status of the promotion
						'active',
						// activation date of the promotion
						$start,
						// expiration date of the promotion if needed.
						$expires,
					];

					$promotion_model = new PromotionsModel();
					// try to find existing promotion so we can update it instead of storing a new value.
					$existing_promotion = $promotion_model->where( [
						[ 'position', 'profile-premium' ],
						[ 'user_id', $customer_id ],
						[ 'status', 'active' ],
					] )->get( '', '', 'id, expires_at' );

					// update the expiration date in the database.
					if ( ! empty( $existing_promotion ) ) {
						$new_expiration_date = date( 'Y-m-d H:i:s', strtotime( "+ {$item['quantity']} months", strtotime( $existing_promotion[0]->expires_at ) ) );
						$promotion_model->set( 'expires_at', $new_expiration_date )->where( 'id', $existing_promotion[0]->id )->update();
					} else {
						// save promotion data in the database.
						$promotion_model->store( $promotions_values );
					}
				}
			}

			update_post_meta( $order_id, 'type', 'subscription' );
			update_post_meta( $order_id, 'subscription_processed', true );
		}

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
		if ( ! isset( $values['publish_product'] ) || empty( $values['publish_product'] ) ) {
			return;
		}

		// We're adding custom wc product to wc order as wc uses promotion product for the id.
		$item->update_meta_data( 'publish_product', $values['publish_product'] );
	}

	public function manage_columns( $columns ) {
		$old_columns = $columns;
		$columns     = array(
			'cb'      => $old_columns['cb'],
			'title'   => __( 'Title', 'lisfinity-core' ),
			'package' => __( 'Package', 'lisfinity-core' ),
			'order'   => __( 'Order', 'lisfinity-core' ),
			'vendor'  => __( 'Vendor', 'lisfinity-core' ),
			'starts'  => __( 'Started', 'lisfinity-core' ),
			'renews'  => __( 'Renews', 'lisfinity-core' ),
		);

		return apply_filters( 'lisfinity__manage_columns_subscriptions', $columns );
	}

	public function manage_custom_column( $column, $post_id ) {
		$model        = new SubscriptionModel();
		$subscription = $model->where( [
			[ 'post_id', $post_id ],
			[ 'status', 'active' ]
		] )->get( '', '', 'id, user_id, product_id, order_id, starts_at, expires_at' );
		if ( ! $subscription ) {
			return;
		}
		$subscription = array_shift( $subscription );
		$user         = get_userdata( $subscription->user_id );
		$business_id  = lisfinity_get_premium_profile_id( $subscription->user_id );
		switch ( $column ) {
			case 'package':
				$listing_permalink = admin_url( '/post.php?post=' . $subscription->product_id . '&action=edit' );
				?>
				<div class="payout-order"><a
						href="<?php echo esc_url( $listing_permalink ); ?>"><strong><?php echo esc_html( get_the_title( $subscription->product_id ) ); ?></strong></a>
				</div>
				<?php
				break;
			case 'vendor':
				$business_permalink = admin_url( '/post.php?post=' . $business_id . '&action=edit' );
				?>
				<a href="<?php echo esc_url( $business_permalink ); ?>"><?php echo esc_html( get_the_title( $business_id ) ); ?></a>
				<?php
				break;
			case 'order':
				$order_permalink = admin_url( '/post.php?post=' . $subscription->order_id . '&action=edit' );
				?>
				<div class="payout-order"><a
						href="<?php echo esc_url( $order_permalink ); ?>"><strong><?php echo sprintf( esc_html__( 'Order: #%s', 'lisfinity-core' ), $subscription->order_id ); ?></strong></a>
				</div>
				<?php
				break;
			case 'starts':
				?>
				<div class="subscription-starts">
					<?php echo esc_html( $subscription->starts_at ); ?>
				</div>
				<?php
				break;
			case 'renews':
				?>
				<div class="subscription-renews">
					<?php echo esc_html( $subscription->expires_at ); ?>
				</div>
				<?php
				break;
			default :
				break;
		}

		return apply_filters( 'lisfinity_manage_custom_column_subscriptions', $column, $post_id );
	}

	/**
	 * Get all packages related to a given user
	 * ----------------------------------------
	 *
	 * @param $user_id
	 *
	 * @return array|mixed|object|string|void|null
	 */
	public function get_user_packages( $user_id ) {
		$package_model = new PackageModel();
		$packages      = $package_model->where( 'user_id', $user_id )->get();

		return $packages;
	}

	/**
	 * Add package to cart and proceed to checkout
	 * -------------------------------------------
	 */
	public function add_to_cart() {
		if ( isset( $_GET['lc-cart'] ) ) {
			WC()->cart->empty_cart();
			$key = WC()->cart->add_to_cart( $_GET['lc-cart'] );

			if ( ! $key ) {
				return __( 'Package could not be added to the cart.', 'lisfinity-core' );
			}

			$permalink = get_permalink( wc_get_page_id( 'checkout' ) );

			if ( wp_safe_redirect( $permalink ) ) {
				exit;
			}
		}
	}

	/**
	 * Get product packages query
	 * --------------------------
	 *
	 * @return \WP_Query
	 */
	public function get_promotions_query() {
		$type = \WC_Product_Payment_Subscription::$type;
		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $type,
					'operator' => 'IN',
				],
			],
		];

		$products = new \WP_Query( $args );

		return $products;
	}

	/**
	 * Format products query to be used in select field
	 * ------------------------------------------------
	 *
	 * @return array
	 */
	public function format_promotions_select() {
		$select   = [];
		$products = $this->get_promotions_query();

		if ( $products->have_posts() ) {
			foreach ( $products->posts as $product ) {
				$select[ $product->ID ] = $product->post_title;
			}
		}

		return $select;
	}
}
