<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include Fury Helper Class
get_template_part( 'framework/classes/class-fury-helper' );

// Include Kirki Framework File
get_template_part( 'framework/admin/kirki/kirki' );

/**
 * Override Kirki Textdomain
 *
 * @since 1.0
 */
function fury_kirki_textdomain( $l10n ) {
    $l10n['background-color']  = esc_attr__( 'Background Color', 'fury' );
    return $l10n;
}
add_filter( 'kirki/fury/l10n', 'fury_kirki_textdomain' );

// Include Dynamic CSS File
get_template_part( 'framework/classes/class-fury-dynamic-css' );

// Include Fury Upsell Class File
get_template_part( 'framework/admin/modules/fury-upsell/class-customize' );

/**
 * Customize Register
 *
 * @since 1.0
 */
function fury_customize_register( $wp_customize ) {
    
    // Remove WordPress "Colors" Feature From Customizer
    $wp_customize->remove_section( 'colors' );
    
    // Remove WooCommerce "Columns" Feature From Customizer
    $wp_customize->remove_control( 'woocommerce_catalog_columns' );
    
}
add_action( 'customize_register', 'fury_customize_register' );

#########################################
# THEME CONFIG
#########################################
Kirki::add_config( 'fury_option', array(
    'capability'    => 'edit_theme_options',
    'option_type'   => 'theme_mod'
) );
#################################################
# GENERAL PANEL
#################################################
Kirki::add_panel( 'fury_general_panel', array(
    'title'     => esc_attr__( 'General', 'fury' ),
    'priority'  => 30
) );
########################################################
# GENERAL BODY SECTION
########################################################
Kirki::add_section( 'fury_general_body_section', array(
    'title' => esc_attr__( 'Body', 'fury' ),
    'panel' => 'fury_general_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Body Font', 'fury' ),
    'tooltip'   => esc_attr__( 'Customize website body font.', 'fury' ),
    'settings'  => 'fury_body_font',
    'section'   => 'fury_general_body_section',
    'type'      => 'typography',
    'transport' => 'auto',
    'choices'   => array(
        'variant'   => array( 'standard', '500', '700', '900' )
    ),
    'default'   => array(
        'font-family'   => 'Maven Pro',
        'variant'       => 'regular',
        'font-size'     => '14px'
    ),
    'output'    => array(
        array(
            'element' => 'body'
        )
    )
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Body Background Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Customize body background color.', 'fury' ),
    'settings'  => 'fury_body_bg_color',
    'section'   => 'fury_general_body_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => 'body, div.offcanvas-wrapper',
            'property'  => 'background-color'
        )
    ),
    'default'   => '#ffffff'
) );
########################################################
# GENERAL EXTRA SECTION
########################################################
Kirki::add_section( 'fury_general_extra_section', array(
    'title' => esc_attr__( 'Extra', 'fury' ),
    'panel' => 'fury_general_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Custom jQuery Head', 'fury' ),
    'tooltip'   => esc_attr__( 'Add custom jQuery code into theme head area.', 'fury' ),
    'settings'  => 'fury_custom_jquery_head',
    'section'   => 'fury_general_extra_section',
    'type'      => 'code',
    'choices'   => array(
        'language' => 'js'
    )
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Custom jQuery Footer', 'fury' ),
    'tooltip'   => esc_attr__( 'Add custom jQuery code into theme footer area.', 'fury' ),
    'settings'  => 'fury_custom_jquery_footer',
    'section'   => 'fury_general_extra_section',
    'type'      => 'code',
    'choices'   => array(
        'language' => 'js'
    )
) );
########################################################
# GENERAL PAGES SECTION
########################################################
Kirki::add_section( 'fury_general_pages_section', array(
    'title' => esc_attr__( 'Pages', 'fury' ),
    'panel' => 'fury_general_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Page Title', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable page titles globally.', 'fury' ),
    'settings'  => 'fury_page_title',
    'section'   => 'fury_general_pages_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Page Meta', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable page meta globally. (Page author, category, comments count on very top.)', 'fury' ),
    'settings'  => 'fury_page_meta',
    'section'   => 'fury_general_pages_section',
    'type'      => 'switch',
    'default'   => true
) );
##########################################################
# GENERAL STYLING SECTION
##########################################################
Kirki::add_section( 'fury_general_styling_section', array(
    'title' => esc_attr__( 'Styling', 'fury' ),
    'panel' => 'fury_general_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Primary Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select theme primary color.', 'fury' ),
    'settings'  => 'fury_primary_color',
    'section'   => 'fury_general_styling_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.btn-outline-primary',
            'property'  => 'color'
        ),
        array(
            'element'   => '.woocommerce .product-badge',
            'property'  => 'background'
        ),
        array(
            'element'   => '.btn-primary, .btn-primary:hover, .btn-outline-primary:hover, .list-group-item.active, .pagination .page-numbers > li.active > span',
            'property'  => 'background-color'
        ),
        array(
            'element'   => '.btn-outline-primary, .list-group-item.active, .pagination .page-numbers > li.active > span',
            'property'  => 'border-color'
        )
    ),
    'default'   => '#0da9ef'
) );
##############################################################
# GENERAL COMMENTS SECTION
##############################################################
Kirki::add_section( 'fury_general_comments_section', array(
    'title' => esc_attr__( 'Comments', 'fury' ),
    'panel' => 'fury_general_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Tags Suggestion', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable tags suggestion below comment form.', 'fury' ),
    'settings'  => 'fury_comments_tags_suggestion',
    'section'   => 'fury_general_comments_section',
    'type'      => 'switch',
    'default'   => true
) );
#############################################
# LAYOUT
#############################################
Kirki::add_panel( 'fury_layout_panel', array(
    'title'     => esc_attr__( 'Layout', 'fury' ),
    'priority'  => 40
) );
#########################################################
# LAYOUT GENERAL SECTION
#########################################################
Kirki::add_section( 'fury_layout_general_section', array(
    'title' => esc_attr__( 'General', 'fury' ),
    'panel' => 'fury_layout_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Sidebar', 'fury' ),
    'tooltip'   => esc_attr__( 'Select sidebar type.', 'fury' ),
    'settings'  => 'fury_sidebar_layout',
    'section'   => 'fury_layout_general_section',
    'type'      => 'select',
    'choices'   => array(
        'right-sidebar' => esc_attr__( 'Right Sidebar', 'fury' ),
        'left-sidebar'  => esc_attr__( 'Left Sidebar', 'fury' ),
        'none'          => esc_attr__( 'No Sidebar', 'fury' )
    ),
    'default'   => 'right-sidebar'
) );
################################################
# HEADER
################################################
Kirki::add_panel( 'fury_header_panel', array(
    'title'     => esc_attr__( 'Header', 'fury' ),
    'priority'  => 40
) );
###########################################################
# HEADER TOP BAR SECTION
###########################################################
Kirki::add_section( 'fury_header_top_bar_section', array(
    'title' => esc_attr__( 'Top Bar', 'fury' ),
    'panel' => 'fury_header_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Top Bar', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable header top bar feature.', 'fury' ),
    'settings'  => 'fury_header_top_bar',
    'section'   => 'fury_header_top_bar_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Email', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your contact email address.', 'fury' ),
    'settings'          => 'fury_header_top_bar_email',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'text',
    'default'           => 'johndoe@example.com',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_top_bar',
            'operator'  => '==',
            'value'     => true
        )
    )
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Phone', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your contact phone number.', 'fury' ),
    'settings'          => 'fury_header_top_bar_phone',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'text',
    'default'           => '00 22 159 4421',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_top_bar',
            'operator'  => '==',
            'value'     => true
        )
    )
) );
Kirki::add_field( 'fury_option', array(
	'type'        => 'custom',
	'settings'    => 'fury_htb_styling_separator',
	'section'     => 'fury_header_top_bar_section',
	'default'     => '<div style="border: 1px dashed #5e666e; padding: 30px; color: #555d66;">' . esc_html__( 'Enabe option below only if you want to customize header top bar styling.', 'fury' ) . '</div>',
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Custom Styling', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable top bar styling options.', 'fury' ),
    'settings'  => 'fury_htb_styling',
    'section'   => 'fury_header_top_bar_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'                 => esc_attr__( 'Background', 'fury' ),
    'tooltip'               => esc_attr__( 'Enable header topbar bacground color styling.', 'fury' ),
    'settings'              => 'fury_htb_bg_color_switch',
    'section'               => 'fury_header_top_bar_section',
    'type'                  => 'radio-buttonset',
    'choices'               => array(
        'background-color'  => esc_attr__( 'Backgroun Color', 'fury' ),
        'gradient-color'    => esc_attr__( 'Gradient Color', 'fury' )
    ),
    'active_callback'       => array(
        array(
            'setting'       => 'fury_htb_styling',
            'operator'      => '==',
            'value'         => true
        )
    ),
    'default'               => 'gradient-color'
) );
Kirki::add_field( 'fury_option', array(
    'tooltip'           => esc_attr__( 'Set header top bar custom background color.', 'fury' ),
    'settings'          => 'fury_htb_bg_color',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_htb_bg_color_switch',
            'operator'  => '==',
            'value'     => 'background-color'
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.topbar',
            'property'  => 'background-color'
        )
    ),
    'default'           => '#f5f5f5'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Start Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient start color.', 'fury' ),
    'settings'          => 'fury_htb_gradient_start_color',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'color',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_htb_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '#5540d9'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'End Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient end color.', 'fury' ),
    'settings'          => 'fury_htb_gradient_end_color',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'color',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_htb_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '#ee2762'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Angle', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient angle.', 'fury' ),
    'settings'          => 'fury_htb_gradient_angle',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'slider',
    'choices'           => array(
        'min'           => '0',
        'max'           => '360',
        'step'          => '1'
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_htb_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '90'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Links Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header top bar links/text color.', 'fury' ),
    'settings'          => 'fury_htb_links_color',
    'section'           => 'fury_header_top_bar_section',
    'transport'         => 'auto',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'output'            => array(
        array(
            'element'   => '.topbar .topbar-column a, .topbar .topbar-column a:not(.social-button)',
            'property'  => 'color'
        )
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => 'rgba(255,255,255,0.72)'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Links Hover Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header top bar links/text hover color.', 'fury' ),
    'settings'          => 'fury_htb_links_hover_color',
    'section'           => 'fury_header_top_bar_section',
    'transport'         => 'auto',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'output'            => array(
        array(
            'element'   => '.topbar .topbar-column a:not(.social-button):hover',
            'property'  => 'color'
        )
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => '#fff'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Top Bar Border Bottom', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header top bar border bottom color.', 'fury' ),
    'settings'          => 'fury_htb_border_bottom_color',
    'section'           => 'fury_header_top_bar_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_htb_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.topbar',
            'property'  => 'border-bottom-color'
        )
    ),
    'default'           => 'rgba(255, 255, 255, 0.2)'
) );
############################################################
# HEADER GENERAL SECTION
############################################################
Kirki::add_section( 'fury_header_general_section', array(
    'title' => esc_attr__( 'General', 'fury' ),
    'panel' => 'fury_header_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Sticky Header', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable sticky header feature.', 'fury' ),
    'settings'  => 'fury_header_sticky',
    'section'   => 'fury_header_general_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Profile Icon', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable user profile icon on header right area.', 'fury' ),
    'settings'  => 'fury_header_profile',
    'section'   => 'fury_header_general_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Search Icon', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable search icon on header right area.', 'fury' ),
    'settings'  => 'fury_header_search',
    'section'   => 'fury_header_general_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
	'type'        => 'custom',
	'settings'    => 'fury_header_styling_separator',
	'section'     => 'fury_header_general_section',
	'default'     => '<div style="border: 1px dashed #5e666e; padding: 30px; color: #555d66;">' . esc_html__( 'Enabe option below only if you want to customize header styling.', 'fury' ) . '</div>',
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Custom Styling', 'fury' ),
    'tooltip'           => esc_attr__( 'Enable header styling options.', 'fury' ),
    'settings'          => 'fury_header_styling',
    'section'           => 'fury_header_general_section',
    'type'              => 'switch',
    'default'           => false
) );
Kirki::add_field( 'fury_option', array(
    'label'                 => esc_attr__( 'Background', 'fury' ),
    'tooltip'               => esc_attr__( 'Enable header bacground color styling.', 'fury' ),
    'settings'              => 'fury_header_bg_color_switch',
    'section'               => 'fury_header_general_section',
    'type'                  => 'radio-buttonset',
    'choices'               => array(
        'background-color'  => esc_attr__( 'Backgroun Color', 'fury' ),
        'gradient-color'    => esc_attr__( 'Gradient Color', 'fury' )
    ),
    'active_callback'       => array(
        array(
            'setting'       => 'fury_header_styling',
            'operator'      => '==',
            'value'         => true
        )
    ),
    'default'               => 'gradient-color'
) );
Kirki::add_field( 'fury_option', array(
    'tooltip'           => esc_attr__( 'Set header custom background color.', 'fury' ),
    'settings'          => 'fury_header_bg_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_header_bg_color_switch',
            'operator'  => '==',
            'value'     => 'background-color'
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => 'header.navbar',
            'property'  => 'background-color'
        )
    ),
    'default'           => '#fff'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Start Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient start color.', 'fury' ),
    'settings'          => 'fury_header_gradient_start_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_header_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '#5540d9'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'End Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient end color.', 'fury' ),
    'settings'          => 'fury_header_gradient_end_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_header_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '#ee2762'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Angle', 'fury' ),
    'tooltip'           => esc_attr__( 'Set gradient angle.', 'fury' ),
    'settings'          => 'fury_header_gradient_angle',
    'section'           => 'fury_header_general_section',
    'type'              => 'slider',
    'choices'           => array(
        'min'           => '0',
        'max'           => '360',
        'step'          => '1'
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_header_bg_color_switch',
            'operator'  => '==',
            'value'     => 'gradient-color'
        )
    ),
    'default'           => '90'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Menu Links Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header navigation menu links color.', 'fury' ),
    'settings'          => 'fury_header_menu_links_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.site-menu ul:not(.sub-menu) > li > a',
            'property'  => 'color'
        ),
        array(
            'element'   => '.offcanvas-toggle, .toolbar .search, .toolbar .account, .toolbar .cart',
            'property'  => 'color'
        ),
        array(
            'element'   => 'header.navbar .site-search > input',
            'property'  => 'color'
        ),
        array(
            'element'   => 'header.navbar .site-search .search-tools .clear-search, header.navbar .site-search .search-tools .close-search',
            'property'  => 'color'
        )
    ),
    'default'           => '#fff'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Menu Links Hover Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header navigation menu links hover color.', 'fury' ),
    'settings'          => 'fury_header_menu_links_hover_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.site-menu ul:not(.sub-menu) > li:hover > a',
            'property'  => 'color'
        ),
        array(
            'element'   => '.site-menu ul:not(.sub-menu) > li.active > a',
            'property'  => 'color'
        ),
        array(
            'element'   => '.site-menu ul:not(.sub-menu) > li.active > a',
            'property'  => 'border-bottom-color'
        ),
        array(
            'element'   => '.offcanvas-toggle:hover, header.navbar .site-search .search-tools .clear-search:hover, header.navbar .site-search .search-tools .close-search:hover',
            'property'  => 'color'
        )
    ),
    'default'           => 'rgba(255,255,255,0.72)'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Icons Link Hover Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header icons links hover color.', 'fury' ),
    'settings'          => 'fury_header_icons_link_hover_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.toolbar .search:hover, .toolbar .account:hover, .toolbar .cart:hover',
            'property'  => 'color'
        )
    ),
    'default'           => 'rgba(255,255,255,0.72)'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Icons Hover Background Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header icons on hover background color.', 'fury' ),
    'settings'          => 'fury_header_icons_hover_bg_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.toolbar .search:hover, .toolbar .account:hover, .toolbar .cart:hover',
            'property'  => 'background-color'
        )
    ),
    'default'           => 'rgba(255, 255, 255, 0.2)'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Header Borders Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header navigation canvas menu & icons border color.', 'fury' ),
    'settings'          => 'fury_header_borders_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => '.offcanvas-toggle',
            'property'  => 'border-right-color'
        ),
        array(
            'element'   => '.toolbar .search, .toolbar .account, .toolbar .cart',
            'property'  => 'border-color'
        ),
        array(
            'element'   => '.toolbar .cart > .subtotal',
            'property'  => 'border-left-color'
        )
    ),
    'default'           => 'rgba(255, 255, 255, 0.2)'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Header Border Bottom Color', 'fury' ),
    'tooltip'           => esc_attr__( 'Set header border bottom color.', 'fury' ),
    'settings'          => 'fury_header_border_bottom_color',
    'section'           => 'fury_header_general_section',
    'type'              => 'color',
    'choices'           => array(
        'alpha'         => true
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'   => 'header.navbar',
            'property'  => 'border-bottom-color'
        )
    ),
    'default'           => 'rgba(255, 255, 255, 0.2)'
) );
###########################################
# HEADER LOGO SECTION
###########################################
Kirki::add_section( 'title_tagline', array(
    'title' => esc_attr__( 'Logo', 'fury' ),
    'panel' => 'fury_header_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Site Title Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Set site title custom color.', 'fury' ),
    'settings'  => 'fury_header_logo_color',
    'section'   => 'title_tagline',
    'type'      => 'color',
    'transport' => 'auto',
    'choices'   => array(
        'alpha' => true
    ),
    'output'    => array(
        array(
            'element'   => '.custom-logo-link',
            'property'  => 'color'
        )
    ),
    'default'   => '#606975'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Site Title Hover Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Set site title custom hover color.', 'fury' ),
    'settings'  => 'fury_header_logo_hover_color',
    'section'   => 'title_tagline',
    'type'      => 'color',
    'transport' => 'auto',
    'choices'   => array(
        'alpha' => true
    ),
    'output'    => array(
        array(
            'element'   => '.custom-logo-link:hover',
            'property'  => 'color'
        )
    ),
    'default'   => '#0da9ef'
) );
##########################################
# HEADER IMAGE SECTION
##########################################
Kirki::add_section( 'header_image', array(
    'title' => esc_attr__( 'Header Image', 'fury' ),
    'panel' => 'fury_header_panel'
) );
#############################################
# NAVIGATIONS
#############################################
Kirki::add_panel( 'fury_navigations', array(
    'title'     => esc_attr__( 'Navigations', 'fury' ),
    'priority'  => 50
) );
#########################################################
# OFF-CANVAS NAVIGATION
#########################################################
Kirki::add_section( 'fury_nav_offcanvas_section', array(
    'title' => esc_attr__( 'Off-Canvas Navigation', 'fury' ),
    'panel' => 'fury_navigations'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable Canvas Navigation', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable Offcanvas navigation on header left side.', 'fury' ),
    'settings'  => 'fury_header_offcanvas_menu',
    'section'   => 'fury_nav_offcanvas_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Canvas Open Background Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select off-canvas navigation wrapper background color.', 'fury' ),
    'settings'  => 'fury_header_offcanvas_menu_bg_color',
    'section'   => 'fury_nav_offcanvas_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.offcanvas-container',
            'property'  => 'background-color'
        )
    ),
    'default'   => '#374250'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Links Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select off-canvas menu links color.', 'fury' ),
    'settings'  => 'fury_header_offcanvas_menu_links_color',
    'section'   => 'fury_nav_offcanvas_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.offcanvas-menu ul li a',
            'property'  => 'color'
        )
    ),
    'default'   => '#fff'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Links Hover Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select off-canvas menu links hover color.', 'fury' ),
    'settings'  => 'fury_header_offcanvas_menu_links_hover_color',
    'section'   => 'fury_nav_offcanvas_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.offcanvas-menu ul li a:hover',
            'property'  => 'color'
        )
    ),
    'default'   => '#0da9ef'
) );
#########################################
# PRIMARY NAVIGATION SECTION
#########################################
Kirki::add_section( 'fury_nav_primary_section', array(
    'title' => esc_attr__( 'Primary Navigation', 'fury' ),
    'panel' => 'fury_navigations'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Links Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select primary navigation links color.', 'fury' ),
    'settings'  => 'fury_nav_primary_links_color',
    'section'   => 'fury_nav_primary_section',
    'type'      => 'color',
    'transport' => 'auto',
    'choices'   => array(
        'alpha' => true
    ),
    'output'    => array(
        array(
            'element'   => '.site-menu > ul > li > a',
            'property'  => 'color'
        )
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => false
        )
    ),
    'default'   => '#606975'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Links Hover Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select primary navigation links hover color.', 'fury' ),
    'settings'  => 'fury_nav_primary_links_hover_color',
    'section'   => 'fury_nav_primary_section',
    'type'      => 'color',
    'transport' => 'auto',
    'choices'   => array(
        'alpha' => true
    ),
    'output'    => array(
        array(
            'element'   => 'a.offcanvas-toggle:hover, .site-menu ul:not(.sub-menu) > li.active > a, .site-menu ul:not(.sub-menu) > li:hover > a',
            'property'  => 'color'
        ),
        array(
            'element'   => '.site-menu > ul > li.active > a',
            'property'  => 'border-bottom-color'
        )
    ),
    'active_callback'   => array(
        array(
            'setting'   => 'fury_header_styling',
            'operator'  => '==',
            'value'     => false
        )
    ),
    'default'   => '#0da9ef'
) );
####################################################
# BREADCRUMB PANEL
####################################################
Kirki::add_panel( 'fury_breadcrumb_panel', array(
    'title'     => esc_attr__( 'Breadcrumb', 'fury' ),
    'priority'  => 50
) );
################################################################
# BREADCRUMB GENERAL SECTION
################################################################
Kirki::add_section( 'fury_breadcrumb_general_section', array(
    'title' => esc_attr__( 'General', 'fury' ),
    'panel' => 'fury_breadcrumb_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable breadcrumb feature.', 'fury' ),
    'settings'  => 'fury_breadcrumb_enable',
    'section'   => 'fury_breadcrumb_general_section',
    'type'      => 'switch',
    'default'   => true
) );
################################################################
# BREADCRUMB STYLING SECTION
################################################################
Kirki::add_section( 'fury_breadcrumb_styling_section', array(
    'title' => esc_attr__( 'Styling', 'fury' ),
    'panel' => 'fury_breadcrumb_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Background Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select breadcrumb background color.', 'fury' ),
    'settings'  => 'fury_breadcrumb_bg_color',
    'section'   => 'fury_breadcrumb_styling_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.page-title',
            'property'  => 'background-color'
        )
    ),
    'default'   => '#f5f5f5'
) );
####################################################
# SLIDER PANEL
####################################################
Kirki::add_panel( 'fury_slider_panel', array(
    'title'     => esc_attr__( 'Slider', 'fury' ),
    'priority'  => 60
) );
#########################################################
# SLIDER GENERAL SECTION
#########################################################
Kirki::add_section( 'fury_slider_general_section', array(
    'title' => esc_attr__( 'General', 'fury' ),
    'panel' => 'fury_slider_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable Fury slider.', 'fury' ),
    'settings'  => 'fury_slider_enable',
    'section'   => 'fury_slider_general_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable on Pages', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable Fury slider on custom pages.', 'fury' ),
    'settings'  => 'fury_slider_on_pages',
    'section'   => 'fury_slider_general_section',
    'type'      => 'select',
    'multiple'  => 999,
    'choices'   => Fury_Helper::dropdown_pages()
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Background Image', 'fury' ),
    'tooltip'   => esc_attr__( 'Set custom slider background image.', 'fury' ),
    'settings'  => 'fury_slider_bg_image',
    'section'   => 'fury_slider_general_Section',
    'type'      => 'image',
    'default'   => fury_uri . 'assets/img/slider-bg.jpg'
) );
#########################################################
# SLIDE 1 SECTION
#########################################################
Kirki::add_section( 'fury_slider_slide_1_section', array(
    'title' => esc_attr__( 'Slide #1', 'fury' ),
    'panel' => 'fury_slider_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable slide ?', 'fury' ),
    'settings'  => 'fury_slide_1_enable',
    'section'   => 'fury_slider_slide_1_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Slide Text', 'fury' ),
    'tooltip'   => esc_attr__( 'Add custom slide text.', 'fury' ),
    'settings'  => 'fury_slide_1_text',
    'section'   => 'fury_slider_slide_1_section',
    'type'      => 'editor',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_1_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => esc_attr__( 'Learn more about Fury', 'fury' )
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Slide Image', 'fury' ),
    'tooltip'   => esc_attr__( 'Set custom slide image.', 'fury' ),
    'settings'  => 'fury_slide_1_image',
    'section'   => 'fury_slider_slide_1_section',
    'type'      => 'image',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_1_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => fury_uri . 'assets/img/slide-image.png'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Slider Button', 'fury' ),
    'tooltip'           => esc_attr__( 'Enable or disable slider button.', 'fury' ),
    'settings'          => 'fury_slide_1_button',
    'section'           => 'fury_slider_slide_1_section',
    'type'              => 'switch',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_1_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => true
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Button URL', 'fury' ),
    'tooltip'           => esc_attr__( 'Set button custom url.', 'fury' ),
    'settings'          => 'fury_slide_1_button_url',
    'section'           => 'fury_slider_slide_1_section',
    'type'              => 'text',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_1_enable',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_slide_1_button',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Button Title', 'fury' ),
    'tooltip'           => esc_attr__( 'Set button custom url.', 'fury' ),
    'settings'          => 'fury_slide_1_button_title',
    'section'           => 'fury_slider_slide_1_section',
    'type'              => 'text',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_1_enable',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_slide_1_button',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => esc_attr__( 'Learn More', 'fury' )
) );
#########################################################
# SLIDE 2 SECTION
#########################################################
Kirki::add_section( 'fury_slider_slide_2_section', array(
    'title' => esc_attr__( 'Slide #2', 'fury' ),
    'panel' => 'fury_slider_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable slide ?', 'fury' ),
    'settings'  => 'fury_slide_2_enable',
    'section'   => 'fury_slider_slide_2_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Slide Text', 'fury' ),
    'tooltip'   => esc_attr__( 'Add custom slide text.', 'fury' ),
    'settings'  => 'fury_slide_2_text',
    'section'   => 'fury_slider_slide_2_section',
    'type'      => 'editor',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_2_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => esc_attr__( 'Learn more about Fury', 'fury' )
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Slide Image', 'fury' ),
    'tooltip'   => esc_attr__( 'Set custom slide image.', 'fury' ),
    'settings'  => 'fury_slide_2_image',
    'section'   => 'fury_slider_slide_2_section',
    'type'      => 'image',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_2_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'   => fury_uri . 'assets/img/slide-image.png'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Slider Button', 'fury' ),
    'tooltip'           => esc_attr__( 'Enable or disable slider button.', 'fury' ),
    'settings'          => 'fury_slide_2_button',
    'section'           => 'fury_slider_slide_2_section',
    'type'              => 'switch',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_2_enable',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => true
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Button URL', 'fury' ),
    'tooltip'           => esc_attr__( 'Set button custom url.', 'fury' ),
    'settings'          => 'fury_slide_2_button_url',
    'section'           => 'fury_slider_slide_2_section',
    'type'              => 'text',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_2_enable',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_slide_2_button',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Button Title', 'fury' ),
    'tooltip'           => esc_attr__( 'Set button custom url.', 'fury' ),
    'settings'          => 'fury_slide_2_button_title',
    'section'           => 'fury_slider_slide_2_section',
    'type'              => 'text',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_slide_2_enable',
            'operator'  => '==',
            'value'     => true
        ),
        array(
            'setting'   => 'fury_slide_2_button',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => esc_attr__( 'Learn More', 'fury' )
) );
##############################################
# BLOG
##############################################
Kirki::add_panel( 'fury_blog_panel', array(
    'title'     => esc_attr__( 'Blog', 'fury' ),
    'priority'  => 70
) );
##############################################################
# BLOG SINGLE POST
##############################################################
Kirki::add_section( 'fury_blog_single_post_section', array(
    'title' => esc_attr__( 'Single Post', 'fury' ),
    'panel' => 'fury_blog_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Posts Title', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable single post titles globally.', 'fury' ),
    'settings'  => 'fury_blog_single_post_title',
    'section'   => 'fury_blog_single_post_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Posts Navigation', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable single post prev/next navigation feature.', 'fury' ),
    'settings'  => 'fury_blog_single_post_nav',
    'section'   => 'fury_blog_single_post_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Related Articles', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable single post related articles feature.', 'fury' ),
    'settings'  => 'fury_blog_single_post_related_articles',
    'section'   => 'fury_blog_single_post_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Related Articles Heading', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter custom title for Related Articles heading.', 'fury' ),
    'settings'          => 'fury_blog_single_post_related_articles_h4',
    'section'           => 'fury_blog_single_post_section',
    'type'              => 'text',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_blog_single_post_related_articles',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => __( 'You May Also Like', 'fury' )
) );
#######################################################
# BLOG POST META
#######################################################
Kirki::add_section( 'fury_blog_meta_section', array(
    'title' => esc_attr__( 'Post Meta', 'fury' ),
    'panel' => 'fury_blog_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Post Author', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable post meta author.', 'fury' ),
    'settings'  => 'fury_blog_meta_author',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Post Date', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable post meta date.', 'fury' ),
    'settings'  => 'fury_blog_meta_date',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Post Tags', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable post meta tags.', 'fury' ),
    'settings'  => 'fury_blog_meta_tags',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Post Comments', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable post meta comments.', 'fury' ),
    'settings'  => 'fury_blog_meta_comments',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Read More Custom Link Text', 'fury' ),
    'tooltip'   => esc_attr__( 'Change "Read More" link text with custom one.', 'fury' ),
    'settings'  => 'fury_blog_meta_read_more_custom',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'text',
    'default'   => esc_attr__( 'Read More', 'fury' )
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Read More Link Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select read more link color.', 'fury' ),
    'settings'  => 'fury_blog_meta_read_more_link_color',
    'section'   => 'fury_blog_meta_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => 'a.moretag',
            'property'  => 'color'
        )
    ),
    'default'   => '#0da9ef'
) );
########################################################
# SOCIAL ICONS
########################################################
Kirki::add_section( 'fury_social_icons_section', array(
    'title'             => esc_attr__( 'Social Icons', 'fury' ),
    'priority'          => 80
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Enable', 'fury' ),
    'tooltip'           => esc_attr__( 'Enable or disable social share feature.', 'fury' ),
    'settings'          => 'fury_social_icons',
    'section'           => 'fury_social_icons_section',
    'type'              => 'switch',
    'default'           => true
) );
/* Waiting for Kirki 3.1 since fontawesome
   not working in repeater right now.
Kirki::add_field( 'fury_option', array(
	'type'                 => 'repeater',
	'label'                => esc_attr__( 'Social Icons', 'fury' ),
	'section'              => 'fury_social_icons_section',
	'priority'             => 10,
	'row_label'            => array(
        'type'             => 'text',
		'value'            => esc_attr__( 'New Social Icon', 'fury' ),
	),
	'button_label'         => esc_attr__( 'Add New Social Icon', 'fury' ),
	'settings'             => 'fury_social_icons',
	'default'              => array(
		array(
            'icon'         => 'fa-facebook',
			'title'        => esc_attr__( 'Facebook', 'fury' ),
			'url'          => 'https://',
		),
		array(
            'icon'         => 'fa-twitter',
			'title'        => esc_attr__( 'Twitter', 'fury' ),
			'url'          => '#',
		),
	),
	'fields'              => array(
        'icon'            => array(
            'type'        => 'fontawesome',
            'label'       => esc_attr__( 'Icon', 'fury' ),
            'description' => esc_attr__( 'Select social icon.', 'fury' ),
            'default'     => ''
        ),
		'title'           => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link Text', 'fury' ),
			'description' => esc_attr__( 'This will be the label for your link', 'fury' ),
			'default'     => '',
		),
		'url'             => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Page URL', 'fury' ),
			'description' => esc_attr__( 'Set your social page url.', 'textdomain' ),
			'default'     => 'https://',
		),
	)
) );
*/
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Facebook', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your Facebbok page url.', 'fury' ),
    'settings'          => 'fury_social_icon_facebook',
    'section'           => 'fury_social_icons_section',
    'type'              => 'text',
    'default'           => '',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_social_icons',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Twitter', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your Twitter page url.', 'fury' ),
    'settings'          => 'fury_social_icon_twitter',
    'section'           => 'fury_social_icons_section',
    'type'              => 'text',
    'default'           => '',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_social_icons',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Youtube', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your YouTube page url.', 'fury' ),
    'settings'          => 'fury_social_icon_youtube',
    'section'           => 'fury_social_icons_section',
    'type'              => 'text',
    'default'           => '',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_social_icons',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Instagram', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your Instagram page url.', 'fury' ),
    'settings'          => 'fury_social_icon_instagram',
    'section'           => 'fury_social_icons_section',
    'type'              => 'text',
    'default'           => '',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_social_icons',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
