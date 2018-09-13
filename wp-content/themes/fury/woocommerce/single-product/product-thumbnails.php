<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.2
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$post_thumbnail_id  = $product->get_image_id();
$attachment_ids     = $product->get_gallery_image_ids();

if ( $attachment_ids && has_post_thumbnail() ) {
    $hash = 2;
    
    $f_image_attributes = wp_get_attachment_image_src( $post_thumbnail_id );
    $f_image_alt        = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
    $f_image_title      = empty( $f_image_alt ) ? get_the_title() : $f_image_alt;
    
    echo '<ul class="product-thumbnails">';
    
    echo '<li class="active">';
        echo '<a href="#hash-1">';
            echo '<img src="'. esc_url( $f_image_attributes[0] ) .'" alt="'. esc_html( $f_image_title ) .'">';
        echo '</a>';
    echo '</li>';
    
	foreach ( $attachment_ids as $attachment_id ) {
        
        $attributes = wp_get_attachment_image_src( $attachment_id );
        $alt        = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
        $title      = empty( $alt ) ? get_the_title() : $alt;
        
        echo '<li>';
            echo '<a href="#hash-'. esc_attr( $hash ) .'">';
                echo '<img src="'. esc_url( $attributes[0] ) .'" alt="'. esc_html( $title ) .'">';
            echo '</a>';
        echo '</li>';
        
        $hash++;
	}
    echo '</ul>';
}
