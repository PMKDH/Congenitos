<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include Butterbean Metabox Framework
require_once( Fury_Plugin_dir . 'includes/metabox/butterbean/butterbean.php' );

/**
 * Fury Theme Metabox
 *
 * @since 1.0
 */
class Fury_Theme_Metabox {
    
    /**
     * Class Constructor
     *
     * @since 1.0
     */
    function __construct() {
        $this->post_types = array(
            'post',
            'page',
            'product',
            'elementor_library'
        );
        
        // Butterbean Register
        add_action( 'butterbean_register', array( $this, 'butterbean_register' ), 10, 2 );
        
        // Register Scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }
    
    /**
     * Admin Enqueue Scripts
     *
     * @since 1.0
     */
    function admin_enqueue_scripts() {
        
    }
    
    /**
     * Get Sliders Dropdown
     *
     * @since 1.0
     */
    function get_sliders_dropdown() {
        if( class_exists( 'Fury_Slider_Plugin') ) {
            
            // Default Sliders
            $sliders = array( 
                ''      => esc_html__( 'Select Slider', 'fury-core' ),
                'fury'  => esc_html__( 'Fury Slider', 'fury-core' ) 
            );
            
            // If Layer Slider active.
            /*
            if( class_exists( 'LS_Sliders' ) ) {
                $slider['layer'] = esc_html__( 'Layer Slider', 'fury-core' );
                $sliders = array_merge( $sliders, $slider );
            }*/
            
            // If Revolution slider active.
            /*
            if( class_exists( 'RevSliderAdmin' ) ) {
                $slider['revolution'] = esc_html__( 'Revolution Slider', 'fury-core' );
                $sliders = array_merge( $sliders, $slider );
            }*/
            
            return $sliders;
        }
    }
    
    /**
     * Fury Slider Categories
     *
     * @since 1.0
     */
    function fury_slider_categories() {
        $taxonomy = 'fury-slider-category';
        $category = array( 
            '' => __( 'Select Category', 'fury-core' ) 
        );
        
        $terms = get_terms( $taxonomy );
        
        if( $terms && ! is_wp_error( $terms ) ) {
            foreach( $terms as $term ) {
                $categories[$term->term_id] = $term->name;
            }
            $categories = array_replace( $category, $categories );
        } else {
            $categories = $category;
        }
        
        return $categories;
    }
    
    /**
     * Layer Slider Categories
     *
     * @since 1.0
     */
    function ls_slider_categories() {
        $category = array(
            '' => __( 'Select Category', 'fury-core' )
        );
        return $category;
    }
    
    /**
     * Revolution Slider Categories
     *
     * @since 1.0
     */
    function rev_slider_categories() {
        $category = array(
            '' => __( 'Select Category', 'fury-core' )
        );
        return $category;
    }
    
    /**
     * Butterbean Register
     *
     * @since 1.0
     */
    function butterbean_register( $butterbean, $post_type ) {
        $screen     = get_current_screen();
        $post_types = $this->post_types;
        $type       = $screen->post_type;
        
        $butterbean->register_manager(
            'fury_theme_metabox',
            array(
                'label'     => esc_html__( 'Fury Theme Options', 'fury-core' ),
                'post_type' => $post_types,
                'context'   => 'normal',
                'priority'  => 'high'
            )
        );
        
        $manager = $butterbean->get_manager( 'fury_theme_metabox' );
        
        ###########################
        # GENERAL SECTION
        ###########################
        $manager->register_section(
            'fury_metabox_tab_main',
            array(
                'label' => esc_html__( 'General', 'fury-core' ),
                'icon'  => 'dashicons-admin-generic'
            )
        );
        $manager->register_control(
            '_fury_title',
            array(
                'section'           => 'fury_metabox_tab_main',
                'type'              => 'select',
                'label'             => sprintf( esc_html__( '%s Title', 'fury-core' ), ucfirst( $type ) ),
                'description'       => sprintf( esc_html__( 'Enable or disable %s title.', 'fury-core' ), $type ),
                'choices'           => array(
                    ''              => esc_html__( 'Default', 'fury-core' ),
                    'on'            => esc_html__( 'Enable', 'fury-core' ),
                    'off'           => esc_html__( 'Disable', 'fury-core' )
                )
            )
        );
        $manager->register_setting(
            '_fury_title',
            array(
                'sanitize_callback' => 'sanitize_key'
            )
        );
        $manager->register_control(
            '_fury_social_share',
            array(
                'section'       => 'fury_metabox_tab_main',
                'type'          => 'select',
                'label'         => esc_html__( 'Social Share', 'fury-core' ),
                'description'   => sprintf( esc_html__( 'Enable social share icons for this %s.', 'fury-core' ), $type ),
                'choices'       => array(
                    ''          => esc_html__( 'Default', 'fury-core' ),
                    'on'        => esc_html__( 'Enable', 'fury-core' ),
                    'off'       => esc_html__( 'Disable', 'fury-core' )
                )
            )
        );
        $manager->register_setting(
            '_fury_social_share',
            array(
                'sanitize_callback' => 'sanitize_key'
            )
        );
        $manager->register_control(
            '_fury_sidebar_layout',
            array(
                'section' 		=> 'fury_metabox_tab_main',
                'type'    		=> 'select',
                'label'   		=> esc_html__( 'Sidebar', 'fury-core' ),
                'description'   => esc_html__( 'Select sidebar posititon.', 'fury-core' ),
                'choices' 		=> array(
                    ''              => esc_html__( 'Default', 'fury-core' ),
                    'right-sidebar' => esc_html__( 'Right Sidebar', 'fury-core' ),
                    'left-sidebar' 	=> esc_html__( 'Left Sidebar', 'fury-core' ),
                    'none'          => esc_html__( 'No Sidebar', 'fury-core' )
                )
            )
        );
        $manager->register_setting(
            '_fury_sidebar_layout',
            array(
                'sanitize_callback' => 'sanitize_key',
            )
        );
        ###########################
        # HEADER SECTION
        ###########################
        $manager->register_section(
            'fury_metabox_tab_header',
            array(
                'label' => esc_html__( 'Header', 'fury-core' ),
                'icon'  => 'dashicons-editor-kitchensink'
            )
        );
        $manager->register_control(
            '_fury_logo',
            array(
                'section' 		=> 'fury_metabox_tab_header',
                'type'    		=> 'image',
                'label'   		=> esc_html__( 'Logo', 'fury-core' ),
                'description'   => sprintf( esc_html__( 'Select custom logo for this %s.', 'fury-core' ), $type )
            )
        );
        $manager->register_setting(
            '_fury_logo',
            array(
                'sanitize_callback' => 'sanitize_key',
            )
        );
        if( class_exists( 'Fury_Slider_Plugin' ) ) {
            ###########################
            # SLIDER SECTION
            ###########################
            $manager->register_section(
                'fury_metabox_tab_slider',
                array(
                    'label' => esc_html__( 'Slider', 'fury-core' ),
                    'icon'  => 'dashicons-slides'
                )
            );
            $manager->register_control(
                '_fury_slider_enable',
                array(
                    'section'       => 'fury_metabox_tab_slider',
                    'type'          => 'select',
                    'label'         => esc_html__( 'Enable', 'fury-core' ),
                    'description'   => sprintf( esc_html__( 'Enable slider on this %s.', 'fury-core' ), $type ),
                    'choices'       => array(
                        'off'       => esc_html__( 'Disable', 'fury-core' ),
                        'on'        => esc_html__( 'Enable', 'fury-core' )
                    )
                )
            );
            $manager->register_setting(
                '_fury_slider_enable',
                array(
                    'sanitize_callback' => 'sanitize_key'
                )
            );
            $manager->register_control(
                '_fury_slider_type',
                array(
                    'section'       => 'fury_metabox_tab_slider',
                    'type'          => 'select',
                    'label'         => esc_html__( 'Slider Type', 'fury-core' ),
                    'description'   => esc_html__( 'Select slider type.', 'fury-core' ),
                    'choices'       => $this->get_sliders_dropdown()
                )
            );
            $manager->register_setting(
                '_fury_slider_type',
                array(
                    'sanitize_callback' => 'sanitize_key'
                )
            );
            $manager->register_control(
                '_fury_slider_category',
                array(
                    'section'       => 'fury_metabox_tab_slider',
                    'type'          => 'select',
                    'label'         => esc_html__( 'Slider Category', 'fury-core' ),
                    'description'   => esc_html__( 'Select slider category.', 'fury-core' ),
                    'choices'       => $this->fury_slider_categories()
                )
            );
            $manager->register_setting(
                '_fury_slider_category',
                array(
                    'sanitize_callback' => 'sanitize_key'
                )
            );
        }
        // Layer Slider Categories
        /*
        if( class_exists( 'LS_Sliders' ) ) {
            $manager->register_control(
                '_fury_ls_slider_category',
                array(
                    'section'       => 'fury_metabox_tab_slider',
                    'type'          => 'select',
                    'label'         => esc_html__( 'Slider Category', 'fury-core' ),
                    'description'   => esc_html__( 'Select slider category.', 'fury-core' ),
                    'choices'       => $this->ls_slider_categories()
                )
            );
            $manager->register_setting(
                '_fury_ls_slider_category',
                array(
                    'sanitize_callback' => 'sanitize_key'
                )
            );
        }*/
        // Revolution Slider Categories
        /*
        if( class_exists( 'RevSliderAdmin' ) ) {
            $manager->register_control(
                '_fury_rev_slider_category',
                array(
                    'section'       => 'fury_metabox_tab_slider',
                    'type'          => 'select',
                    'label'         => esc_html__( 'Slider Category', 'fury-core' ),
                    'description'   => esc_html__( 'Select slider category.', 'fury-core' ),
                    'choices'       => $this->rev_slider_categories()
                )
            );
            $manager->register_setting(
                '_fury_rev_slider_category',
                array(
                    'sanitize_callback' => 'sanitize_key'
                )
            );
        }*/
    }
}
new Fury_Theme_Metabox();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
