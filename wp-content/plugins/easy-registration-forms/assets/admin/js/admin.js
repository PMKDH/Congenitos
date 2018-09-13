jQuery(document).ready(function(){
    $= jQuery;
    
    var show_progress= function(state){
        if(state)
        {
            $(".erf-ajax-progress").show();
        }
        else
        {
             $(".erf-ajax-progress").hide();
        }
    }
    
    /*
     * 
     * @params {string,string} form_name,form_type (Registration or Contact Form)
     * Send ajax request to create new form. 
     * Used actions: erf_new_form
     */
    var create_form= function(form_name,form_type){
        if(form_name=="")
            return;
        
        form_type = form_type || 'reg';

        var request_data= { 
                            action: 'erf_new_form',
                            title : form_name,
                            form_type: form_type
                          };
        show_progress(true);                  
        $.post(ajaxurl,request_data,function(response){
              if(response.success)
              {
                  window.location= response.data.redirect;
              }
              else
              {
                  $("#erf_overview_add_form_response").html("Something went wrong.");
              }
        }).complete(function(){
            show_progress(false);
        });
    };
   
    /*
     * Used for following HTML files: admin/overview/html/overview.php
     */ 
    $("#erf_overview_add_form").click(function(){
        $( "#erf_overview_add_form_dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Add": function() {
              var form_name= $("#erf_overview_input_form_name").val(); 
              var form_type= $('input[name=erf_overview_input_form_type]:checked').val();
              create_form(form_name,form_type);
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
        });
    });
    
    /*
     * Used on individual submission page for adding note
     */
    $('#erf_submission_add_note').click(function(){
        $( "#erf_submission_add_note_dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Add": function() {
                var note= $("#erf_submission_note_text").val();
                if(note){
                   $(this).children('form').submit();
                }
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
        });
    });
    
    /*
     * Used on individual submission page for adding note
     */
    $('#erf_submission_add_note').click(function(){
        $( "#erf_submission_add_note_dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Add": function() {
                var note= $("#erf_submission_note_text").val();
                if(note){
                   $(this).children('form').submit();
                }
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
        });
    });
    
   
    /*
     * Used on Settings page
     */
    $('#erf_submission_print').click(function(){
        var clone = $('.erf-wrapper').clone();
        $('body').after(clone);
        window.print();
        $('body + .erf-wrapper').remove();
    });
    
    $("#erf_configuration_form").submit(function(){
    });

    // Show hide child parent options
    $('.erf-child-rows').slideUp();
    $(".erf-has-child input").each(function(){
            $(this).change(function(){
                var targetElement= $(this);
                var rowContainer= targetElement.closest('.erf-row');
                var index= targetElement.data('child-index');
                if(!index)
                    index=0;
                
                if(targetElement.data('has-child')!=1){
                    rowContainer.next('.erf-child-rows').slideUp(); 
                }
                
                
                
                if(targetElement.is(':checked')){
                    rowContainer.nextUntil('.erf-row').slideUp();
                    if(index!=-1)
                    rowContainer.nextAll().eq(index).slideDown();
                    return;
                }
                else
                {   
                    rowContainer.nextAll().eq(index).slideUp();
                    return;
                }
                
            });
        });
        
    $(".erf-has-child input").each(function(){
            $(this).trigger('change');
    });
    
    if($('#erf_configure_limit_by_date').length>0){
    $('#erf_configure_limit_by_date').datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date()});
    }
    
    
    $("#form-code").click(function(){
        $(this).focus();
        $(this).select();        
        document.execCommand('copy');
        $('#copy-message').fadeIn('slow').delay('200').fadeOut('slow');
        
    });
    $(".erf-shortcode").click(function(){
        $(this).focus();
        $(this).select();        
        document.execCommand('copy');
        $(this).siblings('.copy-message').fadeIn('slow').delay('200').fadeOut('slow');
        
    });
    
//    $( ".erf-card-wrap" ).tooltip({
//        track: true
//    });
    /*
     * Used for following HTML files: admin/submission/html/payment-part.php
     */ 
    $("#erf_payment_change_status").click(function(){
        $( "#erf_payment_status_dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Add": function() {
                   $(this).children('form').submit();
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
        });
    });
    
 
});

function erf_overview_delete_form(url){
    $( "#erf_overview_delete_form_dialog" ).dialog({
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Confirm": function() {
                window.location= url;
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
     });
}




