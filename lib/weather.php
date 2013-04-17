<?php
/*
	JSON Google Weather v1.01
	
	Description: 
	Born out of necessity, this PHP file satisfies a faster/easier way to return Google Weather XML data for AJAX applications/websites. 
	By using JSON Google Weather, developers can use the more convenient JSON format to display weather data.
	
	License:
	Originally developed by Fredrick Galoso for use with KRUI Radio (kruiradio.org)
	Copyright 2010, Fredrick Galoso, wayoutmind.com
	Dual licensed under the MIT or GPL Version 2 licenses
 
	Last Updated: 03-19-2010
*/

///Load Google Weather API information (XML)
 	// $city = "Iowa City IA"; //can explicitly define city/zipcode or
	$city = $_GET['city']; //use GET parameter "?city=" appended to this file's URL/AJAX request
	$request_url = 'http://www.google.com/ig/api?weather='.$city.'';
	$xml = simplexml_load_file($request_url) or die("Google Weather feed not loading");
//Parse current conditions XML
$current_condition = $xml->weather->current_conditions->condition[data];
$current_temp = $xml->weather->current_conditions->temp_f[data];
$current_humidity = $xml->weather->current_conditions->humidity[data];
$current_wind = $xml->weather->current_conditions->wind_condition[data];
$current_icon = explode("/ig/images/weather/", $xml->weather->current_conditions->icon[data]);
///Begin JSON
header("Content-Type: application/json");
echo "{";
	//Return current conditions in JSON format
echo ('"current" : {
	"condition":"'.$current_condition.'",
	"temp":"'.$current_temp.'",
	"humidity":"'.$current_humidity.'",
	"wind_condition":"'.$current_wind.'",
	"icon":"'.$current_icon[1].'"
	},');
//Parse four day outlook XML
	for ($i = 0; $i <= 3; $i++){
	$forcast_day[$i] = $xml->weather->forecast_conditions->$i->day_of_week[data];
	$forcast_condition[$i] = $xml->weather->forecast_conditions->$i->condition[data];
	$forcast_low[$i] = $xml->weather->forecast_conditions->$i->low[data];
	$forcast_high[$i] = $xml->weather->forecast_conditions->$i->high[data];
	$forcast_icon[$i] = explode("/ig/images/weather/", $xml->weather->forecast_conditions->$i->icon[data]);
	//Return four day outlook in JSON format
		echo ('"'.$i.'" : {
			"day":"'.$forcast_day[$i].'",
			"condition":"'.$forcast_condition[$i].'",
			"low":"'.$forcast_low[$i].'",
			"high":"'.$forcast_high[$i].'",
			"icon":"'.$forcast_icon[$i][1].'"
		}');
	if ($i< 3){
	echo ",";
	}
}
echo "}";
?>