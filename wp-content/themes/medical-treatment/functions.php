<?php
add_action( 'wp_enqueue_scripts', 'medical_treatment_theme_css',999);
function medical_treatment_theme_css() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
	wp_enqueue_style( 'default-css', get_stylesheet_directory_uri()."/css/default.css" );
	wp_dequeue_style('health-center-lite-default',get_template_directory_uri() .'/css/default.css');
}

// Unregister Health center lite
function medical_treatment_remove_some_widgets(){

	unregister_sidebar( 'sidebar-project' );
}
add_action( 'widgets_init', 'medical_treatment_remove_some_widgets', 11 );

require( get_stylesheet_directory() . '/functions/customizer/customizer-pro.php' );
require( get_stylesheet_directory() . '/functions/widget/custom-sidebar.php' );


/**
 * Enqueue script for custom customize control.
 */
function medical_treatment_custom_customize_enqueue() {
	wp_enqueue_script( 'medical_treatment_customizer_script', get_stylesheet_directory_uri() . '/js/healthcenter_customizer.js', array( 'jquery', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'medical_treatment_custom_customize_enqueue' );

add_action( 'customize_register', 'medical_treatment_remove_custom', 1000 );
function medical_treatment_remove_custom($wp_customize) {
  $wp_customize->remove_setting('health_pro_customizer');
  $wp_customize->remove_control('demo_Review');
}

add_action( 'after_setup_theme', 'medical_treatment_setup' ); 	
   	function medical_treatment_setup()
   	{	
   		require_once( get_stylesheet_directory() . '/theme_setup_data.php' );
		load_theme_textdomain( 'medical-treatment', get_stylesheet_directory() . '/languages' );
	}
	
?>