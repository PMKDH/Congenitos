<?php
/**
 * Child themes template
 */
?>
<div id="child_themes" class="health-tab-pane">

	<?php
		$current_theme = wp_get_theme();
	?>
<div class="container-fluid">
		<div class="row">	

	<div class="health-pane-center">

		<h1><?php esc_html_e( 'Install & Use Health Center Lite Child Themes', 'health-center-lite' ); ?></h1>

		<p><?php esc_html_e( 'Below you will find a selection of Health center lite child themes that will totally transform the look of your site.', 'health-center-lite' ); ?></p>

	</div>

	<div class="col-md-4">
	<div class="health-tab-pane-half health-tab-pane-first-half">
		<!-- health Blue -->
		<div class="health-child-theme-container">
			<div class="health-child-theme-image-container">
				<img src="<?php echo esc_url( get_template_directory_uri() ) . '/functions/health-info/img/medical-treatment.png'; ?>" alt="<?php esc_html_e( 'Medical Treatment Child Theme', 'health-center-lite' ); ?>" />
				<div class="health-child-theme-description">
					<h2><?php esc_html_e( 'Medical Treatment', 'health-center-lite' ); ?></h2>
				</div>
			</div>
			<div class="health-child-theme-details">
				<?php if ( 'health-blue' != $current_theme['Name'] ) { ?>
					<div class="theme-details">
						<span class="theme-name"><?php _e('Medical Treatment','health-center-lite'); ?></span>
						<span class="theme-btn">
						<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=medical-treatment' ), 'install-theme_medical-treatment' ) ); ?>" class="button button-primary"><?php printf( __( 'Install %s now', 'health-center-lite' ), '<span class="screen-reader-text">medical-treatment</span>' ); ?></a>
						<a class="button button-secondary" target="_blank" href="https://wp-themes.com/medical-treatment"><?php esc_html_e( 'Live Preview','health-center-lite'); ?></a>
						</span>
						<div class="health-clear"></div>
					</div>
					<?php } else { ?>
					<div class="theme-details active">
						<span class="theme-name"><?php echo esc_html_e( 'medical-treatment - Current theme', 'health-center-lite' ); ?></span>
						<a class="button button-secondary customize right" target="_blank" href="<?php echo get_site_url(). '/wp-admin/customize.php' ?>"><?php esc_html_e('Customize','health-center-lite'); ?></a>
						<div class="health-clear"></div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
	<div class="col-md-4">
	<div class="health-tab-pane-half health-tab-pane-first-half">
		<!-- health Green -->
		<div class="health-child-theme-container">
			<div class="health-child-theme-image-container">
				<img src="<?php echo esc_url( get_template_directory_uri() ) . '/functions/health-info/img/health-care.png'; ?>" alt="<?php esc_html_e( 'Health Care Child Theme', 'health-center-lite' ); ?>" />
				<div class="health-child-theme-description">
					<h2><?php esc_html_e( 'Health care', 'health-center-lite' ); ?></h2>
				</div>
			</div>
			<div class="health-child-theme-details">
				<?php if ( 'health-green' != $current_theme['Name'] ) { ?>
					<div class="theme-details">
						<span class="theme-name"><?php _e('Health Care','health-center-lite'); ?></span>
						<span class="theme-btn">
						<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=health-care' ), 'install-theme_health-care' ) ); ?>" class="button button-primary"><?php printf( __( 'Install %s now', 'health-center-lite' ), '<span class="screen-reader-text">Health Care</span>' ); ?></a>
						<a class="button button-secondary" target="_blank" href="https://wp-themes.com/health-care"><?php esc_html_e( 'Live Preview','health-center-lite'); ?></a>
						</span>
						<div class="health-clear"></div>
					</div>
				<?php } else { ?>
					<div class="theme-details active">
						<span class="theme-name"><?php echo esc_html_e( 'Health Care - Current theme', 'health-center-lite' ); ?></span>
						<a class="button button-secondary customize right" target="_blank" href="<?php echo get_site_url(). '/wp-admin/customize.php' ?>"><?php esc_html_e('Customize','health-center-lite'); ?></a>
						<div class="health-clear"></div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
	
	<div class="col-md-4">	
		<div class="health-tab-pane-half health-tab-pane-first-half">	
			<!-- Zifer Child -->
			<div class="health-child-theme-container">
				<div class="health-child-theme-image-container">
					<img src="<?php echo esc_url( get_template_directory_uri() ) . '/functions/health-info/img/health-press.png'; ?>" alt="<?php esc_html_e( 'Health Press Child Theme', 'health-center-lite' ); ?>" />
					<div class="health-child-theme-description">
						<h2><?php esc_html_e( 'Health Press', 'health-center-lite' ); ?></h2>
					</div>
				</div>
				<div class="health-child-theme-details">
					<?php if ( 'health red' != $current_theme['Name'] ) { ?>
						<div class="theme-details">
							<span class="theme-name"><?php _e('Health Press','health-center-lite');?></span>
							<span class="theme-btn">
							<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=healthpress' ), 'install-theme_healthpress' ) ); ?>" class="button button-primary"><?php printf( __( 'Install %s now', 'health-center-lite' ), '<span class="screen-reader-text">Health Press</span>' ); ?></a>
							<a class="button button-secondary" target="_blank" href="https://wp-themes.com/healthpress"><?php esc_html_e( 'Live Preview','health-center-lite'); ?></a>
							</span>
							<div class="health-clear"></div>
						</div>
					<?php } else { ?>
						<div class="theme-details active">
							<span class="theme-name"><?php echo esc_html_e( 'Health Press - Current theme', 'health-center-lite' ); ?></span>
							<a class="button button-secondary customize right" target="_blank" href="<?php echo get_site_url(). '/wp-admin/customize.php' ?>"><?php esc_html_e( 'Customize','health-center-lite'); ?></a>
							<div class="health-clear"></div>
						</div>
					<?php } ?>
				</div>
			</div>
		 </div>
	 </div>
	</div>
</div>	
	</div>