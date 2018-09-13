<?php 
      // Enqueue scripts  
      wp_deregister_script('yoast-seo-babel-polyfill');  // Removing Yoast SEO polyfill.
      wp_deregister_script('yoast-seo-react-dependencies');
      
      wp_enqueue_script('erf-form-builder');  
      wp_enqueue_script('erf-form-builder-render');
      wp_enqueue_script('erf-font-awesome-icon-picker');
      wp_enqueue_script('erf-masked-input');
      
      // Enqueue Styles
      wp_enqueue_style('erf-font-awesome-icon-picker');
      wp_enqueue_style('erf-builder-style');
?>
<div class="erf-form-builder-wrapper">
    <input type="text" value="<?php echo $form['title']; ?>" id="erforms-form-title" placeholder="<?php echo __('Form Name', 'erforms'); ?>" />
    <div class="erf-feature-request">
        <p class="description erf-form-code"><?php _e('To embed this form on your site, please paste the following shortcode inside a post or page.', 'erforms'); ?> <input id="form-code" title="Click to copy Shortcode" type="text" readonly value='[erforms id="<?php echo $form_id; ?>"]'> <span style="display:none" id="copy-message"><?php _e('Copied to Clipboard','erforms'); ?></span></p>
    </div>

    <div id="erforms_fb_editor">
        <div id="erf_progress">
            <div class="loader"></div>
        </div>
    </div>
    <p class="submit">
        <input type="button" value="<?php _e('Save', 'erforms'); ?>" class="button button-primary" id="erforms-form-save-btn" /> 
        <input type="button" value="<?php _e('Save & Close', 'erforms'); ?>" class="button button-primary" id="erforms-form-save-close-btn" />
    </p>
</div>

<div id="erf_rich_text_editor" title="<?php _e('Rich Text Editor','erforms'); ?>" style="display: none;">
    <textarea id="erf_editor"></textarea>
</div>