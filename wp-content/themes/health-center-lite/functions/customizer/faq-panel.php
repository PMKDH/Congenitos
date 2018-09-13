<?php
// customizer serive panel
function customizer_faq_panel( $wp_customize ) {

	//faq panel
	$wp_customize->add_panel( 'faq_panel' , array(
	'title'      => __('FAQ section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 550,
   	) );
	
		//faq panel
		$wp_customize->add_section( 'faq_settings' , array(
		'title'      => __('Settings', 'health-center-lite'),
		'panel'  => 'faq_panel',
		'priority'   => 1,
		) );
			
			// enable faq section
			$wp_customize->add_setting('hc_lite_options[faq_enable]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[faq_enable]',array(
			'label' => __('Hide section','health-center-lite'),
			'section' => 'faq_settings',
			'type' => 'checkbox',
			) );
			
		// headings
		$wp_customize->add_section( 'faq_headings' , array(
		'title'      => __('Section Header', 'health-center-lite'),
		'panel'  => 'faq_panel',
		'priority'   => 2,
		) );
			
			// faq title
			$wp_customize->add_setting('hc_lite_options[hc_head_faq]',array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[hc_head_faq]',array(
			'label' => __('Title','health-center-lite'),
			'section' => 'faq_headings',
			'type' => 'text',
			) );
	
}
add_action( 'customize_register', 'customizer_faq_panel' );