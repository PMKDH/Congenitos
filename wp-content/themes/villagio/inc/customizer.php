<?php
/**
 * Villagio Theme Customizer
 *
 * @package Villagio
 */


/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since Villagio 1.0
 *
 * @see villagio_header_style()
 */
function villagio_custom_header_and_background()
{
    $color_scheme = villagio_get_color_scheme();
    $default_background_color = trim($color_scheme[0], '#');
    $default_text_color = trim($color_scheme[3], '#');

    /**
     * Filter the arguments used when adding 'custom-background' support in Villagio.
     *
     * @since Villagio 1.0
     *
     * @param array $args {
     *     An array of custom-background support arguments.
     *
     * @type string $default -color Default color of the background.
     * }
     */
    add_theme_support('custom-background', apply_filters('villagio_custom_background_args', array(
        'default-color' => $default_background_color,
    )));

    /**
     * Filter the arguments used when adding 'custom-header' support in Villagio.
     *
     * @since Villagio 1.0
     *
     * @param array $args {
     *     An array of custom-header support arguments.
     *
     * @type string $default -text-color Default color of the header text.
     * @type int $width Width in pixels of the custom header image. Default 1200.
     * @type int $height Height in pixels of the custom header image. Default 280.
     * @type bool $flex -height      Whether to allow flexible-height header images. Default true.
     * @type callable $wp -head-callback Callback function used to style the header image and text
     *                                      displayed on the blog.
     * }
     */
    add_theme_support('custom-header', apply_filters('villagio_custom_header_args', array(
        'default-text-color' => $default_text_color,
        'width' => 1000,
        'height' => 250,
        'flex-height' => true,
        'wp-head-callback' => 'villagio_header_style',
    )));
    add_theme_support('custom-logo', array(
        'height' => 40,
        'width' => 40,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
    ));
}

add_action('after_setup_theme', 'villagio_custom_header_and_background');

if (!function_exists('villagio_header_style')) :
    /**
     * Styles the header text displayed on the site.
     *
     * Create your own villagio_header_style() function to override in a child theme.
     *
     * @since Villagio 1.0
     *
     * @see villagio_custom_header_and_background().
     */
    function villagio_header_style()
    {
        // If the header text option is untouched, let's bail.
        if (display_header_text()) {
            return;
        }

        // If the header text has been hidden.
        ?>
        <style type="text/css" id="villagio-header-css">
            @media (max-width: 47.9375em) {
                .custom-logo-link {
                    margin-bottom: 1.2em;
                }
            }

            .site-title-wrapper,
            .site-branding .site-title,
            .site-description {
                clip: rect(1px, 1px, 1px, 1px);
                position: absolute;
            }
        </style>
        <?php
    }
endif; // villagio_header_style

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @since Villagio 1.0
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function villagio_customize_register($wp_customize)
{
    $color_scheme = villagio_get_color_scheme();

    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title a',
            'container_inclusive' => false,
            'render_callback' => 'villagio_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.site-description',
            'container_inclusive' => false,
            'render_callback' => 'villagio_customize_partial_blogdescription',
        ));
    }


    // Remove the core header textcolor control, as it shares the main text color.
    $wp_customize->remove_control('header_textcolor');
    //Remove the core header image control.
    $wp_customize->remove_control('header_image');


