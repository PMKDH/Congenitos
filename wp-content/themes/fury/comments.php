<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to fury_comment() which is
 * located in the fury-functions.php file.
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * If comments are disabled or there are zero comment
 * return early without loading the comments.
 */
if( ! comments_open() || ! get_comments_number() ) {
    return;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
} ?>

<!-- Comments -->
<section class="padding-top-3x" id="comments">

    <h3 class="padding-bottom-1x">
        <?php esc_html_e( 'Comments', 'fury' ); ?>
    </h3>
    
    <?php wp_list_comments( array( 'callback' => 'fury_comment', 'style' => 'ol' ) ); ?>
    
    <?php do_action( 'fury_comments_pagination' ); ?>
    
    <?php comment_form(); ?>
    
</section><!-- Comments End -->
