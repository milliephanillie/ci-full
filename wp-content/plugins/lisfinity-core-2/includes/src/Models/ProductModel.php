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

use function Lisfinity\Schedules\lisfinity_schedules;

/**
 * Class ProductModel
 * ------------------------------
 *
 * @package Lisfinity\Product
 */
class ProductModel {

	private $query_args;

	public function init() {
		add_image_size( 'product-thumbnail-placeholder', 1, 1, true );
		add_image_size( 'product-thumbnail', 480, 260, true );
		add_image_size( 'product-slider', 740, 410, true );
		add_image_size( 'product-slider-thumb', 75, 75, true );
	}

	/**
	 * Define all available product types
	 * ----------------------------------
	 *
	 * @return array
	 */
	public function available_product_types() {
		$types = [
			'ad'       => __( 'Ad', 'lisfinity-core' ),
			'discount' => __( 'Discount', 'lisfinity-core' ),
			'event'    => __( 'Event', 'lisfinity-core' ),
			'rent'     => __( 'Rent', 'lisfinity-core' ),
		];

		return apply_filters( 'lisfinity__available_product_types', $types );
	}

	/**
	 * Get default product types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_default_product_types() {
		$types = $this->available_product_types();
		if ( empty( $types ) ) {
			return [];
		}

		$types = array_keys( $types );

		return apply_filters( 'lisfinity__get_default_product_types', $types );
	}

	/**
	 * Get chosen product types from the list of available ones
	 * ---------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_chosen_product_types() {
		$types           = get_option( 'product-types' );
		$available_types = $this->available_product_types();
		$chosen_types    = [];

		if ( empty( $types ) ) {
			return $available_types;
		}

		foreach ( $available_types as $value => $label ) {
			if ( in_array( $value, $types, true ) ) {
				$chosen_types[ $value ] = $label;
			}
		}

		return $chosen_types;
	}

	/**
	 * Get product duration number
	 * ---------------------------
	 *
	 * @return bool|mixed
	 */
	public function get_product_duration() {
		$duration = lisfinity_get_option( 'product-duration' );

		if ( empty( $duration ) || ! $duration ) {
			return false;
		}

		return $duration;
	}

	/**
	 * Calculate the product expiration date
	 * -------------------------------------
	 *
	 * @param string $duration
	 *
	 * @return bool|false|int
	 */
	public function calculate_expiration_date( $duration = '' ) {
		if ( empty( $duration ) ) {
			$duration = $this->get_product_duration();
		}
		if ( ! $duration ) {
			return false;
		}

		$current_time = current_time( 'timestamp' );
		$expiration   = strtotime( "+ {$duration} days", $current_time );

		return $expiration;
	}

	public function manage_columns( $columns ) {
		$product_type = $_GET['product_type'] ?? false;

		if ( ! in_array( $product_type, [ 'simple', 'grouped', 'external', 'variable' ] ) ) {
			unset( $columns['product_cat'] );
			unset( $columns['product_tag'] );
			unset( $columns['featured'] );
			unset( $columns['sku'] );
		}

		$custom_columns = [
			'ad_expires'  => __( 'Ad Expires', 'lisfinity-core' ),
			'ad_category' => __( 'Ad Category', 'lisfinity-core' ),
			'ad_vendor'   => __( 'Vendor', 'lisfinity-core' ),
		];

		return lisfinity_insert_at_position( $columns, $custom_columns, 3 );
	}

