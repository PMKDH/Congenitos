<?php
/**
 * Getting started template
 */

$customizer_url = admin_url() . 'customize.php' ;
?>

<div id="getting_started" class="health-tab-pane active">

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h1 class="health-info-title text-center"><?php echo __('About Health Center Lite Theme','health-center-lite'); ?><?php if( !empty($health['Version']) ): ?> <sup id="health-theme-version"><?php echo esc_attr( $health['Version'] ); ?> </sup><?php endif; ?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="health-tab-pane-half health-tab-pane-first-half">
				<p><?php esc_html_e( 'Health Centre is a clean, elegant and very flexible WordPress theme for Health and Medical business company. All the elements required in a medical theme are given in HealthCentre. The theme is designed in such a manner makes it suitable for other service company and corporate categories.

Packed with everything you need to launch your site hassle free! Powerful and user friendly Customize Theme Options, Sidebar enabled HomePage Sections having support for all the WordPress core widgets. Premium theme is packed with many widgets like Service Page Widget, Portfolio Widget, Patient Testimonials Widget,Latest News Widget , Faq widgets , Info widget etc ','health-center-lite');?></p>
				<p>
				<?php esc_html_e( 'Premium version even allows you to configure the layout of sidebars, for example if you want to show services, portfolio,testomonials in 2,3 and 4 column layout, than you are not required to add any css of php code just specify the section layout and get the desired result. Theme also support famous Page Builders since number of widgets are built in. ', 'health-center-lite' ); ?>
				</p>
				<b>
				<p>
				<?php esc_html_e( 'Note: Intresting fact about this theme is, after upgrade you can start from where you have left in the lite version. You have to install the premium version as a separate package like any other WordPress Theme.', 'health-center-lite' ); ?>
				</p>
				</b>
				</div>
			</div>
			<div class="col-md-6">
				<div class="health-tab-pane-half health-tab-pane-first-half">
				<img src="<?php echo esc_url( get_template_directory_uri() ) . '/functions/health-info/img/health.png'; ?>" alt="<?php esc_html_e( 'health-center-lite Blue Child Theme', 'health-center-lite' ); ?>" />
				</div>
			</div>	
		</div>
	
	
		 <div class="row">
		 
			<div class="health-tab-center">

				<h1><?php esc_html_e( "Useful Links", 'health-center-lite' ); ?></h1>

			</div>
			
			<div class="col-md-6"> 
				<div class="health-tab-pane-half health-tab-pane-first-half">

					<a href="<?php echo 'http://webriti.com/demo/wp/lite/healthcentre/'; ?>" target="_blank"  class="info-block"><div class="dashicons dashicons-desktop info-icon"></div>
					<p class="info-text"><?php echo _e('Lite Demo','health-center-lite'); ?></p></a>
					
					
					<a href="<?php echo 'http://webriti.com/demo/wp/preview/?prev=healthcentre/'; ?>" target="_blank"  class="info-block"><div class="dashicons dashicons-book-alt info-icon"></div>
					<p class="info-text"><?php echo _e('View Pro','health-center-lite'); ?></p></a>
					
					<a href="<?php echo 'http://webriti.com/support/categories/healthcentre'; ?>" target="_blank"  class="info-block"><div class="dashicons dashicons-sos info-icon"></div>
					<p class="info-text"><?php echo _e('Premium Theme Support','health-center-lite'); ?></p></a>
					
				</div>
			</div>
			
			<div class="col-md-6">	
				<div class="health-tab-pane-half health-tab-pane-first-half">
					
					<a href="<?php echo 'https://wordpress.org/support/view/theme-reviews/health-center-lite#postform'; ?>" target="_blank"  class="info-block"><div class="dashicons dashicons-smiley info-icon"></div>
					<p class="info-text"><?php echo __('Rate This Theme','health-center-lite'); ?></p></a>
					
					<a href="<?php echo 'https://wordpress.org/support/theme/health-center-lite'; ?>" target="_blank"  class="info-block"><div class="dashicons dashicons-sos info-icon"></div>
					<p class="info-text"><?php echo _e('Support','health-center-lite'); ?></p></a>
				</div>
			</div>
			
		</div>	
	</div>
</div>	