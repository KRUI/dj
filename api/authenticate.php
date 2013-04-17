<?php 
/*  [OBJECTIVE]
	SFEAuth: A mechanism for simple, flexible, and extensible HTTP digest and token based authentication for internal and API application access.
	
	[GOALS]
	Object-oriented approach to implementation
	Use header(Authorization:) for application requests
	Use http_parse_headers() prior to application responses
	
	[TODO]
	Nonce generation per unique request
	SQL query for token generation not working?
	Look into OAuth 2.0 compatibility?
*/
//KRUI-DJ API Authentication/XSRF/XSS security layer
session_start();
date_default_timezone_set('America/Chicago');
require_once('../lib/db.php'); // Connect to application database.
	$db = new Database();
	$db->connect();
$request_time = time();
$username = $_SESSION['user'];
class SFEAuth
{
	function requestToken($request_type, $request_time, $username)
	{
	//Request types can be one of two catagories, internal requests (0) or API requests (1)
		if ($request_type == '0'){ 
			$userAuthQuery = mysql_query("SELECT session_start,password FROM users WHERE username='" . mysql_real_escape_string($username) . "' LIMIT 1");
			$userAuth = mysql_fetch_assoc($userAuthQuery);
			echo('<pre>');
			print_r($userAuth);
			echo('<br/>');
			print_r($_SESSION);
			echo('<br/>');
			print_r('API request time: '.$request_time);
			echo('<br/>');
			print_r('username: '.$username);
			echo('</pre>');
		}
	}
	function newToken()
	{
		
	}
	function renewToken() 
	{
		
	}
	function destroyToken()
	{
		
	}
}
$a = new SFEAuth();
$a->requestToken(0, $request_time, $username);
?>