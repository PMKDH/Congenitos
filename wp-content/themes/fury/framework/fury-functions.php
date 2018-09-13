<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Generate Header Class
 *
 * @used in header.php
 * @since 1.0
 */
function fury_header_class() {
    $header['sticky'] = esc_attr( 
        get_theme_mod( 'fury_header_sticky', true ) 
    );
    $header['transparent'] = esc_attr(
        get_theme_mod( 'fury_header_transparent', false )
    );
    if( $header['sticky'] ) {
        echo ' navbar-sticky';
    }
    if( $header['transparent'] ) {
        echo ' navbar-transparent';
    }
}

/**
 * Post Wrapper Class
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_post_wrapper_class' ) ) {
    function fury_post_wrapper_class() {
        switch( fury_mod_sidebar() ) {
            case 'left-sidebar':
                $class = 'col-xl-9 col-lg-8 order-lg-2';
            break;
            case 'none':
                $class = 'col-lg-10';
            break;
            default: $class = 'col-xl-9 col-lg-8';
        }
        echo esc_attr( $class );
    }
}

/**
 * Page/Post Title
 * enable | disable
 *
 * @since 1.0.6
 */
if( ! function_exists( 'fury_mod_title' ) ) {
    
    function fury_mod_title() {
        global $post;
        
        // If enabled through meta.
        $meta = esc_attr( get_post_meta( $post->ID, '_fury_title', true ) );
        if( $meta ) {
            return $meta;
        }
        
        // If customizer page title is set.
        $page_title = esc_attr( get_theme_mod( 'fury_page_title', true ) );
        if( $page_title && is_page() ) {
            return 'on';
        }
        
        // If customizer post title is set.
        $post_title = esc_attr( get_theme_mod( 'fury_blog_single_post_title', true ) );
        if( $post_title && is_single() ) {
            return 'on';
        }
    }
    
}

/**
 * Social Share Enable - Disable
 * 
 * @since 1.0.6
 */
if( ! function_exists( 'fury_mod_social_share' ) ) {
    
    function fury_mod_social_share() {
        global $post;
        
        // If enabled through meta.
        $meta = esc_attr( get_post_meta( $post->ID, '_fury_social_share', true ) );
        if( $meta ) {
            return $meta;
        }
        
        // If enabled through customizer.
        $customizer = esc_attr( get_theme_mod( 'fury_social_share', true ) );
        if( $customizer ) {
            return 'on';
        }
    }
    
}

/**
 * Sidebar Layout
 *
 * @since 1.0
 */
if( ! function_exists( 'fury_mod_sidebar' ) ) {
    
    function fury_mod_sidebar() {
        global $post;
        
        if( is_front_page() ) {
            $post_id = esc_attr( get_option( 'page_on_front' ) );
        } else {
            $post_id = is_object( $post ) ? $post->ID : '';
        }
        
        // If enabled through meta.
        $meta = isset( $post_id ) ? esc_html( get_post_meta( $post_id, '_fury_sidebar_layout', true ) ) : '';
        if( $meta ) {
            return $meta;
        }
        
        // If customizer content layout set.
        $customizer = esc_attr( get_theme_mod( 'fury_sidebar_layout', 'right-sidebar') );
        if( $customizer ) {
            return $customizer;
        }
    }
    
}

/**
 * Add "active" Class to Active Navigation
 *
 * @since 1.0
 */
