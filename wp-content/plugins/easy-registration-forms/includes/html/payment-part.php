<div class="erf-payment-wrapper" style="display:none">
        <?php if($form['plan_type']=='fixed' && !empty($form['fixed_plan_ids'])): ?>
            <div class="fb-select form-group">
                <label class="fb-text-label">Plan<?php echo erforms_currency_symbol($this->options['currency']); ?></label>
                <select <?php echo $form['plan_required']==1 ? 'required' : '' ?> class="form-control erf-price" name="plan_ids[]" <?php echo count($form['fixed_plan_ids'])>1 ? 'multiple' : ''; ?>>
                    <option value=""><?php _e('Choose','erforms')?></option>
                    <?php foreach($form['fixed_plan_ids'] as $id): $plan= $plan_model->get_plan($id); ?>
                        <option value="<?php echo $plan['id']; ?>"><?php echo $plan['name'].' ('.erforms_currency_symbol($this->options['currency'],false).$plan['price'].') '; ?></option>
                    <?php endforeach; ?>
                </select>  
                <div class="erf-price-total">
                    <p>
                        <span class="erf-total-title"><?php _e('Total','erforms'); ?></span> <?php echo erforms_currency_symbol($this->options['currency'],false); ?><span class="erf-total-payment">
                            0
                        </span>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($form['plan_type']=='user' && !empty($form['user_plan_id'])): ?>
            <div class="erf-payment-fields form-group fb-number"><label class="fb-text-label">Plan<?php echo erforms_currency_symbol($this->options['currency']); ?></label><input <?php echo $form['plan_required']==1 ? 'required' : '' ?> type="number" value="0" class="form-control erf-price" name="user_price" /></div>
            <div class="erf-price-total">
                <p><span class="erf-total-title"><?php _e('Total','erforms'); ?></span> <?php echo erforms_currency_symbol($this->options['currency'],false); ?> <span class="erf-total-payment">0</span></p>
            </div>
        <?php endif; ?>  
            
        <div>  
            <?php if(is_array($this->options['payment_methods'])) : ?>
                <?php foreach($this->options['payment_methods'] as $payment_method): ?>
                    <input type="radio" checked name="payment_method" value="<?php echo $payment_method; ?>"/> <?php echo strtoupper($payment_method); ?>
                <?php endforeach; ?>
            <?php endif; ?> 
        </div>
               
</div>  
        
