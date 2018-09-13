function erf_util_redirect(url, timer) {
    var timer = timer || 2000;
    if (erf_util_is_url(url)) {
        setTimeout(function () {
            window.location = url;
        }, timer);
    }
}

function erf_util_is_url(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    return pattern.test(str);
}


function erf_update_url_query(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}

function erf_handle_form_ajax_response(response,selector,form) {
    var formParentBlock= selector || jQuery(form).closest('.erf-container');
    if(formParentBlock.find('.erf-edit-submission-form .erf-reg-form-container').length>0){ // Check for edit submission pop up
       formParentBlock=  formParentBlock.find('.erf-edit-submission-form .erf-reg-form-container');
    }
        
    if (response.success)
    { 
        /* Remove Form HTML and place Thank You message */
        if (response.msg)
        {   
            jQuery('html, body').animate({
                    scrollTop: formParentBlock.offset().top
            }, 300);
            jQuery(form).remove();
            formParentBlock.html(response.msg);
        }
        
        /* Handling redirection */
        if (response.redirect_to)
        {
            if(response.msg==""){
                erf_util_redirect(response.redirect_to,0);
            }
            else
                erf_util_redirect(response.redirect_to);
            return;
        }
        
        /* Handling page reload */
        if (response.reload && response.form_id) {
            var currentUrl = document.location.href;
            currentUrl = erf_update_url_query(currentUrl, 'erf_form', response.form_id);
            document.location.href = erf_update_url_query(currentUrl, 'erf_auto_login', 1);
            return;
        }
        // Create the event.
        var formSubmitEvent = document.createEvent('Event');
        
        // Define that the event name is 'erforms_submit_response'.
        formSubmitEvent.initEvent('erforms_submit_response', true, true);
        formSubmitEvent.detail= {'formParent': formParentBlock,'response':response};
        document.dispatchEvent(formSubmitEvent);
    } else
    {
        var data = response.data;

        for (i = 0; i < data.length; i++) {
            if (data[i][0]) {
                var fieldElement = jQuery(form).find('input[name=' + data[i][0] + ']');
                if (fieldElement.length > 0) {
                    fieldElement.after('<div class="erf-field-error">' + data[i][1] + '</div>');
                    var parsleyGroup = fieldElement.data('parsley-group');

                    if (parsleyGroup.length > 0) {
                        var errorPageIndex = parseInt(parsleyGroup.replace('block-', ''));
                        if (!fieldElement.is(':visible')) {
                            jQuery(form).find('[page-index=' + errorPageIndex + ']').addClass('erf-has-errors');
                        }
                        var form_error_container= jQuery(form).find('.erf-errors');
                        form_error_container.append("<div class='erf-error-row'>" + data[i][1] + "</div>");
                        jQuery('html, body').animate({
                            scrollTop: form_error_container.offset().top
                        }, 500);
                    }
                } else{
                    var form_error_container= jQuery(form).find('.erf-errors');
                    form_error_container.append("<div class='erf-error-row'>" + data[i][1] + "</div>");
                    jQuery('html, body').animate({
                        scrollTop: form_error_container.offset().top
                    }, 500);
                }
            }

        }
    }
}