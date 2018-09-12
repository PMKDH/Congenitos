<?php
if ( ! function_exists( 'villagio_mphb_sc_services_service_read_more' ) ) :
	/**
	 * Displays read more btn mphb_sc_services_service
	 */
	function villagio_mphb_sc_services_service_read_more() {
		echo '<p class="more-link"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="button">' . esc_html__( 'Read more', 'villagio' ) . '</a></p>';
	}


endif;

add_action( 'mphb_sc_services_service_details', 'villagio_mphb_sc_services_service_read_more', 70 );

if ( ! function_exists( 'villagio_mphb_sc_services_service_read_more' ) ) :
	/**
	 * Displays read more btn mphb_sc_services_service
	 */
	function villagio_mphb_sc_services_service_read_more() {
		echo '<p class="more-link"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="button">' . esc_html__( 'Read more', 'villagio' ) . '</a></p>';
	}


endif;

add_action( 'mphb_sc_services_service_details', 'villagio_mphb_sc_services_service_read_more', 70 );


if ( ! function_exists( 'villagio_mphb_loop_room_type_categories' ) ) :
	/**
	 * Displays mphb_room_type_category
	 */
	function villagio_mphb_loop_room_type_categories() {
		global $post;
		echo get_the_term_list( $post->ID, 'mphb_room_type_category', '<span class="mphb_room_type_categories_header_wrapper">', ' ', '</span>' );
	}


endif;
if ( ! function_exists( 'villagio_mphb_render_loop_room_type_before_featured_image' ) ) :
	/**
	 * Displays mphb_render_loop_room_type_before_featured_image
	 */
	function villagio_mphb_render_loop_room_type_before_featured_image() {
		echo '<span class="mphb_room_type_featured_image_wrapper">';
	}


endif;
if ( ! function_exists( 'villagio_mphb_render_loop_room_type_after_featured_image' ) ) :
	/**
	 * Displays mphb_render_loop_room_type_after_featured_image
	 */
	function villagio_mphb_render_loop_room_type_after_featured_image() {
		villagio_mphb_loop_room_type_categories();
		echo '</span><!-- .mphb_room_type_featured_image_wrapper -->';
	}


endif;


add_action( 'mphb_render_loop_room_type_before_featured_image', 'villagio_mphb_render_loop_room_type_before_featured_image', 10 );

add_action( 'mphb_render_loop_room_type_after_featured_image', 'villagio_mphb_render_loop_room_type_after_featured_image', 0 );

add_action( 'mphb_loop_room_type_gallery_main_slider_flexslider_before', 'villagio_mphb_render_loop_room_type_before_featured_image', 10 );

add_action( 'mphb_loop_room_type_gallery_main_slider_flexslider_after', 'villagio_mphb_render_loop_room_type_after_featured_image', 10 );


if ( ! function_exists( 'villagio_mphb_widget_rooms_item_top' ) ) :
	/**
	 * Displays villagio_mphb_widget_rooms_item_top
	 */
	function villagio_mphb_widget_rooms_item_top() {
		global $post;
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'villagio-thumb-large' );
		echo '<div class="mphb_room_type_bg" style="background-image: url(' . esc_url( $thumb['0'] ) . ')"><div class="mphb_room_type_bg_wrapper"><div><div class="wrapper">';
		villagio_mphb_loop_room_type_categories();
	}


endif;
if ( ! function_exists( 'villagio_mphb_widget_rooms_item_bottom' ) ) :
	/**
	 * Displays villagio_mphb_widget_rooms_item_bottom
	 */
	function villagio_mphb_widget_rooms_item_bottom() {
		echo '</div></div><!-- .wrapper --></div><!-- .mphb_room_type_bg_wrapper --></div><!-- .mphb_room_type_bg -->';
	}


endif;


if ( ! function_exists( 'villagio_mphb_widget_rooms_thumbnail_size' ) ) :
	/**
	 * Widget rooms thumbnail size
	 */
	function villagio_mphb_widget_rooms_thumbnail_size( $size ) {
		if ( mphb_is_search_results_page() ) {
			return $size;
		}
		if ( is_page_template( 'template-wide-screen-page.php' ) ) {
			return 'villagio-thumb-small-x2';
		}
		if ( is_page_template( 'template-full-width-page.php' ) ) {
			return 'villagio-thumb-small-x2';
		}

		return 'villagio-thumb-small';
	}
endif;

if ( ! function_exists( 'villagio_mphb_loop_room_type_gallery_main_slider_image_size' ) ) :
	/**
	 * Gallery image size
	 */
	function villagio_mphb_loop_room_type_gallery_main_slider_image_size( $size ) {
		/*if ( is_page_template( 'template-front-page.php' ) ) {
			return 'villagio-thumb-small';
		}*/
		return 'villagio-thumb-small-x2';
	}
endif;
add_filter( 'mphb_loop_room_type_gallery_main_slider_image_size', 'villagio_mphb_loop_room_type_gallery_main_slider_image_size' );

add_filter( 'mphb_loop_service_thumbnail_size', 'villagio_mphb_widget_rooms_thumbnail_size' );
add_filter( 'mphb_loop_service_thumbnail_size', 'villagio_mphb_widget_rooms_thumbnail_size' );
add_filter( 'mphb_loop_room_type_thumbnail_size', 'villagio_mphb_widget_rooms_thumbnail_size' );
add_action( 'mphb_widget_rooms_item_top', 'villagio_mphb_widget_rooms_item_top', 20 );
add_action( 'mphb_widget_rooms_item_bottom', 'villagio_mphb_widget_rooms_item_bottom', 20 );

add_action( 'mphb_sc_rooms_render_details', array( '\MPHB\Views\LoopRoomTypeView', 'renderAttributes' ), 0 );

// add link to services image
add_action( 'mphb_render_loop_service_before_featured_image', 'villagio_mphb_render_loop_service_before_featured_image' );
function villagio_mphb_render_loop_service_before_featured_image() {
	echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '">';
}

add_action( 'mphb_render_loop_service_after_featured_image', 'villagio_mphb_render_loop_service_after_featured_image' );
function villagio_mphb_render_loop_service_after_featured_image() {
	echo '</a>';
}

// single room-type gallery image size
add_filter( 'mphb_single_room_type_gallery_image_size', 'villagio_mphb_single_room_type_gallery_image_size' );
function villagio_mphb_single_room_type_gallery_image_size() {
	return 'villagio-thumb-small';
}