<?php
/**
 * The template for the widget areas on home page
 *
 * @package Villagio
 */

// If we get this far, we have widgets. Let's do this.
?>
<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
	<aside id="homepage-widgets" class="homepage-widgets" role="complementary">
		<div class="homepage-widget-area">
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
		</div><!-- .widget-area -->
		<div class="clear"></div>
	</aside><!-- .homepage-widgets -->
<?php endif;