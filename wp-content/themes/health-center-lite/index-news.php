<!-- HC Recent News & Why Choose us Section -->
<div class="container">
	<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup()); ?>
	
	<div id="sidebar-news" class="row sidebar-news">
	
		<?php if($current_options['news_enable'] == false){ ?>
		<!--Recent News-->
		<div class="col-md-6 hc_post_section">
		<?php
		if($current_options['news_enable'] == false){
			if(is_active_sidebar('sidebar-news'))
			{
				dynamic_sidebar('sidebar-news');
			}
			
		} 
		?>
		</div>
		
		<?php } 
		$current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
		if($current_options['faq_enable'] == false){ 
		?>
		<!--Why choose us-->
		<div class="col-md-6 hc_accordion_section">
		
			<?php if($current_options['hc_head_faq']!='') { ?>
			<div class="hc_heading_title">
				<h3><?php echo esc_attr($current_options['hc_head_faq']); ?></h3>
			</div>
			<?php }  
				 if( is_active_sidebar('sidebar-faq') ){
					dynamic_sidebar('sidebar-faq');
				 }
				?>
		</div>
		<?php } ?>
	</div>
</div>