<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.2
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

// Custom Modification
$attachment_ids = $product->get_gallery_image_ids();

?>
<div class="col-md-6">
    <div class="product-gallery">
        <?php
        if ( has_post_thumbnail() ) {
            
            $f_image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
            $f_image_alt        = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
            $f_image_title      = empty( $f_image_alt ) ? get_the_title() : $f_image_alt;
            $f_image_size       = $f_image_attributes[1] .'x'. $f_image_attributes[2];
            
            $html  = '<!-- Gallery Wrapper -->';
            $html .= '<div class="gallery-wrapper">';
                
                // Featured Image
                $html .= '<div class="gallery-item active">';
                    $html .= '<a href="'. esc_url( $f_image_attributes[0] ) .'" data-hash="hash-1" data-size="'. esc_attr( $f_image_size ) .'"></a>';
                $html .= '</div>';
                
                if( $attachment_ids ) {
                    $hash = 2;
                    foreach ( $attachment_ids as $attachment_id ) {
                        
                        $attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
                        $size       = $attributes[1] .'x'. $attributes[2];

                        $html .= '<div class="gallery-item">';
                            $html .= '<a href="'. esc_url( $attributes[0] ) .'" data-hash="hash-'. esc_attr( $hash ) .'" data-size="'. $size .'"></a>';
                        $html .= '</div>';

                        $hash++;
                    }
                }
            
            $html .= '</div><!-- Gallery Wrapper End -->';
            
            
                
            $html .= '<!-- Product Carousel -->';
                $html .= '<div class="product-carousel owl-carousel">';
                
                // Featured Image
                $html .= '<div data-hash="hash-1">';
                    $html .= '<img src="'. esc_url( $f_image_attributes[0] ) .'" alt="'. esc_html( $f_image_title ) .'">';
                $html .= '</div>';
                
                // Owl Carousel
                if( $attachment_ids ) {
                    $hash = 2;
                    
                    foreach ( $attachment_ids as $attachment_id ) {
                        $attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
                        $alt        = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
                        $title      = empty( $alt ) ? get_the_title() : $alt;

                        $html .= '<div data-hash="hash-'. esc_attr( $hash ) .'">';
                            $html .= '<img src="'. esc_url( $attributes[0] ) .'" alt="'. esc_html( $title ) .'">';
                        $html .= '</div>';

                        $hash++;
                    }
                }
            
            $html .= '</div><!-- Product Carousel End -->';
            
        } else {
            $html  = '<div class="gallery-wrapper">';
                $html .= '<div class="gallery-item active">';
                    $html .= sprintf( '<a href="%s" data-hash="hash-1" data-size="800x600"></a>', esc_url( wc_placeholder_img_src() ) );
                $html .= '</div>';
            $html .= '</div>';
            
            $html .= '<div class="product-carousel owl-carousel">';
                $html .= '<div data-hash="hash-1" class="no-img">';
                    $html .= '<img src="'. esc_url( wc_placeholder_img_src() ) .'" alt="'. esc_html__( 'Awaiting product image', 'fury' ) .'">';
                $html .= '</div>';
            $html .= '</div>';
        }

        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

        do_action( 'woocommerce_product_thumbnails' );
        ?>
    </div>
</div>
