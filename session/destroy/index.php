<?php
//Logout+destroy session
session_start();
date_default_timezone_set('America/Chicago');
$username = $_SESSION['username'];
$time = time();
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
fopen('../../lib/chat/log.html', w); //Clear chat contents
mysql_query("UPDATE users SET session_end='".mysql_real_escape_string($time)."' WHERE username='".mysql_real_escape_string($username)."'");
//Clear session and return to login page
session_destroy();
header("location:../../");
?>