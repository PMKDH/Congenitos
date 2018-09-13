<?php
/**
 * The template for displaying 404 pages (Not Found)
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

<img class="d-block m-auto" src="<?php echo esc_url( fury_img ) . '404.jpg'; ?>" alt="<?php esc_attr_e( '404 Not Found', 'fury' ); ?>">

<div class="padding-top-1x mt-2 text-center">
    
    <h3><?php esc_html_e( 'Page Not Found', 'fury' ); ?></h3>
    
    <p><?php esc_html_e( 'It seems we can\'t find page you are looking for. Maybe try a search ?', 'fury' ); ?></p>
    
    <?php get_search_form(); ?>
    
</div>

<?php get_footer(); ?>
