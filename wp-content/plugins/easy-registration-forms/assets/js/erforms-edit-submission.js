jQuery(document).on('erf_edit_submission_form',function(ev,form,submission,formData)
{   $= jQuery;
    if(typeof form==="undefined" || !formData || !submission)
        return;
    var prepareForm= function(){
        if(!erform_ajax.is_admin){
            form.find(':input,:radio,:checkbox').not('[type="submit"]').addClass('erf-disabled');  // By default disable all the fields
            if(formData.hasOwnProperty('en_edit_sub') && formData.hasOwnProperty('edit_fields') && formData.edit_fields.length>0){
                for(var i=0;i<formData.edit_fields.length;i++){
                    var single= $("[name=" + formData.edit_fields[i] + "]"); // For single fields
                    var multi= $("[name='" + formData.edit_fields[i] + "[]']"); // For array type of fields
                    if(single.length>0){
                        single.removeClass('erf-disabled');
                    }

                    if(multi.length>0){
                        multi.removeClass('erf-disabled');
                    }
                }
            }
        }
        $.each(submission.fields_data, function(key,field_data) {
        var field= $("[name=" + field_data.f_name + "]");
        if(field_data.f_type=='checkbox-group' || field_data.f_type=='select')
        {
            if(field.length==0)
            {
                 field= $("[name='" + field_data.f_name + "[]']");
            }
        }
        if(field.length==0 /*|| field.prop('disabled')*/)
              return;

        if (field.is(':radio') || field.is(':checkbox')) {
            field.each(function(){
               if(jQuery(this).val()==field_data.f_val){
                   jQuery(this).prop('checked',true);
                   return;
               } 
            });
        }
        if(field.is(':file')){ // Skip file type inputs
            return;
        }
        if(!field.is(':radio')){ // Excluding radio inputs
            field.val(field_data.f_val);
        }

        field.trigger('change');
        });
        $('<input>').attr({style: 'display:none;',type: 'text',name: 'submission_id',value:submission.id}).appendTo(form);
    }
    prepareForm();
    
});


jQuery(document).on('erf_edit_submission_field',function(ev,form,fieldName,submission){
     if(!submission)
         return;
     $.each(submission.fields_data, function(key,field_data) {
            if(fieldName==field_data.f_name){
                var field= $("[name=" + fieldName + "]");
                if(field_data.f_type=='checkbox-group' || field_data.f_type=='select')
                {
                    if(field.length==0)
                    {
                         field= $("[name='" + field_data.f_name + "[]']");
                    }
                }
                if(field.length==0)
                  return;
                if (field.is(':radio') || field.is(':checkbox')) {
                    field.each(function(){
                       if(jQuery(this).val()==field_data.f_val){
                           jQuery(this).prop('checked',true);
                           return;
                       } 
                    });
                }
                if(field.is(':file')){ // Skip file type inputs
                    return;
                }
                if(!field.is(':radio')){ // Excluding radio inputs
                    field.val(field_data.f_val);
                }
                field.trigger('change');
            }
    });
              
});

   