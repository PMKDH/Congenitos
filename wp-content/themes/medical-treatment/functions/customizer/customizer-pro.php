<?php
//Pro Button

function medical_treatment_pro_customizer( $wp_customize ) {

//View Demo Link
class WP_medical_demo_Customize_Control extends WP_Customize_Control {
    public $type = 'new_menu';
    /**
    * Render the control's content.
    */
    public function render_content() {
    ?>
	  <div class="pro-box">
     <a href="<?php echo 'http://webriti.com/demo/wp/healthcentre/home-page-two/';?>" target="_blank" class="demo" id="review_pro">
	 <?php _e('View Demo','medical-treatment' ); ?></a>
	 </div>
    <?php
    }
}

$wp_customize->add_setting(
    'medical_demo_Review',
    array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
    )	
);
$wp_customize->add_control( new WP_medical_demo_Customize_Control( $wp_customize, 'medical_demo_Review', array(	
		'section' => 'health_pro_section',
		'setting' => 'medical_demo_Review',
    ))
);
}
add_action( 'customize_register', 'medical_treatment_pro_customizer' );
?>