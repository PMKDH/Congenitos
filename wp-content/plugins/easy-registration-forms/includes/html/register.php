<?php 
        $captcha_enabled= $form['recaptcha_enabled'];
        $label_position= $form['label_position'];
        $layout= $form['layout'];
        $hide_form= false;
        $plan_model= erforms()->plan;
        $sub_id= isset($_GET['sub_id']) ? absint($_GET['sub_id']) : 0;
?>
<div class="erf-container erf-label-<?php echo $label_position ?> erf-layout-<?php echo $layout ?> erf-style-<?php echo $form['field_style']; ?>">
    <?php
        $hide_form= false;
        if(!is_user_logged_in() && !empty($form['enable_login_form'])){
            echo do_shortcode('[erforms_login show_register_form=1]');
            $hide_form= true;
        }
    ?>
    
    <div class="erf-reg-form-container" style="<?php echo ($hide_form && empty($this->errors)) ? 'display:none': ''; ?>">
        <?php include('layout_options.php'); ?>
        <?php if($success) : ?>
            <div class="erf-success">
                <?php echo $form['success_msg']; ?>
            </div> 
        <?php else: ?>

        <?php // Before output hook.
            $errors= apply_filters('erforms_before_form_processing',array(),$form);
            if(!empty($errors) && is_array($errors)){
                foreach($errors as $error){
                    echo '<div class="erf-error-row erf-error form-group">'.$error[1].'</div>';
                }
                return;
            }


            if(empty($form['allow_re_register']) && is_user_logged_in()){
                _e('You are not allowed to submit the form as you are already registered.','erforms');
                return;
            }
        ?> 
        <div class="erf-content-above">
                <?php echo $form['before_form']; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="erf-form erf-front-form" data-parsley-validate="" novalidate="true" autocomplete="off" data-erf-submission-id="<?php echo $sub_id; ?>" data-erf-form-id="<?php echo $form['id'] ?>">

            <div class="erf-errors">
                <?php foreach($this->errors as $error) : ?>
                        <div class='erf-error-row'><?php echo $error[1]; ?></div>
                <?php endforeach; ?>    
            </div>




            <div class="erf-form-html" id="erf_form_<?php echo $id; ?>">
                <?php echo do_shortcode($form_html); ?>
            </div>   
            
            <div class="erf-external-form-elements">
                <?php do_action('erf_before_submit_btn',$form); ?>

                <?php 
                    if(!empty($form['plan_enabled']) && !empty($this->options['payment_methods']) && empty($sub_id)){
                     include('payment-part.php'); 
                    }
                ?>

                <!-- Opt in checkbox -->
                <?php if(!empty($form['opt_in']) && empty($sub_id) && erforms_show_opt_in()): ?>
                    <div class="form-group">
                      <input type='checkbox' value="1" name='opt_in' <?php echo !empty($form['opt_default_state']) ? 'checked': ''; ?> />
                      <?php echo $form['opt_text']; ?>
                    </div>
                <?php endif; ?>
                <!-- Opt in ends here -->

                <!-- Show reCaptcha if configured -->
                <?php if(!is_user_logged_in() && !empty($this->options['recaptcha_configured']) && !empty($this->options['rc_site_key']) && $captcha_enabled) : ?>
                    <div class="g-recaptcha erf-recaptcha clearfix" data-sitekey="<?php echo $this->options['rc_site_key']; ?>"></div>
                <?php endif; ?>
                <!-- reCaptcha ends here -->
                
                <?php do_action('erforms_form_end',$form); ?> 
            </div>
            <!-- Contains multipage Next,Previous buttons -->
            <div class="erf-form-nav clearfix"></div>  
            
            <!-- Single page form button -->
            <div class="erf-submit-button clearfix"></div>


            

            <input type="hidden" name="erform_id" value="<?php echo $form['id']; ?>" />
            <input type="hidden" name="erform_submission_nonce" value="<?php echo wp_create_nonce('erform_submission_nonce'); ?>" />
            <input type="hidden" name="action" value="erf_submit_form" />
            <input type="hidden" name="redirect_to" id="erform_redirect_to" />

            <?php if(is_user_logged_in()): ?>
                <input type="hidden" name="erf_user" value="<?php echo get_current_user_id();?>" />
            <?php endif; ?>

            <?php if(!is_user_logged_in() && !empty($form['enable_login_form'])) : ?>
                <div class="erf-account-switch erf-clearfix">
                    <a class="erf-show-login" href="javascript:void(0)"><?php _e('Already have an account?','erforms'); ?></a>
                </div>
            <?php endif; ?>

               
        </form>
    <?php endif; ?>
    </div>
</div>




