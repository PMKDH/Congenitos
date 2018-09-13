<?php
/**
 * The template for displaying WooCommerce my account page
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
    
<?php while ( have_posts() ) : the_post(); ?>
    
    <?php the_content(); ?>

<?php endwhile; ?>

<?php get_footer(); ?>