// Add main text color setting and control.
    $wp_customize->add_setting('main_text_color', array(
        'default' => $color_scheme[1],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'main_text_color', array(
        'label' => esc_html__('Main Text Color', 'villagio'),
        'section' => 'colors',
    )));

    // Add Brand color setting and control.
    $wp_customize->add_setting('brand_color', array(
        'default' => $color_scheme[2],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'brand_color', array(
        'label' => esc_html__('Brand Color', 'villagio'),
        'section' => 'colors',
    )));

    // Add Hover Brand color setting and control.
    $wp_customize->add_setting('brand_color_hover', array(
        'default' => $color_scheme[3],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'brand_color_hover', array(
        'label' => esc_html__('Brand Color Hover for Buttons', 'villagio'),
        'section' => 'colors',
    )));

    /*
     * Add 'Theme Options' section
     */
    $wp_customize->add_section(
        'villagio_theme_options', array(
            'title' => esc_html__('Theme Options', 'villagio'),
            'priority' => 30,
            'capability' => 'edit_theme_options'
        )
    );
    /*
     *  Add the 'Front page slider animation' setting.
     */
    $wp_customize->add_setting(
        'villagio_slider_animation', array(
            'default' => 'slide',
            'sanitize_callback' => 'villagio_sanitize_text',
        )
    );

    /*
     *  Add the  'Enable slideshow' setting.
     */
    $wp_customize->add_setting('villagio_slideshow', array(
        'default' => 0,
        'sanitize_callback' => 'villagio_sanitize_checkbox',
    ));
    /*
     * Add the upload control for the  'Enable slideshow' setting.
     */
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize, 'villagio_slideshow', array(
            'label' => esc_html__('Enable Front Page slideshow', 'villagio'),
            'section' => 'villagio_theme_options',
            'settings' => 'villagio_slideshow',
            'type' => 'checkbox'
        ))
    );

    /*
     * Add the upload control for the 'Front page slider animation' setting.
     */
    $wp_customize->add_control(
        'villagio_slider_animation', array(
            'label' => esc_html__('Animation', 'villagio'),
            'type' => 'select',
            'choices' => array(
                'slide' => esc_html__('Slide', 'villagio'),
                'fade' => esc_html__('Fade', 'villagio')
            ),
            'section' => 'villagio_theme_options',
            'settings' => 'villagio_slider_animation'
        )
    );

    /*
	 *  Add the  'Slideshow speed' setting.
	 */
    $wp_customize->add_setting('villagio_slideshow_speed', array(
        'default' => 7000,
        'sanitize_callback' => 'villagio_sanitize_positive_integer',
    ));
    /*
     * Add the upload control for the  'Slideshow speed' setting.
     */
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize, 'villagio_slideshow_speed', array(
            'label' => esc_html__('Speed (ms)', 'villagio'),
            'section' => 'villagio_theme_options',
            'settings' => 'villagio_slideshow_speed'
        ))
    );

}

add_action('customize_register', 'villagio_customize_register', 11);

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Villagio 1.2
 * @see villagio_customize_register()
 *
 * @return void
 */
function villagio_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Villagio 1.2
 * @see villagio_customize_register()
 *
 * @return void
 */
