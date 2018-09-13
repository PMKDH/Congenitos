<?php
/**
 * Template Name: Fury Full-Width
 *
 * @package Theme-Vision
 * @subpackage Fury
 * @since 1.0.6
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php get_header(); ?>

<section class="container-fluid">
<?php if( have_posts() ): while( have_posts() ): the_post(); ?>

    <?php get_template_part( 'templates/page/content-page' ); ?>

<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
