<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fury Core Class
 *
 * @since 1.0
 */
class Fury_Core {
    
    /**
     * Theme Version
     */
    public static $version;
    
    /**
     * Development Mode
     */
    public static $development;
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        if( self::$development ) {
            self::$version = esc_attr( uniqid() );
        }
        
        $this->defines();
        
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'after_setup_theme', array( $this, 'fury_setup' ) );
        add_filter( 'body_class', array( $this, 'body_class' ) );
    }
    
    /**
     * Enqueue Theme Scripts
     *
     * @since 1.0
     */
    function wp_enqueue_scripts() {
        
        if ( is_singular() ) {
            wp_enqueue_script( "comment-reply" );
        }
        
        // Bootstrap 4
        wp_enqueue_style( 'fury-bootstrap', fury_css . 'bootstrap.min.css', array(), self::$version );
        
        // Fury Stylesheet
        wp_enqueue_style( 'fury', get_stylesheet_uri(), array(), self::$version );
        
        // Modernizr JS
        wp_enqueue_script( 'fury-modernizr', fury_js . 'modernizr.min.js', array('jquery'), self::$version );
        
        // Vendor JS
        wp_enqueue_script( 'fury-vendor', fury_js . 'vendor.min.js', array('jquery'), self::$version, true );
        
        // Scripts JS
        wp_enqueue_script( 'fury-scripts', fury_js . 'scripts.js', array('jquery'), self::$version, true );
        
        // Functions JS
        wp_enqueue_script( 'fury-functions', fury_js . 'functions.js', array('jquery'), self::$version );
        
    }
    
    /**
     * Theme Setup
     *
     * @since 1.0
     */
    function fury_setup() {
        
        if ( ! isset( $content_width ) ) {
            $content_width = 1170;
        }
        
        /*
         * Make theme available for translation.
         * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/fury
         */
        load_theme_textdomain( 'fury', fury_dir . 'languages' );
        
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );
        
        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();
        
        // Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
        add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );
        
        // Add custom logo support.
        $cl_defaults = array(
            'height'      => 88,
            'width'       => 258,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' )
        );
        add_theme_support( 'custom-logo', $cl_defaults );
        
        // This theme uses post thumbnails.
        add_theme_support( 'post-thumbnails' );
        
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        
        // Default Menus
        $menus = array(
            'fury-primary'   => esc_html__( 'Primary Menu', 'fury' ),
            'fury-mobile'    => esc_html__( 'Mobile Menu', 'fury' )
        );
        
        // If WooCommerce Not Active
        if( ! class_exists( 'woocommerce' ) ) {
            $menus = array_merge( $menus, 
                array( 'fury-offcanvas' => esc_html__( 'Off-Canvas Menu', 'fury' ) ) 
            );
        }
        
        // Register Menus
        register_nav_menus( $menus );
        
        // Header Defaults
        $header = array(
            'flex-width'    => true,
            'width'         => 1920,
            'flex-height'   => true,
            'height'        => 500 
        );
        
        // Custom Header
        add_theme_support( 'custom-header', $header );
        
        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );
        
        // Add WooCommerce Support
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        
        // Add Custom Image Sizes
        add_image_size( 'fury-related', 100, 100, true ); // Related Articles (Single Post)
        
    }
    
    /**
     * Theme Defines
     *
     * @since 1.1.6
     */
    function defines() {
        if( ! defined( 'fury_version' ) ) {
            define( 'fury_version', self::$version );
        }
    }
    
    /**
     * Body Classes
     *
     * @since 1.0.3
     */
    function body_class( $classes ) {
        if( class_exists( 'woocommerce' ) && is_account_page() && ! is_user_logged_in() ) {
            $classes[] = 'woocommerce-login-page';
        }
        return $classes;
    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
