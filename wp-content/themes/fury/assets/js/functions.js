jQuery(document).ready(function($){
    "use strict";
    
    /**
     * Off-Canvas Menu Wrappers & Classes
     *
     * @since 1.0
     */
    if( $('nav.offcanvas-menu > ul > li').hasClass('menu-item-has-children') ) {
        $('nav.offcanvas-menu > ul li.menu-item-has-children').addClass('has-children');
        $('nav.offcanvas-menu > ul li.has-children > a').wrap('<span>').parent().append('<span class="sub-menu-toggle"></span>');
        $('nav.offcanvas-menu > ul li.has-children > ul.sub-menu').removeClass('sub-menu').addClass('offcanvas-submenu');
    }
    
    /**
     * Fallback Menu
     * ========================================
     * Add "sub-menu" Class To "ul.children"
     * Unwrap Fallback Menu Container "div.menu"
     *
     * @since 1.0
     */
    if( $('nav.site-menu > div').hasClass('menu') ) {
        $('nav.site-menu > div.menu ul.children').addClass('sub-menu');
        $('nav.site-menu > div.menu > ul').unwrap();
    }
    
    /**
     * Add Custom Class To Stated Inputs
     * =================================
     * input[type=text]
     * input[type=password]
     * input[type=tel]
     * input[type=email]
     *
     * @since 1.0
     */
    $('input[type=text], input[type=password], input[type=tel], input[type=email]').addClass('form-control');
    
    /**
     * Add Custom Class To Stated Inputs
     * =================================
     * input[type=button]
     * input[type=submit]
     *
     * @since 1.0
     */
    $('input[type=button], input[type=submit]').not('.btn-outline-primary').addClass('btn btn-primary');
    
    /**
     * Add Custom Class To Textareas
     * =============================
     * <textarea></textarea>
     *
     * @since 1.0
     */
    $('textarea').addClass('form-control form-control-rounded');
    
    /**
     * bbResolutions Dropdown
     *
     * @since 1.0.6
     */
    if( $('select#bbr-topic-resolution').hasClass('bbr-resolutions-dropdown') ) {
        $('select#bbr-topic-resolution').addClass('form-control');
    }
    
    /**
     * Add Archive Widget Dropdown Class
     * From none to "form-control".
     *
     * @since 1.0
     */
    if( $('section.widget').hasClass('widget_archive') ) {
        $('section.widget.widget_archive select[name=archive-dropdown]').attr("class", "form-control");
    }
    
    /**
     * Recent Comments Widget
     *
     * @since 1.0
     */
    $('.widget.widget_recent_comments span.comment-author-link > a').addClass('navi-link');
    
    /**
     * Add "has-children" Class to Categories Widget li
     *
     * @since 1.0
     */
    $('.widget_categories ul > li > ul.children').parent().addClass('has-children expanded');
    
    
    /**
     * Add Pagination "active" Class
     *
     * @since 1.0
     */
    $('nav.pagination ul li span.current').parent().addClass('active');
    
    /**
     * bbPress Page
     * 
     * @since 1.0
     */
    
    // Submit Post Button
    $('button#bbp_reply_submit, button#bbp_topic_submit').addClass('btn btn-primary');
    
    // Notify me of follow-up replies via email (checkbox)
    // Set as private reply (checkbox)
    $('input#bbp_topic_subscription, input#bbp_private_reply').addClass('custom-control-input fury-bbp-control');
    $('label[for=bbp_topic_subscription], label[for=bbp_private_reply]').addClass('custom-control-label fury-bbp-control');
    $('input.fury-bbp-control').each(function(){
        $(this).next('label').andSelf().wrapAll('<div class="custom-control custom-checkbox"></div>');
    });
    
    // Topic Type && Topic Status Dropdown
    $('#bbp_stick_topic_select, #bbp_topic_status_select').addClass('form-control');
    
    /*****************************************************************
     * WOOCOMMERCE RELATED
     ****************************************************************/
    
    // Add "has-children" Class to Product Categories Widget li - Product Categories Widget
    $('.widget_product_categories ul > li > ul.children').parent().addClass('has-children expanded');
    
    // Unwrap div.woocommerce from My Account Page
    if( $('body').hasClass('woocommerce-account') ) {
        $('body.woocommerce-account div.woocommerce .col-lg-4').unwrap();
    }
    
    // Checkout Page - Billing Details
    setTimeout(function(){
        $('.woocommerce-billing-fields__field-wrapper p').addClass('form-group').wrap('<div class="col-sm-6"></div>');
        $('.woocommerce-shipping-fields__field-wrapper p').addClass('form-group').wrap('<div class="col-sm-6"></div>');
        
        $('.woocommerce-billing-fields__field-wrapper span.woocommerce-input-wrapper input').unwrap();
        $('.woocommerce-billing-fields__field-wrapper span.woocommerce-input-wrapper select').unwrap();
        $('.woocommerce-shipping-fields__field-wrapper span.woocommerce-input-wrapper input').unwrap();
        $('.woocommerce-shipping-fields__field-wrapper span.woocommerce-input-wrapper select').unwrap();
        
        $('.woocommerce-checkout .woocommerce-additional-fields__field-wrapper p.form-row.notes').addClass('form-group');
        $('.woocommerce-checkout .woocommerce-additional-fields__field-wrapper textarea').unwrap();
    }, 1);
    
    // My Account - Login / Register Page
    if( $('body').hasClass('woocommerce-login-page') ) {
        $('.row > .woocommerce').unwrap();
    }
    
    // My Account - Lost Password
    if( $('body').hasClass('woocommerce-lost-password') ) {
        $('div.col-lg-8.col-md-10').wrap('<div class="row justify-content-center"></div>');
    }
    
    // Single Product
    if( $('body').hasClass('single-product') ) {
        $('a.reset_variations').addClass('navi-link');
    }
    
    // WC Widgets
    $('.widget_price_filter button').removeClass('button').addClass('btn btn-outline-primary btn-sm');
    
    // Ajax remove product in the cart
    $(document).on('click', '.cart .dropdown-product-remove > a', function (e) {
        e.preventDefault();

        var product_id          = $(this).attr("data-product_id"),
            cart_item_key       = $(this).attr("data-cart_item_key"),
            product_container   = $(this).parents('.dropdown-product-item');

        // Add loader
        product_container.fadeOut("slow");
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: "product_remove",
                product_id: product_id,
                cart_item_key: cart_item_key
            },
            success: function(response) {
                if ( ! response || response.error )
                    return;
                
                var fragments = response.fragments;

                // Replace fragments
                if ( fragments ) {
                    $.each( fragments, function( key, value ) {
                        $( key ).replaceWith( value );
                    });
                }
            }
        });
    });  
    
});