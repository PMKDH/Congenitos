<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

wc_print_notices(); ?>

<div class="col-lg-8 col-md-10">

    <h2><?php _e( 'Lost your password?', 'fury' ); ?></h2>

    <ol class="list-unstyled">
        <li><span class="text-primary text-medium">1.</span> <?php echo esc_html__( 'Please enter your username or email address.'. 'fury' ); ?></li>
        <li><span class="text-primary text-medium">2.</span> <?php echo esc_html__( 'You will receive a link to create a new password via email.', 'fury' ); ?></li>
    </ol>

    <form method="post" class="card mt-4">
        <div class="card-body">
            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="user_login"><?php esc_html_e( 'Username or email', 'fury' ); ?></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" />
            </p>

            <div class="clear"></div>

            <?php do_action( 'woocommerce_lostpassword_form' ); ?>
        </div>

        <div class="card-footer">
            <input type="hidden" name="wc_reset_password" value="true" />
            <button type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Reset password', 'fury' ); ?>"><?php esc_html_e( 'Reset password', 'fury' ); ?></button>
        </div>

        <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
        
    </form>
</div>
