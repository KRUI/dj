//Global.js
jQuery(window).load(function () {
//login
jQuery('input').placeholder();
jQuery(document).ready(function() {
    jQuery(document).keydown(function(e) {
        if (e.keyCode === 13) {
            jQuery.ajax({
        		type: 'POST',
        		url: 'session/new/',
        		data: ({
        			//Application authentication
        			type:'web_server',
        			client_id:'68a21cf09858ad8285ac40f37b83a1d717af5494',
        			client_secret:'33a670bacd2733b601fb77a99eb9af91ee92ea0bc4a53c142cb6751631cdf6bf',
        			redirect_uri:'/playlist/',
        			state:'new',
        			scope:'internal',
        			//User authentication
        			studio:jQuery('#select_studio').val(),
        			username:jQuery('#username').val(),
        			password:jQuery('#password').val()
        			}),
        			dataType: 'json',
        			success: function(auth){
        				if (auth.state == 'verified'){
        					window.location.replace(auth.redirect_uri);
        				}
        			},
        			complete: function(xhr, statusText){
        				if (xhr.status != '200'){
        					var auth = jQuery.parseJSON(xhr.responseText);
        					alert(auth.error);
        				}
        			}
        		});
        }
    });
});
jQuery('a#login_button').button().click(function(){
	jQuery.ajax({
		type: 'POST',
		url: 'session/new/',
		data: ({
			//Application authentication
			type:'web_server',
			client_id:'68a21cf09858ad8285ac40f37b83a1d717af5494',
			client_secret:'33a670bacd2733b601fb77a99eb9af91ee92ea0bc4a53c142cb6751631cdf6bf',
			redirect_uri:'/playlist/',
			state:'new',
			scope:'internal',
			//User authentication
			studio:jQuery('#select_studio').val(),
			username:jQuery('#username').val(),
			password:jQuery('#password').val()
			}),
			dataType: 'json',
			success: function(auth){
				if (auth.state == 'verified'){
					window.location.replace(auth.redirect_uri);
				}
			},
			complete: function(xhr, statusText){
				if (xhr.status != '200'){
					var auth = jQuery.parseJSON(xhr.responseText);
					alert(auth.error);
				}
			}
		});
});
	jQuery('#request').dialog({
		autoOpen: false,
		title: "Request Access",
		width: 500,
		buttons: {
			'Submit Request':function(){
				jQuery.ajax({
					type: 'POST',
					url: 'session/request/',
					data: ({
						//reCAPTCHA
						recaptcha_challenge_field:jQuery('#recaptcha_challenge_field').val(),
						recaptcha_response_field:jQuery('#recaptcha_response_field').val(),
						//Access Request
						firstname:jQuery('#request_firstname').val(),
						lastname:jQuery('#request_lastname').val(),
						email:jQuery('#request_email').val(),
						semester:jQuery('#request_semester').val(),
						year:jQuery('#request_year').val()
					}),
					dataType: 'json',
					success: function(request){
						alert(request.status);
					},
					complete: function(xhr, statusText){
						if (xhr.status != '200'){
							Recaptcha.reload("6Le7ZbsSAAAAALmYyu26hMOoi_e-P8g1bqpGwbsV", 'recaptcha', {
								theme: 'clean',
								callback: Recaptcha.focus_response_field
							});
							alert(xhr.responseText);
						}
					}
				});
				jQuery('#request_firstname, #request_lastname, #request_email').val('');
				jQuery('input').placeholder();
			}
		},
		modal: true
	});
	jQuery('a#request_button').button().click(function(){
		Recaptcha.create("6Le7ZbsSAAAAALmYyu26hMOoi_e-P8g1bqpGwbsV", 'recaptcha', {
			theme: 'clean',
			callback: Recaptcha.focus_response_field
		});
		jQuery('#request').dialog('open');
		return false;
	});
});