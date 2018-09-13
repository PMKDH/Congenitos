<?php
//Project Sidebar
	register_sidebar( array(
			'name' => __('Homepage project section - sidebar', 'medical-treatment' ),
			'id' => 'medical-treatment-sidebar-project',
			'description' => __('Use the widget WBR: HC Page Widget to add project type content','medical-treatment'),
			'before_widget' => '<div id="%1$s" class="col-md-4 widget %1$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

?>