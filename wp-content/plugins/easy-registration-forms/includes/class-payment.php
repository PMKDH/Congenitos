<?php

class ERForms_Offline_Payment
{   
    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action('erf_settings_payment', array($this,'payment_settings'));
        add_filter('erf_before_save_settings', array($this,'save_settings'));
        add_filter('erf_before_submission_insert', array($this, 'update_submission'),10,3);
        add_filter('erf_default_global_options',array($this,'global_options'));
    }
    
    /*
     * Loads Offline related global settings
     */
    public function payment_settings($options){
        include 'admin/settings/html/payment-settings.php';
    }
    
    /*
     * Saving offline related settings
     */
    public function save_settings($options){
      $options['send_offline_email'] =   empty($_POST['send_offline_email']) ? 0 : 1;
      $options['offline_email'] =   $_POST['offline_email'];
      $options['offline_email_from'] =   sanitize_text_field($_POST['offline_email_from']);
      $options['offline_email_from_name'] =   sanitize_text_field($_POST['offline_email_from_name']);
      $options['offline_email_subject'] =   sanitize_text_field($_POST['offline_email_subject']);
      return $options;
    }
    
    //Update Payment related meta
    public function update_submission($meta,$form_id,$data){
            $form_model= erforms()->form;
            $form= $form_model->get_form($form_id);
            $options_model= erforms()->options;
            $options= $options_model->get_options();
            $plan_model = erforms()->plan;
            
            
            if($form['type']!='reg' || empty($form['plan_enabled']) || empty($form['plan_type']))
                return $meta;
            $plan_id=0;
            $payment_method= isset($data['payment_method']) ? $data['payment_method'] : '';
            if(!in_array($payment_method, $options['payment_methods']))
                    return $meta;
            
            if($form['plan_type']=='user' && !empty($form['user_plan_id'])){
              $meta['erform_amount']= absint($data['user_price']);
              if(empty($meta['amount'])) // Skip if amount is empty
                  return $meta;
              $meta['erform_payment_status']= ERFORMS_PENDING;
              $meta['erform_payment_invoice']= wp_generate_password(10,false,false);
              $plan_id= $form['user_plan_id'];
              $plan= $plan_model->get_plan($plan_id);
              $meta['erform_plan']= $plan;
              $meta['erform_currency']= $options['currency']; 
            }
            else if($form['plan_type']=='fixed' && is_array($form['fixed_plan_ids']) && !empty($form['fixed_plan_ids']))
            {
                $amount= 0;
                $plans= array();
                $plan_ids= $data['plan_ids'];
                if(empty($plan_ids) || !is_array($plan_ids))
                    return $meta;
                foreach($plan_ids as $id){
                    if(in_array($id, $form['fixed_plan_ids'])){
                        $plan= $plan_model->get_plan($id);
                        $plans[]= $plan;
                        $amount += absint($plan['price']);
                    }
                }
               $meta['erform_amount']= $amount;
               $meta['erform_payment_invoice']= wp_generate_password(10,false,false);
               $meta['erform_payment_status']= ERFORMS_PENDING; 
               $meta['erform_plan']= $plans;
               $meta['erform_currency']= $options['currency'];
            }
            
            $meta['erform_payment_method']= $payment_method;
            return $meta;
        }
        
        public function global_options($options){
            $options['offline_email']='';
            $options['offline_email_from']='';
            $options['offline_email_from_name']='';
            $options['offline_email_subject']='';
            return $options;
        }
}

new ERForms_Offline_Payment;