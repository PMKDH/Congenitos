<?php
/**
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Admin_Plan {

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
		if ( 'erforms-plans' === $page ) {
			// Load the class that builds the overview table.
			require_once ERFORMS_PLUGIN_DIR . 'includes/admin/plan/class-plan-table.php';
                        
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
			add_action( 'erforms_admin_page',    array( $this, 'output'   ) );

			// Provide hook for addons.
			do_action( 'erforms_plan_init' );
		}
                else if ( 'erforms-plan' === $page ) 
                {
                    add_action( 'erforms_admin_page',array( $this, 'plan') );
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
                }
	}

	

	/**
	 * Enqueue assets for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {
            // Hook for addons.
            do_action( 'erf_admin_plan_enqueue' );
            
	}

	/**
	 * Build the output for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function output() { 
            include 'html/plans.php';
		
	}
        
        /**
        * @since 1.0.0
        */
        public function plan() {
           $this->save_plan();
           $plan_model= erforms()->plan;
           $plan_id= isset($_REQUEST['plan_id']) ? absint($_REQUEST['plan_id']) : 0; 
           $plan= $plan_model->get_plan($plan_id);
           if(empty($plan))
             return;
           $options= erforms()->options->get_options();
           include 'html/plan.php';
        }
        
        private function save_plan(){ 
            $plan_model= erforms()->plan;
            if(isset($_POST['erf_save_plan'])){
                $plan_id= absint($_POST['id']);
                $type= sanitize_text_field($_POST['type']);
                $name= sanitize_text_field($_POST['name']);
                $price= absint($_POST['price']);
                
                $plan= empty($plan_id) ? array() : $plan_model->get_plan($plan_id);
                $plan['id']= $plan_id;
                $plan['type']= $type;
                $plan['name']= $name;
                $plan['price']= $price;
                if(empty($plan_id)){
                    $id= $plan_model->add_plan($plan);
                    if(isset($_POST['save_close'])){
                        erforms_redirect(admin_url('admin.php?page=erforms-plans'));
                    }
                    else{
                        erforms_redirect(admin_url('admin.php?page=erforms-plan&view=fields&plan_id='.$id));
                    }
                   
                }
                else
                {
                   $plan_model->update_plan($plan); 
                }  
                
               if(isset($_POST['save_close'])){
                erforms_redirect(admin_url('admin.php?page=erforms-plans'));
               }
            }
        }
        
}
new ERForms_Admin_Plan;
