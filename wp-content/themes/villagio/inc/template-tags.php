<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Villagio
 */

if ( ! function_exists( 'villagio_get_posted_on_by' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function villagio_get_posted_on_by() {
		global $post;
		$string      = '';
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<i class="fa fa-clock-o" aria-hidden="true"></i> <time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';


		$byline = sprintf(
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="fa fa-user" aria-hidden="true"></i> '
			. esc_html( get_the_author() ) . '</a></span>'
		);

		$string = '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		return $string;
	}
endif;
if ( ! function_exists( 'villagio_posted_on_by_filter' ) ) :
	function villagio_posted_on_by_filter( $result ) {
		if ( 'mphb_room_type' === get_post_type() ) :
			return '';
		endif;

		return $result;
	}

endif;
add_filter( 'villagio_get_posted_on_by', 'villagio_posted_on_by_filter' );

if ( ! function_exists( 'villagio_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time, author, categories.
	 */
	function villagio_posted_on() {
		global $post;
		$posted_on_by = apply_filters( 'villagio_get_posted_on_by', villagio_get_posted_on_by() );
		if ( $posted_on_by != '' ) {
			echo $posted_on_by;
		}
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			$id     = get_the_ID();
			$title  = get_the_title();
			$number = get_comments_number( $id );
			$more   = wp_kses(_n( '%1$s Comment<span class="screen-reader-text"> on %2$s</span>', '%1$s Comments<span class="screen-reader-text"> on %2$s</span>', $number, 'villagio' ), array( 'span' => array( 'class' => array() ) ));

			echo '<span class="comments-link">';
			comments_popup_link( '<i class="fa fa-comment" aria-hidden="true"></i> ' . sprintf( wp_kses(__( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'villagio' ), array( 'span' => array( 'class' => array() ) )), $title ),
				'<i class="fa fa-comment" aria-hidden="true"></i> ' . sprintf( wp_kses(__( '1 Comment<span class="screen-reader-text"> on %s</span>', 'villagio' ), array( 'span' => array( 'class' => array() ) )), $title ),
				'<i class="fa fa-comment" aria-hidden="true"></i> ' . sprintf( $more, number_format_i18n( $number ), $title ) );
			echo '</span>';
		}
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( ', ' );
			if ( $categories_list && villagio_categorized_blog() ) {
				printf( '<span class="cat-links"><i class="fa fa-folder" aria-hidden="true"></i> %1$s</span>', $categories_list ); // WPCS: XSS OK.
			}
			villagio_the_tags();

		}

	}
endif;

if ( ! function_exists( 'villagio_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function villagio_entry_footer() { ?>
		<div class="entry-footer">
			<?php
			if ( 'post' === get_post_type() ) {
				villagio_posted_on();
			}
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
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function villagio_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'villagio_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'villagio_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so villagio_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so villagio_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in villagio_categorized_blog.
 */
function villagio_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'villagio_categories' );
}

add_action( 'edit_category', 'villagio_category_transient_flusher' );
add_action( 'save_post', 'villagio_category_transient_flusher' );


if ( ! function_exists( 'villagio_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function villagio_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<header class="entry-header">
				<?php global $post;
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'villagio-thumb-large' );
				?>
				<div class="post-thumbnail"
				     style="background-image: url(<?php echo esc_url( $thumb['0'] ); ?>);max-width:<?php echo $thumb['1'] ?>px;">
					<?php the_post_thumbnail( 'villagio-thumb-medium' ); ?>

				</div><!-- .post-thumbnail -->
				<div class="wrapper header-wrapper">
					<?php if ( is_sticky() ) : ?>
						<div class="sticky-post-wrapper">
							<span class="sticky-post"><?php esc_html_e( 'Featured', 'villagio' ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( function_exists( 'villagio_mphb_loop_room_type_categories' ) ) : villagio_mphb_loop_room_type_categories(); endif; ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</div><!-- .wrapper -->
			</header><!-- .entry-header -->
		<?php else : ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
				<?php if ( is_sticky() ) : ?>
					<span class="sticky-post"><?php esc_html_e( 'Featured', 'villagio' ); ?></span>
				<?php endif; ?>
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			</a><!-- .post-thumbnail -->
		<?php endif; // End is_singular()
	}
endif;
if ( ! function_exists( 'villagio_home_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function villagio_home_thumbnail() {
		get_sidebar( 'frontpage' );
	}
endif;

if ( ! function_exists( 'villagio_the_post_navigation' ) ) :
	/**
	 * Displays the post navigation.
	 */
	function villagio_the_post_navigation() {
		$prevPost      = get_previous_post();
		$prevthumbnail = '';
		$classPrev     = ' no-bg';
		$classNext     = ' no-bg';
		if ( $prevPost ) {
			$img = get_the_post_thumbnail_url( $prevPost->ID, 'villagio-thumb-small' );
			if ( $img ) {
				$prevthumbnail = ' style="background-image:url(' . $img . ');"';
				$classPrev     = '';
			}
		}
		$nextPost      = get_next_post();
		$nextthumbnail = '';
		if ( $nextPost ) {
			$img = get_the_post_thumbnail_url( $nextPost->ID, 'villagio-thumb-small' );
			if ( $img ) {
				$nextthumbnail = ' style="background-image:url(' . $img . ');"';
				$classNext     = '';
			}
		}
		the_post_navigation( array(
			'next_text' => '<div class="nav-bg' . $classNext . '"' . $nextthumbnail . '><div><span class="meta-nav" aria-hidden="true">' . esc_html__( 'next', 'villagio' ) . '</span> ' .
			               '<span class="screen-reader-text">' . esc_html__( 'Next post:', 'villagio' ) . '</span> ' .
			               '<span class="post-title">%title</span></div></div>',
			'prev_text' => '<div class="nav-bg' . $classPrev . '"' . $prevthumbnail . '><div><span class="meta-nav" aria-hidden="true">' . esc_html__( 'previous', 'villagio' ) . '</span> ' .
			               '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'villagio' ) . '</span> ' .
			               '<span class="post-title">%title</span></div></div>'
		) );

	}
endif;

if ( ! function_exists( 'villagio_the_posts_pagination' ) ) :
	/**
	 * Displays the post pagination.
	 */
	function villagio_the_posts_pagination() {
		the_posts_pagination( array(
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'villagio' ) . ' </span>',
			'mid_size'           => 2,
		) );
	}
endif;

if ( ! function_exists( 'villagio_excerpt' ) ) :
	/**
	 * Displays the optional excerpt.
	 *
	 * Wraps the excerpt in a div element.
	 *
	 * Create your own villagio_excerpt() function to override in a child theme.
	 *
	 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
	 */
	function villagio_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) :
			$excerpt = get_the_excerpt();
			if ( ! empty( $excerpt ) ) { ?>
				<div class="<?php echo $class; ?>">
					<?php echo $excerpt; ?>
				</div><!-- .<?php echo $class; ?> -->
			<?php }
		endif;
	}
endif;
if ( ! function_exists( 'villagio_the_tags' ) ) :
	/**
	 * Displays post tags.
	 */
	function villagio_the_tags() {
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'Used between list items, there is a space.', 'villagio' ), '' );
			if ( $tags_list ) {
				printf( '<span class="tags-links"><i class="fa fa-tag" aria-hidden="true"></i> <span class="screen-reader-text">%1$s </span>%2$s</span>',
					esc_html_x( 'Tags', 'Used before tag names.', 'villagio' ),
					$tags_list
				);
			}
		}
	}
