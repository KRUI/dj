//Podcast.js
jQuery(document).ready(function() {
jQuery(window).load(function () {
//tool tips
	jQuery('#nav_playlist,#nav_podcast').tipsy({fade: true, gravity: 'w'});
	jQuery('.pc_link').tipsy({fade: true, gravity: 'w'});
//alert message effects
	jQuery("#alert:hidden:first").slideDown(1000);
	jQuery("#alert_message:hidden:first").fadeIn(2000);
//placeholder text
	jQuery("#pc_title, #pc_link, #pc_desc, #pe_title, #pe_desc").placeholder();
//podcast create
	jQuery('button#podcast_create').button();
	jQuery('button#podcast_publish').button();
});
});