function villagio_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Registers color schemes for Villagio.
 *
 * Can be filtered with {@see 'villagio_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Page Background Color.
 * 3. Link Color.
 * 4. Main Text Color.
 * 5. Secondary Text Color.
 *
 * @since Villagio 1.0
 *
 * @return array An associative array of color scheme options.
 */
function villagio_get_color_schemes()
{
    /**
     * Filter the color schemes registered for use with Villagio.
     *
     * The default schemes include 'default', 'dark', 'gray', 'red', and 'yellow'.
     *
     * @since Villagio 1.0
     *
     * @param array $schemes {
     *     Associative array of color schemes data.
     *
     * @type array $slug {
     *         Associative array of information for setting up the color scheme.
     *
     * @type string $label Color scheme label.
     * @type array $colors HEX codes for default colors prepended with a hash symbol ('#').
     *                              Colors are defined in the following order: Main background, page
     *                              background, link, main text, secondary text.
     *     }
     * }
     */
    return apply_filters('villagio_color_schemes', array(
        'default' => array(
            'label' => esc_html__('Default', 'villagio'),
            'colors' => array(
                '#ececec',
                '#333333',
                '#01bea0',
                '#333333',
            ),
        )
    ));
}

if (!function_exists('villagio_get_color_scheme')) :
    /**
     * Retrieves the current Villagio color scheme.
     *
     * Create your own villagio_get_color_scheme() function to override in a child theme.
     *
     * @since Villagio 1.0
     *
     * @return array An associative array of either the current or default color scheme HEX values.
     */
    function villagio_get_color_scheme()
    {
        $color_scheme_option = get_theme_mod('color_scheme', 'default');
        $color_schemes = villagio_get_color_schemes();

        if (array_key_exists($color_scheme_option, $color_schemes)) {
            return $color_schemes[$color_scheme_option]['colors'];
        }

        return $color_schemes['default']['colors'];
    }
endif; // villagio_get_color_scheme

if (!function_exists('villagio_get_color_scheme_choices')) :
    /**
     * Retrieves an array of color scheme choices registered for Villagio.
     *
     * Create your own villagio_get_color_scheme_choices() function to override
     * in a child theme.
     *
     * @since Villagio 1.0
     *
     * @return array Array of color schemes.
     */
    function villagio_get_color_scheme_choices()
    {
        $color_schemes = villagio_get_color_schemes();
        $color_scheme_control_options = array();

        foreach ($color_schemes as $color_scheme => $value) {
            $color_scheme_control_options[$color_scheme] = $value['label'];
        }

        return $color_scheme_control_options;
    }
endif; // villagio_get_color_scheme_choices


if (!function_exists('villagio_sanitize_color_scheme')) :
    /**
     * Handles sanitization for Villagio color schemes.
     *
     * Create your own villagio_sanitize_color_scheme() function to override
     * in a child theme.
     *
     * @since Villagio 1.0
     *
     * @param string $value Color scheme name value.
     *
     * @return string Color scheme name.
     */
    function villagio_sanitize_color_scheme($value)
    {
        $color_schemes = villagio_get_color_scheme_choices();

        if (!array_key_exists($value, $color_schemes)) {
            return 'default';
        }

        return $value;
    }
endif; // villagio_sanitize_color_scheme

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @since Villagio 1.0
 *
 * @see wp_add_inline_style()
 */
function villagio_color_scheme_css()
{
    $color_scheme_option = get_theme_mod('color_scheme', 'default');

    // Don't do anything if the default color scheme is selected.
    if ('default' === $color_scheme_option) {
        return;
    }

    $color_scheme = villagio_get_color_scheme();


    // If we get this far, we have a custom color scheme.
    $colors = array(
        'background_color' => $color_scheme[0],
        'main_text_color' => $color_scheme[1],
        'brand_color' => $color_scheme[2],
        'secondary_text_color' => $color_scheme[3],

    );

    $color_scheme_css = villagio_get_color_scheme_css($colors);

    wp_add_inline_style('villagio-style', $color_scheme_css);
}

add_action('wp_enqueue_scripts', 'villagio_color_scheme_css');

/**
 * Returns CSS for the color schemes.
 *
 * @since Villagio 1.0
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function villagio_get_color_scheme_css($colors)
{
    $colors = wp_parse_args($colors, array(
        'background_color' => '',
        'main_text_color' => '',
        'brand_color' => '',
        'brand_color_hover' => ''
    ));

    return <<<CSS
	/* Color Scheme */

	/* Background Color */
	body {
		background-color: {$colors['background_color']};
	}
	/* Brand Color */
	button, .button, input[type="button"], input[type="reset"], input[type="submit"],
	blockquote {
	  border-color:{$colors['brand_color']};
	}
	
	a:hover,
	.footer-navigation a:hover,
	.top-navigation-right a:hover,
	.main-navigation a:hover,
	.search-form .search-submit:hover,
	.footer-navigation .current_page_item > a,
	.footer-navigation .current-menu-item > a,
	.footer-navigation .current_page_ancestor > a,
	.footer-navigation .current-menu-ancestor > a,
	.top-navigation-right .current_page_item > a,
	.top-navigation-right .current-menu-item > a,
	.top-navigation-right .current_page_ancestor > a,
	.top-navigation-right .current-menu-ancestor > a,
	.main-navigation .current_page_item > a,
	.main-navigation .current-menu-item > a,
	.main-navigation .current_page_ancestor > a,
	.main-navigation .current-menu-ancestor > a,
	.post-navigation a:hover .no-bg .post-title,
	.menu-toggle:hover,
	.entry-title a:hover,
	.entry-footer a:hover,
	.comment-content a,
	.entry-content a,
	.entry-summary a,
	.page-content a,
	.more-link,
	.comment-metadata a.comment-edit-link,
	.comment-reply-link,
	.author-info a,
	.related-posts a,
	.widget.widget_calendar tbody a,
	.widget.widget_wpcom_social_media_icons_widget a.genericon:hover
	 {
	  color: {$colors['brand_color']};
	}

	button, .button, input[type="button"], input[type="reset"], input[type="submit"],
	body .mphb_room_type_categories_header_wrapper a:hover,
	body .mphb-room-type  .mphb-flexslider .flexslider ul.flex-direction-nav a:hover,
	body .mphb-room-type  .flexslider ul.flex-direction-nav a:hover ,
	.pagination a.prev:hover, .pagination a.next:hover{
	  background-color: {$colors['brand_color']};
	}
	body .site-content .mphb_sc_services-wrapper .more-link .button,
	body .site-content .mphb_sc_services-wrapper .mphb-service-title a:hover,
	body .mphb-calendar .datepick-ctrl .datepick-cmd:hover, 
	body .datepick-popup .datepick-ctrl .datepick-cmd:hover,
	body .site-content .mphb-view-details-button:hover,
	body .mphb-loop-room-type-attributes a:hover,
	body .mphb-single-room-type-attributes a:hover,
	body.page-template-template-front-page .mphb-loop-room-type-attributes:last-of-type a:hover {
	  color: {$colors['brand_color']};
	} 	
	
	/* Brand Color  Hover*/	
    body  .mphb_room_type_categories_header_wrapper a:hover,
	body .mphb_room_type_categories_header_wrapper a:hover{
	  background-color:  {$colors['brand_color_hover']};
	}
	button:hover,
	.button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover {
	  background-color:  {$colors['brand_color_hover']};
	  border-color: {$colors['brand_color_hover']};
	}	
	
	/* Main Text Color */
	body,body .mphb_sc_checkout-wrapper .mphb-booking-details .mphb-check-out-date time,  body .mphb_sc_checkout-wrapper .mphb-booking-details .mphb-check-in-date time {
	  color: {$colors['main_text_color']};
	}
	mark, ins {
	  background: {$colors['main_text_color']};
	}
	/* Woocommerce */
    body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    body.woocommerce #respond input#submit, body.woocommerce a.button, body .woocommerce a.button,body.woocommerce button.button,body .woocommerce button.button, body.woocommerce input.button, body .woocommerce input.button{
	    border-color: {$colors['brand_color']};
    }
    body.woocommerce #respond input#submit.alt, body.woocommerce a.button.alt,body .woocommerce a.button.alt, body.woocommerce button.button.alt,body .woocommerce button.button.alt, body.woocommerce input.button.alt,
    body.woocommerce nav.woocommerce-pagination ul li a.prev:hover, body.woocommerce nav.woocommerce-pagination ul li a.next:hover,
    body.woocommerce #respond input#submit, body.woocommerce a.button,body .woocommerce a.button, body.woocommerce button.button,body .woocommerce button.button, body.woocommerce input.button, body .woocommerce input.button,
    body.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    body.woocommerce .widget_price_filter .ui-slider .ui-slider-handle{
        background-color: {$colors['brand_color']};
    }
    body .woocommerce a.remove:hover{ 
        color: {$colors['brand_color']};!important;
    }
    body.woocommerce p.stars.selected a.active::before, body.woocommerce p.stars.selected a:not(.active)::before, body.woocommerce p.stars:hover a::before, body.woocommerce p.stars a:hover::before,
    body.woocommerce div.product .woocommerce-tabs ul.tabs li a,
    body.woocommerce div.product div.images .woocommerce-product-gallery__trigger:hover,
    body.woocommerce .woocommerce-breadcrumb a,
    body.woocommerce .star-rating span::before{
        color: {$colors['brand_color']};;
    }
    body .site-header-cart .cart-contents .amount,
    body.woocommerce div.product .product_meta span > span,
    body.woocommerce div.product .woocommerce-variation-price .price, body.woocommerce div.product p.price,
    body.woocommerce ul.products li.product .price{
         {$colors['main_text_color']};
    }
    body.woocommerce #respond input#submit.alt:hover, body.woocommerce a.button.alt:hover,body .woocommerce a.button.alt:hover, body.woocommerce button.button.alt:hover,  body .woocommerce button.button.alt:hover, body.woocommerce input.button.alt:hover,  
    body.woocommerce #respond input#submit:hover, body.woocommerce a.button:hover,body .woocommerce a.button:hover, body.woocommerce button.button:hover, body .woocommerce button.button:hover, body.woocommerce input.button:hover,body .woocommerce input.button:hover{
        border-color:  {$colors['brand_color_hover']};
    } 
    body.woocommerce #respond input#submit.alt:hover, body.woocommerce a.button.alt:hover,body .woocommerce a.button.alt:hover, body.woocommerce button.button.alt:hover, body .woocommerce button.button.alt:hover, body.woocommerce input.button.alt:hover,
    body.woocommerce .widget_price_filter .ui-slider .ui-slider-handle:hover,
    body.woocommerce #respond input#submit:hover, body.woocommerce a.button:hover,body .woocommerce a.button:hover, body.woocommerce button.button:hover,body .woocommerce button.button:hover, body.woocommerce input.button:hover,body .woocommerce input.button:hover{
        background-color:  {$colors['brand_color_hover']};
    }        
