<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Villagio
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php villagio_post_thumbnail(); ?>
    <header class="entry-header">
        <?php if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            if (is_sticky()) : ?>
                <div class="sticky-post-wrapper">
                    <span class="sticky-post"><?php esc_html_e('Featured', 'villagio'); ?></span>
                </div>
            <?php endif;
        } ?>
        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
        <?php the_content(sprintf(
        /* translators: %s: Name of current post. */
            wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'villagio'), array('span' => array('class' => array()))),
            the_title('<span class="screen-reader-text">"', '"</span>', false)
        ));
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'villagio'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->
    <?php
    villagio_entry_footer(); ?>
</article><!-- #post-## -->