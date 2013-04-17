<?php
session_start();
date_default_timezone_set('America/Chicago');
$username = $_SESSION['user'];
$time = time();
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
mysql_query("UPDATE users SET session_end='".mysql_real_escape_string($time)."' WHERE username='".mysql_real_escape_string($username)."'");
//Clear session and return to login page
session_destroy();
header("location:../../");
?>