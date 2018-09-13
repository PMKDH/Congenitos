<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
if($current_options['home_projects_enabled'] == false) { ?>
<div class="container">
	<?php if($current_options['project_heading_one'] || $current_options['project_tagline'] ) { ?>
	<div class="row">
		<div class="section-header">
			<?php if($current_options['project_heading_one']!='') { ?>
			<h1 class="section-title"><?php echo esc_attr($current_options['project_heading_one']); ?></h1>
			
			<?php } ?>
			<?php if($current_options['project_tagline']!='') { ?>
			<p class="section-subtitle"><?php echo esc_attr($current_options['project_tagline']); ?></p>
			<?php } ?>			
		</div>
	</div>		
	<?php  } if(is_active_sidebar('sidebar-project'))
	{
		echo '<div id="sidebar-project" class="row sidebar-project">';
		dynamic_sidebar('sidebar-project');
		echo '</div>';
	}
	?>
	<div class="row"><div class="hc_home_border"></div></div>
</div><?php } ?>
<!-- /HC Portfolio Section -->	