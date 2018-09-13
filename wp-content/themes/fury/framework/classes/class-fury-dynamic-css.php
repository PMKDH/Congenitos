<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dynamic CSS Class
 *
 * @since 1.0
 */
class Fury_Dynamic_CSS {
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        add_action( 'wp_head', array( $this, 'render' ) );
        
    }
    
    /**
     * Header Top Bar Gradient Background
     *
     * @since 1.3.7
     */
    private function header_top_bar() {
        if( get_theme_mod( 'fury_htb_styling', true ) && 
            get_theme_mod( 'fury_htb_bg_color_switch', 'gradient-color') == 'gradient-color' ) {
            $start  = esc_attr( get_theme_mod( 'fury_htb_gradient_start_color', '#5540d9' ) );
            $end    = esc_attr( get_theme_mod( 'fury_htb_gradient_end_color', '#ee2762' ) );
            $angle  = esc_attr( get_theme_mod( 'fury_htb_gradient_angle', '90' ) );
            $css  = '.topbar {';
                $css .= 'background-color:'. $start .';';
                $css .= 'background-image:linear-gradient('. $angle .'deg, '. $start .' 0%, '. $end .' 100%)';
            $css .= '}';
            
            echo $css;
        }
    }
    
    /**
     * Header Gradient Background
     *
     * @since 1.3.7
     */
    private function header() {
        if( get_theme_mod( 'fury_header_styling', false ) && 
            get_theme_mod( 'fury_header_bg_color_switch', 'gradient-color') == 'gradient-color' ) {
            $start  = esc_attr( get_theme_mod( 'fury_header_gradient_start_color', '#5540d9' ) );
            $end    = esc_attr( get_theme_mod( 'fury_header_gradient_end_color', '#ee2762' ) );
            $angle  = esc_attr( get_theme_mod( 'fury_header_gradient_angle', '90' ) );
            $css  = 'header.navbar, header.navbar .site-search {';
                $css .= 'background-color:'. $start .';';
                $css .= 'background-image:linear-gradient('. $angle .'deg, '. $start .' 0%, '. $end .' 100%)';
            $css .= '}';
            $css .= 'header.navbar .site-search > input {';
                $css .= 'background-color:transparent;';
            $css .= '}';
            
            echo $css;
        }
    }
    
    /**
     * Render CSS Code
     *
     * @since 1.0
     */
    function render() { ?>
    <style type="text/css" id="fury-customize-css">
        
        <?php if( is_admin_bar_showing() ): ?>
        header.navbar-sticky.navbar-stuck { top: 32px; }
        .offcanvas-header { margin-top: 32px; }
        <?php endif; ?>
        
        <?php $this->header_top_bar(); ?>
        <?php $this->header(); ?>
        
    </style>
    <?php
    }
    
}
new Fury_Dynamic_CSS();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
