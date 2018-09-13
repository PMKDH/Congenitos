<?php

/*
 * System wide utility hooks
 */
class ERForms_Do_Actions{
    
    public function __construct(){
        add_action('plugins_loaded',array($this, 'load_tasks'));
        add_action('erforms_before_login_button',array($this,'before_login_button'));
        /* Submission property formatters*/
        add_filter('erforms_address_country_formatter_html',array($this,'country_formatter'),10,3);
        add_filter('erforms_address_state_formatter_html',array($this,'state_formatter'),10,3);
        add_filter('erforms_address_country_formatter_csv',array($this,'country_formatter'),10,3);
        add_filter('erforms_address_state_formatter_csv',array($this,'state_formatter'),10,3);
        add_action('wp_ajax_erforms_form_submission_data',array($this,'form_submission_data_ajax'));
        add_action('wp_ajax_nopriv_erforms_form_submission_data',array($this,'form_submission_data_ajax'));
        add_action('wp_ajax_erforms_get_form_for_edit', array($this,'get_form_for_edit_ajax'));
        add_action('wp_ajax_erforms_delete_submission', array($this,'delete_submission_ajax'));
    }
    
    public function country_formatter($country_code,$field_name,$submission){
        if(empty($country_code))
            return $country_code;
        
        $countries= erforms_address_country();
        if(!empty($countries[$country_code])){
            return $countries[$country_code];
        }
        return $country_code;
    }
    
    // Returns state name on the basis of previous country field
    public function state_formatter($state_code,$field_name,$sub_id){
        if(empty($state_code) || empty($field_name) || empty($sub_id))
            return $state_code;
        
        $submission= erforms()->submission->get_submission($sub_id); // Load submission data
        $country_code='';
        foreach($submission['fields_data'] as $field){
            if(!empty($field['f_entity']) && $field['f_entity']=='address' && !empty($field['f_entity_property']) && $field['f_entity_property']=='country'){
                $country_code= $field['f_val'];
            }
            else if(!empty($field['f_entity']) && $field['f_entity']=='address' && !empty($field['f_entity_property']) && $field['f_entity_property']=='state'){
                if($field['f_name']==$field_name){
                    if(empty($country_code)) // No country field found
                        return $state_code;
                    break;
                }
                else
                {
                    $country_code='';
                }
            }
        }
        
        if(empty($country_code))
            return $state_code;
        
        $states= erforms_load_country_states($country_code);
        if(!empty($states) && !empty($states[$country_code][$state_code]))
            return $states[$country_code][$state_code];
        return $state_code;
    }
    
    public function before_login_button(){
       $options= erforms()->options->get_options();
       $output= do_shortcode($options['social_login']);
       if(!empty($output))
           echo $output;
    }
    
    /*
     * Returns Form data along with submission data (If valid submission ID passed)
     */
    public function form_submission_data_ajax(){
          $form_id= absint($_POST['form_id']);
          $submission_id= absint($_POST['submission_id']);
          $form= erforms()->form->get_form($form_id);
          $submission= erforms()->submission->get_submission($submission_id);
          
          if(empty($form)){
             wp_send_json_error();
          }
          
          if(!empty($submission)){
              if(erforms_edit_permission($form,$submission)){
                $form_attr_data= array('conditions'=>array(),'fields'=>$form['fields'],'id'=>$form['id'],'en_edit_sub'=>$form['en_edit_sub'],'edit_fields'=>$form['edit_fields']); 
                if(isset($form['conditions'])){
                    $form_attr_data['conditions']= $form['conditions'];
                }
                wp_send_json_success(array('submission'=>$submission,'form'=>$form));
              }
          }
          else
          {
               // Sending only required data
               $form_attr_data= array('conditions'=>array(),'fields'=>$form['fields'],'id'=>$form['id'],'en_edit_sub'=>$form['en_edit_sub'],'edit_fields'=>$form['edit_fields']);
               if(isset($form['conditions'])){
                    $form_attr_data['conditions']= $form['conditions'];
               }
               wp_send_json_success(array('form'=>$form_attr_data));
          }
          
          wp_send_json_error();
    } 
    
    public function get_form_for_edit_ajax(){
          $form_id= absint($_POST['form_id']);
          $submission_id= absint($_POST['submission_id']);
          $form= erforms()->form->get_form($form_id);
          $submission= erforms()->submission->get_submission($submission_id);
          
          if(empty($form) || empty($submission)){
             wp_send_json_error();
          }
          
          if(erforms_edit_permission($form,$submission)){
              $response= array(); 
              $html= '<div class="erf-reg-form-container">'.
                        '<div class="erf-success"></div>'.  
                        '<form method="post" enctype="multipart/form-data" class="erf-form erf-front-form" data-parsley-validate="" novalidate="true" autocomplete="off" data-erf-submission-id="'.$submission['id'].'" data-erf-form-id="'.$form['id'].'">'.
                        '<div class="erf-errors"></div>'.
                        '<div class="erf-form-html" id="erf_form_'.$form_id.'">'.$form['form_html'].'</div>'.
                        '<div class="erf-submit-button clearfix"></div>'.
                        '<div class="erf-form-nav clearfix"></div>'. 
                        '<input type="hidden" name="erform_id" value="'.$form_id.'" />'.
                        '<input type="hidden" name="erform_submission_nonce" value="'.wp_create_nonce('erform_submission_nonce')    .'" />'.
                        '<input type="hidden" name="action" value="erf_submit_form" />';
             if($form['type']=='reg'){
                 $html.='<input type="hidden" name="redirect_to" id="erform_redirect_to" />';
                 $html .= '<input type="hidden" name="erf_user" value="'.get_current_user_id().'" />';
             } 
                      
                            
              $html.= '</form></div></div>';                
              $response['form_html']= $html;
              $response['submission']= $submission;
              wp_send_json_success($response);
          }

          wp_send_json_error(array('error'=>__('You not not allowed to edit this submission','erforms')));
         
      }
      
      public function load_tasks(){
          // Update plugin version into database.
          $existing_version= get_site_option('erforms_version');
          if(!empty($existing_version)){
              $current_version= erforms()->version;
              if(version_compare($existing_version,$current_version,'<')){
                  update_site_option('erforms_version',erforms()->version);
              }
          }
          
      }
      
      public function delete_submission_ajax(){
        $form_id= absint($_POST['form_id']);
        $submission_id= absint($_POST['submission_id']);
        $form= erforms()->form->get_form($form_id);
        $submission= erforms()->submission->get_submission($submission_id);
          
        if(empty($form) || empty($submission)){
             wp_send_json_error(array('msg'=>__('Operation not allowed','erforms')));
        }
          
        if(erforms_edit_permission($form,$submission)){
            // Now check if deletion is allowed
            if(empty($form['allow_sub_deletion'])){
                wp_send_json_error(array('msg'=>__('Operation not allowed','erforms')));
            }
            erforms()->submission->delete(array($submission_id));
            $user= wp_get_current_user();
            wp_schedule_single_event(time() + 100,'erf_submission_deleted',array($form,$submission,$user));
            wp_send_json_success();
        }
        wp_send_json_error(array('msg'=>__('Operation not allowed','erforms')));
      }
    
}

new ERForms_Do_Actions();