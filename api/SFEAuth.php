<?php 
/*  [OBJECTIVE]
	SFEAuth: A PHP library for Simple, Flexible, and Extensible HTTP digest and token based authentication for internal and API application access.
	Provides methods for setting up an "authorization server", "resource server", or "client".
	
	[GOALS]
	Object-oriented approach to implementation
	Full upstream compliance and compatibility with the latest OAuth 2.0 Protocol (http://tools.ietf.org/html/draft-ietf-oauth-v2-08)
	
	[TODO]
	Use header(Authorization:) for application requests
	Use http_parse_headers() prior to application responses
	
	[README]
	SFEAuth provides methods for three different authentication roles:
	
	- Authorization Server
		Responds to authentication requests and manages tokens
	
	- Resource Server
		Responds to client requests given valid authorization credentials.
	
	- Client
		Sends authentication requests to authorization server, which if authorized, gains access to resource server.
*/
session_start();
$request_time = time();

//[Obtaining End-User Authorization], define request parameters
$type = $_GET['type']; 
		/* type: The client type (user-agent or web server).
         Determines how the authorization server delivers the
         authorization response back to the client. The parameter value
         MUST be set to "web_server" or "user_agent". */
$client_id = $_GET['client_id'];
		/* client_id: REQUIRED. The client identifier. */
$client_secret = $_GET['client_secret'];
		/* client_secret: REQUIRED if the client identifier has a matching secret. */
$redirect_uri = $_GET['redirect_uri'];
		/* redirect_uri: REQUIRED, unless a redirection URI has been established between 
		the client and authorization server via other means. 
		An absolute URI to which the authorization server will redirect 
		the user-agent to when the end-user authorization step is 
		completed. The authorization server SHOULD require the client 
		to pre-register their redirection URI.  Authorization servers 
		MAY restrict the redirection URI to not include a query 
		component as defined by [RFC3986] section 3. */
$state = $_GET['state']; 
/* SFEAuth defines client request 'state' as either "new", "verified", or "destroy". 
Authorization server sends a callback 'state' that is either "pending", "verified", "destroy". */
		/* state: OPTIONAL. An opaque value used by the client to maintain state 
		between the request and callback.  The authorization server 
		includes this value when redirecting the user-agent back to the client. */
$scope = $_GET['scope']; 
/* SFEAuth defines 'scope' as either "internal" or "api". 
The 'scope' determines what implementation of resource authentication is to be used.*/
		/* scope: OPTIONAL. The scope of the access request expressed as a list 
		of space-delimited strings. The value of the "scope" parameter 
		is defined by the authorization server. If the value contains 
		multiple space-delimited strings, their order does not matter, 
		and each string adds an additional access range to the requested scope. */

