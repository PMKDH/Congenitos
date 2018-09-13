jQuery(document).ready(function ($){
    "use strict";
    
    function Metaboxes() {
        var $slider = $('#butterbean-control-_fury_slider_enable select').val();
        
        // If slider is disabled hide unnecessary dropdown menus.
        if( $slider == 'off' ) {
            $('#butterbean-control-_fury_slider_type, #butterbean-control-_fury_slider_category').fadeOut();
        }
        
        // If slider is enabled or disabled show/hide slider type & category dropdown.
        $('select[name=butterbean_fury_theme_metabox_setting__fury_slider_enable]').on( 'change', function() {
           if( $( this ).val() == 'on' ) {
               $('#butterbean-control-_fury_slider_type, #butterbean-control-_fury_slider_category').fadeIn();
           } else {
               $('#butterbean-control-_fury_slider_type, #butterbean-control-_fury_slider_category').fadeOut();
           }
        });
    }
    Metaboxes();
});
