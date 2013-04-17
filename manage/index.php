<?php
//Manage
session_start();
date_default_timezone_set('America/Chicago');
require_once('../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
if(!isset($_SESSION['username'])){
header("location:../"); // Re-direct to login
}
else {
	$username = $_SESSION['username'];
	$user = mysql_fetch_assoc(mysql_query("SELECT uid,admin,status,username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($username)."'"));
	$globalSettings = mysql_fetch_assoc(mysql_query("SELECT * FROM global_settings"));
	if ($user['admin'] != '1' && $user['status'] != 'active'){
		header("location:../"); // Re-direct to login
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../assets/css/reset.css" />
<link rel="stylesheet" type="text/css" href="../assets/js/jquery-ui-aristo/css/aristo/jquery-ui-1.8rc3.custom.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/manage.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/js.css" />
<!--javascript-->
<script type="text/javascript" src="../assets/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="../assets/js/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="../assets/js/jquery.placeholder.js"></script>
<title>KRUI DJ - Management</title>	
</head>
<body>
<div id="wrapper">
	<div id="nav_menu">
		<ul id="nav_items">
			<li class="nav_link" title="Change application wide settings"><a href="../manage/">Application</a></li>
			<li class="nav_link" title="Add, archive, delete, and manage users/groups"><a href="users/">Users and Groups</a></li>
			<li class="nav_link" title="View, edit, and create reports"><a href="reports/">Reports</a></li>
		</ul>
	    <div id="nav_right"><a href="../playlist/">Return to application</a> | <a id="nav_settings" href="" title="Settings">settings</a> | <a href="../session/destroy/" title="Logout of KRUI DJ">logout</a></div>
	</div>
	<div id="content_container">
		<div id="content">
			<div id="content_view">
				<ul>
					<li><a href="#content_view_1">Global</a></li>
					<li><a href="#content_view_2">Playlist</a></li>
					<li><a href="#content_view_3">Podcast</a></li>
				</ul>
				<div id="content_view_1" class="content_view_content">
					<h3>Overview</h3><br/>
					<ul id="app_overview">
						<li><strong>Application version:</strong></li>
						<li>
						    <strong>Version comments:</strong>
						</li>
					</ul><br/>
					<h3>Global application settings</h3><br/>
					<ul id="app_settings">
						<li title="Root domain that contains application assets (somedomain.com)" class="app_settings_item">Application domain:<input type="text" class="text_input" id="global_domain" value="<?php echo $globalSettings['root_domain'] ?>"/></li>
						<li title="URL that users use to access the application (http://somedomain.com/)" class="app_settings_item">Application URL:<input type="text" class="text_input" id="global_url" value="<?php echo $globalSettings['root_url'] ?>"/></li>
						<li title="Modifies the alert message that all users see upon login" class="app_settings_item">Global alert message:<input type="text" class="text_input" id="global_message" value="<?php echo $globalSettings['alert_message'] ?>"/></li>
						<li title="Email address that recieves access requests and other alerts" class="app_settings_item">Global administrator email:<input type="text" class="text_input" id="global_email" value="<?php echo $globalSettings['global_admin_email'] ?>"/></li>
						<li><h3>External service settings</h3><br/></li>
						<li title="Twitter &#64;Anywhere/consumer key used for built in Twitter functions" class="app_settings_item">Twitter &#64;Anywhere/consumer key:<input type="text" class="text_input" id="global_twitter_consumer" value="<?php echo $globalSettings['twitter_consumer_key'] ?>"></li>
						<li title="Twitter consumer secret used in conjunction with consumer key for OAuth 1.0a authentication" class="app_settings_item">Twitter consumer secret:<input type="text" class="text_input" id="global_twitter_secret" value="<?php echo $globalSettings['twitter_consumer_secret'] ?>"></li>
						<li title="Bit.ly API username used for built in custom URL shortner" class="app_settings_item">Bit.ly API username:<input type="text" class="text_input" id="global_bitly_username" value="<?php echo $globalSettings['bitly_api_username'] ?>"></li>
						<li title="Bit.ly API key used in conjunction with username" class="app_settings_item">Bit.ly API key:<input type="text" class="text_input" id="global_bitly_key" value="<?php echo $globalSettings['bitly_api_key'] ?>"></li>
					</ul>
					<br/><a id="app_settings_button">Save</a>
				</div>
				<div id="content_view_2" class="content_view_content"></div>
				<div id="content_view_3" class="content_view_content"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="../assets/js/manage.js"></script>
</body>
</html>
