<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="list-group">
    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
        <?php $classes = wc_get_account_menu_item_classes( $endpoint ); ?>
        <?php $classes = str_replace( 'is-active', 'active', $classes ); ?>
        <?php if( $label == 'Orders' ): ?>
            <a class="list-group-item with-badge <?php echo $classes; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?><span class="badge badge-primary badge-pill"></span></a>
        <?php else: ?>
            <a class="list-group-item <?php echo $classes; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
