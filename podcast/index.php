<?php
//Podcast
session_start();
date_default_timezone_set('America/Chicago');
if(!isset($_SESSION['username'])){
header("location:../"); // Re-direct to index.php
}
$username = $_SESSION['username'];
// Connect database.
require_once('../lib/db.php');
	$db = new Database();
	$db->connect();
//Fetch real name
$userParam = "SELECT uid,firstname,lastname,admin FROM users WHERE username='" . mysql_real_escape_string($username) . "'";
$userQuery = mysql_query($userParam);
$user = mysql_fetch_array($userQuery);
$user_info = ($_SESSION['user_info'] = $user);
//Fetch user alert message
$alertQuery = mysql_query("SELECT alert_message,root_url FROM global_settings LIMIT 1");
$alertMessage = mysql_fetch_assoc($alertQuery);
//Stored user settings
$storedSettingsQuery = mysql_query("SELECT profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE username='" . mysql_real_escape_string($username) . "'");
$storedSettings = mysql_fetch_assoc($storedSettingsQuery);
//Fetch existing podcast shows
$showParam = "SELECT show_id,title,url,description FROM podcast_shows WHERE uid='" . mysql_real_escape_string($user_info[uid]) . "'";
$showQuery = mysql_query($showParam);
$result = 0;
while ($showResult = mysql_fetch_array($showQuery)) {
	$show['show_id'][$result] = $showResult['show_id'];
	$show['title'][$result] = $showResult['title'];
	$show['url'][$result] = $showResult['url'];
	$show['description'][$result] = $showResult['description'];
	$result++;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../assets/css/reset.css" />
<link rel="stylesheet" type="text/css" href="../assets/js/jquery-ui-aristo/css/aristo/jquery-ui-1.8rc3.custom.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/main.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/js.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/iconic.css" />
<!--javascript-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>-->
<script type="text/javascript" src="../assets/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="../assets/js/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="../assets/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="../assets/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<title>KRUI DJ - Podcast</title>
</head>
<body>
<div id="wrapper">
  <div id="main_menu">
    <!--logo-->
  </div>
  <!--navigation-->
  <div id="nav_menu">
	<ul id="nav_items">
		<li class="nav_link" title="Log songs played on air"><a href="../playlist">Playlist</a></li>
		<li class="nav_link" title="Create/update a podcast"><a href="../podcast">Podcast</a></li>
	</ul>
	<div id="nav_right"><?php if ($user_info['admin'] == '1'){ ?><a href="../manage/" title="Manage application, users, and reports">manage</a> | <?php } ?><a id="nav_settings" href="" title="Edit your settings">settings</a> | <a href="../session/destroy/" title="Logout of KRUI DJ">logout</a></div>
  </div>
  <!--user alert message-->
  <div id="alert">
    <div id="alert_message"><span class="iconic lightbulb" style="font-size:16px; font-style:none;"></span>&nbsp;&nbsp;<?php echo $alertMessage['alert_message']; ?></div>
  </div>
  <!--application-->
  <div id="app_container">
      <div id="app">
        <!--insert main app functions and content-->
        <div id="app_content">
		  <div id="user_img"><?php
				if ($storedSettings['profile_gravatar'] == '1'){
					echo ('<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($storedSettings['profile_email']))).'" alt="Your profile picture" height="75" width="75"/>');
				}
				else {
					if ($storedSettings['profile_image'] == 'default'){
						echo ('<img src="../assets/images/default.png" alt="Your profile picture" height="75" width="75"/>');
					}
					else {
						echo ('<img src="../assets/users/'.$username.'/'.$storedSettings['profile_image'].'" alt="Your profile picture" height="75" width="75"/>');
					}
				}
			?></div>
          <div id="user_info">Hello <?php echo $user_info[firstname]; ?>, got a podcast?</div>
          <div class="app_top" id="podcast">
            <ul><li id="podcast_header"><h3>Create a new podcast</h3></li></ul>
            <br />
            <form action="../lib/podcast/new.php" method="post">
            <ul>
              <li><input type="text" id="pc_title" class="text_input" name="podcast_title" placeholder="Title" /></li>
              <li><input type="text" id="pc_link" class="text_input" name="podcast_link" placeholder="Link (blog/website URL)" /></li>
              <li><input type="text" id="pc_desc" class="text_input" name="podcast_desc" placeholder="Description" /></li>
              <li><button type="submit" id="podcast_create">Create podcast</button></li>
            </ul>
            </form>
            <br />
          </div>
         <!--existing podcast-->
          <div class="app_middle" id="podcast_episode">
		  <ul><li id="podcast_episode_header"><h3>Create a new episode</h3></li></ul>
          <form action="../lib/podcast/update.php" method="post" enctype="multipart/form-data">
          	<?php
			if (count($show['show_id']) != 0){
				echo("<p>Select an existing podcast:</p>");
		  		for ($i = 0; $i < $result; $i++){
					echo stripslashes('<input type="checkbox" name="podcast_show" value="'.$show['show_id'][$i].'" />'.'<a href="'.$alertMessage['root_url'].'podcast/rss/?channel='.$show['show_id'][$i].'" class="pc_link" title="Description: '.$show['description'][$i].'">'.$show['title'][$i].'</a>');
				}
			}
			else echo("No existing podcasts: Please create a podcast before publishing an episode.");
		   	?>
          <ul>
            <li><input type="text" id="pe_title" class="text_input" name="episode_title" placeholder="Episode title" /></li>
            <li><input type="text" id="pe_desc" class="text_input" name="episode_desc" placeholder="Episode description" /></li>
           	<li><input type="file" name="file" id="pe_file" /></li>
          </ul>
            <p><button type="submit" id="podcast_publish">Publish</button></p>
			</form>
          </div>
         <!--end podcast-->
         
        </div>
        <!--end app content-->
      </div>
  </div>
	<!--inline hidden content-->
<div style="display:none">
	<!--settings-->
	<div id="settings">
		<div id="settings_header"><h3>Edit account details</h3></div>
		<div id="settings_sub">
			<ul>
				<li>
				<form method="post" action="../lib/picture.php" enctype="multipart/form-data">
				<?php
					if ($storedSettings['profile_gravatar'] == '1'){
						echo('Use <a href="http://en.gravatar.com/" target="_blank">Gravatar</a><input type="checkbox" id="settings_gravatar" name="profile_gravatar" value="1" checked="checked"/> or Upload image
							<br/><input type="file" name="file" id="picture" />');
					}
					else {
						echo('Use <a href="http://en.gravatar.com/" target="_blank">Gravatar</a><input type="checkbox" id="settings_gravatar" name="profile_gravatar" value="1" /> or Upload image
							<br/><input type="file" name="file" id="picture" />');
					}
				?>
				<p><button type="submit">Upload</button></p>
				</form>
				<br/><br/>
				</li>
				<li><input id="settings_firstname" type="text" class="text_input" name="firstname" value="<?php echo($user_info['firstname']); ?>"/></li>
				<li><input id="settings_lastname" type="text" class="text_input" name="lastname" value="<?php echo($user_info['lastname']); ?>"/></li>
				<li><input id="settings_email" type ="text" class="text_input" name="profile_email" value="<?php echo ($storedSettings['profile_email']); ?>"/></li>
				<li><input id="settings_url" type="text" class ="text_input" name="profile_url" value="<?php echo ($storedSettings['profile_url']); ?>"/></li>
				<li><input id="settings_twitter" type="text" class ="text_input" name="profile_twitter" value="<?php echo ($storedSettings['profile_twitter']); ?>"/></li>
				<li><textarea id="settings_bio" rows="3" cols="32" name="profile_bio"><?php echo stripslashes(htmlentities($storedSettings['profile_bio'])); ?></textarea></li>
			</ul>
		</div>
		<br />
		<form method="post">
			<ul>
				<li>
				Profile picture
				<br/>
				<?php
					if ($storedSettings['profile_gravatar'] == '1'){
						echo ('<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($storedSettings['profile_email']))).'" alt="Your profile picture" height="75" width="75"/>');
					}
					else {
						if ($storedSettings['profile_image'] == 'default'){
							echo ('<img src="../assets/images/default.png" alt="Your profile picture" height="75" width="75"/>');
						}
						else {
							echo ('<img src="../assets/users/'.$username.'/'.$storedSettings['profile_image'].'" alt="Your profile picture" height="75" width="75"/>');
						}
					}
				?>
				</li>
				<li>First name</li>
				<li>Last name</li>
				<li>Email</li>
				<li>URL</li>
				<li>Twitter<br/></li>
				<li>About you and/or your show</li>
			</ul>
		</form>
	</div>
	<!--end settings-->
</div>
<!--podcast.js-->
<script type="text/javascript" src="../assets/js/global.js"></script>
<script type="text/javascript" src="../assets/js/podcast.js"></script>
</body>
</html>