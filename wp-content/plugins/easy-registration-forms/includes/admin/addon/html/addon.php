<div class="wrap about-wrap full-width-layout erf-addons">
    <h1><?php _e('Easy Registration Form Add-on', 'erforms'); ?></h1>
    <div class="erf-add-on-wrap">
        
        <div class="erf-add-on">
            <a href="http://www.easyregistrationforms.com/product/conditional-field-extension/" target="_blank">
            <div class="add-on-img">
                        
                <img src="<?php echo ERFORMS_PLUGIN_URL.'/assets/admin/images/addons/conditional-logics.png' ?>">
            </div>
            <h3><?php _e('Conditional Logics', 'erforms'); ?></h3>
            <p><?php _e('Conditional Logic extension allows you to show/hide fields on the basis of desired conditions in addition mail can be sent to various recipients on the basis of selected.', 'erforms'); ?></p>
            </a>
        </div>
        
        <div class="erf-add-on">
            <a href="http://www.easyregistrationforms.com/product/mailchimp-extension/">
            <div class="add-on-img">
                <img src="<?php echo ERFORMS_PLUGIN_URL.'/assets/admin/images/addons/mail-chimp.png' ?>">
            </div>
            <h3><?php _e('Mailchimp Integration', 'erforms'); ?></h3>
            <p><?php _e('Create MailChimp signup forms in WordPress to grow your email list.', 'erforms'); ?></p>
            </a>
        </div>
        
        
        <div class="erf-add-on">
            <a href="#">
            <div class="add-on-img">
                <img src="<?php echo ERFORMS_PLUGIN_URL.'/assets/admin/images/addons/paypal-coming-soon.png' ?>">
            </div>
            <h3><?php _e('PayPal Integration', 'erforms'); ?></h3>
            <p><?php _e('Allows user to pay through PayPal', 'erforms'); ?></p>
            </a>
        </div>
        
    </div>

    
    
    <div class="erf-feature-request">
        <h4><?php _e('Have a feature in mind, share with us ', 'erforms'); ?><a href="http://www.easyregistrationforms.com/support/" target="_blank"><?php _e('here', 'erforms'); ?></a></h4>
    </div>
</div>

<style>
    .erf-add-on-wrap{
        display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: flex-start;
	align-items: stretch;
	align-content: center;
        margin-top: 50px;
    }
    .erf-add-on-wrap .erf-add-on{
        width: 25%;
        max-width: 300px;
        text-align: center;
        padding: 0 20px;
        margin-bottom: 30px;
        box-sizing: border-box;
    }
    .erf-add-on-wrap a{
        text-decoration: none;
        color: inherit;
    }
    .erf-feature-request h4{
        text-align: center;
    }
    @media all and (max-width: 1200px) {}
    @media all and (max-width: 979px) {}
    @media all and (max-width: 767px) {
        .erf-add-on-wrap .erf-add-on{
            width: 50%;
            max-width: 300px;
        }
    }
    @media all and (max-width: 479px) {}

</style>
