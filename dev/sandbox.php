<?php
session_start();
date_default_timezone_set('America/Chicago');
require_once('../api/SFEAuth.php');
//Test SFEAuth
$SFEAuth = new SFEAuth();
//$SFEAuth->authServer(time(),$_GET['type'],$_GET['client_id'],$_GET['client_secret'],$_GET['redirect_uri'],$_GET['state'],$_GET['scope']);
//$SFEAuth->unitTest(time(),"hello",$_GET['scope']);
$client_name = 'localhost';
$client_id = hash_hmac('sha1',$client_name,mt_rand());
$client_secret = $SFEAuth->unitTest(time(),$client_id,'internal');
$redirect_uri = 'weather.php?city=52242';
echo('<pre>');
echo ('client ID: '.$client_id.'<br/>');
echo ('API key: '.$client_secret.'<br/>');
echo ('Request URI: '.$_SERVER['REQUEST_URI'].'<br/>');
print_r($_SESSION);
echo('</pre>');
?>