endif;


if ( ! function_exists( 'villagio_excerpt_more' ) && ! is_admin() ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
	 * a 'Continue reading' link.
	 *
	 * Create your own villagio_excerpt_more() function to override in a child theme.
	 *
	 *
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	function villagio_excerpt_more() {
		if ( get_post_type() === 'mphb_room_service' ) {
			return ' &hellip;';
		}
		$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( wp_kses(__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'villagio' ), array( 'span' => array( 'class' => array() ) )), get_the_title( get_the_ID() ) )
		);

		return ' &hellip; ' . $link;
	}

endif;

add_filter( 'excerpt_more', 'villagio_excerpt_more' );
if ( ! function_exists( 'villagio_read_more' ) ) :
	/**
	 * Create your own villagio_read_more() function to override in a child theme.
	 */
	function villagio_read_more( $more_link, $more_link_text ) {

		return '<p>' . $more_link . '</p>';
	}

endif;

if ( ! function_exists( 'villagio_child_pages' ) ) :
	/**
	 * Displays the page child pages.
	 */
	function villagio_child_pages() {
		global $post;
		$args   = array(
			'post_type'      => 'page',
			'posts_per_page' => 3,
			'post_parent'    => $post->ID,
			'order'          => 'ASC',
			'orderby'        => 'menu_order'
		);
		$parent = new WP_Query( $args );
		if ( $parent->have_posts() ) :?>
			<div class="entry-child-pages">
				<div class="entry-child-pages-wrapper">
					<?php while ( $parent->have_posts() ) : $parent->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php if ( ! ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) ) { ?>
								<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
									<?php the_post_thumbnail( 'post-thumbnail' ); ?>
									<i class="fa fa-link" aria-hidden="true"></i>
								</a>
							<?php }
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
							?>
						</article><!-- #post-## -->
					<?php endwhile; ?>
				</div><!-- .entry-child-pages -->
			</div><!-- .entry-child-pages -->
		<?php endif;
		wp_reset_query();

	}

