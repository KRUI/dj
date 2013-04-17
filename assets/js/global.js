//Global.js
jQuery(window).load(function () {
//tool tips
	jQuery('li.nav_link').tipsy({fade: true, gravity: 'w'});
	jQuery('#app_content:hidden:first').fadeIn(2000);
//settings
	//dialog
	jQuery('#settings').dialog({
		autoOpen: false,
		title: "Settings",
		modal: true,
		width: 400,
		buttons: {
			'Save':function(){
				if (jQuery('input#settings_gravatar').is(':checked')){
					var gravatar = "1";
				}
				else {
					var gravatar = "0";
				}
				jQuery.ajax({
					type: 'POST',
					url: '../lib/settings.php',
					data: ({
						firstname:jQuery('input#settings_firstname').val(),
						lastname:jQuery('input#settings_lastname').val(),
						profile_gravatar: gravatar,
						profile_email:jQuery('input#settings_email').val(),
						profile_url:jQuery('input#settings_url').val(),
						profile_twitter:jQuery('input#settings_twitter').val(),
						profile_bio:jQuery('textarea#settings_bio').val()
					}),
					dataType: 'json',
					success: function(status){
						alert(status.message);
					}
				});
			}
		}
	});
	//dialog open
	jQuery('a#nav_settings').click(function(){
		jQuery('#settings').dialog('open');
		return false;
	});
});