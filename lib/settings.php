<?php
//Settings
session_start();
date_default_timezone_set('America/Chicago');
require_once('db.php'); // Connect database.
	$db = new Database();
	$db->connect();
$username = $_SESSION['username'];
$url = mysql_fetch_assoc(mysql_query("SELECT root_url FROM global_settings LIMIT 1")); //$url['root_url']
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$profile_gravatar = $_POST['profile_gravatar'];
$profile_email = $_POST['profile_email'];
$profile_url = $_POST['profile_url'];
$profile_twitter = $_POST['profile_twitter'];
$profile_bio = $_POST['profile_bio'];
//Fetch (any) existing values
$storedSettingsQuery = mysql_query("SELECT profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($username)."'");
$storedSettings = mysql_fetch_assoc($storedSettingsQuery);
if ($firstname != $_SESSION['firstname']) {
	mysql_query("UPDATE users SET firstname = '".mysql_real_escape_string($firstname)."' WHERE username='".mysql_real_escape_string($username)."'");
	$_SESSION['user_info']['firstname'] = $firstname;	
}
if ($lastname != $_SESSION['lastname']) {
	mysql_query("UPDATE users SET lastname = '".mysql_real_escape_string($lastname)."' WHERE username='".mysql_real_escape_string($username)."'");
	$_SESSION['user_info']['lastname'] = $lastname;
}
if ($profile_gravatar != $storedSettings['profile_gravatar']){
	mysql_query("UPDATE users SET profile_gravatar = '".mysql_real_escape_string($profile_gravatar)."' WHERE username='".mysql_real_escape_string($username)."'");
}
if ($profile_email != $storedSettings['profile_email']) {
	mysql_query("UPDATE users SET profile_email = '".mysql_real_escape_string($profile_email)."' WHERE username='".mysql_real_escape_string($username)."'");
}
if ($profile_url != $storedSettings['profile_url']) {
	mysql_query("UPDATE users SET profile_url = '".mysql_real_escape_string($profile_url)."' WHERE username='".mysql_real_escape_string($username)."'");
}
if ($profile_twitter != $storedSettings['profile_twitter']) {
	mysql_query("UPDATE users SET profile_twitter = '".mysql_real_escape_string($profile_twitter)."' WHERE username='".mysql_real_escape_string($username)."'");
}
if ($profile_bio != $storedSettings['profile_bio']) {
	mysql_query("UPDATE users SET profile_bio = '".mysql_real_escape_string($profile_bio)."' WHERE username='".mysql_real_escape_string($username)."'");
}
header("Content-Type: application/json");
header("Cache-Control: no-store");
echo ('{"message":"User settings successfully updated."}');
exit();
?>
