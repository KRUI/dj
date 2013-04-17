<?php
//Playlist
session_start();
date_default_timezone_set('America/Chicago');
if(!isset($_SESSION['username'])){
header("location:../"); // Re-direct to index.php
}
$username = $_SESSION['username'];
require_once('../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
//Fetch real name
$userParam = "SELECT uid,firstname,lastname,admin FROM users WHERE username='" . mysql_real_escape_string($username) . "'";
$userQuery = mysql_query($userParam);
$user = mysql_fetch_assoc($userQuery);
$user_info = ($_SESSION['user_info'] = $user);
//Fetch user alert message
$alertQuery = mysql_query("SELECT alert_message FROM global_settings LIMIT 1");
$alertMessage = mysql_fetch_array($alertQuery);
//Stored user settings
$storedSettingsQuery = mysql_query("SELECT profile_gravatar,profile_image,profile_email,profile_url,profile_bio,profile_twitter FROM users WHERE username='" . mysql_real_escape_string($username) . "'");
$storedSettings = mysql_fetch_assoc($storedSettingsQuery);
//Return recent songs
$songQuery = mysql_query("SELECT song_name,song_artist,song_album,created_at,rating,total_rating,total_ratings FROM playlist WHERE uid='".$user_info[uid]."' ORDER BY created_at DESC LIMIT 20");
$result = 0;
while ($songResult = mysql_fetch_array($songQuery)) {
	$playlist['song_artist'][$result] = $songResult['song_artist'];
	$playlist['song_album'][$result] = $songResult['song_album'];
	$playlist['song_name'][$result] = $songResult['song_name'];
	$playlist['created_at'][$result] = $songResult['created_at'];
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
<link rel="stylesheet" type="text/css" href="../assets/css/playlist.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/js.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/iconic.css" />
<!--javascript-->
<script type="text/javascript" src="../assets/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="../assets/js/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="../assets/js/jquery.placeholder.js"></script>
<!--playlist.js-->
<script type="text/javascript" src="../assets/js/global.js"></script>
<script type="text/javascript" src="../assets/js/playlist.js"></script>
<title>KRUI DJ - Playlist</title>
</head>
<body>
<div id="wrapper">
  <div id="main_menu">
    <div id="app_logo"></div>
  </div>
  <!--navigation-->
  <div id="nav_menu">
	<ul id="nav_items">
		<li class="nav_link" title="Log songs played on air"><a href="../playlist/">Playlist</a></li>
		<li class="nav_link" title="Create/update a podcast"><a href="../podcast/">Podcast</a></li>
	</ul>
    <div id="nav_right"><?php if ($user_info['admin'] == '1'){ ?><a href="../manage/" title="Manage application, users, and reports">manage</a> | <?php } ?><a id="nav_settings" href="" title="Edit your settings">settings</a> | <a href="../session/destroy/" title="Logout of KRUI DJ">logout</a></div>
  </div>
  <!--user alert message-->
  <div id="alert">
      <div id="alert_message"><span class="iconic lightbulb" style="font-size:16px; font-style:none;"></span>&nbsp;&nbsp;<?php echo(htmlentities(stripslashes($alertMessage[0]))); ?></div>
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
          <div id="user_info">Hi <?php echo $user_info['firstname']; ?>, what'cha playing?</div>
          <br />
          <div class="app_top">
            <ul><li id="playlist_header"><h3>Add a new song play</h3></li><li id="playlist_weather"><h3>Check the <a class="inline" href="#weather">weather</a></h3></li></ul>
             <strong>Song Request?</strong> <input type="checkbox" id="request" name="song_request" value="1" />
			 <strong>Rotation </strong>
			<select id="rotation" name="song_rotation">
				<option value="2">Red</option>
				<option value="3">Orange/Yellow</option>
				<option value="4">Green</option>
				<option value="5">Blue</option>
			</select>
             <ul>
                <li><input type="text" id="artist" class="text_input" name="song_artist" placeholder="Artist" /></li>
                <li><input type="text" id="album" class="text_input" name="song_album" placeholder="Album" /></li>
                <li><input type="text" id="name" class="text_input" name="song_name" placeholder="Song Name" /></li>
                <li>&nbsp;<a id="playlist_button">Submit</a></li>
             </ul>
            
         <!--inline hidden content-->
      <div style="display:none">
        <div id="weather">
            <!--weather-->
          <div id="weather_container">
            <div id="weather_data">
              <div id="weather_current"></div>
              <br />
              <div id="weather_outlook"></div>
           </div>
         </div>
         <!--end weather-->
         </div>
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
          </div>
       <!--recent playlist-->
       <div class="app_middle">
			<!--playlist view-->
       	<div id="playlist_view">
			<ul>
				<li><a href="#playlist_view_1">Recently Played</a></li>
				<li><a href="#playlist_view_2">Statistics</a></li>
				<li><a href="#playlist_view_3">Social Media</a></li>
			</ul>
			<!--tab 1-->
			<div id="playlist_view_1" class="playlist_view_content">
				<ul>
			       <li id="li_artist"><?php for ($i = 0; $i < $result; $i++) { echo stripslashes('<p id="artist_'.($result - $i).'" class="recent_artist">&nbsp;'.htmlentities($playlist['song_artist'][$i]).'</p>'); }?></li>
			       <li id="li_album"><?php for ($i = 0; $i < $result; $i++) { echo stripslashes('<p id="album_'.($result - $i).'" class="recent_album">&nbsp;'.htmlentities($playlist['song_album'][$i]).'</p>'); }?></li>
			       <li id="li_name"><?php for ($i = 0; $i < $result; $i++) { echo stripslashes('<p id="song_'.($result - $i).'" class="recent_song">&nbsp;'.htmlentities($playlist['song_name'][$i]).'</p>'); }?></li>   
			    </ul>
			</div>
			<!--tab 2-->
			<div id="playlist_view_2" class="playlist_view_content">
				<div id="stat_rotation"></div>
			</div>
			<!--tab 3-->
			<div id="playlist_view_3" class="playlist_view_content">
				
			</div>
       	</div>
       <!--end playlist view-->
     </div>
       <!--end recent playlist-->
        <!--end app content-->
      </div>
    </div>
  </div>
</div>
<div id="chat_wrapper">
<div id="chat_title"><span class="iconic chat" style="font-size: 26px; font-weight: 500;"></span><a href="#">Studio Chat</a></div>
<!--chat-->
		 <div id="chat_container">
         	<div id="chat">
            <div id="chat_box">
            <?php  
			if(file_exists("../lib/chat/log.html") && filesize("../lib/chat/log.html") > 0){  
    			$handle = fopen("../lib/chat/log.html", "r");  
    			$contents = fread($handle, filesize("../lib/chat/log.html"));  
    			fclose($handle);
    			echo $contents;
			}  
			?>
            </div>
         	<form name="message" action="">  
        		<input type="text" id="user_message" name="user_message" />
   			</form>
            </div>
         </div>
 <!--end chat-->
</div>
<!--DOM client side data store-->
<div id="data" style="display:none">
	<div id="studio"><?php echo ($_SESSION['studio']);?></div>
</div>
<!--statistics-->
<?php
require_once('../lib/statistics.php'); //Initiate statistical engine
	$stats = new Statistics();
?>
<script type='text/javascript' src='http://www.google.com/jsapi'></script>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['imagepiechart']});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Rotation');
	        data.addColumn('number', 'Count');
	        data.addRows(5);
	        data.setValue(0, 0, 'One ('+<?php $stats->playlistRotation($user['uid'], 1); ?>+')');
	        data.setValue(0, 1, <?php $stats->playlistRotation($user['uid'], 1); ?>);
	        data.setValue(1, 0, 'Red ('+<?php $stats->playlistRotation($user['uid'], 2); ?>+')');
	        data.setValue(1, 1, <?php $stats->playlistRotation($user['uid'], 2); ?>);
	        data.setValue(2, 0, 'Orange/Yellow ('+<?php $stats->playlistRotation($user['uid'], 3); ?>+')');
	        data.setValue(2, 1, <?php $stats->playlistRotation($user['uid'], 3); ?>);
	        data.setValue(3, 0, 'Green ('+<?php $stats->playlistRotation($user['uid'], 4); ?>+')');
	        data.setValue(3, 1, <?php $stats->playlistRotation($user['uid'], 4); ?>);
	        data.setValue(4, 0, 'Blue ('+<?php $stats->playlistRotation($user['uid'], 5); ?>+')');
	        data.setValue(4, 1, <?php $stats->playlistRotation($user['uid'], 5); ?>);

	        var chart = new google.visualization.ImagePieChart(document.getElementById('stat_rotation'));
	        chart.draw(data, {
				backgroundColor: '#f0f0f0',
				colors: ['660066', 'ff0000', 'ffcc33', '80c65a', '3399cc'],
				width: 430, 
				height: 240, 
				title: 'Song plays by rotation'
				});
	      }
</script>
</body>
</html>