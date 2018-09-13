<?php
/**
 * The template for displaying Search Results pages
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
<?php if( have_posts() ): ?>

    <?php while ( have_posts() ) : the_post(); ?>
    <div class="card mb-4">
        <div class="card-header">
            <span class="badge badge-pill badge-primary"><?php _e( 'Blog post', 'fury' ); ?></span>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <?php if( has_post_thumbnail() ): ?>
                    <a href="<?php the_permalink(); ?>" class="pr-4 hidden-xs-down" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>
                <div>
                    <h5><a href="<?php the_permalink(); ?>" class="navi-link"><?php the_title(); ?></a></h5>
                    <p><?php the_excerpt(); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    
    <?php do_action( 'fury_pagination' ); ?>
    
    <?php else: ?>

    <div class="card mb-4">
        <div class="card-header">
            <span class="badge badge-pill badge-danger"><?php _e( 'Search', 'fury' ); ?></span>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div>
                    <h5><?php _e( 'Not Found', 'fury' ); ?></h5>
                    <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fury' ); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
