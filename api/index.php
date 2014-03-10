<?php
//API v0.1
session_start();
require_once('SFEAuth.php');
	$SFEAuth = new SFEAuth();
require_once('../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
$request = explode('/',$_SERVER['REQUEST_URI']);
$api['request']['base'] = $request[2];
$api['request']['username'] = $_GET['username'];
$api['request']['uid'] = $_GET['uid'];
$api['request']['callback'] = $_GET['callback'];
$api['request']['limit'] = $_GET['limit'];
$api['request']['after'] = $_GET['after'];
//Define valid base resources
$api['valid']['resources'] = array('playlist', 'podcast', 'user');
//Define valid resource items
$api['valid']['playlist'] = array('latest', 'items');
$api['valid']['user'] = array('profile');
//Validate requested resource is valid
if (!(in_array($api['request']['base'], $api['valid']['resources']))){
	//Invalid resource, return error & terminate API request execution
	header("HTTP/1.1 400 Bad Request");
	header("Content-Type: application/json");
	header("Cache-Control: no-store");
	echo '{"error":"invalid resource"}';
	exit();
}
//Store request item, parse, validate, and store return format
if (preg_match('/.json.+|.json/', $request[4])){
	$api['request']['item'] = preg_replace('/.json.+|.json/', '', $request[4]);
	$api['request']['format'] = 'json';
}
else if (preg_match('/.xml.+|.xml/', $request[4])){
	$api['request']['item'] = preg_replace('/.xml.+|.xml/', '', $request[4]);
	$api['request']['format'] = 'xml';
}
else if (preg_match('/.rss.+|.rss/', $request[4])){
	$api['request']['item'] = preg_replace('/.rss.+|.rss/', '', $request[4]);
	$api['request']['format'] = 'rss';
}
else {
	header("HTTP/1.1 400 Bad Request");
	header("Content-Type: application/json");
	header("Cache-Control: no-store");
	echo '{"error":"invalid return format"}';
	exit();
}
//Return data
switch ($api['request']['base']) {
	case 'playlist':
		$studio = $request[3];
		switch ($api['request']['item']) {
			case 'latest':
				$item = mysql_fetch_assoc(mysql_query("SELECT uid,request,song_name,song_artist,song_album,song_rotation,created_at FROM playlist WHERE studio='" . mysql_real_escape_string($studio) . "' ORDER BY created_at DESC LIMIT 1"));
				if ($item['request'] == '1') {$item['request_value'] = 'true';}
				else {$item['request_value'] = 'false';}
				$item['user'] = mysql_fetch_assoc(mysql_query("SELECT username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE uid='".$item['uid']."' LIMIT 1"));
				if ($item['user']['profile_gravatar'] == '1') {$item['user']['image'] = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($item['user']['profile_email'])));}
				else if ($item['user']['profile_image'] != 'default') {$item['user']['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/assets/users/'.$item['user']['username'].'/'.$item['user']['profile_image'];}
				else {$item['user']['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/default.png';}
				switch ($api['request']['format']) {
					case 'json':
						header("Content-Type: application/json");
						header("Access-Control-Allow-Origin: *");
						header("Cache-Control: no-store");
						$json = '{';
						$json .= '"song":{"name":"'.stripslashes(htmlentities($item['song_name'])).'","artist":"'.stripslashes(htmlentities($item['song_artist'])).'","album":"'.stripslashes(htmlentities($item['song_album'])).'","request":"'.$item['request_value'].'","time":"'.gmdate('Y-m-d\TH:i:s\Z',$item['created_at']).'"},';
						$json .= '"user":{"firstname":"'.$item['user']['firstname'].'","lastname":"'.$item['user']['lastname'].'","url":"'.$item['user']['profile_url'].'","twitter":"'.$item['user']['profile_twitter'].'","image":"'.$item['user']['image'].'"}';
						$json .= '}';
						if (isset($api['request']['callback'])) {
						    echo ($api['request']['callback'].'('.$json.');');
						} else {
						    echo ($json);
						}
						exit();
					case 'xml':
						echo('<pre>');
						print_r($item);
						echo('</pre>');
						exit();
					case 'rss':
						exit();
				}
			case 'items':
			$after = 0;
			if (isset($api['request']['after'])) {
			    $after = $api['request']['after'];
			}
			$studio = $request[3];
				if (isset($api['request']['username'])){
					$user = mysql_fetch_assoc(mysql_query("SELECT uid FROM users WHERE username='".mysql_real_escape_string($api['request']['username'])."'"));
					$itemQuery = mysql_query("SELECT uid,request,song_name,song_artist,song_album,song_rotation,created_at FROM playlist WHERE studio='".mysql_real_escape_string($studio)."' AND uid='".mysql_real_escape_string($user['uid'])."' AND created_at >= ".mysql_real_escape_string($after)." ORDER BY created_at DESC LIMIT ".mysql_real_escape_string($api['request']['limit'])."");
				}
				else {
					$itemQuery = mysql_query("SELECT uid,request,song_name,song_artist,song_album,song_rotation,created_at FROM playlist WHERE studio='".mysql_real_escape_string($studio)."' AND created_at >= ".mysql_real_escape_string($after)." ORDER BY created_at DESC LIMIT ".mysql_real_escape_string($api['request']['limit'])."");
				}
				switch ($api['request']['format']) {
					case 'json':
						header("Content-Type: application/json");
						header("Access-Control-Allow-Origin: *");
						header("Cache-Control: no-store");
						$json = '[';
						$result = 0;
						while ($itemResult = mysql_fetch_assoc($itemQuery)) {
							$item[$result]['uid'] = $itemResult['uid'];
							if ($itemResult['request'] == '1') {$item[$result]['request_value'] = 'true';}
							else {$item[$result]['request_value'] = 'false';}
							$item[$result]['song_name'] = json_encode(stripslashes($itemResult['song_name']));
							$item[$result]['song_artist'] = json_encode(stripslashes($itemResult['song_artist']));
							$item[$result]['song_album'] = json_encode(stripslashes($itemResult['song_album']));
							$item[$result]['song_rotation'] = $itemResult['song_rotation'];
							$item[$result]['created_at'] = $itemResult['created_at'];
							$item[$result]['user'] = mysql_fetch_assoc(mysql_query("SELECT username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE uid='".$item[$result]['uid']."' LIMIT 1"));
							if ($item[$result]['user']['profile_gravatar'] == '1') {$item[$result]['user']['image'] = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($item['user']['profile_email'])));}
							else if ($item[$result]['user']['profile_image'] != 'default') {$item[$result]['user']['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/assets/users/'.$item[$result]['user']['username'].'/'.$item[$result]['user']['profile_image'];}
							else {$item[$result]['user']['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/default.png';}
							$json .= '[{';
							$json .= '"song":{"name":'.$item[$result]['song_name'].',"artist":'.$item[$result]['song_artist'].',"album":'.$item[$result]['song_album'].',"request":"'.$item[$result]['request_value'].'","time":"'.gmdate('Y-m-d\TH:i:s\Z',$item[$result]['created_at']).'"},';
							$json .= '"user":{"firstname":'.json_encode($item[$result]['user']['firstname']).',"lastname":'.json_encode($item[$result]['user']['lastname']).',"url":"'.$item[$result]['user']['profile_url'].'","twitter":"'.$item[$result]['user']['profile_twitter'].'","image":"'.$item[$result]['user']['image'].'"}';
							$json .= '}]';
							if ($result < mysql_num_rows($itemQuery) - 1){
								$json .= ',';
							}
							$result++;
						}
						$json .= ']';
						if (isset($api['request']['callback'])) {
						    echo ($api['request']['callback'].'('.$json.');');
						} else {
						    echo ($json);
						}
						exit();
					case 'xml':
						exit();
					case 'rss':
						exit();
				}
			
		}
		break;
	case 'podcast':
		break;
	case 'user':
		switch ($api['request']['item']) {
			case 'profile':
				if (isset($api['request']['uid'])){
					$user = mysql_fetch_assoc(mysql_query("SELECT username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE uid='".mysql_real_escape_string($api['request']['uid'])."' LIMIT 1"));
				}
				else if (isset($api['request']['username'])){
				    $user = mysql_fetch_assoc(mysql_query("SELECT username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($api['request']['username'])."' LIMIT 1"));
				}
				switch ($api['request']['format']) {
					case 'json':
						header("Content-Type: application/json");
						header("Cache-Control: no-store");
						echo('{');
						echo(json_encode($user));
						echo('}');
					exit();
					case 'xml':
					exit();
					case 'rss':
					exit();
				}
			case 'search':
				switch ($api['request']['format']) {
					case 'json':
						header("Content-Type: application/json");
						header("Cache-Control: no-store");
						echo('{');
					exit();
					case 'xml':
					exit();
					case 'rss':
					exit();
				}
		}
		break;
}
