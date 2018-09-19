<div class="erf-payment_info">
    <table class="erf-submission-table striped wp-list-table fixed widefat">
        <tbody>
            <tr>
                <th colspan="2" class="erf-submission-title">
                    <?php _e('Payment via','erforms'); ?> : 
                    <?php echo erforms_payment_method_title($submission['payment_method']); ?>
                </th>
            </tr>
            <tr>
                <th><?php _e('Amount', 'erforms'); ?></th>
                <td><?php echo erforms_currency_symbol($submission['currency'], false) . $submission['amount']; ?></td>
            </tr>
           <tr>
                <th><?php _e('Payment Status', 'erforms'); ?></th>
                <td><a href="javascript:void(0)" id="erf_payment_change_status"><?php echo ucwords($submission['payment_status']); ?></a></td>
            </tr>
    
            <tr>
                <th><?php _e('Payment Invoice', 'erforms'); ?></th>
                <td><?php echo $submission['payment_invoice']; ?></td>
            </tr>
        
            <?php if (is_array($submission['plan']) && !isset($submission['plan']['name']) && is_array($submission['plan'])) : ?>
                    <?php $plan_names= array();
                          foreach($submission['plan'] as $plan){
                              $plan_names[]= $plan['name'];
                          } 
                    ?>
                    <tr>
                        <th><?php _e('Plan Name', 'erforms'); ?></th>
                        <td><?php echo implode(', ', $plan_names); ?></td>
                    </tr>
            <?php else: ?>
                <tr>
                    <th><?php _e('Plan Name', 'erforms'); ?></th>
                    <td><?php echo $submission['plan']['name']; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Change Payment Status Dialog -->
<div id="erf_payment_status_dialog" style="display:none" title="<?php _e('Change Status','erforms'); ?>">
    <form method="POST">
        <div class="erf-row">
            <div class="erf-control-label">
                <label><?php _e('Status', 'erforms'); ?></label>
                <select name="payment_status">
                    <?php $status_list= erforms_status_options(); ?>
                    <?php foreach($status_list as $status) : ?>
                            <option <?php echo $submission['payment_status']==$status ? 'selected' : '' ?> value="<?php echo $status; ?>"><?php echo ucwords($status); ?></option>
                    <?php endforeach; ?>
                </select>
            </div> 
        </div>

        <div class="erf-row">
            <div class="erf-control-label erf-control erf-has-child">
                <label><input type="checkbox" data-has-child="1" name="notify_user" value="1" /> <?php _e('Notify User', 'erforms'); ?></label>
            </div>  
        </div>

        <div class="erf-child-rows erf-row">
            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Message', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <textarea rows="5" name="notify_email" placeholder="<?php _e('Description goes here','erforms') ?>"></textarea>
                </div>  
            </div>
        </div>

        <div class="erf-row">
            <div class="erf-control-label erf-control erf-has-child">
                <label><input type="checkbox" data-has-child="1" name="add_note" value="1" /> <?php _e('Add Internal Note', 'erforms'); ?></label>
            </div>
            <div class="">
                
            </div>  
        </div>

        <div class="erf-child-rows erf-row">
            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Note Text', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <textarea rows="5" name="note_text"></textarea>
                </div>  
            </div>
        </div>
        <input type="hidden" name="erf_change_payment_status"/>
    </form>
</div>  
