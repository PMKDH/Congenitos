<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom jQuery Head
 *
 * @since 1.1.6
 */
if( ! function_exists( 'fury_custom_jquery_head' ) ) {
    function fury_custom_jquery_head() {
        $code = strip_tags( get_theme_mod( 'fury_custom_jquery_head', '' ) );
        
        $output  = '<script type="text/javascript">';
            $output .= $code;
        $output .= '</script>';
        
        echo $output;
    }
}
add_action( 'wp_head', 'fury_custom_jquery_head' );

/**
 * Social Icons
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_social_icons' ) ) {
    function fury_social_icons( $location = null ) {
        if( get_theme_mod( 'fury_social_icons', true ) ) {
            $icons = array(
                'facebook'  => esc_url( get_theme_mod( 'fury_social_icon_facebook', '#' ) ),
                'twitter'   => esc_url( get_theme_mod( 'fury_social_icon_twitter', '#' ) ),
                'youtube'   => esc_url( get_theme_mod( 'fury_social_icon_youtube', '#' ) ),
                'instagram' => esc_url( get_theme_mod( 'fury_social_icon_instagram', '#' ) ),
                'pinterest' => esc_url( get_theme_mod( 'fury_social_icon_pinterest', '#' ) )
            );
            foreach( $icons as $name => $url ) {
                if( $location == 'topbar' && ! empty( $url ) ) {
                    echo '<a href="'. $url .'" class="social-button sb-'. esc_attr( $name ) .' shape-none sb-dark" target="_blank">';
                        echo '<i class="socicon-'. esc_attr( $name ) .'"></i>';
                    echo '</a>';
                }
            }
        }
    }
}
add_action( 'fury_social_icons', 'fury_social_icons' );

/**
 * Theme Logo || Tag Line
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_logo' ) ) {
    function fury_logo() {
        global $post;
        if( function_exists( 'the_custom_logo' ) ) {
            $meta = is_object( $post ) ? esc_attr( get_post_meta( $post->ID, '_fury_logo', true ) ) : '';
            if( $meta ) {
                $logo_url = wp_get_attachment_url( $meta );
                $logo  = '<a href="'. esc_url( home_url( '/' ) ) .'" title="'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'" ';
                $logo .= 'class="custom-logo-link">';
                    $logo .= '<img src="'. esc_url( $logo_url ) .'" alt="'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'" class="custom-logo">';
                $logo .= '</a>';
                echo $logo;
            }
            else
            if( has_custom_logo() ) {
                the_custom_logo();
            } else {
                $logo  = '<a href="'. esc_url( home_url( '/' ) ) .'" title="'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'" ';
                $logo .= 'class="custom-logo-link txt">';
                    $logo .= esc_attr( get_bloginfo( 'name', 'display' ) );
                    if( get_bloginfo( 'description' ) ) {
                        $logo .= '<span class="descr">'. esc_html( get_bloginfo( 'description', 'display' ) ) .'</span>';
                    }
                $logo .= '</a>';
                echo $logo;
            }
        }
    }
}
add_action( 'fury_logo', 'fury_logo' );


/**
 * Theme Menu
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_menu' ) ) {
    function fury_menu( $location = null ) {
        $args['container']      = false;
        $args['theme_location'] = $location;
        switch( $location ) {
            case 'fury-primary':
                $args['link_before'] = '<span>';
                $args['link_after']  = '</span>';
            break;
            case 'fury-offcanvas':
                $args['menu_class']  = 'menu';
                $args['menu_id']     = 'menu';
                $args['link_before'] = '';
                $args['link_after']  = '';
            break;
            case 'fury-mobile':
                $args['link_before'] = '';
                $args['link_after']  = '';
            break;
        }
        wp_nav_menu( $args );
    }
}
add_action( 'fury_menu', 'fury_menu' );


/**
 * Off-Canvas Menu
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_off_canvas_menu' ) ) {
    function fury_off_canvas_menu( $location = null ) {
        if( class_exists( 'woocommerce' ) ) {
            $terms = get_terms( 'product_cat' );
            echo '<ul class="menu">';
            if ( $terms ) {
                foreach ( $terms as $term ) {
                    if( $term->parent == 0 ) {
                        $term_children = get_term_children( $term->term_id, 'product_cat' );
                        $has_children = ! empty( $term_children ) ? true : false;
                        $class = ! empty( $term_children ) ? ' class="has-children"' : '';
                        echo '<li'. $class .'>';
                            if( $has_children ) echo '<span>';
                                echo '<a href="'. esc_url( get_term_link( $term ) ) .'" class="'. esc_attr( $term->slug ) .'">';
                                    echo esc_html( $term->name );
                                echo '</a>';
                            if( $has_children ) echo '<span class="sub-menu-toggle"></span></span>';
                            if( $has_children ) {
                                echo '<ul class="offcanvas-submenu">';
                                    foreach( $term_children as $subcategory ) {
                                        $term = get_term_by( 'id', $subcategory, 'product_cat' );
                                        echo '<li><a href="'. esc_url( get_term_link( $subcategory, 'product_cat' ) ) .'">'. esc_html( $term->name ) .'</a></li>';
                                    }
                                echo '</ul>';
                            }
                        echo '</li>';
                    }
                }
            } else {
                echo '<li><a href="#">'. esc_html__( 'No Product Categories Found', 'fury' ) .'</a></li>';
            }
            echo '</ul>';
        } else { // Custom Menu
            do_action( 'fury_menu', 'fury-offcanvas' );
        }
    }
}
add_action( 'fury_off_canvas_menu', 'fury_off_canvas_menu' );


/**
 * Cart Dropdown
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_cart_dropdown' ) ) {
    function fury_cart_dropdown() {
        global $woocommerce;
        
        $items      = $woocommerce->cart->get_cart();
        $currency   = get_woocommerce_currency_symbol();
        
        echo '<div class="toolbar-dropdown">';
            if( ! empty( $items ) ) {
                foreach( $items as $item => $values ) {
                    
                    $price      = get_post_meta( $values['product_id'] , '_price', true );
                    $thumb      = wc_get_product( $values['product_id'] ); // Product Image
                    $_product   = wc_get_product( $values['data']->get_id() );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $values['product_id'], $values, $item );
                    
                    $remove     = apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                        '<a href="%s" aria-label="%s" class="remove-from-cart" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s"><i class="icon-cross"></i></a>',
                        esc_url( wc_get_cart_remove_url( $item ) ),
                        esc_html__( 'Remove this item', 'fury' ),
                        esc_attr( $product_id ),
                        esc_attr( $_product->get_sku() ),
                        esc_attr( $item )
                    ), $item );
                    
                    echo '<div class="dropdown-product-item">';
                        echo '<span class="dropdown-product-remove">';
                            echo $remove;
                        echo '</span>';
                        echo '<a class="dropdown-product-thumb" href="'. esc_url( $_product->get_permalink() ) .'">';
                            echo $thumb->get_image();
                        echo '</a>';
                        echo '<div class="dropdown-product-info">';
                            echo '<a class="dropdown-product-title" href="'. esc_url( $_product->get_permalink() ) .'">';
                                echo esc_html( $_product->get_title() );
                            echo '</a>';
                            echo '<span class="dropdown-product-details">';
                                echo esc_attr( $values['quantity'] ) .' x '; 
                                echo esc_attr( $currency ) . esc_html( $price );
                            echo '</span>';
                        echo '</div>';
                    echo '</div>';
                }
                echo '<div class="toolbar-dropdown-group">';
                    echo '<div class="column">';
                        echo '<span class="text-lg">'. esc_html__( 'Total', 'fury' ) .':</span>';
                    echo '</div>';
                    echo '<div class="column text-right">';
                        echo '<span class="text-lg text-medium">'. esc_html( strip_tags( WC()->cart->get_cart_total() ) ) .'</span>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="toolbar-dropdown-group">';
                    echo '<div class="column">';
                        echo '<a a href="'. esc_url( wc_get_cart_url() ) .'" class="btn btn-sm btn-block btn-secondary">'. 
                            esc_html__( 'View Cart', 'fury' ) .'</a>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<a href="'. esc_url( wc_get_checkout_url() ) .'" class="btn btn-sm btn-block btn-success">'. 
                            esc_html__( 'Checkout', 'fury' ) .'</a>';
                    echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="dropdown-product-item empty">';
                    esc_html_e( 'Cart is empty !', 'fury' );
                echo '</div>';
            }
        echo '</div>';
    }
}
add_action( 'fury_cart_dropdown', 'fury_cart_dropdown' );


/**
 * Header Image
 *
 * @since 1.0.4
 */
