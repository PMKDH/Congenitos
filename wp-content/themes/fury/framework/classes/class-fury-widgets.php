<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fury Widgets Class
 *
 * @since 1.0
 */
class Fury_Widgets {
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        
    }
    
    /**
     * Initialize Widgets
     *
     * @since 1.0
     */
    function widgets_init() {
        register_sidebar( array(
            'name'          => esc_html__( 'Main Sidebar', 'fury' ),
            'id'            => 'fury-sidebar',
            'description'   => esc_html__( 'Appears on posts & pages.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        if( class_exists( 'woocommerce' ) ) {
            register_sidebar( array(
                'name'          => esc_html__( 'Shop Sidebar', 'fury' ),
                'id'            => 'fury-shop-sidebar',
                'description'   => esc_html__( 'Appears on WooCommerce pages.', 'fury' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>'
            ) );
        }
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 1', 'fury' ),
            'id'            => 'fury-footer-widget-1',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 2', 'fury' ),
            'id'            => 'fury-footer-widget-2',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 3', 'fury' ),
            'id'            => 'fury-footer-widget-3',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 4', 'fury' ),
            'id'            => 'fury-footer-widget-4',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 5', 'fury' ),
            'id'            => 'fury-footer-widget-5',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget 6', 'fury' ),
            'id'            => 'fury-footer-widget-6',
            'description'   => esc_html__( 'Appears on footer area.', 'fury' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-light-skin">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
    }
    
}
new Fury_Widgets();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
