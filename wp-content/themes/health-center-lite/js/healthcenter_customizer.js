jQuery(document).ready(function() {
	
	/* service panel */
	wp.customize.section( 'sidebar-widgets-sidebar-service' ).panel( 'service_panel' );
	wp.customize.section( 'sidebar-widgets-sidebar-service' ).priority( '3' );
	
	/* Project panel */
	wp.customize.section( 'sidebar-widgets-sidebar-project' ).panel( 'project_panel' );
	wp.customize.section( 'sidebar-widgets-sidebar-project' ).priority( '3' );
	
	/* Recent News Panel */
	wp.customize.section( 'sidebar-widgets-sidebar-news' ).panel( 'news_panel' );
	wp.customize.section( 'sidebar-widgets-sidebar-news' ).priority( '2' );
	
	/* FAQ panel */
	wp.customize.section( 'sidebar-widgets-sidebar-faq' ).panel( 'faq_panel' );
	wp.customize.section( 'sidebar-widgets-sidebar-faq' ).priority( '3' );
	
	/* Footer right section  */
	wp.customize.section( 'sidebar-widgets-footer-right-section' ).panel( 'copyright_panel' );
	wp.customize.section( 'sidebar-widgets-footer-right-section' ).priority( '2' );
	
});