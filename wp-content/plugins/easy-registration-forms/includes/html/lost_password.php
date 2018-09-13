<?php if(!is_user_logged_in()) : ?>
    <?php if(isset($_POST['erf_lost_password'])) { do_action('erf_lost_password'); } ?>
    <div class="erf-password-lost-container" class="widecolumn" style="display:none">

                    <h3><?php _e( 'Forgot Your Password?', 'erforms' ); ?></h3>
                <p>
                    <?php
                        _e("Enter your email address",'erforms');
                    ?>
                </p>
                <div class="erf-error"></div>
                <form id="lostpasswordform" class="erf-form" action="" method="post" onkeypress="return event.keyCode != 13;">
                    <div class="fb-text form-group">
                        <label for="user_login" class="fb-text-label"><?php _e( 'Email', 'personalize-login' ); ?></label>
                        <input type="email" name="user_login" class="form-control" id="erf_user_login">
                    </div>

                    <div class="fb-text form-group">
                        <button type="button" name="submit" class="lostpassword-button erf-reset-password btn btn-default">
                            <?php _e( 'Reset Password', 'erforms' );?>
                        </button>
                    </div>
                    <input type="hidden" name="action" value="erf_lost_password" />
                    <div class="erf-account-switch erf-clearfix">
                            <a class="erf-show-login" href="javascript:void(0)"><?php _e('Back to Login','erforms'); ?></a>
                    </div>
                </form>
    </div>
<?php endif; ?>
