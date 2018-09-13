<?php

/**
 * User Related
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_User {

    private  $meta_prefix= 'erf_';
    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action('erf_user_created', array($this, 'post_user_creation'), 10, 3);
        add_filter('login_redirect', array($this,'login_redirect'), 10, 3 );
        add_filter('erf_login_redirect', array($this,'login_redirect'), 10, 3 );
        add_filter('erf_ajax_before_sub_response',array($this,'ajax_before_sub_response'),10,1);
        
        // WP User Related
        add_action('edit_user_profile', array($this,'show_profile_fields'));
        add_action('show_user_profile', array($this,'show_profile_fields'));
        add_action( 'personal_options_update',array($this,'update_user_status'));
        add_action( 'edit_user_profile_update',array($this,'update_user_status'));
        add_action('manage_users_columns',array($this,'add_user_column'));
        add_action('manage_users_custom_column', array($this,'fill_status_column'),10,3);
        add_filter('authenticate', array($this,'authenticate_user'), 30, 3 );
        add_action('wp_ajax_erf_reset_password', array( $this, 'reset_password'));
        add_action('wp_ajax_nopriv_erf_reset_password', array( $this, 'reset_password'));
        add_action('wp_ajax_erf_login_user', array( $this, 'process_login'));
        add_action('wp_ajax_nopriv_erf_login_user', array( $this, 'process_login'));
        add_action('wp_ajax_erf_change_password', array( $this, 'change_password_ajax'));
        add_action('wp_ajax_erf_account_verification',array($this,'account_verification'));
        add_action('wp_ajax_nopriv_erf_account_verification',array($this,'ajax_account_verification'));
        add_shortcode('erforms_account_verification',array($this,'account_verification'));
        add_action('wp_ajax_erf_ajax_log_in',array($this,'ajax_log_in'));
        add_action('wp_ajax_nopriv_erf_ajax_log_in',array($this,'ajax_log_in'));
    }

    public function post_user_creation($user_id, $form_id,$sub_id) {
        $form_model = erforms()->form;
        $form= $form_model->get_form($form_id);
        
        // Check for user role assignment if not already logged in
        if (is_user_logged_in())
            return false;
        
        $default_role = $form['default_role'];
        if(!empty($default_role)){ 
            $this->set_user_role($user_id, $default_role);
        }
        
        
        // Update form and submission ID in user meta for future reference 
        $this->update_meta($user_id, 'form', $form['id']);
        $this->update_meta($user_id, 'submission', $sub_id);
        
        // Auto User Activation Check
        $auto_activation = $form['auto_user_activation'];
        if (!empty($auto_activation)) {
            $this->update_meta($user_id, 'active', 1);
            do_action('erf_async_user_activated',$user_id);
            //wp_schedule_single_event(current_time('timestamp') + 20, 'erf_async_user_activated',array($user_id));
        } else {
            // Check if email verification has to be done
            if(!empty($form['en_email_verification'])){
                $hash=  wp_generate_password(12,false);
                $this->update_meta($user_id, 'verification_hash',$hash);
                //do_action('erf_send_verification_link',$user_id,$hash,$form,$sub_id);
                wp_schedule_single_event(time()+50,'erf_send_verification_link',array($user_id,$hash,$form,$sub_id));
            }
            $this->update_meta($user_id, 'active', 0);
        }
        
        
        // Auto Login check
        $auto_login = $form['auto_login'];
        if (!empty($auto_login) && !empty($auto_activation)) {
            $this->login_user_by_id($user_id);
        }
    }
    
    

    public function set_user_role($user_id, $role) {
        $user = get_user_by('ID', $user_id);
        if (empty($user))
            return false;

        $user->set_role($role);
    }
    
    public function get_user_roles($user_id) {
        $user = get_user_by('ID', $user_id);
        if (empty($user))
            return false;

        return $user->roles;
    }

    /*
     * Login user by ID
     */
    private function login_user_by_id($user_id) {
        if (headers_sent())
            return;
        //delete_user_meta($user_id,$this->meta_prefix.'verification_hash');
        do_action('erf_user_logged_in',$user->ID);
        wp_set_auth_cookie($user_id);
    }
    
    public function login_user_by_credentials($credentials= array(),$secure_cookie=''){
        return wp_signon($credentials,$secure_cookie);
    }

    public function update_meta($user_id, $meta, $value) {
        $meta = 'erf_' . $meta;
        update_user_meta($user_id, $meta, $value);
    }
    
    public function get_meta($user_id, $meta,$single= true) {
        $meta = 'erf_'.$meta;
        return get_user_meta($user_id, $meta, $single);
    }

    public function login_redirect($redirect_to,$request,$user){
        if(is_wp_error($user) || empty($user))
        return $redirect_to;
    
        if(empty($user->ID))
            return $redirect_to;
        $redirect_to= $this->redirection_url_after_login($redirect_to,$user);
        return $redirect_to;

    }
    
    public function process_login(){
        $response= array('success'=>0);
        if(isset($_POST['action']) && $_POST['action']=='erf_login_user' && !empty($_POST['erf_username']) && !empty($_POST['erf_password'])){
            $username= sanitize_text_field($_POST['erf_username']);
            $password= sanitize_text_field($_POST['erf_password']);
            $redirect_to= $_POST['redirect_to'];
            
            if(!empty($username) && !empty($password)){
                $user= get_user_by('login',$username);
                if(empty($user)){
                    $user= get_user_by('email',$username);
                }
                if(!empty($user)){ 
                    $creds = array();
                    $creds['user_login'] = $user->user_login;
                    $creds['user_password'] = $password;
                    $creds['remember']= isset($_POST['rememberme']) ? true : false;
                    
                    $user= $this->login_user_by_credentials($creds,is_ssl());
                    if($user instanceof WP_Error){
                       if(isset($user->errors) && isset($user->errors['incorrect_password'])){
                           erforms()->errors['login_error']="The password you entered for the username <strong>$username</strong> is incorrect.";
                       }
                       else{
                            erforms()->errors['login_error']=  $user->get_error_message();
                       }
                       $response['msg']= erforms()->errors['login_error'];
                    }
                    else{
                        $response['success']=1;
                        $response['reload']= true;
                        wp_set_current_user($user->ID);
                        $response['redirect']= apply_filters('erf_login_redirect',$redirect_to,"",$user);
                    } 
                }
                else
                {
                     erforms()->errors['login_error']= __('ERROR: No such user exists.','erforms');
                     $response['msg']= erforms()->errors['login_error'];
                }
                
            }
            
        }
        echo json_encode($response);
        wp_die();
    }
    
    public function show_profile_fields($user){ 
        erforms_show_user_fields($user);
    }
    
    public function update_user_status($user_id){
        if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
        $current_user= wp_get_current_user();
   
        if($current_user->ID==$user_id)
            return;
        
        if(!isset($_POST['erf_user_status']))
            return; 
        
        $active= absint($_POST['erf_user_status']);
        $current_status= $this->get_meta( $user_id,'active');
	$this->update_meta( $user_id,'active',$active);
        
        if($current_status!=$active && $active==1)
        {   
            do_action('erf_user_activated',$user_id);
        }
        
    }
    
    public function add_user_column($column_headers) {
        $column_headers['user_status'] = __('User Status','erforms');
        return $column_headers;
    }
    
    public function fill_status_column($value, $column_name, $user_id) {
        $user = get_userdata($user_id);
        if ('user_status'==$column_name) 
        {
          $active= $this->get_meta($user->ID,'active');
          if($active==='0')
              return __('Inactive','erforms');
          else
              return __('Active','erforms');
        }
        return $value;
    }
    
    public function ajax_before_sub_response($response){
        $submission= erforms()->submission->get_submission($response['submission_id']);
        $form= erforms()->form->get_form($submission['form_id']);
        $auto_login = $form['auto_login'];
        $auto_activation = $form['auto_user_activation'];
        $redirect_to= $form['redirect_to'];
        if (!is_user_logged_in() && empty($redirect_to) && !empty($auto_login) && !empty($auto_activation)) {
            $response['msg']= __('Please wait, While we are setting up your account.','erforms');
            $response['reload']= true;
        }
        
        if(erforms()->frontend->edit_sub_status){
            if(isset($response['redirect_to'])) unset($response['redirect_to']);
            if(isset($response['reload'])) unset($response['reload']);
            $response['msg']= __('Submission edited successfully','erforms');
        }

        return $response;
    }
    
    public function get_user($user_id){
        return get_userdata($user_id);
    }
    
    public function authenticate_user($user, $username, $password){
        // username and password are correct
        if ($user instanceof WP_User) {
            $is_active = $this->get_meta($user->ID, 'active');
            if($is_active==='0')
            {
                return new WP_Error('status_deactive',__('ERROR: Your account is not active.','erforms'));
            }
        }
    
        return $user;
    }
    
    /**
    * Initiates password reset.
    */
   public function reset_password() {
        $email= $_POST['user_login'];
        $response= array('success'=>1,'msg'=>__('Check your email for the confirmation link.','erforms'));
        if(empty($email)){
            $response['msg']= __('Please provide a valid email');
        }
         
        $errors = $this->retrieve_password();
        if (is_wp_error($errors)) {
            $response['msg']=  $errors->get_error_message();
            $response['success']=0;
        }
        echo json_encode($response);
        exit;
   }
    
   
   public function retrieve_password(){
       
	$errors = new WP_Error();

	if ( empty( $_POST['user_login'] ) || ! is_string( $_POST['user_login'] ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
	} elseif ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
	}

	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
		return $errors;
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key = get_password_reset_key( $user_data );

	if ( is_wp_error( $key ) ) {
		return $key;
	}

	if ( is_multisite() ) {
		$site_name = get_network()->site_name;
	} else {
		/*
		 * The blogname option is escaped with esc_html on the way into the database
		 * in sanitize_option we want to reverse this for the plain text arena of emails.
		 */
		$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	/* translators: %s: site name */
	$message .= sprintf( __( 'Site Name: %s'), $site_name ) . "\r\n\r\n";
	/* translators: %s: user login */
	$message .= sprintf( __( 'Username: %s'), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

	/* translators: Password reset email subject. %s: Site name */
	$title = sprintf( __( '[%s] Password Reset' ), $site_name );

	/**
	 * Filters the subject of the password reset email.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $title      Default email title.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	/**
	 * Filters the message body of the password reset mail.
	 *
	 * If the filtered message is empty, the password reset email will not be sent.
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
        
	if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ){
            return new WP_Error('erf_email_error',__('The email could not be sent.','erforms'));	
        }

	return true;

   }
   
   public function check_user_by_username($commands,$form){
       $username= sanitize_text_field($_POST['field_value']);
       if(empty($username)){
           return $commands;
       }
       $command= erforms_default_field_command();
       if(username_exists($username)){
           $command['error']= __('Username already exists.','erforms');
       }
       array_push($commands,$command);
       return $commands;
   }
   
   /*
    * Change password for authenticated users
    */
   public function change_password_ajax(){
     // Delete report action
    if (defined('DOING_AJAX') && DOING_AJAX){
        $errors= array();
        $nonce=  isset($_POST['erform_change_pwd_nonce']) ? $_POST['erform_change_pwd_nonce'] : '';
        if (!wp_verify_nonce($nonce,'erform_change_pwd_nonce')) {
            $errors[]=__('Invalid Security Token, Please refresh the page and try again','erforms'); 
            wp_send_json_error(array('errors'=>$errors));
        }
        
        $current_password= sanitize_text_field($_POST['password_current']);
        $current_password= trim($current_password);
        if(empty($current_password)){
            $errors[]=__('Current Password Required.','erforms');
            wp_send_json_error(array('errors'=>$errors));
        }
        
        $current_user = wp_get_current_user();
        // Check current password
        $match= wp_check_password($current_password, $current_user->user_pass, $current_user->ID);
        if(empty($match)){
            $errors[]=__('Current Password Incorrect.','erforms');
            wp_send_json_error(array('errors'=>$errors));
        }
        
        $password_1= sanitize_text_field($_POST['password_1']);
        $password_1= trim($password_1);
        if(empty($password_1)){
            $errors[]=__('New Password Required.','erforms');
            wp_send_json_error(array('errors'=>$errors));
        }
        
        wp_set_password($password_1,$current_user->ID);
        
        // Log-in again.
        wp_set_auth_cookie($current_user->ID);
        wp_set_current_user($current_user->ID);
        do_action('wp_login', $current_user->user_login, $current_user);
        wp_send_json_success(array('msg'=>__('Password changed successfully.','erforms')));
    }   
   }
   
   // Called when Account verification page is not configured 
   public function ajax_account_verification(){
       $hash= isset($_REQUEST['erf_account_hash']) ? $_REQUEST['erf_account_hash'] : '';
       if(empty($hash)){
           _e('Missing verification hash','erforms');
           wp_die();
       }
       
       $form_id= isset($_REQUEST['erf_form']) ? absint($_REQUEST['erf_form']) : 0;
       $form= erforms()->form->get_form($form_id);
       if(empty($form)){
           _e('Form not found.','erforms');
           wp_die();
       }
       
       // Find User
       $user_meta_query= array(
                            array(
                                'key'=>$this->meta_prefix.'verification_hash',
                                'value'=>$hash,
                                'compare'=>''    
                            )
       );
       $users= get_users(array('meta_query'=>$user_meta_query));
       if(empty($users)){
          _e('No such users exists','erforms'); 
          wp_die();
       }
      
       foreach($users as $user){ // Execute block only for first user as verification hash meant to be unique
           $current_status= $this->get_meta($user->ID,'active');
           if($current_status==='' || !empty($current_status))
           {   
                _e('User account is already activated','erforms');
           }
           else
           {
               $this->update_meta($user->ID, 'active', 1);
               do_action('erf_user_activated',$user->ID);
               //delete_user_meta($user->ID,$this->meta_prefix.'verification_hash');
               $auto_login = $form['auto_login_after_ver'];
               $redirect_to= home_url();
               if (!empty($auto_login)) {
                    $this->login_user_by_id($user->ID);
                    $redirect_to= $this->redirection_url_after_login($redirect_to,$user);
               }
               
                echo strip_shortcodes($form['user_acc_verification_msg']);
                echo '<script>document.location="'.$redirect_to.'";</script>';
               
               
               
           }
           break;
       }
       exit;
   }
   
   // Registered with [erforms_account_verification] shortcode
   public function account_verification(){
       ob_start();
       $hash= isset($_REQUEST['erf_account_hash']) ? $_REQUEST['erf_account_hash'] : '';
       if(empty($hash)){
           return ob_get_clean();
       }
       
       $form_id= isset($_REQUEST['erf_form']) ? absint($_REQUEST['erf_form']) : 0;
       $form= erforms()->form->get_form($form_id);
       if(empty($form) || $form['type']!='reg'){
           return ob_get_clean();
       }
       
       // Find User
       $user_meta_query= array(
                            array(
                                'key'=>$this->meta_prefix.'verification_hash',
                                'value'=>$hash,
                                'compare'=>''    
                            )
       );
       $users= get_users(array('meta_query'=>$user_meta_query));
       if(empty($users)){
           _e('Verification link does not match with any User\'s record.','erforms');
           return ob_get_clean();
       }
       if(is_user_logged_in()){
           include('html/account_verification.php');
            return ob_get_clean();
       }
       
       foreach($users as $user){ // Execute block only for first user as verification hash meant to be unique
           $current_status= $this->get_meta($user->ID,'active');
           if($current_status==='' || !empty($current_status))
           {   
               _e('User account is already activated','erforms');
           }
           else
           {
               $this->update_meta($user->ID, 'active', 1);
               do_action('erf_user_activated',$user->ID);
               //delete_user_meta($user->ID,$this->meta_prefix.'verification_hash');
               include('html/account_verification.php');
               do_action('erf_user_account_verified',$user);
           }
           break;
       }
       return ob_get_clean();
   }
   
   // Logins user from AJAX request. Example: Used in Auto Login after successfull account verification
   public function ajax_log_in(){
       $type= sanitize_text_field($_POST['type']);
       $value= sanitize_text_field($_POST['value']);
       
       if(empty($type) || empty($value)){
           wp_send_json_error();
       }
       
       if($type=='user_hash'){
           $user_meta_query= array(
                            array(
                                'key'=>$this->meta_prefix.'verification_hash',
                                'value'=>$value,
                                'compare'=>''    
                            )
            );
           $users= get_users(array('meta_query'=>$user_meta_query));
           if(empty($users)){
            wp_send_json_error();
           }
           $options= erforms()->options->get_options();
           foreach($users as $user){
                $this->login_user_by_id($user->ID);
                $redirect_to= esc_url_raw($_POST['redirect_to']);
                $redirect_to= $this->redirection_url_after_login($redirect_to,$user);
                break;
           }
           wp_send_json_success(array('redirect_to'=>$redirect_to));
       }
       wp_send_json_success(); 
   }
   
   // Returns redirection URL as configured in Global Settings
   public function redirection_url_after_login($default_url,$user){
        $options= erforms()->options->get_options();
        if(!empty($options['en_role_redirection'])){
            foreach($user->roles as $role){
                if(!empty($options[$role.'_login_redirection'])){
                     $default_url= $options[$role.'_login_redirection'];
                     break;
                }
            }
        }
        else if(!empty($options['after_login_redirect_url']))
        {   
            $default_url = $options['after_login_redirect_url'];
        }
        return $default_url;
   }
}

