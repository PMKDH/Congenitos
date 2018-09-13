<?php
// customizer Recent News panel
function customizer_recent_news_panel( $wp_customize ) {

	//Recent News panel
	$wp_customize->add_panel( 'news_panel' , array(
	'title'      => __('Recent news section', 'health-center-lite'),
	'capability'     => 'edit_theme_options',
	'priority'   => 540,
   	) );
	
		//Recent News panel
		$wp_customize->add_section( 'news_settings' , array(
		'title'      => __('Settings', 'health-center-lite'),
		'panel'  => 'news_panel',
		'priority'   => 1,
		) );
			
			// enable Recent News section
			$wp_customize->add_setting('hc_lite_options[news_enable]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('hc_lite_options[news_enable]',array(
			'label' => __('Hide section','health-center-lite'),
			'section' => 'news_settings',
			'type' => 'checkbox',
			) );
	
}
add_action( 'customize_register', 'customizer_recent_news_panel' );