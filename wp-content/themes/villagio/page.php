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
 * @package Villagio
 */

get_header(); ?>

<?php if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		villagio_post_thumbnail(); ?>
		<div class="wrapper main-wrapper clear">
			<div id="primary" class="content-area ">
				<main id="main" class="site-main" role="main">
					<?php get_template_part( 'template-parts/content', 'page' );
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif; ?>
				</main><!-- #main -->
			</div><!-- #primary -->
            <?php if (function_exists('is_cart') && function_exists('is_checkout')) {
                if (!is_cart() && !is_checkout()) {
                    get_sidebar();
                }
            } else {
                get_sidebar();
            } ?>
		</div><!-- .wrapper -->
		<?php
	endwhile; // End of the loop.
else:?>
	<div class="wrapper main-wrapper clear">
		<div id="primary" class="content-area ">
			<main id="main" class="site-main" role="main">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- .wrapper -->
<?php endif;
get_footer();