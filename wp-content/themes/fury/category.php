<?php
/**
 * The template for displaying Category pages
 *
 * Used to display archive-type pages for posts in a category.
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
    
    <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
    
        <?php get_template_part( 'templates/post/content', get_post_format() ); ?>
    
    <?php endwhile; ?>
    
        <?php do_action( 'fury_pagination' ); ?>
    
    <?php else: ?>
    
    <?php endif; ?>
    
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
