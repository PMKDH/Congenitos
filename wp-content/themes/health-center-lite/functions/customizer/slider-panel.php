<?php
function hc_home_slider_customizer( $wp_customize ) {
/* Header Section */
	$wp_customize->add_panel( 'slider_setting', array(
		'capability'     => 'edit_theme_options',
		'priority'   => 500,
		'title'      => __('Slider section', 'health-center-lite'),
	) );
	
	/* Option list of all post */	
    $options_posts = array();
    $options_posts_obj = get_posts('posts_per_page=-1');
    $options_posts[''] = __( 'Choose Post','health-center-lite' );
    foreach ( $options_posts_obj as $posts ) {
    	$options_posts[$posts->ID] = $posts->post_title;
    }

	$wp_customize->add_section(
        'slider_section_settings',
        array(
            'title' => __('Slider section','health-center-lite'),
            'description' => '',
			'panel'  => 'slider_setting',)
    );
	
			//Hide slider
			
			$wp_customize->add_setting(
			'hc_lite_options[home_slider_enabled]',
			array(
				'default' => false,
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
			)	
			);
			$wp_customize->add_control(
			'hc_lite_options[home_slider_enabled]',
			array(
				'label' => __('Hide Home Slider','health-center-lite'),
				'section' => 'slider_section_settings',
				'type' => 'checkbox',
				'description' => __('Enable slider on front page.','health-center-lite'),
			));
			
	$wp_customize->add_section(
        'slider_post_settings',
        array(
            'title' => __('Select slider post','health-center-lite'),
            'description' => '',
			'panel'  => 'slider_setting',)
    );	
	
			//Select Post One
			$wp_customize->add_setting('hc_lite_options[slider_post]',array(
				'capability'=>'edit_theme_options',
				'sanitize_callback'=>'sanitize_text_field',
				'type' => 'option',
			));
			
			$wp_customize->add_control('hc_lite_options[slider_post]',array(
				'label' => __('Select post','health-center-lite'),
				'section'=>'slider_post_settings',
				'type'=>'select',
				'choices'=>$options_posts,
			));

	
	
	}
	add_action( 'customize_register', 'hc_home_slider_customizer' );
	?>