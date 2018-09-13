<?php
// Footer copyright section 
function health_copyright_customizer( $wp_customize ) {
	
	$wp_customize->add_panel('copyright_panel',array(
    'title' => __('Footer copyright settings','health-center-lite'),
	'capability'     => 'edit_theme_options',
    'priority' => 580,
    ) );
	
		$wp_customize->add_section('copyright_section',array(
			'title' => __('Footer copyright settings','health-center-lite'),
			'panel'=>'copyright_panel',
			'priority' => 1,
		));
	
			$wp_customize->add_setting(
			'hc_lite_options[footer_copyright]',
			array(
				'default' => '',
				'type' =>'option',
				'sanitize_callback' => 'hc_copyright_sanitize_text'
			));
			
			$wp_customize->add_control(
			'hc_lite_options[footer_copyright]',
			array(
				'label' => __('Copyright text','health-center-lite'),
				'section' => 'copyright_section',
				'type' => 'textarea',
			));	


			function hc_copyright_sanitize_text( $input ) {

			return wp_kses_post( force_balance_tags( $input ) );

			}
	
			function hc_copyright_sanitize_html( $input ) {

			return force_balance_tags( $input );

			}
}
add_action( 'customize_register', 'health_copyright_customizer' );