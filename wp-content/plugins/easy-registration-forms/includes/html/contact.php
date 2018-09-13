<?php 
        $captcha_enabled= $form['recaptcha_enabled'];
        $label_position= $form['label_position'];
        $layout= $form['layout'];
        $sub_id= isset($_GET['sub_id']) ? absint($_GET['sub_id']) : 0;
?>
<div class="erf-container erf-contact erf-label-<?php echo $label_position ?> erf-layout-<?php echo $layout ?>">
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
                echo $error[1].'<br>';
            }
            return;
        }

        if(!empty($form['allow_only_registered']) && !is_user_logged_in()){
            _e('Only authenticated users are allowed to submit the form. If you are already registered, Please login and try again.','erforms');
            return;
        }
    ?> 
        <div class="erf-content-above">
            <?php echo $form['before_form']; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="erf-form erf-front-form" data-parsley-validate="" novalidate="true" data-erf-submission-id="<?php echo $sub_id; ?>" data-erf-form-id="<?php echo $form['id'] ?>">
            <div class="erf-errors">
                <?php foreach($this->errors as $error) : ?>
                        <div class='erf-error-row'><?php echo $error[1]; ?></div>
                <?php endforeach; ?>    
            </div>
            <div class="erf-form-html" id="erf_form_<?php echo $id; ?>">
                <?php echo do_shortcode($form_html); ?>
            </div>    
            <?php do_action('erf_before_submit_btn',$form); ?>
            
            <!-- Opt in checkbox -->
            <?php if(!empty($form['opt_in']) && empty($sub_id) && erforms_show_opt_in()): ?>
                <div class="form-group">
                  <input type='checkbox' name='opt_in' <?php echo !empty($form['opt_default_state']) ? 'checked': ''; ?> />
                  <?php echo $form['opt_text']; ?>
                </div>
            <?php endif; ?>
            <!-- Opt in ends here -->
            
            <!-- Show reCaptcha if configured -->
            <?php if(!is_user_logged_in() &&  !empty($this->options['recaptcha_configured']) && !empty($this->options['rc_site_key']) && $captcha_enabled) : ?>
                <div class="g-recaptcha erf-recaptcha clearfix" data-sitekey="<?php echo $this->options['rc_site_key']; ?>"></div>
            <?php endif; ?>
            <!-- reCaptcha ends here -->    
            
            <!-- Contains multipage Next,Previous buttons -->
            <div class="erf-form-nav clearfix"></div> 
            
            <!-- Single page form button -->
            <div class="erf-submit-button clearfix"></div>
            
            <input type="hidden" name="erform_id" value="<?php echo $form['id']; ?>" />
            <input type="hidden" name="erform_submission_nonce" value="<?php echo wp_create_nonce('erform_submission_nonce'); ?>" />
            <input type="hidden" name="action" value="erf_submit_form" />
            <?php do_action('erforms_form_end',$form); ?>
        </form>
    <?php endif; ?>
    
</div>
