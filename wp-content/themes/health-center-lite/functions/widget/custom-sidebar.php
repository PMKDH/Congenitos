<?php	
add_action( 'widgets_init', 'webriti_widgets_init');
function webriti_widgets_init() {
	
	/*sidebar*/
	register_sidebar( array(
		'name' => __( 'Sidebar widget area', 'health-center-lite' ),
		'id' => 'sidebar-primary',
		'description' => __( 'Sidebar widget area', 'health-center-lite' ),
		'before_widget' => '<div id="%1$s" class="widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
	/* Top header widget area */
	register_sidebar( array(
		'name' => __( 'Top header sidebar', 'health-center-lite' ),
		'id' => 'sidebar-header',
		'description' => __( 'Top header sidebar', 'health-center-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// Service Widget Sidebar	
	register_sidebar( array(
		'name' => __( 'Homepage service section - sidebar', 'health-center-lite' ),
		'id' => 'sidebar-service',
		'description' => __('Use the widget WBR: Page Widget to add service type content','health-center-lite'),
		'before_widget' => '<div id="%1$s" class="col-md-3 hc_service_area widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	//Project Sidebar
	register_sidebar( array(
			'name' => __('Homepage project section - sidebar', 'health-center-lite' ),
			'id' => 'sidebar-project',
			'description' => __('Use the widget WBR: Page Widget to add project type content','health-center-lite'),
			'before_widget' => '<div id="%1$s" class="col-md-3 widget %1$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
	// Home Page Latest news Sidebar
	register_sidebar( array(
		'name' => __('Homepage latest news section - sidebar', 'health-center-lite' ),
		'id' => 'sidebar-news',
		'description' => __('Homepage latest news section - sidebar','health-center-lite'),
		'before_widget' => '<div id="%1$s" class="hc_post_section widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="hc_heading_title"><h3 class="widget-title">',
		'after_title' => '</h3></div>',
	) );
	
	
	// Home Page Faqs Sidebar
	register_sidebar( array(
		'name' => __('Homepage faq section - sidebar', 'health-center-lite' ),
		'id' => 'sidebar-faq',
		'description' => __('Faq widget area','health-center-lite'),
		'before_widget' => '<div id="%1$s" class="widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="hc_heading_title"><h3 class="widget-title">',
		'after_title' => '</h3></div>',
	) );
	
	//Footer Widget
	register_sidebar( array(
		'name' => __('Footer widget area', 'health-center-lite' ),
		'id' => 'footer-widget-area',
		'description' => __('Footer widget area', 'health-center-lite' ),
		'before_widget' => '<div id="%1$s" class="col-md-3 hc_footer_widget_column widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	
	//Footer Right Sidebar
	register_sidebar( array(
		'name' => __('Footer right section area', 'health-center-lite' ),
		'id' => 'footer-right-section',
		'description' => __('Footer right section area', 'health-center-lite' ),
		'before_widget' => '<div id="%1$s" class="widget %1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}