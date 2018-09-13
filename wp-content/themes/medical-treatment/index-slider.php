<?php
	$hc_lite_options=medical_treatment_theme_data_setup(); 
	$current_options = wp_parse_args(  get_option( 'hc_lite_options', array() ), $hc_lite_options );
	$slider_post_one = $current_options['slider_post'];
	$slider_post = array($slider_post_one);
	if($current_options['home_slider_enabled'] == false) {
	if(  $current_options['slider_post'] !='' ){
	?>
	<div class="row" style="position:relative" >
		<?php
			$slider_query = new WP_Query( array( 'post__in' => $slider_post , 'ignore_sticky_posts' => 1));
				if( $slider_query->have_posts() ){                
					while( $slider_query->have_posts() ){
						$slider_query->the_post();		
			$defalt_arg =array('class' => "img-responsive");
			if( has_post_thumbnail() ){ ?>
		<?php the_post_thumbnail('', $defalt_arg);  ?>
		<div class="slide-caption">
			<div class="slide-text-bg1"><h1><?php the_title(); ?></h1>
			</div>
			<div class="slide-text-bg2"><span><?php the_content(); ?></span>
			</div>
		</div>
	<?php } } } ?>
	</div>
	<?php }
	else {
		if($current_options['hc_home_page_image']!='') { 
	?>
	<div class="row" style="position:relative" >	
		<img src="<?php echo esc_url($current_options['hc_home_page_image']); ?>" class="img-responsive" />
		<div class="slide-caption">
			<div class="slide-text-bg1"><h1><?php if(isset($current_options['home_image_title']))	{ echo esc_attr($current_options['home_image_title']); } ?></h1>
			</div>
			<div class="slide-text-bg2"><span><?php if(isset($current_options['home_image_description']))	{ echo esc_attr( $current_options['home_image_description']); } ?></span>
			</div>
			<?php 
			if($current_options['slider_readmore_text'] != '') { ?>
					<div class="slide-btn-area-sm">
					<a <?php  if($current_options['slider_image_readmore_ink_target'] == true ) { echo "target='_blank'"; }  ?> href="<?php if($current_options['slider_image_readmore_link']) { echo esc_attr($current_options['slider_image_readmore_link']);  } ?>" class="slide-btn-sm">
					<span><?php echo esc_attr($current_options['slider_readmore_text']); ?></span>
					</a>
					</div>
				<?php } ?>
		</div>
	</div>
	<?php }  } 
	
} ?>