<?php
// customizer Callout panel
function customizer_callout_panel( $wp_customize ) {

	//Callout panel
	$wp_customize->add_panel( 'callout_panel' , array(
	'title'      => __('Callout section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 570,
   	) );
	
		//Callout panel
		$wp_customize->add_section( 'callout_settings' , array(
		'title'      => __('Settings', 'health-center-lite'),
		'panel'  => 'callout_panel',
		'priority'   => 1,
		) );
			
			// enable Callout section
			$wp_customize->add_setting('hc_lite_options[callout_enable]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[callout_enable]',array(
			'label' => __('Hide callout section','health-center-lite'),
			'section' => 'callout_settings',
			'type' => 'checkbox',
			) );
			
	// headings
	$wp_customize->add_section( 'callout_headings' , array(
	'title'      => __('Section Header', 'health-center-lite'),
	'panel'  => 'callout_panel',
	'priority'   => 2,
	) );


			//Footer callout text
			$wp_customize->add_setting(
			'hc_lite_options[call_out_text]',
			array(
				'default' => '',
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
				)
			);	
			$wp_customize->add_control('hc_lite_options[call_out_text]',array(
			'label'   => __('Text','health-center-lite'),
			'section' => 'callout_headings',
			 'type' => 'textarea',)  );
			 
			 
			 $wp_customize ->add_setting (
			'hc_lite_options[call_out_button_text]',
			array( 
			'default' => '',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option',
			) 
			);

			$wp_customize->add_control (
			'hc_lite_options[call_out_button_text]',
			array (  
			'label' => __('Button Text','health-center-lite'),
			'section' => 'callout_headings',
			'type' => 'text',
			) );
			
			
			$wp_customize ->add_setting (
			'hc_lite_options[call_out_button_link]',
			array( 
			'default' => '#',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option',
			) );

			$wp_customize->add_control (
			'hc_lite_options[call_out_button_link]',
			array (  
			'label' => __('Button Link','health-center-lite'),
			'section' => 'callout_headings',
			'type' => 'text',
			) );

			$wp_customize->add_setting(
				'hc_lite_options[call_out_button_link_target]',
				array('capability'     => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
				));

			$wp_customize->add_control(
				'hc_lite_options[call_out_button_link_target]',
				array(
					'type' => 'checkbox',
					'label' => __('Open link in new tab','health-center-lite'),
					'section' => 'callout_headings',
				)
			); 
	
}
add_action( 'customize_register', 'customizer_callout_panel' );