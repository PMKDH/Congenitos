<?php
/**
 * Register Resent News widget
 *
 */
add_action('widgets_init','wbr_resent_news_widget');
function wbr_resent_news_widget(){
	
	return register_widget('wbr_resent_news_widget');
}

class wbr_resent_news_widget extends WP_Widget{
	
	function __construct() {
		parent::__construct(
			'wbr_resent_news_widget', // Base ID
			__('WBR : Recent News Widget', 'health-center-lite'), // Name
			array( 'description' => __( 'Recent News widget', 'health-center-lite' ), ) // Args
		);
	}
	
	public function widget( $args , $instance ) {
		$instance['title'] = isset($instance['title']) ? $instance['title'] : '';
		$instance['show_news'] = isset($instance['show_news']) ? $instance['show_news'] : 3;
		
		$loop = new WP_Query(array( 'post_type' => 'post', 'showposts' => $instance['show_news'] ,'ignore_sticky_posts' => 1 ));
		
		echo $args['before_widget'];
			
			if($instance['title']):
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			endif;
			
		if( $loop->have_posts() ) : 
			
			while ( $loop->have_posts() ) : $loop->the_post();
				?>
				<div class="media hc_post_area">			
				<aside class="hc_post-date-type">
					<div class="date entry-date updated">
						<div class="day"><?php echo get_the_date('j'); ?></div>
						<div class="month-year"><?php echo get_the_date('M , Y'); ?></div>
					</div>
				</aside>
				<div class="media-body">
					<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
					<p><?php echo custom_excerpt(30,'...'); ?></p>
				</div>
			</div>
				<?php
			endwhile;
			
		endif;
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		$instance['title'] = isset($instance['title']) ? $instance['title'] : '';
		$instance['show_news'] = isset($instance['show_news']) ? $instance['show_news'] : 3;
	?>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','health-center-lite' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'show_news' ); ?>"><?php _e( 'Number of news to show','health-center-lite' ); ?></label> 
		<input size="3" maxlength="2" id="<?php echo $this->get_field_id( 'show_news' ); ?>" name="<?php echo $this->get_field_name( 'show_news' ); ?>" type="number" value="<?php echo $instance['show_news']; ?>" />
	</p>
		
	<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
		$instance['show_news'] = ( ! empty( $new_instance['show_news'] ) ) ? $new_instance['show_news'] : '';
		
		return $instance;
	}
}