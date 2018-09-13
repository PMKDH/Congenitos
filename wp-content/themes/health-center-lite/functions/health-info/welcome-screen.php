<?php
/**
 * Welcome Screen Class
 */
class health_screen {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'health_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'health_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'health_style_and_scripts' ) );

		/* enqueue script for customizer */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'health_scripts_for_customizer' ) );

		/* load welcome screen */
		add_action( 'health_info_screen', array( $this, 'health_getting_started' ), 	    10 );
		add_action( 'health_info_screen', array( $this, 'health_action_required' ), 	    20 );
		add_action( 'health_info_screen', array( $this, 'health_child_themes' ), 		    30 );
		add_action( 'health_info_screen', array( $this, 'health_github' ), 		        40 );
		add_action( 'health_info_screen', array( $this, 'health_welcome_free_pro' ), 		50 );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_health_dismiss_required_action', array( $this, 'health_dismiss_required_action_callback') );
		add_action( 'wp_ajax_nopriv_health_dismiss_required_action', array($this, 'health_dismiss_required_action_callback') );

	}

	public function health_register_menu() {
		add_theme_page( 'About Health center Theme', 'About Health center Theme', 'activate_plugins', 'health-info', array( $this, 'hc_welcome_screen' ) );
	}

	public function health_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'health_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @sfunctionse 1.8.2.4
	 */
	public function health_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing health Lite! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'health-center-lite' ), '<a href="' . esc_url( admin_url( 'themes.php?page=health-info' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=health-info' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with health Lite', 'health-center-lite' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 * @sfunctionse  1.8.2.4
	 */
	public function health_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_health-info' == $hook_suffix ) {
			
			
			wp_enqueue_style( 'health-info-css', get_template_directory_uri() . '/functions/health-info/css/bootstrap.css' );
			
			wp_enqueue_style( 'health-info-screen-css', get_template_directory_uri() . '/functions/health-info/css/welcome.css' );

			wp_enqueue_script( 'health-info-screen-js', get_template_directory_uri() . '/functions/health-info/js/welcome.js', array('jquery') );

			global $health_required_actions;

			$nr_actions_required = 0;

			/* get number of required actions */
			if( get_option('health_show_required_actions') ):
				$health_show_required_actions = get_option('health_show_required_actions');
			else:
				$health_show_required_actions = array();
			endif;

			if( !empty($health_required_actions) ):
				foreach( $health_required_actions as $health_required_action_value ):
					if(( !isset( $health_required_action_value['check'] ) || ( isset( $health_required_action_value['check'] ) && ( $health_required_action_value['check'] == false ) ) ) && ((isset($health_show_required_actions[$health_required_action_value['id']]) && ($health_show_required_actions[$health_required_action_value['id']] == true)) || !isset($health_show_required_actions[$health_required_action_value['id']]) )) :
						$nr_actions_required++;
					endif;
				endforeach;
			endif;

			wp_localize_script( 'health-info-screen-js', 'healthLiteWelcomeScreenObject', array(
				'nr_actions_required' => $nr_actions_required,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_template_directory_uri(),
				'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.','health-center-lite' )
			) );
		}
	}

	/**
	 * Load scripts for customizer page
	 * @sfunctionse  1.8.2.4
	 */
	public function health_scripts_for_customizer() {

		wp_enqueue_style( 'health-info-screen-customizer-css', get_template_directory_uri() . '/functions/health-info/css/welcome_customizer.css' );
		wp_enqueue_script( 'health-info-screen-customizer-js', get_template_directory_uri() . '/functions/health-info/js/welcome_customizer.js', array('jquery'), '20120206', true );

		global $health_required_actions;

		$nr_actions_required = 0;

		/* get number of required actions */
		if( get_option('health_show_required_actions') ):
			$health_show_required_actions = get_option('health_show_required_actions');
		else:
			$health_show_required_actions = array();
		endif;

		if( !empty($health_required_actions) ):
			foreach( $health_required_actions as $health_required_action_value ):
				if(( !isset( $health_required_action_value['check'] ) || ( isset( $health_required_action_value['check'] ) && ( $health_required_action_value['check'] == false ) ) ) && ((isset($health_show_required_actions[$health_required_action_value['id']]) && ($health_show_required_actions[$health_required_action_value['id']] == true)) || !isset($health_show_required_actions[$health_required_action_value['id']]) )) :
					$nr_actions_required++;
				endif;
			endforeach;
		endif;

		wp_localize_script( 'health-info-screen-customizer-js', 'healthLiteWelcomeScreenObject', array(
			'nr_actions_required' => $nr_actions_required,
			'aboutpage' => esc_url( admin_url( 'themes.php?page=health-info#actions_required' ) ),
			'customizerpage' => esc_url( admin_url( 'customize.php#actions_required' ) ),
			'themeinfo' => __('View Theme Info','health-center-lite'),
		) );
	}

	/**
	 * Dismiss required actions
	 * @sfunctionse 1.8.2.4
	 */
	public function health_dismiss_required_action_callback() {

		global $health_required_actions;

		$health_dismiss_id = (isset($_GET['dismiss_id'])) ? $_GET['dismiss_id'] : 0;

		echo $health_dismiss_id; /* this is needed and it's the id of the dismissable required action */

		if( !empty($health_dismiss_id) ):

			/* if the option exists, update the record for the specified id */
			if( get_option('health_show_required_actions') ):

				$health_show_required_actions = get_option('health_show_required_actions');

				$health_show_required_actions[$health_dismiss_id] = false;

				update_option( 'health_show_required_actions',$health_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$health_show_required_actions_new = array();

				if( !empty($health_required_actions) ):

					foreach( $health_required_actions as $health_required_action ):

						if( $health_required_action['id'] == $health_dismiss_id ):
							$health_show_required_actions_new[$health_required_action['id']] = false;
						else:
							$health_show_required_actions_new[$health_required_action['id']] = true;
						endif;

					endforeach;

				update_option( 'health_show_required_actions', $health_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 * Welcome screen content
	 * @sfunctionse 1.8.2.4
	 */
	public function hc_welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		?>
		<div class="container-fluid">
		<div class="row">
		<div class="col-md-12">
		<ul class="health-nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting Started','health-center-lite'); ?></a></li>
			<li role="presentation"><a href="#actions_required" aria-controls="actions_required" role="tab" data-toggle="tab"><?php esc_html_e( 'Actions Required','health-center-lite'); ?></a></li>
			<li role="presentation"><a href="#github" aria-controls="github" role="tab" data-toggle="tab"><?php esc_html_e( 'Why Upgrade Pro','health-center-lite'); ?></a></li>
			<li role="presentation"><a href="#free_pro" aria-controls="free_pro" role="tab" data-toggle="tab"><?php esc_html_e( 'Free VS PRO','health-center-lite'); ?></a></li>
			<li role="presentation"><a href="#child_themes" aria-controls="child_themes" role="tab" data-toggle="tab"><?php esc_html_e( 'Child Themes','health-center-lite'); ?></a></li>
			
		</ul>
		</div>
		</div>
		</div>

		<div class="health-tab-content">

			<?php do_action( 'health_info_screen' ); ?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 *
	 */
	public function health_getting_started() {
		require_once( get_template_directory() . '/functions/health-info/sections/getting-started.php' );
	}

	
	/**
	 * Action Requerd
	 *
	 */
	public function health_action_required() {
		require_once( get_template_directory() . '/functions/health-info/sections/actions-required.php' );
	}
	
	/**
	 * Child themes
	 *
	 */
	public function health_child_themes() {
		require_once( get_template_directory() . '/functions/health-info/sections/child-themes.php' );
	}

	/**
	 * Contribute
	 *
	 */
	public function health_github() {
		require_once( get_template_directory() . '/functions/health-info/sections/github.php' );
	}


	/**
	 * Free vs PRO
	 * 
	 */
	public function health_welcome_free_pro() {
		require_once( get_template_directory() . '/functions/health-info/sections/free_pro.php' );
	}
}

$GLOBALS['health_screen'] = new health_screen();