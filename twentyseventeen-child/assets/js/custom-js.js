	jQuery(document).ready(function(){
		jQuery( "#user_reg_form" ).validate({
			   rules: {
			    // simple rule, converted to {required:true}
			    user_name: "required",
				user_email: {
				      required: true,
				      email: true
				},
			    user_password:{
				      required: true,
				      minlength: 8
				    }, 
			    user_password_confirm: {
			    	required: true,
			    	equalTo : "#confirm_pass"
			    }

			  },
			  submitHandler: function(form) {
			  	jQuery(".custom-reg_loader_wrp").addClass('overlay');
			  	jQuery(".custom-reg_loader").css("display","block");
			  	 /*
				 * On  submit form jquery and ajax to create user
				 */
					var user_name 		= jQuery('.user_name_class').val();
					var user_password 	= jQuery('.user_password_confirm_class').val();
					var user_email 		= jQuery('.user_email_class').val();
					jQuery.ajax({
			    		url  	: ajax_object.ajaxurl,
			    		type 	: 'POST',
			    		data 	:{
			    				user_name     : user_name,
			    				user_password : user_password,
			    				user_email 	  : user_email,
			    				security	  : ajax_object.ajax_nonce,
			    				action        : 'user_registration_ajax_func'
			    		},
			    		success : function(data){
			    			/*
							 * On  submit form popup with suitable message
							 */
							 
			    			if(data == 'user email already in use please try something new')
			    			{
			    				jQuery(".custom-reg_loader").css("display","none");
			    				jQuery('.custom-dailog-body').empty();
			    				jQuery('.custom-dailog-body').append('<h2 class="error_in_reg">User Email already in use please try something new</h2>');
			    				jQuery('.custom_popup_alert').css("display","block");
			    				jQuery(".custom_popup_alert").fadeIn(2500);
			    				jQuery(".custom_popup_alert").fadeOut(4000);
			    				 setTimeout(function(){
								   window.location.reload(1);
								}, 4000);
			    			}
			    			else if(data == 'You have successfully Register'){
			    				jQuery(".custom-reg_loader").css("display","none");
			    				jQuery('.custom-dailog-body').empty();
								jQuery('.custom-dailog-body').append('<h2 class="success_in_reg">You have successfully Registered.Please check your email for logins.</h2>');
			    				jQuery('.custom_popup_alert').css("display","block");
			    				jQuery(".custom_popup_alert").fadeIn(2500);
			    				jQuery(".custom_popup_alert").fadeOut(4000);
			    				setTimeout(function(){
								   window.location.reload(1);
								}, 4000);
			    			}
			    		}
					})
			  	}
		});
	});
