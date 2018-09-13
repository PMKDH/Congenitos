<?php

/**
 * Main Submission handler
 *
 * Contains a bunch of helper methods as well.
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Submission extends ERForms_Post {

    protected $post_type = 'erforms_submission';

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Register erforms custom post type
        $this->register_cpt();
        add_action('erf_post_submission', array($this, 'post_submission'), 1);
        add_action('erf_post_edit_submission', array($this, 'post_edit_submission'), 1);
        add_action('erf_send_data_to_external_url', array($this, 'send_to_external_url'));
        add_action('erf_submission_report', array($this, 'send_submission_report'), 10, 2);
        add_action('wp_ajax_erf_submission_export', array($this, 'export_submission'));
        add_action('wp_ajax_erforms_get_submission', array($this, 'get_submission_ajax'));
        add_action('wp_ajax_erforms_get_submission_html', array($this, 'get_submission_html_ajax'));
        add_action('erf_label_assigned',array($this,'label_assigned'),10,3);
        add_action('erf_label_revoked',array($this,'label_revoked'),10,3);
    }

    /**
     * Registers the custom post type to be used for forms.
     *
     * @since 1.0.0
     */
    public function register_cpt() {
        // Custom post type arguments, which can be filtered if needed
        $args = apply_filters(
                'erforms_post_type_args', array(
            'labels' => array(),
            'public' => false,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_admin_bar' => false,
            'rewrite' => false,
            'query_var' => false,
            'can_export' => false,
            'supports' => array('title', 'revisions'),
                )
        );

        // Register the post type for Submission
        register_post_type('erforms_submission', $args);
    }

    // Saves submission data
    public function save($form_id, $data, $edit = false) {
        $form = erforms()->form->get_form($form_id);
        if (empty($form))
            return false;

        $errors = array();
        $fields_schema = $form['fields'];

        $form_type = "contact";   // 0 for "Contact Form". 1 for "Registration Form"

        if (empty($fields_schema))
            return false;

        $old_submission = null;
        $non_input_fields = erforms_non_input_fields();
        if ($edit) {
            $submission_id = absint($data['submission_id']);
            $old_submission = $this->get_submission($submission_id);

            /*
             *  Prefill $data with old submission values for fields which are not editable. 
             *  
             */
            if(!erforms_is_user_admin()){
                foreach ($old_submission['fields_data'] as $f_data) {
                    if (!in_array($f_data['f_name'], $form['edit_fields'])) {
                        if (isset($data[$f_data['f_name']])) {
                            $data[$f_data['f_name']] = $f_data['f_val'];
                        } else if (isset($_FILES[$f_data['f_name']])) {
                            unset($_FILES[$field->name]);
                            $data[$f_data['f_name']] = $f_data['f_val'];
                        }
                    }
                    // Avoid overriding file in case file was already uploaded and not given while edit submission   
                    if ($f_data['f_type'] == 'file' && !empty($f_data['f_val'])) {
                        if(!ERForms_Validation::is_file_uploaded($f_data['f_name'])){
                            $data[$f_data['f_name']] = $f_data['f_val'];
                        }
                    }
                }
            }
            
        }
        
        $submission = array('fields_data' => array());
        $meta = array(); // Submission meta options
        $meta['erform_form_id'] = $form_id;
        $meta['erform_attachments'] = array();
        foreach ($fields_schema as $field) {
            $field = (object) $field;

            if (!isset($field->subtype)) {
                $field->subtype = $field->type;
            }
            //Avoid saving sensitive information such as Password
            if ($field->subtype == "password")
                continue;
            if (in_array($field->type, $non_input_fields))
                continue;

            if (isset($data[$field->name])) {

                if ($field->subtype == 'user_email') {
                    $form_type = "reg";
                    if (!empty($old_submission)) {
                        $old_user = $old_submission['user'];
                        if (!empty($old_user)) {
                            $submission['fields_data'][] = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => $old_user['user_email'], 'f_type' => $field->subtype);
                            continue;
                        }
                    } else {
                        $user = wp_get_current_user();
                        if (!empty($user->ID)) {
                            $submission['fields_data'][] = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => $user->user_email, 'f_type' => $field->subtype);
                            continue;
                        }
                    }
                }


                if ($field->type == 'date') {
                    $timestamp = strtotime($data[$field->name]);
                    $date = '';
                    if (!empty($timestamp))
                        $date = date(erforms_php_date_format(), $timestamp);

                    $submission['fields_data'][] = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => $date, 'f_type' => $field->subtype, 'f_timestamp' => $timestamp);
                    continue;
                }

                if (!empty($field->enableUnique) && !empty($data[$field->name])) {
                    $meta['erform_' . $field->name] = $data[$field->name];
                }

                $field_data = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => $data[$field->name], 'f_type' => $field->subtype);
                if (!empty($field->user_roles)) {
                    $field_data['user_role'] = 1;
                }

                // Entity Data
                if (!empty($field->entityType) && !empty($field->entityProperty)) {
                    $field_data = array_merge($field_data, array('f_entity' => $field->entityType, 'f_entity_property' => $field->entityProperty));
                    if ($field->entityType == 'user' && $field->entityProperty == 'username') {
                        $user = wp_get_current_user();
                        if (!empty($user->ID)) {
                            $field_data['f_val'] = $user->user_login;
                        }
                    }
                }

                $submission['fields_data'][] = $field_data;
            } else if (isset($_FILES[$field->name])) {
                if (!ERForms_Validation::is_file_uploaded($field->name))
                    continue;
                // These files need to be included as dependencies when on the front end. (In case admin-ajax call is not happening due to javascript conflict.)
                require_once(ABSPATH.'wp-admin/includes/image.php');
                require_once(ABSPATH.'wp-admin/includes/file.php');
                require_once(ABSPATH.'wp-admin/includes/media.php');
                
                add_filter('upload_dir', array($this,'submission_upload_dir'));
                $attachment_id = media_handle_upload($field->name, 0);
                remove_filter('upload_dir', array($this,'submission_upload_dir'));
                if (is_wp_error($attachment_id)) {
                    return $attachment_id;
                }
                $meta['erform_attachments'][] = array('f_label' => $field->label, 'f_val' => $attachment_id, 'f_name' => $field->name);
                $submission['fields_data'][] = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => $attachment_id, 'f_type' => $field->subtype);
            }
            else{
                $submission['fields_data'][] = array('f_name' => $field->name, 'f_label' => $field->label, 'f_val' => '', 'f_type' => $field->subtype);
            }
        }

        if (!empty($meta['erform_attachments'])) {
            $meta['erform_has_attachments'] = 1;
        } else {
            unset($meta['erform_attachments']);
        }
        $meta = apply_filters('erf_before_submission_insert', $meta, $form_id, $data);
        // Create post object
        $args = array(
            'post_title' => wp_strip_all_tags('Submission for : #' . $form_id),
            'post_content' => erforms_encode($submission),
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'meta_input' => $meta
        );
        if($edit) // Edit submission
        {
            $args['ID'] = $submission_id;
            $current_time = current_time('mysql');
            wp_update_post($args);
            $id = $submission_id;
        } 
        else // Inserts new submission
        {
            $id = $this->add($args);
        }
        
        if (is_wp_error($id)) 
        {
            return false;
        }
        
        if ($id > 0) 
        {
            $submission = $this->get_submission($id);
            $errors = apply_filters('erf_after_submission_insertion', $errors, $submission, $data);
            if (empty($errors)) 
            {
                if ($edit){
                    do_action('erf_post_edit_submission', $submission);
                }
                else{
                    do_action('erf_post_submission', $submission);
                }
            }
            return $errors;
        }

        return false;
    }

    /**
     * Add new form.
     *
     * @since 1.0.0
     *
     * @param string $title
     * @param array $args
     * @param array $data
     *
     * @return mixed
     */
    public function add($args = array(), $data = array()) {
        // Merge args and create the form
        $form = wp_parse_args(
                $args, array(
            'post_status' => 'publish',
            'post_type' => $this->post_type,
                )
        );

        $form_id = wp_insert_post($form);
        return $form_id;
    }

    /*
     *  Attaches unique submission IDs
     */

    private function update_unique_id($submission) {
        $form_model = erforms()->form;
        $form = $form_model->get_form($submission['form_id']);
        $form_type = $form_model->get_meta($submission['form_id'], 'type', true);

        // Check form configuration if Unique ID enabled
        $unique_id_enabled = $form['enable_unique_id'];

        if (empty($unique_id_enabled))
            return;
        $unique_id = '';
        $unique_id_gen_method = $form['unique_id_gen_method'];

        if ($unique_id_gen_method == 'auto') {
            $unique_id = wp_generate_password(10, false, false);
        } else if ($unique_id_gen_method == 'configure') {
            $current_index = absint($form['unique_id_index']);
            $unique_id_prefix = $form['unique_id_prefix'];
            $unique_id_padding = $form['unique_id_padding'];
            if (empty($unique_id_padding))
                $unique_id_padding = 1;
            $unique_id = $unique_id_prefix . ($current_index + $unique_id_padding);
            $form['unique_id_index'] = $current_index + $unique_id_padding;
            $form_model->update_form($form);
        }

        $this->update_meta($submission['id'], 'unique_id', $unique_id);
    }

    /*
     * Checks for unique values for submissions related to a single form
     */

    public function is_unique_value($value, $name, $form_id, $submission_id = 0) {
        $name = 'erform_' . $name;
        $meta_query_args = array(
            'relation' => 'AND', // Optional, defaults to "AND"
            array(
                'key' => $name,
                'value' => $value,
                'compare' => '='
            ),
            array(
                'key' => 'erform_form_id',
                'value' => $form_id,
                'compare' => '='
            )
        );

        $args = array(
            'meta_query' => $meta_query_args
        );
        if (!empty($submission_id)) {
            $args['exclude'] = array($submission_id);
        }
        $submissions = $this->get('', $args);

        if (empty($submissions))
            return true;
        return false;
    }

    /*
     * Returns submission
     * Accepts WP_Post or post_id
     */

    public function get_submission($post) {
        if (empty($post))
            return false;

        if (!($post instanceof WP_Post)) {
            $post = $this->get($post);
        }
        if (empty($post))
            return false;

        $submission = erforms_decode($post->post_content);
        $submission['id'] = $post->ID;
        $all_meta = $this->get_meta($post->ID);
        $meta_keys = $this->meta_keys();

        foreach ($all_meta as $key => $meta) {
            $key = str_replace('erform_', '', $key);
            if (in_array($key, $meta_keys)) {
                $submission[$key] = maybe_unserialize($meta[0]);
            }
        }
        $date_format = get_option('date_format');
        $submission['created_date'] = get_the_date($date_format . ' g:i a', $post->ID);
        $submission['modified_date'] = get_the_modified_date($date_format . ' g:i a', $post->ID);

        if (isset($submission['user']) && !empty($submission['user'])) {
            $user = get_userdata($submission['user']);

            if (!empty($user)) {
                $submission['user'] = $user->to_array();
                $is_active = erforms()->user->get_meta($user->ID, 'active');
                if ($is_active === '0') {
                    $status = 0;
                } else {
                    $status = 1;
                }
                $submission['user_active'] = $status;

                // User role data
                $submission['user_role'] = $user->roles;
            }
            else
            {
                $submission['user']= null;
            }
        }
        // Parsing fields data
        return $submission;
    }

    public function get_submissions_by_form($form_id) {
        $meta_query_args = array(
            array(
                'key' => 'erform_form_id',
                'value' => $form_id,
                'compare' => '='
            )
        );

        $args = array('meta_query' => $meta_query_args);
        $posts = $this->get('', $args);
        $submissions = array();

        foreach ($posts as $post) {
            $submissions[] = $this->get_submission($post->ID);
        }
        return $submissions;
    }

    /*
     * Returns all submissions from a single user
     */

    public function get_submissions_from_user($user_id, $exclude = array(), $form_id = 0, $query_args = array()) {
        $meta_query_args = array(
            array(
                'key' => 'erform_user',
                'value' => $user_id,
                'compare' => '='
            )
        );

        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => $meta_query_args
        );

        $args = wp_parse_args($query_args, $args);
        //  erforms_debug($args); die;
        $posts = $this->get('', $args);
        $submissions = array();

        foreach ($posts as $post) {
            if (!empty($exclude) && in_array($post->ID, $exclude)) {
                continue;
            }
            $submission = $this->get_submission($post);
            if (!empty($form_id) && $submission['form_id'] != $form_id) {
                continue;
            }

            $submissions[] = $submission;
        }

        return $submissions;
    }

    public function update($submission) {
        if (empty($submission))
            return false;

        $all_meta = $this->meta_keys();
        foreach ($all_meta as $meta) {
            if (isset($submission[$meta])) {
                $this->update_meta($submission['id'], $meta, $submission[$meta]);
                unset($submission[$meta]);
            }
        }

        // Update post 37
        $post = array(
            'ID' => $submission['id'],
            'post_content' => erforms_encode($submission),
        );

        wp_update_post($post);
    }

    /*
     * Will be called once by WordPress Single Event Scheduler
     */

    public function send_to_external_url($id) {
        $submission = $this->get_submission($id);
        $form_model = erforms()->form;
        $form = $form_model->get_form($submission['form_id']);
        $external_url = $form['external_url'];
        $external_url_enabled = $form['enable_external_url'];

        if (empty($external_url_enabled))
            return;

        if (ERForms_Validation::url($external_url)) {
            $submission = apply_filters('erf_before_posting_external_url', $submission);
            $response = wp_remote_post($external_url, array(
                'method' => 'POST',
                'timeout' => 30,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $submission,
                'cookies' => array())
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $submission['external_url'] = array('status' => false, 'url' => $external_url, 'error' => $error_message);
            } else {
                $submission['external_url'] = array('status' => true, 'url' => $external_url);
            }
        } else {
            $submission['external_url'] = array('status' => false, 'url' => $external_url, 'error' => __('Seems to be an invalid URL.', 'erforms'));
        }

        $this->update($submission);
    }

    public function post_submission($submission) {
        $id = $submission['id'];
        $form_id = $submission['form_id'];
        // Checks for Unique ID
        $this->update_unique_id($submission);
        // Seperating thread for sending data to external URL.
        $form_model = erforms()->form;
        $form = $form_model->get_form($form_id);
        $external_url_enabled = $form['enable_external_url'];
        if (!empty($external_url_enabled)) {
            //$this->send_to_external_url($id);
            wp_schedule_single_event(time() + 60, 'erf_send_data_to_external_url', array($id));
        }
        do_action('erf_async_post_submission',$submission['id']);
        //wp_schedule_single_event(current_time('timestamp') + 10, 'erf_async_post_submission', array($submission['id']));
    }

    public function post_edit_submission($submission) {
        do_action('erf_async_post_edit_submission',$submission['id']);
        //wp_schedule_single_event(current_time('timestamp') + 10, 'erf_async_post_edit_submission', array($submission['id']));
        $this->add_note($submission, __('Submission Edited', 'erforms'));
    }

    public function submissions_data_for_chart($form_id, $days = 0) {
        $posts = $this->get_submissions_by_days($form_id, $days, 'ASC');
        $chart_data = "['Date', 'Number of Submissions'],";
        $temp = array();
        foreach ($posts as $post) {
            $date = date('Y-m-d', strtotime($post->post_date));
            if (isset($temp[$date])) {
                $temp[$date] = $temp[$date] + 1;
            } else {
                $temp[$date] = 1;
            }
        }

        foreach ($temp as $key => $val) {
            $chart_data .= '["' . $key . '",' . $val . '],';
        }

        if (empty($posts))
            return false;

        return $chart_data;
    }

    public function meta_keys() {
        $meta_input = array('form_id', 'user', 'unique_id', 'submission_notes', 'amount', 'payment_status', 'plan', 'currency', 'payment_method', 'payment_invoice','attachments','payment_logs');
        return $meta_input;
    }

    public function add_note($submission, $text) {
        if (is_scalar($submission)) {
            $submission = erforms()->submission->get_submission($submission);
        }

        $notes = $this->get_meta($submission['id'], 'submission_notes');
        if (empty($notes))
            $notes = array();

        $note = array();
        $note['time'] = current_time('mysql', 0);
        $current_user = wp_get_current_user();
        $note['by'] = $current_user->display_name;
        $note['user'] = $current_user->ID;
        $note['text'] = $text;
        $notes[] = $note;
        $this->update_meta($submission['id'], 'submission_notes', $notes);
    }

    public function get_revisions($submission_id) {
        $posts = wp_get_post_revisions($submission_id);
        if (empty($posts))
            return array();
        $submissions = array();
        foreach ($posts as $post) {
            $submissions[] = $this->get_submission($post);
        }
        return $submissions;
    }

    /**
     * 
     * @param type $form_id
     * @param type $days
     * @return array|Posts
     */
    protected function get_submissions_by_days($form_id, $days = 0, $order = 'DESC') {
        if (empty($form_id))
            return array();

        $args = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => $order);
        $args['meta_query'] = array(
            array(
                'key' => 'erform_form_id',
                'value' => $form_id,
                'compare' => '='
            )
        );
        switch ($days) {
            case 1: $args['date_query'] = array(array('after' => '24 hours ago'));
                break;
            case 2: $args['date_query'] = array(array('after' => '48 hours ago'));
                break;
            case 7: $args['date_query'] = array(array('after' => '1 week ago'));
                break;
            case 30: $args['date_query'] = array(array('after' => '1 month ago'));
                break;
            case 60: $args['date_query'] = array(array('after' => '2 month ago'));
                break;
            case 0:
            default: $args['date_query'] = array(array('after' => 'today'));
        }

        $posts = $this->get('', $args);
        return $posts;
    }

    /**
     * 
     * @param type $form_id
     * @param type $index
     */
    public function send_submission_report($form_id, $index) {
        $form = erforms()->form->get_form($form_id);
        if (empty($form)) {
            erforms_delete_scheduled_report(array('form_id' => (int) $form_id, 'index' => (int) $index));
            return false;
        }


        $reports = $form['reports'];

        if (!isset($reports[$index])) { //Return if report is not available.
            erforms_delete_scheduled_report(array('form_id' => (int) $form_id, 'index' => (int) $index));
            return false;
        }

        $report = $reports[$index];

        if (empty($report['active'])) { //Return as report is inactive
            return false;
        }

        $posts = $this->get_submissions_by_days($form_id, $report['range']);
        $posts = apply_filters('erf_before_report_send', $posts, $form_id, $index);

        if (empty($posts))
            return false;

        $keys = array();
        $report_fields = $report['fields'];
        if (empty($report_fields))
            return false; // No data to export
        foreach ($report_fields as $rf) {
            if (!empty($rf['included'])) {
                array_push($keys, $rf['alias']);
            }
        }

        $data = array();
        if (!empty($keys)) { // Push column keys 
            array_push($data, $keys);
            foreach ($posts as $index => $post) {  // Looping thourgh all the filtered posts
                $submission = $this->get_submission($post->ID); // Load submission
                $row = $this->get_row_by_report_fields($submission, $report_fields);
                if (!empty($row)) {
                    array_push($data, $row);
                }
            }
        }

        if (empty($data))
            return false;

        $export_model = new ERForms_Submission_Export('csv', false);
        $path = $export_model->export($form_id, $data);
        $email_model = new ERForms_Emails();
        $email_model->send_submission_report($report, $path);
    }

    /*
     * Load submission data only for reported fields in given key order
     */

    public function get_row_by_report_fields($submission, $report_fields) {
        $data = array();
        $formatter = new ERForms_Submission_Formatter('report', $submission);
        $submission = $formatter->format();
        $submission['indexed_field_data'] = array();

        foreach ($submission['fields_data'] as $fd) { // Reformatting submission field array for faster searching
            $submission['indexed_field_data'][$fd['f_name']] = $fd['f_val'];
        }
        foreach ($report_fields as $f_name => $rf) {
            if (!empty($rf['included'])) {

                if (isset($submission['indexed_field_data'][$f_name])) { // Check for field name in submission fields data
                    $data[$rf['alias']] = $submission['indexed_field_data'][$f_name];
                } elseif (isset($submission[$f_name])) { // Again Checking in submission
                    $data[$rf['alias']] = $submission[$f_name];
                } else {
                    $data[$rf['alias']] = '';
                }
            }
        }
        return $data;
    }

    public function export_submission($form_id=0,$search='',$label_filter='') {
        if (!current_user_can('administrator'))
            wp_die('You are not allowed to access this page');

        $form_id = empty($form_id) ? absint(urldecode($_REQUEST['erform_id'])) : absint($form_id);
        $search = empty($search) ? sanitize_text_field(urldecode($_REQUEST['search'])) : $search;
        $label_filter= empty($label_filter) ? absint(urldecode($_REQUEST['label_filter'])) : $label_filter;
       
        if (empty($form_id))
            wp_die('No Such Form exists');
        
        $user = get_user_by('email', $search);
        $meta_input = array();
        if (!empty($user)) {
            $meta_input = array(
                'relation' => 'AND',
                array(
                    'key' => 'erform_form_id',
                    'value' => $form_id
                ),
                array(
                    'key' => 'erform_user',
                    'value' => $user->ID
                )
            );
        } else if ($search) {
            $meta_input = array(
                'relation' => 'OR',
                array(
                    'key' => 'erform_form_id',
                    'value' => $form_id
                ),
                array(
                    'key' => 'erform_unique_id',
                    'value' => $search
                )
            );
        } else {
            $meta_input = array(
                array(
                    'key' => 'erform_form_id',
                    'value' => $form_id
                )
            );
        }

        $post_query = array(
            'orderby' => 'ID',
            'order' => 'DESC',
            'nopaging' => true,
            'meta_query' => $meta_input,
            'post_type' => $this->post_type
        );
        
        if(!empty($label_filter)){
            $post_query['tax_query']= array(
                                        array('taxonomy' => erforms()->label->get_tax_type(),
                                              'field'    => 'term_id',
                                              'terms'    => $label_filter)
                                      );
        }
                    
        if (!empty($search)) {
            $post_query['s'] = $search;
        }

        $data_query = new WP_Query($post_query);
        $posts = $data_query->posts;

        $fields = erforms_get_report_fields($form_id);
        $keys = array_values($fields);

        // Formatting fields 
        $report_fields = array();
        foreach ($fields as $f_name => $f_label) {
            $report_fields[$f_name] = array('alias' => $f_label, 'included' => 1, 'label' => $f_label);
        }
        $data = array();
        array_push($data, $keys); // CSV columns

        foreach ($posts as $index => $post) {  // Looping thourgh all the filtered posts
            $submission = $this->get_submission($post->ID); // Load submission
            $row = $this->get_row_by_report_fields($submission, $report_fields);
            if (!empty($row)) {
                array_push($data, $row);
            }
        }
        $export_model = new ERForms_Submission_Export('csv', false);
        $path = $export_model->export($form_id, $data);
        erforms_download_file($path, 'text/csv; charset=utf-8', true);
        wp_die();
    }

    private function remove_sensitive_submission_info($submission) {
        $sensitive_info = array();
        foreach ($sensitive_info as $info) {
            if (isset($submission[$info])) {
                unset($submission[$info]);
            }
        }
        return $submission;
    }

    public function get_submission_ajax() {
        $form_id = absint($_POST['form_id']);
        $submission_id = absint($_POST['submission_id']);
        $form = erforms()->form->get_form($form_id);
        $submission = erforms()->submission->get_submission($submission_id);

        if (empty($form) || empty($submission)) {
            wp_send_json_error();
        }

        if (erforms_edit_permission($form, $submission)) {
            wp_send_json_success($submission);
        }
        wp_send_json_error();
    }

    public function get_submission_html_ajax() {
        $form_id = absint($_POST['form_id']);
        $submission_id = absint($_POST['submission_id']);
        $form = erforms()->form->get_form($form_id);
        $submission = erforms()->submission->get_submission($submission_id);
        if (empty($form) || empty($submission)) {
            wp_send_json_error();
        }

        if (erforms_submission_view_permission($form, $submission)) {
            $html = erforms_front_submission_table($submission);
            wp_send_json_success(array('html' => $html));
        }
        wp_send_json_error();
    }
    
    public function submission_upload_dir($pathdata){
        $options= erforms()->options->get_options();
        $dir= $options['upload_dir'];
        if(empty( $pathdata['subdir'])) 
        {
            $pathdata['path']   = $pathdata['path'] . '/'.$dir;
            $pathdata['url']    = $pathdata['url'] . '/'.$dir;
            $pathdata['subdir'] = '/'.$dir;
        } 
        else 
        {
            $new_subdir = '/'.$dir. $pathdata['subdir'];
            $pathdata['path']   = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['path'] );
            $pathdata['url']    = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['url'] );
            $pathdata['subdir'] = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['subdir'] );
        }
        return $pathdata;
    }
    
    public function attachments_by_form($form_id,$exclude = array(),$query_args = array()){
        $meta_query_args = array(
            'relation'=>'AND',
            array(
                'key' => 'erform_attachments',
                'compare' => 'EXISTS'
            ),
            array(
                'key'=>'erform_form_id',
                'value'=>$form_id
            )
        );

        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => $meta_query_args
        );

        $args = wp_parse_args($query_args, $args);
        //  erforms_debug($args); die;
        $posts = $this->get('', $args);
        $submissions = array();

        foreach ($posts as $post) {
            if (!empty($exclude) && in_array($post->ID, $exclude)) {
                continue;
            }
            $submission = $this->get_submission($post);
            $submissions[] = $submission;
        }

        return $submissions;
    }
   
    public function label_assigned($sub_id,$name,$taxonomy){
        $label= erforms()->label->get_label_by_name($name);
        $status= '<div><span class="label erf-label-'.sanitize_title($label['name']).'">'.ucwords($name).'</span>';
        $msg= sprintf(__('%s status added.', 'erforms'),$status);
        $msg.='</div>';
        $this->add_note($sub_id, $msg);
    }
    
    public function label_revoked($sub_id,$name,$taxonomy){
        $label= erforms()->label->get_label_by_name($name);
        $status= '<div><span class="label erf-label-'.sanitize_title($label['name']).'">'.ucwords($name).'</span>';
        $msg= sprintf(__('%s status removed.', 'erforms'),$status);
        $msg.='</div>';
        $this->add_note($sub_id, $msg);
    }
    
    public function save_submission($submission){
        if(empty($submission['id'])){
            return false;
        }
        $args = array(
            'post_content' => erforms_encode($submission),
            'ID'=>$submission['id']
        );
        return wp_update_post($args);
    }
}