if( ! function_exists( 'fury_header_image' ) ) {
    function fury_header_image() {
        if( get_header_image() ) {
            echo '<div id="site-header" class="fury-header-image">';
                echo '<a href="'. esc_url( home_url( '/' ) ) .'" rel="home">';
                    echo '<img src="'. esc_url( get_header_image() ) .'" width="'. absint( get_custom_header()->width ) .'" 
                          height="'. absint( get_custom_header()->height ) .'" alt="'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'">';
                echo '</a>';
            echo '</div>';
        }
    }
}
add_action( 'fury_header_image', 'fury_header_image' );


/**
 * Theme Breadcrumb
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_breadcrumb' ) ) {
    function fury_breadcrumb() {
        if( get_theme_mod( 'fury_breadcrumb_enable', true ) ) {
            get_template_part( 'framework/classes/class-fury-breadcrumb' );
            fury_breadcrumb_trail();
        }
    }
}
add_action( 'fury_breadcrumb', 'fury_breadcrumb' );


/**
 * Content Wrapper
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_content_wrapper' ) ) {
    function fury_content_wrapper() {
        $row['class'] = '';
        
        if( 'none' == fury_mod_sidebar() ) {
            $row['class'] = esc_attr( ' justify-content-center' );
        }
        
        // Is WooCommerce Single Product Page.
        $single_product = false;
        if( class_exists( 'woocommerce' ) ) {
            if( is_product() ) {
                $single_product = true;
            }
        }
        
        // If is not full width template.
        if( ! is_page_template( 'templates/full-width.php' ) ) {
            echo '<!-- Content Wrapper -->';
            echo '<div class="content-wrapper container padding-bottom-3x clearfix">';
            
            // Do not output row if is 404 page or WooCommerce single product page.
            if( ! is_404() && ! $single_product ) {
                echo '<div class="row'. $row['class'] .'">';
            }
        }
    }
}
add_action( 'fury_content_wrapper', 'fury_content_wrapper' );


/**
 * Content Wrapper End
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_content_wrapper_end' ) ) {
    function fury_content_wrapper_end() {
        if( ! is_404() ) {
            echo '</div><!-- Row End -->';
        }
        echo '</div><!-- Content Wrapper End -->';
    }
}
add_action( 'fury_content_wrapper_end', 'fury_content_wrapper_end' );


/**
 * Post Meta
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_post_meta' ) ) {
    function fury_post_meta() {
        $meta['date']       = esc_attr( get_theme_mod( 'fury_blog_meta_date', true ) );
        $meta['author']     = esc_attr( get_theme_mod( 'fury_blog_meta_author', true ) );
        $meta['tags']       = esc_attr( get_theme_mod( 'fury_blog_meta_tags', true ) );
        $meta['comments']   = esc_attr( get_theme_mod( 'fury_blog_meta_comments', true ) );
        
        $class = apply_filters( 'fury_post_meta_wrapper_class', 'col-md-3' );
        
        $html = '<div class="'. esc_attr( $class ) .'">';
            $html .= '<ul class="post-meta">';
                if( $meta['date'] ) {
                    $html .= '<li><i class="icon-clock"></i> '. get_the_date() .'</li>';
                }
                if( $meta['author'] ) {
                    $html .= '<li><i class="icon-head"></i> '. ucfirst( get_the_author() ) .'</li>';
                }
                if( $meta['tags'] ) {
                    $html .= Fury_Helper::get_tags();
                }
                if( $meta['comments'] ) {
                    $html .= '<li><a href="'. esc_url( get_the_permalink() ) .'#comments"><i class="icon-speech-bubble"></i> ';
                    $html .= Fury_Helper::comments_count() . '</a></li>';
                }
            $html .= '</ul>';
        $html .= '</div>';
        
        echo $html;
    }
}
add_action( 'fury_post_meta', 'fury_post_meta' );


/**
 * Single Post Tags
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_post_tags' ) ) {
    function fury_post_tags() {
        if( has_tag() ) {
            foreach( get_the_tags() as $tag ) {
                $tag_link = get_tag_link( $tag->term_id );
                $html  = "<a href='{$tag_link}' title='{$tag->name} Tag' class='sp-tag'>";
                $html .= "#{$tag->name}</a>, ";
            }
            echo $html;
        }
    }
}
add_action( 'fury_post_tags', 'fury_post_tags' );


/**
 * Social Share Icons
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_social_share' ) ) {
    function fury_social_share() {
        $defaults = array(
            'facebook',
            'twitter',
            'linkedin',
            'google_plus'
        );
        if( get_theme_mod( 'fury_social_share', true ) ) {
            $icons = get_theme_mod( 'fury_social_share_sortable', $defaults );
            $html  = '<div class="entry-share">';
                $html .= '<span class="text-muted">'. esc_html__( 'Share', 'fury' ) .'</span>';
                $html .= '<div class="share-links">';
                    foreach( $icons as $icon ) {
                        switch( $icon ) {
                            case 'facebook':
                                $url    = sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%s', get_permalink() );
                                $title  = 'Facebook';
                            break;
                            case 'twitter':
                                $url    = sprintf( 'https://twitter.com/intent/tweet?url=%s', get_permalink() );
                                $title  = 'Twitter';
                            break;
                            case 'linkedin':
                                $url    = sprintf( 'http://www.linkedin.com/shareArticle?mini=true&url=%s', get_permalink() );
                                $title  = 'LinkedIn';
                            break;
                            case 'google_plus':
                                $url    = sprintf( 'https://plus.google.com/share?url=%s', get_permalink() );
                                $title  = 'Google+';
                                $icon   = 'google-plus';
                            break;
                        }
                        $html .= '<a href="'. esc_url( $url ) .'" class="social-button shape-circle sb-'. esc_attr( $icon ) .'" ';
                        $html .= 'data-toggle="tooltip" data-placement="top" data-original-title="'. esc_attr( $title ) .'">';
                            $html .= '<i class="socicon-'. esc_attr( str_replace( '-', '', $icon ) ) .'"></i>';
                        $html .= '</a>';
                    }
                $html .= '</div>';
            $html .= '</div>';
            
            echo $html;
        }
    }
}
add_action( 'fury_social_share', 'fury_social_share' );


/**
 * Post Navigation
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_post_nav' ) ) {
    function fury_post_nav() {
        if( get_theme_mod( 'fury_blog_single_post_nav', true ) ) {
            echo '<!-- Post Navigation -->';
            echo '<div class="entry-navigation">';
                echo '<div class="column text-left">';
                    previous_post_link( '%link', '<i class="fa fa-angle-left"></i> ' . esc_html__( 'Prev', 'fury' ) ); 
                echo '</div>';
                echo '<div class="column">';
                    echo '<a href="'. esc_url( home_url( '/' ) ) .'" class="btn btn-outline-secondary view-all" data-toggle="tooltip" ';
                    echo 'data-placement="top" data-original-title="'. esc_attr__( 'Home', 'fury' ) .'">';
                        echo '<i class="icon-menu"></i>';
                    echo '</a>';
                echo '</div>';
                echo '<div class="column text-right">';
                    next_post_link( '%link', esc_html__( 'Next', 'fury' ) . ' <i class="fa fa-angle-right"></i>' );
                echo '</div>';
            echo '</div><!-- Post Navigation End -->';
        }
    }
}
add_action( 'fury_post_nav', 'fury_post_nav' );


/**
 * Post Related Articles
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_post_related_articles' ) ) {
    function fury_post_related_articles() {
        global $post;
        
        if( get_theme_mod( 'fury_blog_single_post_related_articles', true ) ) {
            
            $heading = esc_html( get_theme_mod( 'fury_blog_single_post_related_articles_h4', esc_html__( 'You May Also Like', 'fury' ) ) );
        
            $categories = get_the_category( $post->ID );

            if( $categories ) {

                $category_ids = array();

                foreach( $categories as $individual_category ) {
                    $category_ids[] = $individual_category->term_id;
                }

                $args=array(
                    'category__in'          => $category_ids,
                    'post__not_in'          => array( $post->ID ),
                    'posts_per_page'        => 3, // Number of related posts that will be shown.
                    'ignore_sticky_posts'   => 1
                );

                $my_query = new wp_query( $args );

                if( $my_query->have_posts() ) {

                    echo '<h3 class="padding-top-3x padding-bottom-1x">'. $heading .'</h3>'; ?>

                    <div class="owl-carousel" data-owl-carousel='{"nav": false,"dots": true,"loop": true,"autoplay": true, "autoHeight": true,"margin": 30,"responsive": {"0":{"items":1},"630":{"items":2},"991":{"items":3},"1200":{"items":3}} }'><?php
                        
                        while( $my_query->have_posts() ) { 
                            $my_query->the_post(); ?>
                            <div class="widget widget-featured-posts">
                                <div class="entry">
                                    
                                    <?php if( has_post_thumbnail() ): ?>
                                    <div class="entry-thumb">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('fury-related'); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="entry-content">
                                        <h4 class="entry-title">
                                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <span class="entry-meta"><?php echo ucfirst( get_the_author() ); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        }

                    echo '</div>';
                }
            }
            wp_reset_query();
        }
    }
}
add_action( 'fury_post_related_articles', 'fury_post_related_articles' );


/**
 * Comments Pagination
 *
 * @used in comments.php
 * @since 1.0
 */
