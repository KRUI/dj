<?php
//Manage Users/Groups
session_start();
date_default_timezone_set('America/Chicago');
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
require_once('../../lib/time.php'); //Time
$time = new Timer();
if(!isset($_SESSION['username'])){
header("location:../"); // Re-direct to login
}
else {
	$username = $_SESSION['username'];
	$user = mysql_fetch_assoc(mysql_query("SELECT uid,admin,status,username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($username)."'"));
	$globalSettings = mysql_fetch_assoc(mysql_query("SELECT * FROM global_settings"));
	if ($user['admin'] != '1' && $user['status'] != 'active'){
		header("location:../../"); // Re-direct to login
	}
}

$usersQuery = mysql_query("SELECT uid,request,song_name,song_artist,song_album,song_rotation,created_at FROM playlist WHERE studio='main'");
$index = 0;
while($users = mysql_fetch_assoc($usersQuery)) {
    $result[$index] = $users;
    $index++;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../../assets/css/reset.css" />
<link rel="stylesheet" type="text/css" href="../../assets/js/jquery-ui-aristo/css/aristo/jquery-ui-1.8rc3.custom.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/manage.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/js.css" />
<!--javascript-->
<script type="text/javascript" src="../../assets/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.placeholder.js"></script>
<title>KRUI DJ - Management</title>	
</head>
<body>
<div id="wrapper">
	<div id="nav_menu">
		<ul id="nav_items">
			<li class="nav_link" title="Change application wide settings"><a href="../../manage/">Application</a></li>
			<li class="nav_link" title="Add, archive, delete, and manage users/groups"><a href="../users/">Users and Groups</a></li>
			<li class="nav_link" title="View, edit, and create reports"><a href="../reports/">Reports</a></li>
		</ul>
	    <div id="nav_right"><a href="../../playlist/">Return to application</a> | <a id="nav_settings" href="" title="Settings">settings</a> | <a href="../../session/destroy/" title="Logout of KRUI DJ">logout</a></div>
	</div>
	<div id="content_container">
		<div id="content">
			<div id="content_view">
				<ul>
					<li><a href="#content_view_1">89.7 FM</a></li>
					<li><a href="#content_view_2">The Lab</a></li>
				</ul>
				<div id="content_view_1" class="content_view_content">
					<div id="user_view">
					<h3>Overview</h3><br/>
					<strong>Single User:</strong><br/>
					<ul id="users_create">
						<li><input type="text" id="users_create_firstname" class="text_input" placeholder="First name"/></li>
						<li><input type="text" id="users_create_lastname" class="text_input" placeholder="Last name"/></li>
						<li><input type="text" id="users_create_username" class="text_input" placeholder="Username"/></li>
						<li><input type="text" id="users_create_password" class="text_input" placeholder="Password"/></li>
						<li><input type="text" id="users_create_email" class="text_input" placeholder="Email"/></li>
						<li><a id="user_create_button">Submit</a></li>
					</ul><br/>
					
					<h3>Seven day playlist</h3><br/>
					<ul id="users_manage">
					    <ul id="users_summary">
					        <ul id="users_summary_header">
					            <li id="users_summary_name"><strong>Song</strong></li>
    					        <li id="users_summary_email"><strong>Album</strong></li>
    					        <li id="users_summary_status"><strong>Artist</strong></li>
    					        <li id="users_summary_activity"><strong>Last played at</strong></li>
					        </ul>
					        <ul id="users_list">
					            <li class="users_list_name">
					                <?php
					                    for ($i = 0; $i < count($result); $i++) {
					                        echo ('<p>'.$result[$i]['firstname'].' '.$result[$i]['lastname'].'</p>');
					                    }
					                ?>
					            </li>
					            <li class="users_list_email">
					                <?php
					                    for ($i = 0; $i < count($result); $i++) {
					                        echo ('<p>'.$result[$i]['profile_email'].'</p>');
					                    }
					                ?>
					            </li>
					            <li class="users_list_status">
					                <?php
					                    for ($i = 0; $i < count($result); $i++) {
					                        if ($result[$i]['admin'] == 1) {
					                            echo('<p>Administrator</p>');
					                        }
					                        else if ($result[$i]['status'] == 'active' && $result[$i]['admin'] == 0) {
					                            echo('<p>Active</p>');
					                        }
					                        else if ($result[$i]['status'] == 'new' && $result[$i]['admin'] == 0) {
					                            echo('<p>New</p>');
					                        }
					                        else {
					                            echo('<p>Inactive</p>');
					                        }
					                    }
					                ?>
					            </li>
					            <li class="users_list_activity">
					                <?php
					                    for ($i = 0; $i < count($result); $i++) {
					                        //echo ('<p>'.$time->nicetime(date($result[$i]['session_start'])).'</p>');
					                        //2011-01-28 10:00
					                        if ($result[$i]['session_start'] == 0) {
					                            echo('<p>Never</p>');
					                        }
					                        else {
					                            echo('<p>'.$time->nicetime(date('Y-m-d H:m', $result[$i]['session_start'])).'</p>');					                            
					                        }
					                    }
					                ?>
					            </li>
					        </ul>
					    </ul>
					    <?php
					        
					    ?>
					</ul>
				</div>
				<div id="content_view_2" class="content_view_content"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="../../assets/js/manage.js"></script>
</body>
</html>