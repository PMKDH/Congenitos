<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fury Slider Defaults
 *
 * @since 1.1.6
 */
if( ! class_exists( 'Fury_Slider_Defaults' ) ) {
    abstract class Fury_Slider_Defaults {

        /**
         * Slider Enabled
         *
         * @access private
         * @since 1.1.6
         */
        private $enabled;
        
        /**
         * Enable on Pages
         *
         * @access private
         * @since 1.1.6
         */
        private $on_pages;

        /**
         * Slider Background
         *
         * @access private
         * @since 1.1.6
         */
        private $background;

        /**
         * Slider Args
         *
         * @access private
         * @since 1.1.6
         */
        private $data_args;

        /**
         * Slider Slides
         *
         * @access private
         * @since 1.1.6
         */
        private $slides = array();

        /**
         * Theme Root URI Path
         *
         * @access private
         * @since 1.1.6
         */
        private $path_uri;

        /**
         * Class Constructor
         *
         * @since 1.1.6
         */
        function __construct() {

            $this->path_uri     = get_template_directory_uri() . '/';
            $this->enabled      = esc_attr( get_theme_mod( 'fury_slider_enable', true ) );
            $this->background   = esc_attr( get_theme_mod( 'fury_slider_bg_image', $this->path_uri . 'assets/img/slider-bg.jpg' ) );
            $this->data_args    = $this->data_args();
            $this->allowed_html = $this->allowed_html();
            $this->on_pages     = get_theme_mod( 'fury_slider_on_pages', array() );
            
            add_action( 'fury_slider', array( $this, 'init' ) );
        }

        /**
         * Enqueue Scripts
         *
         * @since 1.1.6
         */
        function enqueue_scripts() {}
        
        /**
         * Slider Data Arguments
         *
         * @since 1.1.6
         */
        function data_args() {
            $arg  = '{';
                $arg .= '&quot;nav&quot;: true,';
                $arg .= '&quot;dots&quot;: true,';
                $arg .= '&quot;loop&quot;: true,';
                $arg .= '&quot;autoplay&quot;: true,';
                $arg .= '&quot;autoplayTimeout&quot;: 7000';
            $arg .= '}';
            
            return $arg;
        }
        
        /**
         * Allowed HTML Tags in Slider Text Area
         *
         * @since 1.1.6
         */
        function allowed_html() {
            return array( 
                'a'  => array( 'href'  => array() ),
                'p'  => array(),
                'br' => array(), 
                'em' => array(),
                'ol' => array(),
                'ul' => array(), 
                'li' => array(), 
                'strong' => array()
            );
        }

        /**
         * Get Active Slides
         *
         * @access public
         * @since 1.1.6
         */
        public function get_slides() {
            $this->slides = array(
                1 => array(
                    'enabled'       => get_theme_mod( 'fury_slide_1_enable', true ),
                    'text'          => get_theme_mod( 'fury_slide_1_text', esc_attr__( 'Learn more about Fury', 'fury' ) ),
                    'image'         => get_theme_mod( 'fury_slide_1_image', $this->path_uri . 'assets/img/slide-image.png' ),
                    'button'        => get_theme_mod( 'fury_slide_1_button', true ),
                    'button_url'    => get_theme_mod( 'fury_slide_1_button_url', '#' ),
                    'button_title'  => get_theme_mod( 'fury_slide_1_button_title', esc_attr__( 'Learn More', 'fury' ) )
                ),
                2 => array(
                    'enabled'       => get_theme_mod( 'fury_slide_2_enable', true ),
                    'text'          => get_theme_mod( 'fury_slide_2_text', esc_attr__( 'Learn more about Fury', 'fury' ) ),
                    'image'         => get_theme_mod( 'fury_slide_2_image', $this->path_uri . 'assets/img/slide-image.png' ),
                    'button'        => get_theme_mod( 'fury_slide_2_button', true ),
                    'button_url'    => get_theme_mod( 'fury_slide_2_button_url', '#' ),
                    'button_title'  => get_theme_mod( 'fury_slide_2_button_title', esc_attr__( 'Learn More', 'fury' ) )
                )
            );
            
            return $this->slides;
        }

        /**
         * Slider Initialization
         *
         * @since 1.1.6
         */
        function init() {

            if( $this->enabled ) {
                if( ! empty( $this->on_pages ) ) {
                    $enabled = $this->enabled ? is_page( $this->on_pages ) : false;
                } else {
                    $enabled = $this->enabled ? is_home() : false;
                }
            } else {
                $enabled = false;
            }
            
            if( $enabled ) {
            
                $html  = '<section class="hero-slider" style="background:url('. $this->background .');">';
                    $html .= '<div class="owl-carousel large-controls dots-inside" data-owl-carousel="'. $this->data_args .'">';
                        foreach( $this->get_slides() as $slide ) {
                            if( $slide['enabled'] ) {
                                $html .= '<div class="item">';
                                    $html .= '<div class="container padding-top-3x">';
                                        $html .= '<div class="row justify-content-center align-items-center">';
                                            $html .= '<div class="col-lg-5 col-md-6 padding-bottom-2x text-md-left text-center">';
                                                $html .= '<div class="from-bottom">';
                                                    if( $slide['text'] ) {
                                                        $html .= '<div class="h2 text-body text-normal mb-4 pb-1">';
                                                            $html .= wp_kses( $slide['text'], $this->allowed_html );
                                                        $html .= '</div>';
                                                    }
                                                $html .= '</div>';
                                                if( $slide['button'] ) {
                                                    $html .= '<a class="btn btn-primary scale-up delay-1"';
                                                    $html .= ' href="'. esc_url( $slide['button_url'] ) .'">';
                                                    $html .= esc_html( $slide['button_title'] ) .'</a>';
                                                }
                                            $html .= '</div>';
                                            if( $slide['image'] ) {
                                                $html .= '<div class="col-md-6 padding-bottom-2x mb-3">';
                                                    $html .= '<img class="d-block mx-auto"';
                                                    $html .= ' src="'. esc_url( $slide['image'] ) .'"';
                                                    $html .= ' alt="'. strip_tags( $slide['text'] ) .'">';
                                                $html .= '</div>';
                                            }
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            }
                        }
                    $html .= '</div>';
                $html .= '</section>';

                echo $html;
            }
        }
    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
