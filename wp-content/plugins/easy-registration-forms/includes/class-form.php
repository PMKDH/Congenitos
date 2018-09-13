<?php

/**
 * Main form handler
 *
 * Contains a bunch of helper methods as well.
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Form extends ERForms_Post
{
    
        protected $post_type='erforms';
	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register erforms custom post type
		$this->register_cpt();

		// Add erforms to new-content admin bar menu
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 99 );
                add_action('wp_ajax_erf_new_form', array($this,'ajax_add_form'));
                add_action('wp_ajax_erf_save_form', array($this,'ajax_save_form'));
                add_action('wp_ajax_erf_get_form', array($this,'get_form_ajax'));
                add_action('wp_ajax_nopriv_erf_get_form', array($this,'get_form_ajax'));
                add_action('wp_ajax_erforms_get_form_meta', array($this,'get_form_meta_ajax'));
                add_action('wp_ajax_nopriv_erforms_get_form_meta', array($this,'get_form_meta_ajax'));
                add_filter('erforms_before_form_processing', array($this,'check_submission_limits'),10,2);
                add_filter('erforms_before_form_processing', array($this,'check_form_user_restrictions'),10,2);
                add_filter('erforms_parse_success_message', array($this,'parse_fields_in_content'),10,2);
                $this->initialize_form_field_hooks();
	}

	/**
	 * Registers the custom post type to be used for forms.
	 *
	 * @since 1.0.0
	 */
	public function register_cpt() {

		// Custom post type arguments, which can be filtered if needed
		$args = apply_filters(
			'erforms_post_type_args',
			array(
				'labels'              => array(),
				'public'              => false,
				'exclude_from_search' => true,
				'show_ui'             => false,
				'show_in_admin_bar'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'can_export'          => false,
				'supports'            => array( 'title' ),
			)
		);

		// Register the post type for Forms
		register_post_type( ERFORMS_FORM_POST_TYPE, $args );
	}

	/**
	 * Adds "ERForm" item to new-content admin bar menu item.
	 *
	 * @since 1.1.7.2
	 *
	 * @param object $wp_admin_bar
	 */
	public function admin_bar( $wp_admin_bar ) {

		if ( ! is_admin_bar_showing() || ! current_user_can( apply_filters( 'erforms_manage_cap', 'manage_options' ) ) ) {
			return;
		}

		$args = array(
			'id'     => 'erform',
			'title'  => 'ERForm',
			'href'   => admin_url( 'admin.php?page=erforms-dashboard' ),
			'parent' => 'new-content',
		);
		$wp_admin_bar->add_node( $args );
	}
        
        public function get_form($post){
            if(!($post instanceof WP_Post)){
                $post = $this->get($post);
            }
            
            if(empty($post->ID))
                return false;
            
            $form= $this->get_meta($post->ID,'form_data');
            $form['id']= $post->ID;
            $form['fields']= $form['fields'];
            $default_meta_values= erforms_default_form_meta($form['type']);
            
            foreach($default_meta_values as $key=>$val){
                if(!isset($form[$key])){
                    if(isset($default_meta_values[$key]))
                        $form[$key]= $default_meta_values[$key];
                    else
                        $form[$key]='';
                }
            }

            return $form;
        
        }

        public function get_user_field_from_schema($form_schema){
            if(empty($form_schema) || empty($form_schema['fields']) || !is_array($form_schema['fields']) )
                return false;
            
            foreach($form_schema['fields'] as $key=>$field){
                if($field['subtype']=="user_email")
                {
                   return  $field;
                }
            }
        }


        public function is_user_role_allowed($form){
            if(!is_array($form))
            {
                $form= $this->get_form($form);
            }

            $current_user = wp_get_current_user();
            $current_user_roles= (array) $current_user->roles;
            $allowed= false;
            if(!empty($form['access_roles'])){
                foreach($current_user_roles as $role){
                    if (in_array($role,$form['access_roles']) ) {
                        $allowed= true;
                        break;
                    }
                }
            }
            else
            {
                $allowed= true;
            }
            
            return $allowed;
        }
        
        
        public function ajax_add_form(){
           if(!current_user_can('administrator')) 
               wp_die('Operation now allowed');
           
           $form_title = sanitize_text_field($_POST['title']);
           $form_type = sanitize_text_field($_POST['form_type']);
           $form_type = empty($form_type) ? "reg" : $form_type;
           $form_id= $this->add_form($form_title,$form_type);
           if ($form_id) 
           {
               $response = array(
                   'id' => $form_id,
                   'redirect' => add_query_arg(
                           array(
                       'tab' => 'build',
                       'form_id' => $form_id,
                       'newform' => '1',
                           ), admin_url('admin.php?page=erforms-dashboard')
                   ),
               );
               wp_send_json_success($response);
           } 
           else {
               die(__('Error creating form', 'erforms'));
           }
        }
        
        /**
        * Create a new form
        *
        * @since 1.0.0
        */
        public function add_form($form_title,$form_type='reg') {
           // Create form
           $data = array();

           if ($form_type == "reg") {
               $form_content = erforms_sample_reg_form();
           } 
           elseif($form_type == "contact"){
               $form_content = erforms_sample_contact_form();
           }
           
           $form_content= apply_filters('erf_default_form_fields',$form_content,$form_type);
           $form_content['title']= $form_title;
           $form_content['fields']= erforms_decode($form_content['fields']);
           
           $all_meta = erforms_default_form_meta($form_type);
           foreach($all_meta as $key=>$value){
               $form_content[$key]= $value;
           }
          // Updating dynamic form attributes
           $form_content['auto_reply_subject']= $form_title.' '.$form_content['auto_reply_subject'];
           $args = array(
                    'post_title'    => $form_title,
                    'post_content'  => 'Form Data',
                    'post_type' => $this->post_type,    
                    'post_status'   => 'publish',
                    'meta_input' => array('erform_form_data' => $form_content)
                    );
           
           $form_id = $this->add($args);
           return $form_id;
       }
       
       public function update_form($form){
           if(empty($form))
                return false;
            
            // Update post 
            $post = array(
                'ID'           => $form['id'],
                'post_title'   => $form['title'],
                'meta_input' => array('erform_form_data' => $form)
            );
            wp_update_post( $post );
       }
       
       public function ajax_save_form(){
            // Check for form data
            if (empty($_POST['data'])) {
                die(__('No data provided', 'erforms'));
            }
            
            if(!current_user_can('administrator')) 
               wp_die('Operation now allowed');
            
            $form_id= absint($_POST['id']);
            if(empty($form_id))
                die(__('No such Form existsyyy', 'erforms'));
            
            $form= $this->get_form($form_id);
            if(empty($form))
                die(__('No such Form exists', 'erforms'));
            
            $data= json_decode(stripslashes($_POST['data']),true);
            if(empty($data)) //Invalid data, Do not save data
            {
                die(__('Invalid form data.', 'erforms'));
            }
            
            $form['fields'] = $data['fields']; 
            $form['id']= absint($_POST['id']);
            $form['form_html']= $data['form_html'];
            $form['title']= sanitize_text_field($data['title']);
            if (!$form['id'])
            {
                die(__('An error occurred and the form could not be saved', 'erforms'));
            } 
            else 
            {
                $this->update_form($form);
                wp_send_json_success(
                        array(
                            'form_desc' => $form['settings']['form_desc'],
                            'redirect' => admin_url('admin.php?page=erforms-dashboard&form_id='.$form['id']),
                        )
                );
                die;
            }
       }
       
       public function check_submission_limits($errors,$form){
          if(empty($form['enable_limit']))
            return $errors;
 
          if($form['limit_type']=='date'){
              if(empty($form['limit_by_date']))
                  return $errors;
              $limit_time= strtotime($form['limit_by_date']);
              if(empty($limit_time)) 
                  return $errors;
              $limit= $limit_time + (23*3600);
              $current_time= current_time('timestamp');
              if($current_time>$limit)
              {
                  $errors[]= array('limit_error',$form['limit_message']);
              }
          } 
          else if($form['limit_type']=='number')
          {
              $limit= $form['limit_by_number'];
              if(empty($limit))
                  return $errors;
              $submission_model= erforms()->submission;
              $submissions= $submission_model->get_submissions_by_form($form['id']);
              if(count($submissions)>=$limit){
                  $errors[]= array('limit_error',$form['limit_message']);
              }
          }  
          
          return $errors;
       }
   
       public function check_form_user_restrictions($errors,$form){
        // Check for user roles
        $allowed= $this->is_user_role_allowed($form);
          if(empty($allowed)){
              $errors[]= array('role_not_allowed',$form['access_denied_msg']);
          }
        
        
        return $errors;
       }
              
       /**
	 * Delete Form (Along with all related submissions).
	 *
	 * @since 1.0.0
	 *
	 * @param array $ids
	 *
	 * @return boolean
	 */
	public function delete($ids=array()) {
            if (!is_array($ids)) {
               $ids = array($ids);
            }

            $ids = array_map( 'absint', $ids );
            $submission_model= erforms()->submission;
            foreach ( $ids as $id ) {
               //Get all the submissions
               $submissions=  $submission_model->get_submissions_by_form($id);
               foreach($submissions as $submission){
                   wp_delete_post( $submission['id'], true );
               }
               wp_delete_post( $id, true );
            }

            return true;
	}
        
        /**
	 * Duplicate Form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ids
	 *
	 * @return boolean
	 */
        public function duplicate($ids= array()){
            if (!is_array($ids)) {
               $ids = array($ids);
            }
            
            $ids = array_map('absint',$ids );
            foreach ($ids as $id ) {
               $post= get_post($id); 
               if(!empty($post)){
                 erforms_duplicate_post($post);  
               }
               
            }
            return true;
        }
        
        public function get_fields_dropdown($form_id,$exclude= array('submit','password')){
            $form= $this->get_form($form_id);
            $dropdown= array();
           
            foreach($form['fields'] as $field){
                if(isset($field['type']) && in_array($field['type'],$exclude)){
                    continue;
                }
                else if(isset($field['subtype']) && in_array($field['subtype'],$exclude)){
                    continue;
                }
                elseif(isset($field['entityProperty']) && in_array($field['entityProperty'],$exclude)){
                    continue;
                }
                
                if(isset($field['name'])){
                    $dropdown[$field['name']]= $field['label'];
                }
                
            }
            
            return $dropdown;
        }
        
        /*
         * Return content with replaced data values after form submission
         */
        public function parse_fields_in_content($content,$submission_id){ 
            if(empty($submission_id))
                return $content;
            $submission= erforms()->submission->get_submission($submission_id);
            if(empty($submission))
                return $content;
            foreach($submission['fields_data'] as $fields)
            {   $current_field = '{{'.$fields['f_label'].'}}';
                if(is_array($fields['f_val'])){
                    $fields['f_val']= implode(",",$fields['f_val']);
                }
                $content = str_replace($current_field,$fields['f_val'],$content);
            }
            if(array_key_exists('unique_id', $submission)){
                $content = str_replace('{{UNIQUE_ID}}',$submission['unique_id'],$content);
            }
            return $content;
        }
        
        public function get_form_ajax(){
          $form_id= absint($_POST['form_id']);
          if(empty($form_id))
                die(__('No such Form exists', 'erforms'));
          $form= $this->get_form($form_id);
            if(empty($form))
                die(__('No such Form exists', 'erforms'));
          
          $form= $this->filter_form_info($form);  
          $response = array(
                'form' => $form
          );
          wp_send_json_success($response);
          wp_die();  
      }
      
      public function dynamic_rules($form){
        if (!isset($form['id'])) { // Load form object if form id passed
            $form = $this->form_model->get_form($form);
        }
        $global_types= array('address','user');
        $form['dynamic_rules']= array('change'=>array(),'load'=>array());
        
        foreach($form['fields'] as $field){
            if(!isset($field['name']))
                continue;
            
            if(!empty($field['entityType']) && in_array($field['entityType'],$global_types)){  
                $has_action= has_action('erforms_'.$field['entityType'].'_'.$field['entityProperty'].'_load');
                if($has_action){
                    array_push($form['dynamic_rules']['load'],array('action'=>'erforms_'.$field['entityType'].'_'.$field['entityProperty'].'_load','field_name'=>$field['name']));
                }
                
                $has_action= has_action('erforms_'.$field['entityType'].'_'.$field['entityProperty'].'_change');
                if($has_action){
                    array_push($form['dynamic_rules']['change'],array('action'=>'erforms_'.$field['entityType'].'_'.$field['entityProperty'].'_change','field_name'=>$field['name']));
                }
            }
            
            // Check for any hooks
            $has_filter= has_action('erforms_'.$form['id'].'_'.$field['name'].'_change');
            if($has_filter){
                 array_push($form['dynamic_rules']['change'],array('action'=>'erforms_'.$form['id'].'_'.$field['name'].'_change','field_name'=>$field['name']));
            }
            
            $has_filter= has_action('erforms_'.$form['id'].'_'.$field['name'].'_load');
            if($has_filter){
                 array_push($form['dynamic_rules']['load'],array('action'=>'erforms_'.$form['id'].'_'.$field['name'].'_load','field_name'=>$field['name']));
            }
        }

        return $form['dynamic_rules'];
      }
      
      private function initialize_form_field_hooks(){
        add_action('wp_ajax_erforms_field_load_commands',array($this,'field_load_commands_ajax')); 
        add_action('wp_ajax_nopriv_erforms_field_load_commands',array($this,'field_load_commands_ajax'));  
        add_action('wp_ajax_erforms_field_change_command',array($this,'field_change_command_ajax')); 
        add_action('wp_ajax_nopriv_erforms_field_change_command',array($this,'field_change_command_ajax'));  
        add_filter('erforms_address_country_load','erforms_address_country_load',10,2);
        add_filter('erforms_address_country_change','erforms_address_country_change',10,2);
        
        add_filter('erforms_user_username_change',array(erforms()->user,'check_user_by_username'),10,2); 
      }
      
      public function get_form_meta_ajax(){
          $meta= sanitize_text_field($_POST['meta']);
          $form_id= absint($_POST['form_id']);
          
          $form= $this->get_form($form_id);
          $form= $this->filter_form_info($form); 
         
          if(method_exists($this,$meta)) 
          {  
             wp_send_json_success(array($meta=>$this->$meta($form))); 
          } 
          else
          {
               if(isset($form[$meta])){
                wp_send_json_success(array($meta=>$form[$meta])); 
               }
          }
          
          wp_send_json_error();
      }
      
      private function filter_form_info($form,$exclude= array('pwd_res_answer')){
          foreach($exclude as $info){
              if(isset($form[$info])){
                  unset($form[$info]);
              }
          }
          return $form;
      }
      
      /*
       *   Returns load commands which must be executed after form load.
       *  `action_on` : Represents names of all the fields where values have to be filled
       *  `data : Data to be filled with in the HTML element
       *  `default_value`: Element's default value 
       *  `options`: Represents dropdown options existence in data
       *   `callback`: Callback function called after command execution. 
       */
      public function field_load_commands_ajax(){
         $form_id= absint($_POST['form_id']);
         if(empty($form_id)){
             wp_send_json_error();
         }
         
         $form= $this->get_form($form_id);
         if(empty($form)){
             wp_send_json_error();
         }
         
         $commands= array();
         $dynamic_rules= $this->dynamic_rules($form);
         foreach($dynamic_rules['load'] as $rule){
             $command= array('data'=>'','default_value'=>'','options'=>false,'on'=>array(),'callback'=>'');
             $command= apply_filters($rule['action'],$command,$form);
             array_push($commands,$command);
         }
         
         wp_send_json_success(array('commands'=>$commands));
      }
      
      /*
       *   Returns change commands which are triggered during field value change.
       *  `on` : Represents names of all the fields where values have to be filled
       *  `default_value`: Element's default value 
       *  `options`: Represents dropdown options existence in data
       *   `callback`: Callback function called after command execution.
       */
      public function field_change_command_ajax(){
         $form_id= absint($_POST['form_id']);
         if(empty($form_id)){
             wp_send_json_error();
         }
         
         $form= $this->get_form($form_id);
         if(empty($form)){
             wp_send_json_error();
         }
         
         $action= sanitize_text_field($_POST['change_action']);
         if(empty($action)){
              wp_send_json_error();
         }
         
         /*
          * Single command example
          */
         //$command= array('data'=>'','default_value'=>'','options'=>false,'on'=>array(),'callback'=>'');
         
         $commands= apply_filters($action,array(),$form);
         wp_send_json_success(array('commands'=>$commands));
      }
      
      public function get_forms(){
          $posts= $this->get();
          if(empty($posts))
              return array();
          $forms= array();
          foreach($posts as $post){
              $form= $this->get_form($post);
              array_push($forms,$form);
          }
          return $forms;
      }
 
}