CSS;
}


/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 *
 * @since Villagio 1.0
 */
function villagio_color_scheme_css_template()
{
    $colors = array(
        'background_color' => '{{ data.background_color }}',
        'main_text_color' => '{{ data.main_text_color }}',
        'brand_color' => '{{ data.brand_color }}',
        'brand_color_hover' => '{{ data.brand_color_hover }}',
    );
    ?>
    <script type="text/html" id="tmpl-villagio-color-scheme">
        <?php echo villagio_get_color_scheme_css($colors); ?>
    </script>
    <?php
}

add_action('customize_controls_print_footer_scripts', 'villagio_color_scheme_css_template');


/**
 * Enqueues front-end CSS for the link color.
 *
 * @since Villagio 1.0
 *
 * @see wp_add_inline_style()
 */
function villagio_brand_color_css()
{
    $color_scheme = villagio_get_color_scheme();
    $default_color = $color_scheme[2];
    $brand_color = get_theme_mod('brand_color', $default_color);

    // Don't do anything if the current color is the default.
    if ($brand_color === $default_color) {
        return;
    }

    $css = '
	button, .button, input[type="button"], input[type="reset"], input[type="submit"],
	blockquote {
	  border-color: %1$s;
	}
	
	a:hover,
	.footer-navigation a:hover,
	.top-navigation-right a:hover,
	.main-navigation a:hover,
	.search-form .search-submit:hover,
	.footer-navigation .current_page_item > a,
	.footer-navigation .current-menu-item > a,
	.footer-navigation .current_page_ancestor > a,
	.footer-navigation .current-menu-ancestor > a,
	.top-navigation-right .current_page_item > a,
	.top-navigation-right .current-menu-item > a,
	.top-navigation-right .current_page_ancestor > a,
	.top-navigation-right .current-menu-ancestor > a,
	.main-navigation .current_page_item > a,
	.main-navigation .current-menu-item > a,
	.main-navigation .current_page_ancestor > a,
	.main-navigation .current-menu-ancestor > a,
	.post-navigation a:hover .no-bg .post-title,
	.menu-toggle:hover,
	.entry-title a:hover,
	.entry-footer a:hover,
	.comment-content a,
	.entry-content a,
	.entry-summary a,
	.page-content a,
	.more-link,
	.comment-metadata a.comment-edit-link,
	.comment-reply-link,
	.author-info a,
	.related-posts a,
	.widget.widget_calendar tbody a,
	.widget.widget_wpcom_social_media_icons_widget a.genericon:hover {
	  color: %1$s;
	}
	button, .button, input[type="button"], input[type="reset"], input[type="submit"],
	.mphb_room_type_categories_header_wrapper a:hover,
	body .mphb-room-type .mphb-flexslider .flexslider ul.flex-direction-nav a:hover,
	body .mphb-room-type .flexslider ul.flex-direction-nav a:hover ,
	.pagination a.prev:hover, .pagination a.next:hover{
	  background-color: %1$s;
	}
	body .site-content .mphb_sc_services-wrapper .more-link .button,
	body .site-content .mphb_sc_services-wrapper .mphb-service-title a:hover,
	body .mphb-calendar .datepick-ctrl .datepick-cmd:hover, 
	body .datepick-popup .datepick-ctrl .datepick-cmd:hover,
	body .mphb-loop-room-type-attributes a:hover, .mphb-single-room-type-attributes a:hover,
	body .site-content .mphb-view-details-button:hover,
	body .mphb-loop-room-type-attributes a:hover,
	body .mphb-single-room-type-attributes a:hover,
	body.page-template-template-front-page .mphb-loop-room-type-attributes:last-of-type a:hover {
	  color: %1$s;
	} 	
	 
	';
    if (class_exists('WooCommerce')) {
        $css .= '
        body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
        body.woocommerce #respond input#submit, body.woocommerce a.button, body .woocommerce a.button,body.woocommerce button.button, body .woocommerce button.button, body.woocommerce input.button, body .woocommerce input.button{
	        border-color: %1$s;
        } 
        body.woocommerce #respond input#submit.alt, body.woocommerce a.button.alt, body .woocommerce a.button.alt, body.woocommerce button.button.alt,body .woocommerce button.button.alt, body.woocommerce input.button.alt,
        body.woocommerce nav.woocommerce-pagination ul li a.prev:hover, body.woocommerce nav.woocommerce-pagination ul li a.next:hover,
        body.woocommerce #respond input#submit, body.woocommerce a.button,body .woocommerce a.button, body.woocommerce button.button,body .woocommerce button.button, body.woocommerce input.button, body .woocommerce input.button,
        body.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
        body.woocommerce .widget_price_filter .ui-slider .ui-slider-handle{
            background-color: %1$s;
        }
        body .woocommerce a.remove:hover{ 
            color: %1$s!important;
        }
        body.woocommerce p.stars.selected a.active::before, body.woocommerce p.stars.selected a:not(.active)::before, body.woocommerce p.stars:hover a::before, body.woocommerce p.stars a:hover::before,
        body.woocommerce div.product .woocommerce-tabs ul.tabs li a,
        body.woocommerce div.product div.images .woocommerce-product-gallery__trigger:hover,
        body.woocommerce .woocommerce-breadcrumb a,
        body.woocommerce .star-rating span::before{
            color: %1$s;
       }

        ';
    }

    wp_add_inline_style('villagio-style', sprintf($css, $brand_color));
}

