<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fury Admin Menu Class
 *
 * @since 1.0
 */
class Fury_Admin_Menu {
    
    /**
     * Class Constructor
     */
    function __construct() {
        if( ! class_exists( 'Fury_Pro_Plugin' ) ) {
            $this->register_menu();
            add_action( 'admin_menu', array( $this, 'register_submenu' ) );
        }
    }
    
    /**
     * Register Main Menu
     *
     * @since 1.0
     */
    function register_menu() {
        add_menu_page(
            __( 'Fury Theme Panel', 'fury-core' ),
            __( 'Fury Theme', 'fury-core' ),
            'read',
            'fury-theme-panel',
            array(
                $this,
                'fury_theme_panel'
            ),
            'dashicons-admin-generic',
            999
        );
    }
    
    /**
     * Register SubMenu
     *
     * @since 1.0
     */
    function register_submenu() {
        add_submenu_page(
            'fury-theme-panel',
            esc_html__( 'Upgrade to PRO', 'fury-core' ),
            '<strong style="color:#0da9ef;">'. esc_html__( 'Upgrade to PRO', 'fury-core' ) .'</strong>',
            'manage_options', 
            'fury-theme-extensions',
            array(
                $this,
                'fury_theme_extensions'
            )
        );
    }
    
    /**
     * Render Fury Theme Panel Page
     *
     * @since 1.0
     */
    function fury_theme_panel() { ?>
        <div class="wrap about-wrap full-width-layout">
            
            <h1><?php esc_html_e( 'Fury Core Theme Panel', 'fury-core' ); ?></h1>
            
            <p class="about-text">
                <?php $string = esc_html__( 'Fury Core plugin extends Fury WordPress Theme with page/post options metabox.', 'fury-core' ); 
                $string = str_replace( 'Fury WordPress Theme', '<a href="https://wordpress.org/themes/fury/" target="_blank">Fury WordPress Theme</a>', $string );
                echo $string;
                ?>
            </p>
            
            <h2 class="nav-tab-wrapper wp-clearfix">
                <a class="nav-tab nav-tab-active"><?php esc_html_e( 'What\'s New', 'fury-core' ); ?></a>
            </h2>
            
            <div class="changelog point-releases">
                <h3><?php esc_html_e( 'Maintenance and Security Releases', 'fury-core' ); ?></h3>
                <p><strong><?php esc_html_e( 'Version', 'fury-core' ); ?> 1.0.3</strong>
                    <?php esc_html_e( 'minor changes', 'fury-core' ) ?>. (06 July 2018)</p>
            </div>
            
            <div class="changelog">
                <h2><?php esc_html_e( 'Fury Core Plugin Features', 'fury-core' ); ?></h2>
                <div class="under-the-hood">
                    <div class="col">
                        <p><?php esc_html_e( 'Extends Fury theme with theme options metabox so you can easily turn (on | off) theme features for single posts or pages.', 'fury-core' ); ?></p>
                        <img src="<?php echo esc_url( Fury_Plugin_url . 'assets/img/fury-core-metabox.jpg' ); ?>">
                    </div>
                </div>
            </div>
            
        </div>
    <?php
    }
    
    /**
     * Render Fury Theme Extensions Page
     *
     * @since 1.0
     */
    function fury_theme_extensions() {
        $extension['fury-slider'] = array(
            'name'          => esc_html__( 'Fury Slider', 'fury-core' ),
            'slug'          => 'fury-slider',
            'author'        => 'Theme Vision',
            'author_uri'    => esc_url( 'https://theme-vision.com/' ),
            'logo_uri'      => esc_url( Fury_Plugin_url . 'assets/img/fury-slider-cover.png' ),
            'demo_uri'      => 'http://furytheme.com/'
        ); ?>
        <div class="wrap about-wrap full-width-layout">
            
            <h1><?php esc_html_e( 'Fury PRO', 'fury-core' ); ?> ($49)</h1>
            
            <p class="about-text">
                <?php $string = esc_html__( 'Fury PRO extension (plugin) adds extra features to Fury theme. Fury PRO also provide you a premium support on theme-vision.com forums with high priority for 6 months and once premium support is expired you can always extend it for next 6 months just for 7$.', 'fury-core' ); 
                echo str_replace( 'Fury theme', '<a href="https://wordpress.org/themes/fury/" target="_blank">Fury theme</a>', $string ); ?>
            </p>
            
            <div class="feature-section">
                <h2>
                    <a href="https://theme-vision.com/product/fury-pro/" target="_blank">
                        <?php esc_html_e( 'Upgrade to Fury PRO by Clicking on This URL', 'fury-core' ); ?>
                    </a>
                </h2>
            </div>
            
            <div class="changelog">
                <h2>Fury PRO Extension Features</h2>
                <div class="under-the-hood three-col">
                    <div class="col">
                        <h3>WPBakery Page Builder</h3>
                        <p>WPBakery page builder is premium plugin which extends Fury theme with drag & drop page building and tons of nice shortcodes build specially for Fury theme.</p>
                    </div>
                    <div class="col">
                        <h3>Fury Slider</h3>
                        <p>Fury Slider extends Fury Theme with nice looking and responsive slider feature specailly build for Fury theme.</p>
                    </div>
                    <div class="col">
                        <h3>Layer Slider</h3>
                        <p>Layer Slider is premium plugin which extends Fury theme with nice and responsive slider feature with drag & drop features.</p>
                    </div>
                </div>
                <div class="under-the-hood three-col">
                    <div class="col">
                        <h3>Revolution Slider</h3>
                        <p>Revolution Slider extends Fury Theme with nice looking and responsive slider feature with drag & drop features.</p>
                    </div>
                    <div class="col">
                        <h3>Customizer</h3>
                        <p>Fury PRO extension extends fury theme customize options with many new features which are listed on our website.</p>
                    </div>
                </div>
            </div>
            
        </div>
    <?php
    }
    
}
new Fury_Admin_Menu();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
