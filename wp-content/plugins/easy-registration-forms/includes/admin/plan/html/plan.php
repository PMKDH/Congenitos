<div class="erf-wrapper erforms-settings wrap erf-wrapper-bg">
    <div class="erf-page-title">
        <h1><?php _e('Plan', 'erforms'); ?></h1>
        <div class="erf-page-menu">
            <ul class="erf-nav clearfix">

            </ul>        
        </div>
    </div>
    <div class="erforms-new-plan">
        <form action="" method="post">
            <fieldset class="erf-plan-wrap">
                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('Pricing Type', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <select name="type" id="erf_plan_type">
                            <option <?php echo $plan['type']=='fixed' ? 'selected': ''; ?> value="fixed"><?php _e('Fixed','erforms') ?></option>
                            <option <?php echo $plan['type']=='user' ? 'selected': ''; ?> value="user"><?php _e('User Defined','erforms') ?></option>
                        </select>    
                    </div>  
                </div>    

                <div class="erf-row">
                    <div class="erf-control-label">
                        <label><?php _e('Name', 'erforms'); ?></label>
                    </div>
                    <div class="erf-control">
                        <input required="" type="text" name="name" value="<?php echo $plan['name']; ?>" />
                    </div>  
                </div>

                <div class="erf-row erf-plan" id="erf_plan_fixed">
                    <div class="erf-control-label">
                        <label><?php _e('Price', 'erforms'); ?><?php echo erforms_currency_symbol($options['currency']); ?></label>
                    </div>
                    <div class="erf-control">
                        <input type="number" min="0" name="price" value="<?php echo $plan['price']; ?>" />
                    </div>  
                </div>


            </fieldset>

            <p class="submit">
                <input type="hidden" name="erf_save_plan" />
                <input type="hidden" name="id" value="<?php echo $plan['id']; ?>"  /> 
                <input type="submit" class="button button-primary" value="<?php _e('Save', 'erforms'); ?>" name="save" />
                <input type="submit" class="button button-primary" value="<?php _e('Save & Close', 'erforms'); ?>" name="save_close" />
            </p>
        </form>    
    </div>
</div>  


<script>
(function($){
    $('#erf_plan_type').change(function(){
        var element= $(this);
        var childElement= $('#erf_plan_' + element.val());
        $('.erf-plan').hide();
        if(childElement.length>0){
            childElement.slideDown();
        }
        
    });
    
    $('#erf_plan_type').trigger('change');
})(jQuery);
</script>