if( ! function_exists( 'fury_comments_pagination' ) ) {
    function fury_comments_pagination() {
        if( get_previous_comments_link() || get_next_comments_link() ) {
            $html = '<nav class="pagination">';
                $html .= '<div class="column">';
                    $html .= get_previous_comments_link();
                $html .= '</div>';
                $html .= '<div class="column text-align-right">';
                    $html .= get_next_comments_link();
                $html .= '</div>';
            $html .= '</nav>';
            echo $html;
        }
    }
}
add_action( 'fury_comments_pagination', 'fury_comments_pagination' );


/**
 * Blog Pagination
 * the_posts_pagination() - not using it since we need custom pagination html markup.
 * 
 * @since 1.0
 */
if( ! function_exists( 'fury_pagination' ) ) {
    function fury_pagination() {
        global $wp_query, $loop;
        
        $big        = 999999999;
        $total      = $wp_query->max_num_pages;
        $translated = esc_html__( 'Page', 'fury' );

        if( get_query_var('paged') ) {
            $paged = get_query_var('paged');
        }
        elseif( get_query_var('page') ) {
            $paged = get_query_var('page');
        } else { 
            $paged = 1; 
        }
        
        if( $total > 1 ) {
            echo '<nav class="pagination">';
                echo '<div class="column">';
                    echo paginate_links( array(
                        'base'                  => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'                => '?paged=%#%',
                        'current'               => max( 1, $paged ),
                        'total'                 => $total,
                        'prev_next'             => false,
                        'type'                  => 'list',
                        'before_page_number'    => '<span class="screen-reader-text">' . $translated . '</span>'
                    ) );
                echo '</div>';
                echo '<div class="column text-right hidden-xs-down">';
                    $next = get_next_posts_link( esc_html__( 'Next', 'fury' ) . '<i class="icon-arrow-right"></i>' );
                    $next = str_replace( 'href', 'class="btn btn-outline-secondary btn-sm" href', $next );
                    echo $next;
                echo '</div>';
            echo '</nav>';
        }
    }
}
add_action( 'fury_pagination', 'fury_pagination' );

/**
 * Custom jQuery Footer
 *
 * @since 1.1.6
 */
if( ! function_exists( 'fury_custom_jquery_footer' ) ) {
    function fury_custom_jquery_footer() {
        $code = strip_tags( get_theme_mod( 'fury_custom_jquery_footer', '' ) );
        
        $output  = '<script type="text/javascript">';
            $output .= $code;
        $output .= '</script>';
        
        echo $output;
    }
}
add_action( 'wp_footer', 'fury_custom_jquery_footer' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
