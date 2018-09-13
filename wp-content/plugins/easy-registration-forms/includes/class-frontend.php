<?php

/**
 * Form front-end rendering.
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Frontend {

    /**
     *
     * @var array
     */
    public $validator;
    public $errors = array();   
    public $options = array(); // Global options
    public $submission_id = 0;
    public $edit_sub_status = false;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Register shortcode.
        add_shortcode('erforms', array($this, 'form_shortcode'));
        add_shortcode('erforms_login', array($this, 'login_shortcode'));
        add_shortcode('erforms_preview', array($this, 'preview'));
        add_shortcode('erforms_my_account', array($this, 'my_account'));

        add_filter('erf_form_validated', array($this, 'form_validated'), 10, 2);
        add_filter('erf_after_submission_insertion', array($this, 'after_submission_insertion'), 10, 3);
        add_filter('register_url', array($this, 'register_url'));
        add_filter('erf_form_render_allowed', array($this, 'form_render_allowed'), 10, 2);
        // Ajax actions
        add_action('wp_ajax_erf_change_form_layout', array($this, 'change_form_layout'));

        add_action('wp_ajax_erf_submit_form', array($this, 'ajax_submit_form'));
        add_action('wp_ajax_nopriv_erf_submit_form', array($this, 'ajax_submit_form'));
        
        $this->validator = new ERForms_Validator;
        $this->options = erforms()->options->get_options();
    }
    
    /*
     * Load assets (CSS and JS)
     */

    public function enqueues($form = array()) {
        $js_libraries = isset($this->options['js_libraries']) ? $this->options['js_libraries'] : 'allow_all';

        if ($js_libraries == 'allow_all' || in_array('jquery', $js_libraries)) {
            wp_enqueue_script('jquery');
        }

        if ($js_libraries == 'allow_all' || in_array('jquery_ui', $js_libraries)) {
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_style('wp-jquery-ui-dialog');
        }

        if ($js_libraries == 'allow_all' || in_array('parsley', $js_libraries)) {
            wp_enqueue_script('erf-parsley', ERFORMS_PLUGIN_URL . 'assets/js/parsley.min.js');
        }

        if ($js_libraries == 'allow_all' || in_array('masking', $js_libraries)) {
            wp_enqueue_script("erf-masked-input", ERFORMS_PLUGIN_URL . 'assets/js/jquery.masked.input.js');
        }

        if ($js_libraries == 'allow_all' || in_array('font_awesome', $js_libraries)) {
            wp_enqueue_script('erf-font-awesome-js', 'https://use.fontawesome.com/7faa004e41.js');
        }

        if ($js_libraries == 'allow_all' || in_array('recaptcha', $js_libraries)) {
            if (!empty($this->options['recaptcha_configured'])) {
                wp_enqueue_script('erf-recaptcha', 'https://www.google.com/recaptcha/api.js');
            }
        }

        wp_enqueue_script('erf-form', ERFORMS_PLUGIN_URL . 'assets/js/erforms-form.js', array('jquery-ui-dialog'));

        $parsley_strings = erforms_error_strings();
        if (!empty($form)) {
            $user_fields = erforms_filter_user_fields($form['id']);
        } else {
            $user_fields = array();
        }

        $logged_in = is_user_logged_in() ? 1 : 0;
        $js_strings = erforms_js_strings();
        $plans = erforms()->plan->get_plans();
        $is_admin= erforms_is_user_admin();
        wp_localize_script('erf-form', 'erform_ajax', array('url' => admin_url('admin-ajax.php'), 'parsley_strings' => $parsley_strings,
            'user_fields' => $user_fields, 'logged_in' => $logged_in,'is_admin'=>$is_admin, 'js_strings' => $js_strings, 'plans' => $plans));
        wp_enqueue_script('erf-util-functions', ERFORMS_PLUGIN_URL . 'assets/js/utility-functions.js');
        wp_enqueue_script('erf-editable-dd', ERFORMS_PLUGIN_URL . 'assets/js/jquery-editable-select.min.js');

        wp_enqueue_style('erf-front-style', ERFORMS_PLUGIN_URL . 'assets/css/style.css');
        wp_enqueue_style('erf-front-style-responsive', ERFORMS_PLUGIN_URL . 'assets/css/responsive.css');
        wp_enqueue_style('erf-password-strength', ERFORMS_PLUGIN_URL . 'assets/css/password.min.css');
        wp_enqueue_script('jquery-ui-datepicker', '', array('jquery'));
        wp_enqueue_style('erf-jquery-datepicker-css', ERFORMS_PLUGIN_URL . 'assets/css/jquery-datepicker.css');
        wp_enqueue_style('erf-editable-dd-css', ERFORMS_PLUGIN_URL . 'assets/css/jquery-editable-select.min.css');
        do_action('erforms_frontend_enqueues', $form);
    }

    /**
     * Shortcode wrapper for the outputting a form.
     *
     * @since 1.0.0
     *
     * @param array $atts
     *
     * @return string
     */
    public function form_shortcode($atts) {

        $atts = shortcode_atts(array(
            'id' => false,
            'title' => false,
            'layout_options' => 1,
            'description' => false,
                ), $atts, 'output');
        ob_start();

        $this->render_form($atts);
        $sub_id = isset($_GET['sub_id']) ? absint($_GET['sub_id']) : 0;
        if (!empty($sub_id) && erforms_edit_permission($atts['id'], $sub_id)) { // Edit submission allowed for only admin (Exception: My Account page allows to edit submission from user)
            $this->edit_submission($sub_id, $atts);
        }
        return ob_get_clean();
    }

    /**
     * Shortcode wrapper for the outputting a login form.
     *
     * @since 1.0.0
     *
     * @param array $atts
     *
     * @return string
     */
    public function login_shortcode($attr) {

        ob_start();
        wp_enqueue_style('erf-front-style', ERFORMS_PLUGIN_URL . 'assets/css/style.css');
        wp_enqueue_script('erf-login-widget', ERFORMS_PLUGIN_URL . 'assets/js/erforms-login-widget.js', array('jquery'));
        wp_localize_script('erf-login-widget', 'erform_ajax_url', admin_url('admin-ajax.php'));
        $this->render_login_form($attr);

        return ob_get_clean();
    }

    /**
     * Primary function to render a login form on the frontend.
     *
     * @since 1.0.0
     *
     */
    public function render_login_form($attr) {
        if (empty($attr['show_register_form'])) {
            echo '<div class="erf-container">';
            include 'html/login-form.php';
            echo '</div>';
        } else {
            include 'html/login-form.php';
        }
    }

    /**
     * Primary function to render a form on the frontend.
     *
     * @since 1.0.0
     *
     * @param int $id
     * @param boolean $title
     * @param boolean $description
     */
    public function render_form($atts) {
        $id = $atts['id'];
        $title = $atts['title'];
        $description = $atts['description'];

        $id = absint($id);
        if (empty($id)) {
            return;
        }
        
        $form_model = erforms()->form;
        $form = $form_model->get_form($id);

        if (empty($form)) {
            _e('No such form exists in Database.', 'erforms');
            return;
        }


        // Basic information.
        $success = false;
        $title = filter_var($title, FILTER_VALIDATE_BOOLEAN);
        $description = filter_var($description, FILTER_VALIDATE_BOOLEAN);

        // If the form does not contain any fields do not proceed.
        if (empty($form['fields'])) {
            _e('No form fields.', 'erforms');
            return;
        }


        if (!empty($_POST['action']) && $_POST['action'] == 'erf_submit_form' && !empty($_POST['erform_id']) && absint($_POST['erform_id']) == $id) {
            $response= $this->submit_form($form);            
            if (!empty($response)) {
                $success = true;
            }
        }

        if (empty($success)) {
            $success = $this->show_success_message($form);
        }

        $this->enqueues($form);
        $form_html = $form['form_html'];
        $show_form = apply_filters('erf_form_render_allowed', true, $form);
        if ($show_form) {
            $form_template = $form['type'] == 'reg' ? 'register.php' : 'contact.php';
            include 'html/' . $form_template;
        }
    }

    /*
     * Handles form validation & submission
     */
    private function submit_form($form){
        if (empty($form)){
            $this->errors= array('erf_form_error',__('No such Form exists','erforms'));
            return false;
        }
        $request_data = erforms_sanitize_request_data($form['fields']);
        $submission_id = isset($request_data['submission_id']) ? absint($request_data['submission_id']) : 0;
        if (!empty($submission_id)) { // Set edit submission status
            $this->edit_sub_status = true;
        }
        $this->errors = $this->validate($request_data,$form);
        if (!empty($this->errors)) {
            return false;
        }
        $errors = apply_filters('erf_form_validated', $form['id'], $request_data);
        if (!empty($errors)) {
            $this->errors = $errors;
            return false;
        }
        $success_message = do_shortcode(wpautop(apply_filters('erforms_parse_success_message', $form['success_msg'], $this->submission_id)));
        $response = array(
            'success' => true,
            'msg' => $success_message,
            'form_id' => $form['id'],
            'submission_id' => $this->submission_id
        );
        $redirect_to = $form['redirect_to'];
        if (!empty($redirect_to)) {
            $response['redirect_to'] = $redirect_to;
        } else {
            // After login URL
            $user = wp_get_current_user();
            if (empty($user->ID)) {  // Only for non logged in users
                if (!empty($form['auto_user_activation']) && !empty($form['auto_login'])) {
                    $submission = erforms()->submission->get_submission($this->submission_id);
                    if(!empty($submission['user'])){
                        $user = get_user_by('ID', $submission['user']['ID']);
                        $redirect_to = apply_filters('erf_login_redirect', '', "", $user);
                        if (!empty($redirect_to)) {
                            $response['redirect_to'] = $redirect_to;
                        }
                    }
                    
                }
            }
        }

        $response = apply_filters('erf_ajax_before_sub_response', $response);
        return $response;
    }
    
    /* 
     * Validates form while submission
     * Make sure to pass only sanitized data
     */
    public function validate($data,$form) {
        if (!is_user_logged_in()) { // Check captcha only for guest users
            $g_r_captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : 'wrong captcha';
            if ($form['recaptcha_enabled']) {
                $valid = erforms_validate_captcha($g_r_captcha);
                if (!$valid) {
                    return array(array('form_error', __('Invalid/Expired Recapctha','erforms')));
                }
            }
        }

        $errors = apply_filters('erforms_before_form_processing', array(), $form);
        if (!empty($errors) && is_array($errors)) {
            return $errors;
        }

        $errors = $this->validator->validate($form, $data);

        return $errors;
    }
    
    /*
     * Check if success message has to be shown (Only for non ajax submissions)
     */
    public function show_success_message($form) {
        if (empty($_GET['erf_form']) || empty($_GET['erf_auto_login']))
            return false;

        $form_id = absint($_GET['erf_form']);
        if ($form['id'] != $form_id)
            return false;
        $auto_login = $form['auto_login'];
        if (!empty($auto_login)) {
            return true;
        }
    }


    /*
     * Called after form validation.
     * Saves submission data
     */
    public function form_validated($form_id, $data) {
        $submission_id = isset($data['submission_id']) ? absint($data['submission_id']) : 0;
        $data = apply_filters('erf_before_submission_save', $data, $form_id);
        if (!empty($submission_id)) // Edit submission
        {
            $errors = erforms()->submission->save($form_id, $data, true);
        } 
        else  // Inserts new submission
        {
            $errors = erforms()->submission->save($form_id, $data);
        }

        return $errors;
    }

    /*
     * Called after submission save
     * Registers new user into WordPress.
     * Also map field values to user meta (If configured)
     */
    public function after_submission_insertion($errors, $submission, $data) {
        $sub_model = erforms()->submission;
        $form_model = erforms()->form;
        $form = $form_model->get_form($submission['form_id']);
        if ($form['type'] == "reg") { // Handling of registration forms
            $user = 0;
            $id = 0;
            // Get mapping for user meta fields if any
            $user_field_map = erforms_filter_user_fields($form['id'], $submission['fields_data']);
            // Avoid user registration process if user already logged in
            if (!is_user_logged_in()) {
                $email_or_username = $user_field_map['user_email'];

                if (isset($user_field_map['password'])) {
                    // Silently creates user  
                    $username = isset($user_field_map['username']) ? $data[$user_field_map['username']] : $data[$email_or_username];
                    $id = wp_create_user($username, $data[$user_field_map['password']], $data[$email_or_username]);
                } else {
                    // Register user and sends random password via email notification
                    $id = register_new_user($data[$email_or_username], $data[$email_or_username]);
                }

                if (is_wp_error($id)) {
                    // In case something goes wrong delete the submission
                    wp_delete_post($submission['id'], true);
                    $error_code = $id->get_error_code();
                    if ($error_code == 'existing_user_login') {
                        $email_or_username = 'username_error';
                    }

                    $errors[] = array($email_or_username, $id->get_error_message($id->get_error_code()));
                    return $errors;
                } else {
                    $selected_role = erforms_get_selected_role($submission['form_id'], $data);
                    if (!empty($selected_role)) { // Means user has selected any role
                        $user_model = erforms()->user;
                        $user_model->set_user_role($id, $selected_role);
                    }
                    do_action('erf_user_created', $id, $form['id'], $submission['id']);
                }
            } else {
                // Get user details
                $user = wp_get_current_user();
                $id = $user->ID;
            }
            if(!$this->edit_sub_status){
                $sub_model->update_meta($submission['id'], 'user', $id);
            }
            
            foreach ($user_field_map as $req_key => $meta_key) {
                $is_primary_key = in_array($meta_key, erforms_primary_field_types());
                if (isset($data[$req_key]) && !$is_primary_key) {
                    update_user_meta($id, $meta_key, $data[$req_key]);
                }
            }
        }
        else
        {
            $user = wp_get_current_user();
            if(!$this->edit_sub_status){
                if(!empty($user->ID)){
                    $user = wp_get_current_user();
                    $sub_model->update_meta($submission['id'], 'user', $user->ID);
                }
            }
            // Get mapping for user meta fields if any
            if(!empty($user->ID)){
                $user_field_map = erforms_filter_user_fields($form['id'], $submission['fields_data']);
                foreach ($user_field_map as $req_key => $meta_key) {
                    if (isset($data[$req_key])) {
                        update_user_meta($user->ID, $meta_key, $data[$req_key]);
                    }
                }
            }      
        }
        $this->submission_id = $submission['id'];
        return $errors;
    }
    
    
    /*
     * Handles ajax form submission
     */
    public function ajax_submit_form() {
        $form_id = absint($_POST['erform_id']);
        $form = erforms()->form->get_form($form_id);
        $response= $this->submit_form($form);
        if(empty($response)){ // If empty then show errors
            wp_send_json_error($this->errors);
        }
        wp_send_json($response);
    }

    /* 
     * Filter to return default registration URL for WordPress
     */
    public function register_url($url) {
        $post_id = $this->options['default_register_url'];
        if (empty($post_id))
            return $url;
        $post = get_post($post_id);
        if (empty($post))
            return $url;

        $url = home_url("?p=" . $post_id);
        return $url;
    }

    /*
     * For admin only (Allows form layout settings change from front end)
     */
    public function change_form_layout() {
        if (!current_user_can('administrator') || empty($_POST['change_form_layout_nonce']))
            return;

        $change_form_layout_nonce = $_POST['change_form_layout_nonce'];
        if (!wp_verify_nonce($change_form_layout_nonce, 'change_form_layout_nonce'))
            return;

        $form_id = absint($_POST['erform_id']);
        if (empty($form_id))
            return;

        $form_model = erforms()->form;
        $form = $form_model->get_form($form_id);
        if (empty($form))
            return;

        $layout = sanitize_text_field($_POST['layout']);
        $label_position = sanitize_text_field($_POST['label_position']);


        $form['layout'] = $layout;
        $form['label_position'] = $label_position;
        $form_model->update_form($form);
        $response = array(
            'success' => true,
        );

        wp_send_json($response);
        die;
    }

    /*
     * Shows Form Preview
     */
    public function preview() {
        if (empty($_GET['erform_id']))
            return;
        $form_id = absint($_GET['erform_id']);
        if (empty($form_id))
            return;

        ob_start();
        
        echo do_shortcode('[erforms id="' . $form_id . '"]');

        return ob_get_clean();
    }

    /*
     * Edit submission
     */
    private function edit_submission($sub_id, $form_atts) {
        $submission = erforms()->submission->get_submission($sub_id);
        if (empty($submission) || empty($form_atts['id']))
            return;
        
        // Make sure Form exists and Form ID matches with submission ID
        $form = erforms()->form->get_form($form_atts['id']);
        if (empty($form))
            return;
        wp_enqueue_script('erf-edit-submission', ERFORMS_PLUGIN_URL . 'assets/js/erforms-edit-submission.js');
    }

    /*
     * Called just before rendering the form
     */
    public function form_render_allowed($show_form, $form) {
        // Check if form is password protected
        if (!empty($form['en_pwd_restriction'])) {
            if (is_user_logged_in() && empty($form['pwd_res_en_logged_in'])) { // Password protection disabled for logged in users 
                return $show_form;
            }

            $password_error = false;
            if (isset($_POST['erform_id']) && $_POST['erform_id'] == $form['id']) {
                if (isset($_POST['erf_answer'])) {
                    if (strtolower(trim($_POST['erf_answer'])) == strtolower(trim($form['pwd_res_answer']))) {
                        return $show_form;
                    } else {
                        $password_error = true;
                    }
                }
            }

            include('html/password_protection.php');
            return false;
        }
        return $show_form;
    }

    /*
     * Renders frontend my account.
     */
    public function my_account($atts) {
        global $wp;
        ob_start();
        
        if (!is_user_logged_in()) { // Show Login if user is not already logged in 
            return do_shortcode('[erforms_login]');
        }
        $this->enqueues();

        wp_enqueue_script('erf-edit-submission', ERFORMS_PLUGIN_URL . 'assets/js/erforms-edit-submission.js');
        wp_enqueue_script('erf-my-account', ERFORMS_PLUGIN_URL . 'assets/js/erforms-my-account.js', array('jquery'));
        wp_enqueue_script('erf-pwd-meter', ERFORMS_PLUGIN_URL . 'assets/js/password.min.js', array('jquery'));
        wp_enqueue_style('erf-pwd-meter', ERFORMS_PLUGIN_URL . 'assets/css/password.min.css', array('jquery'));
        wp_enqueue_script('erf-print-submission', ERFORMS_PLUGIN_URL . 'assets/js/printThis.js');
        $current_user = wp_get_current_user();

        /* Pagination related */
        $per_page = 10;
        $paged = isset($_GET['erf_paged']) ? absint($_GET['erf_paged']) : 0;
        $submissions = erforms()->submission->get_submissions_from_user($current_user->ID);
        $total_submissions = count($submissions);
        $offset = $paged * $per_page;
        $show_next = ($offset + $per_page) < $total_submissions ? true : false;
        $show_prev = $offset > 0 ? true : false;

        $submissions = array_slice($submissions, $offset, $per_page, true);
        if(!empty($atts['wc'])){
            include('html/wc_my_account.php');
        }
        else
        {
          include('html/my_account.php');
        }
        return ob_get_clean();
    }
   
}
