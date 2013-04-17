<?php
session_start();
//Ensure date/time setting
date_default_timezone_set('America/Chicago');
// Connect database.
require_once('db.php');
	$db = new Database();
	$db->connect();
//define variables
$uid = $_SESSION['user_info']['uid'];
$studio = $_SESSION['studio'];
$song_name = $_POST['song_name'];
$song_artist = $_POST['song_artist'];
$song_album = $_POST['song_album'];
$song_rotation = $_POST['song_rotation'];
$created_at = time();
//Check if song was requested
if ($_POST['song_request'] == "1") {
	$request = "1";
}
else {
	$request = "0";
}
//SQL
$insertParam = "INSERT INTO playlist (uid, studio, request, song_name, song_artist, song_album, song_rotation, created_at) VALUES ('" . mysql_real_escape_string($uid) . "', '" . mysql_real_escape_string($studio) . "', '" . mysql_real_escape_string($request) . "', '" . mysql_real_escape_string($song_name) . "', '" . mysql_real_escape_string($song_artist) . "', '" . mysql_real_escape_string($song_album) . "', '" . mysql_real_escape_string($song_rotation) . "', '" . mysql_real_escape_string($created_at) . "')";
mysql_query($insertParam);
?>