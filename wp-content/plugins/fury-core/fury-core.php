<?php
/*
 Plugin Name: Fury Core
 Plugin URI: https://theme-vision.com/fury-wordpress-theme/
 Description: Fury Core plugin extends <a href="https://wordpress.org/themes/fury/">Fury WordPress theme</a> with extra metabox features and few more. Not working with other themes than <a href="https://wordpress.org/themes/fury/">Fury theme</a>.
 Version: 1.0.3
 Author: Theme Vision
 Author URI: https://theme-vision.com/
 License: GPLv3
 Text Domain: fury-core
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define Plugin URL & DIR
define( 'Fury_Plugin_url',  plugin_dir_url( __FILE__ ) . '/' );
define( 'Fury_Plugin_dir',  plugin_dir_path( __FILE__ ) . '/' );


// Include Fury Core Class
require_once( Fury_Plugin_dir . 'includes/class-fury-core-init.php' );


/**
 * Fury Core Class
 *
 * @since 1.0
 */
final class Fury_Core_Plugin extends Fury_Core_Init_Plugin {
    
    
    /**
	 * Fury Core Instance
     *
	 * @since 1.0
	 */
	private static $instance = null;
    
    
    /**
     * Plugin Class Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        
        parent::$version     = '1.0.3';
        parent::$development = false;
        
        parent::__construct();
        
    }
    
    
    /**
	 * Fury Core Instance
	 *
	 * @since 1.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
        }
		return self::$instance;
	}
    
    
}
Fury_Core_Plugin::get_instance();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
