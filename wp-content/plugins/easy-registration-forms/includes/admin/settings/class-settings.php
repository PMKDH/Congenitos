<?php
/**
 * Form Settings
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Settings {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
	}

	/**
	 *
	 * @since 1.0.0
	 */
	public function init() {
                
		// Check what page we are on
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
                
		// Only load if we are actually on the builder
		if ( 'erforms-settings' === $page ) {
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
                    add_action( 'erforms_admin_page',array( $this, 'output') );
		}
	}
        
        /**
	 * Enqueue assets for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {
            // Hook for addons.
            do_action( 'erf_admin_settings_enqueue' );
	}
        
	/**
	 * Load the appropriate files to build the page.
	 *
	 * @since 1.0.0
	 */
	public function output() {
           $options= $this->save_settings(); 
           include 'html/settings.php';
	}
        
        private function save_settings(){ 
            $options_model= erforms()->options;
            $options= $options_model->get_options('erf_gsettings');
            
            if(isset($_POST['erf_save_settings'])){
                $options['rc_site_key'] = sanitize_text_field($_POST['rc_site_key']);
                $options['rc_secret_key'] =   sanitize_text_field($_POST['rc_secret_key']);
                $upload_dir =   sanitize_text_field($_POST['upload_dir']);
                $options['upload_dir'] =   empty($upload_dir) ? 'erf_uploads' : $upload_dir;
                $options['recaptcha_configured'] =   empty($_POST['recaptcha_configured']) ? 0 : 1;
                $options['en_wc_my_account'] =   empty($_POST['en_wc_my_account']) ? 0 : 1;
                $options['default_register_url'] =   empty($_POST['default_register_url']) ? 0 : absint($_POST['default_register_url']);
                $options['after_login_redirect_url']= esc_url($_POST['after_login_redirect_url']);
                $options['social_login']= $_POST['social_login'];
                $options['js_libraries']= $_POST['js_libraries'];
                $payment_methods= isset($_POST['payment_methods']) ? $_POST['payment_methods'] : array();
                $options['payment_methods']= $payment_methods;
                $options['currency']= $_POST['currency'];
                $options['en_role_redirection'] =   empty($_POST['en_role_redirection']) ? 0 : absint($_POST['en_role_redirection']);
                $roles= get_editable_roles();
                foreach($roles as $key=>$role){
                    $options[$key.'_login_redirection']= sanitize_text_field($_POST[$key.'_login_redirection']);
                }
                $options= apply_filters('erf_before_save_settings',$options);
                $options_model->save_options($options);
            }
            
            if(isset($_POST['savec'])){// Save and Close
                $url= admin_url('admin.php?page=erforms-settings');
                erforms_redirect($url);
                exit;
            }
            
            return $options;
        }
        /**
    * Enqueue assets for the overview page.
    *
    * @since 1.0.0
    */
        
}

new ERForms_Settings;
