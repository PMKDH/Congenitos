<div class="erf-row">
    <div class="erf-control-label">
        <label><?php _e('Configure Offline', 'erforms'); ?></label>
    </div>
    <div class="erf-control erf-has-child">
        <input class="erf_toggle" type="checkbox" data-has-child="1" name="payment_methods[]" value="offline" <?php echo in_array('offline', $options['payment_methods']) ? 'checked' : ''; ?>/>
        <label></label>
        <p class="description"><?php _e('It allow merchants to track payments made via cash, checks, bank transfers, at the desk, postal orders, or any other means besides online payment methods such as cards, PayPal, etc. Once you have received the payment, you will have to manually record it', 'erforms') ?></p>
    </div>  
</div>

<div class="erf-child-rows">
    <div class="erf-row">
        <div class="erf-control-label">
            <label><?php _e('Send Email', 'erforms'); ?></label>
        </div>
        <div class="erf-control erf-has-child">
            <input class="erf_toggle" type="checkbox" data-has-child="1" name="send_offline_email" value="1" <?php echo $options['send_offline_email'] == 1 ? 'checked' : ''; ?>/>
            <label></label>
            <p class="description"><?php _e('Sends email to users to let them know about the payment procedure.', 'erforms') ?></p>
        </div>  
    </div>

    <div class="erf-row">
        <div class="erf-control-label">
            <label><?php _e('From Email', 'erforms'); ?></label>
        </div>
        <div class="erf-control">
            <input type="text" value="<?php echo $options['offline_email_from']; ?>" name="offline_email_from" />
            <p class='description'><?php _e('This displays who the message is from, It is recommened to use Domain email address.', 'erforms'); ?></p>
        </div>  
    </div>

    <div class="erf-row">
        <div class="erf-control-label">
            <label><?php _e('From Name', 'erforms'); ?></label>
        </div>
        <div class="erf-control">
            <input type="text" value="<?php echo $options['offline_email_from_name']; ?>" name="offline_email_from_name" />
            <p class='description'><?php _e('When used together with the \'From Email\', it creates a from address like Name "&ltemail@address.com&gt"', 'erforms'); ?></p>
        </div>  
    </div>

    <div class="erf-row">
        <div class="erf-control-label">
            <label><?php _e('Subject', 'erforms'); ?></label>
        </div>
        <div class="erf-control">
            <input type="text" value="<?php echo $options['offline_email_subject']; ?>" name="offline_email_subject" /><br>
            <p class="description"><?php _e('Subject of the mail sent to the user.', 'erforms'); ?></p>
        </div>  
    </div>
    
    <div>
        <div class="erf-row">
            <div class="erf-control-label">
                <label><?php _e('Message', 'erforms'); ?></label>
            </div>
            <div class="erf-control">
                <?php
                $editor_id = 'offline_email';
                $settings = array('editor_class' => 'erf-editor');
                wp_editor($options['offline_email'], $editor_id, $settings);
                ?>
            </div>  
        </div>
    </div>
</div> 