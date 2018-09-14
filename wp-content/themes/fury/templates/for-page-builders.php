<?php
/**
 * Template Name: For Page Builders
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.1.8
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php get_header(); ?>

<section>
<?php if( have_posts() ): while( have_posts() ): the_post(); ?>

    <?php the_content(); ?>

<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