Kirki::add_field( 'fury_option', array(
    'label'             => esc_attr__( 'Pinterest', 'fury' ),
    'tooltip'           => esc_attr__( 'Enter your Pinterest page url.', 'fury' ),
    'settings'          => 'fury_social_icon_pinterest',
    'section'           => 'fury_social_icons_section',
    'type'              => 'text',
    'default'           => '',
    'active_callback'   => array(
        array(
            'setting'   => 'fury_social_icons',
            'operator'  => '==',
            'value'     => true
        )
    ),
    'default'           => '#'
) );
##########################################################
# SOCIAL SHARE SECTION
##########################################################
Kirki::add_section( 'fury_social_share_section', array(
    'title'     => esc_attr__( 'Social Share', 'fury' ),
    'priority'  => 90
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Enable', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable or disable social share feature.', 'fury' ),
    'settings'  => 'fury_social_share',
    'section'   => 'fury_social_share_section',
    'type'      => 'switch',
    'default'   => true
) );
Kirki::add_field( 'fury_option', array(
	'type'        => 'sortable',
	'settings'    => 'fury_social_share_sortable',
	'label'       => __( 'Make custom order.', 'fury' ),
	'section'     => 'fury_social_share_section',
	'default'     => array(
		'facebook',
		'twitter',
		'linkedin',
        'google_plus'
	),
	'choices'     => array(
		'facebook'    => esc_attr__( 'Facebook', 'fury' ),
		'twitter'     => esc_attr__( 'Twitter', 'fury' ),
		'linkedin'    => esc_attr__( 'LinkedIn', 'fury' ),
		'google_plus' => esc_attr__( 'Google+', 'fury' )
	),
    'active_callback' => array(
        array(
            'setting'   => 'fury_social_share',
            'operator'  => '==',
            'value'     => true
        )
    )
) );
###################################################
# WOOCOMMERCE PANEL
###################################################
Kirki::add_panel( 'woocommerce', array(
    'title'     => esc_attr__( 'WooCommerce', 'fury' ),
    'priority'  => 110
) );
###############################################################
# WOOCOMMERCE GENERAL SECTION
###############################################################
Kirki::add_section( 'fury_woocommerce_general_section', array(
    'title'     => esc_attr__( 'General', 'fury' ),
    'panel'     => 'woocommerce',
    'priority'  => 1
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Related Products', 'fury' ),
    'tooltip'   => esc_attr__( 'Enable WooCommerce related products section on single product page (bottom).', 'fury' ),
    'settings'  => 'fury_woocommerce_related_products',
    'section'   => 'fury_woocommerce_general_section',
    'type'      => 'switch',
    'default'   => true
) );
################################################
# FOOTER
################################################
Kirki::add_panel( 'fury_footer_panel', array(
    'title'     => esc_attr__( 'Footer', 'fury' ),
    'priority'  => 120,
) );
#########################################################
# FOOTER GENERAL SECTION
#########################################################
Kirki::add_section( 'fury_footer_general_section', array(
    'title' => esc_attr__( 'General', 'fury' ),
    'panel' => 'fury_footer_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Footer Copyright', 'fury' ),
    'tooltip'   => esc_attr__( 'Set custom footer copyright text.', 'fury' ),
    'settings'  => 'fury_footer_copyright',
    'section'   => 'fury_footer_general_section',
    'type'      => 'editor',
    'default'   => ''
) );
#############################################################
# FOOTER STYLING SECTION
#############################################################
Kirki::add_section( 'fury_footer_styling_section', array(
    'title' => esc_attr__( 'Styling', 'fury' ),
    'panel' => 'fury_footer_panel'
) );
Kirki::add_field( 'fury_option', array(
    'label'     => esc_attr__( 'Background Color', 'fury' ),
    'tooltip'   => esc_attr__( 'Select custom footer background color.', 'fury' ),
    'settings'  => 'fury_footer_bg_color',
    'section'   => 'fury_footer_styling_section',
    'type'      => 'color',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'   => '.site-footer',
            'property'  => 'background-color'
        )
    ),
    'default'   => '#374250'
) );
#################################################################
# WORDPRESS DEFAULT SECTIONS
#################################################################
// Move Homepage Settings Section under General
Kirki::add_section( 'static_front_page', array(
    'title'     => esc_attr__( 'Homepage Settings', 'fury' ),
    'panel'     => 'fury_general_panel',
    'priority'  => 1
) );
// Move Additional CSS Section under General
Kirki::add_section( 'custom_css', array(
    'title'     => esc_attr__( 'Additional CSS', 'fury' ),
    'panel'     => 'fury_general_panel',
    'priority'  => 2
) );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
