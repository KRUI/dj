//manage.js
jQuery(document).ready(function() {
	//placeholder
	jQuery('.text_input').placeholder();
	//tool tips
	jQuery('li.nav_link').tipsy({fade: true, gravity: 'w'});
	jQuery('li.app_settings_item').tipsy({fade: true, gravity: 'w'});
	//tab navigation
	jQuery('#content_view').tabs();
	//application management
	jQuery('a#app_settings_button').button().click(function() {
	    jQuery.ajax({
	        type: 'POST',
	        url: 'lib/manage.php',
	        data: ({
	            action:'update',
	            domain:jQuery('input#global_domain').val(),
	            url:jQuery('input#global_url').val(),
	            message:jQuery('input#global_message').val(),
	            email:jQuery('input#global_email').val(),
	            twitter_consumer:jQuery('input#global_twitter_consumer').val(),
	            twitter_secret:jQuery('input#global_bitly_username').val(),
	            bitly_username:jQuery('input#global_twitter_secret').val(),
	            bitly_key:jQuery('input#global_bitly_key').val()
	        }),
	        dataType: 'json',
	        success: function(update){
	            alert(update.status);
	        },
	        complete: function(xhr, statusText){
	            if (xhr.status != '200'){
					var update = jQuery.parseJSON(xhr.responseText);
					alert(update.error);
				}
	        }
	    });
	});
	//user management
	jQuery('a#user_create_button').button().click(function(){
		jQuery.ajax({
			type: 'POST',
			url: '../lib/users.php',
			data: ({
				type:'single',
				action:'new',
				status:'new',
				username:jQuery('input#users_create_username').val(),
				firstname:jQuery('input#users_create_firstname').val(),
				lastname:jQuery('input#users_create_lastname').val(),
				password:jQuery('input#users_create_password').val(),
				password_update:'1',
				email:jQuery('input#users_create_email').val()
			}),
			dataType: 'json',
			success: function(create){
				alert(create.status);
			},
			complete: function(xhr, statusText){
				if (xhr.status != '200'){
					var create = jQuery.parseJSON(xhr.responseText);
					alert(create.error);
				}
			}
		});
		jQuery('input#users_create_username').val('');
		jQuery('input#users_create_firstname').val('');
		jQuery('input#users_create_lastname').val('');
		jQuery('input#users_create_password').val('');
		jQuery('input#users_create_email').val('');
		jQuery('.text_input').placeholder();
	});
	jQuery('button#multiple_user_button').button();
});