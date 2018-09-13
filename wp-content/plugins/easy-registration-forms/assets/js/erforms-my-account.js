(function ($) {
    $(document).ready(function () {
        var windowWidth = $(window).width();
        var myAccountDiv = $('.erf-my-account');
        var autoResize = function () {
            if (windowWidth > 1024) {
                if (myAccountDiv.innerWidth() < 700) {
                    myAccountDiv.closest('.erf-container').addClass('erf-small-inner');
                }
            }
        };
        autoResize();
        $(window).resize(function () {
            autoResize();
        });

        
        
        $('.erf-load-submission-row').click(function () {
            var submissionID = $(this).data('submission-id');
            var formID = $(this).data('form-id');
            var submissionContainer = $(this).siblings('.erf-modal');
            var contentContainer= submissionContainer.find('.erf-modal-body');
            submissionContainer.toggle();
            contentContainer.html(erform_ajax.js_strings.loading_submission_info)
            // Fetch updated submission info.
            $.post(erform_ajax.url, {form_id: formID, submission_id: submissionID, action: 'erforms_get_submission_html'}, function (res) {
                if (res.success) {
                    if (res.data.hasOwnProperty('html')) {
                        contentContainer.html(res.data.html);
                    }
                }
            }).fail(function (xhr, textStatus, e) {
                contentContainer.html(erform_ajax.js_strings.edit_form_load_error);
            });
        });
        
        var registerCloseModal= function(){
            $('.erf-modal-close').click(function () {
                $(this).closest('.erf-modal').hide();
            });
        }
        registerCloseModal();
        // Edit submission                
        $('.erf-edit-submission-row').click(function () {
            var modalContainer = $(this).parent('.erf-my-account-col-edit').find('.erf-modal');
            var submissionContainer = modalContainer.find('.erf-modal-body');
            var submissionID = $(this).data('submission-id');
            var formID = $(this).data('form-id');
            modalContainer.show();
            submissionContainer.html(erform_ajax.js_strings.loading_edit_form);
            $.post(erform_ajax.url, {form_id: formID, submission_id: submissionID, action: 'erforms_get_form_for_edit'}, function (res) {
                if (res.success) {
                    if (res.data.hasOwnProperty('form_html')) {
                        submissionContainer.html(res.data.form_html);
                        $(document).trigger('erf_process_form', [modalContainer]);
                    }
                }
                else{
                    if(res.data.hasOwnProperty('error')){
                         submissionContainer.html(res.data.error);
                    }
                }
                registerCloseModal();
            }).fail(function (xhr, textStatus, e) {
                submissionContainer.html(erform_ajax.js_strings.edit_form_load_error);
            });
        });
        
        // Delete submission
        $('.erf-delete-submission-row').click(function () {
            var submissionID = $(this).data('submission-id');
            var formID = $(this).data('form-id');
            var row= $(this).closest('.erf-my-account-details');
            $.post(erform_ajax.url, {form_id: formID, submission_id: submissionID, action: 'erforms_delete_submission'}, function (res) {
                if (res.success) {
                   row.remove();
                }
                else{
                    if(res.data.hasOwnProperty('msg')){
                         alert(res.data.msg);
                    }
                }
            }).fail(function (xhr, textStatus, e) {
                alert('Unable to connect to server');
            });
        });
        
        $('.erf-my-account-nav a').click(function () {
            $('.erf-my-account-nav a').parent().removeClass('erf-my-account-navigation-link-active');
            $(this).parent().addClass('erf-my-account-navigation-link-active');
            var tagid = $(this).data('tag');
            $('.erf-my-account-profile-tab').removeClass('active').addClass('erf-hidden');
            $('#' + tagid).addClass('active').removeClass('erf-hidden');
        });

        window.Parsley.addValidator('confirmPassword', {
            validateString: function (value, passwordFieldId) {
                return $("#" + passwordFieldId).val() == value;
            },
            messages: {
                en: erform_ajax.parsley_strings.confirmPassword,
            }
        });


        $(".erf-change-password").submit(function(event){
            var form = $(this);
            var formContainer=$(form).closest('.erf-my-account-profile-tab');
            var formInstance = form.parsley();
            var formData = new FormData(form[0]);
            var errorContainer= formContainer.find('.erf-errors');
            errorContainer.html('');
            form.find('button[type="submit"]').append('<span class="erf-loader"></span>');
            
            $.ajax({
                url: erform_ajax.url,
                type: 'POST',
                data: formData,
                async: true,
                success: function (response) {
                         if(response.success){
                             var message= response.data.msg;
                             form.find('button[type="submit"] .erf-loader').remove();
                             formContainer.html(message);
                             
                         }
                         else
                         {  
                            form.find('button[type="submit"] .erf-loader').remove();
                            if(response.data.hasOwnProperty('errors')){
                                for(var i=0;i<response.data.errors.length;i++){
                                    errorContainer.append('<div class="erf-error-row">' + response.data.errors[i] + '</div><br>');
                                }
                            }
                             
                         }
                },
                cache: false,
                contentType: false,
                processData: false
            });
            event.preventDefault();
        });
        
        $('.erf-password').password({
            shortPass: erform_ajax.js_strings.shortPass,
            badPass: erform_ajax.js_strings.badPass,
            goodPass: erform_ajax.js_strings.goodPass,
            strongPass: erform_ajax.js_strings.strongPass,
            enterPass: 'Type your password',
            showPercent: false,
            showText: true, // shows the text tips
            animate: false, // whether or not to animate the progress bar on input blur/focus
            animateSpeed: 'fast', // the above animation speed
            username: false, // select the username field (selector or jQuery instance) for better password checks
            usernamePartialMatch: false, // whether to check for username partials
            minimumLength: 5 // minimum password length (below this threshold, the score is 0)
        });

    });
    
    
    
    $(window).bind("load", function() {
        var currentURL = $(location).attr('href');
        if(currentURL.indexOf('erf_paged') != -1){
            $('a[data-tag="submissions"]').trigger( "click" );
        }
    });

})(jQuery);

function erfPrintSubmission(obj){
  jQuery(obj).closest('.erf-submission-info').find('.erf-modal-body').printThis();
}

