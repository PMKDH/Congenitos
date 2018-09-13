<?php
// customizer serive panel
function hc_customizer_service_panel( $wp_customize ) {

	//Service panel
	$wp_customize->add_panel( 'service_panel' , array(
	'title'      => __('Service section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 520,
   	) );
	
		//Service panel
		$wp_customize->add_section( 'service_settings' , array(
		'title'      => __('Settings', 'health-center-lite'),
		'panel'  => 'service_panel',
		'priority'   => 1,
		) );
			
			// enable service section
			$wp_customize->add_setting('hc_lite_options[service_enable]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[service_enable]',array(
			'label' => __('Hide section','health-center-lite'),
			'section' => 'service_settings',
			'type' => 'checkbox',
			) );
			
		// headings
		$wp_customize->add_section( 'service_headings' , array(
		'title'      => __('Section Header', 'health-center-lite'),
		'panel'  => 'service_panel',
		'priority'   => 2,
		) );
			
			// Service title
			$wp_customize->add_setting('hc_lite_options[service_title]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[service_title]',array(
			'label' => __('Title','health-center-lite'),
			'section' => 'service_headings',
			'type' => 'text',
			) );
			
			// service description
			$wp_customize->add_setting('hc_lite_options[service_description]',array(
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[service_description]',array(
			'label' => __('Subtitle','health-center-lite'),
			'section' => 'service_headings',
			'type' => 'textarea',
			'sanitize_callback' => 'sanitize_text_field',
			) );
	
}
add_action( 'customize_register', 'hc_customizer_service_panel' );


// Intro section panel
function customizer_introsection_panel( $wp_customize ) {
	
	// Intro section panel
	$wp_customize->add_panel( 'intro_panel' , array(
	'title'      => __('Big intro section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 510,
   	) );
		
		// intro section
		$wp_customize->add_section( 'intro_section' , array(
		'title'      => __('Big intro section', 'health-center-lite'),
		'panel'  => 'intro_panel',
		) );
				
			//Site intro
			$wp_customize->add_setting('hc_lite_options[site_intro_tex]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[site_intro_tex]',array(
			'label' => __('Site intro','health-center-lite'),
			'section' => 'intro_section',
			'type' => 'text',
			) );
			
			//Site intro call now
			$wp_customize->add_setting('hc_lite_options[call_now_text]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[call_now_text]',array(
			'label' => __('Call now text','health-center-lite'),
			'section' => 'intro_section',
			'type' => 'text',
			) );
			
			//Call Now Number
			$wp_customize->add_setting('hc_lite_options[call_now_number]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[call_now_number]',array(
			'label' => __('Call now number','health-center-lite'),
			'section' => 'intro_section',
			'type' => 'text',
			) );
}
add_action( 'customize_register', 'customizer_introsection_panel' );