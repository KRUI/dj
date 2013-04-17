/**
 * Slide Box : a jQuery Plug-in
 * Samuel Garneau <samgarneau@gmail.com>
 * http://samuelgarneau.com
 * 
 * Released under no license, just use it where you want and when you want.
 */

(function($){
	
	$.fn.slideBox = function(params){
	
		var content = $(this).html();
		var defaults = {
			width: "100%",
			height: "200px",
			position: "bottom"			// Possible values : "top", "bottom"
		}
		
		// extending the fuction
		if(params) $.extend(defaults, params);
		
		var divPanel = $("<div class='slide-panel'>");
		var divContent = $("<div class='content'>");
	
		$(divContent).html(content);
		$(divPanel).addClass(defaults.position);
		$(divPanel).css("width", defaults.width);
		
		// centering the slide panel
		$(divPanel).css("left", (100 - parseInt(defaults.width))/2 + "%");
	
		// if position is top we're adding 
		if(defaults.position == "top")
			$(divPanel).append($(divContent));
		
		// adding buttons
		$(divPanel).append("<div class='slide-button'>Ouvrir</div>");
		$(divPanel).append("<div style='display: none' id='close-button' class='slide-button'>Fermer</div>");
		
		if(defaults.position == "bottom")
			$(divPanel).append($(divContent));
		
		$(this).replaceWith($(divPanel));
		
		// Buttons action
		$(".slide-button").click(function(){
			if($(this).attr("id") == "close-button")
				$(divContent).animate({height: "0px"}, 1000);
			else
				$(divContent).animate({height: defaults.height}, 1000);
			
			$(".slide-button").toggle();
		});
	};
	
})(jQuery);