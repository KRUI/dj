<?php
//Users function
session_start();
date_default_timezone_set('America/Chicago');
$time = time();
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
if(!isset($_SESSION['username'])){
header("location:../../"); // Re-direct to login
}
else {
	$username = $_SESSION['username'];
	$user = mysql_fetch_assoc(mysql_query("SELECT uid,admin,status,username,firstname,lastname,profile_gravatar,profile_image,profile_email,profile_bio,profile_twitter FROM users WHERE username='".mysql_real_escape_string($username)."'"));
	$globalSettings = mysql_fetch_assoc(mysql_query("SELECT * FROM global_settings"));
	if ($user['admin'] != '1' && $user['status'] != 'active'){
		header("location:../../"); // Re-direct to login
	}
}
if ($_POST['type'] == 'single' && isset($_SESSION['SFEAuth'])) {
    /*
        Create new user
    */
	if ($_POST['action'] == 'new'){
		if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".mysql_real_escape_string($_POST['username'])."'")) == '0'){
			mysql_query("INSERT INTO users (created_at,status,username,firstname,lastname,password,password_update,profile_email) VALUES ('".$time."','".mysql_real_escape_string($_POST['status'])."','".strtolower(mysql_real_escape_string($_POST['username']))."','".mysql_real_escape_string($_POST['firstname'])."','".mysql_real_escape_string($_POST['lastname'])."','".hash('sha256',mysql_real_escape_string($_POST['password']))."','".mysql_real_escape_string($_POST['password_update'])."','".mysql_real_escape_string($_POST['email'])."')");
			mkdir('../../assets/users/'.strtolower(trim(mysql_real_escape_string($_POST['username']))), 0755);
			mkdir('../../assets/users/'.strtolower(trim(mysql_real_escape_string($_POST['username']))).'/podcast', 0755);
			header("Content-Type: application/json");
			header("Cache-Control: no-store");
			echo ('{"status":"New user successfully created and notified of new account creation."}');
			exit();
		}
		else {
			header("HTTP/1.1 400 Bad Request");
			header("Content-Type: application/json");
			header("Cache-Control: no-store");
			echo ('{"error":"Error: Account creation failed, user '.$_POST['username'].' already exists."}');
			exit();
		}
	}
	else {
		header("HTTP/1.1 400 Bad Request");
		header("Content-Type: application/json");
		header("Cache-Control: no-store");
		echo ('{"error":"Error: Account creation failed."}');
	}
} else if ($_GET['type'] == 'multiple' && isset($_SESSION['SFEAuth'])) {
    //File upload parameters
    $inputFile_name = $_FILES['file']['name'];
    $temp_name = $_FILES['file']['tmp_name'];
    $type = $_FILES['file']['type'];
    $error = $_FILES['file']['error'];
    $size = $_FILES['file']['size'];
    switch ($type) {
    	case 'text/csv':
    		$file_name = md5($inputFile_name).'.csv';
    		break;
    }
    //CSV file uploading
    if ($type == 'text/csv'){
    	if ($size < 1048576){
    		move_uploaded_file($temp_name, '../data/csv/'.$file_name);
    		//Loop through CSV file
    		$handle = fopen('../data/csv/'.$file_name, 'r');
    		while (($data = fgetcsv($handle, 0, ',')) !== FALSE) {
    		    $status = 'new';
    		    $username = strtolower(trim($data[2]));
    		    $firstname = $data[0];
    		    $lastname = $data[1];
    		    $password = hash('sha256',mysql_real_escape_string($data[3]));
    		    $password_update = 1;
    		    $profile_email = $data[4];
    		    $query = "INSERT INTO users (created_at, status, username, firstname, lastname, password, password_update, profile_email) VALUES ('" . mysql_real_escape_string(time()) . "', '" . mysql_real_escape_string($status) . "', '" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string($firstname) . "', '" . mysql_real_escape_string($lastname) . "', '" . mysql_real_escape_string($password) . "', '" . mysql_real_escape_string($password_update) . "', '" . mysql_real_escape_string($profile_email) . "')";
    		    if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".mysql_real_escape_string($username)."'")) == '0'){
    		        mysql_query($query);
    		        mkdir('../../assets/users/'.strtolower(trim(mysql_real_escape_string($username))), 0755);
        			mkdir('../../assets/users/'.strtolower(trim(mysql_real_escape_string($username))).'/podcast', 0755);
    		    }
    		}
    	}
    	else {
    		echo ("CSV size greater than 1 MB. Please choose a smaller file.");
    	}
    }
    else {
    	echo("Invalid file type. Please upload a valid CSV document.");
    	echo($type);
    }
    header('location:'.getenv("HTTP_REFERER").'');
}
?>