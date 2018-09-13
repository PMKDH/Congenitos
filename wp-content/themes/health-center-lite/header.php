<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>" charset="<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>"/>
	<!-- Theme Css -->
	<?php 
	$hc_current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());	
	wp_head(); ?>
</head>
 <body <?php body_class(); ?> >
<!-- Wrapper -->
<div id="wrapper">
<!-- Header Section -->
<div class="header_section">
	<div class="container">
		<!-- Logo & Contact Info -->
		<div class="row">
			<div class="col-md-6">
				<div class="hc_logo">
					<h1>
					<?php
					if(has_custom_logo())
					{
					// Display the Custom Logo
					the_custom_logo();
					}
					elseif($hc_current_options['upload_image_logo'])
					{ ?>
					<img src="<?php echo $hc_current_options['upload_image_logo']; ?>" style="height:<?php if($hc_current_options['height']!='') { echo $hc_current_options['height']; }  else { "50"; } ?>px; width:<?php if($hc_current_options['width']!='') { echo $hc_current_options['width']; }  else { "150"; } ?>px;" />	
					<?php }
					// No Custom Logo, just display the site's name
					else {
					?>
					<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h1>
					<?php } 
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; ?></p>
					<?php endif; ?>
					</h1>
				</div>
			</div>					
			<div class="col-md-6">
				<?php if( is_active_sidebar('sidebar-header') ) { ?>
				<div class="head_cont_info sidebar-header">
					<?php dynamic_sidebar('sidebar-header'); ?>
				</div>
				<div class="clear"></div>
				<?php } ?>
			</div>
		</div>
		<!-- /Logo & Contact Info -->
	</div>	
</div>	
<!-- /Header Section -->	
<!-- Navbar Section -->
<div class="navigation_section">
	<div class="container navbar-container">
		<nav class="navbar navbar-default" role="navigation">
		  <div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
		  </div>
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php
			wp_nav_menu( array(  
					'theme_location' => 'primary',
					'container'  => 'nav-collapse collapse navbar-inverse-collapse',
					'menu_class' => 'nav navbar-nav',
					'fallback_cb' => 'webriti_fallback_page_menu',
					'walker' => new webriti_nav_walker()
					)
				);	
			?>
		  </div>
		</nav>
	</div>
</div><!-- /Navbar Section -->