add_action('wp_enqueue_scripts', 'villagio_brand_color_css', 11);

/**
 * Enqueues front-end CSS for the main text color.
 *
 * @since Villagio 1.0
 *
 * @see wp_add_inline_style()
 */
function villagio_main_text_color_css()
{
    $color_scheme = villagio_get_color_scheme();
    $default_color = $color_scheme[1];
    $main_text_color = get_theme_mod('main_text_color', $default_color);

    // Don't do anything if the current color is the default.
    if ($main_text_color === $default_color) {
        return;
    }

    $css = '
		/* Custom Main Text Color */
		body ,body .mphb_sc_checkout-wrapper .mphb-booking-details .mphb-check-out-date time,  body .mphb_sc_checkout-wrapper .mphb-booking-details .mphb-check-in-date time{
	        color: %1$s;
		}
		mark, ins {
		     background: %1$s;
		}
	';
    if (class_exists('WooCommerce')) {
        $css .= '
            body .site-header-cart .cart-contents .amount,
            body.woocommerce div.product .product_meta span > span,
            body.woocommerce div.product .woocommerce-variation-price .price, body.woocommerce div.product p.price,
            body.woocommerce ul.products li.product .price{
                color: %1$s;
            }
        ';
    }
    wp_add_inline_style('villagio-style', sprintf($css, $main_text_color));
}