function fury_active_nav_class( $classes, $item ) {
    if( in_array( 'current-menu-item', $classes ) ) {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'fury_active_nav_class', 10, 2 );

/**
 * Add Custom Class To Tables
 *
 * @since 1.0
 */
function fury_add_custom_table_class( $content ) {
    return str_replace( '<table>', '<table class="table">', $content );
}
add_filter( 'the_content', 'fury_add_custom_table_class' );

/**
 * Modify The Read More Link Text
 *
 * @since 1.0
 */
function fury_modify_read_more_link() {
    $html  = '<a class="more-link text-medium" href="' . get_permalink() . '">';
    $html .= esc_html__( 'Read More', 'fury' ) . '</a>';
    
    return $html;
}
add_filter( 'the_content_more_link', 'fury_modify_read_more_link' );

/**
 * Modify the Read More text when using the the_excerpt()
 * 
 * @since 1.0
 */
function fury_new_excerpt_more( $more ) {
    global $post;
	
    $txt   = get_theme_mod( 'fury_blog_meta_read_more_custom', __( 'Read More', 'fury' ) );
    $html  = '<p class="margin-top-10"><a class="moretag text-medium" href="'. get_permalink( $post->ID ) .'">';
    $html .= esc_html( $txt ) .'</a></p>';
    
    return $html;
}
add_filter('excerpt_more', 'fury_new_excerpt_more');

/**
 * Change the Tag Cloud's Font Sizes
 *
 * @since 1.0
 */
function fury_change_tag_cloud_font_sizes( array $args ) {
    $args['smallest']   = '10';
    $args['default']    = '10';
    $args['largest']    = '10';
    
    return $args;
}
add_filter( 'widget_tag_cloud_args', 'fury_change_tag_cloud_font_sizes' );

/**
 * Modify "Categories" Widget Dropdown Class
 * From "postform" to "form-control".
 *
 * @since 1.0
 */
function fury_widget_categories_dropdown_args( $cat_args ) {
    $cat_args['class'] = 'form-control';
    return $cat_args;
}
add_filter( 'widget_categories_dropdown_args', 'fury_widget_categories_dropdown_args' );

/**
 * Add Custom Class To Single Post Navigation
 *
 * @since 1.0
 */
function fury_posts_link_attributes( $output ) {
    $code = 'class="btn btn-outline-secondary btn-sm"';
    return str_replace( '<a href=', '<a '. $code .' href=', $output );
}
add_filter( 'next_post_link', 'fury_posts_link_attributes' );
add_filter( 'previous_post_link', 'fury_posts_link_attributes' );

if( ! function_exists( 'fury_comment' ) ) {
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own fury_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since 1.0
     */
    function fury_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) {
            case 'pingback':
            case 'trackback':
                
            break;
            default: // Normal Comments
                global $post; ?>
                <div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
                    <div class="comment-author-ava">
                        <?php echo get_avatar( $comment, 50 ); ?>
                    </div>
                    <div class="comment-body">
                        <div class="comment-header">
                            <h4 class="comment-title">
                                <?php echo get_comment_author(); ?>
                                <?php 
							     // If current post author is also comment author, make it known visually.
							     if( $comment->user_id === $post->post_author ) {
                                     echo '<i class="fa fa-star"></i> ';
                                 } ?>
                            </h4>
                        </div>
                        <p class="comment-text">
                            <?php comment_text(); ?>
                            <?php if( '0' == $comment->comment_approved ): ?>
                                <div class="iziToast iziToast-info">
                                    <div class="iziToast-body" style="padding-left: 33px;">
                                        <i class="iziToast-icon icon-flag"></i>
                                        <strong><?php esc_html_e( 'Info', 'fury' ); ?></strong>
                                        <p><?php esc_html_e( 'Your comment is awaiting moderation.', 'fury' ); ?></p>
                                    </div>
                                  <button class="iziToast-close"></button>
                                </div>
                            <?php endif; ?>
                        </p>
                        <div class="comment-footer">
                            <div class="column">
                                <span class="comment-meta">
                                    <?php 
                                    printf( 
                                        '<time datetime="%s">%s</time>',
                                        get_comment_time( 'c' ),
                                        /* translators: 1: date, 2: time */
                                        sprintf( esc_html__( '%1$s at %2$s', 'fury' ), get_comment_date(), get_comment_time() ) 
                                    ); ?>
                                </span>
                            </div>
                            <div class="column">
                                <?php
                                // Comment Reply
                                comment_reply_link( array_merge( 
                                    $args, 
                                    array( 
                                        'reply_text'    => '<i class="icon-reply"></i>' . esc_html__( 'Reply', 'fury' ), 
                                        'depth'         => $depth, 
                                        'max_depth'     => $args['max_depth'] 
                                    ) 
                                ) );
                                // Comment Edit (Administrators)
                                edit_comment_link( ' | <i class="fa fa-edit"></i>' . esc_html__( 'Edit', 'fury' ) ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            break;
        }
    }
}

/**
 * Comment Form Defaults
 *
 * @since 1.0
 */
function fury_comment_form_defaults( $defaults ) {
    global $current_user;
    
    $comments['tags_suggestion'] 		= esc_attr( get_theme_mod( 'fury_comments_tags_suggestion', true ) );
    
    $defaults['class_form']             = 'row';
    
    $defaults['title_reply_before']     = '<h4 class="padding-top-2x padding-bottom-1x">';
    $defaults['title_reply_after']      = '</h4>';
    
    $defaults['title_reply']			= sprintf( '%s <span>%s</span>', esc_html__( 'Leave a', 'fury' ), esc_html__( 'Comment', 'fury' ) );
    $defaults['logged_in_as']           = '';
    
    // Comment Textarea
    $defaults['comment_field']          = '<div class="col-12"><div class="form-group"><label for="comment">'. esc_html__( 'Comment', 'fury' );
    $defaults['comment_field']         .= '</label><textarea class="form-control form-control-rounded" rows="7" id="comment" ';
    $defaults['comment_field']         .= 'name="comment"';
    $defaults['comment_field']         .= 'placeholder="'. esc_attr__( 'Write your comment here...', 'fury' ) .'" aria-required="true">';
    $defaults['comment_field']         .= '</textarea></div></div>';
    
    // Comment Submit Button
    $defaults['submit_button']          = '<div class="col-12 text-right"><input name="submit" type="submit" ';
    $defaults['submit_button']         .= 'class="btn btn-pill btn-primary" value="'. esc_attr__( 'Post Comment', 'fury' ) .'">';
    $defaults['submit_button']         .= '</div>';
    
    $defaults['comment_notes_before']   = '';
    
    // If Tags Suggestion Enabled
    if( $comments['tags_suggestion'] ) {
        $tags = esc_html__( 'You may use these', 'fury' ) . 
                ' <abbr title="'. esc_attr__( 'HyperText Markup Language', 'fury' ) .'">HTML</abbr> ' . 
                esc_html__( 'tags and attributes', 'fury' ) .': <code>'. allowed_tags() . '</code>';
        
        $defaults['comment_notes_after'] = '<div class="col-12"><p class="form-allowed-tags">'. $tags .'</p></div>';
    }
    
    return $defaults;
}
add_filter( 'comment_form_defaults', 'fury_comment_form_defaults' );

