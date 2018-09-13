jQuery(document).ready(function(){
    $= jQuery; 
    var editor= $("#erforms_fb_editor");
    if(editor.length==0)
        return;
    var progressWheel= $("#erf_progress");
    /*
     * Initializing
     */
    // Disables last child
    $("ul").sortable({
        items: "li:not(:last-child)"
    });
    // Disables click event on field control
    setTimeout(function(){ $(".form-builder .pull-right .ui-sortable-handle").unbind( "click" ); },1000);
    
    var form_data= erf_data.form_data;
    var form_type= form_data.type;
    var disableFields= ['autocomplete','paragraph'];
    var primaryField= ['user_email','password'];
    if(form_type!='reg'){
        disableFields.push('username');
    }
    /***************** Builder default configuration  *************/
    options = {
      disableFields:disableFields,
      controlOrder: [
      'username',    
      'text',
      'textarea',
      'select',
      'address-details',
      'button',
      'checkbox-group',
      'radio-group',
      'file',
      'date',
      'number'
      ],
      editOnAdd: true,
      typeUserAttrs: {
        text: {  
            label:{}, 
            description:{},
            confirmPassword: { 
                label: 'Confirmar contraseña',
                type: 'checkbox',
                },
            advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
            },
            subtype:{
                options:{
                    'text': 'Text Field',
                    'email' : 'Email',
                    'user_email': 'User Email',
                    'password': 'User Password',
                    'tel' : 'Tel',
                    'url': 'URL'

                },
                onchange: "erforms_primary_field_options(this)"
            },placeholder:{},
            className:{},
            masking:{
                    label: 'Masking Format',
                    type:'text',
                    placeholder: '(99) 9999-999',
                    onchange: "erforms_mask_changed(this)"
            },
            pattern:{
                    label: 'Input Pattern',
                    type: 'text',
            },
            maxlength:{},
            icon:{
                label: 'Icon',
                type: 'text',
                class: 'irforms-icp fld-icon',
                onchange: 'erforms_iconChanged(this)',
            },
            name:{},
            addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            },
            enableUnique: {
              label: 'Only Unique',
              type: 'checkbox',
            },
            entityType:{label:'Entity',type: 'text'},
            entityProperty:{label:'Entity Property',type: 'text'}
        },
        file:{
            label:{},
            description:{},
            advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
            },
            subtype:{
                options:{
                   file: 'File Upload'
                }
            },
            placeholder:{},className:{},
            accept: {
            label: 'Allowed File Types',
            type: 'text',
          },
          name:{},
          icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          }
        },
        button:{
            subtype:{
              options: {
                  'submit': 'Submit',
                  'button': 'Button',
                  'reset': 'Reset'
              }  
            }
        },
        textarea:{
          label:{}, 
          subtype:{
              options:{
                  'textarea': 'Text Area',
              },
              onchange: "erforms_primary_field_options(this)"
          },
          value:{},description:{},
          advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
          },
          placeholder:{},className:{},maxlength:{},rows:{},
          icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
          name:{},
          addUserField: {
            label: 'Map To UserMeta',
            type: 'checkbox',
            onclick: 'erforms_showChildren(this)',
            'data-child': 'addUserFieldMap-wrap'
          },
          addUserFieldMap: {
            label: 'Meta Key',
            type: 'text',
          }
        },
        'checkbox-group':{
            description:{},
            advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
            },value:{},
            toggle:{type:'checkbox'},name:{},className:{},
            icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
            },
            addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            }
        },
        date:{
            label:{},value:{},description:{},
            advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
            },
            placeholder:{},
            min: {
                label: 'Minimum Date',
                type: 'text',
                'dataDateFormat': 'mm/dd/yy'
            },
            max: {
                label: 'Maximum Date',
                type: 'text',
                'dataDateFormat': 'mm/dd/yy'
            },
            className:{},
            name:{},
            icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
          addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            }
        },
        'radio-group':{
            description:{},
             'user_roles':{
              label: 'User Roles',
              type: 'checkbox',
              onclick: 'erforms_fill_user_roles(this)',
            },
            advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
            },value:{},
             name:{},className:{},
             icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
          addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            }
        },
        autocomplete:{
             name:{},value:{},description:{},
             advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
             },
             placeholder:{},className:{},
             icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
          addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            }
        },
        number:{
             label:{},value:{},
             min:{label:'Minimum',type: 'number'},max:{label:'Maximum',type: 'number'},
             advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
             },
            step:{label:'Step',type: 'number'},placeholder:{},description:{},
            pattern:{
                    label: 'Input Pattern',
                    type: 'text',
            },
            className:{},name:{},
             icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
           addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            },
            enableUnique: {
              label: 'Only Unique',
              type: 'checkbox',
            },
        },
        select: {
          name:{},value:{},description:{},
          advance: {
                type: 'button',
                value: '+ Advanced Settings',
                label: ' ',
                onclick: 'erforms_show_advance_settings(this)',
          },
          placeholder:{},className:{},
          icon:{
              label: 'Icon',
              type: 'text',
              class: 'irforms-icp fld-icon'
          },
          addUserField: {
              label: 'Map To UserMeta',
              type: 'checkbox',
              onclick: 'erforms_showChildren(this)',
              'data-child': 'addUserFieldMap-wrap'
            },
            addUserFieldMap: {
              label: 'Meta Key',
              type: 'text',
            },
           entityType:{label:'Entity',type: 'text',value:''},
           entityProperty:{label:'Entity Property',type: 'text',value:''},
        },
        button:{
            name:{},
            'dataErfBtnPos': {
                      label: 'Position',
                      options: {
                          left: 'Left',
                          right: 'Right',
                          center: 'Center'
                      }
            },
            
        },
        paragraph:{
            editor:{
                label: ' ',
                type: 'button',
                value: 'Show Editor',
                class: 'rt_editor button button-primary',
                onclick: 'openRichTextEditor(this)'
            },
            className: {
                
            },
            height:{
                label: 'Height',
                type: 'number'
            },
            customType:{
                label: ' ',
                type: 'hidden'
            },
            subtype:{
                options:{
                    'div': 'div',
                    'p' : 'p',
                }
            }
            
        },
        hidden:{
             label: {},
             value: {}
         }
        },
      // Register Event onAdd
      typeUserEvents: {
            text: {
                onadd: function (fld) {
                    var subtypeEl= jQuery(fld).find('.frm-holder .form-elements .fld-subtype');
                    var subtypeVal= subtypeEl.val();
                    subtypeEl.closest('.subtype-wrap').hide();
                    if(primaryField.indexOf(subtypeVal)>=0){
                        // Removing actions from Primary fields to restrict duplication and removal
                        removeActions(fld,['delete','copy']);
                        jQuery(fld).find('.frm-holder .form-elements .masking-wrap').remove();
                    } 
                    erforms_add_form_element_help(fld);
                    wrapAdvanceSettings(fld,5);
                    fixCheckedPropForField(fld, ["addUserField","enableUnique","confirmPassword"]);
                    
                   
                    if(subtypeEl.val()=='url'){
                        jQuery(fld).find('.masking-wrap').hide();
                    }
                    
                    if(subtypeEl.val()!='password'){
                        jQuery(fld).find('.confirmPassword-wrap').remove();
                    }
                    toggleEntityInfoFields(false,fld);
                    // Check for entity Info
                    if($(fld).find('[name=entityType]').length>0 && $(fld).find('[name=entityType]').val()=='user'){
                        // Check for more than one Username field
                        if($('.form-wrap li.text-field .prev-holder [entity-property=username]').length>1)
                        {
                           alert('There can not be multiple Username fields'); 
                           formBuilder.actions.removeField($(fld).prop('id'));
                        }
                        else
                        {
                            $(fld).find('span.field-subtype').html($(fld).find('[name=entityProperty]').val());
                            jQuery(fld).find('.masking-wrap').hide();
                        }
                    }
                    $(fld).find('.prev-holder input').prop('readonly',true); // To disabled auto completed
                }
            },
            textarea:{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,5);
                    fixCheckedPropForField(fld, ["addUserField"]);
                    erforms_add_form_element_help(fld);
                }
            },
            number:{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,5);
                    fixCheckedPropForField(fld, ["addUserField","enableUnique"]);
                    erforms_add_form_element_help(fld);
                }
            },
            date:{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,4);
                    fixCheckedPropForField(fld, ["addUserField"]);
                    $(fld).find('[data-date-format]').each(function(){
                        $(this).datepicker({dateFormat: $(this).data('date-format'),changeYear: true,changeMonth: true,yearRange: '-100:+20'});
                    });
                    erforms_add_form_element_help(fld);
                }
            },
            autocomplete:{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,6);
                    fixCheckedPropForField(fld, ["addUserField"]);
                    erforms_add_form_element_help(fld);
                }
            },
            select:{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,7);
                    fixCheckedPropForField(fld, ["addUserField"]);
                    erforms_add_form_element_help(fld);
                    toggleEntityInfoFields(false,fld);
                    
                    // Check for address fields
                    if($(fld).find('[name=entityType]').val()=='address'){
                        $(fld).find('.fld-multiple,.field-options input').prop('disabled', true);
                    }
                }
            },
            'checkbox-group':{
                onadd: function (fld) {
                    fixCheckedPropForField(fld, ["toggle","addUserField"]);
                    wrapAdvanceSettings(fld,6);
                    erforms_add_form_element_help(fld);
                    jQuery(fld).find('.option-selected[value=true]').prop('checked',true);
                }
            },
            'radio-group':{
                onadd: function (fld) {
                    wrapAdvanceSettings(fld,7);
                    fixCheckedPropForField(fld, ["addUserField","user_roles"]);
                    erforms_add_form_element_help(fld);
                    jQuery(fld).find('.option-selected[value=true]').prop('checked',true);
                }
            },
            file:{
                onadd: function (fld) {
                    $(fld).find(".multiple-wrap,.placeholder-wrap").remove();
                    wrapAdvanceSettings(fld,3);
                    erforms_add_form_element_help(fld);
                }
            },
            button : {
                onadd: function(fld){
                    var subtypeEl= jQuery(fld).find('.frm-holder .form-elements .fld-subtype');
                    var subtypeVal= subtypeEl.val();
                    
                    if(subtypeVal=="submit")
                    {
                     subtypeEl.closest('.subtype-wrap').hide();    
                     removeActions(fld,['delete','copy']);   
                    }
                    else
                    {
                        subtypeEl.find("option[value='submit']").remove();
                    }
                    erforms_add_form_element_help(fld);
                }
            },
            paragraph:{
                 onadd: function(fld){
                            // Check if this field is used as spacer/separator
                            var spacer= $(fld).find('div[custom-type=spacer]');
                            if(spacer.length>0){
                                 jQuery(fld).find('.frm-holder .form-elements .label-wrap').hide();
                                 jQuery(fld).find('.frm-holder .form-elements .subtype-wrap').hide();
                            }
                            else{ // Remove height option as it is only for space/separator
                                jQuery(fld).find('.frm-holder .form-elements .height-wrap').remove();
                            }
                            
                            // Check if this field is used as Page Break
                            var pageBreak= $(fld).find('div[custom-type=page-break]');
                            if(pageBreak.length>0){
                                var container= pageBreak.closest('.form-field');
                                if(container.length>0){
                                    container.addClass('page-break');
                                    container.find('.input-wrap .form-control').addClass('splitter');
                                    container.find('.field-subtype').html('Splitter');
                                    jQuery(fld).find('.label-wrap label').html('Title');
                                    jQuery(fld).find('.frm-holder .form-elements .subtype-wrap').hide();
                                }
                            }
                            
                            var subtypeEl= jQuery(fld).find('.frm-holder .form-elements .fld-subtype');
                            var subtypeVal= subtypeEl.val();
                            jQuery(fld).find('.subtype-wrap').hide();
                            
                            
                            
                            if(pageBreak.length>0 || spacer.length>0){
                                jQuery(fld).find('.rt_editor').hide();
                            }
                            else{
                                // Editor for DIV and P tags
                                    jQuery(fld).find('.field-subtype').html('RichText');
                            }
                            
                            
                        }
            },
            hidden:{
                onadd: function (fld) {
                    jQuery(fld).find('.value-wrap').attr('style','display: block !important');
                }
            }
        },
       disabledAttrs:['access'],
       fields : [
                    {
                      label: 'Email',
                      type: "text",
                      subtype: "email",
                      customIcon: 'fa fa-envelope'
                    },
                    {
                      label: 'Phone',
                      type: "text",
                      subtype: "tel",
                      customIcon: 'fa fa-phone',
                      icon: ' '
                    },
                    {
                      label: 'URL',
                      type: "text",
                      subtype: "url",
                      customIcon: 'fa fa-internet-explorer',
                      icon: ' '
                    },
                    {
                        label: 'Separator',
                        type: 'paragraph',
                        name: 'spacer',
                        subtype: 'div',
                        customIcon: 'fa fa-arrows-v',
                        className: 'spacer',
                        customType: 'spacer'
                    },
                    {
                        label: 'Splitter',
                        type: 'paragraph',
                        name: 'page',
                        subtype: 'div',
                        customIcon: 'fa fa-file-text-o',
                        className: '',
                        customType: 'page-break'
                    },
                    
                ],
       inputSets: [
        {
            label: 'Username',
            icon: 'fa fa-user',
            name: 'username', // optional - one will be generated from the label if name not supplied
            showHeader: false, // optional - Use the label as the header for this set of inputs
            fields: [
                {
                  type: 'text',
                  label: 'Username',
                  entityType: 'user',
                  entityProperty:'username',
                  className: 'form-control',
                  required: true
                },
            ]
          },
          {
          label: 'Address',
          icon: 'fa fa-address-book-o',
          name: 'address-details', // optional - one will be generated from the label if name not supplied
          showHeader: false, // optional - Use the label as the header for this set of inputs
          fields: [
              {
                type: 'select',
                label: 'Country',
                entityType: 'address',
                entityProperty:'country',
                className: 'form-control',
                values:[{}]
              },
              {
                type: 'select',
                label: 'State / Province',
                entityType: 'address',
                entityProperty:'state',
                className: 'form-control',
                values:[{}]
              },
              {
                type: 'text',
                label: 'Town / City',
                className: 'form-control',
                entityType: 'address',
                entityProperty:'city',
              },
              {
                type: 'text',
                label: 'Street Address 1',
                className: 'form-control',
                entityType: 'address',
                entityProperty:'street1',
              },
              {
                type: 'text',
                label: 'Street Address 2',
                className: 'form-control',
                entityType: 'address',
                entityProperty:'street2',
              },
              {
                  type: 'text',
                  label: 'Postcode / Zip',
                  className: 'form-control',
                  entityType: 'address',
                  entityProperty:'zip',
              }
            ]
        },
        {
          label: 'RichText',
          icon: 'fa fa-code',
          name: 'richtext-field', // optional - one will be generated from the label if name not supplied
          showHeader: false, // optional - Use the label as the header for this set of inputs
          fields: [
              {
                type: 'paragraph',
                label: 'Type your content here.',
                className: 'fb-rich-text'
           }]
        },
        
       ],         
       fieldRemoveWarn: true         
    };
    /************* Default configuration ends here ********************/
    
    var formBuilder = editor.formBuilder(options);
    var fields=  JSON.stringify(form_data.fields);    
    setTimeout(function(){ setFormData(); },500);
    
    function setFormData(){
        formBuilder.actions.setData(fields);
        $('#erf-form-builder-wrapper').show();
        editor.find('.irforms-icp').iconpicker(); 
        erforms_mask_fields();
    }
    /*************** Save form fields   *********************/
    $("#erforms-form-save-btn").click(function(){
        progressWheel.show();
        $('.advance-settings').hide();
        $('.advance-wrap').find('input').val('+ Advanced Settings');
        save_form(false);
    });
    
    $("#erforms-form-save-close-btn").click(function(){
        progressWheel.hide();
        $('.advance-settings').hide();
        $('.advance-wrap').find('input').val('+ Advanced Settings');
        save_form(true);
    });
    
    /*************** Create form fields   *********************/
    $("#erforms-create-form-btn").click(function(){
        var data ={
                    action: 'erf_new_form',
                    title  : $('#erforms-form-title').val(),
                    id: $("#erforms-form-id").val(),
        };
        
        $.post(ajaxurl, data, function(res) {
                    if (res.success) {
                            if (res.data.redirect) {
                                    window.location.href = res.data.redirect;
                            }
                    } else {
                            //console.log(res);
                    }
            }).fail(function(xhr, textStatus, e) {
                    //console.log(xhr.responseText);
        });
    });
    
    /*************** Save form fields   *********************/
    var save_form= function(close){
        
        form_data.fields= formBuilder.actions.getData();
        form_data.form_html= formHTML();
        form_data.title= $('#erforms-form-title').val();

            var data ={
                    action: 'erf_save_form',
                    data  : JSON.stringify(form_data),
                    id: form_data.id,
                    
            };
            $.post(ajaxurl, data, function(res) {
                    if (res.success) {
                        progressWheel.hide();
                            if (res.data.redirect && close) {
                                    window.location.href = res.data.redirect;
                            }
                    } else {
                            //console.log(res);
                            progressWheel.hide();
                    }
            }).fail(function(xhr, textStatus, e) {
                   // console.log(xhr.responseText);
                   progressWheel.hide();
            });
    
    }
    /*************** Remove last field from editor  *********************/
    function removeField () {
        formBuilder.actions.removeField();
    } 
    
    function toggleEntityInfoFields(show,fld){
        if(show){
            $(fld).find('.entityType-wrap').show();
            $(fld).find('.entityProperty-wrap').show();
            return;
        }
        $(fld).find('.entityType-wrap').hide();
        $(fld).find('.entityProperty-wrap').hide();
    }
    /************** Checkbox fix ***************************************/
    function fixCheckedPropForField (fld, fieldNames) {
        for(var i=0;i<fieldNames.length;i++){
            // Retrieve the Checkbox as a $ object
            $checkbox = $(".fld-"+ fieldNames[i], fld);

            // According to the value of the attribute "value", check or uncheck
            if($checkbox.val() == "true"){
                $checkbox.attr("checked", true);
            }else{
                $checkbox.attr("checked", false);
            }
            erforms_showChildren($checkbox);
        }   
    };
    
    var formHTML= function(){
     formData= form_data.fields;
     formData= processFields(formData);
     var $markup = $('<div/>');
     $markup.formRender({formData});
     console.log($markup[0].innerHTML);
     return $markup[0].innerHTML;
    }
    
    var processFields= function(formData){
        var fields= [];
        for(var i=0;i<formData.length;i++){
            // Adding confirm Password field if configured
            if(formData[i].type=='text'){
                 formData[i].value=''; // Remove any auto fill value
                 if(formData[i].subtype=='password' && formData[i].confirmPassword){
                    fields.push(formData[i]);
                    var newField= jQuery.extend({}, formData[i]);
                    newField['data-parsley-confirm-password']= formData[i].name;
                    newField['label']= erf_data.js.confirm_password;
                    newField['name']= Math.random().toString(36).substring(7);
                    newField['id']= newField['name'];
                    fields.push(newField);
                    continue;
                 }
                 
            }
            else if(formData[i].type=='date'){
                formData[i]['data-erf-type']= 'date';
                formData[i]['data-date-format']= 'mm/dd/yy';
            }
            
            if(formData[i].subtype && formData[i].subtype=='div'){ // Avoiding undefined values on front end
                if(!formData[i].label){
                    formData[i].label='&nbsp;';
                }
            }
            
            fields.push(formData[i]);
        }
        return fields;
    }
    
    // Adding icon picker with newly added fields.
    $(document).bind("fieldAdded", function(e, eventObj) { 
        $('.irforms-icp').iconpicker(); 
    });
    
    var removeActions= function(fld,actions){
        for(var i=0;i<actions.length;i++){
            if(actions[i]=="toggle")
                jQuery(fld).find(".field-actions .toggle-form").remove();
            else if(actions[i]=="copy")
                jQuery(fld).find(".field-actions .copy-button").remove(); 
            else if(actions[i]=="delete")
                jQuery(fld).find(".field-actions .del-button").remove(); 
                
        }            
    }
    
    var wrapAdvanceSettings= function(fld,after){
        var after= after || 4;
        var formElements= $(fld).find(".frm-holder .form-elements");
        $(formElements).find('.form-group:gt(' + after +')').wrapAll('<div class="advance-settings erform-hidden"></div>');
    }
    
    
    progressWheel.hide();
});

    
    
