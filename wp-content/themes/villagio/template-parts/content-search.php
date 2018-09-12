<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Villagio
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php villagio_post_thumbnail(); ?>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header><!-- .entry-header -->
	<?php villagio_excerpt(); ?>
	<?php villagio_entry_footer(); ?>
</article><!-- #post-## -->