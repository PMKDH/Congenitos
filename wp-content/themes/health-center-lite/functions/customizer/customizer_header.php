<?php
function health_header_customizer( $wp_customize ) {
/* Header Section */
	$wp_customize->add_panel( 'header_options', array(
		'priority'       => 300,
		'capability'     => 'edit_theme_options',
		'title'      => __('Header settings', 'health-center-lite'),
	) );
	
	//Custom css
	$wp_customize->add_section( 'custom_css' , array(
		'title'      => __('Custom CSS', 'health-center-lite'),
		'panel'  => 'header_options',
		'priority'   => 100,
   	) );
	$wp_customize->add_setting(
	'hc_lite_options[webrit_custom_css]'
		, array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
		'sanitize_callback'    => 'wp_filter_nohtml_kses',
		'sanitize_js_callback' => 'wp_filter_nohtml_kses',
		'type'=> 'option',
    ));
    $wp_customize->add_control( 'hc_lite_options[webrit_custom_css]', array(
        'label'   => __('Custom CSS', 'health-center-lite'),
        'section' => 'custom_css',
        'type' => 'textarea',
    )); 
	}
	add_action( 'customize_register', 'health_header_customizer' );
	?>