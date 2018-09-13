<!-- HC Service Section -->
<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
if($current_options['service_enable'] == false) { ?>
<div class="container">
	<?php if( $current_options['site_intro_tex'] || $current_options['call_now_text'] || $current_options['call_now_number']) {?>
	<div class="row hc_home_callout">		
		<?php if($current_options['site_intro_tex']!='') { ?>
		<div class="col-md-6 hc_home_title">
			<h1><?php echo esc_attr($current_options['site_intro_tex']); ?></h1>
			
		</div>
		<?php } ?>
		<?php if(($current_options['call_now_text']!='') || $current_options['call_now_number']!='') { ?>
		<div class="col-md-6 hc_home_callnow_title">			
			<h1><?php echo esc_attr($current_options['call_now_text']); ?><span><?php echo esc_attr($current_options['call_now_number']); ?></span></h2>
		</div>
		<?php } ?>
	</div>	
	<div class="row"><div class="hc_home_border"></div></div>
	<?php } 
	if($current_options['service_title'] || $current_options['service_description'])
	{?>
	<div class="row">
		<div class="section-header">
			<?php if($current_options['service_title']!='') { ?>
			<h1 class="section-title"><?php echo esc_attr($current_options['service_title']); ?></h1>
			<?php } ?>
			<?php if($current_options['service_description']!='') { ?>
			<p class="section-subtitle"><?php echo esc_attr($current_options['service_description']); ?></p>
			<?php } ?>		
		</div>
	</div>
	<?php } if(is_active_sidebar('sidebar-service'))
	{
		echo '<div id="sidebar-service" class="row sidebar-service">';
		dynamic_sidebar( 'sidebar-service' );
		echo '</div>';
	}
	 
	?>
	
	<div class="row"><div class="hc_home_border"></div></div>
</div><?php } ?>
<!-- /HC Service Section -->