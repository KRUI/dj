<?php
//Create new session
session_start();
date_default_timezone_set('America/Chicago');
$time = time();
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
require_once('../../api/SFEAuth.php'); //SFEAuth
	$SFEAuth = new SFEAuth();
	$SFEAuth->authServer($time,$_POST['type'],$_POST['client_id'],$_POST['client_secret'],$_POST['redirect_uri'],$_POST['state'],$_POST['scope']);
$username = $_POST['username'];
$password = hash('sha256', $_POST['password']);
$authQuery = "SELECT * FROM users WHERE username='" . mysql_real_escape_string($username) . "' AND password='" . mysql_real_escape_string($password) . "' LIMIT 1";
$result = mysql_query($authQuery);
if (mysql_num_rows($result)!='0') { // If match.
	$_SESSION['username'] = $username; // Create session username.
	$_SESSION['studio'] = $_POST['studio'];
	mysql_query(" UPDATE users SET session_start='".mysql_real_escape_string($time)."' WHERE username='".mysql_real_escape_string($username)."' ");
	header("Content-Type: application/json");
	header("Cache-Control: no-store");
	echo ('{"state":"verified", "redirect_uri":"'.$_POST['redirect_uri'].'"}');
	exit();
}
else { // If not match
	//Verify that existing user sessions are cleared
	if ( isset($_SESSION['user'])){
			unset($_SESSION['user']);
			unset($_SESSION['user_info']);
			unset($_SESSION['SFEAuth']);
	}
	//Return error
	header("HTTP/1.1 401 Unauthorized");
	header("Content-Type: application/json");
	header("Cache-Control: no-store");
	echo ('{"error":"Invalid username or password. Please try again, or request access."}');
}
?>