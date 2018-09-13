<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Files Initialization Class
 *
 * @since 1.0
 */
class Fury_Init {
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        $this->get_template_files();
        
    }
    
    /**
     * Get Template Files
     *
     * @since 1.0
     */
    function get_template_files() {
        
        if( is_admin() ) {
            get_template_part( 'framework/fury-plugin-activation' );
        }
        
        get_template_part( 'framework/admin/fury-customizer' );
        get_template_part( 'framework/classes/class-fury-helper' );
        get_template_part( 'framework/classes/class-fury-widgets' );
        get_template_part( 'framework/classes/class-fury-woocommerce' );
        get_template_part( 'framework/classes/class-fury-slider' );
        get_template_part( 'framework/classes/class-fury-core' );
        get_template_part( 'framework/fury-functions' );
        get_template_part( 'framework/fury-actions' );
        get_template_part( 'framework/fury-filters' );
        
    }
    
}
new Fury_Init();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
