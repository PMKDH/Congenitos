<?php
function hc_scripts()
{	// Theme Css 

	wp_enqueue_style('health-style', get_stylesheet_uri() );
	
	wp_enqueue_style('health-center-lite-default', WEBRITI_TEMPLATE_DIR_URI . '/css/default.css');
		
	wp_enqueue_style('health-center-lite-responsive', WEBRITI_TEMPLATE_DIR_URI . '/css/media-responsive.css');
   		
	//wp_enqueue_style('health-center-lite-bootstrap', WEBRITI_TEMPLATE_DIR_URI . '/css/bootstrap.css');
   		
	wp_enqueue_style('health-center-lite-font-awesome', WEBRITI_TEMPLATE_DIR_URI . '/css/font-awesome/css/font-awesome.min.css');	
	
	wp_enqueue_style('health-center-lite-font', WEBRITI_TEMPLATE_DIR_URI . '/css/font/font.css');
   		
	wp_enqueue_script('health-center-lite-menu', WEBRITI_TEMPLATE_DIR_URI .'/js/menu/menu.js',array('jquery'));
   		
	wp_enqueue_script('health-center-lite-bootstrap_min', WEBRITI_TEMPLATE_DIR_URI .'/js/bootstrap.min.js');	
}

add_action('wp_enqueue_scripts', 'hc_scripts');


	add_action('wp_enqueue_scripts', 'hc_scripts');
   	if ( is_singular() ){ wp_enqueue_script( "comment-reply" );	}

//Customizer css
function hc_custmizer_style()
 {
		wp_enqueue_style('health-customizer-css',WEBRITI_TEMPLATE_DIR_URI.'/css/cust-style.css');
}
add_action('customize_controls_print_styles','hc_custmizer_style');


add_action('wp_head','hc_head_enqueue_custom_css');
function hc_head_enqueue_custom_css()
{
	$hc_lite_options=theme_data_setup(); 
	$current_options = wp_parse_args(  get_option( 'hc_lite_options', array() ), $hc_lite_options ); 
	if($current_options['webrit_custom_css']!='') {  ?>
	<style>
	<?php echo wp_filter_nohtml_kses($current_options['webrit_custom_css']); ?>
	</style>
<?php } }
function hc_registers() {

	wp_enqueue_script( 'health_customizer_script', get_template_directory_uri() . '/js/healthcenter_customizer.js', array("jquery"), '20120206', true  );
}
add_action( 'customize_controls_enqueue_scripts', 'hc_registers' );
?>