<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
 if($current_options['callout_enable'] == false) {?>

<?php if( $current_options['call_out_text']!='' || $current_options['call_out_button_text']!='' ) { ?>
<!-- HC Callout Section -->
<div class="hc_callout_section">
	<div class="row hc_callout_area">
		<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup()); ?>
		
		<?php if($current_options['call_out_text']!='') { ?>
		<div class="col-md-9">
			<h1><?php echo esc_attr($current_options['call_out_text']); ?>
			</h1>
		</div>
		<?php } ?>
		
		<?php if($current_options['call_out_button_text']!='') { ?>
		<div class="col-md-3">
			<a href="<?php if($current_options['call_out_button_link']!='') { echo esc_url($current_options['call_out_button_link']); }?>" <?php if($current_options['call_out_button_link_target']==true) { echo "target='_blank'"; } ?> class="hc_callout_btn"><?php echo esc_attr($current_options['call_out_button_text']); ?></a>
		</div>
		<?php } ?>
		
	</div>
</div>
<!-- /HC Callout Section -->

<?php }
} ?>