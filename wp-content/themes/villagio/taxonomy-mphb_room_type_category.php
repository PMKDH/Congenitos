<?php
/**
 * The template for displaying archive taxonomy mphb_room_type_category
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Villagio
 */

get_header(); ?>
	<div class="wrapper main-wrapper clear">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php if ( have_posts() ) : ?>
					<header class="page-header">
						<?php echo '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>'; ?>
					</header><!-- .page-header -->
					<?php
					/* Start the Loop */
					$wrapperClass = apply_filters( 'mphb_sc_rooms_wrapper_class', 'mphb_sc_rooms-wrapper mphb-room-types' );
					echo '<div class="' . esc_attr( $wrapperClass ) . '">';
					do_action( 'mphb_sc_room_before_loop' );
					while ( have_posts() ) : the_post();
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						do_action( 'mphb_sc_room_before_item' );
						get_template_part( 'template-parts/content', 'mphb_room_type_category' );
						do_action( 'mphb_sc_room_after_item' );
					endwhile;
					// Previous/next page navigation.
					villagio_the_posts_pagination();
					do_action( 'mphb_sc_room_after_loop' );
					echo '</div>';
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif; ?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- .main-wrapper -->
<?php get_footer();