var erforms_showChildren= function (obj){
   var dataProp=  jQuery(obj).attr('data-child');
   var elementsToHide= jQuery(obj).closest('.form-group').next("." + dataProp);
   if(jQuery(obj).is(':checked')){
       jQuery(elementsToHide).show("medium");
   } else{
        jQuery(elementsToHide).hide("medium");
   }
}

var erforms_isFieldUnique= function(type){
    if($("." + type +"-field").length>1)
        return false;
    return true;
}

var erforms_mask_changed= function(obj){
}

var erforms_mask_fields= function(){
  setTimeout(function(){
      jQuery("#erforms_fb_editor [masking]").each(function(){
        var pattern= $(this).attr('masking');
        if(pattern){
          var target= this;
          jQuery(target).mask(pattern);
        }
        });  
  },500);    
   
};

var erforms_primary_field_options= function(obj){
    if(obj.value!='user_email' && obj.value!='password')
        return;
    obj= jQuery(obj);
    var objectId= obj.attr('id');
    var holderId= objectId.replace('subtype-','');
    var target= jQuery("#" + holderId);
    var count= jQuery("#erforms_fb_editor input[type='" + obj.val() + "']").length;
    
    if(count>0)
    {
        var label= jQuery("#erforms_fb_editor input[type='" + obj.val() + "']").siblings("label");
        if(obj.val()=="user_email"){
            alert("There can not be multiple User Email in a single form. " + label.text() + " is already configured as User Email.");
            obj.val('email');
        } else if(obj.val()=="password"){
            alert("There can not be multiple User Password in a single form. " + label.text() + " is already configured as User Password.");
            obj.val('text');
        }
    }
}

