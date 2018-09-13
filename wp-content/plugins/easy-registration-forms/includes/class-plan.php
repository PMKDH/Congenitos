<?php
/**
 * Main form handler
 *
 * Contains a bunch of helper methods as well.
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
 */
class ERForms_Plan extends ERForms_Post
{
        protected $post_type='erforms_plan';
	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() { 
		// Register erforms_plan custom post type
		$this->register_cpt();
	}

	/**
	 * Registers the custom post type to be used for forms.
	 *
	 * @since 1.0.0
	 */
	public function register_cpt() {
                // Custom post type arguments, which can be filtered if needed
		$args = apply_filters(
			'erforms_post_type_args',
			array(
				'labels'              => array(),
				'public'              => false,
				'exclude_from_search' => true,
				'show_ui'             => false,
				'show_in_admin_bar'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'can_export'          => false,
				'supports'            => array( 'title' ),
			)
		);

		// Register the post type for Plan
		register_post_type( 'erforms_plan', $args );
	}
        
        /*
         * Returns Plan
         * Accepts WP_Post or post_id
         */
        public function get_plan($post){
            if(!($post instanceof WP_Post) && $post>0){
                $post = $this->get($post);
                if(empty($post))
                  $post=0;
            }
            
            $meta_keys= $this->meta_keys();
            $plan= array();
            if(empty($post)){  // Returning mock plan array
                $plan['id']=0;
                $plan['name']='';
                foreach($meta_keys as $key){
                   $plan[$key]= '';
                }
                return $plan;
            }            
            
            $plan['id']= $post->ID;
            $plan['name']= $post->post_title;
            $all_meta= $this->get_meta($post->ID); // Fetch all meta keys
            foreach($all_meta as $key=>$meta){
                $key= str_replace('erform_', '', $key);
                if(in_array($key, $meta_keys)){
                    $plan[$key]= $meta[0];
                }
            }
            $plan['created_date']= get_the_date('',$post->ID);
            return $plan;
        }
        
        public function meta_keys(){
            return array('price','type');
        }
        
        /*
         * Accepts Plan array
         */
        public function update_plan($plan){
           if(empty($plan))
                return false;
            
            $meta_keys= $this->meta_keys();
            $meta_input= array();
            foreach($meta_keys as $key){
               $meta_input['erform_'.$key]= $plan[$key];
            }
             
            // Update post
            $post = array(
                'ID'           => $plan['id'],
                'post_title'   => $plan['name'],
                'meta_input' => $meta_input
            );
            wp_update_post( $post );
       }
       
       /*
         * Accepts Plan array
         */
        public function add_plan($plan){
           if(empty($plan))
                return false;
            
            $meta_keys= $this->meta_keys();
            $meta_input= array();
            foreach($meta_keys as $key){
               $meta_input['erform_'.$key]= $plan[$key];
            }
             
            // Update post
            $post = array(
                'ID'           => $plan['id'],
                'post_type'    => $this->post_type,
                'post_status'  => 'publish',
                'post_title'   => $plan['name'],
                'meta_input' => $meta_input
            );
            return wp_insert_post($post);
       }
       
       public function get_plans_dropdown($args,$type='fixed'){
           $meta_query= array(
                        array(
                            'key' => 'erform_type',
                            'value' => $type,
                            'compare' => '='
                        ));
           $posts = $this->get('',array('meta_query'=>$meta_query));
           $multiple='';
           $dropdown= '<select>';
            if(isset($args['name'])){
               $id= str_replace('[]', '', $args['name']); 
               if(isset($args['multiple']))
                   $multiple='multiple';
               $dropdown= '<select name="'.$args['name'].'" id="'.$id.'" '.$multiple.'>'; 
            }
            
            if(isset($args['default'])){
                $dropdown .= '<option value="">'.$args['default'].'</option>';
            }
            if(!empty($posts) && is_array($posts)){
                foreach($posts as $post){
                    if(isset($args['selected'])){
                        if($args['selected']==$post->ID)
                            $dropdown .= '<option selected value="'.$post->ID.'">'.$post->post_title.'</option>';
                        elseif(is_array($args['selected']) && in_array($post->ID,$args['selected'])){
                            $dropdown .= '<option selected value="'.$post->ID.'">'.$post->post_title.'</option>';
                        }
                        else
                            $dropdown .= '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
                    }
                    else
                    $dropdown .= '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
                }
            }
            $dropdown .= '</select>';
            return $dropdown;
           
       }
       
       public function get_plans(){
           $posts= $this->get();
           $plans= array();
           foreach($posts as $post){
               $plans[]= $this->get_plan($post);
           }
           
           return $plans;
       }
       
       public function get_plans_by_type($type){
           $plans= $this->get_plans();
           $result= array();
           foreach($plans as $plan){
               if($plan['type']==$type){
                   array_push($result,$plan);
               }
           }
           return $result;
       }
}
