<div id="erforms-overview" class="wrap erforms-admin-wrap erforms-overview erf-wrapper-bg">
    <?php
    $form_cards = new ERForms_Form_Cards;
    $form_cards->prepare_items();
    ?>
    <?php if(isset($options['consent_allowed']) && $options['consent_allowed']==2): ?>
    <div class="updated settings-error notice is-dismissible">
        <form method="post">
            <p>
                In order for us to better serve you, allow us to track usage of this plugin.
                &nbsp;<input type="submit" name="erf_consent_allow" value="Allow" class="button action"/>
                <input type="submit" name="erf_consent_disallow" value="Disallow" class="button action"/>
            </p>
        </form>
    </div>
    <?php endif; ?>
    <form id="erforms-overview-table" method="get" action="<?php echo admin_url('admin.php?page=erforms-overview'); ?>">
        <div class="erf-page-title">
            <h1 class="wp-heading-inline">
                <?php _e('Forms Overview', 'erforms'); ?>
            </h1>
            <div class="erf-page-menu">
                <ul class="erf-nav clearfix">
                    <li class="erf-nav-item"><a href="javascript:void(0)" id="erf_overview_add_form"><?php _e('Add New', 'erforms'); ?></a></li>
                </ul>
                <div class="erf-search-form">
                    <?php $search = isset($_GET['filter_key']) ? sanitize_text_field(urldecode($_GET['filter_key'])) : ''; ?>
                    <label><?php _e('Search','erforms'); ?> <input type="text" value="<?php echo $search; ?>" name="filter_key" placeholder="<?php _e('Form Name','erforms'); ?>"></label>
                </div>
            </div>
        </div>

        <div class="erforms-admin-content">
                <input type="hidden" name="page" value="erforms-overview" />
                <div class="erf-card-wrap">
                    <?php $form_cards->views(); ?>
                    <?php $form_cards->display(); ?>
                </div>

        </div>
        <input type="submit" style="display:none"/>
    </form>    

</div>

<div id="erf_overview_add_form_dialog" title="Add New Form" style="display: none;">
    <?php _e('NAME OF YOUR FORM','erforms'); ?> <input type="text" id="erf_overview_input_form_name" placeholder="Form Here"/>
    <div id="erf_overview_add_form_response">&nbsp;</div>
    <div class="erf-ajax-progress" style="display:none"></div>

    <div class="erf-choose-form-type">
        
        <div class="erf-form-type">
            <div class="erf-form-type-head">
                <input value="reg" type="radio" name="erf_overview_input_form_type" id="registration-form" checked/>
                <label for="registration-form"><?php _e('Registration Form','erforms'); ?></label> 
            </div>
        </div>
        
        <?php do_action('erf_form_type'); ?>
        
        <div class="erf-form-type">
            <div class="erf-form-type-head">
                <input type="radio" name="erf_overview_input_form_type" value="contact" id="contact-form"/>
                <label for="contact-form"><?php _e('Contact/Other Form','erforms'); ?></label> 
            </div>
        </div>
        
        
    </div>

</div>

<div id="erf_overview_delete_form_dialog" title="<?php _e('Are you sure you want to delete?','erforms'); ?>" style="display: none;">
    <?php _e('Deletion of form will remove all the related submissions.','erforms'); ?>
</div>
