<div class="erf-login-container">
    
    <div class="erf-error">
        <?php
            $errors = erforms()->errors;
            if (!empty($errors['login_error'])){ // Showing login errors
             echo $errors['login_error'].'<br>';  
            } 
        ?>
    </div>       
      
    
    <div class="erf-message">
        
    </div>

<?php if (!is_user_logged_in()) : ?>
        <form action="" method="post" class="erf-login-form erf-form">
            <div class="fb-text form-group">
                <label for="erf_username" class="fb-text-label">
    <?php _e('Usuario/Email', 'erforms') ?><span class="fb-required">*</span>
                </label>

                <input required="" value="<?php echo isset($_POST['erf_username']) ? $_POST['erf_username'] : ''; ?>" type="text" class="form-control" id="erf_username" name="erf_username">
            </div>

            <div class="fb-text form-group">
                <label for="erf_password" class="fb-text-label">
    <?php _e('Contraseña', 'erforms') ?><span class="fb-required">*</span>
                </label>

                <input type="password" value="<?php echo isset($_POST['erf_password']) ? $_POST['erf_password'] : ''; ?>" required="" class="form-control" id="erf_password" name="erf_password">
            </div>

            <div class="fb-text form-group">

                <label for="rememberme" class="fb-text-label">
                    <input name="rememberme" <?php echo isset($_POST['rememberme']) ? 'checked' : ''; ?> type="checkbox" id="erf_rememberme" value="forever">
    <?php _e('Recuérdame', 'erforms') ?>
                </label>
            </div>


            <input type="hidden" name="action" value="erf_login_user"  />
            <input type="hidden" name="erf_login_nonce" id="erf_login_nonce" value="<?php echo wp_create_nonce('erf_login_nonce'); ?>" />
            
            <div class="erf-before-login-btn">
                <?php do_action('erforms_before_login_button'); ?>
            </div>   
            
            <div class="erf-submit-button">    
                <div class="fb-button form-group">
                    <button type="submit" class="btn btn-default" style="default">Entrar</button>
                </div>
            </div>
    <?php if (isset($attr['show_register_form']) && !empty($attr['show_register_form'])) : ?>
                <div class="erf-account-switch">
                    <a class="erf-show-register" href="javascript:void(0)"><?php _e('Register', 'erforms') ?></a>
                    <a class="erf-show-lost-password"  href="javascript:void(0)" title="<?php _e('Recuperar contraseña', 'erforms') ?>"><?php _e('¿Has olvidado tu contraseña?', 'erforms') ?></a>
                </div>
    <?php else: ?>
                <div class="erf-account-switch">
                    <a class="erf-show-lost-password"  href="javascript:void(0)" title="<?php _e('Recuperar contraseña', 'erforms') ?>"><?php _e('¿Has olvidado tu contraseña?', 'erforms') ?></a>
                </div>
    <?php endif; ?>

        </form>
    
        <form id="erf_login_reload_form" method="POST">

        </form>

    <?php else: ?>
        <div><a href="<?php echo wp_logout_url(get_permalink()); ?>">Logout</a></div>
<?php endif; ?>
</div>

<?php include 'lost_password.php'; ?>