add_action('wp_enqueue_scripts', 'villagio_main_text_color_css', 11);


/**
 * Enqueues front-end CSS for the link color.
 *
 * @since Villagio 1.0
 *
 * @see wp_add_inline_style()
 */
function villagio_brand_color_hover_css()
{
    $color_scheme = villagio_get_color_scheme();
    $default_color = $color_scheme[3];
    $brand_color_hover = get_theme_mod('brand_color_hover', $default_color);

    // Don't do anything if the current color is the default.
    if ($brand_color_hover === $default_color) {
        return;
    }

    $css = '
	body .mphb_room_type_categories_header_wrapper a:hover,
	body .mphb_room_type_categories_header_wrapper a:hover{
	  background-color:  %1$s;
	}
	.entry-child-pages-list .more-link:hover,
	button:hover,
	.button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover {
	  background-color:  %1$s;
	  border-color:  %1$s;
	}	
	';
    if (class_exists('WooCommerce')) {
        $css .= '    
        body.woocommerce #respond input#submit.alt:hover, body.woocommerce a.button.alt:hover,body .woocommerce a.button.alt:hover, body.woocommerce button.button.alt:hover,body .woocommerce button.button.alt:hover, body.woocommerce input.button.alt:hover,  
        body.woocommerce #respond input#submit:hover, body.woocommerce a.button:hover,body .woocommerce a.button:hover, body.woocommerce button.button:hover,body .woocommerce button.button:hover, body.woocommerce input.button:hover, body .woocommerce input.button:hover{
            border-color:  %1$s;
        } 
        body.woocommerce #respond input#submit.alt:hover, body.woocommerce a.button.alt:hover, body .woocommerce a.button.alt:hover, body.woocommerce button.button.alt:hover,body .woocommerce button.button.alt:hover, body.woocommerce input.button.alt:hover,
        body.woocommerce .widget_price_filter .ui-slider .ui-slider-handle:hover,
        body.woocommerce #respond input#submit:hover, body.woocommerce a.button:hover,body .woocommerce a.button:hover, body.woocommerce button.button:hover,body .woocommerce button.button:hover, body.woocommerce input.button:hover ,body .woocommerce input.button:hover{
            background-color:  %1$s;
        }
        
        ';
    }

    wp_add_inline_style('villagio-style', sprintf($css, $brand_color_hover));
}

