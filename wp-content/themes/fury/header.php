<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    
    <!-- Meta Character Encoding -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    
    <!-- Mobile Specific Meta Tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Relationships Meta Data Profile -->
    <link rel="profile" href="http://gmpg.org/xfn/11">
    
    <!-- Pingback -->
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    
    <?php wp_head(); ?>
    
</head>
<body <?php body_class(); ?>>
    
    <?php
    $user_info          = get_userdata( get_current_user_id() );
    $user_profile_url   = class_exists( 'Woocommerce' ) ? 
                          esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) : 
                          esc_url( get_edit_user_link() );
    $username           = is_user_logged_in() ? esc_html( ucfirst( $user_info->user_login ) ) : esc_html__( 'Guest', 'fury' );
    $email              = is_user_logged_in() ? esc_html( $user_info->user_email ) : esc_html__( 'Welcome Guest', 'fury' ); 
    $login_txt          = is_user_logged_in() ? esc_html__( 'Logout', 'fury' ) : esc_html__( 'Login', 'fury' ); 
    
    // WooCommerce Login
    if( ! is_user_logged_in() && class_exists( 'Woocommerce' ) ) {
        $login_url = esc_url( wc_get_page_permalink( 'myaccount' ) );
    }
    else // WordPress Login
    if( ! is_user_logged_in() && ! class_exists( 'Woocommerce' ) ) {
        $login_url = esc_url( wp_login_url( home_url() ) );
    }
    else // Logout
    if( is_user_logged_in() ) {
        $login_url = esc_url( wp_logout_url( home_url() ) );
    } ?>
    
    <!-- Fury Main Wrapper -->
    <div id="fury-main-wrapper" class="fury-main-wrapper">
    
    <?php if( get_theme_mod( 'fury_header_offcanvas_menu', true ) ): ?>
    <!-- Off-Canvas Menu -->
    <div class="offcanvas-container" id="shop-categories">
        <div class="offcanvas-header">
            <?php if( class_exists( 'woocommerce' ) ): ?>
                <h3 class="offcanvas-title"><?php _e( 'Shop Categories', 'fury' ); ?></h3>
            <?php else: ?>
                <h3 class="offcanvas-title"><?php _e( 'Side Navigation', 'fury' ); ?></h3>
            <?php endif; ?>
        </div>
        <nav class="offcanvas-menu">
            <?php do_action( 'fury_off_canvas_menu' ); ?>
        </nav>
    </div><!-- Off-Canvas Menu End -->
    <?php endif; ?>
    
    <!-- Mobile Menu -->
    <div class="offcanvas-container" id="mobile-menu">
        <a class="account-link" href="<?php echo get_edit_user_link(); ?>">
            <div class="user-ava">
                <?php echo get_avatar( get_current_user_id() ); ?>
            </div>
            <div class="user-info">
                <h6 class="user-name"><?php echo $username; ?></h6>
                <span class="text-sm text-white opacity-60"><?php echo $email; ?></span>
            </div>
        </a>
        <nav class="offcanvas-menu">
            <?php do_action( 'fury_menu', 'fury-mobile' ); ?>
        </nav>
    </div><!-- Mobile Menu End -->
    
    <?php if( get_theme_mod( 'fury_header_top_bar', true ) ): ?>
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-column">
            
            <?php 
            $socemail = esc_html( get_theme_mod( 'fury_header_top_bar_email', 'johndoe@example.com' ) ); 
            $socphone = esc_html( get_theme_mod( 'fury_header_top_bar_phone', '00 22 159 4421' ) ); ?>
            
            <?php if( $socemail ): ?>
                <a class="hidden-md-down" href="mailto:<?php echo $socemail; ?>">
                    <i class="icon-mail"></i>&nbsp; <?php echo $socemail; ?>
                </a>
            <?php endif; ?>
            
            <?php if( $socphone ): ?>
                <a class="hidden-md-down" href="tel:<?php echo $socphone; ?>">
                    <i class="icon-bell"></i>&nbsp; <?php echo $socphone; ?>
                </a>
            <?php endif; ?>
            
            <?php do_action( 'fury_social_icons', 'topbar' ); ?>
            
        </div>
        <div class="topbar-column">
            
        </div>
    </div><!-- Topbar End -->
    <?php endif; ?>
    
    <!-- Header -->
    <header class="navbar<?php fury_header_class(); ?>">
        
        <?php if( get_theme_mod( 'fury_header_search', true ) ): ?>
        <!-- Search-->
        <form class="site-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="text" name="s" placeholder="<?php esc_attr_e( 'Type to search...', 'fury' ); ?>" value="<?php echo get_search_query() ?>">
            <div class="search-tools"><span class="clear-search"><?php esc_html_e( 'Clear', 'fury' ); ?></span><span class="close-search"><i class="icon-cross"></i></span></div>
        </form><!-- Search End -->
        <?php endif; ?>
        
        <!-- Branding -->
        <div class="site-branding">
            <div class="inner">
                
                <?php if( get_theme_mod( 'fury_header_offcanvas_menu', true ) ): ?>
                <a class="offcanvas-toggle cats-toggle" href="#shop-categories" data-toggle="offcanvas"></a>
                <?php endif; ?>
                <a class="offcanvas-toggle menu-toggle" href="#mobile-menu" data-toggle="offcanvas"></a>
                
                <?php do_action( 'fury_logo' ); ?>
                
            </div>
        </div><!-- Branding End -->
        
        <!-- Primary Navigation -->
        <nav class="site-menu">
            <?php do_action( 'fury_menu', 'fury-primary' ); ?>
        </nav><!-- Primary Navigation End -->
        
        <!-- Toolbar -->
        <div class="toolbar">
            <div class="inner">
                <div class="tools">
                    
                    <?php if( get_theme_mod( 'fury_header_profile', true ) ): ?>
                    <!-- Account -->
                    <div class="account">
                        <a href="<?php echo $user_profile_url; ?>"></a><i class="icon-head"></i>
                        <ul class="toolbar-dropdown">
                            <li class="sub-menu-user">
                                <div class="user-ava">
                                    <?php echo get_avatar( get_current_user_id() ); ?>
                                </div>
                                <div class="user-info">
                                    <h6 class="user-name"><?php echo $username; ?></h6>
                                    <span class="text-xs text-muted"><?php echo $email ?></span>
                                </div>
                            </li>
                            <li><a href="<?php echo $user_profile_url; ?>"><?php esc_html_e( 'My Account', 'fury' ); ?></a></li>
                            <li class="sub-menu-separator"></li>
                            <li>
                                <a href="<?php echo $login_url; ?>"> 
                                <i class="icon-unlock"></i><?php echo $login_txt; ?></a>
                            </li>
                        </ul>
                    </div><!-- Account End -->
                    <?php endif; ?>
                    
                    <?php if( get_theme_mod( 'fury_header_search', true ) ): ?>
                        <div class="search"><i class="icon-search"></i></div>
                    <?php endif; ?>
                    
                    <?php if( class_exists( 'woocommerce' ) ): ?>
                    <!-- Cart -->
                    <div class="cart">
                        
                        <a href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html_e( 'View Cart', 'fury' ); ?>"></a>
                        <i class="icon-bag"></i>
                        <span class="count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                        <span class="subtotal"><?php echo esc_html( strip_tags( WC()->cart->get_cart_total() ) ); ?></span>
                        
                        <?php do_action( 'fury_cart_dropdown' ); ?>
                        
                    </div><!-- Cart End -->
                    <?php endif; ?>
                    
                </div>
            </div>
        </div><!-- Toolbar End -->
        
    </header><!-- Header End -->
    
    <!-- Off-Canvas Wrapper -->
    <div class="offcanvas-wrapper">
        
        <?php do_action( 'fury_slider' ); ?>
        
        <?php do_action( 'fury_header_image' ); ?>
    
        <?php do_action( 'fury_breadcrumb' ); ?>
        
        <?php do_action( 'fury_content_wrapper' ); ?>
        