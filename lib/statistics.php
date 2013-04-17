<?php
//Statistics engine for KRUI DJ
class Statistics 
{
	function __construct() {
		session_start();
		date_default_timezone_set('America/Chicago');
		require_once('db.php'); // Connect database.
			$db = new Database();
			$db->connect();
	} 
	function playlistRotation($uid, $rotation) {
		$result = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM playlist WHERE uid='".mysql_escape_string($uid)."' AND song_rotation='".mysql_escape_string($rotation)."'"));
		echo $result[0];
	}
	function artistCount($uid, $artist) {
	    /*
	        Stuff here
	    */
	}
}
?>