<?php

namespace Lisfinity\Models\Vendors;

use Lisfinity\Models\SubscriptionModel;

class PayoutsModel {

	public static $type = 'payout';

	public function init() {
		$this->register_post_type();
	}

	protected function register_post_type() {
		$post_type = self::$type;

		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Vendors Payout', 'lisfinity-core' );
		$plural   = __( 'Vendors Payouts', 'lisfinity-core' );

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

	public function insert_payout( $order_id ) {
		$order = wc_get_order( $order_id );

		// return if payout has already been processed.
		if ( get_post_meta( $order_id, 'payout_processed', true ) ) {
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$customer_id = $order->get_customer_id();
			$product     = wc_get_product( $item['product_id'] );
			$total       = $order->get_total();
			$commission  = (int) lisfinity_get_option( 'vendors-site-commission' );
			$owner_id    = carbon_get_post_meta( $product->get_id(), 'product-owner' );

			// see if the user has different commission depending on the subscription package.
			$subscription_model = new SubscriptionModel();
			$subscription       = $subscription_model->where( [
				[ 'user_id', $owner_id ],
				[ 'status', 'active' ],
			] )->get( '', '', 'id, transaction_fee' );
			if ( ! empty( $subscription ) && isset( $subscription[0]->transaction_fee ) ) {
				$commission = (float) $subscription[0]->transaction_fee;
			}

			if ( 'listing' === \WC_Product_Factory::get_product_type( $product->get_id() ) ) {
				if ( $commission > 0 ) {
					$amount_due         = $total - ( $total * ( $commission / 100 ) );
					$amount_site_earned = $total - $amount_due;

					$payout_id = wp_insert_post( [
						'post_type'   => PayoutsModel::$type,
						'post_status' => 'publish',
						'post_title'  => "Payout for Order #{$order_id}",
					] );

					if ( ! empty( $payout_id ) ) {
						$business_id       = carbon_get_post_meta( $product->get_id(), 'product-business' );
						$stripe_account_id = get_post_meta( $business_id, '_stripe-connect-id', true );
						carbon_set_post_meta( $payout_id, 'payout-product', $item['product_id'] );
						carbon_set_post_meta( $payout_id, 'payout-order', $order_id );
						carbon_set_post_meta( $payout_id, 'payout-vendor', carbon_get_post_meta( $item['product_id'], 'product-owner' ) );
						update_post_meta( $payout_id, '_amount-due', $amount_due );
						update_post_meta( $payout_id, '_amount-earned', $amount_site_earned );
						if ( 'stripe_connect' === $order->get_payment_method() && ! empty( $stripe_account_id ) ) {
							update_post_meta( $payout_id, '_payout-status', 'paid' );
						} else {
							update_post_meta( $payout_id, '_payout-status', 'not_paid' );
						}
					}
				}
			}
		}

		update_post_meta( $order, 'payout_processed', true );
	}

	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'            => $old_columns['cb'],
			'title'         => __( 'Product Title', 'lisfinity-core' ),
			'listing'       => __( 'Listing', 'lisfinity-core' ),
			'order'         => __( 'Order', 'lisfinity-core' ),
			'vendor'        => __( 'Vendor', 'lisfinity-core' ),
			'amount_due'    => __( 'Amount Due', 'lisfinity-core' ),
			'amount_earned' => __( 'Amount Earned', 'lisfinity-core' ),
			'actions'       => __( 'Actions', 'lisfinity-core' ),
		);

