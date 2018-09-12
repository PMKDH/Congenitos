<?php

namespace MPHBW;

use \MPHB\Admin\Groups;
use \MPHB\Admin\Fields;

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class WoocommerceGateway extends \MPHB\Payments\Gateways\Gateway {

	public function __construct(){
		add_filter( 'mphb_gateway_has_sandbox', array( $this, 'hideSandbox' ), 10, 2 );
		parent::__construct();

		if ( $this->isActive() ) {
			add_filter( 'mphb_sc_checkout_single_gateway_hide_billing_details', array( $this, 'hideBillingDetails' ), 10, 2 );
			new WoocommerceListener();
			new WoocommerceHelper( MPHBW()->getSettings()->main()->getProductId() );

			if ( MPHBW()->getSettings()->main()->isUseRedirect() ) {
				add_filter( 'woocommerce_get_return_url', array( $this, 'addRedirectToWooReturnUrl' ), 10, 2 );
				add_filter( 'woocommerce_get_cancel_order_url_raw', array( $this, 'addRedirectToWooCancelUrl' ), 10, 1 );
				add_filter( 'woocommerce_get_cancel_order_url', array( $this, 'addRedirectToWooCancelUrl' ), 10, 1 );
				add_action( 'woocommerce_thankyou', array( $this, 'redirect' ), 10, 0 );
			}
		}

		add_action( 'update_option_mphbw_product_id', array( $this, 'fixMPHBProductPrice' ), 10, 2 );
	}

	/**
	 *
	 * @param boolean $isShow
	 * @param string $gatewayId
	 * @return boolean
	 */
	public function hideSandbox( $isShow, $gatewayId ){
		if ( $gatewayId == $this->id ) {
			$isShow = false;
		}
		return $isShow;
	}

	public function redirect(){
		if ( isset( $_GET['mphbw_redirect'] ) ) {
			wp_safe_redirect( $_GET['mphbw_redirect'] );
		}
	}

	/**
	 *
	 * @param string $returnUrl
	 * @param \WC_Order|null $order
	 * @return string
	 */
	public function addRedirectToWooReturnUrl( $returnUrl, $order ){
		if ( !$order ) {
			return $returnUrl;
		}

		$returnUrl	 = remove_query_arg( 'redirect', $returnUrl );
		$booking	 = null;

		foreach ( $order->get_items() as $orderItem ) {
			if ( $orderItem->get_product_id() != MPHBW()->getSettings()->main()->getProductId() ) {
				continue;
			}
			if ( empty( $orderItem->get_meta( '_mphb_payment_id' ) ) ) {
				continue;
			}
			$payment = MPHB()->getPaymentRepository()->findById( $orderItem->get_meta( '_mphb_payment_id' ) );
			if ( !$payment ) {
				continue;
			}
			$booking = MPHB()->getBookingRepository()->findById( $payment->getBookingId() );
			if ( !$booking ) {
				continue;
			}
		}

		if ( $booking ) {
			$return		 = esc_url_raw( MPHB()->settings()->pages()->getPaymentSuccessPageUrl( $booking ) );
			$returnUrl	 = add_query_arg( 'mphbw_redirect', $return, $returnUrl );
		}

		return $returnUrl;
	}

	/**
	 *
	 * @param string $cancelUrl
	 * @return string
	 */
	public function addRedirectToWooCancelUrl( $cancelUrl ){
		$urlQueryVars = array();
		parse_str( wp_parse_url( htmlspecialchars_decode( $cancelUrl ), PHP_URL_QUERY ), $urlQueryVars );
		if ( empty( $urlQueryVars['order_id'] ) ) {
			return $cancelUrl;
		}

		$order = wc_get_order( $urlQueryVars['order_id'] );
		foreach ( $order->get_items() as $orderItem ) {
			if ( $orderItem->get_product_id() != MPHBW()->getSettings()->main()->getProductId() ) {
				continue;
			}
			if ( empty( $orderItem->get_meta( '_mphb_payment_id' ) ) ) {
				continue;
			}
			$payment = MPHB()->getPaymentRepository()->findById( $orderItem->get_meta( '_mphb_payment_id' ) );
			if ( !$payment ) {
				continue;
			}
			$booking = MPHB()->getBookingRepository()->findById( $payment->getBookingId() );
			if ( !$booking ) {
				continue;
			}
		}

		if ( $booking ) {
			$cancelUrl = add_query_arg( 'redirect', esc_url_raw( MPHB()->settings()->pages()->getPaymentFailedPageUrl( $booking ) ), $cancelUrl );
		}

		return $cancelUrl;
	}

	public function fixMPHBProductPrice( $oldProductId, $productId ){
		// Skip same id
		if ( $productId == $oldProductId ) {
			return;
		}

		$product = wc_get_product( $productId );
		if ( !$product ) {
			return;
		}

		// Set default fake price to avoid failing is_purchasable
		if ( $product->get_price() == '' ) {
			$defaultFakePrice = 1;
			$product->set_regular_price( $defaultFakePrice );
			$product->save();
		}
	}

	/**
	 *
	 * @param bool $isHide
	 * @param \MPHB\Payments\Gateways\Gateway $gateway
	 * @return boolean
	 */
	public function hideBillingDetails( $isHide, $gateway ){
		if ( $gateway->getId() == $this->id ) {
			$isHide = true;
		}
		return $isHide;
	}

	protected function initDefaultOptions(){
		$defaults = array(
			'title'			 => __( 'WooCommerce Payments', 'mphb-woocommerce' ),
			'description'	 => '',
			'enabled'		 => false,
			'product_id'	 => ''
		);

		return array_merge( parent::initDefaultOptions(), $defaults );
	}

	protected function setupProperties(){
		parent::setupProperties();
		$this->adminTitle = __( 'WooCommerce', 'mphb-woocommerce' );
	}

	protected function getWooProducts(){
		$goods	 = array( 0 => __( 'Select product', 'mphb-woocommerce' ) );
		$args	 = array(
			'numberposts'		 => -1,
			'post_type'			 => 'product',
			'suppress_filters'	 => true
		);

		$collection = get_posts( $args );

		foreach ( $collection as $item ) {
			$goods[$item->ID] = $item->post_title;
		}

		return $goods;
	}

	/**
	 *
	 * @param \MPHB\Admin\Tabs\SettingsSubTab $subTab
	 */
	public function registerOptionsFields( &$subTab ){
		parent::registerOptionsFields( $subTab );

		$group = new Groups\SettingsGroup( "mphb_payments_{$this->id}_group2", '', $subTab->getOptionGroupName() );

		$products = $this->getWooProducts();

		$groupFields = array(
			Fields\FieldFactory::create( "mphbw_product_id", array(
				'type'			 => 'select',
				'label'			 => __( 'Product', 'mphb-woocommerce' ),
				'description'	 => sprintf( __( 'Go to <a href="%s">WooCommerce > Products</a> and create a product that will be used to make reservations. <strong>Required</strong>.', 'mphb-woocommerce' ), get_admin_url( null, 'post-new.php?post_type=product' ) ),
				'list'			 => $products,
				'default'		 => '0'
			) ),
			Fields\FieldFactory::create( "mphbw_use_redirect", array(
				'type'			 => 'checkbox',
				'label'			 => __( 'Checkout endpoints', 'mphb-woocommerce' ),
				'inner_label'	 => __( 'Use Hotel Booking checkout endpoints instead of WooCommerce ones.', 'mphb-woocommerce' ),
				'default'		 => false
			) )
		);

		$group->addFields( $groupFields );

		$subTab->addGroup( $group );

		if ( MPHBW()->getSettings()->license()->isEnabled() ) {
			$licenseGroup = new Admin\Groups\LicenseSettingsGroup( 'mphbw_license_group', __( 'License', 'mphb-woocommerce' ), $subTab->getOptionGroupName() );
			$subTab->addGroup( $licenseGroup );
		}
	}

	protected function initId(){
		return 'woocommerce';
	}

	/**
	 *
	 * @return bool
	 */
	public function isActive(){
		return parent::isActive() && MPHBW()->getDependencies()->check() && MPHBW()->getDependencies()->checkCurrency() && $this->checkProduct();
	}

	/**
	 *
	 * @return boolean
	 */
	private function checkProduct(){

		$productId = MPHBW()->getSettings()->main()->getProductId();
		if ( !$productId ) {
			return false;
		}

		// @todo not usable on early actions ( before wp_loaded )
//		$product = wc_get_product( $productId );
//		if ( !$product ) {
//			return false;
//		}

		return true;
	}

	/**
	 *
	 * @param \MPHB\Entities\Booking $booking
	 * @param \MPHB\Entities\Payment $payment
	 */
	public function processPayment( \MPHB\Entities\Booking $booking, \MPHB\Entities\Payment $payment ){

		$this->addPaymentToWooCart( $booking, $payment );

		// Redirect to WooCommerce checkout
		wp_redirect( wc_get_checkout_url() );
		exit;
	}

	/**
	 *
	 * @param \MPHB\Entities\Booking $booking
	 * @param \MPHB\Entities\Payment $payment
	 */
	function addPaymentToWooCart( $booking, $payment ){

		$cart = WC()->cart;
		if ( !method_exists( $cart, 'add_to_cart' ) ) {
			return;
		}

		$cart->empty_cart();

		$quantity = 1;

		$itemData = array(
			'_mphb_payment_id' => $payment->getId()
		);

		$cart->add_to_cart( MPHBW()->getSettings()->main()->getProductId(), $quantity, 0, array(), $itemData );

		return true;
	}

}
