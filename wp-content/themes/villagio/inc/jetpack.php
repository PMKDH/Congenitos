<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Villagio
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function villagio_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'villagio_infinite_scroll_render',
		'footer'    => 'page',
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

    add_theme_support( 'jetpack-content-options', array(
        'author-bio'         => true, // display or not the author bio: true or false.
        'author-bio-default' => false, // the default setting of the author bio, if it's being displayed or not: true or false (only required if false).
        'post-details'       => array(
            'stylesheet'      => 'villagio-style', // name of the theme's stylesheet.
            'date'            => '.posted-on', // a CSS selector matching the elements that display the post date.
            'categories'      => '.cat-links', // a CSS selector matching the elements that display the post categories.
            'tags'            => '.tags-links', // a CSS selector matching the elements that display the post tags.
            'author'          => '.byline', // a CSS selector matching the elements that display the post author.
            'comment'         => '.comments-link', // a CSS selector matching the elements that display the comment link.
        ),
    ) );
}
add_action( 'after_setup_theme', 'villagio_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function villagio_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
}
