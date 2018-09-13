<?php
/*--------------------------------------------------------------------*/
/*     Register Google Fonts
/*--------------------------------------------------------------------*/
function health_fonts_url() {
	
    $fonts_url = '';
		
    $font_families = array();
 
	$font_families = array('Roboto:400,700','italic');
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

    return $fonts_url;
}
function hc_scripts_styles() {
    wp_enqueue_style( 'health-fonts', health_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'hc_scripts_styles' );
?>