// Show hide advance setting section from individual field settins.
var erforms_show_advance_settings= function(obj){
    var advanceGroup= jQuery(obj).closest(".form-group").next(".advance-settings");
    advanceGroup.slideToggle("slow",function(){
        if (advanceGroup.is(':visible')){
            $(obj).val('- Advanced Settings');
        }
        else
        {
             $(obj).val('+ Advanced Settings');
        }
    });  
}

var erforms_fill_user_roles= function(obj){
    var formHolder= jQuery(obj).parents('.frm-holder');
    formHolder.find('.sortable-options li').remove(); // Remove existing options
    
    for(var i=0;i<erf_data.roles.length;i++){
        formHolder.find('.add-opt').trigger('click');
    }
    
    formHolder.find('.sortable-options li').each(function(index){
        jQuery(this).find('.option-label').val(erf_data.roles[index].name);
        jQuery(this).find('.option-value').val(erf_data.roles[index].role);
    });
    
}

var erforms_iconChanged= function(obj){
    
}

var erforms_add_form_element_help= function(fld){
    var fieldWraps = erf_data.text_helpers;
    
    for (value in fieldWraps) {
        if(value=='advance-wrap')
            continue;
        
        var helpTextWrap = jQuery(fld).find('.form-elements .form-group.' +value );
        helpTextWrap.append('<div class="form-element-help"><i class="fa fa-info-circle" aria-hidden="true"></i>  ' + fieldWraps[value] +'</div>');
    }
    

}

var openRichTextEditor= function(obj){
     var field_container= jQuery(obj).closest('.form-field');
     var form_elements= jQuery(obj).closest(".form-elements");
     var editor_div= form_elements.find('div[contenteditable=true]');
     var field_label= field_container.find(".field-label");
     var editor= jQuery('#erf_editor');
     editor.val(editor_div.html());
     jQuery( "#erf_rich_text_editor" ).dialog({
          resizable: false,
          height: "auto",
          width: 800,
          modal: true,
          open: function( event, ui ) {
              wp.editor.initialize('erf_editor',{
                       tinymce:  {
                           setup : function(ed) {
                                    ed.onChange.add(function(ed, l) {
                                        editor_div.html(ed.getContent());
                                    });
                             },
                            wpautop:true
                       },
                       quicktags: true,mediaButtons:true

                    });
          },
          beforeClose: function( event, ui ) {
              wp.editor.remove('erf_editor');
          },
          buttons: {
            "Apply": function() {
               editor_div.html(wp.editor.getContent('erf_editor'));
               $(this).dialog( "close" ); 
            },
            Cancel: function() {
              $(this).dialog( "close" );
            }
          }
        });
    }
