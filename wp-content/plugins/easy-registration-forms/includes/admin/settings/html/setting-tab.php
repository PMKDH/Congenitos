<div class="erf-wrapper erforms-settings wrap erf-wrapper-bg">
    <div class="erf-page-title">
        <h1 class="wp-heading-inline">
            <?php echo $menus[$tab]['label']; ?>
        </h1>
    </div>
    
    <form method="POST">
        <fieldset>
            <div style="<?php echo $tab=='general' ? '' : 'display:none' ?>">
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php echo __('Default Registration Page', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <?php wp_dropdown_pages(array('selected' => $options['default_register_url'], 'show_option_none' => 'Select Page', 'option_none_value' => 0, 'name' => 'default_register_url')); ?>
                        <p class="description"><?php _e('Replaces default WordPress registration page.', 'erforms') ?></p>
                    </div>  
                </div>
                
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php echo __('Default Upload Directory', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <input pattern="^[A-Za-z-_]+$" type="text" name="upload_dir" value="<?php echo $options['upload_dir']; ?>" />
                        <p class="description"><?php _e('Upload directory name where all the file uploads will take place. Please do not use any special characters in directory name. (Only Alphabets,Hyphens(-) and Underscores(_) allowed.)', 'erforms') ?></p>
                    </div>  
                </div>
                
                <div class="erf-row" id="erf_recaptcha">
                    <div class="erf-control-label">
                        <label><?php _e('Configure Recaptcha', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control erf-has-child">
                        <input class="erf_toggle" type="checkbox" data-has-child="1" data-erf-child="erf_settings_recaptcha_options" id="erf_settings_recaptcha_configured" name="recaptcha_configured" value="1" <?php echo empty($options['recaptcha_configured']) ? '' : 'checked'; ?>/>
                        <label></label>
                        <p class="description"><?php _e('It helps protect websites from spam and abuse. A “CAPTCHA” is a test to tell human and bots apart. Also make sure to enable Recapctha setting in Form->Configure->General Settings.', 'erforms') ?></p>
                    </div>  
                </div>
                
                <div class="erf-child-rows" style="display:none"> 
                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Site Key', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <input type="text" name="rc_site_key" value="<?php echo $options['rc_site_key']; ?>"/>
                            <p class="description"><?php _e('Mandatory for reCAPTCHA. For more details, <a href="https://www.google.com/recaptcha/intro/index.html">Click Here</a>', 'erforms') ?></p>
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Secret Key', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <input type="text" name="rc_secret_key" value="<?php echo $options['rc_secret_key']; ?>"/>
                            <p class="description"><?php _e('Mandatory for reCAPTCHA. For more details, <a href="https://www.google.com/recaptcha/intro/index.html">Click Here</a>', 'erforms') ?></p>
                        </div>  
                    </div>
                </div>
                
                 <?php do_action('erf_settings_general',$options,$tab); ?> 
            </div>
            
            <div style="<?php echo $tab=='user_login' ? '' : 'display:none' ?>">
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('Login Shortcode', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <textarea name="social_login"><?php echo $options['social_login']; ?></textarea>
                        <p class="description"><?php _e("Place any content (including shortcodes) after login button. Useful in case you want to integrate any other plugin's functionality with ERF Login.", 'erforms'); ?></p>
                    </div>  
                </div>
                
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('After Login Redirect To', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <input type="url" name="after_login_redirect_url" value="<?php echo $options['after_login_redirect_url'] ?>" />
                        <p class="description"><?php _e('URL of the page where user will be redirected after login to WordPress. This value will be overriden in case below role based redirection is enabled and configured.', 'erforms') ?></p>
                    </div>  
                </div>
                
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('Enable Role Based Login Redirection', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control erf-has-child">
                        <input class="erf_toggle" type="checkbox" name="en_role_redirection" value="1" <?php echo empty($options['en_role_redirection']) ? '' : 'checked'; ?>/>
                        <label></label>
                        <p class="description"><?php _e('Enable login redirection per role.', 'erforms') ?></p>
                    </div>  
                </div>
                
                <div class="erf-child-rows">
                    <?php $roles = get_editable_roles(); ?>
                    <?php foreach ($roles as $key => $role): ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php echo $role['name']; ?></label>
                            </div>
                            <div class="erf-control">
                                <input type="text" value="<?php echo $options[$key . '_login_redirection'] ?>" name="<?php echo $key ?>_login_redirection" />
                                <p class="description"><?php echo $role['name'] . __(' will be redirected to this URL after login.', 'erforms') ?></p>
                            </div>  
                        </div>               
                    <?php endforeach; ?>
                </div>
                 <?php do_action('erf_settings_user_login',$options,$tab); ?> 
            </div>
            
            <div style="<?php echo $tab=='external' ? '' : 'display:none' ?>">
                <div class="erf-row <?php echo erforms_is_woocommerce_activated() ? '' : 'erf-disabled'; ?>">
                    <div class="erf-control-label">
                        <label><?php _e('WooCommerce My Account Integration', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <input class="erf_toggle" type="checkbox" name="en_wc_my_account" value="1" <?php echo empty($options['en_wc_my_account']) ? '' : 'checked'; ?>/>
                        <label></label>
                        <p class="description"><?php _e('Adds a <b>Submissions</b> link in WooCommerce <b>My Account</b> area. Please resave Permalinks from WordPress settings.', 'erforms') ?></p>
                    </div>  
                </div>
                
                <?php do_action('erf_settings_external',$options); ?> 
            </div>
            
            <?php do_action('erf_global_settings',$options,$tab); ?> 
            
            <div style="<?php echo $tab=='payments' ? '' : 'display:none' ?>">
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('Currency', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <?php $currencies = erforms_currencies(); ?>
                        <select name="currency">
                            <?php foreach ($currencies as $code => $name): ?>
                                <?php if ($options['currency'] == $code): ?>
                                    <option selected value="<?php echo $code; ?>"><?php echo $name . erforms_currency_symbol($code); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $code; ?>"><?php echo $name . erforms_currency_symbol($code); ?></option>
                                <?php endif; ?>    
                            <?php endforeach; ?>
                        </select>    
                    </div>  
                </div>
                
                <?php do_action('erf_settings_payment',$options); ?>
            </div>
            
            <div style="<?php echo $tab=='developer' ? '' : 'display:none' ?>">
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('JavaScript Libraries', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control erf-cb-list ">
                        <input name="js_libraries[]" <?php echo in_array('jquery', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="jquery"><?php _e('jQuery', 'erforms') ?>
                        <input name="js_libraries[]" <?php echo in_array('masking', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="masking"><?php _e('Masking', 'erforms') ?>
                        <input name="js_libraries[]" <?php echo in_array('font_awesome', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="font_awesome"><?php _e('Font Awesome', 'erforms') ?>
                        <input name="js_libraries[]" <?php echo in_array('parsley', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="parsley"><?php _e('Validation', 'erforms') ?>
                        <input name="js_libraries[]" <?php echo in_array('recaptcha', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="recaptcha"><?php _e('reCaptcha', 'erforms') ?>
                        <input name="js_libraries[]" <?php echo in_array('jquery_ui', $options['js_libraries']) ? 'checked' : '' ?> type="checkbox" value="jquery_ui"><?php _e('jQuery UI', 'erforms') ?>
                        <p class="description"><?php _e('Note: Only for Advance Users. Helpful in detecting javascript conflicts. ', 'erforms'); ?></p>
                    </div>  
                </div>
                
                <?php do_action('erf_settings_developer',$options); ?>
            </div>
            


            <input type="hidden" name="erf_save_settings" />
            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php _e('Save', 'erforms'); ?>" name="save" /> 
                <input type="submit" class="button button-primary" value="<?php _e('Save & Close', 'erforms'); ?>" name="savec" /> 
            </p>
        </fieldset>
    </form>



</div>