endif;

if ( ! function_exists( 'villagio_child_pages_list' ) ) :
	/**
	 * Displays the page child pages.
	 */
	function villagio_child_pages_list() {
		global $post;
		$args   = array(
			'post_type'      => 'page',
			'posts_per_page' => 6,
			'post_parent'    => $post->ID,
			'order'          => 'ASC',
			'orderby'        => 'menu_order'
		);
		$parent = new WP_Query( $args );
		if ( $parent->have_posts() ) :?>
			<div class="entry-child-pages-list">
				<div class="entry-child-pages-list-wrapper">
					<?php while ( $parent->have_posts() ) : $parent->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-wrapper">
								<?php if ( ! ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) ) { ?>
									<a class="post-thumbnail" href="<?php the_permalink(); ?>"
									   aria-hidden="true">
										<?php the_post_thumbnail( 'villagio-thumb-small' ); ?>
									</a>
								<?php }
								the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
								?>
								<?php
								add_filter( 'the_content_more_link', 'villagio_read_more', 10, 2 );
								the_content( esc_html__( 'Read More', 'villagio' ) );
								remove_filter( 'the_content_more_link', 'villagio_read_more', 10, 2 ); ?>
							</div><!-- .entry-wrapper -->
						</article><!-- #post-## -->
					<?php endwhile; ?>
				</div><!-- .entry-child-pages -->
			</div><!-- .entry-child-pages -->
		<?php endif;
		wp_reset_query();

	}

endif;
if ( ! function_exists( 'villagio_related_posts' ) ) :
	/**
	 * Displays related posts
	 */
	function villagio_related_posts( $post ) {
		if ( 'post' === get_post_type() ) {
			$orig_post = $post;
			global $post;
			$tags = wp_get_post_tags( $post->ID );
			if ( $tags ) {
				$tag_ids = array();
				foreach ( $tags as $individual_tag ) {
					$tag_ids[] = $individual_tag->term_id;
				}
				$args     = array(
					'tag__in'        => $tag_ids,
					'post__not_in'   => array( $post->ID ),
					'posts_per_page' => 4
				);
				$my_query = new wp_query( $args );
				if ( $my_query->have_posts() ):
					?>
					<div class="related-posts">
						<h2 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'villagio' ); ?></h2>
						<!-- .related-posts-title -->
						<ul>
							<?php
							while ( $my_query->have_posts() ) {
								$my_query->the_post();
								?>
								<li>
									<a href="<?php the_permalink() ?>" rel="bookmark"
									   title="<?php the_title(); ?>"><?php the_title(); ?></a>
								</li>
							<?php } ?>
						</ul>
					</div><!-- .related-posts -->
					<?php
				endif;
				?>
				<?php
			}
			$post = $orig_post;
			wp_reset_query();
		}
	}

endif;
