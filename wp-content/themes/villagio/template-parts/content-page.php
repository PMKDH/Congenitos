<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Villagio
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) { ?>
		<?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' ); ?>
	<?php } ?>
	<div class="entry-content">
		<?php the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'villagio' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->
	<?php if ( current_user_can( 'edit_posts' ) ) {
		?>
		<div class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
				/* translators: %s: Name of current post */
					esc_html__( '%2$s Edit %1$s', 'villagio' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false ),
					'<i class="fa fa-pencil" aria-hidden="true"></i>'
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</div><!-- .entry-footer -->
		<?php
	} ?>
</article><!-- #post-## -->
