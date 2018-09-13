<!-- Footer Widget Secton -->
<?php $current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup()); ?>
<div class="hc_footer_widget_area">	
	<div class="container">
		<div id="sidebar-footer" class="row sidebar-footer">
      <?php 
        if ( is_active_sidebar( 'footer-widget-area' ) )
        { 
        	dynamic_sidebar( 'footer-widget-area' );
        }
        ?>
    </div>
    <div class="row hc_footer_area">
      <div class="col-md-8">
        <?php 
		if($current_options['footer_copyright']){
					echo $current_options['footer_copyright'];
		}
		else
		{ 
		if(get_option('hc_lite_options')) {?>
		<p><?php  
          if($current_options['footer_customizations']!='') { echo esc_attr($current_options['footer_customizations']); }	?>
         <?php if($current_options['created_by_text']!='') { echo esc_attr($current_options['created_by_text']); } ?>
          <a target="_blank" rel="nofollow" href="<?php if($current_options['created_by_link']!='') { echo esc_url($current_options['created_by_link']); } ?>"><?php if($current_options['created_by_webriti_text']!='') { echo esc_attr($current_options['created_by_webriti_text']); } ?></a>
        </p>
		<?php } } ?>
      </div>
	  <?php if( is_active_sidebar('footer-right-section') ) { ?>
			<div class="col-md-4 sidebar-footer-right">
				<?php dynamic_sidebar('footer-right-section'); ?>
			</div>
			<?php } ?>
    </div>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>