	/**
	 * Manage Custom Report Listings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		$product_type = get_the_terms( $post_id, 'product_type' );

		if ( 'ad_category' === $column && ! empty( $product_type[0] ) && 'listing' === $product_type[0]->slug ) {
			$type        = carbon_get_post_meta( $post_id, 'product-category' );
			$price_type  = carbon_get_post_meta( $post_id, 'product-price-type' );
			$price_types = lisfinity_available_price_types();
			?>
			<span style="font-weight: 700;"><?php echo esc_html( ucwords( str_replace( '-', ' ', $type ) ) ); ?></span>
			<br/>
			<span
				style="font-size: 10px; font-weight: 700; color: green; "><?php echo esc_html( $price_types[ $price_type ] ); ?></span>
			<?php
		}

		if ( 'ad_expires' === $column && ! empty( $product_type[0] ) && 'listing' === $product_type[0]->slug ) {
			if ( ! empty( $product_type[0] ) ) :
				if ( 'listing' === $product_type[0]->slug ) :
					$expires = carbon_get_post_meta( $post_id, 'product-expiration' );
					?>
					<?php if ( $expires < current_time( 'timestamp' ) ) : ?>
					<span
						style="font-weight: 700; padding: 2px 4px; background-color: #f86a6a; color: #fff; border-radius: 4px;"><?php _e( 'Expired', 'lisfinity-core' ); ?></span>
				<?php else: ?>
					<span
						style="font-weight: 700;"><?php echo esc_html( human_time_diff( current_time( 'timestamp' ), $expires ) ); ?></span>
					<br/>
					<span><?php echo esc_html( date( 'Y-m-d H:i:s', (int) $expires ) ); ?></span>
				<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php
		}

		if ( 'ad_vendor' === $column && ! empty( $product_type[0] ) && 'listing' === $product_type[0]->slug ) {
			$business      = carbon_get_post_meta( $post_id, 'product-business' );
			$business_link = get_admin_url() . 'post.php?post=' . $business . '&action=edit';
			?>
			<a href="<?php echo esc_url( $business_link ); ?>" target="_blank"
			   style="font-weight: 700;"><?php echo esc_html( get_the_title( $business ) ); ?></a>
			<?php
		}

		if ( 'is_in_stock' === $column ) {
			if ( lisfinity_is_enabled( lisfinity_get_option( 'product-stock-manage' ) ) ) {
				$product_type = get_the_terms( $post_id, 'product_type' );
				if ( ! empty( $product_type[0] ) ) {
					if ( 'listing' === $product_type[0]->slug ) {
						$stock = get_post_meta( $post_id, '_stock_custom', true );
						if ( '' !== $stock && 0 == $stock ) {
							$stock_html = '<mark class="outofstock outofstock-lisfinity">' . __( 'Out of stock', 'lisfinity-core' ) . '</mark>';
						} else {
							$stock_html = '<mark class="instock instock-lisfinity">' . __( 'In stock', 'lisfinity-core' ) . '</mark>';
							$stock_html .= '' !== $stock ? " ($stock)" : '';
						}

						echo $stock_html;
					}
				}
			}

		}

		return $column;
	}

	/**
	 * Get products query
	 * ------------------
	 *
	 * @param array = $additional_args
	 * @param boolean = $show_expired
	 *
	 * @return \WP_Query
	 */
	public function get_products_query( $additional_args = [], $show_expired = false ) {
		// todo we should add custom hooks for including promotions from the database so we don't have to write a new query every time.
		// todo or we simply can extend default WP_Query and create our own then we can call promotion hook in query args.
		// todo see CouponXxL for extending a query as we've done it there already.
		$type = \WC_Product_Listing::$type;
		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'tax_query'           => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $type,
					'operator' => 'IN',
				],
			],
		];

		// written here because it cannot be overridden otherwise.
		if ( ! isset( $additional_args['posts_per_page'] ) ) {
			$args['posts_per_page'] = - 1;
		}

		$args = wp_parse_args( $additional_args, $args );

		$this->query_args = $args;

		add_filter( 'posts_join', [ $this, 'products_join_meta' ] );
		if ( ! $show_expired ) {
			add_filter( 'posts_where', [ $this, 'products_where_expires' ], 10, 2 );
		}

		if ( isset( $additional_args['owner'] ) ) {
			add_filter( 'posts_where', [ $this, 'products_where_owner' ], 10, 2 );
		}
		if ( isset( $additional_args['meta'] ) ) {
			add_filter( 'posts_where', [ $this, 'products_where_r' ], 10, 2 );
		}

		$products = new \WP_Query( $args );

		if ( ! $show_expired ) {
			$this->remove_expired_db_filters();
		}

		$this->remove_custom_db_filters();

		return $products;
	}

	/**
	 * Join products promotion table to default $wpdb->posts table
	 * -----------------------------------------------------------
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function products_join_meta( $join ) {
		global $wpdb;

		if ( false === strpos( $join, 'LEFT JOIN wp_postmeta' ) ) {
			$join .= " LEFT JOIN {$wpdb->postmeta} AS my_meta ON $wpdb->posts.ID = my_meta.post_id ";

			if ( isset( $this->query_args['owner'] ) ) {
				$join .= " LEFT JOIN {$wpdb->postmeta} AS owner_meta ON $wpdb->posts.ID = owner_meta.post_id ";
			}
		}

		return $join;
	}

	/**
	 * Include query parameter to load products with a running promotion
	 * -----------------------------------------------------------------
	 *
	 * @param $where
	 *
	 * @return string
	 */
	public function products_where_expires( $where ) {
		$where .= " AND ( my_meta.meta_key = '_product-expiration' AND my_meta.meta_value >= UNIX_TIMESTAMP() ) ";

		return $where;
	}

	public function products_where_owner( $where ) {
		if ( is_array( $this->query_args['owner'] ) ) {
			$owners = implode( ',', $this->query_args['owner'] );
			$where  .= " AND ( owner_meta.meta_key = '_product-owner' AND owner_meta.meta_value IN ($owners) ) ";
		} else {
			$where .= " AND ( owner_meta.meta_key = '_product-owner' AND owner_meta.meta_value = {$this->query_args['owner']} ) ";
		}

		return $where;
	}

	/**
	 * Remove custom db filters so they can't interfere with
	 * other wp queries
	 * -----------------------------------------------------
	 */
	public function remove_custom_db_filters() {
		// globally join meta table.
		remove_filter( 'posts_join', [ $this, 'products_join_meta' ] );

		// remove owner.
		remove_filter( 'posts_where', [ $this, 'products_where_owner' ] );
	}

	/**
	 * Remove custom db filters that is checking for expired ads
	 * so they can't interfere with other wp queries
	 * -----------------------------------------------------
	 */
	public function remove_expired_db_filters() {
		// remove expires.
		remove_filter( 'posts_where', [ $this, 'products_where_expires' ] );
	}

	/**
	 * Format products query to be used in select field
	 * ------------------------------------------------
	 *
	 * @return array
	 */
	public function format_products_select() {
		$select   = [];
		$products = $this->get_products_query();

		if ( $products->have_posts() ) {
			foreach ( $products->posts as $product ) {
				$select[ $product->ID ] = $product->post_title;
			}
		}

		return $select;
	}

}
