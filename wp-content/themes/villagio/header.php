<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Villagio
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'villagio' ); ?></a>
	<header id="masthead" class="site-header" role="banner">
		<div class="wrapper">
			<div class="site-header-main">
				<div class="site-branding">
					<div class="site-logo-wrapper" itemscope>
						<?php villagio_the_custom_logo(); ?>
						<div class="site-title-wrapper">
							<?php if ( is_front_page() && is_home() ) : ?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
								                          rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php else : ?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
								                         rel="home"><?php bloginfo( 'name' ); ?></a></p>
								<?php
							endif;
							$description = apply_filters( 'villagio_tagline', get_bloginfo( 'description', 'display' ) );
							if ( $description || is_customize_preview() ) : ?>
								<p class="site-description screen-reader-text"><?php echo $description; /* WPCS: xss ok. */ ?></p>
								<?php
							endif; ?>
						</div>
					</div>
				</div><!-- .site-branding -->

				<div class="site-header-menu" id="site-header-menu">
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div class="menu-toggle-wrapper clear">
							<?php if ( has_nav_menu( 'menu-1' ) || has_nav_menu( 'menu-2' ) ) : ?>
								<button class="menu-toggle" aria-controls="primary-menu"
								        aria-expanded="false"><i class="fa fa-bars" aria-hidden="true"></i>
									<span><?php esc_html_e( 'Menu', 'villagio' ); ?></span></button>
							<?php endif; ?>
							<div class="search-icon-wrapper">
								<a href="#" class="search-icon">
									<i class="fa fa-search" aria-hidden="true"></i>
								</a>
							</div><!-- .search-icon-wrapper -->
						</div> <!--- .menu-toggle-wrapper -->
						<?php if ( has_nav_menu( 'menu-1' ) ) : ?>
							<?php wp_nav_menu( array(
								'theme_location'  => 'menu-1',
								'container_class' => 'menu-primary-container',
								'menu_id'         => 'primary-menu',
								'link_before'     => '<span class="menu-text">',
								'link_after'      => '</span>'
							) ); ?>
						<?php endif; ?>
						<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
						<?php wp_nav_menu( array(
							'theme_location'  => 'menu-2',
							'menu_id'         => 'top-navigation-mobile',
							'menu_class'      => 'top-navigation-mobile theme-social-menu',
							'container_class' => 'menu-top-right-container',
							'link_before'     => '<span class="menu-text">',
							'link_after'      => '</span>'
						) );
						?>
					</nav><!-- #site-navigation -->

					<?php endif;
					?>
					<div class="search-modal">
						<div class="wrapper">
							<a href="#" class="close-search-modal"><i class="fa fa-close"
							                                          aria-hidden="true"></i></a>
							<?php get_search_form(); ?>
						</div><!-- .wrapper -->
					</div><!-- .search-modal-->
				</div>


				<nav class="top-navigation-right" role="navigation"
				     aria-label="<?php esc_attr_e( 'Top Links Menu', 'villagio' ); ?>"><?php if ( has_nav_menu( 'menu-2' ) ) : ?>
						<?php wp_nav_menu( array(
							'theme_location'  => 'menu-2',
							'menu_id'         => 'top-navigation',
							'menu_class'     => 'theme-social-menu',
							'container_class' => 'menu-top-right-container',
							'link_before'     => '<span class="menu-text">',
							'link_after'      => '</span>'
						) ); ?>
					<?php endif;
                    do_action('villagio_header');
					?>
					<div class="search-icon-wrapper">
						<a href="#" class="search-icon">
							<i class="fa fa-search" aria-hidden="true"></i>
						</a>
					</div><!-- .search-icon-wrapper -->
				</nav>
			</div><!-- .site-header-main-->
		</div><!-- .wrapper -->

	</header><!-- #masthead -->
	<div id="content" class="site-content ">
