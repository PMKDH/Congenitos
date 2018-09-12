<?php

namespace MPHBW;

class WoocommerceListener {

	/**
	 *
	 * @var string
	 */
	private $productId;

	public function __construct(){
		$this->productId = MPHBW()->getSettings()->main()->getProductId();

		// Fail Payment
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'failPaymentByWooOrder' ), 10, 1 );
		add_action( 'woocommerce_order_status_failed', array( $this, 'failPaymentByWooOrder' ), 10, 1 );
		add_action( 'woocommerce_order_status_trash', array( $this, 'failPaymentByWooOrder' ), 10, 1 );
		add_action( 'before_delete_post', array( $this, 'failPaymentByWooOrder' ), 10, 1 );

		// Complete Payment
		add_action( 'woocommerce_order_status_completed', array( $this, 'completePaymentByWooOrder' ), 10, 1 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'completePaymentByWooOrder' ), 10, 1 );

		// Hold Payment
		add_action( 'woocommerce_order_status_on-hold', array( $this, 'holdPaymentByWooOrder' ), 10, 1 );
		add_action( 'woocommerce_order_status_pending', array( $this, 'holdPaymentByWooOrder' ), 10, 1 );

		// Refund Payment
		add_action( 'woocommerce_order_status_refunded', array( $this, 'refundPaymentByWooOrder' ), 10, 1 );

		// Change payment status after order creation.
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'processNewOrder' ), 10, 1 );

		// Remove order from cart after cancellation by user
		add_action( 'woocommerce_cancelled_order', array( $this, 'removeCancelledPaymentFromCart' ), 10, 1 );

		// Remove uncompleted payment before search (for unblock accommodation while correcting search parameters)
		add_action( 'mphb_load_search_results_page', array( $this, 'failPendingPayment' ), 10, 0 );
	}

	public function failPendingPayment(){
		$cart = wc()->cart;
		if ( !$cart ) {
			return;
		}

		foreach ( $cart->get_cart_contents() as $cartItemKey => $cartItemData ) {
			if ( $cartItemData['product_id'] != $this->productId ) {
				continue;
			}
			if ( !isset( $cartItemData['_mphb_payment_id'] ) ) {
				continue;
			}
			$payment = MPHB()->getPaymentRepository()->findById( $cartItemData['_mphb_payment_id'] );
			if ( !$payment ) {
				continue;
			}

			add_filter( 'mphb_email_customer_cancelled_booking_prevent', '__return_true' );

			MPHB()->paymentManager()->failPayment( $payment );

			remove_filter( 'mphb_email_customer_cancelled_booking_prevent', '__return_true' );

			$cart->remove_cart_item( $cartItemKey );
		}
	}

	/**
	 *
	 * @param int $orderId
	 */
	public function removeCancelledPaymentFromCart( $orderId ){
		$order = wc_get_order( $orderId );
		if ( !$order ) {
			return;
		}
		$cart = wc()->cart;
		if ( !$cart ) {
			return;
		}
		$paymentId = 0;

		foreach ( $order->get_items() as $orderItem ) {
			if ( $orderItem->get_product_id() == $this->productId &&
				!empty( $orderItem->get_meta( '_mphb_payment_id' ) )
			) {
				$paymentId = $orderItem->get_meta( '_mphb_payment_id' );
			}
		}

		foreach ( $cart->get_cart_contents() as $cartItemKey => $cartItem ) {
			if ( $cartItem['product_id'] == $this->productId &&
				isset( $orderItem['_mphb_payment_id'] ) &&
				$orderItem['_mphb_payment_id'] == $paymentId
			) {
				$cart->remove_cart_item( $cartItemKey );
			}
		}
	}

	/**
	 *
	 * @param int $orderId
	 */
	public function processNewOrder( $orderId ){
		$order = wc_get_order( $orderId );
		switch ( $order->get_status() ) {
			// new order default status
			case 'pending':
				$this->holdPaymentByWooOrder( $orderId );
				break;
		}
	}

	/**
	 *
	 * @param int $orderId
	 */
	public function failPaymentByWooOrder( $orderId ){
		$order = wc_get_order( $orderId );
		if ( !$order ) {
			return;
		}

		foreach ( $order->get_items() as $orderItemId => $orderItem ) {
			if ( $orderItem->get_product_id() != $this->productId ) {
				continue;
			}

			$paymentId = $orderItem->get_meta( '_mphb_payment_id' );
			if ( $paymentId ) {
				$payment = MPHB()->getPaymentRepository()->findById( $paymentId );
				if ( !$payment ) {
					return;
				}

				MPHB()->paymentManager()->failPayment( $payment );
			}
		}
	}

	/**
	 *
	 * @param $orderId
	 */
	public function completePaymentByWooOrder( $orderId ){
		$order = wc_get_order( $orderId );
		if ( !$order ) {
			return;
		}

		foreach ( $order->get_items() as $orderItemId => $orderItem ) {
			if ( $orderItem->get_product_id() != $this->productId ) {
				continue;
			}

			$paymentId = $orderItem->get_meta( '_mphb_payment_id' );
			if ( $paymentId ) {
				$payment = MPHB()->getPaymentRepository()->findById( $paymentId );
				if ( !$payment ) {
					return;
				}

				MPHB()->paymentManager()->completePayment( $payment );
			}
		}
	}

	/**
	 *
	 * @param $orderId
	 */
	public function holdPaymentByWooOrder( $orderId ){
		$order = wc_get_order( $orderId );
		if ( !$order ) {
			return;
		}

		foreach ( $order->get_items() as $orderItemId => $orderItem ) {
			if ( $orderItem->get_product_id() != $this->productId ) {
				continue;
			}

			$paymentId = $orderItem->get_meta( '_mphb_payment_id' );
			if ( $paymentId ) {
				$payment = MPHB()->getPaymentRepository()->findById( $paymentId );
				if ( !$payment ) {
					return;
				}

				MPHB()->paymentManager()->holdPayment( $payment );
			}
		}
	}

	/**
	 *
	 * @param $orderId
	 */
	public function refundPaymentByWooOrder( $orderId ){
		$order = wc_get_order( $orderId );
		if ( !$order ) {
			return;
		}

		foreach ( $order->get_items() as $orderItemId => $orderItem ) {
			if ( $orderItem->get_product_id() != $this->productId ) {
				continue;
			}

			$paymentId = $orderItem->get_meta( '_mphb_payment_id' );
			if ( $paymentId ) {
				$payment = MPHB()->getPaymentRepository()->findById( $paymentId );
				if ( !$payment ) {
					return;
				}

				MPHB()->paymentManager()->refundPayment( $payment );
			}
		}
	}

}
