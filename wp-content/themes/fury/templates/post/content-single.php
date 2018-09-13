<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} 

$author_posts_url = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>

<div class="single-post-meta">
    <div class="column">
        <div class="meta-link">
            <span><?php _e( 'By', 'fury' ); ?></span>
            <a href="<?php echo $author_posts_url; ?>">
                <?php echo ucfirst( get_the_author() ); ?>
            </a>
        </div>
        <div class="meta-link">
            <span><?php _e( 'In', 'fury' ); ?></span>
            <?php echo get_the_category_list( ', ' ); ?>
        </div>
    </div>
    <div class="column">
        <div class="meta-link">
            <i class="icon-clock"></i> <a href="#"><?php echo get_the_date(); ?></a>
        </div>
        <div class="meta-link">
            <a class="scroll-to" href="#comments">
                <i class="icon-speech-bubble"></i>
                <?php echo get_comments_number(); ?>
            </a>
        </div>
    </div>
</div>

<?php if( has_post_thumbnail() ): ?>
<div class="owl-carousel" data-owl-carousel='{ "nav": true," "dots": true, "loop": true }'>
    <figure>
        <?php the_post_thumbnail(); ?>
    </figure>
</div>
<?php endif; ?>

<?php if( 'on' == fury_mod_title() ): ?>
<h2 class="padding-top-2x"><?php the_title(); ?></h2>
<?php endif; ?>

<?php the_content(); ?>

<?php wp_link_pages( array( 'before' => '<kbd class="pages">' . esc_html__( 'Pages:', 'fury' ), 'after' => '</kbd>' ) ); ?>

<?php if( has_tag() || fury_mod_social_share() == 'on' ): ?>
<div class="single-post-footer">
    <div class="column">
        <?php do_action( 'fury_post_tags' ); ?>
    </div>
    <?php if( 'on' == fury_mod_social_share() ): ?>
    <div class="column">
        <?php do_action( 'fury_social_share' ); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>