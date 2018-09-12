<?php
/**
 * The template for the widget areas on home page
 *
 * @package Villagio
 */

// If we get this far, we have widgets. Let's do this.
?>
<?php if ( is_active_sidebar( 'sidebar-6' ) ) : ?>
	<aside id="homepage-widgets-bottom" class="homepage-widgets-bottom" role="complementary">
		<?php dynamic_sidebar( 'sidebar-6' ); ?>
		<div class="clear"></div>
	</aside><!-- .homepage-widgets-bottom -->
<?php endif;