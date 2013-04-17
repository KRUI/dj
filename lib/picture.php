<?php
session_start();
date_default_timezone_set('America/Chicago');
require_once('db.php'); // Connect database.
	$db = new Database();
	$db->connect();
$username = $_SESSION['username'];
//Define vaild filetypes for profile image
$image_types = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
//File upload parameters
$inputFile_name = $_FILES['file']['name'];
$temp_name = $_FILES['file']['tmp_name'];
$type = $_FILES['file']['type'];
$error = $_FILES['file']['error'];
$size = $_FILES['file']['size'];
switch ($type) {
	case 'image/jpeg':
		$file_name = md5($username).'.jpg';
		break;
	case 'image/pjpeg':
		$file_name = md5($username).'.jpg';
		break;
	case 'image/gif':
		$file_name = md5($username).'.gif';
		break;
	case 'image/png':
		$file_name = md5($username).'.png';
		break;
}
//Profile picture uploading
if (in_array($type, $image_types)){
	if ($size < 89600){
		move_uploaded_file($temp_name, '../assets/users/'.$username.'/'.$file_name);
		mysql_query("UPDATE users SET profile_image='".mysql_real_escape_string($file_name)."' WHERE username='".mysql_real_escape_string($username)."'");
	}
	else {
		echo ("Image size greater than 700k. Please choose a smaller profile picture.");
	}
}
else {
	echo("Invalid image type. Please upload a profile picture that is either a JPG, GIF, or PNG.");
	echo($type);
}
header('location:'.getenv("HTTP_REFERER").'');
?>