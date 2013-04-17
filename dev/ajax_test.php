<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>AJAX Weather Test</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function () {
	jQuery.getJSON("./lib/weather.php", "city=Iowa City IA", function(weather) {
  		jQuery('#weather').html('<p>'+weather.current.condition+'</p>'+'<p>'+weather.current.temp+'</p>');
	});
});
</script>
</head>
<body>
<h3>Weather:</h3>
<div id="weather"></div>
</body>
</html>
