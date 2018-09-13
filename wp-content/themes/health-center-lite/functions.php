<?php /**Includes reqired resources here**/
	define('WEBRITI_TEMPLATE_DIR_URI',get_template_directory_uri());	
	define('WEBRITI_TEMPLATE_DIR',get_template_directory());
	define('WEBRITI_THEME_FUNCTIONS_PATH',WEBRITI_TEMPLATE_DIR.'/functions');	
	
	require_once('theme_setup_data.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/menu/default_menu_walker.php' ); // for Default Menus
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/menu/webriti_nav_walker.php' ); // for Custom Menus	
	
	require_once( WEBRITI_THEME_FUNCTIONS_PATH . '/scripts/scripts.php' ); // all js and css file for health-center	
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/commentbox/comment-function.php' ); //for comments
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/widget/custom-sidebar.php' ); //for widget register
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/font/font.php'); //Google Font
	
	// Health Info Page
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/health-info/welcome-screen.php');
	
	//Feature Page Widget
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/widget/wbr-register-page-widget.php' );
	
	//Latest news Widget
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/widget/wbr-resent-news-widget.php' );
	
	
	//Customizer
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/customizer_header.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/customizer_copyright.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/slider-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/service-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/project-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/faq-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/recent_news-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/callout-panel.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/customizer-emailcourse.php');
	require( WEBRITI_THEME_FUNCTIONS_PATH . '/customizer/customizer-pro.php');
	
	//content width
   	if ( ! isset( $content_width ) ) $content_width = 900;		
   	//wp title tag starts here
   	function hc_head( $title, $sep )
   	{	global $paged, $page;		
   		if ( is_feed() )
   			return $title;
   		// Add the site name.
   		$title .= get_bloginfo( 'name' );
   		// Add the site description for the home/front page.
   		$site_description = get_bloginfo( 'description' );
   		if ( $site_description && ( is_home() || is_front_page() ) )
   			$title = "$title $sep $site_description";
   		// Add a page number if necessary.
   		if ( $paged >= 2 || $page >= 2 )
   			$title = "$title $sep " . sprintf( _e( 'Page', 'health-center-lite' ), max( $paged, $page ) );
   		return $title;
   	}	
   	add_filter( 'wp_title', 'hc_head', 10,2 );
	
	add_action( 'after_setup_theme', 'hc_setup' ); 	
	function hc_setup()
	{	// Load text domain for translation-ready
   		load_theme_textdomain( 'health-center-lite', WEBRITI_THEME_FUNCTIONS_PATH . '/lang' );
		
		
		add_theme_support( 'post-thumbnails' ); //supports featured image
   		// This theme uses wp_nav_menu() in one location.
   		register_nav_menu( 'primary', __( 'Primary Menu', 'health-center-lite' ) );
   		// theme support 	
   		$args = array('default-color' => '000000',);
   		add_theme_support( 'custom-background', $args  ); 
   		add_theme_support( 'automatic-feed-links');
		add_theme_support('title-tag');
		//Custom logo
		add_image_size('hc-logo', 150, 50);
		add_theme_support('custom-logo', array('size' => 'hc-logo'));
		
		require_once('theme_setup_data.php');
   		function hc_custom_excerpt_length( $length ) {	return 50; }
   		add_filter( 'excerpt_length', 'hc_custom_excerpt_length', 999 );
   		
   		function hc_new_excerpt_more( $more ) {	return '';}
   		add_filter('excerpt_more', 'hc_new_excerpt_more');	
	}
	
	// Read more tag to formatting in blog page 
   	function hc_content_more($more)
   	{  global $post;
   	   return ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"hc_blog_btn\">Read More<i class='fa fa-long-arrow-right'></i></a>";
   	}   
   	add_filter( 'the_content_more_link', 'hc_content_more' );
	
	
	function custom_excerpt($new_length = 20, $new_more = '...' , $from = 'all' ) {
	
  add_filter('excerpt_length', function () use ($new_length) {
    return $new_length;
  }, 999);
  
  add_filter('excerpt_more', function () use ($new_more) {
    return $new_more;
  });
  
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  echo $output;
}

add_action( 'init', 'add_excerpts_to_pages' );
function add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}
?>