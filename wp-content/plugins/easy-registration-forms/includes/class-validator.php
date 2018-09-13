<?php

class ERForms_Validator {
    
    public function validate($form,$data) {  
        if(empty($form))
            return array();
        
        $errors= array();
        if(!$this->verify_login_nonce($data['erform_submission_nonce'])){
            $errors[]= array('erf_form_error',__('Security token seems to be incorrect. Please reload the page and try again','erforms'));
        }
        else
        {
            $form= apply_filters('erf_fields_before_validation',$form,$data);
            $errors= $this->validate_data($form['fields'],$data);
        }
        
        if(!empty($errors))
            return $errors;
        $submission_id= isset($data['submission_id']) ? absint($data['submission_id']) : 0;
        if(!empty($submission_id)){ // Submission edit
            if(!erforms_edit_permission($form,$submission_id))// Check for user permissions
            {
                $errors[]= array('edit_submission_permission',__('You are not allowed to edit this submission','erforms'));
            }
            return $errors;
        }
        $errors= $this->validate_payment($form,$data);
        $errors= apply_filters('erf_custom_validation',$errors,$form,$data);
        return $errors;
    }
    
    private function verify_login_nonce($nonce_value){
        return wp_verify_nonce($nonce_value,'erform_submission_nonce');
    }
    
    /*
     * Loop through all the fields to extract validation and type information from database.
     */
    public function validate_data($fields= array(),$data){
        $submission_id= isset($data['submission_id']) ? absint($data['submission_id']) : 0;
        $error_strings= erforms_error_strings();
        $errors= array();
        $form_id= absint($data['erform_id']);
        $form= erforms()->form->get_form($form_id);
        foreach($fields as $field){
            $field= (object) $field;
            
            if(!isset($field->name)) // Skip validation for non input fields
                continue;
            if(is_user_logged_in()){
                if(isset($field->subtype) && ($field->subtype=='user_email' || $field->subtype=='password')){
                    continue;
                }
                 
                $field_array= (array) $field;
                if(erforms_is_username_field($field_array)){
                    continue;
                }
            }
            
            if(isset($field->required)){
                if($field->type=="file"){
                    $edit_submission= erforms()->frontend->edit_sub_status;
                    // Skip file validation in case it is edit submission and file field not enabled for edit.
                    if ($edit_submission && $form['type']=='reg' && !in_array($field->name, $form['edit_fields'])) {
                        continue;
                    }
                    else if(!isset($_FILES[$field->name]) || !ERForms_Validation::is_file_uploaded($field->name)){
                     $errors[]= array($field->name,$field->label.': is not uploaded.');
                    }
                }
                else if(!isset($data[$field->name]) || !ERForms_Validation::required($data[$field->name]))
                    $errors[]= array($field->name,$field->label.': is required field.');
                
            }

            if(!empty($field->user_roles)){ // User roles enabled for a field
               $valid_role_selected= false; 
               if(empty($data[$field->name]))  // If no user role passed with request then mark it as valid.
                   $valid_role_selected= true;
               else {
                   if(is_array($field->values)){
                    foreach($field->values as $role){
                        if($data[$field->name]==$role['value']){
                            $valid_role_selected= true;
                            break;
                        }
                     }
                   }
               }
               
               if(!$valid_role_selected){
                   $errors[]= array('invalid_user_role',$field->label.': invalid role selected ');
               }
               
            }
            if(isset($field->maxlength) && isset($data[$field->name]) && !ERForms_Validation::maxlength($data[$field->name],$field->maxlength)){
                $errors[]= array($field->name,$field->label.': can not be greater than '.$field->maxlength);
            }
            
            if(isset($field->minlength) && isset($data[$field->name]) && !ERForms_Validation::minlength($data[$field->name],$field->minlength)){
               $errors[]= array($field->name,$field->label.': can not be less than '.$field->minlength);
            }
            
            if(isset($field->minlength) && isset($data[$field->name]) && !ERForms_Validation::minlength($data[$field->name],$field->minlength)){
               $errors[]= array($field->name,$field->label.': can not be less than '.$field->minlength);
            }
           
            if($field->type=="date"){
               
                if(isset($field->max) && isset($data[$field->name]) && !ERForms_Validation::maxDate($data[$field->name],$field->max)){
                $errors[]= array($field->name,$field->label.': can not be greater than '.$field->max);
                }
                
                if(isset($field->min) && isset($data[$field->name]) && !ERForms_Validation::minDate($data[$field->name],$field->min)){
                   $errors[]= array($field->name,$field->label.': can not be less than '.$field->min);
                }
            }
            
            
            if(isset($field->enableUnique) && isset($data[$field->name]) && !ERForms_Validation::is_unique($data[$field->name],$field->name,$form_id,$submission_id)){
               $errors[]= array($field->name,$field->label.':'.__('Value already exists in database.','erforms'));
            }
            
            if(isset($data[$field->name]) && method_exists('ERForms_Validation',$field->type)){
                if($field->type=='text'){
                    if(!ERForms_Validation::{$field->type}($field->subtype,$data[$field->name])){
                        if($field->subtype=="user_email"){
                            $errors[]= array($field->name,$field->label.": Invalid email address."); 
                        }
                        else
                        {
                            $errors[]= array($field->name,$field->label.": Invalid value.");
                        }
                    }
                }
                else if(!ERForms_Validation::{$field->type}($data[$field->name])){
                    $error_message= ' Invalid value';
                    if(isset($error_strings[$field->type])){
                        $error_message= $error_strings[$field->type];
                    }
                    $errors[]= array($field->name,$field->label.":".$error_message); 
                }
                
            }
            
            
            if($field->type=="file" && isset($_FILES[$field->name]) && ERForms_Validation::is_file_uploaded($field->name)){
               
                // Validate for file extensions
                if(isset($field->accept)){  
                    $accept= trim($field->accept);
                    if(!empty($accept)){
                        $accept= str_replace(' ',',',$accept); // Replace any space with comma
                        $allowed= explode(',', $accept); 
                        $FILES= array($_FILES[$field->name]);
                        foreach($FILES as $FILE){ 
                            if(!ERForms_Validation::verify_file_type($allowed,$FILE,$field->name))
                                $errors[]= array($field->name,$field->label.": ".$FILE['name']." is not in correct format. Allowed formats are ". implode(',',$allowed).".");
                        }

                    }
                }
            }
        }
        return $errors;
    }
    
