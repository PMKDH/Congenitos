<?php
/*---------------------------------------------------------------------------------*
 * @file           theme_stup_data.php
 * @package        Health-Center
 * @copyright      2013 webriti
 * @license        license.txt
 * @author       :	webriti
 * @filesource     wp-content/themes/health=center/theme_setup_data.php
 *	Admin  & front end defualt data file 
 *-----------------------------------------------------------------------------------*/ 
function theme_data_setup()
{	
	return $health_center_theme_options=array(
			
			
			//Callout  
			'call_out_text'=>'',
			'call_out_button_text'=>'',
			'call_out_button_link'=>'#',
			'call_out_button_link_target' =>true,			
			
			
			//Slide 	
			'home_slider_enabled'=>false,
			'slider_post' => '',
			
			
			//Page and Sections Headings
			'hc_head_news' =>'',
			
			//New Data
			'slider_category' => '',
			
			//Service
			'service_enable' => false,
			'service_title'=> '',
			'service_description' => '',
			'service_column_layout'=> 4,
			
			//portfolio / project
			'project_enable' => false,
			'portfolio_title' =>'',
			'portfolio_description' =>'',
			
			//News
			'news_enable' => false,
			
			
			
			//Footer callout
			'callout_enable' => false,
			'site_intro_tex'=>'',
			'call_now_text' =>'',
			'call_now_number' =>'',
			
			//Footer copyright
			'footer_copyright' => '',
			
			//Logo and Fevicon header			
			'upload_image_logo'=>'',
			'height'=>'50',
			'width'=>'150',
			'hc_texttitle'=>'on',
			'home_page_image'=> '',
			
			//'home_service_enabled' => 'on',
			'service_title'=> '',
			'service_description' =>'',
			'service_one_icon'=>'',
			'service_one_title'=>'',
			'service_one_description' => '',
			'home_service_one_link' => '',
			'home_service_one_link_target' => "on",
			
			'service_two_icon'=>'',
			'service_two_title'=> '',
			'service_two_description' => '',
			'home_service_two_link' => '',
			'home_service_two_link_target' => "on",
			
			'service_third_icon'=>'',
			'service_third_title'=>'',
			'service_third_description' => '',
			'home_service_third_link' => '',
			'home_service_third_link_target' => "on",
			
			'service_four_icon'=>'',
			'service_four_title'=> '',
			'service_four_description' =>'',
			'home_service_fourth_link' => '',
			'home_service_fourth_link_target' => "on",
			//Projects Sections
			'home_projects_enabled' => '',
			'project_heading_one' => '',
			'project_tagline' => '',
			'project_one_thumb' => '',
			'project_one_title' => '',
			'project_one_text' => '',
			'home_image_one_link'=>'',
			'home_image_one_link_target'=>'',
		
		    'project_two_thumb' => '',
			'project_two_title' => '',
			'project_two_text' => '',
			'home_image_two_link'=>'',
			'home_image_two_link_target'=>'',
			
			'project_three_thumb' => '',
			'project_three_title' => '',
			'project_three_text' => '',
			'home_image_three_link'=>'',
			'home_image_three_link_target'=>"on",
			
			'project_four_thumb' => '',
			'project_four_title' => '',
			'project_four_text' => '',
			'home_image_four_link'=>'',
			'home_image_four_link_target'=>'',
			'head_news' => '',
			
			'webrit_custom_css'=>'',			
			'footer_customizations' => '',
			'created_by_text' => '',
			'created_by_webriti_text' => '',
			'created_by_link' => '',
			
			//Faq Section
			'hc_head_faq' => '',
			'faq_enable' => false,
		);
}
?>