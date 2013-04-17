<?php
//Users function
session_start();
date_default_timezone_set('America/Chicago');
$time = time();
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
if(!isset($_SESSION['username'])){
header("location:../../"); // Re-direct to login
}
else {
	$username = $_SESSION['username'];
	$user = mysql_fetch_assoc(mysql_query("SELECT uid,admin,status,username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($username)."'"));
	$globalSettings = mysql_fetch_assoc(mysql_query("SELECT * FROM global_settings"));
	if ($user['admin'] != '1' && $user['status'] != 'active'){
		header("location:../../"); // Re-direct to login
	}
}
$domain = $_POST['domain'];
$url = $_POST['url'];
$message = $_POST['message'];
$email = $_POST['email'];
$twitter_consumer = $_POST['twitter_consumer'];
$twitter_secret = $_POST['twitter_secret'];
$bitly_username = $_POST['bitly_username'];
$bitly_key = $_POST['bitly_key'];
if (isset($_SESSION['SFEAuth'])) {
	if ($_POST['action'] == 'update'){
	    if ($domain != $globalSettings['root_domain']) {
	        mysql_query("UPDATE global_settings SET root_domain = '".mysql_real_escape_string($domain)."'");
	    }
	    if ($url != $globalSettings['root_url']) {
	        mysql_query("UPDATE global_settings SET root_url = '".mysql_real_escape_string($url)."'");
	    }
	    if ($message != $globalSettings['alert_message']) {
	        mysql_query("UPDATE global_settings SET alert_message = '".mysql_real_escape_string($message)."'");
	    }
	    if ($email != $globalSettings['global_admin_email']) {
	        mysql_query("UPDATE global_settings SET global_admin_email = '".mysql_real_escape_string($email)."'");
	    }
	    if ($twitter_consumer != $globalSettings['twitter_consumer_key']) {
	        mysql_query("UPDATE global_settings SET twitter_consumer_key = '".mysql_real_escape_string($twitter_consumer)."'");
	    }
	    if ($twitter_secret != $globalSettings['twitter_consumer_secret']) {
	        mysql_query("UPDATE global_settings SET twitter_consumer_secret = '".mysql_real_escape_string($twitter_secret)."'");
	    }
	    if ($bitly_username != $globalSettings['bitly_api_username']) {
	        mysql_query("UPDATE global_settings SET bitly_api_username = '".mysql_real_escape_string($bitly_username)."'");
	    }
	    if ($bitly_key != $globalSettings['bitly_api_key']) {
	        mysql_query("UPDATE global_settings SET bitly_api_key = '".mysql_real_escape_string($bitly_key)."'");
	    }
	    header("Content-Type: application/json");
		header("Cache-Control: no-store");
		echo ('{"status":"Updated global application settings."}');
		exit();
	}
	else {
		header("HTTP/1.1 400 Bad Request");
		header("Content-Type: application/json");
		header("Cache-Control: no-store");
		echo ('{"error":"Error: Global application update failed."}');
	}
}
?>