class SFEAuth
{
	private $signMethod = 'hmac-sha256'; /* Define what signature hash method is to be implemented. 
	Default is 'hmac-sha256'; other options are 'md5' 'sha1' 'sha256' 'hmac-md5' 'hmac-sha1' */
	private function connectDB()
	{
		$host='localhost'; // Host name.
		$db_user='kruifm_staff'; // MySQL username.
		$db_password='rt06vlditkrui897'; // MySQL password.
		$database='kruifm_staff'; // Database name.
		mysql_connect($host,$db_user,$db_password) or die('MySQL Error: '.mysql_error());
		mysql_select_db($database);
	}
// SFEAuth/OAuth authorization server
	function authServer($request_time,$type,$client_id,$client_secret,$redirect_uri,$state,$scope)
	{
		$this->connectDB();	
	//SFEAuth internal resource authorization
		if ($type == 'web_server' && $scope == 'internal'){
			if ($state == 'new'){
				$authQuery = mysql_query("SELECT * FROM SFEAuth WHERE type='".mysql_real_escape_string($type)."' AND client_id='".mysql_real_escape_string($client_id)."' AND client_secret='".mysql_real_escape_string($client_secret)."' AND redirect_uri='".mysql_real_escape_string($redirect_uri)."' LIMIT 1");
				if (mysql_num_rows($authQuery)!='0'){
					$authData = mysql_fetch_assoc($authQuery);
					$access_token = $this->authNewToken($request_time,$client_secret,$scope);
					$refresh_token = $this->authNewToken($request_time,$auth_token,$scope);
					$_SESSION['SFEAuth'][$client_id]['access_token'] = $access_token;
					$_SESSION['SFEAuth'][$client_id]['expires_in'] = $authData['expires_in'];
					$_SESSION['SFEAuth'][$client_id]['refresh_token'] = $refresh_token;
					mysql_query("UPDATE SFEAuth SET request_time='".mysql_real_escape_string($request_time)."',state='verified',access_token='".mysql_real_escape_string($access_token)."',refresh_token='".mysql_real_escape_string($refresh_token)."' WHERE client_id='".mysql_real_escape_string($client_id)."' ");
					if (preg_match('/\?/',$redirect_uri)){ 
						$redirect_url = $_SERVER['SERVER_NAME'].$redirect_uri.'&type='.urlencode($type).'&client_id='.urlencode($client_id).'&state=verified'.'&scope=internal';
					}
					else {
						$redirect_url = $_SERVER['SERVER_NAME'].$redirect_uri.'?type='.urlencode($type).'&client_id='.urlencode($client_id).'&state=verified'.'&scope=internal';
					}
					//header("Location: http://$redirect_url");
				}
				else {
					header("HTTP/1.1 400 Bad Request");
					header("Content-Type: application/json");
					header("Cache-Control: no-store");
					echo('{ "error":"incorrect_client_credentials" }');
					exit();
				}
			}
			else if ($state == 'verified'){
				
			}
			else {
				
			}
		}
		//OAuth 2.0
		else if ($scope == 'api'){
		
		}
		else {
			header("HTTP/1.1 401 Unauthorized");
			header("Content-Type: application/json");
			header("Cache-Control: no-store");
			echo('{ "error":"unauthorized_client" }');
			exit();
		}
	}
// SFEAuth/OAuth resource server
	function resourceServer($type, $client_id, $client_secret, $state, $scope)
	{
		if ($type == 'web_server' && $state == 'verified' && $scope == 'internal'){
			
		}
		else {
			
		}
	}
// SFEAuth/OAuth client
	function client()
	{
		
	}
	private function generateToken($request_time,$client_secret)
	{
		switch($this->signMethod) {
			case 'md5':
				return hash('md5',$client_secret.hash_hmac('md5',$request_time,mt_rand()));
				break;
			case 'sha1':
				return hash('sha1',$client_secret.hash_hmac('md5',$request_time,mt_rand()));
				break;
			case 'sha256':
				return hash('sha256',$client_secret.hash_hmac('md5',$request_time,mt_rand()));
				break;
			case 'hmac-md5':
				return hash_hmac('md5',$client_secret,hash_hmac('md5',$request_time,mt_rand()));
				break;
			case 'hmac-sha1':
				return hash_hmac('sha1',$client_secret,hash_hmac('md5',$request_time,mt_rand()));
				break;
			default:
				return hash_hmac('sha256',$client_secret,hash_hmac('md5',$request_time,mt_rand()));
				break;
		}
	}
// Authorization server dependency methods
	private function authNewToken($request_time,$client_secret,$scope)
	{
		if ($scope == 'internal'){
			return $this->generateToken($request_time,$client_secret);
		}
		else {
			
		}
	}
	private function authRefreshToken($request_time,$client_secret,$scope) 
	{
		
	}
	private function authDestroyToken($scope)
	{
		
	}
	// resource dependency methods
	
	// client dependency methods
	
	function helloWorld()
	{
		echo('<pre>');
		echo('Hello world! This is SFEAuth.');
		echo('</pre>');
	}
	function unitTest($request_time,$client_secret,$scope)
	{
		return $this->authNewToken($request_time,$client_secret,$scope);
	}
}
?>