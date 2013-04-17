//Playlist.js
jQuery(document).ready(function() {
//alert message effects
	jQuery('#alert:hidden:first').slideDown(1000);
	jQuery('#alert_message:hidden:first').fadeIn(2000);
//placeholder text
	jQuery('#artist, #album, #name').placeholder();
//autocomplete
		var search = "../lib/search.php";
		jQuery('#artist').autocomplete(search, {
			extraParams: {type:'song_artist'},
			selectFirst: false });
		jQuery('#album').autocomplete(search, {
			extraParams: {type:'song_album', artist: function() { return jQuery("#artist").val(); }},
			selectFirst: false });
		jQuery('#name').autocomplete(search, {
			extraParams: {type:'song_name', artist: function() { return jQuery("#artist").val(); }, album: function() { return jQuery("#album").val(); }},
		selectFirst: false });
//submit song play
	jQuery('a#playlist_button').button().click(function() {
		if (jQuery('#artist').val() == 'Artist' && jQuery('#album').val() == 'Album' && jQuery('#name').val() == 'Song Name' || jQuery('#artist').val() == '' && jQuery('#album').val() == '' && jQuery('#name').val() == ''){
			alert('Please fill out the song play details completely.'); //Client side form validation
		}
		else {
			var studio = jQuery('#studio').text();
			if (jQuery('#request').is(':checked')){
				var request = "1";
			}
			else {
				var request = "0";
			}
			//Submit song play
			jQuery.post('../lib/log.php', { song_request: request, song_artist: jQuery('#artist').val(), song_album: jQuery('#album').val(), song_name: jQuery('#name').val(), song_rotation: jQuery('#rotation').val() } );
			//Reset form
			jQuery('#request').attr('checked', false);
			jQuery('#artist').val('');
			jQuery('#album').val('');
			jQuery('#name').val('');
			jQuery('#rotation').val('1');
			jQuery.getJSON('../api/playlist/'+studio+'/latest.json', function(playlist){
			if (jQuery('p.recent_artist').length != 0){
				var artistItemID = parseInt(jQuery('p.recent_artist').attr('id').substring(7));
				var albumItemID = parseInt(jQuery('p.recent_album').attr('id').substring(6));
				var songItemID = parseInt(jQuery('p.recent_song').attr('id').substring(5));
				jQuery('p.recent_artist').first().before('<p id="artist_'+(artistItemID + 1)+'" class="recent_artist" style="display:none;">&nbsp;'+playlist.song.artist+'</p>');
				jQuery('p.recent_album').first().before('<p id="album_'+(albumItemID + 1)+'" class="recent_album" style="display:none;">&nbsp;'+playlist.song.album+'</p>');
				jQuery('p.recent_song').first().before('<p id="song_'+(songItemID + 1)+'" class="recent_song" style="display:none;">&nbsp;'+playlist.song.name+'</p>');
				jQuery('p.recent_artist, p.recent_album, p.recent_song').show("fast");
			}
			else if (jQuery('p.recent_artist').length == 0){
				jQuery('li#li_artist').append('<p id="artist_1" class="recent_artist" style="display:none;">&nbsp;'+playlist.song.artist+'</p>');
				jQuery('li#li_album').append('<p id="album_1" class="recent_album" style="display:none;">&nbsp;'+playlist.song.album+'</p>');
				jQuery('li#li_name').append('<p id="song_1" class="recent_song" style="display:none;">&nbsp;'+playlist.song.name+'</p>');
				jQuery('p.recent_artist, p.recent_album, p.recent_song').show("fast");
			}
			if (artistItemID >= 20 || albumItemID >= 20 || songItemID >= 20){
				jQuery('p.recent_artist').last().fadeOut().remove();
				jQuery('p.recent_album').last().fadeOut().remove();
				jQuery('p.recent_song').last().fadeOut().remove();
			}
			});
		}
	});
//playlist view
	jQuery('#playlist_view').tabs();
//weather
	//dialog
	jQuery('#weather').dialog({
		autoOpen: false,
		title: "Weather for Iowa City, IA",
		width: 400,
		modal: true
	});
	//dialog open
	jQuery('#playlist_weather').click(function(){
		jQuery('#weather').dialog('open');
		return false;
	});
		//current
		function loadCurrentWeather() {
		jQuery.getJSON("../lib/weather.php", "city=52242", function(weather) {
  			jQuery('#weather_current').html("Currently: "+'<li>'+weather.current.condition+'</li>, '+'<li>'+weather.current.temp+"&#176;F"+'</li><br/>'+'<li>'+weather.current.wind_condition+'</li>');
			jQuery("#weather_current:hidden:first").fadeIn(1500);
			});
		}
		//four day outlook
		jQuery.getJSON("../lib/weather.php", "city=52242", function(weather) {
			for (i=0; i<=3; i++) {
			var icon = weather[i].icon;
			//TODO - Need to account for all "long name" weather conditions
			switch(weather[i].condition) {
				case "Scattered Thunderstorms":
					var condition = "Scattered<br/>Thunderstorms";
					break;
				case "Isolated Thunderstorms":
					var condition = "Isolated<br/>Thunderstorms";
					break;
				default:
					var condition = "<br/>"+weather[i].condition;
			}
			jQuery('<li id="weather_'+i+'">'+'<p>'+weather[i].day+":"+'</p> '+'<p>'+'<img src="../assets/images/weather/'+icon+'.png"'+'alt="'+icon+'" height="50" width="50" />'+'</p>'+'<p>'+condition+'</p>'+'<p>'+"High: "+weather[i].high+"&#176;F"+'</p>'+'<p>'+"Low: "+weather[i].low+"&#176;F"+'</p>'+'</li>').appendTo('#weather_outlook');
			}
			jQuery("#weather_outlook:hidden:first").fadeIn(1500);
		});
//chat
	jQuery("#chat_title").click(function () {
      jQuery("#chat_container").slideToggle("fast");
    });
	//enter key submits input
	jQuery("#user_message").keydown(function(e){
		if (e.keyCode == 13){
				var client_message = jQuery("#user_message").val();  
	    		jQuery.post("../lib/chat/post.php", {message: client_message});  
	    		jQuery("#user_message").attr("value", "");  
	    		return false;
			}    
		});
	//load chat messages
	function loadChat(){   
    jQuery.ajax({  
        url: "../lib/chat/log.html",  
        cache: false,  
        success: function(html){  
            jQuery("#chat_box").html(html); //Insert chat log into the #chatbox div     
  			jQuery("#chat_box").animate({ scrollTop: jQuery("#chat_box").attr("scrollHeight") - jQuery('#chat_box').height() });
        },  
    });  
	}
//end chat
	//call short polling AJAX
	setInterval (loadChat, 2000);
	loadCurrentWeather();
	setInterval (loadCurrentWeather, 60000);
	//fancybox parameters
	//jQuery("a.inline").fancybox({
		//'hideOnContentClick': false,
		//'scrolling': 'no'
	//});
});