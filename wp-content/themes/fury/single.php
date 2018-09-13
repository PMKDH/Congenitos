<?php
/**
 * The Template for displaying all single posts
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php get_header(); ?>

<div class="<?php fury_post_wrapper_class(); ?>">
    
    <?php while( have_posts() ): the_post(); ?>
    
        <?php get_template_part( 'templates/post/content', 'single' ); ?>
    
    <?php endwhile; ?>
    
    <?php do_action( 'fury_post_nav' ); ?>
    
    <?php do_action( 'fury_post_related_articles' ); ?>
    
    <?php comments_template(); ?>
    
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
