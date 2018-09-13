<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
<div class="row">
<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	<div class="col-md-6">

<?php endif; ?>

		<form class="woocommerce-form woocommerce-form-login login-box" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>
            
            <h4 class="margin-bottom-1x"><?php esc_html_e( 'Login', 'fury' ); ?></h4>

			<div class="form-group input-group">
				<input placeholder="<?php echo esc_attr__('Email or username', 'fury' ); ?>" type="text" class="form-control" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                <span class="input-group-addon">
                    <i class="icon-mail"></i>
                </span>
			</div>
            
			<div class="form-group input-group">
				<input class="form-control" type="password" name="password" id="password" placeholder="<?php echo esc_attr__('Password', 'fury' ); ?>" autocomplete="current-password" />
                <span class="input-group-addon">
                    <i class="icon-lock"></i>
                </span>
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>
            
            <div class="d-flex flex-wrap justify-content-between padding-bottom-1x">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <label for="rememberme" class="custom-control-label"><?php esc_html_e( 'Remember me', 'fury' ); ?></label>
                </div>

                <p class="woocommerce-LostPassword lost_password">
                    <a class="navi-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'fury' ); ?></a>
                </p>
            </div>
            
			<div class="text-center text-sm-right">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="btn btn-primary margin-bottom-none" name="login" value="<?php esc_attr_e( 'Login', 'fury' ); ?>"><?php esc_html_e( 'Login', 'fury' ); ?></button>
			</div>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>
    
	<div class="col-md-6">
        
        <div class="padding-top-3x hidden-md-up"></div>
		
        <h3 class="margin-bottom-1x"><?php esc_html_e( 'Register', 'fury' ); ?></h3>

		<form method="post" class="register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'fury' ); ?> <span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<div class="form-group input-group">
				<input placeholder="<?php _e( 'Email or username', 'fury' ); ?>" type="email" class="form-control" name="email" id="reg_email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                <span class="input-group-addon">
                    <i class="icon-mail"></i>
                </span>
			</div>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<div class="form-group input-group">
					<input placeholder="<?php _e( 'Password', 'fury' ); ?>" type="password" class="form-control" name="password" id="reg_password" />
                    <span class="input-group-addon">
                        <i class="icon-lock"></i>
                    </span>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<div class="col-12 text-center text-sm-right">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="btn btn-primary margin-bottom-none" name="register" value="<?php esc_attr_e( 'Register', 'fury' ); ?>"><?php esc_html_e( 'Register', 'fury' ); ?></button>
			</div>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>
    
<?php endif; ?>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
