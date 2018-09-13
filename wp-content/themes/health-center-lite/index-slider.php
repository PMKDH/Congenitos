<?php
$current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
$slider_post_one = $current_options['slider_post'];
$slider_post = array($slider_post_one);
if( $current_options['slider_post']!='' ){ ?>
<div class="row">
		<?php
			$slider_query = new WP_Query( array( 'post__in' => $slider_post));
				if( $slider_query->have_posts() ){                
					while( $slider_query->have_posts() ){
						$slider_query->the_post();		
			$defalt_arg =array('class' => "img-responsive");
			if( has_post_thumbnail() ){ ?>
		<?php the_post_thumbnail('', $defalt_arg);  } } } ?>
</div>
<?php } else {
	if($current_options = get_option('hc_lite_options'))
	{ 
	$current_options = wp_parse_args( get_option('hc_lite_options', array() ), theme_data_setup());
	?>
		<div class="row">		
			<img src="<?php echo esc_url($current_options['home_page_image']); ?>" class="img-responsive" />
		</div>
		
<?php } }  ?>