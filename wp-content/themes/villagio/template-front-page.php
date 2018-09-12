<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * Template name: Front page
 *
 * @package Villagio
 */

get_header(); ?>
<?php if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		villagio_home_thumbnail(); ?>
		<div class="wrapper front-wrapper clear">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php get_template_part( 'template-parts/content', 'home' );
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif; ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrapper -->
		<?php
	endwhile; // End of the loop.
else:?>
	<div class="wrapper main-wrapper clear">
		<div id="primary" class="content-area full-width">
			<main id="main" class="site-main" role="main">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .wrapper -->
<?php endif;
get_footer();