add_action('wp_enqueue_scripts', 'villagio_brand_color_hover_css', 11);


/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 */
function villagio_customize_control_js()
{
    wp_enqueue_script('color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array(
        'customize-controls',
        'iris',
        'underscore',
        'wp-util'
    ), '20160816', true);
    wp_localize_script('color-scheme-control', 'colorScheme', villagio_get_color_schemes());
}

add_action('customize_controls_enqueue_scripts', 'villagio_customize_control_js');

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 */
function villagio_customize_preview_js()
{
    wp_enqueue_script('villagio-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array('customize-preview'), '20160816', true);
}

add_action('customize_preview_init', 'villagio_customize_preview_js');

/**
 * Sanitize text
 */
function villagio_sanitize_text($txt)
{
    return wp_kses_post($txt);
}

/**
 * Sanitize checkbox
 */
function villagio_sanitize_checkbox($input)
{
    if ($input == 1) {
        return 1;
    } else {
        return '';
    }
}

/**
 * Sanitize position
 */
function villagio_sanitize_positive_integer($str)
{
    if (villagio_is_positive_integer($str)) {
        return intval($str);
    }
}

/**
 * Sanitize is positive integer
 */
function villagio_is_positive_integer($str)
{
    return (is_numeric($str) && $str > 0 && $str == round($str));
}
