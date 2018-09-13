<?php
/**
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Addon {

	/**
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Maybe load overview page.
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Determing if the user is viewing the overview page, if so, party on.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Check what page we are on.
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		// Only load if we are actually on the overview page.
		if ( 'erforms-addon' === $page ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
			add_action( 'erforms_admin_page',    array( $this, 'output'   ) );
		}
	}

	

	/**
	 * Enqueue assets for the Help page.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_style('erf-admin-style',ERFORMS_PLUGIN_URL.'assets/admin/css/style.css');
	}

	/**
	 * Build the output for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function output() { 
        $success_msg= '';    
        if(isset($_POST['erf_report_issue']))
        {
            $email_model= new ERForms_Emails();
            $name= sanitize_text_field($_POST['name']);
            $subject= sanitize_text_field($_POST['subject']);
            $email= sanitize_email($_POST['email']);
            $message= $_POST['message'];
            $email_model->report_issue($name,$email,$subject,$message);
            $success_msg= 'Issue has been reported successfully. Our support team will contact you within 12 hours.';
        }
                  
        include 'html/addon.php';
		
	}
}
new ERForms_Addon;
