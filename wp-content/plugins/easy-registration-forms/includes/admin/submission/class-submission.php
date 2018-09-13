<?php

/**
 * Form submissions
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Admin_Submission {
    
    /**
    * Primary class constructor.
    *
    * @since 1.0.0
    */
    public function __construct() {
           add_action( 'admin_init', array( $this, 'init' ) );
    }
    
    /**
    *
    * @since 1.0.0
    */
    public function init() {
        // Check what page we are on
	$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
        
        // Only load if we are actually on the builder
        if ( 'erforms-submissions' === $page ) {
                // Load the class that builds the overview table.
                require_once ERFORMS_PLUGIN_DIR . 'includes/admin/submission/class-submission-table.php';
                add_action( 'erforms_admin_page',array( $this, 'table') );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
        } 
        else if ( 'erforms-submission' === $page ) {
                add_action( 'erforms_admin_page',array( $this, 'submission') );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
        }
        
        if (isset($_GET['delete_nonce']) && wp_verify_nonce($_GET['delete_nonce'], 'erf_submission_delete')){
           $submission_id= absint($_GET['submission_id']);
           $form_id= erforms()->submission->get_meta($submission_id,'id');
           wp_delete_post($submission_id,true);
           $url= admin_url('admin.php?page=erforms-submissions&erform_id='.$form_id);
           wp_redirect($url);
           exit;        
           
        }
        
        
    }
    
    /**
    * Enqueue assets for the overview page.
    *
    * @since 1.0.0
    */
    public function enqueues() {
        do_action( 'erf_admin_submission_enqueue');
    }
    
    /**
    * @since 1.0.0
    */
    public function table() {
       include 'html/submissions.php';
    }
   
   /**
    * @since 1.0.0
    */
    public function submission() {
       $sub_id= absint($_REQUEST['submission_id']); 
       $submission= erforms()->submission->get_submission($sub_id);
       if(empty($submission))
           return;
     
       $this->change_payment_status($sub_id);
       $notes= $this->save_note($sub_id);
       $submission_model= erforms()->submission;
       $submission= $submission_model->get_submission($sub_id);
       // History submissions for same User
       $user_id= $submission_model->get_meta($sub_id,'user');
       $submissions= $submission_model->get_submissions_from_user($user_id,array($submission['id']),$submission['form_id']);
       $revisions= $submission_model->get_revisions($submission['id']);
       include 'html/submission.php';
   }
   
   private function save_note($sub_id){
       $submission_model= erforms()->submission;
       $submission= $submission_model->get_submission($sub_id);
       $form_model= erforms()->form;
       $form= $form_model->get_form($submission['form_id']);
       $email_model= new ERForms_Emails;
       if(isset($_POST['erf_save_note'])){
           $text= $_POST['note_text'];
           $submission_model->add_note($sub_id,$text);
           
           $notify_user= isset($_POST['notify_user']) ? absint($_POST['notify_user']) : 0;
           if(!empty($notify_user))
           {
              $user= $submission['user'];
              if(!empty($user)){
                  $message = 'Hello '.$user['display_name'].'<br><br>'.$text;
                  $subject= ucwords($form['title']).' '.__('Notification','erforms');
                  $email_model->quick_email($user['user_email'],$subject,$message);
              }
             
           }
           
       }
       $notes= $submission_model->get_meta($sub_id,'submission_notes');
       if(is_array($notes))
           $notes= array_reverse($notes);
       
       return $notes;
   }
   
   private function change_payment_status($sub_id){
       $submission_model= erforms()->submission;
       $submission= $submission_model->get_submission($sub_id);
       $form_model= erforms()->form;
       $form= $form_model->get_form($submission['form_id']);
       $email_model= new ERForms_Emails;
       
       if(isset($_POST['erf_change_payment_status'])){
         $payment_status= $_POST['payment_status'];
         $notify_user= isset($_POST['notify_user']) ? absint($_POST['notify_user']) : 0;
         if($notify_user && !empty($_POST['notify_email']) && !empty($submission['user'])){
             $user= $submission['user'];
             $message = __('Hello','erfroms').' '.$user['display_name'].'<br><br>'.$_POST['notify_email'];
             $subject= ucwords($form['title']).' '.__('Notification','erforms');
             if(!empty($user)){
                 $email_model->quick_email($user['user_email'],$subject,$message);
             }
         }
         $add_note= isset($_POST['add_note']) ? absint($_POST['add_note']) : 0;
         if($add_note){
            $note_text= $_POST['note_text'];
            if(!empty($note_text)){
            $submission_model->add_note($sub_id,$note_text);
            }
         }
         
         // Update Payment Status
         if($submission['payment_status']!=$_POST['payment_status'])
         {
             $payment_status= sanitize_text_field($_POST['payment_status']);
             $note_text= __('Payment status changed to','erforms').' '.ucwords($payment_status);
             $submission_model->add_note($sub_id,$note_text);
             $submission_model->update_meta($sub_id,'payment_status',$payment_status);
             do_action('erf_payment_status_changed',$payment_status,$submission['payment_status'],true,array());
         }
       }
   }
}

new ERForms_Admin_Submission();