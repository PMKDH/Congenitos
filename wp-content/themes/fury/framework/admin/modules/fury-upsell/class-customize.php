<?php
/**
 * Singleton class file.
 *
 * @package ThemeVision
 * @subpackage Fury
 */

if( ! is_plugin_active( 'fury-pro/fury-pro.php' ) ) {

    /**
     * Singleton class for handling the theme's customizer integration.
     *
     * @since  1.1.6
     * @access public
     */
    final class Fury_Customizer_Upsell {

        /**
         * Returns the instance.
         *
         * @since  1.1.6
         * @access public
         * @return object
         */
        public static function get_instance() {

            static $instance = null;

            if ( is_null( $instance ) ) {
                $instance = new self;
                $instance->setup_actions();
            }

            return $instance;
        }

        /**
         * Constructor method.
         *
         * @since  1.1.6
         * @access private
         * @return void
         */
        private function __construct() {}

        /**
         * Sets up initial actions.
         *
         * @since  1.1.6
         * @access private
         * @return void
         */
        private function setup_actions() {

            // Register panels, sections, settings, controls, and partials.
            add_action( 'customize_register', array( $this, 'sections' ) );

            // Register scripts and styles for the controls.
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
        }

        /**
         * Sets up the customizer sections.
         *
         * @since  1.1.6
         * @access public
         * @param  object $manager Customizer manager.
         * @return void
         */
        public function sections( $manager ) {

            // Load custom sections.
            get_template_part( 'framework/admin/modules/fury-upsell/class-fury-customize-theme-info-main' );
            get_template_part( 'framework/admin/modules/fury-upsell/class-fury-customize-upsell-section' );

            // Register custom section types.
            $manager->register_section_type( 'Fury_Customizer_Theme_Info_Main' );

            // Main Documentation Link In Customizer Root.
            $manager->add_section(
                new Fury_Customizer_Theme_Info_Main(
                    $manager, 
                    'fury-theme-info', 
                    array(
                        'theme_info_title' => esc_html__( 'Fury Pro', 'fury' ),
                        'label_url'        => esc_url( 'https://theme-vision.com/product/fury-pro/' ),
                        'label_text'       => esc_html__( 'Upgrade to Pro', 'fury' ),
                        'priority'         => 1
                    )
                )
            );

            // Frontpage Sections Upsell.
            $manager->add_section(
                new Fury_Customizer_Upsell_Section(
                    $manager, 
                    'fury-upsell-slider-sections', 
                    array(
                        'panel'       => 'fury_slider_panel',
                        'priority'    => 500,
                        'options'     => array(
                            esc_html__( 'Slide #3', 'fury' ),
                            esc_html__( 'Slide #4', 'fury' ),
                            esc_html__( 'Slide #5', 'fury' ),
                            esc_html__( 'Slide #6', 'fury' ),
                            esc_html__( 'Slide #7', 'fury' ),
                            esc_html__( 'Slide #8', 'fury' ),
                            esc_html__( 'Slide #9', 'fury' ),
                            esc_html__( 'Slide #10', 'fury' ),
                        ),
                        'button_url'  => esc_url( 'https://theme-vision.com/product/fury-pro/' ),
                        'button_text' => esc_html__( 'Upgrade to PRO', 'fury' ),
                    )
                )
            );
        }

        /**
         * Loads theme customizer CSS.
         *
         * @since  1.1.6
         * @access public
         * @return void
         */
        public function enqueue_control_scripts() {

            wp_enqueue_script( 'fury-upsell-js', fury_modules_url . 'fury-upsell/js/fury-upsell-customize-controls.js', array( 'customize-controls' ), fury_version );

            wp_enqueue_style( 'fury-theme-info-style', fury_modules_url . 'fury-upsell/css/style.css', array(), fury_version );

        }
    }

    Fury_Customizer_Upsell::get_instance();
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
