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
	<?php if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) { ?>
		<header class="entry-header">
			<?php if ( is_sticky() ) : ?>
				<div class="sticky-post-wrapper">
					<span class="sticky-post"><?php esc_html_e( 'Featured', 'villagio' ); ?></span>
				</div>
			<?php endif; ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	<?php } ?>
	<div class="entry-content">
		<?php the_content( sprintf(
		/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'villagio' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'villagio' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->
		<?php villagio_entry_footer(); ?>
	<?php villagio_related_posts( $post ); ?>
	<?php if ( is_single() && get_the_author_meta( 'description' ) && 'post' === get_post_type() ) :
		get_template_part( 'template-parts/biography' );
	endif;
	?>
</article><!-- #post-## -->