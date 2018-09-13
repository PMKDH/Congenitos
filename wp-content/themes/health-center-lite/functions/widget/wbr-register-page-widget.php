<?php
/**
 * Feature Page Widget
 *
 */
add_action('widgets_init','wbr_feature_page_widget');
function wbr_feature_page_widget(){
	
	return register_widget('wbr_feature_page_widget');
}

class wbr_feature_page_widget extends WP_Widget{
	
	function __construct() {
		parent::__construct(
			'wbr_feature_page_widget', // Base ID
			__('WBR : Page Widget', 'health-center-lite'), // Name
			array( 'description' => __('Feature page item widget','health-center-lite'), ) // Args
		);
	}
	
	
	public function widget( $args , $instance ) {
		
		echo $args['before_widget'];
			$instance['selected_page'] = (isset($instance['selected_page'])?$instance['selected_page']:'');
			$instance['hide_image'] = (isset($instance['hide_image'])?$instance['hide_image']:'');
			$instance['below_title'] = (isset($instance['below_title'])?$instance['below_title']:'');
			
			// Check for custom link
			
			
			// fetch page object
			$page= get_post($instance['selected_page']); 
			$title = $page->post_title;
			if(($instance['selected_page']) !=null) {
			echo '<div class="service-widget">';
			if($instance['below_title'] != true)
			{
			// Check for displaying feature image
			if($instance['hide_image'] != true):	
			
						$attr = array( 'class' => 'img-responsive' );
						echo get_the_post_thumbnail($page->ID, 'large', $attr);
		
			endif;
			
			    echo '<h2><a>'. $title .'</a></h2>'; 
			
			}
			else
			{
			echo '<h2 class="widget-title">'. $title .'</h2>';
			if($instance['hide_image'] != true):
			$attr = array( 'class' => 'img-responsive' );
						echo get_the_post_thumbnail($page->ID, 'large', $attr);
			endif;
			}
			if($page->post_excerpt) echo '<p>'.$page->post_excerpt. '</p>'; else echo '<p>'.$page->post_content. '</p>'; // check for the page content
			echo '</div>';
			}
			
			
		echo $args['after_widget']; 	
	}
	
	public function form( $instance ) {
		$instance['selected_page'] = isset($instance['selected_page']) ? $instance['selected_page'] : '';
		$instance['hide_image'] = isset($instance['hide_image']) ? $instance['hide_image'] : '';
		$instance['below_title'] = isset($instance['below_title']) ? $instance['below_title'] : '';
		?>
		<label for="<?php echo $this->get_field_id( 'selected_page' ); ?>"><?php _e('Select pages','health-center-lite' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'selected_page' ); ?>" name="<?php echo $this->get_field_name( 'selected_page' ); ?>">
				<option value>--<?php echo 'Select'; ?>--</option>
				<?php
					$selected_page = $instance['selected_page'];
					$pages = get_pages($selected_page); 				
					foreach ( $pages as $page ) {
						$option = '<option value="' . $page->ID . '" ';
						$option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
						$option .= '>';
						$option .= $page->post_title;
						$option .= '</option>';
						echo $option;
					}
				?>
						
			</select>
			<br/>
			
		</p>
		
		<p>
		<input class="checkbox" type="checkbox" <?php if($instance['hide_image']==true){ echo 'checked'; } ?> id="<?php echo $this->get_field_id( 'hide_image' ); ?>" name="<?php echo $this->get_field_name( 'hide_image' ); ?>" /> 
		<label for="<?php echo $this->get_field_id( 'hide_image' ); ?>"><?php _e('Hide featured image','health-center-lite' ); ?></label>
		</p>
		
		<p>
		<input class="checkbox" type="checkbox" <?php if($instance['below_title']==true){ echo 'checked'; } ?> id="<?php echo $this->get_field_id( 'below_title' ); ?>" name="<?php echo $this->get_field_name( 'below_title' ); ?>" /> 
		<label for="<?php echo $this->get_field_id( 'below_title' ); ?>"><?php _e( 'Display image below title','health-center-lite' ); ?></label>
		</p>
		</select>
		
		
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['selected_page'] = ( ! empty( $new_instance['selected_page'] ) ) ? $new_instance['selected_page'] : '';
		$instance['hide_image'] = ( ! empty( $new_instance['hide_image'] ) ) ? $new_instance['hide_image'] : '';
		$instance['below_title'] = ( ! empty( $new_instance['below_title'] ) ) ? $new_instance['below_title'] : '';
		
		return $instance;
	}
}