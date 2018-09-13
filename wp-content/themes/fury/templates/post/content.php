<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} 

$class = apply_filters( 'fury_post_content_wrapper_class', 'col-md-9' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
    
    <?php do_action( 'fury_post_meta' ); ?>
    
    <div class="<?php echo esc_attr( $class ); ?> blog-post">
        
        <?php if( has_post_thumbnail() ): ?>
        <a href="<?php the_permalink(); ?>" class="post-thumb">
            <?php the_post_thumbnail(); ?>
        </a>
        <?php endif; ?>
        
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php the_excerpt(); ?>
        
    </div>
    
</article>
