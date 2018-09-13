<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="padding-top-2x mt-2 hidden-lg-up"></div>

<h4><?php _e( 'Account Details', 'fury' ); ?></h4>

<hr class="padding-bottom-1x">

<form class="row woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
    
    <hr class="padding-bottom-1x">
    
    <div class="col-md-6">
        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="account_first_name"><?php esc_html_e( 'First name', 'fury' ); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
        </p>
    </div>
    
    <div class="col-md-6">
        <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
            <label for="account_last_name"><?php esc_html_e( 'Last name', 'fury' ); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
        </p>
    </div>
    
    <div class="col-md-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="account_display_name"><?php esc_html_e( 'Display name', 'fury' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'fury' ); ?></em></span>
	   </p>
    </div>
    
    <div class="col-md-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="account_email"><?php esc_html_e( 'Email address', 'fury' ); ?> <span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--email input-text form-control" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
        </p>
    </div>
    
    <div class="col-md-12">
        <fieldset>
            
            <div class="padding-top-2x mt-2 hidden-lg-up"></div>
            
            <h4><?php esc_html_e( 'Password change', 'fury' ); ?></h4>
            
            <hr class="padding-bottom-1x">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'fury' ); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'fury' ); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password_2"><?php esc_html_e( 'Confirm new password', 'fury' ); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />
                    </p>
                </div>
            </div>
        </fieldset>
	</div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="btn btn-primary margin-bottom-none" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'fury' ); ?>"><?php esc_html_e( 'Save changes', 'fury' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
