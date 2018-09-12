<?php
/**
 * The template part for displaying an Author biography
 *
 * @package Villagio
 */
if (function_exists('jetpack_author_bio')) :
    jetpack_author_bio();
else:
    ?>
    <div class="entry-author">
        <h2 class="author-title"><span
                    class="author-heading"><?php esc_html_e('Author:', 'villagio'); ?></span> <?php echo get_the_author(); ?>
        </h2>
        <div class="author-avatar">
            <?php
            /**
             * Filter the villagio author bio avatar size.
             */
            $author_bio_avatar_size = apply_filters('villagio_author_bio_avatar_size', 100);
            echo get_avatar(get_the_author_meta('user_email'), $author_bio_avatar_size); ?>
        </div><!-- .author-avatar -->
        <div class="author-description">
            <p class="author-bio">
                <?php the_author_meta('description'); ?>
            </p><!-- .author-bio -->
            <p>
                <a class="author-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                   rel="author">
                    <?php printf(esc_html__('All Posts by %s', 'villagio'), get_the_author()); ?>
                </a>
            </p>
        </div><!-- .author-description -->
    </div><!-- .author-info -->
<?php endif;