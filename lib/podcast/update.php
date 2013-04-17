<?php
//Update podcasts/episodes
session_start();
//Ensure date/time setting
date_default_timezone_set('America/Chicago');
require_once('../db.php'); // Connect database.
	$db = new Database();
 	$db->connect();
//define variables
$username = $_SESSION['username'];
$show_id = $_POST['podcast_show'];
$episode_title = $_POST['episode_title'];
$episode_desc = $_POST['episode_desc'];
$created_at = time();
//file upload
$inputFile_name = $_FILES['file']['name'];
$file_name = md5($_FILES['file']['name']).'.mp3';
$temp_name = $_FILES['file']['tmp_name'];
$type = $_FILES['file']['type'];
$error = $_FILES['file']['error'];
$size = $_FILES['file']['size'];
$episode_link = $username.'/podcast/'.$file_name;
//SQL query
$insertParam = "INSERT INTO podcast_items (show_id, item_title, item_link, item_desc, pubDate) VALUES ('" . mysql_real_escape_string($show_id) . "', '" . mysql_real_escape_string($episode_title) . "', '" . mysql_real_escape_string($episode_link) . "', '" . mysql_real_escape_string($episode_desc) . "', '" . mysql_real_escape_string($created_at) . "')";
//SQL function
function episodeSQL($episode_link, $insertParam) {
	mysql_query($insertParam);
}
if ($error > 0){
	echo "Error: ".$error;
}
else if ($type != "audio/mp3" && $type != "audio/mpeg"){
	echo "Invalid file type: ".$type;
}
else if ($size < 10000000) {
	if (file_exists('../../assets/users/'.$username.'/podcast/'.$file_name)) {
      echo $inputFile_name." already exists. ";
    }
    else {
      move_uploaded_file($temp_name, '../../assets/users/'.$username.'/podcast/'.$file_name);
	episodeSQL ($episode_link, $insertParam);
	header("location:../../podcast");
    }
}
else {
	echo ("File size exceeds 10 megabytes.");
}
?>