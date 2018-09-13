<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include TGM Plugin Class
get_template_part( 'framework/library/tgm/class-tgm-plugin-activation' );

/**
 * Fury Plugin Installation
 *
 * @since 1.0.3
 */
function fury_tgmpa_register() {
    $plugins = array(
        array(
            'name'              => 'Fury Core',
            'slug'              => 'fury-core',
            'required'          => false,
            'force_activation'  => false
        ),
        /*
        array(
            'name'              => 'Elementor',
            'slug'              => 'elementor',
            'required'          => false,
            'force_activation'  => false
        ),*/
        array(
            'name'              => 'WPForms Lite',
            'slug'              => 'wpforms-lite',
            'required'          => false,
            'force_activation'  => false
        ),
    );
    
    // If Fury Pro Plugin Installed & Activated
    if( is_plugin_active( 'fury-pro/fury-pro.php' ) ) {
        $premium['js_composer'] = array(
            'name'              => 'WPBakery Page Builder',
            'slug'              => 'js_composer',
            'source'            => fury_generate_premium_plugin_uri( 'js_composer' ),
            'required'          => false,
            'force_activation'  => false
        );
        
        $premium['layer_slider'] = array(
            'name'              => 'Layer Slider',
            'slug'              => 'LayerSlider',
            'source'            => fury_generate_premium_plugin_uri( 'LayerSlider' ),
            'required'          => false,
            'force_activation'  => false
        );
        
        $premium['rev_slider'] = array(
            'name'              => 'Revolution Slider',
            'slug'              => 'revslider',
            'source'            => fury_generate_premium_plugin_uri( 'revslider' ),
            'required'          => false,
            'force_activation'  => false
        );
        
        $plugins = array_merge( $plugins, $premium );
    }
    
	tgmpa( $plugins, array(
		'id'           => 'fury_theme',
		'domain'       => 'fury',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => true,
		'dismissable'  => true
	) );
}
add_action( 'tgmpa_register', 'fury_tgmpa_register' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
