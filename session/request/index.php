<?php
//Request user account
session_start();
date_default_timezone_set('America/Chicago');
$time = time();
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$staff['on-air'] = $_POST['on_air'];
$staff['dept-staff'] = $_POST['dept_staff'];
$staff['semester'] = $_POST['semester'];
$staff['year'] = $_POST['year'];
require_once('../../lib/db.php'); // Connect database.
	$db = new Database();
	$db->connect();
require_once('../../lib/recaptcha.php'); // reCAPTCHA validation
$recaptcha['privateKey'] = "6Le7ZbsSAAAAABkggJer0PmItAV5hUO5uYsQ4KCo";
$r_response = recaptcha_check_answer ($recaptcha['privateKey'], $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
if (!$r_response->is_valid) {
  header("HTTP/1.1 401 Unauthorized");
  die ("The reCAPTCHA wasn't entered correctly. Please try again." . " (error: " . $r_response->error . ")");
}
//Send administrator email about account request
else {
	$notification = mysql_fetch_assoc(mysql_query("SELECT root_domain,root_url,global_admin_email FROM global_settings LIMIT 1"));
	$notification['subject'] = "Staff Account Requested: ".$firstname." ".$lastname.", ".$staff['semester']." ".$staff['year']."";
	$notification['message'] = "".$firstname." ".$lastname." has requested a KRUI Radio staff account for ".$staff['semester']." ".$staff['year'].".<br/>Please visit <a href=".'"'.$notification['root_url'].'manage/"'.">".$notification['root_domain']."/manage/</a> to respond to this account request.";
	$notification['headers'] .= $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$notification['headers'] .= 'From: no-reply@'.$notification['root_domain'].'' . "\r\n" .
	    'Reply-To: no-reply@'.$notification['root_domain'].'' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	mail("Administrator <".$notification['global_domain_email'].">", $notification['subject'], $notification['message'], $notification['headers']);
	header("Content-Type: application/json");
	header("Cache-Control: no-store");
	echo('{"status":"Success! Account access request submitted. We'."'".'ll get back to you shortly."}');
}
?>