    /*
     * Validates Payment Price as per the assigned Plan
     */
    private function validate_payment($form,$data){
        $errors= array();
        $options_model= erforms()->options;
        $options= $options_model->get_options();

        // Check if payment method enabled
        if(empty($options['payment_methods']) || $form['type']!='reg')
        {
            return $errors;
        }
        $plan_model = erforms()->plan;
       
        if(empty($form['plan_enabled']) || empty($form['plan_type']))
            return $errors;
        
        if($form['plan_type']=='user' && !empty($form['user_plan_id'])){
          $amount= absint($data['user_price']);  
          // Check if plan required
          if(!empty($form['plan_required']) && empty($amount))
          {
              $errors[]= array('invalid_amount','Invalid Payment amount.');
              return $errors;
          }
          
          $plan= $plan_model->get_plan($form['user_plan_id']);
          if(empty($plan['id'])){
              $errors[]= array('invalid_plan','No such payment option exists.');
          }
        }
        else if($form['plan_type']=='fixed' && is_array($form['fixed_plan_ids']) && !empty($form['fixed_plan_ids']))
        {
            $amount= 0;
            $plans= array();
            $plan_ids= isset($data['plan_ids']) ? $data['plan_ids'] : array();
           
            if(!empty($form['plan_required'])){ // Plan is required. Check if correct plans have been selected
                if(empty($plan_ids) || !is_array($plan_ids))
                {
                    $errors[]= array('invalid_plan','Payment option not selected.');
                    return $errors;
                }
            }
            if(!is_array($plan_ids))
                $errors[]= array('invalid_plan','Invalid Plan Selected.');
            
            foreach($plan_ids as $id){ // Validate if any non configured plan has been passed in request
                if(!in_array($id, $form['fixed_plan_ids'])){
                   $errors[]= array('invalid_plan','Invalid Plan Selected.');
                   return $errors;
                   break;
                }
            }
            
            foreach($plan_ids as $id){ // Check if any such plan exists in database.
               $plan= $plan_model->get_plan($id);
               if(empty($plan['id'])){
                   $errors[]= array('invalid_plan','No such payment option exists.');
                   return $errors;
               }
            }
        }
        
        /*
         * Verifying payment method
         */
        $payment_method= sanitize_text_field($data['payment_method']);
        if(empty($payment_method)) // No payment method selected
        {
            $errors[]= array('payment_method_not_selected',__('Payment method not selected.','erforms'));
        }

        // Check if payment method is enabled
        if(!in_array($payment_method,$options['payment_methods'])){
             $errors[]= array('invalid_payment_method',__('Selected payment method is not available.','erforms'));
        }
        
        return $errors;
        
    }
    

}
