jQuery(document).ready(function() {

	/* If there are required actions, add an icon with the number of required actions in the About health page -> Actions required tab */
    var health_nr_actions_required = healthLiteWelcomeScreenObject.nr_actions_required;

    if ( (typeof health_nr_actions_required !== 'undefined') && (health_nr_actions_required != '0') ) {
        jQuery('li.health-w-red-tab a').append('<span class="health-actions-count">' + health_nr_actions_required + '</span>');
    }

    /* Dismiss required actions */
    jQuery(".health-dismiss-required-action").click(function(){

        var id= jQuery(this).attr('id');
        console.log(id);
        jQuery.ajax({
            type       : "GET",
            data       : { action: 'health_dismiss_required_action',dismiss_id : id },
            dataType   : "html",
            url        : healthLiteWelcomeScreenObject.ajaxurl,
            beforeSend : function(data,settings){
				jQuery('.health-tab-pane#actions_required h1').append('<div id="temp_load" style="text-align:center"><img src="' + healthLiteWelcomeScreenObject.template_directory + '/inc/health-info/img/ajax-loader.gif" /></div>');
            },
            success    : function(data){
				jQuery("#temp_load").remove(); /* Remove loading gif */
                jQuery('#'+ data).parent().remove(); /* Remove required action box */

                var health_lite_actions_count = jQuery('.health-actions-count').text(); /* Decrease or remove the counter for required actions */
                if( typeof health_lite_actions_count !== 'undefined' ) {
                    if( health_lite_actions_count == '1' ) {
                        jQuery('.health-actions-count').remove();
                        jQuery('.health-tab-pane#actions_required').append('<p>' + healthLiteWelcomeScreenObject.no_required_actions_text + '</p>');
                    }
                    else {
                        jQuery('.health-actions-count').text(parseInt(health_lite_actions_count) - 1);
                    }
                }
            },
            error     : function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    });

	/* Tabs in welcome page */
	function health_welcome_page_tabs(event) {
		jQuery(event).parent().addClass("active");
        jQuery(event).parent().siblings().removeClass("active");
        var tab = jQuery(event).attr("href");
        jQuery(".health-tab-pane").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
	}

	var health_actions_anchor = location.hash;

	if( (typeof health_actions_anchor !== 'undefined') && (health_actions_anchor != '') ) {
		health_welcome_page_tabs('a[href="'+ health_actions_anchor +'"]');
	}

    jQuery(".health-nav-tabs a").click(function(event) {
        event.preventDefault();
		health_welcome_page_tabs(this);
    });

		/* Tab Content height matches admin menu height for scrolling purpouses */
	 $tab = jQuery('.health-tab-content > div');
	 $admin_menu_height = jQuery('#adminmenu').height();
	 if( (typeof $tab !== 'undefined') && (typeof $admin_menu_height !== 'undefined') )
	 {
		 $newheight = $admin_menu_height - 180;
		 $tab.css('min-height',$newheight);
	 }

});