/**
 * Comment Form Fields
 *
 * @since 1.0
 */
function fury_comment_form_fields( $fields ) {
	
    // Get the current commenter if available
    $commenter = wp_get_current_commenter();
    
    // Core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );
    
    // Comment Author Input
    $fields['author']  = '<div class="col-sm-6"><div class="form-group"><label for="author">'. esc_html__( 'Name', 'fury' );
    $fields['author'] .= ( $req ? '<span class="required">*</span>' : '' );
    $fields['author'] .= '</label><input id="author" name="author" type="text" class="form-control form-control-rounded" ';
    $fields['author'] .= 'value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. esc_attr__( 'Name', 'fury' ) .'">';
    $fields['author'] .= '</div></div>';
    
    // Comment Email Input
    $fields['email']  = '<div class="col-sm-6"><div class="form-group"><label for="email">'. esc_html__( 'Email', 'fury' );
    $fields['email'] .= ( $req ? '<span class="required">*</span>' : '' );
    $fields['email'] .= '</label><input id="email" name="email" type="text" class="form-control form-control-rounded" ';
    $fields['email'] .= 'value="'. esc_attr(  $commenter['comment_author_email'] ) .'" placeholder="'. esc_attr__( 'Email', 'fury' ) .'">';
    $fields['email'] .= '</div></div>';
    
    // Comment URL Input
    $fields['url'] = '';
    
    return $fields;
}
add_filter( 'comment_form_default_fields', 'fury_comment_form_fields' );

/**
 * Move Comment Textarea to Bottom
 *
 * @since 1.0
 */
function fury_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    
    unset( $fields['comment'] );
    
    $fields['comment'] = $comment_field;
    
    return $fields;
}
add_filter( 'comment_form_fields', 'fury_move_comment_field_to_bottom' );

/**
 * Comments Pagination Attributes
 *
 * @since 1.0
 */
function fury_comments_link_attributes( $args ) {
    return 'class="btn btn-outline-secondary btn-sm"';
}
add_filter( 'next_comments_link_attributes', 'fury_comments_link_attributes' );
add_filter( 'previous_comments_link_attributes', 'fury_comments_link_attributes' );

/**
 * Check If WooCommerce Is Activated
 *
 * @since 1.0
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { 
            return true; 
        } else { 
            return false; 
        }
	}
}

/**
 * Set the WPForms ShareASale ID.
 *
 * @param string $shareasale_id The the default Shareasale ID.
 *
 * @since 1.1.2
 * @return string $shareasale_id
 */
function fury_wpforms_shareasale_id( $shareasale_id ) {
	
	// If this WordPress installation already has an WPForms Shareasale ID
	// specified, use that.
	if ( ! empty( $shareasale_id ) ) {
		return $shareasale_id;
	}
	
	// Define the Shareasale ID to use.
	$shareasale_id = '1355534';
	
	// This WordPress installation doesn't have an Shareasale ID specified, so 
	// set the default ID in the WordPress options and use that.
	update_option( 'wpforms_shareasale_id', $shareasale_id );
	
	// Return the Shareasale ID.
	return $shareasale_id;
}
add_filter( 'wpforms_shareasale_id', 'fury_wpforms_shareasale_id' );

/**
 * Generate Premium Plugin Download URI
 * executed only if "Fury Pro" plugin is installed & activated.
 *
 * @since 1.1.3
 */
function fury_generate_premium_plugin_uri( $slug ) {
    $defaults   = array( 'order_email' => '', 'order_id' => '' );
    $order      = unserialize( get_option( '_fury_license' ) );
    $args       = wp_parse_args( $order, $defaults );
    
    extract( $args );
    
    $order_email    = str_replace( ' ', '', $order_email );
    $order_id       = str_replace( '#', '', $order_id );
    $order_id       = str_replace( ' ', '', $order_id );
    
    if( isset( $order_email ) && isset( $order_id ) ) {
        $license = hash( 'sha256', $order_email . $order_id );
    } else {
        $license = '';
    }
    
    $uri = add_query_arg(
        array(
            'action'    => esc_attr( 'download' ),
            'slug'      => esc_attr( $slug ),
            'order_id'  => esc_attr( $order_id ),
            'license'   => esc_attr( $license ),
        ),
        Fury_Pro_Updater::$api_url
    );
    
    return $uri;
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
