<?php
//Search
require_once('db.php'); // Connect database
	$db = new Database();
	$db->connect();
$query = $_GET['q'];
$limit = $_GET['limit'];
$type = $_GET['type'];
$artist = $_GET['artist'];
$album = $_GET['album'];
if ($type == "song_artist") {
$searchParam = "SELECT DISTINCT ".mysql_real_escape_string($type)." FROM playlist WHERE ".mysql_real_escape_string($type)." LIKE '%" . mysql_real_escape_string($query) . "%' ORDER BY ".mysql_real_escape_string($type)." ASC LIMIT ".mysql_real_escape_string($limit)."";
}
else if ($type == "song_album") {
	$searchParam = "SELECT DISTINCT ".mysql_real_escape_string($type)." FROM playlist WHERE ".mysql_real_escape_string($type)." LIKE '%" . mysql_real_escape_string($query) . "%' AND song_artist='".mysql_real_escape_string($artist)."' ORDER BY ".mysql_real_escape_string($type)." ASC LIMIT ".mysql_real_escape_string($limit)."";
}
else if ($type == "song_name") {
	$searchParam = "SELECT DISTINCT ".mysql_real_escape_string($type)." FROM playlist WHERE ".mysql_real_escape_string($type)." LIKE '%" . mysql_real_escape_string($query) . "%' AND song_artist='".mysql_real_escape_string($artist)."' AND song_album='".mysql_real_escape_string($album)."' ORDER BY ".mysql_real_escape_string($type)." ASC LIMIT ".mysql_real_escape_string($limit)."";
}
else {
	echo ("Error: invalid input");
}
$searchQuery = mysql_query($searchParam);
//Return Results
$result = 0;
while ($searchResults = mysql_fetch_array($searchQuery)) {
	$searchArray[$result] = $searchResults[$type];
	$result++;
}
for ($i = 0; $i < $result; $i++){
	echo stripslashes($searchArray[$i]);
	if ($i < $result - 1){
		echo "|";
	}
}
?>