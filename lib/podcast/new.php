<?php
//Create new podcast
session_start();
//Ensure date/time setting
date_default_timezone_set('America/Chicago');
// Connect database.
require_once('../db.php');
	$db = new Database();
	$db->connect();
//define variables
$uid = $_SESSION['user_info'][uid];
$podcast_title = $_POST['podcast_title'];
$podcast_link = $_POST['podcast_link'];
$podcast_desc = $_POST['podcast_desc'];
$created_at = time();
if ($_POST['podcast_title'] == "Title" || $_POST['podcast_link'] == "Link (blog/website URL)" || $_POST['podcast_desc'] == "Description") {
	echo ('<h3>Please fill out form completely!</h3>');
}
else {
//SQL
$insertParam = "INSERT INTO podcast_shows (uid, title, url, description, created_at) VALUES ('" . mysql_real_escape_string($uid) . "', '" . mysql_real_escape_string($podcast_title) . "', '" . mysql_real_escape_string($podcast_link) . "', '" . mysql_real_escape_string($podcast_desc) . "', '" . mysql_real_escape_string($created_at) . "')";
mysql_query($insertParam);
header("location:../../podcast");
}
?>