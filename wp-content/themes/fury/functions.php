<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Theme Directory
define( 'fury_dir', get_template_directory() . '/' );

// Define Theme Directory URI
define( 'fury_uri', get_template_directory_uri() . '/' );

// Define Theme CSS Directory URI
define( 'fury_css', fury_uri . 'assets/css/' );

// Define Theme JS Directory URI
define( 'fury_js', fury_uri . 'assets/js/' );

// Define Theme IMG Directory URI
define( 'fury_img', fury_uri . 'assets/img/' );

// Define Kirki Framework Images Directory
define( 'fury_kirki_img', fury_uri . 'framework/admin/kirki/assets/images/' );

// Define Fury Modules Directory URI
define( 'fury_modules_url', fury_uri . 'framework/admin/modules/' );

/**
 * Include Theme Files
 */
get_template_part( 'framework/classes/class-fury-init' );

/**
 * Magic Starts Here
 *
 * @since 1.0
 */
final class Fury_Theme extends Fury_Core {
    
    /**
     * Instance
     *
     * @var $instance
     */
    private static $instance;
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        parent::$version     = '1.1.8';
        parent::$development = false;
        
        parent::__construct();
        
    }
    
    /**
     * Initiator
     *
     * @since 1.0.0
     * @return object
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
Fury_Theme::get_instance();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
