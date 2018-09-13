	<?php 
		/**
		Template Name: Business Template
		*/
		get_header();
		//****** get index static banner  ********
		get_template_part('index', 'slider');
		?>
		<div class="row"><div class="hc_home_border"></div></div>
		<?php 
		/****** get index service  ********/
		get_template_part('index', 'services') ;
	
		/****** get index Projects  ********/
		get_template_part('index', 'projects') ;
	
		/****** get index News  ********/
		get_template_part('index', 'news') ;
		
		/**** get Footer callourt *****/
		get_template_part('index','calloutarea');
		
		get_footer();
	?>
		