(function ($) {
    $(document).bind('erf_process_form', function (ev,selector,submission) {
        var erfContainer= selector || $(".erf-container");
        var formContainer = erfContainer.find('.erf-front-form');
        
        if(formContainer.length==0)
            return;
        
        var parsleyConfig = {
            errorsContainer: function (pEle) {
                var $err = pEle.$element.closest('.form-group');
                return $err;
            },
        }
        window.Parsley.addValidator('confirmPassword', {
            validateString: function (value, passwordFieldId) {
                return $("#" + passwordFieldId).val() == value;
            },
            messages: {
                en: erform_ajax.parsley_strings.confirmPassword,
            }
        });

        if (erform_ajax.logged_in == 1) {
            var primaryFields = formContainer.find('[type=user_email],[type=password],[entity-type=user][entity-property=username]');
            primaryFields.val('');
            primaryFields.attr('disabled', true);
            primaryFields.addClass('erf-disabled');
            formContainer.find("[user_roles=true]").attr('disabled', true);
        }

        parsleyConfig.excluded = 'input:hidden,select:hidden,textarea:hidden,file:hidden,:disabled,.erf-disabled';

        var twoColumnLayout = function (form, selector) {
            form.find(selector).each(function () {
                var page = $(this);
                var pageElements = page.children('div');
                var twoColumnWrapper = $('<div class="erf-two-columns" />');
                var row = [];
                for (i = 0; i < pageElements.length; i++) {
                    var field = $(pageElements[i]);
                    var fieldNext = $(pageElements[i + 1]);

                    if (field.hasClass('form-group') && fieldNext.length > 0 && fieldNext.hasClass('form-group')) {
                        pageElements.filter(':eq(' + i + '),:eq(' + (i + 1) + ')').wrapAll(twoColumnWrapper);
                        i++;
                    } else {
                        field.wrap(twoColumnWrapper);
                    }
                }

            });
        }

        var getFormLayout = function (form) {
            var formContainer = form.closest('.erf-container');
            if (formContainer.hasClass('erf-layout-two-column'))
            {
                return 2;
            }
            return 1;

        }


        submit_form = function (form) {
            // Remove all previous error
            $(form).find('[custom-type=page-break]').removeClass('erf-has-errors');

            var formData = new FormData(form);
            $(form).find('.erf-errors').html('');
            var formParentBlock = $(form).closest('.erf-container');
            formParentBlock.find('.erf-field-error').remove();
            var submitButton = $(form).find('.erf-submit-button button');
            if(submitButton.length==0){ // In case of multipage form
                submitButton= $(form).find('.erf-form-nav :submit');
            }
            
            submitButton.append('<span class="erf-loader"></span>');
            submitButton.attr('disabled', true);
            $.ajax({
                url: erform_ajax.url,
                type: 'POST',
                data: formData,
                async: true,
                success: function (response) {
                    submitButton.attr('disabled', false);
                    submitButton.find('.erf-loader').remove();
                    try {
                        erf_handle_form_ajax_response(response,selector, form);
                    } catch (ex) {
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        /*
         * Multipage functionality
         */
        var paginateForm = function (form) {
            var navContainer = form.find('.erf-form-nav'); // It holds previous button
            var pages = []; // Holds all the pages. Note: There are no HTML sections to hold page elements.
            var pageBreaks = []; // Elements which defines page break
            var submitButton = form.find('.erf-submit-button button');
            var submitButtonTitle = submitButton.html();
            var nextButton;
            var prevButton;
            var hideTabs = false;

            // Adds index to each input element so that Parsley can validate them in individual group.
            var addElementGroup = function (formElements, index) {
                formElements.attr('data-parsley-group', 'block-' + index);
                formElements.find(':input').attr('data-parsley-group', 'block-' + index);
            }

            // Ads and registers previous button 
            var addNavButtons = function (index) {
                navContainer.html('');
                // Creating nav buttons
                submitButton.removeClass('erf-nav-next');
                prevButton = submitButton.clone();
                prevButton.attr('type', 'button');
                prevButton.addClass('erf-nav-prev');
                prevButton.html(erform_ajax.js_strings.prev);
                nextButton = submitButton.clone();
                nextButton.html(erform_ajax.js_strings.next);
                nextButton.addClass('erf-nav-next');

                prevButton.click(function () {
                    var selectedIndex = parseInt(navContainer.attr('current-page-index')) - 1;
                    goToPage(selectedIndex);
                });
                if (index > 0)  // Do not add Previous button on first Page
                    navContainer.append(prevButton);
                navContainer.append(nextButton);
                if (index == (pages.length - 1)) {
                    nextButton.html(submitButtonTitle);
                }
                submitButton.remove();
            }

            // Remove Page Break closest DIV
            form.find('div[custom-type=page-break]').unwrap();

            // Check if first element is page Break.
            var firstPageBreak = form.find('.rendered-form div').first().attr('custom-type');
            if (firstPageBreak != 'page-break')  // First element is not page break. 
            {
                form.find('.rendered-form').prepend('<div custom-type="page-break" class="page-break">Page Break</div>');
                hideTabs = true; // Hides tab system for Page breaks
            }

            var pageBreaks = form.find('div[custom-type=page-break]');
            pageBreaks.addClass('page-break');
            navContainer.attr('current-page-index', 0);   // Assigning current page index    
            if(pageBreaks.length>1){
                form.find('.erf-external-form-elements').hide(); // Hiding external form elements for multipage forms
            }
            
            
            var goToPage = function (selectedIndex, next) {
                var currentPageIndex = form.find('.active-page').attr('page-index');
                var next = next || false;
                if (next || selectedIndex < currentPageIndex) {
                    for (i = 0; i < pages.length; i++) {
                        if (selectedIndex == i)
                        {
                            pages[i].show();
                            navContainer.attr('current-page-index', selectedIndex);
                            pageBreaks.removeClass('active-page');
                            $(pageBreaks[selectedIndex]).addClass('active-page');
                            navContainer.html('');
                        } else
                            pages[i].hide();
                    }
                    addNavButtons(selectedIndex);
                }
                
                if((selectedIndex + 1)==pageBreaks.length){
                    form.find('.erf-external-form-elements').show(); // Showing external form elements at last pagination
                }
                else{
                    form.find('.erf-external-form-elements').hide();
                }
                // Scroll on top
                $('html, body').animate({
                    scrollTop: form.closest('.erf-container').offset().top
                }, 300);
            }
            if (hideTabs || pageBreaks.length == 1) {
                pageBreaks.hide();
            }
            if (pageBreaks.length > 1) {
                pageBreaks.each(function (index) {
                    var formElements = $(this).nextUntil('div[custom-type=page-break]');
                    if (formElements.length == 0) { // Inserting blank page
                        formElements = $('<div class="form-group">&nbsp;</div>');
                    }
                    formElements.wrapAll('<div class="erf-page erf-page-' + index + '"></div>');
                    pages.push(formElements);
                    addElementGroup(formElements, index);
                    if (index > 0)
                        formElements.hide(); // Be default hiding all the page elements except initial page
                });
            }


            if (pages.length > 1) {


                form.attr('erf-multipage', 1); // Adding multipage flag

                // By default show first page elements
                pages[0].show();

                // Adding index properties to each page break, Appends page breaks in form starting
                for (i = 0; i < pageBreaks.length; i++) {
                    $(pageBreaks[i]).attr('page-index', i);
                    if (i == 0) {
                        $(pageBreaks[i]).addClass('active-page');
                    }
                    form.find('.rendered-form').before(pageBreaks[i]);
                }
                pageBreaks.wrapAll("<div class='erf-page-breaks'></div>");

                // Registers click event to allow jumping to previous pages
                pageBreaks.click(function () {
                    var selectedIndex = $(this).attr('page-index');
                    goToPage(selectedIndex);
                });

                var formLayout = getFormLayout(form);
                if (formLayout == 2)
                {
                    twoColumnLayout(form, '.erf-page');
                }


                /*
                 * Binds submit button.
                 * Hides previous page elements and shows new elements (If any)
                 * Registers parsley validation.
                 * Sends ajax request on successfull validation.
                 */
                $(form).submit(function (event) {
                    event.preventDefault();
                    var currentIndex = parseInt(navContainer.attr('current-page-index'));
                    var nextIndex = currentIndex + 1;
                    var formInstance = form.parsley(parsleyConfig);
                    pageBreaks.removeClass('erf-has-errors');
                    if (pages[nextIndex] !== void 0) {
                        goToPage(nextIndex, true);
                    }
                    formInstance.whenValidate({
                        group: 'block-' + currentIndex
                    }).done(function () {  // Triggers for last page.
                        if (nextIndex == pages.length) {
                            submit_form(form[0]);
                        }
                    });
                    
                });
            }
        }
        // Multipage functionality ends here

        /*
         * 
         * @param {type} planId
         * @returns {erforms-formL#1.getPriceOfPlan.parsedInt}
         */
        var getPriceOfPlan = function (planId) {
            for (var i = 0; i < erform_ajax.plans.length; i++)
            {
                var plan = erform_ajax.plans[i];
                if (plan.id == planId) {
                    var parsedInt = parseInt(plan.price);
                    if (!isNaN(parsedInt))
                        return parsedInt;
                }
            }
        }
        formContainer.each(function () {
            var formInstance = $(this).parsley(parsleyConfig);
            Parsley.addMessages('en', erform_ajax.parsley_strings);

            // Cloning submit button at last to add any elements externally in Form and delete auto generated button
            var submitBtnBlock = $(this).find('.erf-submit-button');
            var genratedButtonBlock = $(this).find('[type=submit]').closest('.form-group ');

            if (genratedButtonBlock.length > 0) {
                genratedButtonBlock.clone().appendTo(submitBtnBlock);
                genratedButtonBlock.remove();
            }


            var form = $(this);
            var formLayout = getFormLayout(form);
            var formGroups = form.find('.rendered-form');

            /* Change user_email type to email */
            var userEmailField = form.find('input[type=user_email]');
            if (userEmailField.length > 0) {
                userEmailField.attr('data-parsley-type', "email");
                userEmailField.attr('type', "email");
            }

            /*
             * Initialize datepicker for each of the date field.
             */
            form.find('[data-erf-type=date]').each(function () {
                var minDate = $(this).attr('min');
                var maxDate = $(this).attr('max');
                var dateFormat = $(this).data('date-format');
                var dateConfig = {dateFormat: dateFormat, changeMonth: true, changeYear: true, yearRange: '-100:+20'};
                if (minDate)
                    dateConfig.minDate = new Date(minDate);
                if (maxDate)
                    dateConfig.maxDate = new Date(maxDate);
                $(this).datepicker(dateConfig);
                $(this).attr('type', 'text');
                $(this).removeAttr('min'); // Removing min attribute to disable default field validation
                $(this).removeAttr('max');  // Removing max attribute to disable default field validation
            });


            // Current URL for after login redirect (Only for registration forms)
            form.find('#erform_redirect_to').val(jQuery(location).attr('href'));

            // Button position
            form.find('button[data-erf-btn-pos]').each(function () {
                var positionName = $(this).data('erf-btn-pos');
                if (positionName) {
                    $(this).closest('.fb-button').addClass('erf-btn-' + positionName);
                }

            });

            /* Payment related */
            var paymentWrapper = form.find('.erf-payment-wrapper');
            if (paymentWrapper.length > 0) {
                formGroups.append(paymentWrapper.clone(true, true).html());
                paymentWrapper.remove();


                form.find('select.erf-price').change(function () {
                    var element = $(this);
                    var elementTag = element[0].tagName
                    var price = 0;
                    var totalPaymentContainer = form.find('.erf-total-payment:first'); // Making sure to fetch only one element
                    var isArray = Array.isArray(element.val());
                    if (isArray)
                    {
                        var values = element.val();
                        for (var k = 0; k < values.length; k++)
                        {
                            price = price + getPriceOfPlan(values[k]);
                        }
                    } else
                    {
                        price = price + getPriceOfPlan(element.val());
                    }
                    totalPaymentContainer.html(price);
                });

                form.find('input.erf-price').on('keyup change blur', function () {
                    var element = $(this);
                    var price = 0;
                    var parsedInt = parseInt(element.val());
                    var totalPaymentContainer = form.find('.erf-total-payment:first'); // Making sure to fetch only one element
                    if (!isNaN(parsedInt))
                        price += parsedInt;
                    totalPaymentContainer.html(price);
                });

            }
            /* Payment ends here */

            paginateForm(form);
            var is_multipage = form.attr('erf-multipage');
            if (is_multipage != 1) {
                if (formLayout == 2) // Check if two column layout
                {
                    twoColumnLayout(form, '.rendered-form');
                }

                $(this).submit(function (event) {
                    event.preventDefault();
                    submit_form($(this)[0]);
                });
            } else
            {
                submitBtnBlock.find('button').html(erform_ajax.js_strings.next);
                submitBtnBlock.find('button').addClass('erf-nav-next erf-submit-button');
            }
            
            
    
            // Procssing hook for each form.
            var formID= form.data('erf-form-id');
            var submissionID= form.data('erf-submission-id');
            var submission= null;
            var formData= null;
            // Fetch submission data
            if(formID>0){
                $.post(erform_ajax.url, {form_id: formID,submission_id:submissionID, action: 'erforms_form_submission_data'}, function (res) {
                    if (res.success) {
                        if(res.data.hasOwnProperty('submission')){
                            submission= res.data.submission;
                        }
                        if(res.data.hasOwnProperty('form')){
                            formData= res.data.form;
                        }
                        $(document).trigger('erf_process_form_conditions', [form,submission,formData]);
                        $(document).trigger('erf_process_form_dynamic_fields', [form,submission,formData]);
                        $(document).trigger('erf_edit_submission_form',[form,submission,formData]);
                    }
                 }).fail(function (xhr, textStatus, e) {
                });
            }
            

        });

        /*
         * Handling Other Option for Checkbox and Radio Buttons
         */
        formContainer.find('.other-val').hide();
        formContainer.find('input.other-option').each(function () {
            $(this).change(function () {
                var self = $(this);
                var otherElement = self.siblings('label').children('.other-val');
                if (otherElement.length == 0)
                    return;

                if ($(this).is(':checked')) {
                    otherElement.slideDown();
                    otherElement.keyup(function () {
                        self.val(otherElement.val());
                    });
                    return;
                }
                otherElement.slideUp();
            });
        });

        formContainer.find('input[masking]').each(function () {
            var pattern = $(this).attr('masking');
            if (pattern) {
                var target = this;
                $(target).mask(pattern);
            }
        });

        formContainer.find('div[custom-type=spacer]').each(function () {
            var height = $(this).attr('height');
            $(this).html('');
            if (height) {
                $(this).css('height', height + 'px');
            }
        });


        /*
         * Used for Front form (For admin only)
         */
        var erforms_change_form_layout = function (form) {
            var formData = new FormData(form);
            $.ajax({
                url: erform_ajax.url,
                type: 'POST',
                data: formData,
                async: false,
                success: function (response) {
                    try {
                        if (response.success)
                        {
                            location.reload();
                        }
                    } catch (ex) {
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        $(".erf_form_layout_admin_open").each(function () {
            var button = $(this);
            var dialogContainer = button.closest('.erf_front_administration').children('.erf_form_layout_admin_dialog');
            var dialogForm = dialogContainer.find('form');

            if (button.length == 0 || dialogContainer.length == 0 || dialogForm.length == 0)
                return;

            button.click(function () {
                dialogContainer.dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Change": function () {
                            erforms_change_form_layout(dialogForm.get(0));
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            });
        });

    });
    
    /*Dynamic Field Population */
    
    $(document).bind('erf_process_form_dynamic_fields', function (ev, form,submission,formData) {
        var formId = form.find('[name=erform_id]').val();
        if (formId.length > 0) {
            $.post(erform_ajax.url, {form_id: formId, action: 'erforms_get_form_meta', meta: 'dynamic_rules'}, function (res) {
                if (res.success) {
                    var rules = res.data.dynamic_rules;
                    if (rules.load.length > 0) {
                        fieldCommandsOnLoad();
                    }

                    if (rules.change.length > 0) {
                        fieldCommandOnChange(rules.change);
                    }
                }
            }).fail(function (xhr, textStatus, e) {
                //console.log(xhr.responseText);
            });
        }

        var fieldCommandsOnLoad = function (field_name) {
            var data = {action: 'erforms_field_load_commands', form_id: formId};
            $.post(erform_ajax.url, data, function (res) {
                if (res.success) {
                    var commands = res.data.commands;
                    for (var i = 0; i < commands.length; i++) {
                        var command = commands[i];
                        for (var j = 0; j < command.on.length; j++) {
                            var field_name = command.on[j];
                            if (command.options) {
                                setDropdownOptions(field_name, command.data);
                            }
                            if (command.default_value != '') {
                                setElementValue(field_name, command.default_value);
                            }
                        }
                        
                        if(command.callback){
                            if (typeof window[command.callback] === "function")
                            {
                              window[command.callback](form,data,command);
                            }
                        }
                    }
                    jQuery('body').trigger('erf_edit_submission_field',[form,field_name,submission]);
                }
                
            }).fail(function (xhr, textStatus, e) {
                //console.log(xhr.responseText);
            });
        }

        var getElementValue = function (name) {
            var fieldInstance = form.find('[name="' + name + '"]');
            var value = fieldInstance.val();
            return value;
        }

        var setElementValue = function (name, value) {
            var fieldInstance = form.find('[name="' + name + '"]');
            if (fieldInstance.is(':radio')) {
                fieldInstance.filter('[value="' + value + '"]').prop('checked', true);
            } else if (fieldInstance.is(':checkbox')) {
                if (value) {
                    if (value.constructor === Array) {
                        for (var i = 0; i < value.length; i++) {
                            fieldInstance.filter('[value="' + value[i] + '"]').prop('checked', true);
                        }
                    } else
                    {
                        fieldInstance.filter('[value="' + value + '"]').prop('checked', true);
                    }
                }

            }else {
                fieldInstance.val(value);
            }
            fieldInstance.trigger('change');
        }
        var setDropdownOptions = function (name, values) {
            var fieldInstance = form.find('[name="' + name + '"]');
            fieldInstance.empty();
            if (values.length == 0) {
                fieldInstance.editableSelect();
            } else
            {
                if (fieldInstance.hasClass('es-input')) {
                    fieldInstance.editableSelect('destroy'); // In case it is an editable select
                }
                var fieldInstance = form.find('[name="' + name + '"]');
                $.each(values, function (val, label) {
                    fieldInstance.append($('<option>', {
                        value: val,
                        text: label
                    }));
                });
            }
        }

        var fieldCommandOnChange = function (rules) {
            for (var i = 0; i < rules.length; i++) {
                var changeRule = rules[i];
                (function(changeRule){
                    var data = {action: 'erforms_field_change_command', change_action: changeRule.action, field_name: changeRule.field_name, form_id: formId};
                    var fieldInstance = form.find('[name="' + changeRule.field_name + '"]');

                    if (fieldInstance.length == 0)
                        return;

                    fieldInstance.change(function(){
                        data.field_value = getElementValue(changeRule.field_name);
                        $.post(erform_ajax.url, data, function (res) {
                            if (res.success) {
                                var commands= res.data.commands;
                                for(var i=0;i<commands.length;i++){
                                    var command = commands[i];
                                    for (var j = 0; j < command.on.length; j++) {
                                        var field_name = command.on[j];
                                        if (command.options) {
                                            setDropdownOptions(field_name, command.data)
                                        }
                                        if (command.default_value != '') {
                                            
                                            setElementValue(field_name, command.default_value);
                                        }
                                    }
                                    if(command.callback){
                                        if (typeof window[command.callback] === "function")
                                        {
                                          window[command.callback](form,fieldInstance,data,command);
                                        }
                                    }
                                    fieldInstance.parsley().removeError(fieldInstance.prop('name'));
                                    if(command.error){
                                        fieldInstance.parsley().addError(fieldInstance.prop('name'),{message: command.error,updateClass:true});
                                       // alert(command.error);
                                    }
                                    else
                                    {
                                       fieldInstance.parsley().removeError(fieldInstance.prop('name')); 
                                    }
                                    
                                }
                                jQuery('body').trigger('erf_edit_submission_field',[form,field_name,submission]);
                            }
                        }).fail(function (xhr, textStatus, e) {
                            //console.log(xhr.responseText);
                        });
                    });
                })(changeRule)
            }
        }
    });

})(jQuery);

jQuery(document).ready(function () {
    $= jQuery;
    $(document).trigger('erf_process_form');
});


