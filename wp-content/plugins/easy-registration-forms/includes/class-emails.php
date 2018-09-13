<?php
class ERForms_Emails
{   
    private $from= null;
    private $from_name= null;
    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action('erf_async_post_submission', array($this,'post_submission_notification'));
        add_action('erf_async_post_edit_submission', array($this,'post_edit_submission_notification')); // Called after current_time + 10
        add_action('erf_post_submission', array($this,'auto_reply_user')); 
        add_action('erf_post_edit_submission', array($this,'edit_sub_auto_reply_to_user'));
        add_action('erf_async_user_activated',array($this,'async_user_activated')); // Called after current_time + 20
        add_action('erf_user_activated',array($this,'user_activated'));
        add_action('wp_ajax_erf_send_uninstall_feedback',array($this,'send_uninstall_feedback'));
        add_action('erf_send_verification_link',array($this,'send_verification_link'),10,4);
        add_action('erf_submission_deleted',array($this,'send_sub_deletion_notification'),10,3);
    }
    
    /*
     * Notifies admin on submission completion
     */
    public function async_notify_admin($sub_id){
        $submission_model= erforms()->submission;
        $submission= $submission_model->get_submission($sub_id);
        
        $form= erforms()->form->get_form($submission['form_id']);
        if(empty($form['enable_admin_notification']))
            return;
        
        
        $subject= $form['admin_notification_subject'];
        $message= $form['admin_notification_msg'];
        $registration_html= '';
        if (!empty($submission['unique_id'])){
            $registration_html= '<div>'.__('Unique Submission ID', 'erforms').': '.$submission['unique_id'].'</div>';
        }
                     
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            if ($field['f_type'] == 'file' && !empty($field['f_val'])) {
                if (wp_attachment_is_image($field['f_val'])) {
                    $image_attributes = wp_get_attachment_image_src($field['f_val']);
                    $field['f_val']= '<a target="_blank" href="'.wp_get_attachment_url($field['f_val']).'">'.__('View File','erforms').'</a>';
                } else {
                    $url = wp_get_attachment_url($field['f_val']);
                    $field['f_val']='<a target="_blank" href="' . $url . '">'.__('View File','erforms').'</a>';
                }
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $registration_html .= '<div>'.$field['f_label'].': '.$field['f_val'].'</div> <br>';
        }
        
        if(!empty($submission['plan']))
        {
            $registration_html .= '<div>'.__('Amount','erforms').': '.erforms_currency_symbol($submission['currency'], false) . $submission['amount'].'</div> <br>';
            $registration_html .= '<div>'.__('Payment Status','erforms').': '.ucwords($submission['payment_status']).'</div> <br>';
            $registration_html .= '<div>'.__('Payment Invoice','erforms').': '.$submission['payment_invoice'].'</div> <br>';
            $registration_html .= '<div>'.__('Payment Method','erforms').': '.erforms_payment_method_title($submission['payment_method']).'</div> <br>';
        }
        
        if (!empty($submission['unique_id'])){ 
             $registration_html = str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $registration_html);
             $message= str_replace('{{UNIQUE_ID}}', $submission['unique_id'], $message);
        }
        
        $message= str_replace('{{REGISTRATION_DATA}}', $registration_html, $message);
        $to= $form['admin_notification_to'];
        if(empty($to)){
           $to= get_option('admin_email');
        }
        $message= do_shortcode(wpautop($message));
        if(!empty($form['admin_notification_from'])){
             $this->from= $form['admin_notification_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        if(!empty($form['admin_notification_from_name'])){
            $this->from_name= $form['admin_notification_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $message= apply_filters('erf_admin_sub_email',$message,$submission); // Allows to dynamically update the email content
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    /*
     * Send auto reply message to user
     */
    public function auto_reply_user($submission){
        $submission_id= $submission['id']; // Fetching submission entry from database for latest values
        $submission= erforms()->submission->get_submission($submission_id);
        $form= erforms()->form->get_form($submission['form_id']);
        
        if(empty($form['enabled_auto_reply']))
            return;
        
        if($form['type']=='reg'){
            $user= isset($submission['user']) ? $submission['user'] : false;
            if(empty($user))
                return false;
            $to= $user['user_email'];
        }
        else
        {
           if(empty($form['auto_reply_to']))
                return;
            $to= $form['auto_reply_to']; 
        }
        
        $subject= $form['auto_reply_subject'];
        $message= $form['auto_reply_msg'];
        
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $to= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $to);
        }
        
        if (!empty($submission['unique_id'])){
             $message= str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $message);
        }
        
        $message= do_shortcode(wpautop($message));
        if(!empty($form['auto_reply_from'])){
             $this->from= $form['auto_reply_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['auto_reply_from_name'])){
            $this->from_name= $form['auto_reply_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        $message= apply_filters('erf_auto_reply_email',$message,$submission); // Allows to dynamically update the email content
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    public function async_user_activated($user_id){     
        $this->user_activated($user_id);
    }
    
    public function set_html_content_type($content_type) {
	return 'text/html';
    }
    
    public function user_activated($user_id){
        $user_model= erforms()->user;
        $form_id= $user_model->get_meta($user_id,'form');
        if(empty($form_id))
            return false;
        
        $form= erforms()->form->get_form($form_id);
        if(empty($form['enable_act_notification']))
            return false;
        $user= $user_model->get_user($user_id);
        $subject= $form['user_act_subject'];
        $message= $form['user_act_msg'];
        $to= $user->user_email;
        $message= do_shortcode(wpautop($message));
        if(!empty($form['user_act_from'])){
             $this->from= $form['user_act_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['user_act_from_name'])){
            $this->from_name= $form['user_act_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        $message= apply_filters('erf_user_activated_email',$message,$user_id); // Allows to dynamically update the email content
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    public function report_issue($name,$email,$subject,$message){
        $subject= $subject;
        $message= $message;
        $message.= ' <br> From: '.$email;
        $to= 'erformswp@gmail.com';
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
    }
    
    public function quick_email($to,$subject,$message,$from='',$from_name=''){
        $message= do_shortcode(wpautop($message));
        if(!empty($from)){
            $this->from= $from;
            add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($from_name)){
            $this->from_name= $from_name;
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        if(!empty($this->from)){
            $this->from= null;
            remove_filter('wp_mail_from',array($this,'set_email_from'));
        }
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    public function offline_payment_notification($sub_id){
        $options= erforms()->options->get_options();
        $submission_model= erforms()->submission;
        $submission= $submission_model->get_submission($sub_id);
        if(empty($options['send_offline_email']) || empty($options['offline_email']) || empty($submission['payment_method'])){
            return;
        }
        
        if($submission['payment_method']!='offline'){
            return;
        }
        
        $user= isset($submission['user']) ? $submission['user'] : false;
        if(empty($user))
            return;
        
        $form= erforms()->form->get_form($submission['form_id']);
        $to= $user['user_email'];
        if(!empty($options['offline_email_subject'])){
            $subject= $options['offline_email_subject'];
        }
        else{
            $subject= $form['name'].' '.__('Notification','erforms');
        }
        
        $message= $options['offline_email'];
        $message= do_shortcode(wpautop($message));
        
        $message= apply_filters('erf_offline_email',$message,$submission); // Allows to dynamically update the email content
        
        if(!empty($options['offline_email_from'])){
             $this->from= $options['offline_email_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($options['offline_email_from_name'])){
            $this->from_name= $options['offline_email_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));   
        remove_filter( 'wp_mail_from', array($this,'set_email_from'));
        remove_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        
    }
    
    public function post_submission_notification($sub_id){
        $this->async_notify_admin($sub_id);
        $this->offline_payment_notification($sub_id);
    }
    
    /* Responds user after edit submission */
    public function edit_sub_auto_reply_to_user($submission){
        $submission_id= $submission['id']; // Fetching submission entry from database for latest values
        $submission= erforms()->submission->get_submission($submission_id);
        $form= erforms()->form->get_form($submission['form_id']);
   
        if(empty($form['enable_edit_notifications']))
            return;
        
        $user= isset($submission['user']) ? $submission['user'] : false;
        if(empty($user))
            return false;
        $subject= $form['edit_sub_user_subject'];
        $message= $form['edit_sub_user_email'];
        
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
        }
        
        if (!empty($submission['unique_id'])){
             $message= str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $message);
        }
        
        $to= $user['user_email'];
        $message= do_shortcode(wpautop($message));
        if(!empty($form['edit_sub_user_from'])){
             $this->from= $form['edit_sub_user_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['edit_sub_user_from_name'])){
            $this->from_name= $form['edit_sub_user_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        $message= apply_filters('erf_edit_sub_auto_reply_email',$message,$submission); // Allows to dynamically update the email content
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    
    }
    
    /*Notify admin after edit submission */
    public function notify_edit_sub_to_admin($sub_id){
        $submission_model= erforms()->submission;
        $submission= $submission_model->get_submission($sub_id);
        
        $form= erforms()->form->get_form($submission['form_id']);
        if(empty($form['enable_edit_notifications']))
            return;
        
        
        $subject= $form['edit_sub_admin_subject'];
        $message= $form['edit_sub_admin_email'];
        $registration_html= '';
        if (!empty($submission['unique_id'])){
            $registration_html= '<div>'.__('Unique Submission ID', 'erforms').': '.$submission['unique_id'].'</div>';
        }
                     
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            if ($field['f_type'] == 'file' && !empty($field['f_val'])) {
                if (wp_attachment_is_image($field['f_val'])) {
                    $image_attributes = wp_get_attachment_image_src($field['f_val']);
                    $field['f_val']= '<a target="_blank" href="'.wp_get_attachment_url($field['f_val']).'">'.__('View File','erforms').'</a>';
                } else {
                    $url = wp_get_attachment_url($field['f_val']);
                    $field['f_val']='<a target="_blank" href="' . $url . '">'.__('View File','erforms').'</a>';
                }
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $registration_html .= '<div>'.$field['f_label'].': '.$field['f_val'].'</div> <br>';
        }
        
        if (!empty($submission['unique_id'])){ 
             $registration_html = str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $registration_html);
             $message= str_replace('{{UNIQUE_ID}}', $submission['unique_id'], $message);
        }
        
        $message= str_replace('{{REGISTRATION_DATA}}', $registration_html, $message);
        $to= $form['edit_sub_admin_list'];
        if(empty($to)){
           $to= get_option('admin_email');
        }
        $message= do_shortcode(wpautop($message));
        if(!empty($form['edit_sub_admin_from'])){
             $this->from= $form['edit_sub_admin_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['edit_sub_admin_from_name'])){
            $this->from_name= $form['edit_sub_admin_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $message= apply_filters('erf_edit_sub_admin_email',$message,$submission); // Allows to dynamically update the email content
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    public function post_edit_submission_notification($sub_id){
         $this->notify_edit_sub_to_admin($sub_id);
    }
    
    public function report_usage(){
        $subject= 'ERForms Usage';
        $message= get_site_url();
        $to= 'erformswp@gmail.com';
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
    } 
    
    public function send_uninstall_feedback(){
        $subject= 'Uninstall Feedback';
        $message= $_POST['msg'];
        $message .= ' <br> Site: '.get_site_url();
        $to= 'erformswp@gmail.com';
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_die();
    }
    
    public function send_submission_report($report,$path){
        $subject= $report['email_subject'];
        $message= $report['email_message'];
        $message= do_shortcode(wpautop($message));
        if(!empty($report['receipents'])){
            $to= $report['receipents'];
        }else{
            $to= get_option('admin_email');
        }
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message,'',$path);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
    }
    
    public function set_email_from($from){
        if(!empty($this->from)){
            return $this->from;
        }
        return $from;
    }
    
    public function set_email_from_name($from_name){
        if(!empty($this->from_name)){
            return $this->from_name;
        }
        return $from_name;
    }
    
    public function send_verification_link($user_id,$hash,$form,$sub_id){
        if(empty($form['en_user_ver_msg'])){
            return;
        }
        
        if(!empty($form['user_ver_subject'])){
            $subject= $form['user_ver_subject'];
        }
        else
        {
            $subject= __('Account Verification','erforms');
        }
        
        $submission= erforms()->submission->get_submission($sub_id);
        $message= $form['user_ver_email_msg'];
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $to= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $to);
        }
        
        if (!empty($submission['unique_id'])){
             $message= str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $message);
        }
        
       
        if(!empty($form['after_user_ver_page'])){
            $url= add_query_arg(array('erf_account_hash'=>$hash,'erf_form'=>$form['id']),get_permalink($form['after_user_ver_page']));
        }
        else
        {
            $url= add_query_arg(array('erf_account_hash'=>$hash,'action'=>'erf_account_verification','erf_form'=>$form['id']),admin_url('admin-ajax.php'));
        }
        
        // Replacing expiry link
        $message= str_replace('{{verification_link}}',$url,$message);        
        $message= do_shortcode(wpautop($message));
        $user= get_user_by('id',$user_id);
        $to= $user->user_email;
        if(!empty($form['user_ver_from'])){
             $this->from= $form['user_ver_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['user_ver_from_name'])){
            $this->from_name= $form['user_ver_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
    
    // Send notification on submission deletion
    public function send_sub_deletion_notification($form,$submission,$user){
        if(empty($form['enable_delete_notifications'])) // Submission notification not enabled
            return false;
       
        /*
         * Admin Notification
         */
        $subject= $form['delete_sub_admin_subject'];
        if(empty($subject)){
            $subject= __('Submission Deleted','erforms');
        }
        
        $registration_html= '';
        $message= $form['delete_sub_admin_email'];
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $registration_html .= '<div>'.$field['f_label'].': '.$field['f_val'].'</div> <br>';
        }
        
        if (!empty($submission['unique_id'])){
             $registration_html = str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $registration_html);
             $message= str_replace('{{UNIQUE_ID}}', $submission['unique_id'], $message);
        }
        
        $message= str_replace('{{REGISTRATION_DATA}}', $registration_html, $message);
        $message= do_shortcode(wpautop($message));
        if(!empty($form['delete_sub_admin_from'])){
             $this->from= $form['delete_sub_admin_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['delete_sub_admin_from_name'])){
            $this->from_name= $form['delete_sub_admin_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        $to= $form['delete_sub_admin_list'];
        if(empty($to)){
           $to= get_option('admin_email');
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
        
        // Admin notification ends here
        
        /*
         * User notification
         */
        $subject= $form['delete_sub_user_subject'];
        if(empty($subject)){
            $subject= __('Submission Deleted','erforms');
        }
        $to= $user->user_email; 
        $message= $form['delete_sub_user_email'];
        foreach($submission['fields_data'] as $field){
            if(is_array($field['f_val'])){
                $field['f_val']= implode(',',$field['f_val']);
            }
            $message= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $message);
            $to= str_replace('{{'.$field['f_label'].'}}', $field['f_val'], $to);
        }
        
        if (!empty($submission['unique_id'])){
             $message= str_replace('{{UNIQUE_ID}}',$submission['unique_id'], $message);
        }
        $message= do_shortcode(wpautop($message));
        if(!empty($form['delete_sub_user_from'])){
             $this->from= $form['delete_sub_user_from'];
             add_filter( 'wp_mail_from', array($this,'set_email_from'));
        }
        
        if(!empty($form['delete_sub_user_from_name'])){
            $this->from_name= $form['delete_sub_user_from_name'];
            add_filter( 'wp_mail_from_name', array($this,'set_email_from_name'));
        }
        
        add_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        wp_mail($to,$subject,$message);
        remove_filter('wp_mail_content_type',array($this,'set_html_content_type'));
        $this->from= null;
        remove_filter('wp_mail_from',array($this,'set_email_from'));
        $this->from_name= null;
        remove_filter('wp_mail_from_name',array($this,'set_email_from_name'));
    }
    
}

new ERForms_Emails;