jQuery(document).ready(function(){
   $= jQuery   
   $(".erf-show-register,.erf-show-login,.erf-show-lost-password").click(function(){ 
            var erfContainer= $(this).closest('.erf-container');
            var loginContainer= erfContainer.find('.erf-login-container');
            var registrationForm= erfContainer.find('.erf-reg-form-container');
            var lostPasswordContainer= erfContainer.find('.erf-password-lost-container');
            
            if(loginContainer.length>0)
                loginContainer.slideUp();
            if(registrationForm.length>0)
                registrationForm.slideUp();
            if(lostPasswordContainer.length>0)
                lostPasswordContainer.slideUp();
            
            if($(this).hasClass('erf-show-register')){
                 registrationForm.slideDown();
            }
            
            if($(this).hasClass('erf-show-login')){
                 loginContainer.slideDown();
            }
            
            if($(this).hasClass('erf-show-lost-password')){
                 lostPasswordContainer.slideDown();
            }
    });

    $('.erf-reset-password').click(function(){
        var lostPasswordContainer= $(this).closest('.erf-password-lost-container');
        var erfContainer= $(this).closest('.erf-container');
        var loginContainer= erfContainer.find('.erf-login-container');
        lostPasswordContainer.find('.erf-error').html();
        $.ajax({
            url: erform_ajax_url,
            type: 'POST',
            data: {'user_login': lostPasswordContainer.find('#erf_user_login').val(),'action':'erf_reset_password'},
            success: function (response) {
                try{
                   response= JSON.parse(response); 
                   if(response.success){
                       lostPasswordContainer.slideUp();
                       loginContainer.slideDown();
                       loginContainer.find('.erf-message').html(response.msg);
                       loginContainer.find('.erf-error').html('');
                   } else{
                       lostPasswordContainer.find('.erf-error').html(response.msg);
                   }
                  
                } catch(ex){
                }
            },
        }); 
    });
    
    $('.erf-login-form').submit(function(e){
        var loginContainer= $(this).closest('.erf-login-container');
        var loginForm= $(this);
        var rememberme= '';
        if(loginForm.find('#erf_rememberme').is(':checked')){
            rememberme= 'forever';
        }

        loginContainer.find('.erf-error').html('');
        $.ajax({
            url: erform_ajax_url,
            type: 'POST',
            data: {
                    'erf_username': loginForm.find('#erf_username').val(),
                    'erf_password': loginForm.find('#erf_password').val(),
                    'rememberme': rememberme,
                    'erf_login_nonce': loginForm.find('#erf_login_nonce').val(),
                    'redirect_to': $(location).attr('href'),
                    'action':'erf_login_user'
                },
            success: function (response) {
                try{
                   response= JSON.parse(response); 
                   if(response.success){
                       if(response.hasOwnProperty('redirect')){
                           location.href= response.redirect;
                       }
                       else if(response.hasOwnProperty('reload')){
                           loginContainer.find("#erf_login_reload_form").submit();
                       }
                   }
                   else{
                       loginContainer.find('.erf-error').html(response.msg);
                   }
                  
                } catch(ex){
                }
            },
        });
        e.preventDefault();
    });
});