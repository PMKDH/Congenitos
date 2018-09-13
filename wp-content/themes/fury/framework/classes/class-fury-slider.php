<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Fury Slider Defaults
get_template_part( 'framework/classes/abstract/class-fury-slider-defaults' );

/**
 * Fury Slider Initialization
 *
 * @since 1.1.6
 */
if( ! class_exists( 'Fury_Slider' ) ) {
    final class Fury_Slider extends Fury_Slider_Defaults {

        /**
         * Class Constructor
         */
        function __construct() {

            parent::__construct();

        }

    }
    new Fury_Slider;
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
