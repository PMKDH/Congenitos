<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
} ?>

<?php get_header(); ?>

<div class="<?php fury_post_wrapper_class(); ?>">
    
    <?php while ( have_posts() ) : the_post(); ?>
        
        <?php get_template_part( 'templates/page/content', 'page' ); ?>
    
        <?php comments_template(); ?>
    
    <?php endwhile; ?>
    
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
