<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fury Plugin Core Class
 *
 * @since 1.0
 */
class Fury_Core_Init_Plugin {
    
    
    /**
     * Plugin Version
     *
     * @since 1.0
     */
    public static $version;
    
    
    /**
     * Development Mode
     *
     * @since 1.0
     */
    public static $development;
    
    
    /**
     * Class Constructor
     *
     * @since 1.0
     */
    function __construct() {
        
        // Development Mode
        if( self::$development ) {
            self::$version = esc_attr( uniqid() );
        }
        
        // Plugin Textdomain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        
        // Setup Plugin
        add_action( 'init', array( $this, 'plugin_setup' ) );
        
    }
    
    
    /**
	 * Plugin Textdomain
     *
	 * @since 1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'fury-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
    
    
    /**
     * Plugin Setup
     * Load files & scripts only if
     * Fury or Child theme is active.
     *
     * @since 1.0
     */
    public function plugin_setup() {
        $theme = wp_get_theme();
        if ( 'Fury' == $theme->name || 'fury' == $theme->template ) {
            require_once( Fury_Plugin_dir . 'includes/class-fury-menu.php' );
			require_once( Fury_Plugin_dir . 'includes/metabox/class-fury-metabox.php' );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
    }
    
    
    /**
	 * Enqueue Plugin Scripts
	 *
	 * @since 1.0
	 */
	public function admin_enqueue_scripts() { 
        wp_enqueue_script( 'fury-core-backend', Fury_Plugin_url . 'assets/js/backend.js', array(), self::$version );
        wp_enqueue_style( 'fury-core-backend', Fury_Plugin_url . 'assets/css/backend.css', array(), self::$version );
    }
    
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