		return apply_filters( 'lisfinity__manage_columns_payouts', $columns );
	}

	/**
	 * Manage Custom Bookings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		$listing_id                  = carbon_get_post_meta( $post_id, 'payout-product' );
		$product                     = wc_get_product( $listing_id );
		$order_id                    = carbon_get_post_meta( $post_id, 'payout-order' );
		$order                       = wc_get_order( $order_id );
		$payout_status               = carbon_get_post_meta( $post_id, 'payout-status' );
		$user_id                     = carbon_get_post_meta( $post_id, 'payout-vendor' );
		$user                        = get_userdata( $user_id );
		$business_id                 = lisfinity_get_premium_profile_id( $user_id );
		$percentage_tax              = lisfinity_get_option( 'vendors-site-commission' );
		$amount                      = $order->get_total() ?? 1;
		$percentage                  = ! empty( $percentage_tax ) ? ( $amount * $percentage_tax ) / 100 : '';
		$due_amount                  = ! empty( $percentage ) ? $amount - $percentage : $amount;
		$paypal                      = get_post_meta( $business_id, '_payout-paypal', true );
		$gateway                     = get_post_meta( $business_id, '_payout-gateway', true );
		switch ( $column ) {
			case 'listing':
				$listing_permalink = admin_url( '/post.php?post=' . $listing_id . '&action=edit' );
				?>
				<div class="payout-order"><a
						href="<?php echo esc_url( $listing_permalink ); ?>"><strong><?php echo esc_html( get_the_title( $listing_id ) ); ?></strong></a>
				</div>
				<?php
				break;
			case 'vendor':
				$business_permalink = admin_url( '/post.php?post=' . $business_id . '&action=edit' );
				$stripe_connected_id = get_post_meta( $business_id, '_stripe-connect-id', true );
				?>
				<?php if ( 'bank' === $gateway ) : ?>
				<?php
				$bank = [
					'account_name'   => get_post_meta( (int) $business_id, 'bank__account-name', true ),
					'account_number' => get_post_meta( (int) $business_id, 'bank__account-number', true ),
					'bank_name'      => get_post_meta( (int) $business_id, 'bank__bank-name', true ),
					'routing_number' => get_post_meta( (int) $business_id, 'bank__routing-number', true ),
					'iban'           => get_post_meta( (int) $business_id, 'bank__iban', true ),
					'swift'          => get_post_meta( (int) $business_id, 'bank__swift', true ),
				];
				?>
				<?php if ( ! empty( $bank ) ) : ?>
					<ul>
						<?php foreach ( $bank as $label => $value ) : ?>
							<?php $label = str_replace( '_', ' ', $label ); ?>
							<li style="text-transform: capitalize;"><?php echo _( "<strong>$label</strong>: $value" ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endif; ?>
				<?php if ( 'stripe' === $gateway ) : ?>
				<a href="<?php echo esc_url( $business_permalink ); ?>"><?php echo esc_html( get_the_title( $business_id ) ); ?></a>
				<br/>
				<?php if ( ! empty( $stripe_connected_id ) ) : ?>
					<strong><?php echo sprintf( esc_html__( 'Stripe Connect ID: %s', 'lisfinity-core' ), $stripe_connected_id ); ?></strong>
					<br/>
				<?php endif; ?>
			<?php endif; ?>
				<?php if ( 'paypal' === $gateway ) : ?>
				<?php if ( isset( $paypal ) && ! empty( $paypal ) ) : ?>
					<strong><?php echo sprintf( esc_html__( 'PayPal: %s', 'lisfinity-core' ), $paypal ); ?></strong>
				<?php else: ?>
					<span
						class="no-paypal"><?php esc_html_e( 'User does not have PayPal email address set, click on below email to send them a message about it.', 'lisfinity-core' ); ?></span>
					<br>
					<strong><a
							href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a></strong>
				<?php endif; ?>
			<?php endif; ?>
				<?php
				break;
			case 'order':
				$order_permalink = admin_url( '/post.php?post=' . $order_id . '&action=edit' );
				?>
				<div class="payout-order"><a
						href="<?php echo esc_url( $order_permalink ); ?>"><strong><?php echo sprintf( esc_html__( 'Order: #%s', 'lisfinity-core' ), $order_id ); ?></strong></a>
				</div>
				<?php
				break;
			case 'amount_due':
				?>
				<div class="payout-amount">
					<div
						class="payout-amount--base"><?php printf( __( '<strong>Base: </strong> %s', 'lisfinity-core' ), wc_price( $amount ) ); ?></div>
					<div
						class="payout-amount--percentage"><?php printf( __( '<strong>Site Tax: </strong> %s', 'lisfinity-core' ), wc_price( $percentage ) ); ?></div>
				</div>
				<div class="payout-amount--plus"><?php echo esc_html( '-' ); ?></div>
				<div
					class="payout-amount--total"><?php printf( __( '<strong>Total Due: </strong> %s', 'lisfinity-core' ), wc_price( $due_amount ) ); ?></div>
				<?php
				break;
			case 'amount_earned':
				?>
				<div
					class="payout-amount--total"><?php printf( __( '<strong>Total Earned: </strong> %s', 'lisfinity-core' ), wc_price( $percentage ) ); ?></div>
				<?php
				break;
			case 'actions':
				?>
				<?php if ( 'paid' !== $payout_status ) : ?>
				<?php if ( 'bank' !== $gateway ) : ?>
					<button type="button" class="button button-primary button-large process-payment"
							data-gateway="paypal"
							data-id="<?php echo esc_attr( $post_id ) ?>"
							data-amount="<?php echo esc_attr( $due_amount ); ?>"
							data-confirm="<?php echo esc_attr__( 'Are you sure you wish to send the payment to this user? This action is irreversible.', 'lisfinity-core' ); ?>"
							data-email="<?php echo esc_attr( $paypal ) ?>"><?php echo esc_html__( 'Send Payment', '' ); ?></button>
					<div id="<?php echo esc_attr( "paymentAction-{$post_id}" ); ?>" class="payment-action hidden">
						<strong><?php esc_html_e( 'Payment sent', 'lisfinity-core' ); ?></strong></div>
				<?php else: ?>
					<div><?php esc_html_e( 'Please manually change payout status to Paid once the payment has been made. You can do so by clicking to edit this payout and changing the option in the widget at the right of the screen.', 'lisfinity-core' ); ?></div>
				<?php endif; ?>
			<?php else: ?>
				<div class="booking-payment-action">
					<strong><?php esc_html_e( 'Payment sent', 'lisfinity-core' ); ?></strong></div>
			<?php endif; ?>
				<?php
				break;
			default :
				break;
		}

		return apply_filters( 'lisfinity_manage_custom_column_payouts', $column, $post_id );
	}

	public function add_process_payments_button_to_views( $views ) {
		$views['mass-payment'] = '<button id="process-mass-payment" data-nonce="' . wp_create_nonce( 'process_mass_payment' ) . '" class="button button-primary button-large process-mass-payment" data-confirm="' . esc_html__( 'Are you sure you wish to proceed with mass payment? This action is irreversible.', 'lisfinity-core' ) . '" type="button">' . esc_html__( 'Send Payment To All', 'lisfinity-core' ) . '</button>';

		return $views;
	}

	public function get_payouts( $user_id, $status = '' ) {
		$args = [
			'post_type'      => \Lisfinity\Models\Vendors\PayoutsModel::$type,
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'meta_query'     => [
				[
					'key'     => '_payout-vendor',
					'value'   => $user_id,
					'compare' => '=',
				],
			],
			'fields'         => 'ids',
		];

		if ( ! empty( $status ) ) {
			$args['meta_query'][] = [
				'key'     => '_payout-status',
				'value'   => $status,
				'compare' => '=',
			];
		}

		return get_posts( $args );
	}

	public function calculate_due_amount( $amount ) {
		$percentage = lisfinity_get_option( 'vendors-site-commission' );

		return ! empty( $percentage ) ? ( $amount * $percentage ) / 100 : $amount;
	}

	public function calculate_total_due_amount( $user_id, $status ) {
		$total   = 0;
		$payouts = $this->get_payouts( $user_id, $status );
		if ( empty( $payouts ) ) {
			return $total;
		}

		foreach ( $payouts as $payout_id ) {
			$amount = (float) get_post_meta( $payout_id, '_amount-due', true );

			if ( $amount > 0 ) {
				$total += $amount;
			}
		}

		return $total;
	}

	public function get_payout_ids() {
		return get_posts( [
			'post_type'      => PayoutsModel::$type,
			'posts_per_page' => - 1,
			'meta_query'     => [
				[
					'key'   => '_payout-status',
					'value' => 'not_paid',
				],
			],
			'fields'         => 'ids',
		] );
	}


}
