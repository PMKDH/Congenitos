<?php
// customizer project panel
function customizer_project_panel( $wp_customize ) {

	//Service panel
	$wp_customize->add_panel( 'project_panel' , array(
	'title'      => __('Project section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 520,
   	) );
	
		//Service panel
		$wp_customize->add_section( 'project_settings' , array(
		'title'      => __('Settings', 'health-center-lite'),
		'panel'  => 'project_panel',
		'priority'   => 1,
		) );
			
			// enable project section
			$wp_customize->add_setting('hc_lite_options[home_projects_enabled]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[home_projects_enabled]',array(
			'label' => __('Hide section','health-center-lite'),
			'section' => 'project_settings',
			'type' => 'checkbox',
			) );
			
		// headings
		$wp_customize->add_section( 'project_headings' , array(
		'title'      => __('Section Header', 'health-center-lite'),
		'panel'  => 'project_panel',
		'priority'   => 2,
		) );
			
			// Service title
			$wp_customize->add_setting('hc_lite_options[project_heading_one]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[project_heading_one]',array(
			'label' => __('Title','health-center-lite'),
			'section' => 'project_headings',
			'type' => 'text',
			) );
			
			// project description
			$wp_customize->add_setting('hc_lite_options[project_tagline]',array(
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[project_tagline]',array(
			'label' => __('Subtitle','health-center-lite'),
			'section' => 'project_headings',
			'type' => 'textarea',
			'sanitize_callback' => 'sanitize_text_field',
			) );
	
}
add_action( 'customize_register', 'customizer_project_panel' );