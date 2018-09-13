<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Post Meta Wrapper Class
 *
 * @using in fury-actions.php
 * @func fury_post_meta()
 * @since 1.0.4
 */
function fury_post_meta_wrapper_class( $class = null ) {
    if( empty( $class ) ) {
        $class = 'col-md-3';
    } else {
        return $class;
    }
}
add_filter( 'fury_post_meta_wrapper_class', 'fury_post_meta_wrapper_class' );


/**
 * Fury Post Content Wrapper Class
 *
 * @using in templates/post/content.php
 * @since 1.0
 */
function fury_post_content_wrapper_class( $class = null ) {
    if( empty( $class ) ) {
        $class = 'col-md-9';
    } else {
        return $class;
    }
}
add_filter( 'fury_post_content_wrapper_class', 'fury_post_content_wrapper_class' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
